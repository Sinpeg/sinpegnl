<?php
header('Content-Type: text/html; charset=utf-8');
require_once('../../dao/PDOConnectionFactory.php');
require_once('../../modulo/acessib/dao/tpacessibilidadeDAO.php');
require_once('../../vendors/PHPExcel.php');
?>
<?php
/* grant */
require_once '../../classes/sessao.php';
session_start();
$sessao = $_SESSION["sessao"];
if (!isset($sessao)) {
 exit(0);
} else {
 $aplicacoes = $sessao->getAplicacoes();
 if (!$aplicacoes[10]) { // Acessibilidade
 exit(0);
 }
}
?>
<?php
$ano = $_POST['ano']; // ano inicial
$ano1 = $_POST['ano1']; // ano final
$tipo = $_POST['tipo']; // tipo da infraestrutura de ensino
$txtUnidade = $_POST['txtUnidade']; // unidade
?>
<?php
$dados = array();
$daoacess = new TpacessibilidadeDAO();
$row = $daoacess->buscaestruturaacess($ano, $ano1, $tipo, $txtUnidade);
$daoacess->fechar();
?>
<?php
$anos = array();
foreach ($row as $r) {
 $cat[$r["Nome"]] = $r["Nome"]; // primeiro header
 $dados[$r["NomeUnidade"]][$r["Nome"]][$r["Ano"]][] = array(
 "Quantidade" => $r["Quantidade"],
 "Ano" => $r["Ano"]
 );
 $anos[$r["Ano"]] = $r["Ano"];
}
foreach ($dados as $key1 => $dados1) {
 foreach ($dados1 as $key2 => $dados2) {
 if (count($dados[$key1][$key2]) != count($anos)) {
 foreach ($anos as $a) {
 if (!isset($dados[$key1][$key2][$a])) {
 $dados[$key1][$key2][$a][] = array(
 "Quantidade" => 'x',
 "Ano" => $a
 );
 }
 }
 }
 }
}
?>
<?php
$objPHPExcel = new PHPExcel();
$sheetIndex = $objPHPExcel->getIndex($objPHPExcel->getSheetByName('Worksheet'));
$objPHPExcel->removeSheetByIndex($sheetIndex);
$sheet = new PHPExcel_Worksheet($objPHPExcel, "Acessibilidade");
$objPHPExcel->addSheet($sheet, 0);
$objPHPExcel->setActiveSheetIndex(0);
/** Construindo o header * */
$sheet->setCellValue("A1", "Unidade");
$sheet->mergeCells("A1:A2");
$sheet->setCellValue("B1", "Categoria");
$sheet->mergeCells("B1:B2");
$sheet->setCellValue("C1", "Ano");
$sheet->mergeCellsByColumnAndRow(2, 1, 2 + (count($anos) - 1), 1);
$sheet->setCellValueByColumnAndRow(2 + (count($anos)), 1, "Total");
$sheet->mergeCellsByColumnAndRow(2 + (count($anos)), 1, 2 + (count($anos)), 2);
/** fim * */
// Colocando os anos
$cont = 0;
foreach ($anos as $a) {
 $sheet->setCellValueByColumnAndRow(2 + $cont, 2, $a);
 $cont++;
}
// fim
?>
<?php
$ccat = 0;
$cvanos = 0;
//$cont = 0;
foreach ($dados as $x => $xv) { // unidade
 $maxL = $sheet->getHighestRow();
 $sheet->setCellValueByColumnAndRow(0, $maxL + 1, $x);
 $sheet->mergeCellsByColumnAndRow(0, $maxL + 1, 0, ($maxL + 1 + count($cat)));
 $ccat = 2;
 foreach ($xv as $y => $yv) {
 $sheet->setCellValueByColumnAndRow(1, ($maxL + $ccat - 1), ($y));
 $ccat++;
 $cvanos = 0;
 $totalAno = 0;
 foreach ($yv as $z => $zv) {
 foreach ($zv as $key => $value) {
 $v = ($value["Quantidade"] == "x") ? "n??o informado" : $value["Quantidade"];
 $sheet->setCellValueByColumnAndRow($cvanos + 2, ($maxL + $ccat - 2), $v);
 $totalAno+=$value["Quantidade"];
 }
 $sheet->setCellValueByColumnAndRow($cvanos + 3, ($maxL + $ccat - 2), $totalAno);
 $cvanos++;
 }
 }
}
?>
<?php
$file = "Acessibilidade" . date('Y-m-d') . "_" . date('H:m:s');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $file . '.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>
<?php
header('Content-Type: text/html; charset=utf-8');
require '../../dao/PDOConnectionFactory.php';
require_once "../../modulo/labor/dao/laboratorioDAO.php";
require_once dirname(__FILE__) . '/../../vendors/PHPExcel.php';
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
    if (!$aplicacoes[7]) { // laboratório
        exit(0);
    }
}
?>
<?php
$ano_inicio = addslashes($_POST['ano']); // ano de inicio
$ano_fim = addslashes($_POST['ano1']); // ano final
$unidade = addslashes($_POST['txtUnidade']); // unidade
$situacao = $_POST['situacao']; // situacao
$curso = $_POST['curso']; // curso
?>
<?php
$daolab = new LaboratorioDAO();
$dados = array();
$header = array();
$rows = $daolab->buscaLabUnid($unidade, $curso, $situacao, $ano_inicio, $ano_fim);
$anoRef = ($situacao == "A") ? ("AnoAtivacao") : ("AnoDesativacao");
$header = ($curso != "curso") ? (array("Laboratório", "Área", "Ano")) : (array("Laboratório", "Curso", "Área", "Ano"));
foreach ($rows as $row)
    if ($curso == "curso") {
        $dados[$row["NomeUnidade"]][] = array(
            "Laboratorio" => ($row["Laboratorio"]),
            "Curso" => ($row["NomeCurso"]),
            "Area" => $row["Area"],
            "Ano" => $row[$anoRef]
        );
    } else {
        $dados[$row["NomeUnidade"]][] = array(
            "Laboratorio" => ($row["Laboratorio"]),
            "Area" => $row["Area"],
            "Ano" => $row[$anoRef]
        );
    }
?>
<?php
$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheetIndex = $objPHPExcel->getIndex($objPHPExcel->getSheetByName('Worksheet'));
$objPHPExcel->removeSheetByIndex($sheetIndex);
$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($objPHPExcel, "Laboratório");
$objPHPExcel->addSheet($sheet, 0);
$objPHPExcel->setActiveSheetIndex(0);
$sheet->fromArray($header, null, 'A1', false);
?>
<?php
foreach ($dados as $key => $value) {
    $linha = $sheet->getHighestRow() + 1;
    $sheet->getCellByColumnAndRow(1, $linha, true)->setValue($key);
    $sheet->mergeCellsByColumnAndRow(1, $linha, count($header) - 1, $linha);
    $sheet->getStyleByColumnAndRow(1, $linha, null, null)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_PATTERN_MEDIUMGRAY);
    foreach ($value as $value1) {
        ++$linha;
        $sheet->fromArray($value1, NULL, "A$linha", false);
    }
}
?>
<?php
$file = "Laboratorio" . date('Y-m-d') . "_" . date('H:m:s');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $file . '.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>
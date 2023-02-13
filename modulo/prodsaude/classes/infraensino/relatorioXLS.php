<?php

header('Content-Type: text/html; charset=utf-8');
require dirname(__FILE__) . '/../../dao/PDOConnectionFactory.php';
require_once(dirname(__FILE__) . '/../../modulo/infraensino/dao/tipoinfraensinoDAO.php');
require_once (dirname(__FILE__) . '/../../vendors/PHPExcel.php');
//exit(0);
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
    if (!$aplicacoes[9]) { // Acessibilidade
        exit(0);
    }
}
?>
<?php

$ano = addslashes($_POST['ano']); // ano inicial
$ano1 = addslashes($_POST['ano1']); // ano final
$txtUnidade = addslashes($_POST['txtUnidade']); // unidade selecionada
$tipo = $_POST['tipo']; // tipo da infraestrutura de ensino
?>
<?php

$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$objPHPExcel->setActiveSheetIndex(0);
$sheet = $objPHPExcel->getActiveSheet();
//
?>
<?php

$header = array("Unidade Acadêmica", "Tipo da Infraestrutura", "Quantidade", "Ano");
$sheet->fromArray($header, NULL, 'A1');
$daotie = new TipoinfraensinoDAO();
$row = $daotie->buscatipo($tipo, $ano, $ano1, $txtUnidade);
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

$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheetIndex = $objPHPExcel->getIndex($objPHPExcel->getSheetByName('Worksheet'));
$objPHPExcel->removeSheetByIndex($sheetIndex);
$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($objPHPExcel, "Acessibilidade");
$objPHPExcel->addSheet($sheet, 0);
$objPHPExcel->setActiveSheetIndex(0);

/** Construindo o header * */
$sheet->getCell("A1", true)->setValue("Unidade");
$sheet->getStyle("A1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_PATTERN_DARKGRAY);
$sheet->mergeCells("A1:A2");

$sheet->getCell("B1", true)->setValue("Categoria");
$sheet->getStyle("B1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_PATTERN_DARKGRAY);
$sheet->mergeCells("B1:B2");

$sheet->getCell("C1", true)->setValue("Ano");
$sheet->getStyle("C1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_PATTERN_DARKGRAY);
$sheet->mergeCellsByColumnAndRow(3, 1, 2 + (count($anos) - 1), 1);

$sheet->getCellByColumnAndRow(3 + (count($anos)), 1, true)->setValue("Total");
$sheet->mergeCellsByColumnAndRow(3 + (count($anos)), 1, 2 + (count($anos)), 2);
$sheet->getStyleByColumnAndRow(3 + (count($anos)), 1, null, null)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_PATTERN_DARKGRAY);
/** fim * */
// Colocando os anos
$cont = 0;
foreach ($anos as $a) {
    $sheet->getCellByColumnAndRow(3 + $cont, 2, true)->setValue($a);
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
    $sheet->getCellByColumnAndRow(1, $maxL + 1, true)->setValue($x);
    $sheet->getStyleByColumnAndRow(1, $maxL + 1, null, null)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $sheet->mergeCellsByColumnAndRow(1, $maxL + 1, 0, ($maxL + 1 + count($cat)));
    $ccat = 2;
    foreach ($xv as $y => $yv) {
        $sheet->getCellByColumnAndRow(2, ($maxL + $ccat - 1), true)->setValue(($y));
        $ccat++;
        $cvanos = 0;
        $totalAno = 0;
        foreach ($yv as $z => $zv) {
            foreach ($zv as $key => $value) {
                $v = ($value["Quantidade"] == "x") ? "não informado" : $value["Quantidade"];
                $sheet->getCellByColumnAndRow($cvanos + 3, ($maxL + $ccat - 2), true)->setValue($v);
                $totalAno+=$value["Quantidade"];
            }
            $sheet->getCellByColumnAndRow($cvanos + 4, ($maxL + $ccat - 2), true)->setValue($totalAno);
            $cvanos++;
        }
    }
}
?>
<?php

$file = "Infraensino" . date('Y-m-d') . "_" . date('H:m:s');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $file . '.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>
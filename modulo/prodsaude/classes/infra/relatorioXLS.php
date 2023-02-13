<?php
header('Content-Type: text/html; charset=utf-8');
require_once '../../dao/PDOConnectionFactory.php';
require '../../modulo/infra/dao/infraDAO.php';
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
    if (!$aplicacoes[8]) { // Infra
        exit(0);
    }
}
?>
<?php

$txtUnidade = $_POST['txtUnidade']; // código da unidade
$situacao = $_POST['situacao']; // situacao da unidade
$ano = addslashes($_POST['ano']); // ano
$ano1 = addslashes($_POST['ano1']); // ano
$tipo = addslashes($_POST['tipo']); // tipo

$daoin = new InfraDAO(); // objeto de acesso aos dados da infraestrutura
$rows = $daoin->buscaInfraAdmin($ano, $ano1, $situacao, $txtUnidade, $tipo);
$dados = array();
$anoRef = ($situacao == "A") ? ("AnoAtivacao") : ("AnoDesativacao");
foreach ($rows as $row) {
    $dados[$row['NomeUnidade']][$row['NomeTipo']][$row[$anoRef]][] = array(
        'NomeInfra' => ($row["NomeInfra"]),
        'Area' => $row["Area"],
        'HoraInicio' => $row["HoraInicio"],
        'HoraFim' => $row["HoraFim"],
        'Capacidade' => $row["Capacidade"],
        'Ano' => $row[$anoRef]
    );
}
$colunas = array(
    'Infraestrutura',
    'Área',
    'Hora de Início',
    'Hora de Fim',
    'Capacidade',
    'Ano',
);
?>
<?php
$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheetIndex = $objPHPExcel->getIndex($objPHPExcel->getSheetByName('Worksheet'));
$objPHPExcel->removeSheetByIndex($sheetIndex);
//$objPHPExcel->createSheet();
$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($objPHPExcel, "Infraestrutura");
$objPHPExcel->addSheet($sheet, 0);
$objPHPExcel->setActiveSheetIndex(0);
$sheet->fromArray($colunas, null, 'A1', false);
?>
<?php
$pColumn1 = 0;
$pRow1 = 0;
$pColumn2 = count($colunas)-1;
$pRow2 = 0;
foreach ($dados as $key => $value) {
    $nomeUnid = $key;
    $pRow1+= 2;
    $pRow2+= 2;
    // y,x,y,x  
    $sheet->mergeCellsByColumnAndRow($pColumn1 + 1, $pRow1, $pColumn2, $pRow2);
    $sheet->getCellByColumnAndRow($pColumn1 + 1, $pRow1, true)->setValue($nomeUnid);
    $sheet->getStyleByColumnAndRow($pColumn1 + 1, $pRow1, null, null)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_PATTERN_DARKGRAY);
    foreach ($value as $key1 => $value1) {
        foreach ($value1 as $value2) {
            foreach ($value2 as $value3) {
                ++$pRow1;
                ++$pRow2;
                $sheet->fromArray($value3, NULL, "A$pRow1", false);
            }
        }
    }
}
?>
<?php
$file = "Infra" . date('Y-m-d') . "_" . date('H:m:s');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $file . '.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>
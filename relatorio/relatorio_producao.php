<?php
/* Atualização do dia 18/01/2023:
	PHPExcel --> PhpSpreadsheet
*/

//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../dao/PDOConnectionFactory.php';
require_once 'dao/relatoriosDAO.php';
require_once '../../vendor/autoload.php'; //adicionado 18/01/2023

//Recupera os parâmetros enviados via GET
$ano_base = $_GET['anoBase'];

$cod_unidade = $_GET['codUnidade'];

//Instancia a classe
$relatorios = new RelatoriosDAO();

if($cod_unidade == 100000){
	$rows = $relatorios->producaoAdm($ano_base);
}else{
	$rows = $relatorios->producaoUni($ano_base, $cod_unidade);
}

// Create new PHPExcel object
$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

//Define alinhamento 	
$sheet = $objPHPExcel->getActiveSheet();

$sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

//Aumentar largura da da coluna
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(17);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(60);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(12);

//Títulos de Colunas
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'TIPO DE PRODUÇÃO')
->setCellValue('B1', 'PRODUÇÃO')
->setCellValue('C1', 'QUANTIDADE');

//Define background dos títulos do cabeçalho
$objPHPExcel->getActiveSheet()
->getStyle('A1:C1')
->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()
->setRGB('CDCDCD');

// Define o nome da folha
$objPHPExcel->getActiveSheet()->setTitle('Resultados');

// Seta a primeira folha
$objPHPExcel->setActiveSheetIndex(0);

//Define a linha em que começa os dados
$linha = 2;

//Conteúdo do Arquivo
foreach ($rows as $row){
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A'.$linha, $row['tipo'])
	->setCellValue('B'.$linha, $row['Nome'])
	->setCellValue('C'.$linha, $row['quant']);
	
	$linha++;//Avança para próxima linha
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ProducaoIntelectual.xls"');
header('Cache-Control: max-age=0');

// Redirect output to a client’s web browser (Excel5)
//header('Content-Type: application/vnd.ms-excel');
//header('Content-Disposition: attachment;filename="ProducaoIntelectual.xls"');
//header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls'); //alterado 18/01/2023
$objWriter->save('php://output');
exit;

?>
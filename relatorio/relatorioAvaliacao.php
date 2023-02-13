<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../classes/sessao.php';
require_once '../dao/PDOConnectionFactory.php';
require_once 'dao/relatoriosDAO.php';
require_once '../../vendor/autoload.php';
require_once '../classes/relatorioxls2.php';

//Recupera os parâmetros enviados via GET
$ano_base = $_GET['anoBase'];

$avaliacaoDAO = new RelatoriosDAO();
$rows = $avaliacaoDAO->avaliacaoPlano($ano_base);

/* Cria o objeto para exportar os dados em excel */
$objPHPExcel = new RelatorioXLS(); // objeto do excel

//Títulos de Colunas
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ANO BASE: '.$ano_base);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:C1');
$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($objPHPExcel->getStyle());

// Add some data
//Títulos de Colunas
$linha =2;
// Tópicos
$titulo = array('UNIDADE', 'REALIZOU RAT ?','RELATO SOBRE PONTOS DISCUTIDOS NA RAT');
$ascii = 65;
foreach ($titulo as $t) {	
	$objPHPExcel->getActiveSheet()->setCellValue(chr($ascii) . "2", $t);
	$objPHPExcel->getActiveSheet()->getColumnDimension(chr($ascii))->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getStyle(chr($ascii) . "2")->applyFromArray($objPHPExcel->getStyle1());
	$ascii++;
}

$objPHPExcel->getActiveSheet()
->getStyle('A1:C1')
->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()
->setRGB('CDCDCD');

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Relatório de Avaliação');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

//Início do corpo da tabela
foreach($rows as $row){
	$linha++;
	$houveRAT = ($row['RAT']==1)?"SIM":"NÃO";
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A'.$linha, $row['NomeUnidade'])
	->setCellValue('B'.$linha, $houveRAT)
	->setCellValue('C'.$linha, $row['avaliacao']);
	$objPHPExcel->getActiveSheet()->getStyle("A$linha:C$linha")->applyFromArray($objPHPExcel->getStyle2());}

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Relatorio_Avaliacao.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
$objWriter->save('php://output');
exit;
?>
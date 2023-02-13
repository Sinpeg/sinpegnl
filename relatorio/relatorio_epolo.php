<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../dao/PDOConnectionFactory.php';
require_once 'dao/relatoriosDAO.php';
require_once '../../vendor/autoload.php';
require_once '../classes/relatorioxls2.php';

//Recupera os parâmetros enviados via GET
$ano_base = $_GET['anoBase'];

//Instancia a classe
$relatorios = new RelatoriosDAO();
$rows = $relatorios->estruraPolos($ano_base);

/* Cria o objeto para exportar os dados em excel */
$objPHPExcel = new RelatorioXLS(); // objeto do excel

//Títulos de Colunas
$linha =1;

// Tópicos
$titulo = array('UNIDADE', 'POSSUI CONEXÃO À BANDA LARGA?','POSSUI SALA DE COORDENAÇÃO?', 'POSSUI EQUIPAMENTOS PARA VIDEOCONFERÊNCIA?', 'POSSUI SALA EQUIPADA PARA O ATENDIMENTO PELOS TUTORES?', 'POSSUI MICROCOMPUTADORES?');
$ascii = 65;
foreach ($titulo as $t) {
	$objPHPExcel->getActiveSheet()->setCellValue(chr($ascii) . "1", $t);
	$objPHPExcel->getActiveSheet()->getColumnDimension(chr($ascii))->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getStyle(chr($ascii) . "1")->applyFromArray($objPHPExcel->getStyle1());
	$ascii++;
}

$objPHPExcel->getActiveSheet()
->getStyle('A1:F1')
->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()
->setRGB('CDCDCD');

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Relatorio de tecnologias');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$linha = 2;

//Conteúdo 
foreach ($rows as $row){	
	
    $objPHPExcel->getActiveSheet()->getStyle("A$linha")->applyFromArray($objPHPExcel->getStyle2());
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$linha, $row['NomeUnidade']);
	
	if ($row['bandaLarga']==1) {
	    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$linha, "SIM");;
	}else{
	    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$linha, "NÃO");;
	}
	if ($row['coordenacao']==1) {
	    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$linha, "SIM");;
	}else{
	    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$linha, "NÃO");;
	}
	if ($row['videoConf']==1) {
	    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$linha, "SIM");;
	}else{
	    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$linha, "NÃO");;
	}
	if ($row['salaTutor']==1) {
	    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$linha, "SIM");;
	}else{
	    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$linha, "NÃO");;
	}
	if ($row['micros']==1) {
	    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$linha, "SIM");;
	}else{
	    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$linha, "NÃO");;
	}
	
	$linha++;
} 

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Relatório de cadastramento.xls"');
header('Cache-Control: max-age=0');
// If you're serving to  IE 9, then the following may be needed
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
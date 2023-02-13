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
$rows = $relatorios->cadastroTecAssisLab($ano_base);

/* Cria o objeto para exportar os dados em excel */
$objPHPExcel = new RelatorioXLS(); // objeto do excel

//Títulos de Colunas
$linha =1;

// Tópicos
$titulo = array('UNIDADE/CURSO', 'CADASTROU TEC. ASSISTIVA (ANO BASE '.$ano_base.')?','POSSUI LABORATÓRIO P/ O ANO BASE '.$ano_base.'?');
$ascii = 65;
foreach ($titulo as $t) {
	$objPHPExcel->getActiveSheet()->setCellValue(chr($ascii) . "1", $t);
	$objPHPExcel->getActiveSheet()->getColumnDimension(chr($ascii))->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getStyle(chr($ascii) . "1")->applyFromArray($objPHPExcel->getStyle1());
	$ascii++;
}

$objPHPExcel->getActiveSheet()
->getStyle('A1:C1')
->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()
->setRGB('CDCDCD');

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Relatório de cadastramento');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$linha = 2;
$auxUnidade=0;

//Conteúdo 
foreach ($rows as $row){	
	if($row['CodUnidade'] != $auxUnidade){
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$linha, $row['NomeUnidade']);		
			$objPHPExcel->getActiveSheet()->getStyle("A$linha:C$linha")->applyFromArray($objPHPExcel->getStyle5());
			$linha++;		
	}
	
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A'.$linha, $row['NomeCurso']);
	$objPHPExcel->getActiveSheet()->getStyle("A$linha")->applyFromArray($objPHPExcel->getStyle2());
	
	if(is_null($row['tecAssistiva'])){
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('B'.$linha, "NÃO");
		$objPHPExcel->getActiveSheet()->getStyle("B$linha")->applyFromArray($objPHPExcel->getStyle2());
		if(is_null($row['laboratorio'])){
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$linha, "NÃO");
			$objPHPExcel->getActiveSheet()->getStyle("C$linha")->applyFromArray($objPHPExcel->getStyle2());
		}else{
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$linha, "SIM");
			$objPHPExcel->getActiveSheet()->getStyle("C$linha")->applyFromArray($objPHPExcel->getStyle2());
		}
	}else{
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('B'.$linha, "SIM");
		$objPHPExcel->getActiveSheet()->getStyle("B$linha")->applyFromArray($objPHPExcel->getStyle2());
		if(is_null($row['laboratorio'])){
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$linha, "NÃO");
			$objPHPExcel->getActiveSheet()->getStyle("C$linha")->applyFromArray($objPHPExcel->getStyle2());
		}else{
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$linha, "SIM");
			$objPHPExcel->getActiveSheet()->getStyle("C$linha")->applyFromArray($objPHPExcel->getStyle2());
		}
	}
	$auxUnidade = $row['CodUnidade'];
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
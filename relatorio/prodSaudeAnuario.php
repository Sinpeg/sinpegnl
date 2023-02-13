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

$relatorios = new RelatoriosDAO();
$rows = $relatorios->producaoSaudeAnuario($ano_base);

/* Cria o objeto para exportar os dados em excel */
$objPHPExcel = new RelatorioXLS(); // objeto do excel


// Create new PHPExcel object
//$objPHPExcel = new PHPExcel();	
//$sheet = $objPHPExcel->getActiveSheet();
///$sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
//$sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//Títulos de Colunas
$linha =1;
// Add some data
/*
$objPHPExcel->getActiveSheet()->getColumnDimension(chr($ascii))->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A'.$linha, 'SUBUNIDADE/SERVIÇO')
->setCellValue('B'.$linha, 'PROCEDIMENTO')
->setCellValue('C'.$linha, 'TOTAL');
*/
// construção da planilha
$titulo = array('SUBUNIDADE/SERVIÇO', 'PROCEDIMENTO','TOTAL');
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
$objPHPExcel->getActiveSheet()->setTitle('Resultados');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$linha = 2;
$cont = 0;
$cont2=0;
$aux_subUni = "";
$contP=0;

//Conteúdo 
foreach ($rows as $row){
	$arrayDados[$cont] = array("subunidade" => $row['Subunidade'], "servico" => $row['nomeServico'] , "procedimento" => $row['nomeProcedimento'],"total" => $row['nProcedimentos']); 
	
	if($row['Subunidade'] == $aux_subUni OR $aux_subUni==""){
		$contP += $row['nProcedimentos'];  
	}else{
		$totaisP[$cont2] = array($contP);
		$cont2++;
		$contP = 0;
		$contP += $row['nProcedimentos'];		
	}
	
	$cont++;
	$aux_subUni = $row['Subunidade'];
} 
$totaisP[$cont2] = array($contP);

$aux_subUni = "";
$auxTotal =0;
$totalGeral=0;
//Inserir dados no arquivo
for ($i=0;$i<count($arrayDados);$i++){
	if ($arrayDados[$i]['subunidade'] != $aux_subUni) {
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$linha, $arrayDados[$i]['subunidade'])
		->setCellValue('B'.$linha, "")
		->setCellValue('C'.$linha, $totaisP[$auxTotal][0]);//$totaisP[$auxTotal]
		$objPHPExcel->getActiveSheet()->getStyle("A$linha:B$linha")->applyFromArray($objPHPExcel->getStyle5());
		$objPHPExcel->getActiveSheet()->getStyle("C$linha")->applyFromArray($objPHPExcel->getStyle6());
		$totalGeral += $totaisP[$auxTotal][0];
		$auxTotal++;
		$linha++;
	}
	
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A'.$linha, $arrayDados[$i]['servico'])
	->setCellValue('B'.$linha, $arrayDados[$i]['procedimento'])
	->setCellValue('C'.$linha, $arrayDados[$i]['total']);
	$objPHPExcel->getActiveSheet()->getStyle("A$linha:B$linha")->applyFromArray($objPHPExcel->getStyle2());
	$objPHPExcel->getActiveSheet()->getStyle("C$linha")->applyFromArray($objPHPExcel->getStyle4());
	$linha++;
	$aux_subUni = $arrayDados[$i]['subunidade'];
}	

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A'.$linha, "Total Geral")
->setCellValue('C'.$linha, $totalGeral);//$totaisP[$auxTotal]
$objPHPExcel->getActiveSheet()->getStyle("A$linha:B$linha:")->applyFromArray($objPHPExcel->getStyle1());
$objPHPExcel->getActiveSheet()->getStyle("C$linha")->applyFromArray($objPHPExcel->getStyle7());

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Producao_saude.xls"');
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
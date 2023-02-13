<?php
//Exibir Erros
/*ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
*/
require_once '../dao/PDOConnectionFactory.php';
require_once 'dao/relatoriosDAO.php';
require_once '../../vendor/autoload.php';
require_once '../classes/relatorioxls2.php';

//Recupera os parâmetros enviados via GET
$ano_base = $_GET['anoBase'];

//Instancia a classe
$relatorios = new RelatoriosDAO();
$rows = $relatorios->entregaRAA($ano_base);

/* Cria o objeto para exportar os dados em excel */
$objPHPExcel = new RelatorioXLS(); // objeto do excel

//Títulos de Colunas
$linha =1;

// construção da planilha
$titulo = array('UNIDADE', 'DATA DA ENTREGA','ENVIOU ANEXO?','OBSERVAÇÕES');
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
$objPHPExcel->getActiveSheet()->setTitle('Entrega do Relatório');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$linha = 2;
$cont = 0;
$cont2=0;
$aux_subUni = "";
$contP=0;

//Conteúdo 
foreach ($rows as $row){
	if($row['anobase']== $ano_base || is_null($row['anobase'])){
	$aux_anexo=0; 
	if ($row['anobase'] == $ano_base) {
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$linha, $row['nomeUnidade'])
		->setCellValue('B'.$linha, date('d/m/Y',strtotime($row['dataFinalizacao'])));
		$objPHPExcel->getActiveSheet()->getStyle("A$linha:B$linha")->applyFromArray($objPHPExcel->getStyle2());		
	}else if(is_null($row['anobase'])){
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$linha, $row['nomeUnidade'])
		->setCellValue('B'.$linha, "");
		$objPHPExcel->getActiveSheet()->getStyle("A$linha:B$linha")->applyFromArray($objPHPExcel->getStyle2());
	}
	//Buscar envio de anexo
	$rowsAnexo = $relatorios->entregaArq($ano_base, $row['codUnidade']);
	foreach ($rowsAnexo as $r){
		$aux_anexo=1;
	}
	
	if($aux_anexo==1){
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('C'.$linha, "SIM")
		->setCellValue('D'.$linha, "");
		$objPHPExcel->getActiveSheet()->getStyle("C$linha:D$linha")->applyFromArray($objPHPExcel->getStyle2());
	}elseif ($aux_anexo==0){
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('C'.$linha, "NÃO")
		->setCellValue('D'.$linha, "");
		$objPHPExcel->getActiveSheet()->getStyle("C$linha:D$linha")->applyFromArray($objPHPExcel->getStyle2());
	}
	
	$linha++;
	}
} 

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Relatorio_RAA.xls"');
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
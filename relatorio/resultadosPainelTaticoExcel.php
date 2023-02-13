<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

//require_once '../classes/sessao.php';
require_once '../dao/PDOConnectionFactory.php';
require_once '../dao/painelTaticoDAO.php';
//require_once '../classes/validacao.php';
require_once '../../vendor/autoload.php';

//Recupera os parâmetros enviados via GET
$cod_unidade = $_GET['unidade'];
$ano_base = $_GET['anoBase'];

//Definição da classe
$painelTatico = new PainelTaticoDAO();
$rows = $painelTatico->exportarResultadoPainel($ano_base, $cod_unidade);  

//Definindo variáveis auxiliares
$aux = 0;$ob_aux=''; $color_tr="";$aux2=1;
$ind_aux='';$color_tr_ind="";$aux3=1;
$color_meta=0;$color_meta2=0;$aux4=1;
$linha_aux=""; //Para construir a linha da meta do indicador a cada loop
$meta_aux = "";$ano_aux="";
$html="";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Create new PHPExcel object
$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

// Set document properties
$objPHPExcel->getProperties()->setCreator("SISRAA")
->setLastModifiedBy("SISRAA")
->setTitle("Relatório - SISRAA")
->setSubject("Relatório - SISRAA")
->setDescription("Resultados PDU")
->setKeywords("SISRAA Relatorio pdu")
->setCategory("Resultados do PDU");

$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('A:I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

//Títulos de Colunas
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A2', 'OBJETIVO')
->setCellValue('B2', 'INDICADOR')
->setCellValue('C2', 'FÓRMULA')
->setCellValue('D2', 'META')
->setCellValue('E2', 'RESULTADO')
->setCellValue('F2', 'ANÁLISE')
->setCellValue('G2', 'INICIATIVA')
->setCellValue('H2', 'SITUAÇÃO')
->setCellValue('I2', 'FATORES');

//Definindo o merge
$sheet->mergeCells('B1:F1');
$sheet->mergeCells('G1:I1');

//Definindo a coloração das cell
$objPHPExcel->getActiveSheet()
->getStyle('B1:F1')
->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()
->setRGB('D8D8D8');

$objPHPExcel->getActiveSheet()
->getStyle('G1:I1')
->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()
->setRGB('E6E6E6');

$objPHPExcel->getActiveSheet()
->getStyle('A2:I2')
->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()
->setRGB('CDCDCD');

//Definindo Grupos de dados
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('B1', 'INDICADOR')
->setCellValue('G1', 'INICIATIVA');


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Resultados');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);



$sheet->getStyle('A:I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);

$linha = 3;
//Conteúdo do Arquivo
foreach ($rows as $row){	
		
		if($row['metrica']=="Percentual"){
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$linha, $row['Objetivo'])
				->setCellValue('B'.$linha, $row['nomeindicador'])
				->setCellValue('C'.$linha, $row['calculo']);
							
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('D'.$linha, str_replace('.', ',', $row['meta']).'% ')
				->setCellValue('E'.$linha, str_replace('.', ',', $row['meta_atingida']).'% ')
				->setCellValue('F'.$linha, str_replace('.', ',', $row['analiseCritica']))
				->setCellValue('G'.$linha, $row['nomeiniciativa']);
				
		}else{
				//Exibi o primeiro objetivo					
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$linha, $row['Objetivo'])
					->setCellValue('B'.$linha, $row['nomeindicador'])
					->setCellValue('C'.$linha, $row['calculo']);
						
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('D'.$linha, str_replace('.', ',', $row['meta']))
					->setCellValue('E'.$linha, str_replace('.', ',', $row['meta_atingida']))
					->setCellValue('F'.$linha, str_replace('.', ',', $row['analiseCritica']))
					->setCellValue('G'.$linha, $row['nomeiniciativa']);					
		}
			
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('H'.$linha, $row['situacao']);						

		$fatores = "";
		if($row['pfcapacit'] == 1){ $fatores .="-Capacitação".chr(13);}
		if($row['pfrecti'] == 1){ $fatores .="-Recursos de Tecnologia da Informação".chr(13);}
		if($row['pfinfraf'] == 1){ $fatores .="- Infraestrutura Física".chr(13);}
		if($row['pfrecf'] == 1){ $fatores .="- Recursos financeiros".chr(13);}
		if($row['pfplanj'] == 1){ $fatores .="- Planejamento".chr(13);}
		if($row['outros'] != NULL){ $fatores .= '- Outros: '. $row['outros'].chr(13);}
				
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('I'.$linha, $fatores);
		
		$linha++;
}

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Painel_Tatico.xls"');
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
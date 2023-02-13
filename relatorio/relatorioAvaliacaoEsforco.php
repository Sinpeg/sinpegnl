<?php
//Exibir Erros
/*ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
*/

require_once '../dao/PDOConnectionFactory.php';
require_once '../dao/painelTaticoDAO.php';
require_once '../dao/unidadeDAO.php';
require_once '../../vendor/autoload.php';

//Recupera os parâmetros enviados via GET
$ano_base = $_GET['anoBase'];

$painelPDI = new PainelTaticoDAO();
$nomeUnidade = "";

//Banco de Dados e Query para obter o PDI
//Buscar dados de todas as unidades
$rows_pdi = $painelPDI->exportarResultadoPainelPDITodasUnidades($ano_base);
$rows = $painelPDI->exportarResultadoPainelTodasUnidades($ano_base);		


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
$objPHPExcel->getProperties()->setCreator("SINPEG")
->setLastModifiedBy("SINPEG")
->setTitle("Relatório - SInPeG")
->setSubject("Relatório - SInPeG")
->setDescription("Resultados PDU")
->setKeywords("SInPeG Relatorio pdu")
->setCategory("Resultados do PDU");

$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('A:P')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

//Títulos de Colunas
// Add some data
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A2', 'UNIDADE')
	->setCellValue('B2', 'OBJETIVO')
	->setCellValue('C2', 'INDICADOR')
	->setCellValue('D2', 'FÓRMULA')
	->setCellValue('E2', 'META')
	->setCellValue('F2', 'RESULTADO')
	->setCellValue('G2', 'PERCENTUAL DE ALCANCE')
	->setCellValue('H2', 'ANALISE')
	->setCellValue('I2', 'INICIATIVA')
	->setCellValue('J2', 'SITUAÇÃO')
	->setCellValue('K2', 'CAPACITAÇÃO')
	->setCellValue('L2', 'RECURSOS DE TI')
	->setCellValue('M2', 'INFRAESTRUTURA FÍSICA')
	->setCellValue('N2', 'RECURSOS FINANCEIROS')
	->setCellValue('O2', 'PLANEJAMENTO')
	->setCellValue('P2', 'OUTROS');
	
	//Definindo o merge
	$sheet->mergeCells('B1:H1');
	$sheet->mergeCells('I1:P1');
	
	//Definindo a coloração das cell
	$objPHPExcel->getActiveSheet()
	->getStyle('B1:H1')
	->getFill()
	->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
	->getStartColor()
	->setRGB('D8D8D8');
	
	$objPHPExcel->getActiveSheet()
	->getStyle('I1:P1')
	->getFill()
	->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
	->getStartColor()
	->setRGB('E6E6E6');
	
	$objPHPExcel->getActiveSheet()
	->getStyle('A2:P2')
	->getFill()
	->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
	->getStartColor()
	->setRGB('CDCDCD');
	
	//Definindo Grupos de dados
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('B1', 'INDICADOR')
	->setCellValue('I1', 'INICIATIVA');
	
	
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Resultados');
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	
	
	$sheet->getStyle('A:P')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);

	$linha = 3;
	
	//Conteúdo do Arquivo
		foreach ($rows as $row){		
		    if($row['meta']!=0){
        		    
		         //Calcular alcançe
		        if ($row['interpretacao']==1) {//Maior melhor
		            $alcance= (($row['meta_atingida']*100)/$row['meta']);
		        }else{
		            $alcance= (($row['meta']/$row['meta_atingida'])*100);
		        }
		        
		         if($row['metrica']=="Percentual"){
        				$objPHPExcel->setActiveSheetIndex(0)
        				->setCellValue('A'.$linha, $row['NomeUnidade'])
        				->setCellValue('B'.$linha, $row['Objetivo'])
        				->setCellValue('C'.$linha, $row['nomeindicador'])
        				->setCellValue('D'.$linha, $row['calculo']);
        					
        				$objPHPExcel->setActiveSheetIndex(0)
        				->setCellValue('E'.$linha, str_replace('.', ',', $row['meta']).'% ')
        				->setCellValue('F'.$linha, str_replace('.', ',', $row['meta_atingida']).'% ')
        				->setCellValue('G'.$linha, number_format($alcance, 2).'% ')
        				->setCellValue('H'.$linha, $row['analiseCritica'])
        				->setCellValue('I'.$linha, $row['nomeiniciativa']);
        		
        			}else{
        				//Exibi o primeiro objetivo
        				$objPHPExcel->setActiveSheetIndex(0)
        				->setCellValue('A'.$linha, $row['NomeUnidade'])
        				->setCellValue('B'.$linha, $row['Objetivo'])
        				->setCellValue('C'.$linha, $row['nomeindicador'])
        				->setCellValue('D'.$linha, $row['calculo']);
        		
        				$objPHPExcel->setActiveSheetIndex(0)
        				->setCellValue('E'.$linha, str_replace('.', ',', $row['meta']))
        				->setCellValue('F'.$linha, str_replace('.', ',', $row['meta_atingida']))
        				->setCellValue('G'.$linha, number_format($alcance, 2).'% ')
        				->setCellValue('H'.$linha, $row['analiseCritica'])
        				->setCellValue('I'.$linha, $row['nomeiniciativa']);
        			}
        				
        			if($row['pfcapacit'] == 1){ $cap ="sim";}else{$cap="não";}
        			if($row['pfrecti'] == 1){ $recti = "sim";}else{$recti="não";}
        			if($row['pfinfraf'] == 1){ $infra = "sim";}else{$infra="não";}
        			if($row['pfrecf'] == 1){ $recf="sim";}else{$recf="não";}
        			if($row['pfplanj'] == 1){ $planej = "sim";}else{ $planej="não";}
        			if($row['outros'] != NULL){ $outros = "sim";}else{$outros = "não";}
        		
        		
        			$objPHPExcel->setActiveSheetIndex(0)
        			->setCellValue('J'.$linha, $row['situacao'])
        			->setCellValue('K'.$linha, $cap)
        			->setCellValue('L'.$linha, $recti)
        			->setCellValue('M'.$linha, $infra)
        			->setCellValue('N'.$linha, $recf)
        			->setCellValue('O'.$linha, $planej)
        			->setCellValue('P'.$linha, $outros);
        		
        			$linha++;
		    }
		}//if 938
		
		foreach ($rows_pdi as $row){
		
		    //Calcular alcançe
		    if ($row['interpretacao']==1) {//Maior melhor
		        $alcance= (($row['meta_atingida']*100)/$row['meta']);
		    }else{
		        $alcance= (($row['meta']/$row['meta_atingida'])*100);
		    }
		    
		    if($row['metrica']=="Percentual"){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$linha, $row['NomeUnidade'])
				->setCellValue('B'.$linha, $row['Objetivo'])
				->setCellValue('C'.$linha, $row['nomeindicador'])
				->setCellValue('D'.$linha, $row['calculo']);
					
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('E'.$linha, str_replace('.', ',', $row['meta']).'% ')
				->setCellValue('F'.$linha, str_replace('.', ',', $row['meta_atingida']).'% ')
				->setCellValue('G'.$linha, number_format($alcance, 2).'% ')
				->setCellValue('H'.$linha, $row['analiseCritica'])
				->setCellValue('I'.$linha, $row['nomeiniciativa']);
		
			}else{
		
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$linha, $row['NomeUnidade'])
				->setCellValue('B'.$linha, $row['Objetivo'])
				->setCellValue('C'.$linha, $row['nomeindicador'])
				->setCellValue('D'.$linha, $row['calculo']);
		
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('E'.$linha, str_replace('.', ',', $row['meta']))
				->setCellValue('F'.$linha, str_replace('.', ',', $row['meta_atingida']))
				->setCellValue('G'.$linha, number_format($alcance, 2).'% ')
				->setCellValue('H'.$linha, $row['analiseCritica'])
				->setCellValue('I'.$linha, $row['nomeiniciativa']);
			}
		
			if($row['pfcapacit'] == 1){ $cap ="sim";}else{$cap="não";}
			if($row['pfrecti'] == 1){ $recti = "sim";}else{$recti="não";}
			if($row['pfinfraf'] == 1){ $infra = "sim";}else{$infra="não";}
			if($row['pfrecf'] == 1){ $recf="sim";}else{$recf="não";}
			if($row['pfplanj'] == 1){ $planej = "sim";}else{ $planej="não";}
			if($row['outros'] != NULL){ $outros = "sim";}else{$outros = "não";}
		
		
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('J'.$linha, $row['situacao'])
			->setCellValue('K'.$linha, $cap)
			->setCellValue('L'.$linha, $recti)
			->setCellValue('M'.$linha, $infra)
			->setCellValue('N'.$linha, $recf)
			->setCellValue('O'.$linha, $planej)
			->setCellValue('P'.$linha, $outros);
		
			$linha++;
		}



// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Resultados_indicadores.xls"');
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
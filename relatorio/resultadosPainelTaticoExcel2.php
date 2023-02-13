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
$cod_unidade = $_GET['unidade'];
$ano_base = $_GET['anoBase'];

//Definição da classe
/*$painelTatico = new PainelTaticoDAO();
$rows = $painelTatico->exportarResultadoPainel($ano_base, $cod_unidade);  

//Realiza a busca dos resultados dos indicadores do PDI
$rows_pdi = $painelTatico->exportarResultadoPainelPDI($ano_base, $cod_unidade);*/

$painelPDI = new PainelTaticoDAO();
$nomeUnidade = "";
if($cod_unidade != 938){
	//Banco de Dados e Query para obter o PDI
	if ($cod_unidade==0) {//Buscar dados de todas as unidades
		$rows_pdi = $painelPDI->exportarResultadoPainelPDITodasUnidades($ano_base);
		$rows = $painelPDI->exportarResultadoPainelTodasUnidades($ano_base);
	}else{
		$rows_pdi = $painelPDI->exportarResultadoPainelPDI($ano_base, $cod_unidade);
		//Busca para obter os indicadores do PDU
		$rows = $painelPDI->exportarResultadoPainel($ano_base, $cod_unidade);
		//Buscar nome unidade por código
		$unidadeDAO = new UnidadeDAO();
		$rowUni = $unidadeDAO->unidadeporcodigo($cod_unidade);
		foreach ($rowUni as $row){
		    $nomeUnidade = $row['NomeUnidade'];
		}
	}
	
}else {
	$rows_pdi = $painelPDI->exportarResultadoPainelPDI938($ano_base);
}

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
if ($cod_unidade==0){
	// Add some data
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A2', 'UNIDADE')
	->setCellValue('B2', 'OBJETIVO')
	->setCellValue('C2', 'INDICADOR')
	->setCellValue('D2', 'FÓRMULA')
	->setCellValue('E2', 'META')
	->setCellValue('F2', 'RESULTADO')
	->setCellValue('G2', 'ALCANCE')
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
	->getStyle('I1:O1')
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
}else{ //PDU
	
    
    $sheet->mergeCells('A1:O1');
    //Definindo Grupos de dados
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'UNIVERSIDADE FEDERAL DO PARÁ  
'.$nomeUnidade.'
 Resultados do Painel Tático - Ano Base - '.$ano_base.'');
	
    //Aumentar a altura da linha
    $objPHPExcel->setActiveSheetIndex(0)->getRowDimension("1")->setRowHeight(50);
    
    //Aumentar largura da da coluna
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(25);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(25);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(25);
    
    //Altura das células automáticas
    foreach(range('4','100') as $rowID) {
        $objPHPExcel->setActiveSheetIndex(0)->getRowDimension($rowID)->setRowHeight(50);
    }
    
    /*
    foreach(range('A','N') as $columnID) {
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
    }*/
    
    
    
	// Add some data
	$objPHPExcel->setActiveSheetIndex(0)	
	->setCellValue('A3', 'OBJETIVO')
	->setCellValue('B3', 'INDICADOR')
	->setCellValue('C3', 'FÓRMULA')
	->setCellValue('D3', 'META')
	->setCellValue('E3', 'RESULTADO')
	->setCellValue('F3', 'ALCANCE')
	->setCellValue('G3', 'ANALISE')
	->setCellValue('H3', 'INICIATIVA')
	->setCellValue('I3', 'SITUAÇÃO')
	->setCellValue('J3', 'CAPACITAÇÃO')
	->setCellValue('K3', 'RECURSOS DE TI')
	->setCellValue('L3', 'IFRAESTRUTURA FÍSICA')
	->setCellValue('M3', 'RECURSOS FINANCEIROS')
	->setCellValue('N3', 'PLANEJAMENTO')
	->setCellValue('O3', 'OUTROS');
	
	//Definindo o merge
	$sheet->mergeCells('B2:G2');
	$sheet->mergeCells('H2:O2');
	
	//Definindo a coloração das cell
	$objPHPExcel->getActiveSheet()
	->getStyle('B2:G2')
	->getFill()
	->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
	->getStartColor()
	->setRGB('D8D8D8');
	
	$objPHPExcel->getActiveSheet()
	->getStyle('H2:O2')
	->getFill()
	->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
	->getStartColor()
	->setRGB('E6E6E6');
	
	$objPHPExcel->getActiveSheet()
	->getStyle('A3:O3')
	->getFill()
	->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
	->getStartColor()
	->setRGB('CDCDCD');
	
	//Definindo Grupos de dados
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('B2', 'INDICADOR')
	->setCellValue('H2', 'INICIATIVA');
	
	
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Resultados');
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	
	
	$sheet->getStyle('A:O')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
	
	$linha = 4;
	
}




	
	if ($cod_unidade==0) {//Buscar dados de todas as unidades
		
		//Conteúdo do Arquivo
		foreach ($rows as $row){		
			if($row['metrica']=="Percentual"){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$linha, $row['NomeUnidade'])
				->setCellValue('B'.$linha, $row['Objetivo'])
				->setCellValue('C'.$linha, $row['nomeindicador'])
				->setCellValue('D'.$linha, $row['calculo']);
				
				$alcance = ($row['meta_atingida'] *100) / $row['meta'];
				if($alcance>=100){
				    $alcance = 100;
				}
				
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('E'.$linha, str_replace('.', ',', $row['meta']).'% ')
				->setCellValue('F'.$linha, str_replace('.', ',', $row['meta_atingida']).'% ')
				->setCellValue('G'.$linha, str_replace('.', ',', round($alcance)).'% ')
				->setCellValue('H'.$linha, $row['analiseCritica'])
				->setCellValue('I'.$linha, $row['nomeiniciativa']);
		
			}else{
				//Exibi o primeiro objetivo
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$linha, $row['NomeUnidade'])
				->setCellValue('B'.$linha, $row['Objetivo'])
				->setCellValue('C'.$linha, $row['nomeindicador'])
				->setCellValue('D'.$linha, $row['calculo']);
		
				$alcance = ($row['meta_atingida'] *100) / $row['meta'];
				if($alcance>=100){
				    $alcance = 100;
				}
				
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('E'.$linha, str_replace('.', ',', $row['meta']))
				->setCellValue('F'.$linha, str_replace('.', ',', $row['meta_atingida']))
				->setCellValue('G'.$linha, str_replace('.', ',', round($alcance)).'% ')
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
		}//if 938
		
		foreach ($rows_pdi as $row){
		
			if($row['metrica']=="Percentual"){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$linha, $row['NomeUnidade'])
				->setCellValue('B'.$linha, $row['Objetivo'])
				->setCellValue('C'.$linha, $row['nomeindicador'])
				->setCellValue('D'.$linha, $row['calculo']);
					
				$alcance = ($row['meta_atingida'] *100) / $row['meta'];
				if($alcance>=100){
				    $alcance = 100;
				}
				
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('E'.$linha, str_replace('.', ',', $row['meta']).'% ')
				->setCellValue('F'.$linha, str_replace('.', ',', $row['meta_atingida']).'% ')
				->setCellValue('G'.$linha, str_replace('.', ',', round($alcance)).'% ')
				->setCellValue('H'.$linha, $row['analiseCritica'])
				->setCellValue('I'.$linha, $row['nomeiniciativa']);
		
			}else{
		
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$linha, $row['NomeUnidade'])
				->setCellValue('B'.$linha, $row['Objetivo'])
				->setCellValue('C'.$linha, $row['nomeindicador'])
				->setCellValue('D'.$linha, $row['calculo']);
		
				$alcance = ($row['meta_atingida'] *100) / $row['meta'];
				if($alcance>=100){
				    $alcance = 100;
				}
				
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('E'.$linha, str_replace('.', ',', $row['meta']))
				->setCellValue('F'.$linha, str_replace('.', ',', $row['meta_atingida']))
				->setCellValue('G'.$linha, str_replace('.', ',', round($alcance)).'% ')
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
	}else{ //quando for para apenas uma unidade
		if ($cod_unidade!=938){
		//Conteúdo do Arquivo
		foreach ($rows as $row){	
				
				if($row['metrica']=="Percentual"){
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$linha, $row['Objetivo'])
						->setCellValue('B'.$linha, $row['nomeindicador'])
						->setCellValue('C'.$linha, $row['calculo']);
									
					$alcance = ($row['meta_atingida'] *100) / $row['meta'];
					if($alcance>=100){
					    $alcance = 100;
					}
					
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('D'.$linha, str_replace('.', ',', $row['meta']).'% ')
						->setCellValue('E'.$linha, str_replace('.', ',', $row['meta_atingida']).'% ')
						->setCellValue('F'.$linha, str_replace('.', ',', round($alcance)).'% ')
						->setCellValue('G'.$linha, $row['analiseCritica'])
						->setCellValue('H'.$linha, $row['nomeiniciativa']);
						
				}else{
						//Exibi o primeiro objetivo					
						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$linha, $row['Objetivo'])
							->setCellValue('B'.$linha, $row['nomeindicador'])
							->setCellValue('C'.$linha, $row['calculo']);
								
					$alcance = ($row['meta_atingida'] *100) / $row['meta'];
					if($alcance>=100){
					    $alcance = 100;
					}
					
					$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('D'.$linha, str_replace('.', ',', $row['meta']))
							->setCellValue('E'.$linha, str_replace('.', ',', $row['meta_atingida']))
							->setCellValue('F'.$linha, str_replace('.', ',', round($alcance)).'% ')
							->setCellValue('G'.$linha, $row['analiseCritica'])
							->setCellValue('H'.$linha, $row['nomeiniciativa']);					
				}
					
				if($row['pfcapacit'] == 1){ $cap ="sim";}else{$cap="não";}
				if($row['pfrecti'] == 1){ $recti = "sim";}else{$recti="não";}
				if($row['pfinfraf'] == 1){ $infra = "sim";}else{$infra="não";}
				if($row['pfrecf'] == 1){ $recf="sim";}else{$recf="não";}
				if($row['pfplanj'] == 1){ $planej = "sim";}else{ $planej="não";}
				if($row['outros'] != NULL){ $outros = "sim";}else{$outros = "não";}
				
				
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('I'.$linha, $row['situacao'])
					->setCellValue('J'.$linha, $cap)	
					->setCellValue('K'.$linha, $recti)
					->setCellValue('L'.$linha, $infra)
					->setCellValue('M'.$linha, $recf)
					->setCellValue('N'.$linha, $planej)
					->setCellValue('O'.$linha, $outros);				
				
				$linha++;
		}		
		}//if 938
		
		foreach ($rows_pdi as $row){
		
			if($row['metrica']=="Percentual"){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$linha, $row['Objetivo'])
				->setCellValue('B'.$linha, $row['nomeindicador'])
				->setCellValue('C'.$linha, $row['calculo']);
			
				$alcance = ($row['meta_atingida'] *100) / $row['meta'];
				if($alcance>=100){
				    $alcance = 100;
				}
				
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('D'.$linha, str_replace('.', ',', $row['meta']).'% ')
				->setCellValue('E'.$linha, str_replace('.', ',', $row['meta_atingida']).'% ')
				->setCellValue('F'.$linha, str_replace('.', ',', round($alcance)).'% ')
				->setCellValue('G'.$linha, $row['analiseCritica'])
				->setCellValue('H'.$linha, $row['nomeiniciativa']);
		
			}else{
				
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$linha, $row['Objetivo'])
				->setCellValue('B'.$linha, $row['nomeindicador'])
				->setCellValue('C'.$linha, $row['calculo']);
		
				
				
				
				$alcance = ($row['meta_atingida'] *100) / $row['meta'];
				if($alcance>=100){
				    $alcance = 100;
				}
				
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('D'.$linha, str_replace('.', ',', $row['meta']))
				->setCellValue('E'.$linha, str_replace('.', ',', $row['meta_atingida']))
				->setCellValue('F'.$linha, str_replace('.', ',',round($alcance)).'% ')
				->setCellValue('G'.$linha, $row['analiseCritica'])
				->setCellValue('H'.$linha, $row['nomeiniciativa']);
			}
				
			if($row['pfcapacit'] == 1){ $cap ="sim";}else{$cap="não";}
			if($row['pfrecti'] == 1){ $recti = "sim";}else{$recti="não";}
			if($row['pfinfraf'] == 1){ $infra = "sim";}else{$infra="não";}
			if($row['pfrecf'] == 1){ $recf="sim";}else{$recf="não";}
			if($row['pfplanj'] == 1){ $planej = "sim";}else{ $planej="não";}
			if($row['outros'] != NULL){ $outros = "sim";}else{$outros = "não";}
		
		
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('I'.$linha, $row['situacao'])
			->setCellValue('J'.$linha, $cap)
			->setCellValue('K'.$linha, $recti)
			->setCellValue('L'.$linha, $infra)
			->setCellValue('M'.$linha, $recf)
			->setCellValue('N'.$linha, $planej)
			->setCellValue('O'.$linha, $outros);
		
			$linha++;
		}
		
	}	
	




// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Resultados_indicadores_'.$nomeUnidade.'.xls"');
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
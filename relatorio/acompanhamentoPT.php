<?php
//Exibir Erros
/*ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
*/
require_once '../classes/sessao.php';
require_once '../dao/PDOConnectionFactory.php';
require_once 'dao/relatoriosDAO.php';
require_once '../../vendor/autoload.php';
require_once '../classes/relatorioxls2.php';

//Recupera os parâmetros enviados via GET
$ano_base = $_GET['anoBase'];

$relatoriosDAO = new RelatoriosDAO();
$rows = $relatoriosDAO->acompanhamentoPDU($ano_base);

/* Cria o objeto para exportar os dados em excel */
$objPHPExcel = new RelatorioXLS(); // objeto do excel

//Títulos de Colunas
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ANO BASE: '.$ano_base);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:E1');
$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($objPHPExcel->getStyle());

// Add some data
//Títulos de Colunas
$linha =2;
// Tópicos
$titulo = array('UNIDADE', 'CADASTROU O PDU ?','Nº TOTAL DE INDICADORES','Nº DE INDICADORES COM METAS DEFINIDAS','Nº DE INDICADORES COM RESULTADOS');
$ascii = 65;
foreach ($titulo as $t) {
	$objPHPExcel->getActiveSheet()->setCellValue(chr($ascii) . "2", $t);
	$objPHPExcel->getActiveSheet()->getColumnDimension(chr($ascii))->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getStyle(chr($ascii) . "2")->applyFromArray($objPHPExcel->getStyle1());
	$ascii++;
}

$objPHPExcel->getActiveSheet()
->getStyle('A1:E1')
->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()
->setRGB('CDCDCD');

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Relatório de cadastramento');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

//Variáveis auxiliares
$aux_uni="";
$aux_ind="";$count_ind=0;$count_ind_total=0;
$aux_meta="";
$aux_resul="";$count_result=0;
$i=0;$a=0;
//Início do corpo da tabela
foreach($rows as $row){
	$verifica=0;//Verifica se existe documento cadastrado
	if($aux_uni != $row['unidade']){//primeira ocorrência do documento
		if($count_ind !=0){
			//$html .= "<td>".$count_ind."</td><td>".$count_result."</td></tr>";
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$linha, $count_ind_total)
			->setCellValue('D'.$linha, $count_ind)
			->setCellValue('E'.$linha, $count_result);
			$objPHPExcel->getActiveSheet()->getStyle("A$linha:E$linha")->applyFromArray($objPHPExcel->getStyle2());
		}elseif ($i==1){
			//$html .= "<td>".$count_ind."</td><td>".$count_result."</td></tr>";
			$objPHPExcel->setActiveSheetIndex(0)
			//->setCellValue('C'.$linha, $count_ind_total)
			->setCellValue('C'.$linha, $count_ind_total)
			->setCellValue('D'.$linha, $count_ind)
			->setCellValue('E'.$linha, $count_result);
			$objPHPExcel->getActiveSheet()->getStyle("A$linha:E$linha")->applyFromArray($objPHPExcel->getStyle2());
		}

		$count_ind =0;
		$count_ind_total=0;
		$count_result=0;
		$i=0;$a=0;
		
		$linha++;//Avança para próxima linha
		//$html .= "<tr align='center'><td>".utf8_decode($row['unidade'])."</td>";
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$linha, $row['unidade']);
		$objPHPExcel->getActiveSheet()->getStyle("A$linha")->applyFromArray($objPHPExcel->getStyle2());

		$rowsDoc = $relatoriosDAO->verificaCadDoc($ano_base, $row['codUnidade']);
		foreach ($rowsDoc as $r){
			$verifica++;
		}

		if($verifica==0 ){
			//$html .= "<td>".utf8_decode('Não')."</td>";
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B'.$linha, 'Não');			
		}else{
			//$html .= "<td>Sim</td>";
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B'.$linha, utf8_decode('Sim'));
		}
		$objPHPExcel->getActiveSheet()->getStyle("B$linha")->applyFromArray($objPHPExcel->getStyle2());
		
		if(!is_null($row['meta']) && $row['meta']!=0){
			$count_ind++;			
			if (!is_null($row['resultado'])) {
				$count_result++;
			}
		}else {
			$i=1;			
		}
		if (!is_null($row['indicador'])) {
			$count_ind_total++;
		}

	}else{//Caso haja mais de um indicador cadastrado pra o documento
		if($a==0){
			if(!is_null($row['meta']) && $row['meta']!=0){
				$count_ind++;
				//$count_ind_total++;
				if (!is_null($row['resultado'])) {
					$count_result++;
				}
			}
				//$count_ind_total++;
			
			if (!is_null($row['indicador'])) {
				$count_ind_total++;
			}
		}
	}
	$aux_uni = $row['unidade'];
}

//$html .= "<td>".$count_ind."</td><td>".$count_result."</td></tr></table>";
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('C'.$linha, $count_ind_total)
->setCellValue('D'.$linha, $count_ind)
->setCellValue('E'.$linha, $count_result);
$objPHPExcel->getActiveSheet()->getStyle("A$linha:D$linha")->applyFromArray($objPHPExcel->getStyle2());

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Relatorio.xls"');
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
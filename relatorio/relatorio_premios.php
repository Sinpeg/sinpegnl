<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../dao/PDOConnectionFactory.php';
require_once 'dao/relatoriosDAO.php';
require_once '../../vendor/autoload.php';

//Recupera os parâmetros enviados via GET
$ano_base = $_GET['anoBase'];

$cod_unidade = $_GET['codUnidade'];

//Instancia a classe
$relatorios = new RelatoriosDAO();

if($cod_unidade == 100000){
	$rows = $relatorios->premiosAdm($ano_base);
}else{
	$rows = $relatorios->premios($ano_base, $cod_unidade);
}

// Create new PHPExcel object
$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();	
$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
//Títulos de Colunas
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'UNIDADE')
->setCellValue('B1', 'SUBUNIDADE')
->setCellValue('C1', 'ÓRGÃO CONCESSOR')
->setCellValue('D1', 'CATEGORIA')
->setCellValue('E1', 'NOME')
->setCellValue('F1', 'QUANTIDADE')
->setCellValue('G1', 'RECONHECIMENTO')
->setCellValue('H1', 'INFORMAÇÕES COMPLEMENTARES');

$objPHPExcel->getActiveSheet()
->getStyle('A1:H1')
->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()
->setRGB('CDCDCD');

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Resultados');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$linha = 2;
//Conteúdo do Arquivo
foreach ($rows as $row){
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A'.$linha, $row['nomeunidade'])
	->setCellValue('B'.$linha, $row['subunidade'])
	->setCellValue('C'.$linha, $row['OrgaoConcessor'])
	->setCellValue('D'.$linha, $row['categorias'])
	->setCellValue('E'.$linha, $row['Nome'])
	->setCellValue('F'.$linha, $row['Quantidade'])
	->setCellValue('G'.$linha, $row['Reconhecimento'])
	->setCellValue('H'.$linha, $row['infocomplementar']);
	
	$linha++;//Avança para próxima linha
}

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Premios.xls"');
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
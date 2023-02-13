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
$cod_unidade = $_GET['cod_unidade'];

//Instancia a classe
$relatorios = new RelatoriosDAO();

$relatorios = new RelatoriosDAO();
$rows = $relatorios->producaoSaude($ano_base,$cod_unidade);


// Create new PHPExcel object
$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();	
$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
//Títulos de Colunas
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'SUBUNIDADE')
->setCellValue('B1', 'LOCALIZAÇÃO')
->setCellValue('C1', 'SERVIÇO')
->setCellValue('D1', 'PROCEDIMENTO')
->setCellValue('E1', 'N° DE DISCENTES')
->setCellValue('F1', 'N° DE DOCENTES')
->setCellValue('G1', 'N° DE PESQUISADORES')
->setCellValue('H1', 'N° DE PESSOAS ATENDIDAS')
->setCellValue('I1', 'N° DE PROCEDIMENTOS')
->setCellValue('J1', 'N° DE EXAMES');

$objPHPExcel->getActiveSheet()
->getStyle('A1:J1')
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
	->setCellValue('A'.$linha, $row['Subunidade'])
	->setCellValue('B'.$linha, $row['Localizacao'])
	->setCellValue('C'.$linha, $row['nomeServico'])
	->setCellValue('D'.$linha, $row['nomeProcedimento'])
	->setCellValue('E'.$linha, $row['nDiscentes'])
	->setCellValue('F'.$linha, $row['nDocentes'])
	->setCellValue('G'.$linha, $row['nPesquisadores'])
	->setCellValue('H'.$linha, $row['nPessoasAtendidas'])
	->setCellValue('I'.$linha, $row['nProcedimentos'])
	->setCellValue('J'.$linha, $row['nExames']);
	
	$linha++;//Avança para próxima linha
}

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
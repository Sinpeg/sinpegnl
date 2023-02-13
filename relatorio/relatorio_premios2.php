<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../dao/PDOConnectionFactory.php';
require_once '../modulo/premios/dao/PremiosDAO.php';
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
$sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
//Títulos de Colunas
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'UNIDADE')
->setCellValue('B1', 'SUBUNIDADE')
->setCellValue('C1', 'ÓRGÃO CONCESSOR')
->setCellValue('D1', 'NOME')
->setCellValue('E1', 'RECONHECIMENTO')
->setCellValue('F1', 'PAÍS')
->setCellValue('G1', 'LINK DE EVIDÊNCIA')
->setCellValue('H1', 'DISCENTE')
->setCellValue('I1', 'DOCENTE')
->setCellValue('J1', 'TAE');

$objPHPExcel->getActiveSheet()
->getStyle('A1:J1')
->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()
->setRGB('CDCDCD');

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Premios');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$linha = 2;
//Conteúdo do Arquivo
foreach ($rows as $row){

	$daop = new PremiosDAO(); // prêmios
	$dadosPais = $daop->buscarPaisPremio($row["pais"]);
	foreach ($dadosPais as $rowpp) {
		 $pais = $rowpp['paisNome'];
    }

	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A'.$linha, $row['nomeunidade'])
	->setCellValue('B'.$linha, $row['subunidade'])
	->setCellValue('C'.$linha, $row['OrgaoConcessor'])
	->setCellValue('D'.$linha, $row['Nome'])
	->setCellValue('E'.$linha, $row['Reconhecimento'])
	->setCellValue('F'.$linha, $pais)
	->setCellValue('G'.$linha, $row['link'])
	->setCellValue('H'.$linha, $row['Qtde_discente'])
	->setCellValue('I'.$linha, $row['Qtde_docente'])
	->setCellValue('J'.$linha, $row['Qtde_tecnico']);
	
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
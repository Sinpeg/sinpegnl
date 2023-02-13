<?php
ob_start();
//echo ini_get('display_errors');

if (!ini_get('display_errors')) {
	ini_set('display_errors', 1);
	ini_set('error_reporting', E_ALL & ~E_NOTICE);
}

ob_start();
header('Content-Type: text/html; charset=utf-8');

require_once '../../classes/sessao.php';
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../modulo/micros/dao/microsDAO.php';
require_once '../../dao/unidadeDAO.php';

session_start();

$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
$codunidade = $sessao->getCodUnidade();
$ano = $sessao->getAnoBase();

if(!$aplicacoes[6])
	exit;

date_default_timezone_set('Europe/London');
require_once ('../../classes/relatorioxls.php');

$title = array("Unidade", "Uso Acadêmico", " ", "Uso Administrativo", " ");
$title2 = array("com internet", "sem internet", "com internet", "sem internet");

$objPHPExcel = new RelatorioXLS();
$objPHPExcel->header();
$objPHPExcel->maketitle($title);
$objPHPExcel->maketitle($title2);

$unidao = new UnidadeDAO();
$vet = $unidao->buscahierarquia($codunidade);

foreach($vet as $v){
	$hierarquia = $v['hierarquia'];
} 

$subunidades = $unidao->buscasubunidade($hierarquia);

$micdao = new microsDAO();

$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('A:G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$sheet->setCellValue('A1','Universidade Federal do Pará'.chr(13).'Pró-Reitoria de Planejamento'.chr(13).
		'Diretoria de Informações Institucionais'.chr(13).'Relatório de Computadores - Ano Base: '.$ano);

$AcadInt = 0;
$Acad = 0;
$AdmInt = 0;
$Adm = 0;
$line = 4;

foreach($subunidades as $sub){
	$micros = $micdao->buscamicrosunidade($sub['CodUnidade'], $ano);
	foreach($micros as $m){
		$sheet->setCellValue('A'.$line, $sub['NomeUnidade']);
		$sheet->setCellValue('B'.$line, $m['QtdeAcadInt']);
		$AcadInt += $m['QtdeAcadInt'];
		 
		$sheet->setCellValue('C'.$line, $m['QtdeAcad']);
		$Acad += $m['QtdeAcad'];
		
		$sheet->setCellValue('D'.$line, $m['QtdeAdmInt']);
		$AdmInt += $m['QtdeAdmInt'];
		
		$sheet->setCellValue('E'.$line, $m['QtdeAdm']);
		$Adm += $m['QtdeAdm'];
		++$line;
	}
}

$sheet->setCellValue('A'.$line, "Total");
$sheet->setCellValue('B'.$line, $AcadInt);
$sheet->setCellValue('C'.$line, $Acad);
$sheet->setCellValue('D'.$line, $AdmInt);
$sheet->setCellValue('E'.$line, $Adm);

$sheet->fromArray($title, null,'A2');
$sheet->fromArray($title2, null, 'B3');
$sheet->mergeCells('A2:A3');
$sheet->mergeCells('B2:C2');
$sheet->mergeCells('D2:E2');
$sheet->mergeCells('A1:E1');
$objPHPExcel->getActiveSheet()->getStyle('B3:E3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A'.$line)->getFont()->setBold(true);

foreach(range('A','G') as $columnID) {
	$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

$micdao->fechar();
$unidao->fechar();

ob_clean();

$file_name = "Relatorio_de_computadores_".$ano."_".date('d/m/Y').".xls";
$objPHPExcel->download($file_name);

ob_flush();
exit;
?>
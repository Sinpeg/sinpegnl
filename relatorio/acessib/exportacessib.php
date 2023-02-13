<?php
require_once '../../classes/sessao.php';
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../modulo/acessib/dao/tpacessibilidadeDAO.php';
require_once '../../classes/validacao.php';
require_once '../../classes/relatorioxls.php';
session_start();
$aplicacoes = $_SESSION["sessao"]->getAplicacoes();
if (!$aplicacoes[35]) {
    exit();
}
if (!isset($_SESSION["sessao"])) {
    exit();
}
$ano = $_POST['ano']; // ano inicial
$ano1 = $_POST['ano1']; // ano final
$tipo = $_POST['tipo']; // tipo da infraestrutura de ensino
$unidade = $_POST['unidade']; // unidade
/******************************* VALIDAÇÃO DOS DADOS RECEBIDOS ************************************/
$validacao = new Validacao(); // objeto para validação dos dados
if ($ano1=="")
	$ano1 = $ano;
// 1 - Ano
if ($validacao->is_yearempty($ano)) {
	$error = "Por favor, informe o ano inicial!";
}
else if (!$validacao->is_validyear($ano)) {
	$error = "Ano inicial inválido!";
}
// 2 - Ano1
else if (!$validacao->is_validyear($ano1) && !$validacao->is_yearempty($ano1)) {
	$error = "Ano final inválido!";
}
// 3 - Ano1 não deve ser menor que ano 
elseif ($validacao->is_validyear($ano) && $validacao->is_validyear($ano1) && ($ano1<$ano) ) {
	$error = "O ano final deve ser maior ou igual ao inicial.";
	
}
// 4 - Unidade
else if (($unidade!="todas" && $unidade!="institutos" && $unidade!="campus" && $unidade!="nucleos")
		&& (!preg_match("/^[1-9][0-9]{0,}/",$unidade))) {
	$error = "Unidade não encontrada! ";
}
// 5 - Tipo
else if ($tipo!="AR" && $tipo!="BA" && $tipo!="EE" && $tipo!="MA" && $tipo!="RA" && $tipo!="todos") {
	$error = "Tipo inválido!";
}
?>
<?php 
date_default_timezone_set('Europe/London');
//require_once('../../includes/excel/relatorioxls.php');
$title = array("Unidade Acadêmica", "Tipo da Infraestrutura", "Quantidade", "Ano");
$objPHPExcel = new RelatorioXLS();
$objPHPExcel->header();
$objPHPExcel->maketitle($title);
$sheet = $objPHPExcel->getActiveSheet();
if ($ano1=="") {
	$ano1 = ano;
}
switch ($unidade) {
	case "todas":
		$sqlparam= "";
		break;
	case "institutos":
		$sqlparam = "AND `NomeUnidade` LIKE 'instituto%'";
		break;
	case "campus":
		$sqlparam = "AND `NomeUnidade` LIKE 'campus%'";
		break;
	case "nucleos":
		$sqlparam = "AND `NomeUnidade` LIKE 'nucleo%'";
		break;
	default:
		$sqlparam = "AND u.`CodUnidade` = ".$unidade;
		break;
}
$daoacess = new TpacessibilidadeDAO();
$row = $daoacess->buscaestruturaacess($ano, $ano1, $tipo, $sqlparam);
$line = 3;
$cont = 0;
// $sheet->getStyle('A:D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$sheet->setCellValue('A1','Universidade Federal do Pará'.chr(13).'Pró-Reitoria de Planejamento'.chr(13).
		'Diretoria de Informações Institucionais'.chr(13).'Estrutura de Acessibilidade - Período: '.$ano.
		' a '.$ano1);
foreach ($row as $r) {
	$objPHPExcel->getActiveSheet()->setCellValue("A".$line, $r['NomeUnidade']);
	$objPHPExcel->getActiveSheet()->setCellValue("B".$line, ($r['Nome']));
	$objPHPExcel->getActiveSheet()->setCellValue("C".$line, $r['Quantidade']);
	$objPHPExcel->getActiveSheet()->setCellValue("D".$line, $r['Ano']);
	$line++;
}
$sheet->mergeCells('A1:D1');
//$objPHPExcel->getActiveSheet()->setCellValue("C".$line, $cont);
$objPHPExcel->getActiveSheet()->getStyle('C'.$line)->applyFromArray($objPHPExcel->getStyle2());
$file_name = "Relatorio_".$ano."_".date('d/m/Y').".xls";
$objPHPExcel->download($file_name);
ob_flush();
exit;
?>

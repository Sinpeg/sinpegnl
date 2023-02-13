<?php
ob_start();
header('Content-Type: text/html; charset=utf-8'); 
require_once '../../classes/sessao.php'; 
require_once '../../dao/PDOConnectionFactory.php'; 
require_once '../../classes/validacao.php'; 
require_once '../../modulo/infraensino/dao/tipoinfraensinoDAO.php'; 
session_start(); 
$aplicacoes = $_SESSION["sessao"]->getAplicacoes(); 
if (!$aplicacoes[34]) { 
// header("Location:../../index.php"); 
 exit; 
} 
if (!isset($_SESSION["sessao"])) { 
// header("Location:../../index.php"); 
} 
$sessao = $_SESSION["sessao"]; 
if ($sessao->getGrupo()!=1) { 
// header("location:../../index.php"); 
} 
?> 
<?php 
date_default_timezone_set('Europe/London'); 
require_once('../../classes/relatorioxls.php'); 
$ano = $_POST['ano']; // ano inicial 
$ano1 = $_POST['ano1']; // ano final 
$unidade = $_POST['unidade']; // unidade selecionada 
$tipo = $_POST['tipo']; // tipo da infraestrutura de ensino 
if ($ano1=="") { 
 $ano1 = $ano; 
} 
/******************************* VALIDAÇÃO DOS DADOS RECEBIDOS ************************************/ 
$validacao = new Validacao(); // objeto para validação dos dados 
// 1 - Ano 
if ($validacao->is_yearempty($ano)) { 
 print "Por favor, informe o ano inicial!"; 
} 
else if (!$validacao->is_validyear($ano)) { 
 print "Ano inicial inválido!"; 
} 
// 2 - Ano1 
else if (!$validacao->is_validyear($ano1) && !$validacao->is_yearempty($ano1)) { 
 print "Ano final inválido!"; 
} 
// 3 - Unidade 
else if (($unidade!="todas" && $unidade!="institutos" && $unidade!="campus" && $unidade!="nucleos") 
 && (!preg_match("/^[1-9][0-9]{0,}/",$unidade))) { 
 print "Unidade não encontrada! "; 
} 
// 4 - Tipo 
// else if (!is_integer($tipo)) { 
// print "O tipo deve ser um valor numérico"; 
// } 
// else if (is_integer($tipo)) { 
// if ($tipo<0 || $tipo>12) 
// print "O tipo deve ser um valor entre 0 e 12"; 
// } 
/*************************************** FIM DA VALIDAÇÃO *****************************************/ 
else { 
 $title = array("Unidade Acadêmica", "Tipo da Infraestrutura", "Quantidade", "Ano"); 
 $objPHPExcel = new RelatorioXLS(); 
 $objPHPExcel->header(); 
 $objPHPExcel->maketitle($title);
 $sheet = $objPHPExcel->getActiveSheet(); 
 
 switch ($unidade) { 
 case "todas": 
 $sql_param= ""; 
 break; 
 case "institutos": 
 $sql_param = " AND u.`NomeUnidade` LIKE 'instituto%'"; 
 break; 
 case "campus": 
 $sql_param = " AND u.`NomeUnidade` LIKE 'campus%'"; 
 break; 
 case "nucleos": 
 $sql_param = " AND u.`NomeUnidade` LIKE 'nucleo%'"; 
 break; 
 default: 
 $sql_param = " AND u.`CodUnidade` = ".$unidade; 
 break; 
 } 
 $daotie = new TipoinfraensinoDAO(); 
 $row = $daotie->buscatipo1($tipo, $ano, $ano1, $sql_param);
 $line = 3; 
 $cont = 0; 
 $sheet->getStyle('A:C')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP); 
 
 $sheet->setCellValue('A1','Universidade Federal do Pará'.chr(13).'Pró-Reitoria de Planejamento'.chr(13). 
 'Diretoria de Informações Institucionais'.chr(13).'Infraestrutura de Ensino - Período: '.$ano. 
 ' a '.$ano1); 
 foreach ($row as $r) { 
 $objPHPExcel->getActiveSheet()->setCellValue("A".$line, $r['NomeUnidade']); 
 $objPHPExcel->getActiveSheet()->setCellValue("B".$line, ($r['Nome'])); 
 $objPHPExcel->getActiveSheet()->setCellValue("C".$line, $r['Quantidade']); 
 $objPHPExcel->getActiveSheet()->setCellValue("D".$line, $r['Ano']); 
 $line++; 
 $cont += $r['Quantidade']; 
 } 
 $sheet->mergeCells('A1:D1'); 
 $objPHPExcel->getActiveSheet()->setCellValue("C".$line, $cont); 
 $daotie->fechar();
 ob_clean();
 $file_name = "Relatorio_".$ano."_".date('d/m/Y').".xls"; 
 $objPHPExcel->download($file_name); 
 ob_flush(); 
 exit; 
} 
?> 

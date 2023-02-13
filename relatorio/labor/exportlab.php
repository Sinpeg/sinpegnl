<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

//ob_start();
//require_once(dirname(__FILE__).'/../../includes/classes/sessao.php'); 
//require_once (dirname(__FILE__).'/../../includes/dao/PDOConnectionFactory.php'); 
//require_once (dirname(__FILE__).'/../../principal/labor/dao/laboratorioDAO.php'); 
//require_once(dirname(__FILE__).'/../../includes/classes/validacao.php'); 
//echo "teste";
require_once '../../classes/sessao.php';
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../modulo/labor/dao/laboratorioDAO.php';
require_once '../../classes/relatorioxls.php';
require_once '../../classes/validacao.php';
require_once '../../dao/unidadeDAO.php';


session_start();
$aplicacoes = $_SESSION["sessao"]->getAplicacoes();
if (!$aplicacoes[32]) {
    header("Location:../../index.php");
    exit;
}
if (!isset($_SESSION["sessao"])) {
    header("Location:../../index.php");
}


$ano_inicio = addslashes($_POST['ano']); // ano de inicio 
$ano_fim = addslashes($_POST['ano1']); // ano final 
$situacao = $_POST['situacao']; // situacao 
$unidade = $_POST['unidade']; // unidade 
$curso = $_POST['curso']; // curso 
/* * *********************************** VALIDAÇÃO ***************************** */
$valida = new Validacao();
if (($unidade != "todas" && $unidade != "institutos" && $unidade != "campus" && $unidade != "nucleos") && (!preg_match("/^[1-9][0-9]{0,}/", $unidade))) {
    print "Unidade não encontrada! ";
} else if ($valida->is_yearempty($ano_inicio)) {
    print "Preencha o campo ano!";
} else if (!$valida->is_validyear($ano_inicio)) {
    print "Por favor, informe corretamente o campo ano!";
} else if (!$valida->is_yearempty($ano_fim) && !$valida->is_validyear($ano_fim)) {
    print "Por favor, informe corretamente o segundo campo para o ano!";
} else if (($ano_fim < $ano_inicio) && !$valida->is_yearempty($ano_fim)) {
    print "O ano final deve ser maior ou igual ao inicial.";
}
/* * *************************************************************************** */ else {


// date_default_timezone_set('Europe/London'); 
//require_once(dirname(__FILE__).'/../../includes/excel/relatorioxls.php'); 
    $objPHPExcel = new RelatorioXLS();
    $objPHPExcel->header();
    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->setCellValue('A1', 'Universidade Federal do Pará' . chr(13) . 'Pró-Reitoria de Planejamento' . chr(13) .
            'Diretoria de Informações Institucionais' . chr(13) . 'Infraestrutura dos Laboratórios - Período: ' . $ano_inicio .
            ' a ' . $ano_fim);
    $title = ($curso != "curso") ? (array("Unidade do Laboratório", "Laboratório", "Categoria", "Subcategoria", "Sigla", "Área", "Capacidade",
        "Lab. de Ensino", "Nº Estações", "Local", "SO", "Cabeamento estruturado")) : array("Unidade do Laboratório", "Laboratório", "Unidade do Curso", "Curso", "Categoria", "Subcategoria", "Sigla", "Área", "Capacidade",
        "Lab. de Ensino", "Nº Estações", "Local", "SO", "Cabeamento estruturado");
    $merge = ($curso != "curso") ? "A1:L1" : "A1:N1";
    $objPHPExcel->maketitle($title);
    $sheet->mergeCells($merge);
    
    switch ($unidade) {
        case "todas":
        	$sql_param="";
            break;
        case "institutos":
            $sql_param .= " AND ul.`NomeUnidade` LIKE 'instituto%'";
            break;
        case "campus":
            $sql_param .= " AND ul.`NomeUnidade` LIKE 'campus%'";
            break;
        case "nucleos":
            $sql_param .= " AND ul.`NomeUnidade` LIKE 'nucleo%'";
            break;
        default:
            $sql_param .= " AND l.`CodUnidade` = " . $unidade;
            break;
    }
    
    
   
    //Busca Unidades Responsaveis
    $daoUni = new UnidadeDAO();
    $rowUni = $daoUni->buscaUniResponsaveis();
    
    
    //$row = $daolab->buscaLabUnid($sql_param, $curso, $situacao, $ano_inicio_inicio_inicio, $ano_inicio_inicio_fim);
    $line = 3;
    $area = 0;
    
  
    foreach ($rowUni as $row){
    	$daolab = new LaboratorioDAO();
    	/* Faz a consulta  dos laboratórios*/
    	$daolab = new LaboratorioDAO();
    	$rowlab = $daolab->buscaLabUnid2($sql_param, $curso,$ano_inicio, $ano_fim, $situacao,$row['hierarquia_organizacional']);
    	$aux_nome="";
    	
    	   //Iteração nos resultados da consultas 
		    foreach ($rowlab as $r) {
		        switch ($r['SisOperacional']) {
		            case "W":
		                $so = 'Windows';
		                break;
		            case "L":
		                $so = 'Linux';
		                break;
		            case "0":
		                $so = '-';
		                break;
		        }
		        switch ($r['CabEstruturado']) {
		            case 'N':
		                $cab = 'Não';
		                ;
		                break;
		
		            case 'S':
		                $cab = 'Sim';
		                ;
		                break;
		        }
		        switch ($r['LabEnsino']) {
		            case 'N':
		                $labens = 'Não';
		                break;
		            default:
		                $labens = 'Sim';
		                break;
		        }
		        
		       
		        if ($curso != "curso") {
		           $sheet->setCellValue("A" . $line, $row['NomeUnidade']);
		           $sheet->setCellValue("B" . $line, ($r['Laboratorio']));
		           $sheet->setCellValue("C" . $line, ($r['Categoria']));
		           $sheet->setCellValue("D" . $line, ($r['Subcategoria']));
		           $sheet->setCellValue("E" . $line, $r['Sigla']);
		           $sheet->setCellValue("F" . $line, str_replace(".", ",", $r['Area']));
		           $sheet->setCellValue("G" . $line, $r['Capacidade']);
		           $sheet->setCellValue("H" . $line, $labens);
		           $sheet->setCellValue("I" . $line, $r['Nestacoes']);
		           $sheet->setCellValue("J" . $line, ($r['Local']));
		           $sheet->setCellValue("K" . $line, $so);
		           $sheet->setCellValue("L" . $line, $cab);
		        
		        } else {
		          
		            $sheet->setCellValue("A" . $line, $row['NomeUnidade']);
		           $sheet->setCellValue("B" . $line, ($r['Laboratorio']));
		           $sheet->setCellValue("C" . $line, ($r['UnidadeCurso']));
		           $sheet->setCellValue("D" . $line, ($r['NomeCurso']));
		           $sheet->setCellValue("E" . $line, ($r['Categoria']));
		           $sheet->setCellValue("F" . $line, ($r['Subcategoria']));
		           $sheet->setCellValue("G" . $line, $r['Sigla']);
		           $sheet->setCellValue("H" . $line, str_replace(".", ",", $r['Area']));
		           $sheet->setCellValue("I" . $line, $r['Capacidade']);
		           $sheet->setCellValue("J" . $line, $labens);
		           $sheet->setCellValue("K" . $line, $r['Nestacoes']);
		           $sheet->setCellValue("L" . $line, ($r['Local']));
		           $sheet->setCellValue("M" . $line, $so);
		           $sheet->setCellValue("N" . $line, $cab);
		           
		        }
		        $line++;
		        $area += $r['Area'];
		    }    
    }
    
    
	
	
    
    
    ob_clean();
   //$sheet->setCellValue("F" . $line, $area);
// Fim da Iteração 
    $daolab->fechar(); // fecha a conexão 
   	 $file_name = "Relatorio_" . date('d/m/Y') . ".xls";
    $objPHPExcel->download($file_name);
    exit;
}
?> 

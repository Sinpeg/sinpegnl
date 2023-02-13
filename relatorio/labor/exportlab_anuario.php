<?php 

//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
//ob_start(); 
//require_once(dirname(__FILE__).'/../../classes/sessao.php'); 
//require_once(dirname(__FILE__) . '/../../dao/PDOConnectionFactory.php'); 
//require_once(dirname(__FILE__) . '/../../modulo/labor/dao/laboratorioDAO.php'); 
//require_once(dirname(__FILE__) . '/../../classes/relatorioxls.php'); 
//require_once(dirname(__FILE__) . '/../../classes/validacao.php'); 
//echo "teste"; 
//exit(); 
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
 exit; 
} 
?> 
<?php 
$ano = $_POST["ano_anuario"]; // ano de início 
$situacao = $_POST["situacao_anuario"]; // situação 
$unidade = $_POST["unidade_anuario"]; // unidade 
$ano1 = $_POST["ano1_anuario"]; // ano limite 
if ($ano1 == "") 
 $ano1 = $ano; 
/* * *********************************** VALIDAÇÃO ************************************************* */ 
$valida = new Validacao(); 
if (($unidade != "todas" && $unidade != "institutos" && $unidade != "campus" && $unidade != "nucleos") 
 && (!preg_match("/^[1-9][0-9]{0,}/", $unidade))) { 
 print "Unidade não encontrada!"; 
} else if ($valida->is_yearempty($ano)) { 
 print "Preencha o campo ano!"; 
} else if (!$valida->is_validyear($ano)) { 
 print "Por favor, informe corretamente o campo ano!"; 
} else if (!$valida->is_yearempty($ano1) && !$valida->is_validyear($ano1)) { 
 print "Por favor, informe corretamente o segundo campo para o ano!"; 
} else if (($ano1 < $ano) && !$valida->is_yearempty($ano1)) { 
 print "O ano final deve ser maior ou igual ao inicial."; 
} else { 
 /* Seleciona o tipo de pesquisa enviada ao servidor */ 
 switch ($unidade) { 
	 case "todas": 
	 $query ="";	
	 break; 
	 
	 case "campus": 
	 $query = " AND u.`NomeUnidade` LIKE 'campus%'"; 
	 break; 
	 
	 case "nucleos": 
	 $query = " AND u.`NomeUnidade` LIKE 'nucleo%'"; 
	 break; 
	 
	 case "institutos": 
	 $query = " AND u.`NomeUnidade` LIKE 'instituto%'"; 
	 break; 
	 
	 default: 
	 if (is_numeric($unidade)) 
	 $query = " AND u.`CodUnidade` = $unidade"; 
	 break; 
 } 
 /* Fim */ 
 
 //Busca Unidades Responsaveis
 $daoUni = new UnidadeDAO();
 $rowUni = $daoUni->buscaUniResponsaveis();
  
 /* Cria o objeto para exportar os dados em excel */ 
 $objPHPExcel = new RelatorioXLS(); // objeto do excel 
// $sheet = $objPHPExcel->getActiveSheet(); 
 /* Fim */ 
 
 // construção da planilha 
 $titulo = array('Unidade Acadêmica/Laboratórios', 'Área (m2)'); 
 $ascii = 65; 
 foreach ($titulo as $t) { 
	 $objPHPExcel->getActiveSheet()->setCellValue(chr($ascii) . "1", $t); 
	 $objPHPExcel->getActiveSheet()->getColumnDimension(chr($ascii))->setAutoSize(true); 
	 $objPHPExcel->getActiveSheet()->getStyle(chr($ascii) . "1")->applyFromArray($objPHPExcel->getStyle1()); 
	 $ascii++; 
 }
  
 // Construção do conteúdo 
// $nome = ""; 
 $line = 2; 
  /* 
 * Iteração nos resultados e impressão destes no arquivo .xls 
 */ 
 $u = ""; 
 $aux=0;
 $areaTotal=0;
 foreach ($rowUni as $row){
 	
 	
     
 	$daolab = new LaboratorioDAO();
 	// Faz a consulta  dos laboratórios
 	$rowlab = $daolab->buscalabunidade($query, $ano, $ano1, $situacao,$row['hierarquia_organizacional']);
 	$aux_nome="";
 	
 	foreach ($rowlab as $r) {
 		if($u !== $r['NomeUnidade']){
 			
 			if($row['NomeUnidade'] != $aux_nome){ 				
 				$objPHPExcel->getActiveSheet()->setCellValue("A" . $line, $row['NomeUnidade']);
 				$objPHPExcel->getActiveSheet()->getStyle("A$line:B$line")->applyFromArray($objPHPExcel->getStyle1());
 				//$objPHPExcel->getActiveSheet()->setCellValue("A$line");
 				$line++;
 				$u = $r['NomeUnidade'];
 			}
 			
 			//$objPHPExcel->getActiveSheet()->getStyle("A$line")->applyFromArray($objPHPExcel->getStyle1());
 			//$objPHPExcel->getActiveSheet()->mergeCells("A$line:C$line");
 			//$line++;
 			$u = $r['NomeUnidade'];
 		}
 		$areaTotal += $r['Area'];
 		$objPHPExcel->getActiveSheet()->setCellValue("A" . $line, ($r['Nome']));
 		$objPHPExcel->getActiveSheet()->getStyle("A$line")->applyFromArray($objPHPExcel->getStyle2());
 		$objPHPExcel->getActiveSheet()->setCellValue("B" . $line,  $r['Area']);
 		$objPHPExcel->getActiveSheet()->getStyle("B$line")->applyFromArray($objPHPExcel->getStyle4());
 		$line++;	
 		$aux_nome = $row['NomeUnidade'];
 	}
 	
 	
 	// Fim
 	if($areaTotal !=0){
 	    
	 	$objPHPExcel->getActiveSheet()->setCellValue("A" . $line, "TOTAL");
	 	$aux++;
	 	
	 	$objPHPExcel->getActiveSheet()->setCellValue("B" . $line,  $areaTotal);
	 	
	 	$objPHPExcel->getActiveSheet()->getStyle("A$line:B$line")->applyFromArray($objPHPExcel->getStyle3());
	 	$line++;
	 	$areaTotal = 0;
	 	
 	}
 	
 }
 

 
 
 /*
 
 foreach ($row as $r) { 
	 if($u !== $r['NomeUnidade']){ 
		 $objPHPExcel->getActiveSheet()->setCellValue("A" . $line, $r['NomeUnidade']); 
		 $objPHPExcel->getActiveSheet()->getStyle("A$line")->applyFromArray($objPHPExcel->getStyle1()); 
		 $objPHPExcel->getActiveSheet()->mergeCells("A$line:C$line"); 
		 $line++; 
		 $u = $r['NomeUnidade']; 
	 } 
	 $objPHPExcel->getActiveSheet()->setCellValue("B" . $line, ($r['Nome'])); 
	 $objPHPExcel->getActiveSheet()->getStyle("B$line")->applyFromArray($objPHPExcel->getStyle2()); 
	 $objPHPExcel->getActiveSheet()->setCellValue("C" . $line, str_replace(".", ",", $r['Area'])); 
	 $objPHPExcel->getActiveSheet()->getStyle("C$line")->applyFromArray($objPHPExcel->getStyle2()); 
	 $line++; 
 } 
 // Fim da iteração 
*/
 ob_clean();
 $file = "Relatorio_" . $ano . "_" . date('d/m/Y') . ".xls"; 
 $objPHPExcel->download($file); 
} 
//ob_flush(); 
exit; 
?> 

<?php
//Exibir Erros
ini_set('display_errors',1);//Exibir Erros
ini_set('display_startup_erros',1);//Exibir Erros
error_reporting(E_ALL);//Exibir Erros 

//set_include_path(';../..');
require_once('../../classes/sessao.php');
session_start();
if (!isset($_SESSION["sessao"])) {
	echo "Você nã está autorizado a acessar o formulário. Faça login no sistema";
    exit();
}

$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();;
$aplicacoes = $_SESSION["sessao"]->getAplicacoes();
if (!$aplicacoes[14]) {
	echo "Você não tem permissão para acessar este formulário!";
    exit();
}
require_once('../../dao/PDOConnectionFactory.php');
require_once('../../classes/unidade.php');
require_once('../../dao/servicoDAO.php');
require_once('../../classes/servico.php');
//require_once('dao/procedimentoDAO.php');
//require_once('classes/procedimento.php');
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
//$daop =  new ProcedimentoDAO();

$codsubunidade = $_POST["subunidade"]; //Obtem o cod da subunidade enviada ao selecionar o select do form

if (is_numeric($codsubunidade) && $codsubunidade != "" && $codsubunidade != "0") {
if ($anobase==2018 && $codunidade==270){
	$display="<option value=0>Selecione um servi&ccedil;o...</option>";
  
    	       $display .="<option  value=";
               $display .= "37>";
               $display .= "Sem serviço</option>";
    	
   
    $display.="</select>";
	
	
}else{
	$daos = new ServicoDAO();
    $unidade->criaSubunidade($codsubunidade, null, null);
    $rows1 = $daos->buscaservicosSub($codsubunidade);
    $passou = false;
    foreach ($rows1 as $row1) {
        $passou = true;
        $unidade->getSubunidade()->adicionaItemServico($row1['Codigo'], $row1['Nome']);
    }
    
    $display="<option value=0>Selecione um servi&ccedil;o...</option>";
    $cont=0;
    foreach ($unidade->getSubunidade()->getServicos() as $s) {
    	
    	       $display .="<option  value=";
               $display .= $s->getCodigo() .">";
               $display .= ($s->getNome());
    	
    }
    $display.="</select>";
    $daos->fechar();
}
    
  
} else {
//	$display="<select name=servico>";
    $display.="<option value=0>Selecione um servi&ccedil;o...</option>";
 //   $display.="</select>";
}
echo $display;
//ob_end_flush();
?>
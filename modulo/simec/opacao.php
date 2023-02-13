<?php
require_once('classes/programa.php');
require_once('classes/acao.php');
require_once('dao/acaoDAO.php');

session_start();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[2]) {
    header("Location:index.php");
}
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);

//Valida campos
$operacao=$_POST["operacao"];
$codigoprog=$_POST["codigoprog"];
$codigoacao=$_POST["codigoacao"];
$analise=stripslashes($_POST['analise']);

if ($operacao=="A" && is_string($analise) && $analise!=""
&& is_numeric($codigoprog) && is_numeric($codigoacao) && $codigoacao!="" && $codigoprog!="" ){
	$dao= new AcaoDAO();
	$pro= new Programa();
	$pro->setCodigo($codigoprog);
	$pro->criaAcao($codigoacao, $unidade, null, null, null, null,
	strtolower($analise),$anobase);
	$dao->altera($pro->getAcao());
	$dao->fechar();
} else {
//	$mensagem = urlencode(" ");
////	$cadeia="location:../../saida/erro.php?codigo=1&mensagem=".$mensagem;
//	header($cadeia);
//	exit();
}
$cadeia = Utils::createLink('simec', 'consultaacao');
header("Location:$cadeia");
//exit();
//ob_end_flush();
?>
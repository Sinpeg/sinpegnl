<?php
if (!isset($_SESSION["sessao"])){
	//header("location:index.php");
}
require_once('../../dao/PDOConnectionFactory.php');
require_once('../../modulo/uparquivo/classes/arquivo.php');
require_once('../../modulo/uparquivo/dao/arquivoDAO.php');
$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeUnidade();
$codunidade = $sessao->getCodUnidade();
$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
$codusuario = $sessao->getCodusuario();
$data = $sessao->getData();
$codigo= $_GET['codigo'];
if (is_numeric($codigo) && $codigo!=""){
	$dao = new ArquivoDAO();
	$rows = $dao->buscaCodigo($codigo);
	$dao->fechar();
}
?>
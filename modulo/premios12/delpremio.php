<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//session_start();
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[5]) {
    header("Location:index.php");
} 
else{
//	$sessao = $_SESSION["sessao"];
//	$nomeunidade = $sessao->getNomeunidade();
//	$codunidade = $sessao->getCodunidade();
//	$responsavel = $sessao->getResponsavel();
//	$anobase = $sessao->getAnobase();
//	require_once('../../includes/dao/PDOConnectionFactory.php');
	// var_dump($usuario);
	require_once('classes/premios.php');
	require_once ('dao/premiosDAO.php');
	$codigo= $_GET["codigo"];
	if ( is_numeric($codigo) ){
		$dao= new PremiosDAO();
		$p= new Premios();
		$p->setCodigo($codigo);
		$dao->deleta($p);
		$dao->fechar();
	}
        Utils::redirect('premios', 'consultapremios');
//	$cadeia="location:consultapremios.php";
//	header($cadeia);
}
?>




<?php
require '../../classes/unidade.php';
require '../../dao/PDOConnectionFactory.php';
require '../../classes/sessao.php';
require '../../modulo/mapaestrategico/classes/Mapa.php';
require '../../modulo/mapaestrategico/dao/MapaDAO.php';



session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
$daomapa = new MapaDAO();
$codigo = $_GET['codmapa'];
$action = $_GET['action'];
alert("Teste "+$action+" codigo "+$codigo);

if($action == 'D' && $codigo != 0 || $codigo != null){
	$daomapa->deleta($codigo);
	$message = "mapa deletado com sucesso!";
}else{
	$message = "falha ao deletar o mapa";
}

print "<br><img src='webroot/img/accepted.png' width='30' height='30'/>"; print $message;
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
$codigo = $_POST['codmapa'];
$action = $_POST['action'];
//echo $action." PASSOU"; die;
if($action == 'D' && is_numeric($codigo)  &&   $codigo != 0 || $codigo != null){
	$result = $daomapa->deleta($codigo);
	$message = "true";
}else{
	$message = "false";
}


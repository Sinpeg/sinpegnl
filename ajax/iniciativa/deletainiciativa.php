<?php
require '../../classes/unidade.php';
require '../../dao/PDOConnectionFactory.php';
require '../../classes/sessao.php';
require '../../modulo/iniciativa/classe/Iniciativa.php';
require '../../modulo/iniciativa/dao/IniciativaDAO.php';



session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
$dao = new IniciativaDAO();
$codigo = $_POST['codini'];
$action = $_POST['action'];
if($action == 'D' && $codigo != 0 && $codigo != null){
	$dao->deleta($codigo);
	$message = "true";
}else{
	$message = "false";
}


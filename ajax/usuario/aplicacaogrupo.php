<?php
usleep(500000);
/* grant */
require_once '../../classes/sessao.php';
session_start();
$sessao = $_SESSION["sessao"];
if (!isset($sessao)) {
	exit(0);
} else {
	$aplicacoes = $sessao->getAplicacoes();
	if (!$aplicacoes[47]) {
		exit(0);
	}
}

define('BASE_DIR', dirname(__FILE__));
require_once(BASE_DIR . DIRECTORY_SEPARATOR . '../../dao/PDOConnectionFactory.php');
require_once(BASE_DIR . DIRECTORY_SEPARATOR . '../../dao/grupoDAO.php');

$grupo = addslashes($_POST["cad-consulta"]);

$dao = new GrupoDAO();
$_SESSION['grupose'] = $_POST["cad-consulta"];
$aplicacoes = $dao->buscaaplicagrupo($grupo);

$display = "";

foreach ($aplicacoes as $a) {
	$display .="<li>";
	$display .=  $a["Nome"]. "</li>";
}

echo $display;
?>
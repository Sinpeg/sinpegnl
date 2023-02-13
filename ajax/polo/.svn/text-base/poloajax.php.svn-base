<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../modulo/epolo/dao/poloDAO.php';

$poloDAO = new poloDAO();

$op = $_POST["operacao"];
$codUnidade = $_POST["codUnidade"];
$anoBase = $_POST["anoBase"];

$qtdvideo = isset($_POST['qtdvideoconferencia']) ? 1 : 0;
$qtdsala = isset($_POST['qtdsala']) ? 1 : 0;
$qtdmicro = isset($_POST['qtdmicro']) ? 1 : 0;
$qtdbanda = isset($_POST['qtdbanda']) ? 1 : 0;
$qtdsalatutores = isset($_POST['qtdsalatutores']) ? 1 : 0;

$dados = array('anoBase' => $anoBase,'codUnidade' => $codUnidade, 'qtdvideo' => $qtdvideo, 'qtdsala' => $qtdsala, 'qtdmicro' => $qtdmicro, 'qtdbanda' => $qtdbanda, 'qtdsalatutores' => $qtdsalatutores);


switch ($op){
	case "I":
		$poloDAO->insere($dados);
		break;
	case "A":
		$poloDAO->altera($dados);
		break;
}
?>

 

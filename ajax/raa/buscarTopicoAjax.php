<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../modulo/raa/dao/topicoDAO.php';

$raaDAO = new RaaDAO();

$codTopico = $_POST['codTitulo'];

$rows =  $raaDAO->buscarTopicoCod($codTopico);

foreach($rows as $row){
	$titulo = $row['titulo'];
	$situacao = $row['situacao'];
}

//buscar unidades do tópico
$arrayUni = array();
$count = 0;
$rowUT = $raaDAO->buscarUnidadesdoTopico($codTopico);
if($rowUT->rowCount()>0){
	foreach($rowUT as $rows){
		$arrayUni[$count] = $rows['codUnidade'];
		$count++;
	}
}


$dados = array("titulo" => $titulo, "situacao" => $situacao,"arrayUni"=>$arrayUni);

echo json_encode($dados);
?>
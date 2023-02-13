<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../modulo/raa/dao/modeloDAO.php';

$modeloDAO = new ModeloDAO();

$codTopico = $_POST['codTopico'];

$rowsModelos =  $modeloDAO->buscarModeloTopico($codTopico);

$html = "";

foreach($rowsModelos as $row){
	//Corpo da tabela para a ordenação
	if($row['situacao'] == 1){ $class = "ui-state-default success";}else{ $class = "ui-state-default danger";}
	$html .= "<tr data-name='".$row['codigo']."' class='".$class."'><td>".$row['ordemInTopico']."</td><td>".$row['legenda']."</td></tr>";
}
echo $html;
?>
<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../modulo/raa/dao/modeloDAO.php';

$modeloDAO = new ModeloDAO();

$codModelo = $_POST['codModelo'];

$modeloDAO->excluirmodelo($codModelo);

?>
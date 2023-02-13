<?php
require '../../classes/unidade.php';
require '../../dao/PDOConnectionFactory.php';
require '../../modulo/documentopdi/dao/DocumentoDAO.php';

session_start();
$docDAO = new DocumentoDAO();

$anoExtender = addslashes($_POST['ano']);
$codDoc = addslashes($_POST['codDoc']);

$docDAO->prorrogarPDU($anoExtender,$codDoc);


?>


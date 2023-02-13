<?php
session_start();
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

$sessao = $_SESSION["sessao"];

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../dao/usuarioDAO.php';
require_once '../../dao/unidadeDAO.php';
require_once '../../classes/usuario.php';
require_once '../../classes/unidade.php';
require_once('../../classes/sessao.php');


$usuarioDAO = new UsuarioDAO();

//Receber variáveis
$unidade = $_POST['nomeUnidade'];
$user = $_POST['userID'];

$dao=new UnidadeDAO();
$rows=$dao->buscarUnidadeByNome($unidade);

foreach ($rows as $r){
    $idUnidade = $r["CodUnidade"];
}

$usuarioDAO->defUnidadeUser($user, $idUnidade);
?>
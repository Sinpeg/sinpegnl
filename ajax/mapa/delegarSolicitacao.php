<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/usuario.php';
require_once '../../modulo/mapaestrategico/classes/Solicitacao.php';
require_once '../../modulo/indicadorpdi/classe/SolicitacaoVersaoIndicador.php';
require_once '../../modulo/indicadorpdi/dao/SolicitacaoVersaoIndicadorDAO.php';


$sol = new  SolicitacaoVersaoIndicador();
$sol->setCodigo($_POST['codSolicitacao']);
$u=new Usuario();
$u->setCodusuario($_POST['user']);
$sol->setUsuarioanalista($u);
$sol->setCodigo($_POST['codigo']);
$sol->setSituacao("G");
//echo $sol->getSituacao()+"  "+$sol->getCodigo();
$daoSol = new SolicitacaoVersaoIndicadorDAO();
$rowSol = $daoSol->delegar($sol);

?>
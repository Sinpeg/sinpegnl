<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../modulo/mapaestrategico/classes/Solicitacao.php';
require_once '../../modulo/indicadorpdi/classe/SolicitacaoVersaoIndicador.php';
require_once '../../modulo/indicadorpdi/dao/SolicitacaoVersaoIndicadorDAO.php';
require_once '../../modulo/mapaestrategico/classes/ComentarioSolicitPDU.php';
require_once '../../modulo/mapaestrategico/dao/ComentarioSolicitpduDAO.php';

$sol = new  SolicitacaoVersaoIndicador();
$sol->setCodigo($_POST['codSolicitacao']);
$comentario = new ComentarioSolicitPDU();
$comentario->setSolcitacao($sol);
$comentario->setTexto(addslashes($_POST['texto']));
$comentario->setAutor($_POST['user']);

if (!empty($comentario->getTexto())){
	$daoComentario = new ComentarioSolicitpduDAO();
	$daoComentario->insere($comentario);
}
?>
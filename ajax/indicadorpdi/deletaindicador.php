<?php
/* DAO */
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/sessao.php';
require_once '../../classes/unidade.php';
require_once '../../dao/unidadeDAO.php';
require_once '../../modulo/documentopdi/dao/DocumentoDAO.php';
require_once '../../modulo/mapaestrategico/dao/MapaDAO.php';
require_once '../../modulo/indicadorpdi/dao/IndicadorDAO.php';
require_once '../../modulo/indicadorpdi/dao/MapaIndicadorDAO.php';
require_once '../../modulo/metapdi/dao/MetaDAO.php';
/* Model */
require_once '../../modulo/documentopdi/classe/Documento.php';
require_once '../../modulo/mapaestrategico/classes/Mapa.php';
require_once '../../modulo/indicadorpdi/classe/Indicador.php';
require_once '../../modulo/metapdi/classe/Meta.php';
session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
$codunidade = $sessao->getCodUnidade();
$daoindicador = new IndicadorDAO();
$action = $_POST['action'];

/*$rows = $daomapaindicador->buscamapa($codigo, $codmapa, $codunidade);
foreach ($rows as $row)
{
	$codmapaindicador = $row['codigo'];
}
*/
$codindicador=null;
$codindicador =  $_POST['codindicador'];//codigo do mapa indicador

if($action == 'D' && $codindicador != null){
	$daoindicador->deleta($codindicador);
	$message = "Indicador retirado com sucesso!";
	print "<br><img src='webroot/img/accepted.png' width='30' height='30'/>"; print $message;
}else{
	$message = "falha ao deletar o indicador";
	print "<br><img src='webroot/img/error.png' width='30' height='30'/>"; print $message;
}



?>
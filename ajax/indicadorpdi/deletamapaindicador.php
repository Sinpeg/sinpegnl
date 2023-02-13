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
//$codmapa = $_SESSION['codmapa'];
$aplicacoes = $sessao->getAplicacoes();
$codunidade = $sessao->getCodUnidade();
$daomapaindicador = new mapaIndicadorDAO();
$action = $_POST['action'];
/*$rows = $daomapaindicador->buscamapa($codigo, $codmapa, $codunidade);
foreach ($rows as $row)
{
	$codmapaindicador = $row['codigo'];
}
*/
$codmapaindicador=null;
$codmapaindicador =  $_POST['codindicador'];//codigo do mapa indicador
if($action == 'D' && $codmapaindicador != null){
	$retorno = $daomapaindicador->deleta($codmapaindicador);
	if ($retorno==1){
	   $message = "Indicador retirado com sucesso!";
	   echo "<br><img src='webroot/img/accepted.png' width='30' height='30'/>"; echo $message;
	}
}else{
	$message = "Falha ao deletar o indicador!";
	echo "<br><img src='webroot/img/error.png' width='30' height='30'/>"; echo $message;
}



?>
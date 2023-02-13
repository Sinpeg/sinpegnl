<?php 
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/sessao.php';
require_once '../../modulo/mapaestrategico/dao/MapaDAO.php';
require_once '../../modulo/indicadorpdi/dao/IndicadorDAO.php';
require_once '../../modulo/indicadorpdi/dao/MapaIndicadorDAO.php';
require_once '../../modulo/indicadorpdi/dao/SolicitacaoVersaoIndicadorDAO.php';

session_start();
$sessao = $_SESSION["sessao"];
if (!isset($sessao)) {
 echo "Sessão expirou...";
 exit(0);
} else {
 $aplicacoes = $sessao->getAplicacoes();
 if (!$aplicacoes[55]) { 
 echo "Você não tem permissão para acessar esta operação!";
 exit(0);
 }
}

//verificar se existe alguma solicitação para o indicador no respectivo anobase
$daoSol = new SolicitacaoVersaoIndicadorDAO();
$rowSol = $daoSol->buscaSolicitacaoPorMapaindAno($_POST['mapaInd'], $_POST['ano']);

if ($rowSol->rowCount()>0) {
	$aux=1;
}else{
	$aux=0;
}

$daoMapaInd = new MapaIndicadorDAO();
$rowMapaInd = $daoMapaInd->buscaMapaIndicador($_POST['mapaInd']);

foreach ($rowMapaInd as $row){
	$codInd = $row['codIndicador'];
}

$daoInd = new IndicadorDAO();
$rowInd = $daoInd->buscaindicador($codInd);

foreach ($rowInd as $row){
	$nomeInd = $row['nome'];
	$formulaInd = $row['calculo'];
	$interpretacaoInd = $row['interpretacao'];
}

echo json_encode(array('nome'  => $nomeInd,'formula'  => $formulaInd,'interpretacao'  => $interpretacaoInd,'codInd' => $codInd,'possui' => $aux));
?>
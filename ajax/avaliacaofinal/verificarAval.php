<?php

require '../../classes/unidade.php';
require '../../dao/PDOConnectionFactory.php';
require '../../classes/sessao.php';
require '../../classes/Controlador.php';
require '../../modulo/avaliacao/dao/AvaliacaofinalDAO.php';
require '../../modulo/calendarioPdi/dao/CalendarioDAO.php';
require '../../modulo/calendarioPdi/classes/Calendario.php';
require '../../modulo/avaliacao/classe/Avaliacaofinal.php';
require '../../modulo/documentopdi/dao/DocumentoDAO.php';
require '../../modulo/documentopdi/classe/Documento.php';
require '../../modulo/indicadorpdi/dao/IndicadorDAO.php';
require '../../modulo/mapaestrategico/dao/MapaDAO.php';
require '../../util/Utils.php';

$codDocumento = addslashes($_POST['codDocumento']);
$codCalendario = addslashes($_POST['codCalendario']);
$avaliacao = addslashes($_POST['avaliacaofinal']);
$ARAT = addslashes($_POST['userfile']);


$daoindi = new IndicadorDAO();
$daomapa = new MapaDAO();
$querymapa = $daomapa->buscaMapaByDocumento($codDocumento);

$cont = 0;
$cont2 = 0;
$cont3 = 0;
foreach ($querymapa as $mapa){
	$arraymapa[$cont++] = $mapa;
	$queryindi = $queryindic=$daoindi->buscaindicadorpormapa($mapa['Codigo']);
	
	foreach ($queryindi as $indicad){
		if ($indicad['Codigo'] != null and $indicad['meta'] != null and $indicad['meta_atingida'] == null){
			$arrayindi[$cont2++] = $indicad['nome'];
		}
		if($indicad['Codigo'] != null and $indicad['situacao'] == null){
			$arrayini[$cont3++]=$indicad['nomeini'];
		}
	}
}


if($arrayindi or $arrayini){

	if($arrayindi){
	
		$arrayindi = array_unique($arrayindi);
		$msg = "Os indicadores:";
		foreach ($arrayindi as $ind){
			$msg = $msg." ".$ind.",";
		}
		$msg = $msg." .Estão sem resultados para suas metas.";
	}
	
	if($arrayini){
		$arrayini = array_unique($arrayini);
		$msg = $msg." As Iniciativas:";
		foreach ($arrayini as $ini){
			$msg = $msg." ".$ini.",";
		}
		$msg = $msg." .Estão sem resultados.";
	
	}

	
	echo $msg;
}

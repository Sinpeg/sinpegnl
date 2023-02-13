<?php

//obt�m dados do sisraa para o censo
require_once('dao/bibliocensoDAO.php');
require_once('classes/biblicenso.php');


class FachBCenso {
	
function retBibliocenso($be, $anobase){
$daobi = new BibliocensoDAO();
//$lock = new Lock(); // trecho de bloqueio
$rows = $daobi->buscaBibli($be->getIdBibliemec(), $anobase);

foreach ($rows as $row) {
	$be->criaBiblicenso($row['idBiblicenso'], $row['nAssentos'], $row['nEmpDomicilio'], $row['nEmpBiblio'], $row['frequencia'],
			$row['nConsPresencial'], $row['nConsOnline'], $row['fBuscaIntegrada'], $row['comutBibliog'], $row['servInternet'],
			$row['nUsuariosTpc'], $row['redeSemFio'], $row['partRedeSociais'], $row['nItensAcervoElet'], $row['nItensAcervoImp'],
			$row['atendTreiLibras'],$row['acervoFormEspecial'], $row['appFormEspecial'], $row['planoFormEspecial'], $row['sofLeitura'],
			$row['tecVirtual'], $row['impBraile'], $row['portalCapes'],$row['outrasBases'], $row['bdOnlineSerPub'],
			$row['catOnlineSerPub'], $row['ano']);
	
}
return $be;
}

function retBibliocensoId($id){
	$daobi = new BibliocensoDAO();
$bi=null;
	//$lock = new Lock(); // trecho de bloqueio
	$rows = $daobi->buscaBibliId($id);
	foreach ($rows as $row) {
		$bi = new Biblicenso();
		$bi->setNassentos( $row['nAssentos']);
		$bi->setIdBiblicenso($row['idBiblicenso']);
		$bi->setNempDomicilio($row['nEmpDomicilio']);
		$bi->setNempBiblio($row['nEmpBiblio']);
		$bi->setFrequencia($row['frequencia']);
		$bi->setNconsPresencial($row['nConsPresencial']);
	    $bi->setNconsOnline( $row['nConsOnline']);
	    $bi->setFbuscaIntegrada($row['fBuscaIntegrada']);
	    $bi->setComutBibliog($row['comutBibliog']);
	    $bi->setServInternet( $row['servInternet']);
	    $bi->setNusuariosTpc($row['nUsuariosTpc']);
	    $bi->setRedeSemFio( $row['redeSemFio']);
	    $bi->setPartRedeSociais( $row['partRedeSociais']);
	    $bi->setNitensAcervoElet($row['nItensAcervoElet']);
	    $bi->setNitensAcervoImp( $row['nItensAcervoImp']);
		$bi->setAtendTreiLibras(	$row['atendTreiLibras']);
		$bi->setAcervoFormEspecial($row['acervoFormEspecial']);
		$bi->setAppFormEspecial( $row['appFormEspecial']);
		$bi->setPlanoFormEspecial( $row['planoFormEspecial']);
		$bi->setSofLeitura( $row['sofLeitura']);
		$bi->setTecVirtual(	$row['tecVirtual']);
		$bi->setImpBraile( $row['impBraile']);
		$bi->setPortalCapes( $row['portalCapes']);
		$bi->setOutrasBases($row['outrasBases']);
		$bi->setBdOnlineSerPub( $row['bdOnlineSerPub']);
		$bi->setCatOnlineSerPub($row['catOnlineSerPub']);
		$bi->setAno( $row['ano']);
		
	}
	return $bi;
}

function insere($be){
	$daobi = new BibliocensoDAO();
	$daobi->insere($be);
}

function altera($be){
	echo "Altera";
	$daobi = new BibliocensoDAO();
	$daobi->altera($be);
}
}
?>

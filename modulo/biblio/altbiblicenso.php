<?php
ob_start();

$sessao = $_SESSION["sessao"];
$nomeUnidade = $sessao->getNomeunidade();
$anobase = $sessao->getAnobase();
$hierarquia = $sessao->getCodestruturado();

echo "<legend>Censo ".$anobase."</legend>";

if(strpos($nomeUnidade, "BIBLI") !== false){
	include 'altbiblicensoSub.php';
}else{
	include 'altbiblicensoUni.php';
}		


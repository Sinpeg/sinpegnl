<?php
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[10])
{
	header("Location:index.php");
}
else {
require_once('classes/acessibilidade.php');
require_once('dao/acessibilidadeDAO.php');
require_once('classes/tpacessibilidade.php');
require_once('dao/tpacessibilidadeDAO.php');
require_once ('./classes/unidade.php');

$unidade = new Unidade();
$unidade->setCodunidade($sessao->getCodUnidade());
$codUnidade=$unidade->getCodunidade();
$qtdArquitetonica = $_POST["qtdArquitetonica"];
$qtdMobiliario = $_POST["qtdMobiliario"];
$qtdRampas = $_POST["qtdRampas"];
$qtdBanheiros = $_POST["qtdBanheiros"];
$qtdEquipamentos = $_POST["qtdEquipamentos"];

$passou1 = false;
if ($anobase <= 2012) {
    if (is_numeric($qtdArquitetonica) && $qtdArquitetonica != "" && is_numeric($qtdMobiliario) && $qtdMobiliario != "" && is_numeric($qtdRampas) && $qtdRampas != "" && is_numeric($qtdBanheiros) && $qtdBanheiros != "" && is_numeric($qtdEquipamentos) && $qtdEquipamentos != "") {
        $tipoEA = array();
        $tipoEA["AR"] = $qtdArquitetonica;
        $tipoEA["MA"] = $qtdMobiliario;
        $tipoEA["RA"] = $qtdRampas;
        $tipoEA["BA"] = $qtdBanheiros;
        $tipoEA["EE"] = $qtdEquipamentos;
        $passou1 = true;
    }
}
if ($anobase >= 2013) {
    $qtdEs = is_null($_POST["qtdEs"]) ? 0 : 1;
    $qtdAd = is_null($_POST["qtdAd"]) ? 0 : 1;
    $qtdBl = is_null($_POST["qtdBl"]) ? 0 : 1;
    $qtdSs = is_null($_POST["qtdSs"]) ? 0 : 1;
    $qtdSv = is_null($_POST["qtdSv"]) ? 0 : 1;
    $qtdAt = is_null($_POST["qtdAt"]) ? 0 : 1;
    $tipoEA = array();
    $tipoEA["A01"] = is_null($qtdArquitetonica) ? 0 : 1;
    $tipoEA["A02"] = is_null($qtdRampas) ? 0 : 1;
    $tipoEA["A03"] = $qtdEs;
    $tipoEA["A04"] = $qtdAd;
    $tipoEA["A05"] = $qtdBl;
    $tipoEA["A06"] = $qtdSs;
    $tipoEA["A07"] = $qtdSv;
    $tipoEA["A08"] = is_null($qtdEquipamentos) ? 0 : 1;
    $tipoEA["A09"] = is_null($qtdBanheiros) ? 0 : 1;
    $tipoEA["A10"] = $qtdAt;
    $tipoEA["A11"] = is_null($qtdMobiliario) ? 0 : 1;
    
    $passou1 = true;
}
$tEA=array();
if ($passou1) {
    $cont = 0;
    $daoea = new AcessibilidadeDAO();

    foreach ($tipoEA as $i => $tpEA) {
   	 
        $cont++;
        $tEA[$cont] = new Tpacessibilidade();
        $tEA[$cont]->setCodigo($i);
        $consulta = $daoea->buscaea($codUnidade, $anobase, $tEA[$cont]->getCodigo());
        $passou = false;
        foreach ($consulta as $row) {
            $passou = true;
            $tEA[$cont]->criaAcessib($row["CodigoEstrutura"], $unidade, $anobase, $tpEA);
        }//for
        if (!$passou) {
            $tEA[$cont]->criaAcessib(null, $unidade, $anobase, $tpEA);
        }
    }
  
    
    if (!$passou) {
        $daoea->inseretodos($tEA);
    } else {
        $daoea->alteratodos($tEA);
    }
      
    $daoea->fechar();
    Flash::addFlash('Estruturas de acessibilidade foram salvas com sucesso!');
    Utils::redirect('acessib', 'consultaacess');
// exit();
} else {
// $mensagem = urlencode(" ");
    Error::addErro('Erro ao salvar estruturas de acessibilidades. Por favor, tente novamente!');
    Utils::redirect('acessib', 'consultaacess');
// exit();
}
}?>

 

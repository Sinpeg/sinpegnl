<?php

//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('classes/eapimetodologicas.php');
require_once('dao/eapimetodologicasDAO.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[28]) {
    header("Location:index.php");
} 
$qtdExe = $_POST["qtdExe"];
$qtdTra = $_POST["qtdTra"];
$qtdCan = $_POST["qtdCan"];
$qtdSusp = $_POST["qtdSusp"];
$qtdConc = $_POST["qtdConc"];
$qtdDoc = $_POST["qtdDoc"];
$qtdTec = $_POST["qtdTec"];
$qtdBols = $_POST["qtdBols"];
$qtdNBols = $_POST["qtdNBols"];
$qtdPos = $_POST["qtdPos"];
$qtdOutras = $_POST["qtdOutras"];
$operacao = $_POST["operacao"];

//$sessao = $_SESSION["sessao"];
//$nomeUnidade = $sessao->getNomeunidade();
//$codUnidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
//$aplicacoes = $_SESSION["sessao"]->getAplicacoes();
//if (!$aplicacoes[28]) {
//    $mensagem = urlencode(" ");
//    $cadeia = "location:../saida/erro.php?codigo=2&mensagem=" . $mensagem;
//    header($cadeia);
//    exit();
//}
if (is_numeric($qtdExe) && $qtdExe != "" && is_numeric($qtdTra) && $qtdTra != "" && is_numeric($qtdCan) && is_numeric($qtdSusp) && $qtdSusp != "" && is_numeric($qtdConc) && $qtdConc != "" && is_numeric($qtdDoc) && $qtdDoc != "" && is_numeric($qtdTec) && $qtdTec != "" && is_numeric($qtdBols) && $qtdBols != "" && is_numeric($qtdNBols) && $qtdNBols != "" && is_numeric($qtdPos) && $qtdPos != "" && is_numeric($qtdOutras) && $qtdOutras != "") {
    $daopim = new EApimetodologicasDAO();
    $pimetodologicas = new EApimetodologicas();
    $pimetodologicas->setExecucao($qtdExe);
    $pimetodologicas->setTramitacao($qtdTra);
    $pimetodologicas->setCancelado($qtdCan);
    $pimetodologicas->setSuspenso($qtdSusp);
    $pimetodologicas->setConcluido($qtdConc);
    $pimetodologicas->setDocentes($qtdDoc);
    $pimetodologicas->setTecnicos($qtdTec);
    $pimetodologicas->setBolsistas($qtdBols);
    $pimetodologicas->setNBolsistas($qtdNBols);
    $pimetodologicas->setPosgraduao($qtdPos);
    $pimetodologicas->setOutras($qtdOutras);
    $pimetodologicas->setAno($anobase);

    if ($operacao == "I") {
        $daopim->Insere($pimetodologicas);
    } else {
        $codigo = $_POST["codigo"];
        if (is_numeric($codigo)) {
            $pimetodologicas->setCodigo($codigo);
            $daopim->altera($pimetodologicas);
        }
    }
    $daopim->fechar();
    Utils::redirect('eapimetodologicas', 'consultapimetodologicas');
//        $cadeia = "location:.php";
//	header($cadeia);
//	exit();
} else {
    $mensagem = urlencode(" ");
    $cadeia = "location:../saida/erro.php?codigo=1&mensagem=" . $mensagem;
    header($cadeia);
//	exit();
}
//ob_end_flush();
?>
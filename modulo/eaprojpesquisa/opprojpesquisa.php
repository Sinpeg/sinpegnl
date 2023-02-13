<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('classes/eaprojpesquisa.php');
require_once('dao/eaprojpesquisaDAO.php');
//session_start();
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[20]) {
    header("Location:index.php");
}
//$sessao = $_SESSION["sessao"];
//$nomeUnidade = $sessao->getNomeunidade();
//$codUnidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
$qtdExe = $_POST["qtdExe"];
$qtdTra = $_POST["qtdTra"];
$qtdCan = $_POST["qtdCan"];
$qtdSusp = $_POST["qtdSusp"];
$qtdConc = $_POST["qtdConc"];
$qtdDoc = $_POST["qtdDoc"];
$qtdTec = $_POST["qtdTec"];
$qtdDisc = $_POST["qtdDisc"];
$qtdOutras = $_POST["qtdOutras"];
$operacao = $_POST["operacao"];

if (is_numeric($qtdExe) && $qtdExe != "" && is_numeric($qtdTra) && $qtdTra != "" && is_numeric($qtdCan) && $qtdCan != "" && is_numeric($qtdSusp) && $qtdSusp != "" && is_numeric($qtdConc) && $qtdConc != "" && is_numeric($qtdDoc) && $qtdDoc != "" && $qtdTec != "" && is_numeric($qtdTec) && is_numeric($qtdDisc) && $qtdDisc != "" && is_numeric($qtdOutras) && $qtdOutras != "") {
    $daopp = new EAprojpesquisaDAO();

    $projpesquisa = new EAprojpesquisa();
    $projpesquisa->setExecucao($qtdExe);
    $projpesquisa->setTramitacao($qtdTra);
    $projpesquisa->setCancelado($qtdCan);
    $projpesquisa->setSuspenso($qtdSusp);
    $projpesquisa->setConcluido($qtdConc);
    $projpesquisa->setDocentes($qtdDoc);
    $projpesquisa->setTecnicos($qtdTec);
    $projpesquisa->setDiscentes($qtdDisc);
    $projpesquisa->setOutras($qtdOutras);
    $projpesquisa->setAno($anobase);


    if ($operacao == "I") {
        $daopp->Insere($projpesquisa);
    } else {
        $codigo = $_POST["codigo"];
        if (is_numeric($codigo)) {
            $projpesquisa->setCodigo($codigo);
            $daopp->altera($projpesquisa);
        } else {
            $mensagem = urlencode(" ");
            $cadeia = "location:../saida/erro.php?codigo=1&mensagem=" . $mensagem;
            header($cadeia);
            //exit();
        }
    }

    $daopp->fechar();
    $status = ($operacao=="A")?"atualizados":"cadastrados";
    Flash::addFlash("Dados ".$status." com sucesso!");
    Utils::redirect('eaprojpesquisa', 'consultapesquisa'); 
} else {
    $mensagem = urlencode(" ");
    $cadeia = "location:../saida/erro.php?codigo=1&mensagem=" . $mensagem;
    header($cadeia);
    //exit();
}
?>
<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('classes/eaprojextensao.php');
require_once('dao/eaprojextensaoDAO.php');
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[25]) {
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

$sessao = $_SESSION["sessao"];
//$nomeUnidade = $sessao->getNomeunidade();
//$codUnidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();

if (is_numeric($qtdExe) && $qtdExe != "" && is_numeric($qtdTra) && $qtdTra != "" && is_numeric($qtdCan) && is_numeric($qtdSusp) && $qtdSusp != "" && is_numeric($qtdConc) && $qtdConc != "" && is_numeric($qtdDoc) && $qtdDoc != "" && is_numeric($qtdTec) && $qtdTec != "" && is_numeric($qtdBols) && $qtdBols != "" && is_numeric($qtdNBols) && $qtdNBols != "" && is_numeric($qtdPos) && $qtdPos != "" & is_numeric($qtdOutras) && $qtdOutras != "") {
    $daope = new EAprojextensaoDAO();
    $projextensao = new EAprojextensao();
    $projextensao->setExecucao($qtdExe);
    $projextensao->setTramitacao($qtdTra);
    $projextensao->setCancelado($qtdCan);
    $projextensao->setSuspenso($qtdSusp);
    $projextensao->setConcluido($qtdConc);
    $projextensao->setDocentes($qtdDoc);
    $projextensao->setTecnicos($qtdTec);
    $projextensao->setBolsistas($qtdBols);
    $projextensao->setNBolsistas($qtdNBols);
    $projextensao->setPosgraduao($qtdPos);
    $projextensao->setOutras($qtdOutras);
    $projextensao->setAno($anobase);


    if ($operacao == "I") {
        $daope->Insere($projextensao);
    } else {
        $codigo = $_POST["codigo"];
        if (is_numeric($codigo)) {
            $projextensao->setCodigo($codigo);
            $daope->altera($projextensao);
        }
    }

    $daope->fechar();
    Utils::redirect('eaprojextensao', 'consultaextensao');
    
//    $cadeia = "location:consultaextensao.php";
//    header($cadeia);
    //exit();
} else {
    $mensagem = urlencode(" ");
    $cadeia = "location:../saida/erro.php?codigo=1&mensagem=" . $mensagem;
    header($cadeia);
    //exit();
}
ob_end_flush();
?>
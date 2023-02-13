<?php

//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('classes/micros.php');
require_once ('dao/microsDAO.php');
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[6]) {
    header("Location:index.php");
}
$qtdAca = $_POST["qtdAca"];
$qtdAcai = $_POST["qtdAcai"];

$qtdAdm = $_POST["qtdAdm"];
$qtdAdmi = $_POST["qtdAdmi"];

$operacao = $_POST["operacao"];

//$sessao = $_SESSION["sessao"];
$nomeUnidade = $sessao->getNomeunidade();
$codUnidade = $sessao->getCodunidade();
$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
//require_once('../../includes/classes/unidade.php');
$unidade = new Unidade();
$unidade->setCodunidade($codUnidade);
$unidade->setNomeunidade($nomeUnidade);

$daom = new microsDAO();
if ($qtdAca != "" && is_numeric($qtdAca) && $qtdAdm != "" && is_numeric($qtdAdm) &&
        $qtdAcai != "" && is_numeric($qtdAcai) && $qtdAdmi != " " && is_numeric($qtdAdmi)) {
    if ($operacao == "I") {
        $unidade->criaMicros(null, $qtdAca, $qtdAcai, $qtdAdm, $qtdAdmi, $anobase);
        $daom->Insere($unidade->getMicros());
    } elseif ($operacao == "A") {
        $codigo = $_POST["codigo"];
        if ($codigo != "" && is_numeric($codigo)) {
            $unidade->criaMicros($codigo, $qtdAca, $qtdAcai, $qtdAdm, $qtdAdmi, $anobase);
            $daom->altera($unidade->getMicros());
        } else {
            $daom->fechar();
            $mensagem = urlencode(" ");
            $cadeia = "location:../saida/erro.php?codigo=1&mensagem=" . $mensagem;
            header($cadeia);
            exit();
        }
    }
} else {
    $daom->fechar();
//    $mensagem = urlencode(" ");;
    $cadeia = "location:../saida/erro.php?codigo=1&mensagem=" . $mensagem;;
    header($cadeia);;
//    exit();
}
$daom->fechar();
Flash::addFlash('Dados atualizados com sucesso!');
Utils::redirect('micros', 'consultamicros');
//$cadeia = "location:consultamicros.php";
//header($cadeia);
//exit();
//ob_end_flush();
?>
<?php

//require_once('../../includes/classes/sessao.php');
//require_once('../../includes/dao/PDOConnectionFactory.php');
    require_once('classes/tppremios.php');
    require_once ('dao/tppremiosDAO.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[5]) {
    header("Location:index.php");
} 

$nomeitempi = $_POST["itempi"];
$operacao = $_POST["operacao"];
//$sessao = $_SESSION["sessao"];
//$nomeUnidade = $sessao->getNomeunidade();
//$codUnidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
//$anobase = $sessao->getAnobase();

$dao = new TppremiosDAO();
if (is_string($nomeitempi) && $nomeitempi != "") {
    if ($operacao == "I") {
        $itempi = new Tppremios();
        $itempi->setCodpremio(null);
        $itempi->setNome($nomeitempi);
        $dao->Insere($itempi);
        Flash::addFlash('Dados cadastrados com sucesso!');
    } elseif ($operacao == "A") {
        $codigo = $_POST["codigo"];
        if (is_numeric($codigo) && $codigo != "") {
            $itempi = new Tppremios();
            $itempi->setCodPremio($codigo);
            $itempi->setNome($nomeitempi);
            $dao->altera($itempi);
        }
        Flash::addFlash('Dados atualizados com sucesso!');
    }

    $dao->fechar();
}
Utils::redirect('premios', 'consultaitem');
//$cadeia = "location:consultaitempi.php";
//header($cadeia);
//exit();
//ob_end_flush();
?>
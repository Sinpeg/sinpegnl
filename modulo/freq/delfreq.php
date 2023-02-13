<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[17]) {
    header("Location:index.php");
} else {
    $sessao = $_SESSION["sessao"];
//    $nomeunidade = $sessao->getNomeunidade();
//    $codunidade = $sessao->getCodunidade();
//    $responsavel = $sessao->getResponsavel();
//    $anobase = $sessao->getAnobase();

    $codigo = $_GET["codigo"];
    if (is_numeric($codigo) && $codigo != "") {
//        require_once('../../includes/dao/PDOConnectionFactory.php');
        require_once('dao/freqfarmaciaDAO.php');
        require_once('classes/freqfarmacia.php');

        $daoin = new FreqfarmaciaDAO();
        $daoin->deleta($codigo);
        $daoin->fechar();
    }
    Utils::redirect('freq', 'consultafreq');
//    $cadeia = "location:consultafreq.php";
//    header($cadeia);
//    ob_end_flush();
}
?>



<?php

//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[26]) {
    header("Location:index.php");
}else {
//    $sessao = $_SESSION["sessao"];
//	$nomeunidade = $sessao->getNomeunidade();
//	$codunidade = $sessao->getCodunidade();
//	$responsavel = $sessao->getResponsavel();
//	$anobase = $sessao->getAnobase();
//    $aplicacoes = $_SESSION["sessao"]->getAplicacoes();
//    if (!$aplicacoes[26]) {
//        $mensagem = urlencode(" ");
//        $cadeia = "location:../saida/erro.php?codigo=2&mensagem=" . $mensagem;
//        header($cadeia);
////		exit();
//    }
    $codigo = $_GET["codigo"];
    if (is_numeric($codigo) && $codigo != "") {
//		require_once('../../includes/dao/PDOConnectionFactory.php');
        // var_dump($usuario);
        require_once('dao/edprofissionallivreDAO.php');
        require_once('classes/edprofissionallivre.php');
        require_once('classes/tdmedprofissionallivre.php');
        require_once('dao/tdmedprofissionallivreDAO.php');
        $daoin = new EdprofissionallivreDAO();
        $daoin->deleta($codigo);
        $daoin->fechar();
    }
    Utils::redirect('cledprofissional', 'conscleducprof');
//    $cadeia = "location:conscleducprof.php";
//    header($cadeia);
//exit();
//ob_end_flush();
}
?>



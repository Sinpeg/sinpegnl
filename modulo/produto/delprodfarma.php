<?php

//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[16]) {
    header("Location:index.php");
} else {
    $sessao = $_SESSION["sessao"];
//	$nomeunidade = $sessao->getNomeunidade();
//	$codunidade = $sessao->getCodunidade();
//	$responsavel = $sessao->getResponsavel();
//	$anobase = $sessao->getAnobase();
//	require_once('../../includes/dao/PDOConnectionFactory.php');
    // var_dump($usuario);
    require_once('dao/prodfarmaciaDAO.php');
    require_once('classes/prodfarmacia.php');
    $codigo = $_GET['codigo'];
    if ($codigo != "" && is_numeric($codigo)) {
        $dao = new ProdfarmaciaDAO();
        $dao->deleta($codigo);
        $dao->fechar();
        Utils::redirect('produto', 'consultapfarma');
        //exit();
    }
//ob_end_flush();
}
?>



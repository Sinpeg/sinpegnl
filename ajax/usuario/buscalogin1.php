<?php

//require_once('../../includes/classes/sessao.php');
require_once '../../classes/sessao.php';
/*
 * Verifica se o login a ser inserido pelo admin j� existe
 */
session_start();
if (!isset($_SESSION["sessao"])) {
    header("location:../../index.php");
    exit();
} else {
    $sessao = $_SESSION["sessao"];
    $nomeunidade = $sessao->getNomeunidade();
    $codunidade = $sessao->getCodunidade();
    $responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();
    $aplicacoes = $sessao->getAplicacoes();
    $loginsessao = $sessao->getLogin();
    if (!$aplicacoes[3] && !$aplicacoes[23]) {
        $mensagem = urlencode(" ");
        $cadeia = "location:../saida/erro.php?codigo=2&mensagem=" . $mensagem;
        header($cadeia);
        exit();
    }
    require_once '../../dao/PDOConnectionFactory.php';
    require_once '../../dao/usuarioDAO.php';
    require_once '../../classes/unidade.php';
    require_once '../../classes/usuario.php';
//	require_once('../../includes/dao/PDOConnectionFactory.php');
//	require_once('../../includes/dao/usuarioDAO.php');
//	require_once('../../includes/classes/usuario.php');

    $dao = new UsuarioDAO();
    $parametro = addslashes($_POST["parametro"]);
    if ($parametro == "") {
        $display = "Preencha o campo usu&aacute;rio!";
        echo $display;
    } elseif (is_string($parametro)) {
        $rows = $dao->buscaLogin($parametro);
        $passou = false;
        foreach ($rows as $row) {
            $passou = true;
        }

        $dao->fechar();
        if ($passou) {
            $display = "Login j&aacute; est&aacute; sendo utilizado por outro usu&aacute;rio!";
            echo $display;
        } else {
            $display = "";
            echo $display;
        }
    }
}
ob_end_flush();
?>

<?php
//require_once('../../includes/classes/sessao.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[12]) {
    header("Location:index.php");
} 
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
$codcurso = $_GET["codcurso"];
$codlibra = $_GET["codlibra"];
//require_once('../../includes/dao/PDOConnectionFactory.php');
//// var_dump($usuario);
//require_once('../../includes/dao/cursoDAO.php');
//require_once('../../includes/classes/curso.php');
//require_once('../../includes/classes/unidade.php');
require_once('dao/librasDAO.php');
require_once('classes/libras.php');


if (is_numeric($codcurso) && $codcurso != "" && is_string($codlibra) && $codlibra != "") {
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);

    $daolibras = new LibrasDAO();

    $rows = $daolibras->buscaLibras($codlibra);
    foreach ($rows as $row) {
        $unidade->adicionaItemCursosLibras(null, null, $codcurso, null, null, null, $codlibra, $anobase);
    }

    foreach ($unidade->getCursos() as $libr) {
        $daolibras->deleta($libr);
    }
    $daolibras->fechar();
}
Utils::redirect('libras', 'consultalibra');
?>



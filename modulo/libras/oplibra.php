<?php
//require_once('../../includes/classes/sessao.php');;
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[12]) {
    header("Location:index.php");
} 
//$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('dao/librasDAO.php');
require_once('classes/libras.php');
//require_once('../../includes/classes/curso.php');
//require_once('../../includes/dao/cursoDAO.php');
//require_once('../../includes/classes/unidade.php');
$daolibras = new LibrasDAO();
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
//var_dump($unidade);
$cont = 0;
$clibras = $_POST["cursos"];
if (is_array($clibras)) {
    foreach ($clibras as $key => $val) {
        if (is_numeric($val)) {
            $unidade->adicionaItemCursosLibras(null, null, $val, null, null, null, null, $anobase);
        }
    }

    $daolibras->inseretodos($unidade->getCursos());
}

$daolibras->fechar();
Utils::redirect('libras', 'consultalibra');
//$cadeia = "location:consultalibra.php";
//header($cadeia);;
//exit();
//ob_end_flush();
?>


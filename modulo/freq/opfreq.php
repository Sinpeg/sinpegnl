<?php
$sessao = $_SESSION["sessao"];
//$nomeunidade = $sessao->getNomeunidade();
$login = $sessao->getLogin();
if ($login == "admin")
    $codunidade = 270;
else
    $codunidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
$aplicacoes = $_SESSION["sessao"]->getAplicacoes();
if (!$aplicacoes[17]) {
    header("Location:index.php");
//	exit();
}

//require_once('../../includes/dao/PDOConnectionFactory.php');
// var_dump($usuario);

require_once('dao/freqfarmaciaDAO.php');
require_once('classes/freqfarmacia.php');
$di = $_POST['di'];
$doc = $_POST['doc'];
$pq = $_POST['pq'];
$nv = $_POST['nv'];
$mes = $_POST['mes'];
$tipofreq = array();
if (is_numeric($mes) && $mes != "" && $di != " " && $di != "A-Z,a-z" && $doc != " " && $doc != "A-Z,a-z" && $pq != " " && $pq != "A-Z,a-z" && $nv != " " && $nv != "A-Z,a-z") {
    $cont = 0;
    $daofreq = new FreqfarmaciaDAO();
    $cont++;
    $f = new Freqfarmacia();
    $f->setCodigo(null);
    $f->setMes($mes);
    $f->setNalunos($di);
    $f->setNprofessores($doc);
    $f->setNvisitantes($nv);
    $f->setNpesquisadores($pq);
    $f->setAno($anobase);
    $rows = $daofreq->buscaporanomes($anobase, $mes);
    $passou = false;
    foreach ($rows as $row) {
        $passou = true;
        $codigo = $row['Codigo'];
        $f->setCodigo($codigo);
    }
    if (!$passou) {
        $daofreq->insere($f);
    } else {
        $daofreq->altera($f);
    }
    $daofreq->fechar();
}
Utils::redirect('freq', 'consultafreq');
//$cadeia = "location:consultafreq.php";
//header($cadeia);
//exit();
//ob_end_flush();
?>


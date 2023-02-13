<?php
//ob_start();
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[7]) {
 header("Location:index.php");
}
//$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
//$anobase = $sessao->getAnobase();
//require_once('../../includes/dao/PDOConnectionFactory.php');
// var_dump($usuario);
//require_once('../../includes/classes/curso.php');
//require_once('../../includes/classes/unidade.php');;
//require_once('../../includes/classes/campus.php');
require_once('classes/tplaboratorio.php');
require_once('dao/laboratorioDAO.php');
require_once('classes/laboratorio.php');
require_once('dao/labcursoDAO.php');
require_once('classes/labcurso.php');
$unidade = new Unidade();
$unidade->setCodUnidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$codcurso = $_POST["curso"];
$codlab = $_POST["codlab"];
$campus = new Campus();
$campus->setCodigo($_POST["campus"]);
$daolc = new LabcursoDAO();
$passou = 0;
$daolab = new LaboratorioDAO();
$rows = $daolc->buscaCursoLaboratorio($codcurso, $codlab);
foreach ($rows as $row) {
 $passou = 1;
}
if ($passou == 0) {
 $curso = $unidade->criaCurso($campus, null, $codcurso, null, null, null);
 $daolab = new LaboratorioDAO();
 $rows = $daolab->buscaLaboratorio($codlab);
 foreach ($rows as $row) {
 $tipolab = new Tplaboratorio();
 $tipolab->setCodigo($row['Tipo']);
 $lab = $tipolab->criaLab($codlab, $unidade, $row['Nome'], $row['Capacidade'], $row['Sigla'], null, null, null, null, null, null, null, null, null, null);
 }
 $labcurso = $lab->criaLabcurso(null, $curso);
 $daolc->insere($labcurso);
}
$daolc->fechar();
$cadeia = "Location:" . Utils::createLink('labor', 'conslabcurso1', array('codlab' => $codlab));
header($cadeia);
?>

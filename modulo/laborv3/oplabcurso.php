<?php
//ob_start();
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[7]) {
 exit();
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
//$campus = new Campus();
//$campus->setCodigo($_POST["campus"]);
//$campus->setCodigo(NULL);

$daolc = new LabcursoDAO();
$passou = 0;
$daolab = new LaboratorioDAO();
$rows = $daolc->buscaCursoLaboratorio($codcurso, $codlab);
foreach ($rows as $row) {
 $passou = 1;
}
if ($passou == 0) {
 $curso = $unidade->criaCurso(null, null, $codcurso, null, null, null);
 $daolab = new LaboratorioDAO();
 $rows = $daolab->buscaLaboratorio($codlab,$sessao->getAnobase());
 foreach ($rows as $row) {
 $tipolab = new Tplaboratorio();
 $tipolab->setCodigo($row['Codtipo']);
 $lab = $tipolab->criaLabv3($codlab);
 }
 $labcurso = $lab->criaLabcurso(null, $curso,$sessao->getAnobase());
 $daolc->insere($labcurso);
}
//$daolc->fechar();
//$cadeia = "Location:" . Utils::createLink('laborv3', 'conslabcurso', array('codlab' => $codlab));
//header($cadeia);
?>

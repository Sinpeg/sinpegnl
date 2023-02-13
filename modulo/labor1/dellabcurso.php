<?php
ob_start();
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[7]) {
 header("Location:index.php");
} else {
// $sessao = $_SESSION["sessao"];
// $nomeunidade = $sessao->getNomeunidade();
// $codunidade = $sessao->getCodunidade();
// $responsavel = $sessao->getResponsavel();
// $anobase = $sessao->getAnobase();
// require_once('../../includes/dao/PDOConnectionFactory.php');
 // var_dump($usuario);
 require_once('dao/labcursoDAO.php');
 require_once('classes/labcurso.php');
 $daolabcur = new LabcursoDAO();
 $codlab = $_GET["codlab"];
 $codlabcurso = $_GET["codlabcurso"];
 if ($codlabcurso != "" && is_numeric($codlabcurso)) {
 $daolabcur->deleta($_GET["codlabcurso"]);
 $daolabcur->fechar(); 
 Utils::redirect('labor', 'conslabcurso1', array('codlab'=>$codlab));
// $cadeia = "location:".Utils::createLink('labor', 'conslabcurso1', array('codlab'=>$codlab));
// header($cadeia);
// header
 }
}
?>

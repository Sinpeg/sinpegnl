<?php
ob_start();
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[7]) {
 exit();
} else {
// $sessao = $_SESSION["sessao"];
// $nomeunidade = $sessao->getNomeunidade();
// $codunidade = $sessao->getCodunidade();
// $responsavel = $sessao->getResponsavel();
// $anobase = $sessao->getAnobase();
// require_once('../../includes/dao/PDOConnectionFactory.php');
 // var_dump($usuario);
    require_once('dao/labcursoDAO.php');
    require_once('dao/laboratorioDAO.php');
    require_once('classes/labcurso.php');
 require_once('classes/laboratorio.php');
 $daolabcur = new LabcursoDAO();
 $codlab = $_GET["codlab"];
 $codlabcurso = $_GET["codlabcurso"];
 if ($codlabcurso != "" && is_numeric($codlabcurso)) {
    // echo $_GET["codlabcurso"].','.$sessao->getAnobase();
 $daolabcur->deleta($_GET["codlabcurso"],$sessao->getAnobase());
 $lab=new Laboratorio();
 $lab->setCodlaboratorio($codlab);
 $rows=$daolabcur->buscaCursosLaboratorio($lab);
 $passou=0;
 foreach ($rows as $r){
     $passou=1;
     
 }
 //echo "aqui".$count;die;
 if ($passou==0){
     $daolab=new LaboratorioDAO();
     $lab->setSituacao('V');
     $lab->setAtendecursograd(NULL);
     $daolab->alteraSit($lab);
     
 }
 
 Utils::redirect('laborv3', 'conslabcurso', array('codlab'=>$codlab));
 $cadeia = "location:".Utils::createLink('labor', 'conslabcurso1', array('codlab'=>$codlab));
 header($cadeia);
// header
 }
}
?>

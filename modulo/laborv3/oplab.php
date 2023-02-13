<?php
//session_start(); //Sessão já inicializada

$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[7]) {
 header("Location:index.php");
}
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('dao/laboratorioDAO.php');
require_once('classes/laboratorio.php');
//require_once('../../includes/dao/unidadeDAO.php');
//require_once('../../includes/classes/unidade.php');
require_once('classes/tplaboratorio.php');
$daolab = new LaboratorioDAO();
$tipolab = new Tplaboratorio();
$tipolab->setCodigo($_POST["tlab"]);
$unidade = new Unidade();
$unidade->setCodUnidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$so = $_POST["so"];
$areaanterior = $_POST["area"];
$area = addslashes(str_replace(",", ".", $areaanterior));
if (isset($_POST["aulapratica"])) {
 $labensino = "S";
 $resposta = $_POST["resposta"];
 if (!is_numeric($resposta)) {
 $mensagem = urlencode(" ");
 $cadeia = "location:../saida/erro.php?codigo=1&mensagem=" . $mensagem;
 header($cadeia);
 }
} else {
 $labensino = "N";
 $resposta = null;
}
if (isset($_POST["cabo"])) {
 $cabo = "S";
} else {
 $cabo = "N";
}
$capacidade = $_POST["capacidade"];
$nestacoes = $_POST["nestacoes"];
$nomelab = addslashes(strtoupper($_POST["nome"]));
$siglalab = addslashes(strtoupper($_POST["sigla"]));
$locallab = addslashes(strtoupper($_POST["local"]));
$operacao = $_POST["operacao"];
$justificativa = $_POST['justificativa'];

if ($nomelab !== "" && is_string($nomelab) && is_string($siglalab) && is_string($locallab) && is_numeric($capacidade) && is_string($so)) {
 
 //IF de inserção de laboratório	
 if ($operacao == "I"){
 $lab = $unidade->criaLab(null, $tipolab, $nomelab, $capacidade, $siglalab, $labensino, $area, $resposta, $cabo, $locallab, $so, $nestacoes, "A", $anobase, null);
 $daolab->insere($lab);
 echo "inseriu";
 } elseif ($operacao == "A") {//Alterar dados laboratório
 $situacao = $_POST["situacao"];
 
 if ($situacao == "A") {//Alterar dados laboratório
	 $anoativacao = null;
	 $anodesativacao = null;
	 } else {
	 $anoativacao = null;
	 $anodesativacao = $anobase;
	 }
	 $codlab = $_POST["codlab"];
 
	 if ($codlab != "" && is_numeric($codlab)) {
		 $lab = $unidade->criaLab($codlab, $tipolab, $nomelab, $capacidade, $siglalab, $labensino, $area, $resposta, $cabo, $locallab, $so, $nestacoes, $situacao, $anoativacao, $anodesativacao);
		 //$daolab->altera($lab);
		 
		 //echo $lab->getCodlaboratorio()."/".$anobase."/".$justificativa."/"; 
		 
		 //verifica se existe area cadastrada para o lab no ano
		 $qtdArea = $daolab->qtdAreaLabAno($lab->getCodlaboratorio(), $anobase);
		
		if($qtdArea==0){
			$daolab->insereArea($lab, $anobase, $justificativa);
		}else{
			$daolab->alteraArea($lab, $anobase, $justificativa)	;
		} 
		 	
		 //echo $teste;
	 }
 }
 $daolab->fechar();
 //Pede para entrar com cursos vinculdps
 /* require_once('dao/labcursoDAO.php');
 $daolabcurso=new LabcursoDAO();
 $cont=0;
 //busca cursos do lab
 $rows=$daolabcurso->buscaCursosLaboratorio1($codlab);
 foreach ($rows as $row){
 $cont++;
 }
 if ($cont==0){
 require_once('dao/laboratorioDAO.php');
 require_once('classes/laboratorio.php');
 $daolab = new LaboratorioDAO();
 $daolab->deleta($codlab);
 $cadeia="location:consultalab.php";
 header($cadeia);
 exit();
 }else{
 $daolab->fechar();
 $mensagem = urlencode("Este laborat&oacute;rio N&Atilde;O possui cursos vinculados. Informe os cursos que utilizam este laborat&oacute;rio.");
 $cadeia="location:consultalab1.php?msg=".$mensagem;
 header($cadeia);
 exit();
 }
 */
} else {
 $mensagem = urlencode(" ");
 $cadeia = "location:../saida/erro.php?codigo=1&mensagem=" . $mensagem;
 header($cadeia);
}

$cadeia = "location:".Utils::createLink('labor', 'consultalab');
header($cadeia);

//ob_end_flush();
?>

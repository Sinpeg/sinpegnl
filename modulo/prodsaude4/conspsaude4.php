<?php
ob_start();

echo ini_get('display_errors');

if (!ini_get('display_errors')) {
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL & ~E_NOTICE);
}
?>
<?php
//set_include_path(';../../includes');
require_once('../../includes/classes/sessao.php');

//session_start();
if (!isset($_SESSION["sessao"])){
	header("location:../../index.php");
	exit();
}
$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
$codestruturado = $sessao->getCodestruturado();
$aplicacoes = $_SESSION["sessao"]->getAplicacoes();
if (!$aplicacoes[14]){
	echo "Você não tem permissão para visualizar este formulário.";
	exit();
}
$codsubunidade=$_POST["subunidade"];
$codservico=$_POST["servico"];
$codprocedimento=$_POST["procedimento"];
$erro=false;
if ($codunidade==270 ){ // caso a unidade seja 270, então realiza a consulta com o local
  $codlocal=$_POST["local"];
  if ($codlocal=="" || !is_numeric($codlocal)){
  	$erro=true;
  }
}else{
	$codlocal=null;
}
if (is_numeric($codsubunidade) && is_numeric($codservico)
&& is_numeric($codprocedimento)){
	$cadeia = "location:conspsaude41.php?sub=".$codsubunidade."&servico=".$codservico."&proced=".$codprocedimento."&local=".$codlocal;
	header($cadeia);
	exit();
}else {
	$erro=true;
}

if ($erro){
	$mensagem = urlencode(" ");
	echo $mensagem;
	exit();
}
ob_end_flush();
?>

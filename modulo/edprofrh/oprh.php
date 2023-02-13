<?php
//ob_start();
//
//echo ini_get('display_errors');
//
//if (!ini_get('display_errors')) {
//	ini_set('display_errors', 1);
//	ini_set('error_reporting', E_ALL & ~E_NOTICE);
//}
?>
<?php
//require_once('../../includes/classes/sessao.php');
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('classes/rhetemufpa.php');
require_once ('dao/rhetemufpaDAO.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[24]) {
    header("Location:index.php");
}

$codigo = $_GET["codigo"];
$op = $_GET["op"];
$daorh= new rhetemufpaDAO();

if ($op=="excluir")
{
	$daorh->deleta($codigo);
	Utils::redirect('edprofrh', 'consultarh');
}

else{

$qtdDoc = $_POST["qtdDoc"];
$qtdMes = $_POST["qtdMes"];
$qtdEsp = $_POST["qtdEsp"];
$qtdGra = $_POST["qtdGra"];
$qtdTem = $_POST["qtdTem"];
$qtdTec = $_POST["qtdTec"];
$qtdNt = $_POST["qtdNt"];
$subunidade = $_POST["subunidade"];

//$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
//require_once('../../includes/classes/unidade.php');

$operacao = "I";
if (is_numeric($qtdDoc) && $qtdDoc!="" &&
is_numeric($qtdMes) && $qtdMes!="" &&
is_numeric($qtdEsp) && $qtdEsp!="" &&
is_numeric($qtdGra) && $qtdGra!="" &&
is_numeric($qtdTem) && $qtdTem!="" &&
is_numeric($qtdTec) && $qtdTec!="" &&
is_numeric($qtdNt) &&  $qtdNt!="" && is_numeric($subunidade)){
	$unidade = new Unidade();
	$unidade->setCodunidade($codunidade);
	$unidade->setNomeunidade($nomeunidade);	

	$rows=$daorh->buscarhsubunidade($anobase,$subunidade);
	foreach ($rows as $row){
		$codigo=$row['Codigo'];
		$operacao = "A";
	}
	if ($operacao == "I"){
		$unidade->criaRhetemufpa(null,$subunidade,$qtdDoc,$qtdMes,$qtdEsp,$qtdGra,$qtdNt,$qtdTem,$qtdTec,$anobase);

		$daorh->Insere($unidade->getRhufpa());
	}
	elseif($operacao == "A") {
		$unidade->criaRhetemufpa($codigo,$subunidade,$qtdDoc,$qtdMes,$qtdEsp,$qtdGra,$qtdNt,$qtdTem,$qtdTec,$anobase);
		$daorh->altera($unidade->getRhufpa());
	}
	
	Flash::addFlash('Quadro de Pessoal da educação profissional cadatrado com sucesso!');
	Utils::redirect('edprofrh', 'consultarh');
	
   }

   else 
   {
   	Error::addErro('Erro encontrado durante o cadastro do quadro de pessoal da educação profissional');
   	Utils::redirect('prodartistica', 'consultaprodartistica');
   }

}

$daorh->fechar();
//header($cadeia);
//exit();
//ob_end_flush();
?>
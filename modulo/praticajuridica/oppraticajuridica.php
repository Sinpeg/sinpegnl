<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('classes/praticajuridica.php');
require_once('dao/praticajuridicaDAO.php');
require_once('classes/tipopraticajuridica.php');
require_once('dao/tipopraticajuridicaDAO.php');

$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[13]) {
    header("Location:index.php");
}
$qtdAcoes = $_POST["qtdAcoes"];
$qtdAudiencias = $_POST["qtdAudiencias"];
$qtdAtendimentos = $_POST["qtdAtendimentos"];
$qtdOutros = $_POST["qtdOutros"];

//$sessao = $_SESSION["sessao"];
$nomeUnidade = $sessao->getNomeunidade();
$codUnidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();

//require_once('../../includes/classes/unidade.php');
$unidade = new Unidade();
$unidade->setCodunidade($codUnidade);
$unidade->setNomeunidade($nomeUnidade);
$tipoPJ = array();
if (is_numeric($qtdAcoes) && $qtdAcoes!=""
    && is_numeric($qtdAudiencias) && $qtdAudiencias!=""
    && is_numeric($qtdAtendimentos) && $qtdAtendimentos!=""
    && is_numeric($qtdOutros) && $qtdOutros!=""){
	
    $tipoPJ["1"]= $qtdAcoes;
	$tipoPJ["2"] = $qtdAudiencias;
	$tipoPJ["3"] = $qtdAtendimentos;
	$tipoPJ["4"] = $qtdOutros;


	$cont=0;
	$pjDAO = new PraticajuridicaDAO();
	$pj = array();
	foreach ($tipoPJ as $i => $tpPJ ){
		$cont++;
		$pj[$cont] =new Tipopraticajuridica();
		$pj[$cont]->setCodigo($i);
		$consulta = $pjDAO->buscapj($codUnidade,$anobase,$i);
		$passou = false;
		foreach ($consulta as $row){
			$passou = true;
			$pj[$cont]->criaPraticajuridica($row["Codigo"],$unidade,$anobase,$tpPJ);
		}//for
		if (!$passou){
			$pj[$cont]->criaPraticajuridica(null,$unidade,$anobase,$tpPJ);

		}
	}
	if (!$passou){
		$pjDAO->inseretodos($pj);
	}
	else {
		$pjDAO->alteratodos($pj);
	}


	$pjDAO->fechar();
}else {
    $mensagem = urlencode("");
	$cadeia="location:../saida/erro.php?codigo=1&mensagem=".$mensagem;
	header($cadeia);
}
Utils::redirect('praticajuridica', 'consultapjuridica');
//$cadeia = "location:consultapjuridica.php";
//header($cadeia);
//exit();
//ob_end_flush();

?>
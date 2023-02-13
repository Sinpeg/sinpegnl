<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('classes/atividadeextensao.php');
require_once ('dao/atividadeextensaoDAO.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();

if (!$aplicacoes[19]) {
    header("Location:index.php");
}

$tipo = $_POST["tipo"];
$qtd = $_POST["qtd"];
$qtdPart = $_POST["qtdPart"];
$qtdAten = $_POST["qtdAten"];
$operacao = $_POST["operacao"];
$subunidade = $_POST["subunidade"];
//$sessao = $_SESSION["sessao"];
$nomeUnidade = $sessao->getNomeunidade();
$codUnidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
//require_once('../../includes/classes/unidade.php');

$cod = $_GET["codigo"];
$op = $_GET["op"];
$daoae= new AtividadeextensaoDAO();
$unidade = new Unidade();
$unidade->setCodunidade($codUnidade);
$unidade->setNomeunidade($nomeUnidade);

if ($op=="excluir"){
	$daoae->deleta($cod);
	Utils::redirect('atividades', 'consultaatividadeextensao');
}else {
	$atividadeextensao = new Atividadeextensao();
	if (is_numeric($subunidade) && $subunidade!="" && is_numeric($tipo) &&  $tipo!="" && is_numeric($qtd)
	&& $qtd!="" && is_numeric($qtdPart) && $qtdPart!="" && is_numeric($qtdAten) && $qtdAten!=""){
		$rows = $daoae->buscasubunidade($subunidade,$tipo, $anobase);
		$operacao = "I";
		foreach ($rows as $row){
			$codigo=$row['Codigo'];
			$operacao = "A";
		}

		if ($operacao == "I"){
			$unidade->criaAtividadeextensao(null,$subunidade,$tipo,$qtd,$qtdPart,$qtdAten,$anobase);
			$daoae->Insere($unidade->getAtividadeextensao());
		}elseif ($operacao == "A") {
			$unidade->criaAtividadeextensao($codigo,$subunidade,$tipo,$qtd,$qtdPart,$qtdAten,$anobase);
			$daoae->altera($unidade->getAtividadeextensao());
		}
	}else{
		Error::addErro('Erro encontrado durante o cadastro da atividade de extensão!');
		Utils::redirect('atividades', 'consultaatividadeextensao');
	//	$mensagem = urlencode(" ");
	//	$cadeia="location:../saida/erro.php?codigo=1&mensagem=".$mensagem;
	//	header($cadeia);
		//exit();
	}
	Flash::addFlash("Atividade de extensão cadastrada com sucesso!");
	Utils::redirect('atividades', 'consultaatividadeextensao');
	//$cadeia = "location:consultaatividadeextensao.php";
	//header($cadeia);
	//exit();
}

$daoae->fechar(); 
ob_end_flush();
?>
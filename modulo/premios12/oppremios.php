<?php
require_once('classes/premios.php');
require_once ('dao/premiosDAO.php');
require_once('classes/tppremios.php');
require_once ('dao/tppremiosDAO.php');
require_once ('unidadeDAO.php');

if (!$aplicacoes[5]) {
    header("Location:index.php");
    exit;
} 
$orgao = filter_input(INPUT_POST, 'Orgao', FILTER_DEFAULT); // orgão
$nome = filter_input(INPUT_POST, 'Nome', FILTER_DEFAULT); // nome do prêmio
$qtde = filter_input(INPUT_POST, 'qtde', FILTER_DEFAULT); // quantidade
$categoria = filter_input(INPUT_POST, 'categoria', FILTER_DEFAULT); // quantidade
$reconhec = filter_input(INPUT_POST, 'reconhec', FILTER_DEFAULT); // quantidade
$data = filter_input(INPUT_POST, 'data', FILTER_DEFAULT); // quantidade
$operacao = filter_input(INPUT_POST, 'operacao', FILTER_DEFAULT); // operação


$sessao = $_SESSION["sessao"];

$daotp = new TpprimiosDAO();
$daop = new PremiosDAO();
$tp = new Tppremios();
$consulta=$daotp->buscaPorCodigo($reconhec);
foreach ($consulta as $row) {
	$passou = true;
	$tp->setCodpremio($row['CodPremio']);
	$tp->setNome($row['Nome']);
}
if ($sessao->isUnidade()){
	$sub = filter_input(INPUT_POST, 'subunidade', FILTER_DEFAULT);
	$nomeunidade = $sessao->getNomeunidade();
	$codunidade = $sessao->getCodunidade();
	$anobase = $sessao->getAnobase();
	$unidade = new Unidade();
	$unidade->setCodunidade($codunidade);
	$unidade->setNomeunidade($nomeunidade);
	$unidade->criaSubunidade($sub, null, null);
	$unidade->getSubunidade()->criaPremios($orgao, $categoria, $nome, $qtde, $data, $anobase, $tp);
}else{
	$undao = new UnidadeDAO();
	$resultado=$undao->buscaResponsavel($sessao->getCodUnidadeSup());
	foreach ($resultado as $row){
		$unidade = new Unidade();
		$unidade->setCodunidade($row['CodUnidade']);
	}
	$unidade->criaSubunidade( $sessao->getCodunidade(), null, null);
	$unidade->getSubunidade()->criaPremios($orgao, $categoria, $nome, $qtde, $data, $anobase, $tp);
}

// fim
// operação de atualização
if ($operacao=="A") {
    $codigo = filter_input(INPUT_POST, 'codigo', FILTER_DEFAULT); // código prêmio
    $unidade->getSubunidade()->getPremios()->setCodigo($codigo);
    $rows = $daop->buscapremios($codigo); // busca o prêmio
    // O código do prêmio na base de dados não existe
    if ($rows->rowCount()==0) {
        Error::addErro("Não existe pr&ecirc;mio cadastrado com o c&oacute;digo $codigo.");
        Utils::redirect('premios', 'oppremios'); // redireciona a transação
    } else {
    	$daop->altera($unidade);
    }
    
} else if ($operacao=="I") {
   
    $daop->Insere($unidade); // insere
 }
 Utils::redirect('premios', 'consultapremios');
 

<?php
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();

if (!$aplicacoes[5]) {
	header("Location:index.php");
 	
	}

$anobase = $sessao->getAnobase();

$orgao = filter_input(INPUT_POST, 'Orgao', FILTER_DEFAULT); // orgão

$nome = filter_input(INPUT_POST, 'Nome', FILTER_DEFAULT); // nome do prêmio
if ($anobase<2018){
   $qtde = filter_input(INPUT_POST, 'qtde', FILTER_DEFAULT); // quantidade
   $qtdi=0; 
   $qtdo=0; 
   $qtdet=0; 
}else{
   $qtde=0;
   $qtdi=filter_input(INPUT_POST, 'qtdei', FILTER_DEFAULT); 
   $qtdo=filter_input(INPUT_POST, 'qtdeo', FILTER_DEFAULT); 
   $qtdet=filter_input(INPUT_POST, 'qtdet', FILTER_DEFAULT); 
}

$categoria = filter_input(INPUT_POST, 'categoria', FILTER_DEFAULT); // quantidade
$reconhec = filter_input(INPUT_POST, 'reconhec', FILTER_DEFAULT); // quantidade
$pais = filter_input(INPUT_POST, 'pais', FILTER_DEFAULT); 
$operacao = filter_input(INPUT_POST, 'operacao', FILTER_DEFAULT); // operação
$link = $_POST['link'];


$daotp = new TppremiosDAO();
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
	$unidade->getSubunidade()->criaPremios(null, $orgao, $nome, $qtde,$qtdi,$qtdo,$qtdet,$anobase,$tp,$categoria,$pais,$link);
	
}else{

	$undao = new UnidadeDAO();
	$resultado=$undao->buscaidunidade($sessao->getCodUnidadeSup());
	
	foreach ($resultado as $row)
	{
		$unidade = new Unidade();
		$unidade->setCodunidade($row['CodUnidade']);
	}
	$unidade->criaSubunidade( $sessao->getCodunidade(), null, null);
	$unidade->getSubunidade()->criaPremios(null, $orgao, $nome, $qtde,$qtdi,$qtdo,$qtdet,$anobase,$tp,$categoria,$pais,$link);
}

// fim
// operação de atualização
if ($operacao=="A") 
{
    
    $codigo = filter_input(INPUT_POST, 'codigo', FILTER_DEFAULT); // código prêmio
    $rows = $daop->buscapremios($codigo); // busca o prêmio
    // O código do prêmio na base de dados não existe
    $unidade->getSubunidade()->getPremios()->setCodigo($codigo);
    
    if ($rows->rowCount()==0) {
        Error::addErro("Não existe pr&ecirc;mio cadastrado com o c&oacute;digo $codigo.");
        Utils::redirect('premios', 'oppremios'); // redireciona a transação
    } 
    else 
    {
    	$daop->altera($unidade);
    }
    
} else if ($operacao=="I") {
    
    $daop->Insere($unidade); // insere

 }

 Utils::redirect('premios', 'consultapremios');
 
?>
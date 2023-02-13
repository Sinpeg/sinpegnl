<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//session_start();
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[5]) {
    header("Location:index.php");
} 
else{
	
	$nomeunidade = $sessao->getNomeunidade();
	$codunidade = $sessao->getCodunidade();
	$responsavel = $sessao->getResponsavel();
	$anobase = $sessao->getAnobase();
	
	$codigo= $_GET["codigo"];
	
	$daop = new PremiosDAO(); // prêmios
	$rows = $daop->buscapremios($codigo); // busca tudo por código
	$ano = 0; // ano
	// itera dentro dos resultados
	foreach ($rows as $row) 
	{
		$unidadePremio = new Unidade();
		$unidadePremio->setCodunidade($row["CodUnidade"]);
		$subunidade =$row["CodSubunidade"];
		$categoria=$row["Categoria"];
		$reconhecimento=$row["Reconhecimento"];
		$unidadePremio->criaSubunidade($row["CodSubunidade"], null, null);
		//$unidadePremio->getSubunidade()->criaPremios($row["Codigo"],  $row["OrgaoConcessor"], $row["Nome"], $row["Quantidade"], $row["Ano"], $row["Reconhecimento"],$row["Categoria"]);
	}
	
	//$lock = new Lock(); // lock
	//$lock->setLocked(Utils::isApproved(5, $codunidadecpga, $codunidade, $anobase));//aqui
	/*
	if ($sessao->isUnidade())	{
	       
		if ($codunidade != $unidadePremio->getSubunidade()->getCodunidade())
		{
			// tentativa da cpga de excluir prêmio de outras unidades
			Error::addErro("Você não pode excluir prêmios de outras unidades");
			Utils::redirect('premios', 'consultapremios');	
			 
		}else if ( is_numeric($codigo) ){
			$dao= new PremiosDAO();
			$p= new Premios();
			$p->setCodigo($codigo);
			$dao->deleta($p);
			$dao->fechar();
		}
		
		
	}*/
	
	/*else if ($lock->getLocked())
	{
		// tentativa da subunidade de excluir prêmios homologagos
		Error::addErro("Você não pode excluir prêmios homologados");
		Utils::redirect('premios', 'consultapremios');		
	}	*/
	
	/*
	else 
	*/if ( is_numeric($codigo) )
	{
		$dao= new PremiosDAO();
		$p= new Premios();
		$p->setCodigo($codigo);
		$dao->deleta($p);
		$dao->fechar();
	}
		
        Utils::redirect('premios', 'consultapremios');
      

}
?>




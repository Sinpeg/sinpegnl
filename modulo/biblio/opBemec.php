<?php

//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[45]) {
	header("Location:index.php");
	exit();
} else {
require_once('classes\bibliemec.php');
require_once('dao\biblioEmecDAO.php');
$codunidade = $sessao->getCodunidade();
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
//$unidade->setNomeunidade($nomeUnidade);

$cont=0;
if (isset($_POST['idbibliemec'])){

	$idbibliemec=$_POST['idbibliemec'];

	$daobe = new BiblioEmecDAO();
	$be = new Bibliemec();
        $passou=false; 
        
    $rows = $daobe->buscaCodEmecBiblioUnidade($codunidade);
    foreach ($rows as $row){
    	if ($idbibliemec!=$row['idBiliemec']){
    		
    	  $be=new Bibliemec();
    	  $be->setIdBibliemec($row['idBibliemec']);
    	  $be->setUnidade(NULL);
    	  $daobe->altera($be);
    	   
    	}
    }
        
	//busca registro para incluir o codigo da unidade

	$rows = $daobe->buscaBiblioemec($idbibliemec);
	foreach ($rows as $row) {
		$be->setIdBibliemec($row['idBibliemec']);
		$be->setUnidade($unidade);
         $selecionado=$row['idUnidade'];      
	}

    if (is_null($selecionado)) {
  
        $daobe->altera($be);
        $daobe->fechar();
        Flash::addFlash('Sele&ccedil;&atilde;o de unidade do Emec com sucesso');
        Utils::redirect('biblio', 'alteraBemec');
    }else if   ($selecionado!=$codunidade){
    	Flash::addFlash('A biblioteca selecionada já está associada à outra unidade');
    	Utils::redirect('biblio', 'alteraBemec');
    }else{
    	Utils::redirect('biblio', 'alteraBemec');
    	//Utils::redirect('biblio', 'alteraBemec');
    }
    	
   
// exit();
} else {
    Error::addErro('Erro ao salvar. Por favor, tente novamente!');
    Utils::redirect('biblio', 'alteraBemec');
}
}
?>

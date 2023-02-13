<?php
$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade(); // código da unidade
$nomeunidade = $sessao->getNomeUnidade();
$aplicacoes = $sessao->getAplicacoes(); // código das aplicações
$anobase = $sessao->getAnobase();       // ano base
$codestruturado = $sessao->getCodestruturado(); // código estruturado
$erro="";

if (!$aplicacoes[38]) {
    print "Erro ao acessar esta aplicação";
    exit();
}
if ($sessao->getCodunidade()!=938){
	$codindicador =addslashes( $_GET['ind']);
	$codmapa = addslashes($_GET['mapa']);
}else{
	if ($_POST['cxunidade']==""){
		$erro="Preencha a unidade";	
	}else{
		$codindicador =addslashes( $_POST['ind']);
		$codmapa = addslashes($_POST['mapa']);	
		$rows1=$daounidade->buscaporNomeUnidade($_POST['cxunidade']);
	    foreach ($rows1 as $r) {
	        $propindicador=$r['CodUnidade'];
	    }
	    $codunidade=$propindicador;
	}
}
if ($erro==""){

		$daomapaindicador = new MapaIndicadorDAO();
		if($codindicador != null  && $codmapa != null && $codunidade != null ){
			$tipoAssociado=NULL;
			if ($codunidade!=938){
				$mdao=new MapaDAO();
				
				$rows=$mdao->isMapaDocumentoPDI($codmapa);
				foreach ($rows as $r){
					if ($r['tipo']==1){
						$tipoAssociado="PDU";
					}
				}
				//echo "ddd".$tipoAssociado;die;
				
			}
			$daomapaindicador->insere($codindicador,$codmapa,$codunidade,$tipoAssociado);
			$daomapaindicador->fechar();
			$string = "O indicador foi vinculado com sucesso!";
			Flash::addFlash($string);
			Utils::redirect('indicadorpdi', 'consultaindicadorproprio');
		}
		
		
		else {
			$string = "Falha ao vincular o indicador";
			Flash::addFlash($string);
			Utils::redirect('indicadorpdi', 'consultaindicadorproprio');	
		    }
}else{?>
	<div class="erro">
        <img src="webroot/img/error.png" width="30" height="30"/>
        <?php print $erro; ?>
        <span class="plus"></span><a href="<?php echo Utils::createLink('indicadorpdi', 'consultaindicador'); ?>">Voltar</a>
    </div>
<?php }?>	 
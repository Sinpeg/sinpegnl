<?php
//qUANDO NAO FOR A UFPA = 938
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
	$codindicador =addslashes( $_POST['ind']);
	$codmapa = addslashes($_POST['mapa']);

if ($erro==""){
		$daomapaindicador = new MapaIndicadorDAO();
		if($codindicador != null  && $codmapa != null && $codunidade != null ){
			$tipoAssociado=NULL;
			$mdao=new MapaDAO();
				
			$rows=$mdao->isMapaDocumentoPDI($codmapa,$anobase);
			/*foreach ($rows as $r){
				if ($r['tipo']==1){
					$tipoAssociado="PDU";
				}
			}*/
			//echo "ddd".$tipoAssociado;die;				
			
			$daomapaindicador->insere($codindicador,$codmapa,$codunidade,$tipoAssociado);
			$daomapaindicador->fechar();
			$string = "O indicador foi vinculado com sucesso!";
			Flash::addFlash($string);
			Utils::redirect('indicadorpdi', 'consultaindicadorproprio');
		}else {
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
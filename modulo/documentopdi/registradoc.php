<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
$codunidade = $sessao->getCodUnidade();
$unidade = NULL;
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$c = new Controlador();

if($anobase>=2022){
    $missao = "";
    $visao = "";
}else{
    $missao = strip_tags(addslashes($_POST['missao']));
    $visao = strip_tags(addslashes($_POST['visao']));
}

$situacao = 'A';
$nomeunidadeselecionada=isset($_POST['cxunidade'])?addslashes($_POST['cxunidade']):NULL;

if ($_POST['op']="E"  && isset($_POST['coddoc'])){
   $coddoc = addslashes($_POST['coddoc']);
}else{
    $coddoc=0;
}

$objdoc = new Documento();
$unidao = new UnidadeDAO();
$daodoc = new DocumentoDAO();

if (!isset($_POST['pdi'])) {
    $pdi = '';
    $rows=$daodoc->buscaPeriodoGestao($anobase, 2)->fetch();
	$anoinicial=$rows['anoinicial'];
	$anofinal=$rows['anofinal'];
} else {
    $rows=$daodoc->buscaPeriodoGestao($anobase, 1)->fetch();
	$anoinicial=$rows['anoinicial'];
	$anofinal=$rows['anofinal'];
}

//echo var_dump($_POST)."<br>";

if (!$aplicacoes[36] && !$aplicacoes[40]) {
    print "Você não tem permissão para acessar esta aplicação!";
    exit();
}

//echo var_dump($_POST);die;

	$conteudo = NULL;
	$erro = NULL;
	$extensoesOk = ",.rar,.zip,";   
	$arquivo = $_FILES['userfile']['tmp_name'];
	$tamanho = $_FILES['userfile']['size'];
	$tipoarq = "application/octet-stream";//$_FILES['userfile']['type']; //
	$nome = $_FILES['userfile']['name'];
	$erroarq = $_FILES['userfile']['error'];
	$tmpName = $_FILES['userfile']['tmp_name'];
	$passou = 0;
	$extensao = "," . strtolower(substr($nome,(strlen($nome) - 4))) . ",";
	$unidadePlano=NULL;
	//echo $_FILES['userfile']['error'].",".$_FILES['userfile']['size'].",".$_FILES['userfile']['name'].",".$_FILES['userfile']['tmp_name'];
     //echo var_dump($_FILES)."<br>";
	//echo "tamanho ".$_FILES['userfile']['size']." erro". $_FILES['userfile']['error'];

	if ($c->getProfile($sessao->getGrupo()) && is_null($nomeunidadeselecionada) && $coddoc==0){
	    $erro="Selecione a unidade";
	} else if($_FILES['userfile']['error'] != 0){
	    $erro = 'Um erro ocorreu enquanto o arquivo estava sendo importado. '. 'Código de Erro: '. intval($_FILES['userfile']['error']);
	}else if  (strpos($extensoesOk, $extensao)<0){
	    $erro = "Envie arquivos compactados (extens&atilde;o .rar ou .zip).";
	}else if ($tamanho < 0 || $tamanho > 10485760  ) {
	    $erro='Tamanho do arquivo deve ser maior que 0 e menor do que 2 Mbytes!';
	}else if (is_executable($_FILES['userfile']['tmp_name'])){
	    $erro='O arquivo não pode ser um executável';
	}else if (!preg_match("'/^([1-9][0-9]*)$/'", $pdi) && !empty($pdi)) {
	    $erro = "Selecione o documento";
	} else {
		
	    $daodoc = new DocumentoDAO();
	    
		$cadastro = true;
	    
		if (!is_null($nomeunidadeselecionada)){
			
	        $rows=$unidao->unidadePorStr($_POST['cxunidade'], 1);
	        foreach ($rows as $row){
	            $unidade=new Unidade();
	            $unidade->setCodunidade($row['CodUnidade']);
	        }
	        
	    }
		
	    //busca pdu ativo para verificar 
	    $rows=$daodoc->buscadocumentoPrazoEUnidade($anobase, $unidade->getCodunidade())->fetch();
	    
	    if (!empty($rows)){
	    	$cadastro = false;
	    	$pdi=$rows['codigo'];
	    	$anoinicial=$rows['anoinicial'];
	    	$anofinal=$rows['anofinal'];
	    	$nomedoc=$rows['nome'];
	    }else{
	    	$nomedoc='PDU - '.$sessao->getNomeUnidade();
	    }
		
	    //BUSCA SIGLA
	    $rowsigla = $unidao->buscaidunidadeRel($unidade->getCodunidade())->fetch();
	    
		//die($nome.strlen($nome));

	    $nome = $rowsigla!=NULL?'PDU_'.$rowsigla['sigla'].'_'.$anoinicial.'_'.$anofinal.substr($nome, strlen($nome)-4):$nome;
		
	    //verificar periodo se ja existe
	    $objdoc->setNome($nomedoc);
	    $objdoc->setAnoInicial($anoinicial);
	    $objdoc->setAnoFinal($anofinal);
	    $objdoc->setMissao($missao);
        $objdoc->setVisao($visao);
	    $objdoc->setUnidade($unidade);
	    $objdoc->setSituacao($situacao);
	    $objdoc->setCodigo($pdi);
	    
	    if($anobase>=2022){
	        $objdoc->setNomearq("../public/pdu/pdu2022_2025/".$nome);
	    }else{
	        $objdoc->setNomearq("../public/pdu/pdu2017_2020/".$nome);
	    }
	    
	    //echo $objdoc->getNomearq()."ddddd";die;
	    $objdoc->setTamarq($tamanho);
	    $objdoc->setTipoarq($tipoarq);
	
	    
	    $fp = fopen($arquivo, "rb1");
	    
	    $conteudo = fread($fp, $tamanho);
	    $conteudo = addslashes($conteudo);
	    fclose($fp);	    
	    	    	    	    		        	    
	    
	    $objdoc->setAnexo($conteudo);
	    
	    $string="";
	
	    $objdoc->setTipo((($unidade!=NULL && $unidade->getCodunidade()==938) || ($unidadePlano!=NULL && $unidadePlano->getCodunidade()==938))?1:2);
	    if ($cadastro && $objdoc->getAnexo()!=NULL) {
	        $objdoc->setUnidade($unidade);
	        $linhas=$daodoc->insere($objdoc);
	        $passou=1;
	        if($anobase>=2022){
	            $uploaddir = '../public/pdu/pdu2022_2025/';
	        }else{
	            $uploaddir = '../public/pdu/pdu2017_2020/';
	        }	        
			$uploadfile = $uploaddir . basename($nome);
			echo '<pre>';
			if (move_uploaded_file($arquivo, $uploadfile)) {
				
			   $string.= "Arquivo válido e enviado com sucesso.\n";
			} else {
			     $string.= "Possível problema de upload de arquivo!\n";
			     $string.= 'Aqui está mais informações de debug:';
			    print_r($_FILES);die;
			}
			print "</pre>";
	        
			
	        $string = "Documento cadastrado com sucesso!";
	    } else {

	        $daodoc->altera($objdoc);
	        $passou=1;
	        if($anobase>=2022){
	            $uploaddir = '../public/pdu/pdu2022_2025/';
	        }else{
	            $uploaddir = '../public/pdu/pdu2017_2020/';
	        }
			$uploadfile = $uploaddir . basename($nome);
			if (move_uploaded_file($arquivo, $uploadfile)) {
			   echo "Arquivo válido e enviado com sucesso.\n";
			} else {
			    echo "Possível problema de upload de arquivo!\n";
			    //echo 'Aqui está mais informações de debug:';
			    print_r($_FILES);
			}
	        $string = "Documento atualizado com sucesso!";
	        
	    }
	    //else // $erro = "Arquivo inválido!";
	    $daodoc->fechar();
	}
 if ($erro!=NULL){ 
	
?>
    <div class="erro">
        <img src="webroot/img/error.png" width="30" height="30"/>
        <?php print $erro; ?>
    </div>
  <?php    
      Flash::addFlash($erro); 
      Utils::redirect('documentopdi', 'inserirdocpdi');
} else{ 
   //$_SESSION['mensagem']=''; 
   	//Erro na entrada de dados. Não foi possível carregar o arquivo para o servidor.Arquivos devem ter tamanho menor ou igual a 2Mbytes!
	//Flash::addFlash($erro); 
	 $string='<div class="alert alert-success">
        <button type="button " class="close" data-dismiss="alert">&times;</button>
         <strong>Operação realizada com sucesso!</strong></div>';
	 
	
		
	   Utils::redirect('documentopdi', 'editardocpdi',array('msg'=>$string));
 
  //   Flash::addFlash($string); 

  ///Utils::redirect('documentopdi', 'editardocpdi');

  } ?>

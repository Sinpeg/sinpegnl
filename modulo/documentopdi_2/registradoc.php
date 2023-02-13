<?php
ob_start();
// ini_get('display_errors','on');
if (!ini_get('display_errors')) {
 ini_set('display_errors', 1);
 ini_set('error_reporting', E_ALL & ~E_NOTICE);
}
?>
<?php

$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
$codunidade = $sessao->getCodUnidade();
$unidade=NULL;
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$c=new Controlador();

////////////////////////////////////////////////////////////////////////////////
$nomedoc = addslashes($_POST['nomedoc']);
$anoinicial = addslashes($_POST['anoinicial']);
$anofinal = addslashes($_POST['anofinal']);
$missao = strip_tags(addslashes($_POST['missao']));
$visao = strip_tags(addslashes($_POST['visao']));
$situacao = 'A';
$nomeunidadeselecionada=isset($_POST['cxunidade'])?addslashes($_POST['cxunidade']):NULL;
if ($_POST['op']="E"  && isset($_POST['coddoc'])){
   $coddoc = addslashes($_POST['coddoc']);
}else{
    $coddoc=0;
}
$objdoc = new Documento();
////////////////////////////////////////////////////////////////////////////////
if (!isset($_POST['pdi'])) {
    $pdi = NULL;
} else {
    $pdi = addslashes($_POST['pdi']);
    $daodoc = new DocumentoDAO();
    $rows = $daodoc->buscadocumento($pdi);
    foreach ($rows as $row) {
        $anoinicial = $row['anoinicial'];
        $anofinal = $row['anofinal'];
        
    }
}

//echo var_dump($_POST)."<br>";
//echo var_dump($_FILES)."<br>";

if (!$aplicacoes[36] && !$aplicacoes[40]) {
    print "Erro ao acessar esta aplicação";
    exit();
}

/*echo 'file_uploads: '. ini_get('file_uploads'). '<br />';
echo 'upload_tmp_dir: '. ini_get('upload_tmp_dir'). '<br />';
echo 'upload_max_filesize: '. ini_get('upload_max_filesize'). '<br />';
echo 'max_file_uploads: '. ini_get('max_file_uploads'). '<br />';die;*/
//print_r($_FILES);
//echo var_dump($_FILES);die;

$conteudo =NULL;
	$erro = NULL;
	$extensoesOk = ",.rar,.zip,";   
	$arquivo = $_FILES['userfile']['tmp_name'];
	$tamanho = $_FILES['userfile']['size'];
	$tipoarq = "application/octet-stream";//$_FILES['userfile']['type']; //
	$nome = $_FILES['userfile']['name'];
	$erroarq = $_FILES['userfile']['error'];
	$tmpName = $_FILES['userfile']['tmp_name'];
	$passou=0;
	$extensao = "," . strtolower(substr($nome,(strlen($nome) - 4))) . ",";
	$unidadePlano=NULL;
	//echo "tamanho"."erro". $_FILES['userfile']['error'];die;
	if ($c->getProfile($sessao->getGrupo()) && is_null($nomeunidadeselecionada) && $coddoc==0){
	         $erro="Selecione a unidade";
	         
	} else  if($_FILES['userfile']['error'] != 0){
	    $erro = 'Um erro ocorreu enquanto o arquivo estava sendo importado. '. 'Código de Erro: '. intval($_FILES['userfile']['error']);
	}else if  (strpos($extensoesOk, $extensao)<0){
	    $erro = "Envie arquivos compactados (extens&atilde;o .rar ou .zip).";
	}else if ($tamanho < 0 || $tamanho['size'] > 10485760  ) {
	    $erro='Tamanho do arquivo deve ser maior que 0 e menor do que 2 Mbytes!';
	}else if (is_executable($_FILES['userfile']['tmp_name'])){
	    $erro='O arquivo não pode ser um executável';
	}else if (!preg_match('/^([1-9][0-9]*)$/', $pdi) && isset($pdi)) {
	    $erro = "Selecione o documento";
	} else if ($nomedoc == "") {
	    $erro = "Preencha o campo nome do documento";
	} else if ($anoinicial == "") {
	    $erro = "Preencha o ano inicial";
	} else if (!preg_match('/^(2)([0-9]){3}$/', $anoinicial)) {
	    $erro = "Informe corretamente o ano inicial";
	} else if ($anofinal == "") {
	    $erro = "Preencha o ano final";
	} else if (!preg_match('/^(2)([0-9]){3}$/', $anofinal)) {
	    $erro = "Informe corretamente o ano final";
	} else if (($anofinal < $anoinicial)) {
	    $erro = "O ano final deve ser maior que o inicial";
	} else if ($missao == "") {
	    $erro = "Preencha o campo missão";
	} else if ($visao == "") {
	    $erro = "Preencha o campo visão";
	} else {
	    if (!is_null($nomeunidadeselecionada)){
	        $unidao=new UnidadeDAO();
	        $rows=$unidao->unidadePorStr($_POST['cxunidade'], 1);
	        foreach ($rows as $row){
	            $unidade=new Unidade();
	            $unidade->setCodunidade($row['CodUnidade']);
	        }
	    }
	    //verificar periodo se ja existe
	    $daodoc = new DocumentoDAO();
	    $objdoc->setNome($nomedoc);
	    $objdoc->setAnoInicial($anoinicial);
	    $objdoc->setAnoFinal($anofinal);
	    $objdoc->setMissao($missao);
        $objdoc->setVisao($visao);
	    $objdoc->setUnidade($unidade);
	    $objdoc->setSituacao($situacao);
	    $objdoc->setCodigo($pdi);
	    $objdoc->setNomearq("../public/pdu/pdu2017_2020/".$nome);
	    //echo $objdoc->getNomearq()."ddddd";die;
	    $objdoc->setTamarq($tamanho);
	    $objdoc->setTipoarq($tipoarq);
	
	    
	    $fp = fopen($arquivo, "rb1");
	    $conteudo = fread($fp, $tamanho);
	    $conteudo = addslashes($conteudo);
	    fclose($fp);
	    
	    
	    $objdoc->setAnexo($conteudo);
	    
	    $cadastro = true;
	    $rows = $daodoc->buscadocumento($coddoc);
	    foreach ($rows as $row) {
	       // if ($row['CodUnidade'] == $codunidade) {
	            $cadastro = false;
	            $objdoc->setCodigo($row['codigo']);
	            $unidadePlano=new Unidade();
	            $unidadePlano->getCodunidade($row['CodUnidade']);
	            $objdoc->setUnidade($unidadePlano);
	       // }
	    }
	
	    $objdoc->setTipo((($unidade!=NULL && $unidade->getCodunidade()==938) || ($unidadePlano!=NULL && $unidadePlano->getCodunidade()==938))?1:2);
	    if ($cadastro &&  $objdoc->getAnexo()!=NULL) {
	        $objdoc->setUnidade($unidade);
	        $linhas=$daodoc->insere($objdoc);
	        $passou=1;
	  		$uploaddir = '../public/pdu/pdu2017_2020/';
			$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
			
			echo '<pre>';
			if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
				
			   $string.= "Arquivo válido e enviado com sucesso.\n";
			} else {
			     $string.= "Possível problema de upload de arquivo!\n";
			     $string.= 'Aqui está mais informações de debug:';
			    print_r($_FILES);
			}
			print "</pre>";
	        
	        
	        $string = "Documento cadastrado com sucesso!";
	    } else {

	        $daodoc->altera($objdoc);
	        $passou=1;
	        $uploaddir = '../public/pdu/pdu2017_2020/';
			$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
			if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
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

 if ($passou==1){
 		    
 	//Erro na entrada de dados. Não foi possível carregar o arquivo para o servidor.Arquivos devem ter tamanho menor ou igual a 2Mbytes!
	//Flash::addFlash($erro); 
	 $string='<div class="alert alert-success">
        <button type="button " class="close" data-dismiss="alert">&times;</button>
         <strong>Operação realizada com sucesso!</strong></div>';
	 
	
		
	   Utils::redirect('documentopdi', 'editardocpdi',array('msg'=>$string));
	    
	
}else if ($erro!=NULL){ 
	
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
 
    Flash::addFlash($string); 

  Utils::redirect('documentopdi', 'editardocpdi');

  } ?>

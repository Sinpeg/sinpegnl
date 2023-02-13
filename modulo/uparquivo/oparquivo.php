<?php

//session_start();
if (!isset($_SESSION["sessao"])) {
    header("location:index.php");
} else {
    $sessao = $_SESSION["sessao"];
    
//    $nomeunidade = $sessao->getNomeUnidade();
//    $codunidade = $sessao->getCodUnidade();
    $responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnoBase();
    $codusuario = $sessao->getCodusuario();
    $data = $sessao->getData();
    $unidao=new UnidadeDAO();
    $dao = new ArquivoDAO();
    
    $rows=$unidao->buscaidunidadeRel($sessao->getCodunidade());
    foreach ($rows as $r){
        $rowsigla=$r['sigla'];
    }
    
 // echo $_FILES['userfile']['tmp_name'].'-'.$_POST["assunto"].'errro'.$_FILES['userfile']['error'];die;
   $passou=0;
    $codigo = 0;
    if (($_FILES['userfile']['size'] > 0 && ($_FILES['userfile']['size'] <= 20488576) && !is_executable($_FILES['userfile']['tmp_name']))) {
      if($_FILES['userfile']['error'] != 0){
      	echo 'Ocorreu um erro no upload do arquivo. '
      			. 'Error code: '. intval($_FILES['userfile']['error']);
      }else{
      	$arquivo = $_FILES['userfile']['tmp_name'];
        $tamanho = $_FILES['userfile']['size'];
        $assunto = $_POST["assunto"];
        
        $tipo = "application/octet-stream";//$_FILES['userfile']['type'];
        if ($assunto==1){
        	
        	$nome = $_FILES['userfile']['name'];
        	//$nome1 =substr($nome1,strlen($nome1)-4,strlen($nome1)-1);
        }else{
        	//die;
        	$nome = $_FILES['userfile']['name'];
        	
        }
        
        $erro = $_FILES['userfile']['error'];
        
        
        $tmpName = $_FILES['userfile']['tmp_name'];
        
        $comentario = $_POST["comentario"];
        
        $operacao = "I";
        
            $rows = $dao->buscaAssunto($codusuario, $anobase, $assunto);
            foreach ($rows as $row) {
                $operacao = "A";
                $codigo = $row['Codigo'];
            }
         //   echo "aquiss";die;
        $comentario = $_POST['comentario'];
        $passou=0;
        if (is_string($comentario) && $arquivo != "none"
        ) {
           $fp = fopen($arquivo, "rb");
           $conteudo = fread($fp, $tamanho);
           $conteudo = $conteudo;
           fclose($fp);
           
           // $conteudo = file_get_contents($_FILES  ['userfile']['tmp_name']);//$fp;
            $u = new Usuario();
            $u->setCodusuario($codusuario);
            $u->setResponsavel($responsavel);
                        	//echo "passou".$operacao;die;
            $uploaddir = '../public/raa_anexos1/ano'.trim($anobase).'/';
            $nome1=$nome; 
            $tam=strlen($nome)-4;
            $exten=substr($nome,$tam);
            if ($rowsigla!=NULL){
                $nome='RA_'.$rowsigla.$sessao->getAnoBase().$exten;
            }
            $uploadfile = $uploaddir . basename($nome);
            if ($operacao == "I") {
                $u->criaArquivo(null, $assunto, $tipo, $nome, $tamanho, $conteudo, $comentario, null, $data, $anobase);
                $dao->insere($u);
                if (move_uploaded_file($arquivo, $uploadfile)) {
                    $string.= "Arquivo válido e enviado com sucesso.\n";
                } else {
                    
                    $string.= "Possível problema de upload de arquivo!\n";
                    $string.= 'Aqui estão mais informações de debug:';
                    print_r($_FILES);die;
                }
                print "</pre>";
                $passou=1;
                 ?>
                    <div id="success">
                          <img src="webroot/img/accepted.png" width="30" height="30"/>Arquivo atualizado com sucesso!</div>
                <?php 
            } elseif ($operacao == "A") {
                if ($codigo == 0) {
                    $codigo = $_POST['codigo'];
                }
                if ($codigo != "" && is_numeric($codigo)) {
                    $u->criaArquivo($codigo, $assunto, $tipo, $nome, $tamanho, $conteudo, $comentario, $data, null, $anobase);
                    $dao->altera($u);
                    if (move_uploaded_file($arquivo, $uploadfile)) {
                        $string.= "Arquivo válido e enviado com sucesso.\n";
                    } else {
                        echo "aqui2";die;
                        
                        $string.= "Possúvel problema de upload de arquivo!\n";
                        $string.= 'Aqui estão mais informações de debug:';
                        print_r($_FILES);die;
                    }
                    print "</pre>";
                    $passou=1;
                    ?>
                    <div id="success">
                          <img src="webroot/img/accepted.png" width="30" height="30"/>Arquivo atualizado com sucesso!</div>
                <?php }
            }
        } else {
          
            ?>
            
            <div class="erro">
		        <img src="webroot/img/error.png" width="30" height="30"/>
		        <?php print "Erro na entrada de dados. Não foi possível carregar o arquivo para o servidor.";?>
		        
            </div>
      <?php   }
      }
    } else {
        ?>
            
            <div class="erro">
		        <img src="webroot/img/error.png" width="30" height="30"/>
		        <?php print "Arquivo inv&aacute;lido! Pode estar vazio ou ter tamanho maior que 2Mbytes. Não foi possível carregar o arquivo para o servidor";?>
		        
            </div>
      <?php 
    }
}
//echo $passou;
if ($passou==0){
  $string='<div class="erro">
		        <img src="webroot/img/error.png" width="30" height="30"/>
		        Erro na entrada de dados. Não foi possível carregar o arquivo para o servidor.Arquivos devem ter tamanho menor ou igual a 2Mbytes!
		             </div>'; 
  	Utils::redirect('uparquivo', 'consultaarqs',array('msg'=>$string));
  
}else{
	Utils::redirect('uparquivo', 'consultaarqs');
//	Flash::addFlash("Arquivo enviado com sucesso!");
}
?>
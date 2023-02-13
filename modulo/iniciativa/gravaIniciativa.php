<?php


//session_start();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
$codunidade = $sessao->getCodUnidade();
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$c=new Controlador();
////////////////////////////////////////////////////////////////////////////////
if (!empty($_POST['codIniciativa'])){
	$codiniciativa=$_POST['codIniciativa'];
}
$nome=$_POST['nome'];
$anoinicio=$_POST['anoinicio'];

// var_dump($_POST);die;
//echo var_dump($_POST)."<br>";
//echo var_dump($_FILES)."<br>";


if (!$aplicacoes[36] && !$aplicacoes[40]) {
	print "Erro ao acessar esta aplicação";
	exit();
}
//echo "tamanho".filesize($_FILES['userfile']['tmp_name'])."erro". $_FILES['userfile']['error'];


if(!isset($nome)){
	$erro = "Informe o nome!";
}else if  (!isset($anoinicio)){
	$erro = "Informe o ano de início da iniciativa!";
}
//verificar periodo se ja existe
$daoiniciativa = new IniciativaDAO();

$iniciativa = new Iniciativa();
$iniciativa->setNome($nome);
$iniciativa->setUnidade($unidade);
$iniciativa->setAnoinicio($anoinicio);
//$iniciativa->setCoordenador($coordenador);
$erro = NULL;
if ($erro== NULL){
	
	$funcao = $_POST["funcao"];
	
	if ($funcao == "gravar") {
		$idIniciativa = $daoiniciativa->insere($iniciativa);
		
		$_SESSION['idIniciativa'] = $idIniciativa;
		$string = "Iniciativa cadastrada com sucesso! Escolhas os Indicadores referentes a iniciativa.";
	} else if($funcao == "editar") {
		
		$iniciativa->setCodIniciativa($codiniciativa);


		$daoiniciativa->altera($iniciativa);
		$string = "Iniciativa atualizada com sucesso! Agora, você pode vincular o indicador à iniciativa.";
	}

}
if ($erro != NULL){ ?>

    <div class="erro">
        <img src="webroot/img/error.png" width="30" height="30"/>
        <?php print $erro; ?>
    </div>
  <?php    
      Flash::addFlash($erro);
     
      	Utils::redirect('iniciativa', 'editaIniciativa');
      
     } else { ?>
    <div id="success">
        <img src="webroot/img/accepted.png" width="30" height="30"/><?php print $string; ?>
    </div> 
 <?php  
      Flash::addFlash($string);
      if ($funcao == "gravar") {
      // Utils::redirect('iniciativa', 'registraIniciativa');
      }
      if ($funcao == "editar"){
      //	Utils::redirect('iniciativa', 'listaIniciativa');
      }

  } ?>


<?php
define('BASE_DIR', dirname(__FILE__));
require_once(BASE_DIR . DIRECTORY_SEPARATOR . '../../dao/PDOConnectionFactory.php');
require_once(BASE_DIR . DIRECTORY_SEPARATOR . '../../flash/Flash.php');
require '../../modulo/iniciativa/classe/Iniciativa.php';
require '../../classes/unidade.php';
require '../../classes/Controlador.php';
require '../../modulo/iniciativa/dao/IniciativaDAO.php';
require_once '../../classes/sessao.php';
require '../../util/Utils.php';
require '../../dao/unidadeDAO.php';

session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
$codunidade = $sessao->getCodUnidade();

$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$c=new Controlador();

$unidadeDAO = new UnidadeDAO();

$acao = $_POST['funcao'];
$cxunidade = $_POST['cxunidade'];

if (!$aplicacoes[36] && !$aplicacoes[40]) {
	print "Erro ao acessar esta aplicação";
	exit();
}

$erro=NULL;
 if ($_POST['nome']==""){
	$erro = "Informe o nome da iniciativa!";
}


if($codunidade == 938 && $cxunidade ==""){
	$erro = "Informe a unidade!";
}elseif( $codunidade == 938 && $cxunidade !=""){
	//busca do código da unidade selecionada
	$unidade = $unidadeDAO->buscarCodUnidadeByNome($cxunidade);
}

if ($erro == NULL){ 
	$nome= strip_tags($_POST['nome']); 
    $anoinicio=$sessao->getAnobase();
   	$finalidade=NULL;
   
    //verificar periodo se ja existe
    $iniciativa = new Iniciativa();
    $daoiniciativa = new IniciativaDAO();

    $iniciativa->setNome($nome);
    
    $iniciativa->setUnidade($unidade);
    
    $iniciativa->setAnoinicio($anoinicio);
    
    $_POST['situacao']==2?$iniciativa->setAnofinal($sessao->getAnobase()):$iniciativa->setAnofinal(NULL);
    
    $rows=$daoiniciativa->buscaPorNome($nome);
    
    $codigo=0;    
	foreach ($rows as $r){
        $codigo=$r['codIniciativa'];
    }
   
	if ($acao=="gravar") {
		$daoiniciativa->insere($iniciativa);
		$string = "Iniciativa cadastrada com sucesso! Vincule os Indicadores referentes à iniciativa.";
	} else if($acao=="editar") {
		
		$iniciativa->setCodIniciativa($_POST['codIniciativa']);
		
		$daoiniciativa->altera($iniciativa);
		$_SESSION['idIniciativa']=NULL;
		$string = "Iniciativa atualizada com sucesso! Agora, você pode vincular o indicador à iniciativa que NÃO estiver cancelada.";
	}
?>
	<div class="alert alert-success" role="alert">
     <div id="success">
        <img src="webroot/img/accepted.png" width="30" height="30"/><?php print $string; ?>
     </div> 
     </div>
 <?php }else { ?>
    <div class="erro">
        <img src="webroot/img/error.png" width="30" height="30"/>
        <?php print $erro; ?>
    </div> 
  <?php    
    }
 ?>


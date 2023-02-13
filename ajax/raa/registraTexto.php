<?php

require '../../classes/unidade.php';
require '../../dao/PDOConnectionFactory.php';
require '../../classes/sessao.php';

//require '../../classes/Controlador.php';
//require '../../modulo/raa/dao/TopicoDAO.php';
require '../../modulo/raa/dao/textoDAO.php';

require '../../modulo/raa/classes/topico.php';
require '../../modulo/raa/classes/modelo.php';
require '../../modulo/raa/classes/texto.php';
require '../../util/Utils.php';

session_start();
//$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
$codunidade=NULL;
$codtopico=NULL;
$texto=NULL;


$codtopico=$_POST['codtopico'];
$texto=$_POST['texto'];
$codtexto=$_POST['codtexto'];
$anobase=$sessao->getAnobase();

$aplicacoes = $sessao->getAplicacoes();
$codunidade = $sessao->getCodUnidade();
$unidade=NULL;
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);


//echo $_POST['texto']."-".$_POST['codtopico']."<br>";
//$texto = addslashes(base64_encode(gzcompress(serialize($_POST['texto']))));



//if (!$aplicacoes[1] ) {
//	print "Erro ao acessar esta aplicação";
//	exit();
//}
$codtexto=NULL;
$erro = NULL;
if ($texto == NULL || $texto=='<br>') {
	$erro = "O campo texto é obrigatório!";
} else if ($anobase<=0){
    	$erro = "Erro na aplicação, comunique a DINFI pelo fone 3201-8504";

}
	//verificar periodo se ja existe
	$textobj = new Texto();
	$textobj->setAno($anobase);	
	$textobj->setDesctexto($texto);
	$topico=new Topico();
	$topico->setCodigo($codtopico);
	$textobj->setTopico($topico);
	$daotexto=new TextoDAO();
	$unidade=new Unidade();
	$unidade->setCodunidade($codunidade);
	$textobj->setUnidade($unidade);
	
//	echo $codtopico.",".$anobase.",".$codunidade; //die;
	$rows = $daotexto->buscaTexto($codtopico,$anobase,$codunidade);
	
	 if ($rows!=NULL){
	    foreach ($rows as $r){
	        $codtexto=$r['codigo'];
	        $textobj->setCodigo($codtexto);
	        
	    }
  }

if ($erro==NULL){
	
	if ($codtexto==NULL) {		
	    
		$daotexto->insere($textobj);
		$string = "Operação realizada com sucesso!";

	} else if($codtexto>0) {
	    //echo $codtexto;
		$daotexto->altera($textobj);
		$string = "Cadastro atualizado com sucesso!";

	}
    
	//$daotexto->fechar();?>
	<div id="success">
        <img src="webroot/img/accepted.png" width="30" height="30"/><?php print $string; ?>
    </div> 
<?php }
if ($erro != NULL){ ?>
    <div class="erro">
        <img src="webroot/img/error.png" width="30" height="30"/>
        <?php print $erro; ?>
    </div>
 <?php }?>

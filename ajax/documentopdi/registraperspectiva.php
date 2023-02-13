<?php
/* DAO */
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/sessao.php';
require_once '../../classes/unidade.php';
require_once '../../dao/unidadeDAO.php';
require_once '../../modulo/documentopdi/dao/DocumentoDAO.php';
require_once '../../modulo/documentopdi/dao/PerspectivaDAO.php';
/* Model */
require_once '../../modulo/documentopdi/classe/Documento.php';
require_once '../../modulo/documentopdi/classe/Perspectiva.php';
require_once '../../util/Utils.php';
?>
<?php
session_start();
$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodunidade();
$aplicacoes = $sessao->getAplicacoes(); // código das aplicações
?>
<?php
if (!$aplicacoes[38]) {
    print "Erro ao acessar esta aplicação";
    exit();
}
?>
<?php
//$coddoc = addslashes($_POST['doc']);
//$objcod = addslashes($_POST['objetivo']);
//$propindicador = addslashes($_POST['propindicador']);
$perspectiva = addslashes($_POST['perspectiva']);
$acao = addslashes($_POST['acao']);
?>
<?php
$erro = "";
if ($perspectiva == "") {
    $erro = "Preencha o nome da Perspectiva!";
}
else {	
	$daoperspectiva = new PerspectivaDAO();
	$dao = new PerspectivaDAO();
	$p = new Perspectiva();
	$codigo=0;
	if(!empty($_POST['codPerspectiva'])){
		$codigo=$_POST['codPerspectiva'];
	}else if ($_SESSION['idPerspectiva']!=NULL){
		$codigo=$_SESSION['idPerspectiva'];
	}
	if ($codigo==0){
		$p->setNome($perspectiva);
		$idPerspectiva = $daoperspectiva->insere($p);
		
		$_SESSION['idPerspectiva'] = $idPerspectiva;
		$string = "Perspectiva cadastrada com sucesso!";
	} else if($codigo!=0){
		$p->setNome($perspectiva);
		$p->setCodigo($codigo);
		$daoperspectiva->altera($p);
		$_SESSION['idPerspectiva']=NULL;
		$string = "Perspectiva atualizada com sucesso!";
	}
	/*
	 $daoperspectiva = new PerspectivaDAO();
	 $p = new Perspectiva();
	 $p->setNome($perspectiva);
            
        if ($acao == "I") {
        	$daoperspectiva->insere($p);      	
        	$string = "Dados gravados com sucesso!";
        
        } else if ($acao == "A"){
        	$p->setCodigo($_POST['codPerspectiva']);
        	$daoperspectiva->altera($p);
        	$string = "Perspectiva atualizada com sucesso!";
        }*/
          
    }
?>
<?php if ($erro != ""){ ?>
    <div class="erro">
        <img src="webroot/img/error.png" width="30" height="30"/>
        <?php print $erro; ?>
        <span class="plus"></span><a href="<?php echo Utils::createLink('documentopdi', 'inserirperspectiva'); ?>">Voltar</a>
    </div>
<?php }else{ ?>
 <div id="success">
        <img src="webroot/img/accepted.png" width="30" height="30"/>
        <?php print $string;?>
    </div>
<?php } ?>


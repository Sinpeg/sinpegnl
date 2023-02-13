<?php
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/sessao.php';
require_once '../../classes/unidade.php';
require_once '../../dao/unidadeDAO.php';
require_once '../../modulo/documentopdi/dao/DocumentoDAO.php';
require_once '../../modulo/objetivopdi/dao/MapaDAO.php';
require_once '../../modulo/indicadorpdi/dao/IndicadorDAO.php';
//require_once '../../modulo/metapdi/dao/MetaDAO.php';
/* Model */
require_once '../../modulo/documentopdi/classe/Documento.php';
require_once '../../modulo/objetivopdi/classe/Mapa.php';
require_once '../../modulo/indicadorpdi/classe/Indicador.php';
require_once '../../util/Utils.php';
require_once '../../modulo/indicadorpdi/dao/MapaIndicadorDAO.php';

session_start();
$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade(); // código da unidade
$nomeunidade = $sessao->getNomeUnidade();
$aplicacoes = $sessao->getAplicacoes(); // código das aplicações
$anobase = $sessao->getAnobase();       // ano base
$codestruturado = $sessao->getCodestruturado(); // código estruturado
$daounidade=new UnidadeDAO();

if (!$aplicacoes[38]) {
    print "Erro ao acessar esta aplicação";
    exit();
}
$codunidade=0;
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
if ($codunidade>0){

		$daomapaindicador = new MapaIndicadorDAO();
		if($codindicador != null  && $codmapa != null && $codunidade != null ){
			$daomapaindicador->insere($codindicador,$codmapa,$codunidade);
			$daomapaindicador->fechar();?>
	<div id="success">
        <img src="webroot/img/accepted.png" width="30" height="30"/>
        <?php print "Indicador vinculado com sucesso!";?>
         <span class="plus"></span><a href="<?php echo Utils::createLink('indicadorpdi', 'consultaindicadorproprio'); ?>">Consultar indicador</a>     
    </div>
		<?php 
		}
		
		else {?>

 <div class="erro">
        <img src="webroot/img/error.png" width="30" height="30"/>
        <?php print "Falha ao vincular o indicador!"; ?>
        <span class="plus"></span><a href="<?php echo Utils::createLink('indicadorpdi', 'consultaindicador'); ?>">Voltar</a>
    </div>
			
		<?php }
		    
}else{?>
	<div class="erro">
        <img src="webroot/img/error.png" width="30" height="30"/>
        <?php print "Informar unidade proprietária do indicador!"; ?>
        <span class="plus"></span><a href="<?php echo Utils::createLink('indicadorpdi', 'consultaindicador'); ?>">Voltar</a>
    </div>
<?php }?>	





<?php
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/sessao.php';
require_once '../../classes/unidade.php';
require_once '../../dao/unidadeDAO.php';
require_once '../../modulo/documentopdi/dao/DocumentoDAO.php';
require_once '../../modulo/mapaestrategico/dao/MapaDAO.php';
require_once '../../modulo/indicadorpdi/dao/IndicadorDAO.php';
require_once '../../modulo/indicadorpdi/dao/MapaIndicadorDAO.php';
require_once '../../modulo/metapdi/dao/MetaDAO.php';
require_once '../../modulo/calendarioPdi/dao/CalendarioDAO.php';
/* Model */
require_once '../../modulo/documentopdi/classe/Documento.php';
require_once '../../modulo/mapaestrategico/classes/Mapa.php';
require_once '../../modulo/indicadorpdi/classe/Indicador.php';
require_once '../../modulo/indicadorpdi/classe/Mapaindicador.php';
require_once '../../modulo/metapdi/classe/Meta.php';
require_once '../../modulo/calendarioPdi/classes/Calendario.php';

require_once '../../util/Utils.php';
?>
<?php


session_start();
$sessao = $_SESSION['sessao'];
$anobase = $sessao->getAnobase();       // ano base
$codunidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[38]) {
    print "O usuário não tem permissão para acessar este módulo";
    exit();
}


  
$coddoc = $_POST['coddoc'];
$codmapaindicador=$_POST['codmapaind'];
// $coleta = $_POST['coleta'.$i]; // tipo da coleta do indicador

$daometa = new MetaDAO();
$daocalendario = new CalendarioDAO();
$daodoc = new DocumentoDAO();
$rows2 = $daodoc->buscadocumento($coddoc);

$objdoc = new Documento();
foreach ($rows2 as $row2) {
	$objdoc->setCodigo($row2['codigo']);
	$objdoc->setAnoFinal($row2['anofinal']);
	$objdoc->setAnoInicial($row2['anoinicial']);
	
}

  if ($anobase<=2020 && $codunidade!=938){
        	$tempano=2020;
  }else $tempano=$objdoc->getAnoFinal();

$erro="";
$anoInicialNaoOficial=$objdoc->getAnoInicial()==2016?2017:$objdoc->getAnoInicial();
for($i=$anoInicialNaoOficial; $i<=$tempano;$i++){			
			$objmeta = new Meta();
			$arraycal = $daocalendario->buscaCalendarioporAnoBaseOnly($i)->fetch();
			$codcal = $arraycal['codCalendario'];
			$meta=NULL;
			$meta = $_POST['meta'.$i]; // valor da meta
			
			
			$metrica = addslashes($_POST['metrica'.$i]); // métrica

			//echo $coddoc."-".$i;
			if (($erro=="") &&  ($meta=="")) {
					$erro = "Preenchaa corretamente o valor para a meta!".$_POST['meta'.$i];
					
			}
			?>
			<?php if ($erro == ""): 
				    $metabd = $daometa->buscarmetamapaindicador($codmapaindicador, $i);

				    foreach ($metabd as $bdmeta){
				         //$codigometa = (!empty($bdmeta))?$bdmeta['Codigo']:null;
				         $objmeta->setCodigo($bdmeta['Codigo']);  
				    }

				    // atualiza o registro
				    $mi=new Mapaindicador();
				    $mi->setCodigo($codmapaindicador);
				    $objmeta->setMapaindicador($mi);
				    $objmeta->setPeriodo('P');
				    $c=new Calendario();
				    $c->setCodigo($codcal);	
				    $objmeta->setCalendario($c);
				    $objmeta->setAno($i);
				    $objmeta->setMeta(str_replace(",",".",$meta));
				    $objmeta->setMetrica($metrica);
				    $objmeta->setCumulativo("0");
				    $objmeta->setAnoinicial($anobase);
				    $objmeta->setPeriodoinicial(1);
				    $arraymeta[$i] = $objmeta;
				    // Fim
			    endif;
}

if($erro==""){

        $metta = $daometa->buscaMetaResultadoporCodMapaIndiOnly($codmapaindicador,$anobase)->fetch();
 
		if (empty($metta)){
			//echo 'até aqui 1';die;
			$rows_meta = $daometa->insereAlll($arraymeta);
		}else{
			$rows_meta = $daometa->updateAlll($arraymeta);
		}
 ?>
	   
	   <div class="alert alert-success" role="alert">
		  <img src="webroot/img/accepted.png" width="30" height="30"/><?php print "Meta cadastrada com sucesso!"; ?>
		</div>
	   
	        
	        <span class="plus"></span><a href="<?php echo Utils::createLink('indicadorpdi', 'consultaindicador'); ?>">Consultar indicador</a>
	    
<?php }else {?>
	     
	     <div class="alert alert-danger" role="alert">
			  <img src="webroot/img/error.png" width="30" height="30"/>
        <?php print $erro; ?>
		 </div>        
        <span class="plus"></span><a href="<?php echo Utils::createLink('indicadorpdi', 'consultaindicador'); ?>">Voltar</a>
        
         
 <?php } ?> 
 
 
 
 
 

 
 
 
 
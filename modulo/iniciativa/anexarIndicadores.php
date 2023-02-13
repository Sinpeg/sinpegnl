<?php


$sessao = $_SESSION['sessao'];
$codUnidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();
?>

<?php 
	//verifica unidade
	
	$grupos=$sessao->getGrupo();
	$interno = false;
	foreach ($grupos as $grupo){
		if ($grupo == "18"){
			$interno = true;
		}
	}
	
	
	$codIniciativa = $_SESSION['idIniciativa'];
	
?>


<?php 	

	$codDocumento = $_GET['codDocumento'];

	$daoindicador = new IndicadorDAO();
	$daoobjetivo = new ObjetivoDAO();
	$arrayobjetivo = $daoobjetivo->buscaObjetivoPorDocumento($codDocumento);
	
	$i=0;
	foreach ($arrayobjetivo as $objetivo){
		$queryIndicadores = 0;
		$queryIndicadores = $daoindicador->buscaIndicadorPorObjetivoDocumento($objetivo["Codigo"], $codDocumento);
		$j = 0;
		$Indicadores = null;
		foreach ($queryIndicadores as $indicador){
			$Indicadores[$j++] = $indicador;
		}
		$objetivo['indicadores'] = $Indicadores;
		$objetivos[$i++] = $objetivo;
		
	}
	
// 	echo "<pre><br>";var_dump($objetivos);die;


?>

<form class="form-horizontal" method="post" action="<?php print Utils::createLink('iniciativa', 'gravaIndicIni'); ?>" >
	<input class="form-control"type="hidden" name="codIniciativa" value="<?php print $codIniciativa; ?>">
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	  
	  
	  <?php $cont = 0; foreach ($objetivos as $objet){ $cont++;?>
	  	
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingOne">
		      <h4 class="panel-title">
		        <a role="button" data-toggle="collapse" data-parent="#accordion" href="<?php print "#collapse".$cont; ?>" aria-expanded="false" aria-controls="collapseOne<?php print $cont; ?>">
				  
		          
		          <?php print $objet['perspectiva']." - ".$objet['Objetivo']; ?>
		        </a>
		      </h4>
		    </div>
		    <div id="<?php print "collapse".$cont; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
		      <div class="panel-body">
		        <table>
		        	<tr>
		        		<th>Indicador</th>
		        		<th>Vincular</th>
		        	</tr>
		        	<?php foreach ( $objet['indicadores'] as $indicador){$cont2++;?>
		        		
		    	    	<tr>
		    	    		<td><?php print $indicador['nome']; ?></td>
		    	    		<td><input class="form-check-input"name="codmapaind<?php print $cont."-".$cont2;?>" value="<?php print $indicador['codMapaInd']; ?>" type="checkbox"></td>
		    	    			   
		 	    	   	</tr>	
		 	    	   	
		        	<?php } $cont2 = 0; ?>
		        </table>
		      </div>
		    </div>
		  </div>
	  <?php }?>
	  
	</div>
	
	<input class="btn btn-info" type="submit"  name="acao" value="Vincular">

</form>

<script>
	$('#myTabs a').click(function (e) {
	  e.preventDefault()
	  $(this).tab('show')
	})
	
	function anexar(element){

		
		idIndicador = element.getAttribute("idInidicador");
		idObjetivo = element.getAttribute("idobjetivo");
		idPerspectiva = element.getAttribute("idPerspectiva");
		acao = element.getAttribute( "acao" );
		  $.ajax({url: "ajax/iniciativa/inianexar.php", type: 'POST', data: {idUnidade:"<?php print $codUnidade; ?>",idPerspectiva:idPerspectiva,idObjetivo:idObjetivo,codDocumento:"<?php print $codDocumento; ?>",idIndicador:idIndicador,acao:acao}, success: function(data) {
              if(acao = 'anexar'){
				  element.acao = "rrrrr";
              }else if(document.getElementById("desanexar")){
            	  
              }
          }});
		
	}
</script>
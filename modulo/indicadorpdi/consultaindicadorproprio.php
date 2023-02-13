<?php
$sessao = $_SESSION['sessao'];
$codestruturado = $sessao->getCodestruturado();
$codUnidade = $sessao->getCodunidade();
$aplicacoes = $sessao->getAplicacoes(); // código das aplicações
//$codmapa = $_POST['codmapa'];
$coddoc=$_SESSION['coddoc'] ;
$codmapa = $_SESSION['codmapa'];

$daodoc = new DocumentoDAO();

$c=new Controlador();
if (!$c->getProfile($sessao->getGrupo())) {
   $rowsdoc = $daodoc->buscadocumentoporunidadePeriodoSemPDI($codUnidade, $sessao->getAnobase());
}else{
   $rowsdoc = $daodoc->lista($anobase);
  
}

$daoobjetivo=new ObjetivoDAO();
$rowsobjetivo = $daoobjetivo->buscaObjetivoPorMapa($codmapa);
	$objobjetivo = new Objetivo();
	foreach ($rowsobjetivo as $rowobjetivo)
	{
		$objobjetivo->setCodigo($rowobjetivo['codobj']);
		$objobjetivo->setObjetivo($rowobjetivo['des']);
	}

if (!$aplicacoes[38]) {
    print "Erro ao acessar esta aplicação";
    exit();
}

$daoind = new IndicadorDAO();
$objind = array();
$cont3 = 0;
	$rows = $daoind->listaIndicadorNaoVinculado1($sessao->getAnobase(),$coddoc,$codUnidade);
	foreach ($rows as $row) {
		$objind[$cont3] = new Indicador();
		$objind[$cont3]->setCodigo($row['Codigo']);		
		$objind[$cont3]->setNome($row['nome']);
		$objind[$cont3]->setValidade($row['validade']);
		$objind[$cont3]->setCesta($row['cesta']);
		
		$cont3++;
}

?>

<div class="bs-example">
	<ul class="breadcrumb">
		<li class="active"><a href="<?php echo Utils::createLink('mapaestrategico', 'listamapapdu'); ?>">Painel <?php print $sessao->getCodunidade()==938?"Estratégico":"Tático"; ?></a> 
		<i class="fas fa-long-arrow-alt-right"></i>
		<a href="<?php echo Utils::createLink('indicadorpdi', 'consultaindicador'); ?>" >Indicadores Vinculados</a> 
		<i class="fas fa-long-arrow-alt-right"></i>
		<a href="#" >Vincular Indicador</a></li>  
	</ul>
</div>

<div class="card card-info">
	<div class="card-header">
    	<h3 class="card-title">Vincular Indicador</h3>
	</div>
	<div class="card-body">
		<tr>
			<td>    
				<label>Objetivo:</label>
			</td>
			<td>
				<?php echo $objobjetivo->getObjetivo()."<br>"; ?>
			</td>

			<?php if ($sessao->getCodunidade()==938){?>
		
			<?php }else{?>
			<td></td><td></td>
			<?php }?> 
		</tr>
	</div>
    <p></p>
  
 	<div class="card-body">
		<table class="table table-bordered table-hover" id="tabelaIndicadorProprio">
			<thead>
				<tr>
					<th>Indicador</th>
					<th>Cesta de Indicadores</th>
					<th>Editar</th>
				    <th>Excluir</th>
					<th>Vincular</th>
				</tr>
			</thead>	
			<tbody>
				<?php for ($i = 0; $i < $cont3; $i++) { ?>
					<tr>               
						<td><?php print ($objind[$i]->getNome());  ?></td>
						<td> 
							<?php 
							switch ($objind[$i]->getCesta()){
								case 0: print 'PDI 2011';
										break;
								case 1: print 'PDI';
										break;
								case 2: print 'Essencial';
										break;
								case 3: print 'Opcional';
										break;
								case 4: print 'PDU';
										break;
							}
							?>
						</td>

						<td align="center"> 
							<?php if ($objind[$i]->getCesta()==4){?>
							<form class="form-horizontal" method="post" action="<?php echo Utils::createLink('indicadorpdi', 'editarindicador'); ?>">
								<input class="form-control"type="hidden" name="ind" value="<?php echo $objind[$i]->getCodigo(); ?>" />
								<input class="form-control"type="hidden" name="mapa" value="<?php echo $codmapa; ?>" />
								<input class="form-control"type="hidden" name="des" value="<?php echo '0'; ?>" />
								<input class="form-control"type='image' value="editar" width="25" height="25" src="webroot/img/editar.gif" />
								</form>
							<?php } ?>
						</td>
						<td align="center">
							<?php if ($objind[$i]->getCesta()==4){?>
								<button style="border: none" onclick="deletaindicador(this)" id="<?php echo "btn{$i}"; ?>" value="<?php echo ($objind[$i]->getCodigo()); ?>"><img src ="webroot/img/delete.png"/></button>
							<?php } ?>
						</td>
						<td align="center">  
							<form class="form-horizontal" method="post" action="<?php echo Utils::createLink('indicadorpdi', 'regvincularindicador'); ?>">
								<input class="form-control"type="hidden" name="ind" value="<?php echo $objind[$i]->getCodigo(); ?>" />
								<input class="form-control"type="hidden" name="mapa" value="<?php echo $codmapa; ?>" />
								<input class="form-control"type="hidden" name="des" value="<?php echo '0'; ?>" />
								<input class="form-control"type='image' value="editar" width="25" height="25" src="webroot/img/maos.png" />
							</form>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>							
 
	<div class="card-body">
    	<div class="incluir">
			<input class="form-control"type="hidden" name="coddoc" value="<?php echo $coddoc; ?>" />
			<input class="form-control"type="hidden" name="codigo" value="<?php echo $codmapa; ?>" />
			<a href="<?php echo Utils::createLink('indicadorpdi', 'incluiindicador'); ?>" >
				<button  type="button" class="btn btn-info btn">Incluir novo indicador</button>
			</a> 
			<div id="dialog-confirm" title="Confirmação" style="display: none">
				<p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Você tem certeza que deseja eliminar o indicador?</p>
			</div>  
    	</div>
	</div>
</div>
</html>

<script>
	$(function () {
		$('#tabelaIndicadorProprio').DataTable({
		"paging": true,
		"sort": true,
		"lengthChange": true,
		"searching": true,
		"ordering": true,
		"info": true,
		"autoWidth": true,
		"responsive": true,
		});
	});

    function deletaindicador(button){
    	$(function() {
    		$( "#dialog-confirm" ).dialog({
    			resizable: false,
    			height: "auto",
    			width: 400,
    			modal: true,
    			buttons: {
    				"Deletar": function() {
    				$( this ).dialog( "close" );
    				 	$.ajax({
    	                        url: "ajax/indicadorpdi/deletaindicador.php", type: 'POST', data: { codindicador: button.value, action : "D"},
    	                        
    	                        async: true,
    	                        success: function(data) {
    		                    //   alert(data);
    	                        if (data.search("SQLSTATE")>0){
    				        		$('div#message').html("<br><img src='webroot/img/accepted.png' width='30' height='30'/>Erro na operação, o indicador pode estar vinculado a metas ou a iniciativas!");
    		                        
    	                        }else{
    			        		$('div#message').html("<br><img src='webroot/img/accepted.png' width='30' height='30'/>Indicador desvinculado com sucesso!");
    			        		var teste = "#"+button.id;
    			        	 	teste = $(teste).parent().parent();
    			        	 	teste.remove();
    	                        }
    			        	 	
    	                        },
    						error:function(data) {
    							$('div#message').html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'></button><img src='webroot/img/error.png' width='30' height='30'/><strong>Falha ao deletar indicador.</strong></div>");
    	                }						
    		         	});					
    	        },
    	        Cancelar: function() {
    	            $( this ).dialog( "close" );
    	        }
    	      }
    	    });
    	  });
    	}
</script>


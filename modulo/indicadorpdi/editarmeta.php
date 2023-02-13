<script>

  function tecla(){
    evt = window.event;
    var tecla = evt.keyCode;

    if ((tecla > 47 && tecla < 58) || (tecla==44)){ 
    	//alert("ok");
      }else{
    	  alert('Pressione apenas teclas numéricas e a vírgula!');
          evt.preventDefault();
      }
    
  }

</script>
<?php
require_once MODULO_DIR . 'resultadopdi/dao/ResultadoDAO.php';


$sessao = $_SESSION['sessao'];
$coddoc= $_POST['coddoc']; // código do indicador
$codindicador = $_POST['ind'];
$codmapaindicador = $_POST['mapaind'];
$codunidade = $sessao->getCodUnidade(); // código da unidade
$nomeunidade = $sessao->getNomeUnidade();
$aplicacoes = $sessao->getAplicacoes(); // código das aplicações
$anobase = $sessao->getAnobase();       // ano base
$codestruturado = $sessao->getCodestruturado(); // código estruturado

if (!isset($_SESSION["sessao"])) {
	echo "Sessão expirou...";
    exit();
}

if (!$aplicacoes[38]) {
    print "Erro ao acessar esta aplicação";
    exit();
}

$daoindicador = new IndicadorDAO();
$daomapaind = new MapaIndicadorDAO();
$arrayindicador = $daoindicador->buscaindicador($codindicador)->fetch();
/*$rowsmapaind = $daomapaind->buscamapaporindicador($codindicador);
foreach ($rowsmapaind as $rowmapaind) {
	$objmapaind = new Mapaindicador();
	$objmapaind->setCodigo($rowmapaind['codigo']);
	$codmapaindicador = $objmapaind->getCodigo();
}
*/
$_SESSION['codmapaindicador'] = $codmapaindicador ;
$daodoc = new DocumentoDAO();
$rows = $daodoc->buscadocumento($coddoc);

$objdoc = new Documento();
foreach ($rows as $row) {
	$objdoc->setCodigo($row['codigo']);
	$objdoc->setAnoFinal($row['anofinal']);
	$objdoc->setAnoInicial($row['anoinicial']);
	$unidade=new Unidade;
	$unidade->setCodunidade($row['CodUnidade']);
	$objdoc->setUnidade($unidade);
}

$aux_resultMeta = 0; 
$aux_class = "";

$daores = new ResultadoDAO();
$daometa = new MetaDAO();
$querymeta = $daometa->buscaMetaResultadoporCodMapaIndiOnly($codmapaindicador,$anobase);
$cont=0;
$arraymeta=NULL;
foreach ($querymeta as $meta){
	$m=new Meta();
	$m->setCodigo($meta['Codigo']);
	$m->setAno($meta['ano']);
	$m->setMeta($meta['meta']);
	$m->setMetrica($meta['metrica']);
	$m->setAnoinicial($meta['anoinicial']);
	$m->setPeriodoinicial($meta['periodoinicial']);
	$m->setAnofinal($meta['anofinal']);
	$m->setPeriodofinal($meta['periodofinal']);
	$cont++;
	$arraymeta[$cont] = $m;
	
	//Verifica se existe resultado para a referida meta
	$rowsResultMeta = $daores->buscaresultadometa($meta['Codigo']);
	if($rowsResultMeta->rowcount()>0){
		  $aux_resultMeta = 1;
		
		$aux_class = "disabled";
	}
	 
}
$origem=0;
$rowsorigem=$daomapaind->origemDoIndicadorDaUnidadeEPDI($anobase,$codunidade,$codindicador);

if ($rowsorigem->rowcount()>0){
  $origem=1;
  $aux_class = "disabled";
}
//Verifica se pode fazer solicitação de alteração no PDU
$daoResul=new ResultadoDAO();
        if ($objdoc->getUnidade()->getCodunidade()==938 && $objdoc->getUnidade()->getCodunidade()!=$sessao->getCodunidade()){
        	$rows = $daodoc->buscadocumentoporunidadePeriodo($sessao->getCodunidade(), $anobase)->fetch();
        	$rdoc=$rows['codigo'];
        	
        }else{
        	$rdoc=$objdoc->getCodigo();
        }
        
        $rowsresant=$daoResul->verResultadosAnosAnteriores( $sessao->getCodunidade(),$objdoc->getAnoInicial(), $anobase,$coddoc);
	    $contlinhasant= $rowsresant->rowCount();
	    
	    $rowsres=$daoResul->verResultadosAnoPeriodoFinal($sessao->getCodunidade(),$anobase,$coddoc);
		$contlinhas= $rowsres->rowCount();

/*
$passou=0;
$rowsmi1=$daomapaind->buscaIndicadorVinculadoAoObjetivoIncluidoNoPDUporIndicador($anobase, $_POST['codobjpdi'], $codunidade,$codindicador);
if ($rowsmi1->rowcount()>0){
  $passou=1;
}

$c=new Controlador();
if (($arraymeta==NULL) &&     
    (!$c->getProfile($sessao->getGrupo())
   ))
{
	//se grupo for 18  
	       $arraymeta=array();
		   $cont=0;
		   foreach ($rowsmi1 as $o1){
		   	    $cont++;
			 	$arraymeta[$cont]=new Meta();
			 	$mi=new Mapaindicador();$mi->setCodigo($codmapaindicador);
			 	$arraymeta[$cont]->setMapaindicador($mi);
			 	$c=new Calendario();$c->setCodigo($o1['codCalendario']);
			 	$arraymeta[$cont]->setCalendario($c);
	            $arraymeta[$cont]->setPeriodo($o1['periodo']);
	            $arraymeta[$cont]->setMeta($o1['meta']);
	            $arraymeta[$cont]->setAno($o1['ano']);
	            $arraymeta[$cont]->setMetrica($o1['metrica']);
	            $arraymeta[$cont]->setCumulativo(NULL); 
	            
		    }
		 }

*/
?>	
<script>
  
$(function() {
    $('input[name=excluir-meta]').click(function() {
            
    	$('div#resultadoexibemeta').empty();
		$('div#resultado').empty();
		   $('#cadastra-meta22').find('input:text,  select').val('');
        $.ajax({url: "ajax/metapdi/deletameta.php", type: 'POST', data:$('form[name=cadastra-meta]').serialize(), success: function(data) {
                $('div#resultado').html(data);
            }});
    	
    	});   
	
});

</script>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active"><a href="<?php echo Utils::createLink('mapaestrategico', 'listamapapdu'); ?>">
			Painel <?php print $sessao->getCodUnidade()==938?"Estratégico":"Tático"; ?></a> 
			<i class="fas fa-long-arrow-alt-right"></i> 
			<a href="<?php echo Utils::createLink('indicadorpdi', 'consultaindicador'); ?>" >Indicadores Vinculados</a>  
			<i class="fas fa-long-arrow-alt-right"></i> 
			<a href="#" >Editar meta</a></li>  
		</ul>
	</div>
</head>	

<div class="card card-info">
	<form class="form-horizontal" name="cadastra-meta" id="cadastra-meta22" method="POST" action="" id="meta-cadastro">			
		<div class="card-header">
			<h3 class="card-title">Editar Meta</h3>
		</div>
		<div class="card-body">
			<div id="resultado"></div> 	
			<input class="form-control" type="hidden" name="codmapaind" value="<?php echo $codmapaindicador; ?>" >
			<input class="form-control" type="hidden" name="coddoc" id="coddoc" value="<?php echo $coddoc; ?>" >			 
			Indicador: <?php echo $arrayindicador['nome']; ?>

			<table class="table table-bordered table-hover" style="width:500px;" align="center">
				<thead>
					<tr>
						<th>Ano</th>
						<th>Meta</th>
						<th>Unidade de medida</th>
						<?php echo ($contlinhas==0 && $contlinhasant>0)?" <th>Solicitar Alteração</th>":"";?>
					</tr>
				</thead>
				<tbody align="center">  
					<?php
					$dadosDoc = $daodoc->buscadocumentoPrazoEUnidade($anobase,$sessao->getCodunidade())->fetch();
					$anoInicialDoc=$dadosDoc['anoinicial'];
					$anoFinalDoc=$dadosDoc['anofinal'];
					
					for ($i=1;$i<=$cont;$i++) {
						if ($anoInicialDoc<=$arraymeta[$i]->getAno() && $anoFinalDoc>=$arraymeta[$i]->getAno()){////Início do if ?>		
							<tr>
								<td>
									<?php echo $arraymeta[$i]->getAno();?> 
									<input class="form-control"name="codigo<?php echo $arraymeta[$i]->getAno(); ?>" type="hidden" value="<?php echo $arraymeta[$i]->getCodigo();?>"/>
								</td>
								<td class="coluna2">
									<?php if($arraymeta[$i]->getAno() == "2021" OR $arraymeta[$i]->getAno() == "2022"){?>
									<input class="form-control"value ="<?php echo  str_replace('.', ',', $arraymeta[$i]->getMeta());?>" data-mask="000000,00" data-mask-reverse="true" size=10 type="text" name="meta<?php echo $arraymeta[$i]->getAno();?>"/>
									<?php }else{?>
									<input class="form-control"<?php echo $aux_class;?> value ="<?php echo  str_replace('.', ',', $arraymeta[$i]->getMeta());?>" data-mask="000000,00" data-mask-reverse="true" size=10 type="text" name="meta<?php echo $arraymeta[$i]->getAno();?>"/>
									<?php }?>
								</td>                  
								<td>
									<?php if($anobase<2022){
										var_dump($arraymeta[$i]->getAno());
										print '<select class="custom-select" name="metrica'.$arraymeta[$i]->getAno().'" class="sel1">
											<option value="0">Selecione tipo de métrica...</option>
											<option value="P"';
										print $arraymeta[$i]->getMetrica()=='P'?"selected":"";
										print '>Percentual</option>
											<option value="Q"'.($arraymeta[$i]->getMetrica()=='Q')?"selected":"".'>Absoluto</option>					    
											</select>';
									}else{
										switch ($arrayindicador['unidadeMedida']){
											case 'P':
												print 'Percentual(%)';
												break;
											case 'Q':
												print 'Absoluto';
												break;
											case 'R':
												print 'Real(R$)';
												break;
											case 'M':
												print 'Metro quadrado(R$)';
												break;
										}
										
									}?>
								</td> 
									
								<?php if ($contlinhas==0 && $contlinhasant>0) {?>
									<td>
										<?php //Ver se tem resultado
										$rowRes=$daores->buscaresultadometa($arraymeta[$i]->getCodigo());
										if($rowRes->rowCount()==0){
											if($arraymeta[$i]->getAno()==2021 OR $arraymeta[$i]->getAno()==2022){
												$troca_img = "webroot/img/salvar.png";                    
												if($arraymeta[$i]->getAno()==2021){
													 $href = "onclick='salvarMeta2021(".$arraymeta[$i]->getCodigo().")'";
												}else{
													 $href = "onclick='salvarMetaProrrogar(".$arraymeta[$i]->getCodigo().")'";
												}
												$acaoT="";
											}else{
												$troca_img = "webroot/img/troca.png";
												$acaoT = 'class="botModalRepac" data-toggle="modal" data-target="#cadastrarSol"';
												$href = "href='javascript:void(0)'";
											}
											$ajudaT = "";
											$disabledT="";
										}else{
											$troca_img = "webroot/img/troca2.png";
											$ajudaT = "title='Não é possível alterar objetivo, pois possui resultados lançados para este ano.'' data-trigger='hover'";
											$acaoT="";
											$disabledT = 'style="pointer-events: none;';
											$href = "href='javascript:void(0)'";
										} ?> 
					
										<a name="<?php echo $arraymeta[$i]->getCodigo();?>"  <?php echo $href." ".$acaoT;?> >
											<img <?php echo $ajudaT;?> src='<?php echo $troca_img;?>' 
											data-trigger='hover' alt="Solicitar Repactuação da Meta!" width="19" height="19">
										</a>	 
									</td>  
								<?php } ?>             
							</tr>
						<?php } ////fim do if
					} ?>		
				</tbody>
			</table>
		</div>		
		<div class="card-body" align="center">
			<?php //if ($origem == 0 && $aux_resultMeta == 0){
			echo $origem."x".$contlinhas."x".$contlinhasant."<br>";
			if ($origem == 0 && $contlinhas==0 && $contlinhasant==0){ //?>
				<input type="button" value="Gravar" name="24" class="btn btn-info" id="gravar"/>
				<input type="button" value="Excluir" id="remove" name="excluir-meta" class="btn btn-info"/>
			<?php } ?>		      
			<span class="plus"></span>
			<input class="form-control"type="hidden" name="action" value="A" />
			<input class="form-control"type="hidden" name="coleta" value="P" />		
			<input class="form-control"type="hidden" name="coddoc" value="<?php echo $coddoc; ?>">   		
		</div>
    </form>
</div>
	
<!-- Modal Loading-->
<div class="" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="modalLoading" aria-hidden="true">  
    <div class="loader"></div> 
</div>

<!-- Modal Inserir Solicitação-->
<div class="modal fade" id="cadastrarSol" tabindex="-1" role="dialog" aria-labelledby="cadastrarSol" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-centered" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
          		<button type="button" class="close" data-dismiss="modal">&times;</button>
          		<h4 class="modal-title">Cadastrar Solicitação para Repactuação de Meta</h4>
       	 	</div>
      		<div class="modal-body">
      			<div id="opsucesso" style="display: none;">
					<p>
		    			<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
		   				Cadastro realizado com sucesso. Sua solicitação foi enviada para análise.
		  			</p>
				</div>	
				<div id="possuiSolicitacao" style="display: none;">
					<p>
		    			<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
		    			Este indicador já possui uma solicitação enviada para esta meta.
		  			</p>
				</div>
        		<div id="form1">
        			<form class="form-horizontal" name="form-criarSol" id="form-criarSolicitacao" method="POST" enctype='multipart/form-data'  >
						<div id="msg1" class="alert alert-danger" role="alert" style="display:none;"></div>
						<fieldset>		       
							<div id="tabrepact"></div>	     
						</fieldset>	   		 		   
					</form>
				</div>
				<div id="loading" style="display: none;">  
		    		<div class="loader"></div> 
				</div>
      		</div>
      		<div  class="alert alert-danger" role="alert" id="alert" style="display:none;"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" onclick="btnFechar();" data-dismiss="modal">Fechar</button>
				<button type="button" id="btnEnviarSol1" class="btn btn-info">Solicitar</button>
      		</div>
    	</div>
  	</div>
</div>

<script type="text/javascript" src="webroot/js/jquery.mask.js"></script> 

<script>
	$("#gravar").click(function(){
		$("#resultado").empty();
		$.ajax({
			type: "POST",
			url: "ajax/metapdi/registrameta.php",
			data: $("#cadastra-meta22").serialize(),
			success: function(data){
				$("#resultado").html(data);
			}    
		});
	});

	$('.botModalRepac').click(function(event) {
		$("#form1").css("display","");
		$("#btnEnviarSol1").css("display","");

		var meta= $(this).attr('name');
		var doc=$('#coddoc').val();
		$("#msg1").empty();
		$("#opsucesso").css("display","none");
		
		$("#tabrepact").empty();
		$.ajax({
			url: "ajax/metapdi/modalSolicitRepact.php",
			type: "POST",
			data: {meta:meta,doc:doc},
			success: function(data) {
				$("#tabrepact").html(data);
				if (data.substring(9,18)=="SolExiste"){
					$("#btnEnviarSol1").css("display","none");
				}
				//document.write(data);
			},
		});
	});

	function salvarMetaProrrogar(meta) {
		var valor = $("input[name=meta2022]").val();   
		$.ajax({
				url: "ajax/metapdi/registrametaProrrogar.php",
				type: "POST",
				data: { 'valor': valor,'codmeta': meta },
				success: function (data) {
					//alert(data);
					history.go(0);
				},
			});
	}  
 </script>

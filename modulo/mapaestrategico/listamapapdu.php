<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active"><a href="#">Painel <?php print $sessao->getCodunidade()==938?"Estratégico":"Tático"; ?>
			</a></li>
		</ul>
	</div>
</head>

<?php
//session_start ();
$_SESSION['mensagem']='';

$sessao = $_SESSION ['sessao'];

if (! isset ( $sessao )) {
	exit ();
}

$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
$anobase = $sessao->getAnobase();

$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$codunidade1=$unidade->getCodunidade();

$mapadao = new MapaDAO ();
$daodoc = new DocumentoDAO();
$daoobj=new ObjetivoDAO();
$daoResul=new ResultadoDAO();
$daoind=new IndicadorDAO();
$c=new Controlador();
$arraydocs=array();

//preenche array de documentos
$rowsdoc = $daodoc->buscadocumentoporunidadePeriodoSemPDI($unidade->getCodunidade(), $sessao->getAnobase());
$contDocs=0;

foreach ($rowsdoc as $r){
	$unidade->criaDocumento($r['codigo'], $r['nome'],$r['anoinicial'], $r['anofinal'], NULL, NULL, NULL, NULL, NULL);
	$contDocs++;
	$anoinicialdoc=$r['anoinicial'];
	$arraydocs[$contDocs]=$unidade->getDocumento();
	$codDocumento=$r['codigo'];
}

//Buscar Plano de ação
$temPlano=0;
$rowVerificaPLano = $mapadao->verificarPlanoAcao($codunidade, $anobase);

if($rowVerificaPLano->rowCount() > 0){    
    $temPlano = 1;
    foreach($rowVerificaPLano as $rowP){
        $comentarioPlano = $rowP['comentario'];
        $arquivoPlano = $rowP['arquivo'];
    }
}else{    
}

if ($contDocs==0){?>
	<div class="erro">
		<img src="webroot/img/error.png" width="30" height="30" />
		<?php print "É necessário Definir o Documento!"; ?>
	</div>
<?php  } else {
	$rowsresant=$daoResul->verResultadosAnosAnteriores( $sessao->getCodunidade(),$anoinicialdoc, $anobase, $codDocumento);
	$contlinhasant= $rowsresant->rowCount();
	
	$rowsres=$daoResul->verResultadosAnoPeriodoFinal($sessao->getCodunidade(),$anobase, $codDocumento);
	$contlinhas= $rowsres->rowCount();
	// $propmapa = $sessao->getCodUnidade ();able>
	// $mapa = $mapadao->buscaMapaByUnidadeDocumento ( 1, $propmapa );
	echo Utils::deleteModal('Remover ', 'Você tem certeza que deseja remover objetivo selecionado?'); ?>

	<div id="message"></div>
	<div class="card card-info">
		<div class="card-header">
			<h3 class="card-title">Elaborar painel tático</h3>
		</div>
		<form class="form-horizontal" name="chamaTabela">
			<table class="card-body">
				<tr>
					<td class="coluna1">Documento</td>
				</tr>
				<tr>
					<td class="coluna2">
						<select class="custom-select" name="codDocumento" id="selectDocument" class="sel1">
							<?php foreach ($arraydocs as $d) { 
								$ano = $d->getAnoinicial(); 
								if (!$c->getProfile($sessao->getGrupo())) { ?>
									<option selected value=<?php print $d->getCodigo(); ?>>
										<?php print $d->getNome();
										$nomeDoc = $d->getNome(); ?>
										<?php print ' (' . $d->getAnoinicial() . '-' . $d->getAnofinal() . ')';
									?></option>
									<?php $codDocumento = $d->getCodigo();
								} else if ($c->getProfile($sessao->getGrupo())) { ?>
									<option value=<?php print $d->getCodigo(); ?>>
									<?php print $d->getNome(); ?>
									<?php print ' (' . $d->getAnoinicial() . '-' . $d->getAnofinal() . ')'; ?>
									</option>
									<?php } ?>
								<?php }; ?>
						</select>
						<br>
					</td>
				</tr>
			</table>

			<?php
			$mapadao = new MapaDAO ();

			$codunidade = ($sessao->getCodunidsel())?$sessao->getCodunidsel():$sessao->getCodUnidade();

			//realiza filtro de mapa por documento ou por calendario.
			if (!$c->getProfile($sessao->getGrupo())) {
				$mapa = $mapadao->buscaMapaByUnidadeDocumentoOuCalendario1($codDocumento, $codunidade,$anobase);
			}
			?>

			<!--abriga mensagem de resposta quando o usuário tentar deletar pespectiva/objetivo-->
			<div id="m"></div>

			<!-- tabela gerada a partir do objeto mapa -->
			<div class="card-body">
				<?php
				// echo $contlinhasant."x".$contlinhas;die;
				
				if ($contlinhas==0 && $contlinhasant==0){ //exibe botão de adição se nao houver resultado no documento para o ano no periodo final?>
					<a
						href="<?php echo Utils::createLink('mapaestrategico', 'cadastromapapdu'); ?>">
						<button id="mostraTelaCadastro" type="button"
							class="btn btn-info btn">Adicionar novo objetivo estratégico</button>
					</a>
				<?php  }
				if ($contlinhasant!=0 && $contlinhas==0) {
					$acaoT1 = ' data-toggle="modal" data-target="#cadastrarSol1"';
					?>
					<a href="javascript:void(0)" <?php echo $acaoT1;?>><button
					id="botDadosSol" type="button" class="btn btn-info btn">Solicitar
					inclusão de novo objetivo estratégico</button> </a>
					<?php 
				} ?>

				&ensp;	
				<a href="<?php echo Utils::createLink('iniciativa', 'listaIniciativa'); ?>">
					<button id="listainic" type="button" class="btn btn-info btn">Iniciativa</button>
				</a> 

				&ensp; 	
				<button id="btn_plano_acao" type="button" data-toggle="modal" data-target="#enviarPlanoAcao" class="btn btn-info">Plano de Ação</button>
			&ensp;<a
				href="relatorio/painelTaticopdf.php?unidade=<?php echo $codunidade1;?>&anoBase=<?php echo $anobase;?>"><button
					type="button" class="btn btn-info">
					Exportar Painel
					<?php echo $sessao->getCodunidade()==938?"Estratégico":"Tático";?>
				</button> </a> &ensp;		
					<!-- <button id="btn_solicitar_validação" data-toggle="modal" data-target="#enviarSoliValidacao" type="button" class="btn-primary btn">Solicitar Validação do PT</button> -->
			</div>
			
			<div class="card-body">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Perspectiva</th>
							<th>Objetivo</th>
							<th>Deletar</th>
							<th>Indicador</th>
							<?php echo ($contlinhasant!=0 && $contlinhas==0)?"<th>Solicitar Exclusão</th>":"";?>
						</tr>
					</thead>

					<tbody>
						<?php
						$cont = 1;
						$dao=new MapaIndicadorDAO();

						foreach ($mapa as $row) { ?>
							<tr>
								<td><?php echo $row['nomeperspectiva'] ?></td>
								<td><?php echo $row['nomeobjetivo'] ?></td>
								<td align="center">
									<!--<button  id="<?php //echo "btn{$cont}"; $cont++;?>" value="<?php //echo $row['Codigo'];?>" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" >
									<img src ="webroot/img/delete.png"/></button>--> 
									<?php $cont++;
									
									$rowsi = $dao->verSeMapaTemIndicador($row['Codigo'], $anobase)->fetchAll();
									
									if (count($rowsi) > 0) {
										$img = "webroot/img/delete.no.png";
										$disabled = "disabled";
										$evento = "";
										$ajuda = "title='Não é possível excluir, pois o Objetivo possui Indicador vinculado.'' data-trigger='hover'";
									} else {
										$img = "webroot/img/delete2.png";
										$evento = 'onclick="deletamapa(this);"';
										$disabled = "";
										$ajuda = "title='Deletar Objetivo.' data-trigger='hover'";
									}
									?>					
															
									<button type="button" <?php echo $disabled; ?>	id="<?php echo "btn{$cont}";
										$cont++; ?>"value="<?php echo $row['Codigo']; ?>" 
										<?php echo $evento; ?> 
										<?php echo $ajuda; ?>>
										<img src='<?php echo $img; ?>' title="Ajuda" data-trigger='hover'
											alt="Possui indicador vinculado!" width="19" height="19">
									</button>
								</td>
								<td align="center">
									<a href="<?php echo Utils::createLink('indicadorpdi', 'consultaindicador', array('codigo' => $row['Codigo'])); ?>">
										<img src="webroot/img/add.png" /> </a>
								</td>
								<?php if ($contlinhasant != 0 && $contlinhas == 0) { ?>
									<td>
										<?php
										//Ver se tem resultado
							
										/*$rowResult = $daoResul->buscaResultMapaAno($row['Codigo'],$anobase);
										if($rowResult->rowCount()==0){*/
										$troca_img = "webroot/img/troca.png";
										$ajudaT = "";
										$acaoT = 'class="botModalExclui" data-toggle="modal" data-target="#cadastrarSol2"';
										$disabledT = "";
										/*}else{
										$troca_img = "webroot/img/troca2.png";
										$ajudaT = "title='Não é possível alterar objetivo, pois possui resultados lançados para este ano.'' data-trigger='hover'";
										$acaoT="";
										$disabledT = 'style="pointer-events: none;';
										}*/
										?> 
										<a name="<?php echo $row['Codigo']; ?>" href="javascript:void(0)" <?php echo $acaoT; ?>><img <?php echo $ajudaT; ?>
												src='<?php echo $troca_img; ?>' data-trigger='hover'
												alt="Solicitar Exclusão de Objetivo!" width="19" height="19"> 
										</a>
									</td>
								<?php } ?>
							</tr>
							<?php
							$cont++;
						}?>
					</tbody>
				</table>
			</div>
		</form>

		<div id="dialog-confirm" title="Confirmação" style="display: none; margin: auto;">
			<p>
				<span class="ui-icon ui-icon-alert"
					style="margin: 12px 12px 20px 0;"></span> Você tem
				certeza que deseja eliminar o objetivo do seu painel tático?
			</p>
		</div>
	</div>
<?php } //rowcount?>

<?php include "modaismapa.php"?>

<script>  
	function deletamapa(button){
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
							url: "ajax/mapa/deletamapa.php", type: 'POST', data: { codmapa: button.value, action : "D"},
							async: true,
							success: function(data) {
								$('div#message').html("<br><img src='webroot/img/accepted.png' width='30' height='30'/>Objetivo deletado com sucesso!");
								var teste = "#"+button.id;
								teste = $(teste).parent().parent();
								teste.remove();
							},
							error:function(data) {
								$('div#message').html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'></button><img src='webroot/img/error.png' width='30' height='30'/><strong>Falha ao deletar!! Objetivo está vinculado à um ou mais Indicadores.</strong></div>");
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

	$('#resposta-ajax-doc1').click(function(event) {
		var unidade = $("#doc").val();
		$("#m").html("");
		if(unidade != 0){
			$("#m").html("<center><br/><br/><a href='relatorio/painelTaticopdf.php?unidade="+unidade+"&anoBase=<?php echo $anobase;?>'><button type='button' class='btn btn-info'>Exportar Painel</button></a></center>");
		}else {
			$("#m").html("<center>Por favor, Selecione um documento!</center>");
		}		
	});

	$('#botDadosSol').click(function(event) {
		$("#msg1").css("display","none");
		$("#msg1").empty();
		$("#tabIncObj").empty();
		$("#opsucesso").css("display","none");
		$("#form1").css("display","");
		$("#btnEnviarSol1").css("display","");    	
		
		$.ajax({
			url: "ajax/mapa/modalSolicitInc.php",
			type: "POST",
			data: $("form[name=chamaTabela]").serialize(),
			success: function(data) {
				$("#tabIncObj").html(data);
			},
		});
	});


	$('.botModalExclui').click(function(event) { 
		$("#formEO").css("display","");
		$("#btnEnviarSol2").css("display","");
		$("#confirmacaoSol").css("display","none");
		
		var mapa= $(this).attr('name');
		var doccorreto=	 $('#selectDocument').children("option:selected").val();
		
		$("#msg2").empty();
		$("#tabExcObj").empty();
		$("#opsucesso").css("display","none");
		
		$.ajax({
			url: "ajax/mapa/modalSolicitExc.php",
			type: "POST",
			data: {mapa:mapa,doccorreto:doccorreto},
			success: function(data) {
				//alert(data.substring(0,5));
				
				$("#tabExcObj").html(data);
				//alert(data.substring(9,18));
				if (data.substring(9,18)=="SolExiste"){
					$("#btnEnviarSol2").css("display","none");
				}
			}
		});
	});
//window.location.reload();
</script>

<?php include 'modaisPT.php';?>















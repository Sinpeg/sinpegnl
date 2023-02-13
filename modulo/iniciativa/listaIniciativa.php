<?php
$sessao = $_SESSION['sessao'];
$codUnidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();
$_SESSION['idIniciativa'] = NULL;
?>

<?php 
$caldao=new CalendarioDAO;
$codcalendario=$caldao->buscaCalendarioporAnoBaseOnly($sessao->getAnobase())->fetch();
$iniciativadao = new IniciativaDAO();
$querryIniciativa = $iniciativadao->lista($codUnidade,$sessao->getAnobase());
$cont = 0;
if ($querryIniciativa->rowcount()==0){
	Utils::redirect('iniciativa', 'registraIniciativa');
}
foreach ($querryIniciativa as $iniciativa){
	$arrayIniciativa[$cont++] = $iniciativa;
}

$documentodao = new DocumentoDAO();
$documento = $documentodao->buscadocumentoporunidadePeriodoSemPDI($codUnidade, $sessao->getAnoBase())->fetch();
	
//Verifica se pode fazer solicitação de alteração no PDU
$daoResul=new ResultadoDAO();
    
        
      /*  $rowsresant=$daoResul->verResultadosAnosAnteriores( $sessao->getCodunidade(),$documento['anoinicial'], $anobase);
	    $contlinhasant= $rowsresant->rowCount();*/
	    
$rowsres=$daoResul->verResultadosAnoPeriodoFinal($sessao->getCodunidade(),$anobase,$documento[0]);
		$contlinhas= $rowsres->rowCount();
		
	
	$rowsres=$daoResul->verResultadosAnoPeriodoFinal($sessao->getCodunidade(),$anobase,$documento);
	$contlinhas= $rowsres->rowCount();
?>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active">
				<a href="<?php echo Utils::createLink('mapaestrategico', 'listamapapdu'); ?>">
					Painel <?php print $sessao->getCodunidade()==938?"Estratégico":"Tático"; ?>
				</a> 
				<i class="fas fa-long-arrow-alt-right"></i> 
				<a href="#" >Lista Iniciativas</a>
			</li>  
		</ul>
	</div>
</head>

<div class="card card-info">
	<div class="card-header">
		<h3 class="card-title">Iniciativas cadastradas</h3>
	</div>
	<div class="card-body">
		<table id="tabelaIniciativa" class="table table-bordered table-hover" >
			<tfoot>
				<tr>
					<td align="center" colspan=7>
						<br>
						<?php if ($contlinhas==0){?>
							<a href="<?php echo Utils::createLink('iniciativa', 'registraIniciativa'); ?>" >
								<button id="mostraTelaCadastro" type="button" class="btn btn-info btn">Nova Iniciativa</button>
							</a>
							<br/>
						<?php } ?>
					</td>
				</tr>
			</tfoot>
			<thead>
				<tr>
					<th>Iniciativa</th>
					<th>Início</th>
					<th>Período</th>		
					<th>Situação</th>
					<th>Editar</th>
					<th>Deletar</th>
					<th>Vincular Indicador</th>
				</tr>
			</thead>	
			<tbody align="center">
				<?php 
				$dao=new IndicIniciativaDAO();
				$daor= new ResultIniciativaDAO();
				
				foreach ($arrayIniciativa as $iniciativ){?>
					<tr>
						<td><?php print $iniciativ['nome'] ?></td>
						<td><?php print $iniciativ['anoInicio'] ?></td>
						<td><?php print $iniciativ['periodo'] ?></td>
						<td><?php print $iniciativ['Situacao'] ?></td>
						
						<td>
							<a href="<?php print Utils::createLink("iniciativa", "editaIniciativa", array('codIniciativa'=> $iniciativ['codIniciativa'])) ?>">
								<img  src="webroot/img/editar.gif">
							</a>
						</td>
						<td>
							<?php 
							$rowsindicadorvinc=$dao->listaPorIniciativa($iniciativ['codIniciativa'],$sessao->getAnobase());//se tem vinculo com indicadores
							
							$rowsresultado=$daor->resultadoporIni($iniciativ['codIniciativa'],$codcalendario['codCalendario']);//se tem resultado			
							
							if ($rowsresultado->rowCount()==0 && $rowsindicadorvinc->rowCount()==0){
								$img="webroot/img/delete2.png";
								$evento='onclick="deletainiciativa(this);"';
								$disabled = "";
								$ajuda = "title='Deletar Iniciativa.' data-trigger='hover'";
							}else{
									$img="webroot/img/delete.no.png";
									$disabled = "disabled";
									$evento="";
									$ajuda = "title='Não é possível excluir, pois a Iniciativa possui Indicador vinculado.'' data-trigger='hover'";
							}
							?>

							<button <?php echo $disabled;?> id="<?php echo "btn{$iniciativ['codIniciativa']}"; ?>"
							value="<?php echo $iniciativ['codIniciativa'];?>"
							
							<?php echo $evento;?> <?php echo $ajuda;?>> <img src='<?php echo $img;?>' 
							title="Ajuda" data-trigger='hover' alt="Possui indicador vinculado!" width="17" height="17"></button>
						</td>
						<td>
							<?php
							if ($iniciativ['Situacao'] != "Cancelada" && $iniciativ['Situacao'] != "Concluída") { //&& $contlinhas==0){ //se cancelada iniciativa e resultado do ano base inexistente ?>
								<a href="<?php print Utils::createLink("iniciativa", "editarAnexarIndicadores", array('codIniciativa' => $iniciativ['codIniciativa'], 'codDocumento' => $documento['codigo'])) ?>">
									<img   width="20" height="20" src="webroot/img/maos.jpg">
								</a>
							<?php
							}else{?>

							<?php }?>	
						</td>
					</tr>
				<?php }?>
			</tbody>
		</table>
	</div>

</div>

<div id="dialog-confirm" title="Confirmação" style="display: none">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Você tem certeza que deseja eliminar a iniciativa?</p>
</div>

<script>
	function deletainiciativa(button){
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
								url: "ajax/iniciativa/deletainiciativa.php", type: 'POST', data: { codini: button.value, action : "D"},
								async: true,
								success: function(data) {
									$('div#message').html("<br><img src='webroot/img/accepted.png' width='30' height='30'/>Iniciativa deletada com sucesso!");
									var teste = "#"+button.id;
									teste = $(teste).parent().parent();
									teste.remove();
								},
								error:function(data) {
									$('div#message').html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'></button><img src='webroot/img/error.png' width='30' height='30'/><strong>Falha ao deletar!! Iniciativa está vinculada a um ou mais Indicadores.</strong></div>");
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

<script>
    $(function () {
        $('#tabelaIniciativa').DataTable({
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
</script>
    
    
		

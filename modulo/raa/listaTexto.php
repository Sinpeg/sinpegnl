<?php
use phpDocumentor\Reflection\Types\Array_;

require_once 'dao/topicoDAO.php';

include 'funcoesraa.php';

$mdao= new ModeloDAO();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
$anobase=$sessao->getAnobase();

$tDAO = new RaaDAO();
/*
if (!$aplicacoes[1]) {
    header("Location:index.php");
}  else {
*/
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
$anobase = $sessao->getAnobase();
$lista = array();
$cont = 0;
$dao = new TextoDAO();   
$rowsl = $dao->buscaTopicosPreenchidos_Pendentes($anobase, $codunidade);
$topico=array();    
$topico=incluirTopicoNoTexto($rowsl,$topico,$cont,$codunidade,$anobase);
$rowsl = $dao->buscaSubTopicosPreenchidos_Pendentes($anobase, $codunidade);
$topico=incluirTopicoNoTexto($rowsl,$topico,$cont,$codunidade,$anobase);

//}//aplicacoes

//Verificar se o relatório foi finalizado
$disabled = "";
$frows = $dao->buscaFinalizacaoRel($codunidade, $anobase);

foreach ($frows as $row){
	$disabled = "disabled";
}     

//carla - para bloquear a aplicação no prazo
$daocal=new CalendarioDAO();
$rows=$daocal->verificaPrazoCalendarioDoRAA($anobase);

foreach ($rows as $row) {
	//echo $row['habilita'];
     $disabled=$row['habilita']=='D'?"disabled":$disabled;
}
   
$arrayTopicosParaValidacaoNome = array();
$arrayTopicosParaValidacaoId = array();
$contadorTopicos = 0;
?>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<a href="#">Redigir texto RAA</a>
		</ul>
	</div>
</head>

<div class="card card-info" style="width:70%">
	<div class="card-header">
		<h3 class="card-title">Redigir RAA</h3>
	</div>
	<div class="card-body" style="width:100%; text-align:left;">	
		<div id="accordion">	    
			<?php 	  
			$cont=0;
			$nivel=0;
			if (count($topico)>0) {
				$anterior=NULL; 
				foreach ($topico as $t){//exibe os tópicos       
					//verifica se o tópico é vínculado à unidades específicas
					$rowsUnidadesTopico = $tDAO->buscarUnidadestopico($t->getCodigo());
					
					if($rowsUnidadesTopico->rowCount()==0){//Quando não possui nenhuma unidade vínculada específicamente
						?>	  
						<h3 
							<?php print "onclick=carregaModelo(".$t->getCodigo().")"; ?> ><!-- Exibe nome do tópico -->
							<?php 
							if($t->getCodigo()!=2 && $t->getCodigo()!=3 && $t->getCodigo()!=5){
								$contadorTopicos++;
								$arrayTopicosParaValidacaoNome[$contadorTopicos] =  $t->getNome();
								$arrayTopicosParaValidacaoId[$contadorTopicos] = $t->getCodigo();
							}
							
							//print $nivel;			
							if($t->getNivel() != NULL){
								$nivel++;
								print $nivel.". ".$t->getNome();
							}else{
								print "".$t->getNome();
							}
								//print $t->getCodigo();
								//print $t->getNivel().$t->getNome(); ?>
						</h3>
						
						<div id="ac<?php print $t->getCodigo();?>"></div>	      
						
						<?php 
						if($t->getSubtopicos()!=NULL){
							$cont=subtopico($t->getSubtopicos(),$codunidade,$anobase,$nivel,0);
							foreach ($t->getSubtopicos() as $tt){
								$contadorTopicos++;
								$arrayTopicosParaValidacaoNome[$contadorTopicos] =  $tt->getNome();
								$arrayTopicosParaValidacaoId[$contadorTopicos] = $tt->getCodigo();
							}
						}
					}else{//Quando o tópico possui unidades vinculadas
						foreach ($rowsUnidadesTopico as $uniTopicos){
							if($uniTopicos['codUnidade']==$codunidade){?>
								<h3 <?php print "onclick=carregaModelo(".$t->getCodigo().")"; ?> ><!-- Exibe nome do tópico -->
									<?php
									$contadorTopicos++;
									$arrayTopicosParaValidacaoNome[$contadorTopicos] =  $t->getNome();
									$arrayTopicosParaValidacaoId[$contadorTopicos] = $t->getCodigo();
										
									if($t->getNivel() != NULL){
										$nivel++;
										print $nivel.". ".$t->getNome();
									}else{
										print "".$t->getNome();
									}
									//$t->getCodigo();
									//print $t->getNivel().$t->getNome(); ?>
								</h3>
								
								<div id="ac<?php print $t->getCodigo();?>"></div>	      
								
								<?php 
								if ($t->getSubtopicos()!=NULL){
									$cont=subtopico($t->getSubtopicos(),$codunidade,$anobase,$nivel,0);	
								}	  				
							}
						}
					}  	
				}//termina exibição do tópico
			}else{?>
				<div class="erro">
					<img src="webroot/img/error.png" width="30" height="30"/>
					<?php print "É necessário definir tópicos para o Relatório Anual de Atividades de ".$anobase."!"; ?>
				</div>
			<?php } ?>
		</div>
	</div>
	<table class="card-body">
		<tr>
			<div>	
				<td align="center">
					<form class="form-horizontal" method="post" action="<?php echo Utils::createLink('raa', 'consultarTopicos');?>" name="frm" >
						<input class="btn btn-info" type="submit"  name="novo1" value="Novo Tópico..."  id="topico" class="btn btn-info btn">
					</form>
				</td>
				<td align="center">
					<a href="modulo/raa/relatorioRaaPDF.php"> <input type="button" name="btnExportar" value="Exportar Relatório" class="btn btn-info" id="exportar">	
				</td>
				<td align="center">
					<a href="<?php echo Utils::createLink("uparquivo", "consultaarqs"); ?>">
						<input type="button" value="Enviar Anexo"  id="raa" class="btn btn-info">
					</a>
				</td>
				<td align="center">
					<input type="button" name="btnModal" <?php echo $disabled;?> value="Finalizar Relatório" class="btn btn-info" id="finalizar" data-toggle="modal" data-target="#confirmaFinal">
				</td>
			</div>
		<tr>
	</table>
</div>

<!-- Modal Loading-->
<div class="" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="modalLoading" aria-hidden="true">  
    <div class="loader"></div> 
</div>

<!-- Modal Exportar Relatório -->
<div class="modal fade modalFim"  id="exportarRelatorio" tabindex="2" role="dialog" aria-labelledby="exportarRelatorio" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document" style="width: auto;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Em qual formato deseja exportar?</h4>
			</div>
			<div class="modal-body">
				<center><div><a href="modulo/raa/relatorioRaaPDFW.php"><img width="63px" src="webroot/img/word2.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="modulo/raa/relatorioRaaPDF.php"><img width="50px" src="webroot/img/pdf2.png" /></a></div></center>
			</div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>

<!-- Modal Confirmar Finalização do relatório-->
<div class="modal fade modalFim" id="confirmaFinal" tabindex="2" role="dialog" aria-labelledby="confirmaFinal" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Confirmar Finalização </h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div id="div_form_confirme">          
					<form class="form-horizontal" name="form-finalizacao" id="form-finalizacao" method="POST"  >
						<fieldset>		       
							<p>Deseja realmente finalizar a elaboração do Relatório de Atividades ? Após confirmação não será possível a edição.</p>
						</fieldset>
						<input class="form-control"type="hidden" name="codUnidade" value=" <?php echo $codunidade; ?> ">
						<input class="form-control"type="hidden" name="ano" value=" <?php echo $anobase; ?> ">
						
						<?php 
							foreach ($arrayTopicosParaValidacaoNome as $linha) {
								print '<input class="form-control"type="hidden" name=topicosNome[] value="'.$linha.'">';		          
							}
							foreach ($arrayTopicosParaValidacaoId as $linha2) {
								print '<input class="form-control"type="hidden" name=topicosId[] value="'.$linha2.'">';		          
							}
						?>		      
					</form>
				</div>
				<div id="div_pendencia"><span id="span_pendencia"></span></div>
			</div>
			<div class="modal-footer">
				<div id="footer_1">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
					<button type="button" id="btnConfirmaFim" class="btn btn-info">Sim</button>
				</div>
				<div id="footer_2" style="display: none;"><button type="button" id="btnFecharPendencia" class="btn btn-info">Fechar</button></div>    
			</div>
		</div>
	</div>
</div>

<script>
 	$( function() {
	    $( "#accordion" ).accordion({
            collapsible: true,
            active: false,
            heightStyle: "content"
        });
	} );

	//Quando clica no item	
	function carregaModelo(codTopico){
		//alert(codTopico);
		$("#modalLoading").css("display","block");

		var area = "#area"+codTopico;
		var codtexto = "codtexto";

		//alert(area);
		$.ajax({
				url:"ajax/raa/retornaModelo.php",
				type: 'POST',
				data: {codtexto:codtexto,codTopico:codTopico},
				success: function(data) {
					//alert(data);
					$("#modalLoading").css("display","none")
					$("#ac"+codTopico).html(data);
				},
				error: function(data) {
					alert("Data not found");
				}
			});	
	}

</script>


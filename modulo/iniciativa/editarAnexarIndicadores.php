<?php
$sessao = $_SESSION['sessao'];
$codUnidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();
 $anobase=$sessao->getAnobase();
	//verifica unidade
	$daodocumento = new DocumentoDAO();
	$arraydocumento = $daodocumento->buscadocumentoporunidade($codUnidade,$anobase);
	$grupos=$sessao->getGrupo();
	$interno = false;
	foreach ($grupos as $grupo){
		if ($grupo == "18"){
			$interno = true;
		}
	}

foreach ($arraydocumento as $documento){
	if(!($documento['codigo'] == $_GET['codDocumento'] AND ($interno))){
		$doc=true;
	}else{
		$codDocument= $documento['codigo'];
	}
}
if(!$doc){
	Utils::redirect('iniciativa', 'erro');
}

$daoiniciativa = new IniciativaDAO();
$codIniciativa = $_GET['codIniciativa'];

$codDocumento = $_GET['codDocumento'];

$daoindicador = new IndicadorDAO();
$daoobjetivo = new ObjetivoDAO();
$arrayobjetivo = $daoobjetivo->buscaObjetivoPorDocumento1($codDocumento, $codUnidade, $sessao->getAnobase());
?>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active">
				<a href="<?php echo Utils::createLink('mapaestrategico', 'listamapapdu'); ?>">Painel Tático</a>
				<i class="fas fa-long-arrow-alt-right"></i> 
				<a href="<?php echo Utils::createLink('iniciativa', 'listaIniciativa'); ?>">Lista Iniciativas</a>
				<i class="fas fa-long-arrow-alt-right"></i>
				<a href="#" >Vincular Indicador</a>
			</li>  
		</ul>
	</div>
</head>

<?php
if ($arrayobjetivo->rowCount()==0){
   ?> 
    <div class="erro">
	<img src="webroot/img/error.png" width="30" height="30" />
	<?php print "É necessário entrar com os objetivos, indicadores e metas do painel tático!"; ?>
</div>
<?php }else{

	$i=0;
	foreach ($arrayobjetivo as $objetivo){
		$queryIndicadores = 0;
		$queryIndicadores = $daoindicador->buscaIndicadorPorObjetivoDocumento1($objetivo["Codigo"], $codDocumento,$sessao->getAnobase(),$codUnidade);
		$j = 0;
		$indicadores = null;
		foreach ($queryIndicadores as $indicador){
			$indicadores[$j++] = $indicador;
		}
		$objetivo['indicadores'] = $indicadores;
		$objetivos[$i++] = $objetivo;
		
	}
	$objetivo['indicadores'] = $indicadores;
	$objetivos[$i++] = $objetivo;

	/////////////////////////////////////////////////////buscar indicadores selecionados
	$arrayInidicadores=array();
	$querryIndicadores = $daoiniciativa->buscaIndicadoresVinculadosPorIniciativa($codIniciativa,$sessao->getAnobase());
	$cont = 0;

	foreach ($querryIndicadores as $indicador){
		$arrayInidicadores[$cont++] = $indicador;
	}
		
	//     var_dump($arrayInidicadores);die;

	$rowsi= $daoiniciativa->BuscaIniciativa($codIniciativa,$sessao->getAnobase());
	foreach ($rowsi as $r){
		$nome=$r['nome'];
	}
			
	//Verifica se pode fazer solicitação de alteração no PDU
	$daoResul=new ResultadoDAO();


	/*  $rowsresant=$daoResul->verResultadosAnosAnteriores( $sessao->getCodunidade(),$documento['anoinicial'], $anobase);
	$contlinhasant= $rowsresant->rowCount();*/

	$rowsres=$daoResul->verResultadosAnoPeriodoFinal($sessao->getCodunidade(),$anobase,$codDocumento);
	$contlinhas= $rowsres->rowCount();
	?>

	<div class="card card-info" style="width:750px">
		<div class="card-header">
			<h3 class="card-title">Vincular Indicador à Iniciativa: <?php print ($nome);?></h3>
		</div>
		<form class="form-horizontal" method="post" action="<?php print Utils::createLink('iniciativa', 'gravaIndicIni'); ?>" >
			<input class="form-control"type="hidden" name="codIniciativa" value="<?php print $codIniciativa; ?>">
			<div class="card-body">
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
					<?php $cont = 0; 
					foreach ($objetivos as $objet){ 
						$cont++;?> 
						<div class="panel panel-default" style="margin:2px; padding: 5px; border-radius:10px; background-color:#eceff1;">
							<div class="panel-heading" role="tab" id="headingTwo">
								<p class="panel-title">
									<a class="collapsed" style="bachground-color:#fff;" role="button" data-toggle="collapse" data-parent="#accordion" href="<?php print "#collapse".$cont; ?>" aria-expanded="false" aria-controls="collapseOne<?php print $cont; ?>">
										<?php print '<i class="fas fa-caret-down"></i>    '.$objet['perspectiva']." - ".$objet['Objetivo']; ?>
									</a>
								</p>
							</div>
							<div id="<?php print "collapse".$cont; ?>" class="panel-collapse collapse <?php if($cont == 1){print "in";} ?>" role="tabpanel" aria-labelledby="headingOne">
								<div class="panel-body" align="center">
									<table style="width:600px;vertical-align:middle;text-align:center;background-color:white;padding:5px;" class="table table-bordered table-hover">
										<tr>
											<th>Indicador</th>
											<th style="width:90px">Vincular</th>
										</tr>
										<?php 
										if ($objet['indicadores']==NULL) {
											print "<p style='color:red;'> Não foram definidos indicadores para este objetivo!</p>";
										}else{
											$cont2=0;
											foreach ( $objet['indicadores'] as $indicador){
												$cont2++;?>
												<tr>
													<td><?php print $indicador['nome']; ?></td>
													<td>
														<input class="form-check-input"name="codmapaind<?php print $cont."-".$cont2;?>" value="<?php print $indicador['codMapaInd']; ?>" 
																	<?php if ($arrayInidicadores!=NULL){
																		foreach ($arrayInidicadores as $indi){
																			if($indi['Codigo'] == $indicador['Codigo']){
																				print "checked";
																			} 
																		} 				
																	}?> type="checkbox">
													</td>	
												</tr>	
											<?php }
										}
										$cont2 = 0; ?>
									</table>
								</div>
							</div>
						</div>
					<?php }?>
				</div>
			</div>
			<div class="card-body" align="center">
				<input class="form-control"type="hidden" name="codIniciativa" value="<?php print $_GET['codIniciativa']; ?>">
				<?php if ($contlinhas==0){// Se tem resultado, trava?>
					<input class="btn btn-info" type="submit"  name="acao" id="finalizar" value="Concluir" class="btn btn-info btn">
				<?php } ?>
			</div>
		</form>
	</div>
<?php } ?>

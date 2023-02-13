<?php

$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade(); // código da unidade
$nomeunidade = $sessao->getNomeUnidade();
$aplicacoes = $sessao->getAplicacoes(); // código das aplicações
$anobase = $sessao->getAnobase();       // ano base
$codestruturado = $sessao->getCodestruturado(); // código estruturado
$coddoc= $_POST['coddoc']; // código do indicador
$codindicador = $_POST['ind'];
$codmapaindicador = $_POST['mapaind'];
if (!isset($_SESSION["sessao"])) {
	echo "Sessão expirou...";
    exit();
}
if (!$aplicacoes[38]) {
    print "Erro ao acessar esta aplicação";
    exit();
}

$daoindicador = new IndicadorDAO();
$arrayindicador = $daoindicador->buscaindicador($codindicador)->fetch();
$daometa=new MetaDAO();
$daomapaind = new MapaIndicadorDAO();


/*$rowsmapaind = $daomapaind->buscamapaporindicador($codindicador);
foreach ($rowsmapaind as $rowmapaind) {
	$objmapaind = new mapaIndicador();
	$objmapaind->setCodigo($rowmapaind['codigo']);
	$codmapaindicador = $objmapaind->getCodigo();
}*/

$daodoc = new DocumentoDAO();
$rows = $daodoc->buscadocumento($coddoc);

$objdoc = new Documento();
foreach ($rows as $row) {
	$objdoc->setCodigo($row['codigo']);
	$objdoc->setAnoFinal($row['anofinal']);
	$objdoc->setAnoInicial($row['anoinicial']);
}

?>	
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active"><a href="<?php echo Utils::createLink('mapaestrategico', 'listamapapdu'); ?>">
				Painel <?php print $sessao->getCodUnidade()==938?"Estratégico":"Tático"; ?></a> 
				<i class="fas fa-long-arrow-alt-right"></i> 
				<a href="<?php echo Utils::createLink('indicadorpdi', 'consultaindicador'); ?>" >Indicadores Vinculados</a>  
				<i class="fas fa-long-arrow-alt-right"></i>  
				<a href="#" >Cadastrar meta</a>
			</li>  
		</ul>
	</div>
</head>

<div class="card card-info">	
	<div class="card-header">
		<h3 class="card-title">Cadastrar Meta</h3>
	</div>	
	<form class="form-horizontal" name="cadastra-meta" method="POST" action="ajax/metapdi/registrameta.php" id="meta-cadastro">	
		<div class="card-body">
			<div id="resultado"></div>
			Indicador: <?php echo $arrayindicador['nome']; ?>
			<input class="form-control" type="hidden" name="codmapaind" value="<?php echo $codmapaindicador; ?>" >
			<input class="form-control" type="hidden" name="coddoc" value="<?php echo $coddoc; ?>" >
		</div>
		
		<br>
		<div class="card-body">
			<table class="table table-bordered table-hover" style="width: 500px;" align="center">
				<thead>
					<tr>
						<th>Ano</th>
						<th>Meta</th>
						<th>Unidade de medida</th>
					</tr>
				</thead>

				<tbody align="center">  
					<?php 
					if ($anobase<=2020){
						$tempano=2020;
					}else $tempano=$objdoc->getAnoFinal();
						$anoInicialNaoOficial=$objdoc->getAnoInicial()==2016?2017:$objdoc->getAnoInicial();        
						for ($i=$anoInicialNaoOficial; $i <= $tempano ; $i++) {?>		
							<tr>
								<td><?php echo $i; ?></td>
								<td class="coluna2" style="width: 200px;">
									<input class="form-control" size=10 type="text" data-mask="000000,00" data-mask-reverse="true" name="meta<?php echo $i;?>" 
									onchange="mascaradec(this.value);"/> 
								</td>                  
								<td>
									<?php 
									if($anobase<2022){
										print '<select class="custom-select" name="metrica'.$i.'" class="sel1">
										<option value="0">Selecione tipo de métrica...</option>
										<option value="P">Percentual</option>
										<option value="Q">Absoluto</option>
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
										
									}
									?>
								</td>                
							</tr>
					<?php  }  ?>
				</tbody>
			</table>   
		</div> 
		
		<div class="card-body" align="center">
			<input type="button" value="Gravar" name="adicionar-meta" class="btn btn-info"/>		      
			<input class="form-control"type="hidden" name="action" value="I" />
			<input class="form-control"type="hidden" name="coleta" value="P" />		
			<input class="form-control"type="hidden" name="coddoc" value="<?php echo $coddoc; ?>">            
		</div>
	</form>
</div>





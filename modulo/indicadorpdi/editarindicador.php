<?php

$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade(); // código da unidade
$aplicacoes = $sessao->getAplicacoes(); // código das aplicações
$anobase = $sessao->getAnobase();       // ano base
$codestruturado = $sessao->getCodestruturado(); // código estruturado
$codindicador=$_POST['ind'];

if (!$aplicacoes[38]) {
    print "Erro ao acessar esta aplicação";
    exit();
}

$daounidade = new UnidadeDAO();
$rows = $daounidade->buscasubunidades00($codestruturado);
$unidade = array();
$cont = 0;

foreach ($rows as $row) {
	$unidade[$cont] = new Unidade();
	$unidade[$cont]->setCodunidade($row['CodUnidade']);
	$unidade[$cont]->setNomeunidade($row['NomeUnidade']);
	$cont++;
}

$daodoc = new DocumentoDAO();
$objdoc = array();
$cont1 = 0;

for ($i = 0; $i < $cont; $i++) {
	$daodoc = new DocumentoDAO();

	$rows = $daodoc->buscadocumentoporunidade($unidade[$i]->getCodunidade(), $anobase);
	foreach ($rows as $row) {
		$objdoc[$cont1] = new Documento();
		$objdoc[$cont1]->setCodigo($row['codigo']);
		$objdoc[$cont1]->setNome($row['nome']);
		$objdoc[$cont1]->setUnidade($unidade[$i]);
		$objdoc[$cont1]->setAnoInicial($row['anoinicial']);
		$objdoc[$cont1]->setAnoFinal($row['anofinal']);
		$cont1++;
	}
}

$daoind=new IndicadorDAO();
$rows=$daoind->buscaindicador($codindicador);
foreach ($rows as $r) {
	$objind=new Indicador();
    $objind = new Indicador();
	$objind->setCodigo($r['Codigo']);		
	$objind->setNome($r['nome']);
	$objind->setCalculo($r['calculo']);
	$objind->setInterpretacao($r['interpretacao']);
	$objind->setUnidademedida($r['unidadeMedida']);
}

//Verificar valor da interpretação
$inter1="";$inter2="";
if($objind->getInterpretacao() == 1){
	$inter1 = "checked";
}else if($objind->getInterpretacao() == 2){
	$inter2 = "checked";
}

//Verificar unidade de medida
$medidaQ="";$medidaP="";$medidaR="";$medidaM="";
if($objind->getUnidademedida() == 'Q'){
    $medidaQ = "selected";
}else if($objind->getUnidademedida() == 'P'){
    $medidaP = "selected";
}else if($objind->getUnidademedida() == 'R'){
    $medidaR = "selected";
}else if($objind->getUnidademedida() == 'M'){
    $medidaM = "selected";
}


/*
$daomapa = new MapaDAO();
$daoobjetivo = new ObjetivoDAO();
$objmapa = array();
$objobjetivo = array();
$cont2 = 0;
for ($i = 0; $i < $cont1; $i++) {
	$rows = $daomapa->buscamapadocumento($objdoc[$i]->getCodigo());
	foreach ($rows as $row) {
		$objmapa[$cont2] = new Mapa();
		$objmapa[$cont2]->setCodigo($row['Codigo']);
		$rowsobjetivo = $daoobjetivo->buscaobjetivo($row['codObjetivoPDI']);
		foreach ($rowsobjetivo as $rowobjetivo)
		{
			$objobjetivo[$cont2] = new Objetivo();
			$objobjetivo[$cont2]->setCodigo($rowobjetivo['Codigo']);
			$objobjetivo[$cont2]->setObjetivo($rowobjetivo['Objetivo']);
		}
		$objmapa[$cont2]->setObjetivoPDI($objobjetivo[$cont2]);
		$objmapa[$cont2]->setDocumento($objdoc[$i]);
		$cont2++;
	}
}*/

?>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active"><a href="<?php echo Utils::createLink('mapaestrategico', 'listamapapdu'); ?>">Painel Tático</a> 
			<i class="fas fa-long-arrow-alt-right"></i>
			<a href="<?php echo Utils::createLink('indicadorpdi', 'consultaindicador'); ?>" >Indicadores vinculados</a> 
			<i class="fas fa-long-arrow-alt-right"></i>
			<a href="<?php echo Utils::createLink('indicadorpdi', 'consultaindicadorproprio'); ?>" >Consulta Indicadores</a>  
			<i class="fas fa-long-arrow-alt-right"></i>
			<a href="#" >Editar indicador</a></li>  
		</ul>
	</div>
</head>	
<div class="card card-info">
	<div class="card-header">
    	<div class="card-title">Cadastro de Indicadores</div>
	</div>
	
    <form class="form-horizontal" name="cad-indicador" method="POST" action="ajax/indicadorpdi/registraindicador.php" id="indicadores-cadastro">
        <div id="resultado"></div>
		<table class="card-body">
			<tbody>
				<tr>
					<td class="coluna1">
						Indicador
					</td>
				</tr>
				<tr>
					<td>
						<input class="form-control"type=hidden name=codigo value="<?php echo $objind->getCodigo();?>">
						<input class="form-control"type=hidden name=acao value="<?php echo "E";?>">
						
						<textarea name="indicador" rows="5" cols="100"><?php  echo $objind->getNome();?></textarea>
					</td>
				</tr>		        
				<tr> 
					<td class="coluna1">   
						Fórmula de Cálculo
					</td>
				</tr>
				<tr>
					<td>						
						<textarea name="calculo" rows="5" cols="100"><?php  echo $objind->getCalculo();?></textarea>			        
					</td>
					
				</tr>
				<tr> 
					<td class="coluna1">   
						Unidade de medida
					</td>
				</tr>
				<tr>
					<td class="coluna2"><select class="custom-select" name="unidadeMedida">		            						    
						<option <?php print  $medidaP;?> value="P">Percentual (%)</option>
						<option <?php print  $medidaQ;?> value="Q">Absoluto</option>
						<option <?php print  $medidaR;?> value="R">Real (R$)</option>
						<option <?php print  $medidaM;?> value="M">Metro quadrado (m²) </option>							    
						</select>		        
					</td>
			</tr>
				
				<tr> 
					<td class="coluna1">   
						Interpretação	
					</td>
				</tr>
				<tr>
					<td class="coluna2">			
						<div class="custom-control custom-radio">
							<input class="form-control"type="radio" class="custom-control-input" value="1" name="interpretacao" <?php echo $inter1;?>>
							<label class="custom-control-label" for="interpretacao">Quanto maior, melhor.</label>
						</div>				
						<div class="custom-control custom-radio">
							<input class="form-control"type="radio" class="custom-control-input" value="2" name="interpretacao" <?php echo $inter2;?>>
							<label class="custom-control-label" for="interpretacao">Quanto menor, melhor.</label>
						</div>
					</td>
				</tr>				
				
				<?php if ($codunidade == "938") { ?>
				
					<tr>
						<td class="coluna1">	
							Objeto de Mensuração
						</td>
				</tr>
				<tr>
						<td>
							<textarea name="objeto" rows="5" cols="100"></textarea>					
						</td>
					</tr>      
							
					<tr> 
						<td class="coluna1">   
							Unidade de Medida
						</td>
				</tr>
				<tr>
						<td>				
							<textarea name="unidadedemedida" rows="5" cols="100"></textarea>
						</td>
					</tr>
					
					
					
					<tr> 
						<td class="coluna1">   
							Fonte/Método
						</td>
				</tr>
				<tr>
						<td>			
							<textarea name="metodo" rows="5" cols="100"></textarea>
						</td>
					</tr>
					
					<tr> 
						<td class="coluna1">   
							Benchmarch
						</td>
				</tr>
				<tr>
						<td>
							<textarea name="benchmarch" rows="10" cols="100"></textarea>
						</td>
					</tr>
					
					<tr> 
						<td class="coluna1">   
							Observações Adicionais
						</td>
				</tr>
				<tr>
						<td>	
							<textarea name="observacoes" rows="10" cols="100"></textarea>
						</td>
					</tr>
				<?php } ?>      
			</tbody>
		</table>
				  	
  		<div class="card-body" align="center">
			<input type="button" value="Gravar" name="salvarindicador" class="btn btn-info btn"/>
		</div>	      
  	</form> 
</div>

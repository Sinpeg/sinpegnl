<?php
$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade(); // código da unidade
$aplicacoes = $sessao->getAplicacoes(); // código das aplicações
$anobase = $sessao->getAnobase();       // ano base
$codestruturado = $sessao->getCodestruturado(); // código estruturado
?>
<?php
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
	$rows = $daodoc->buscadocumentoporunidade($unidade[$i]->getCodunidade(),$anobase);
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


$daoResul=new ResultadoDAO();

$rowsresant=$daoResul->verResultadosAnosAnteriores( $objdoc[0]->getUnidade()->getCodunidade(),$objdoc[0]->getAnoInicial(), $anobase,$objdoc[0]->getCodigo());
$contlinhasant= $rowsresant->rowCount();
	    
$rowsres=$daoResul->verResultadosAnoPeriodoFinal( $objdoc[0]->getUnidade()->getCodunidade(),$anobase,$objdoc[0]->getCodigo());
		$contlinhas= $rowsres->rowCount();

		
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
			<li class="active"><a href="<?php echo Utils::createLink('mapaestrategico', 'listamapapdu'); ?>">Painel Tático</a> <i class="fas fa-long-arrow-alt-right"></i> 
			<a href="<?php echo Utils::createLink('indicadorpdi', 'consultaindicador'); ?>" >Indicadores vinculados</a>  <i class="fas fa-long-arrow-alt-right"></i> 
			<?php echo ($contlinhas==0 && $contlinhasant>0)?"":'<a href='.Utils::createLink('indicadorpdi', 'consultaindicadorproprio').'>Vincula Indicador</a>  <i class="fas fa-long-arrow-alt-right"></i> ';?> 
			<a href="#" >Cadastro indicador</a></li>  
		</ul>
	</div>
</head>
    
<div class="card card-info">
	<div class="card-header">
    	<h3 class="card-title">Cadastro de Indicadores</h3>
	</div>

	<form class="form-horizontal" name="cad-indicador" method="POST" action="ajax/indicadorpdi/registraindicador.php" id="indicadores-cadastro">
		<div id="resultado"></div>
		<table class="card-body">
			<tr>
				<td class="coluna1">
					<label>Indicador </label>
				</td>
			</tr>
			<tr>
				<td class="coluna2">
					<textarea class="form-control" name="indicador" rows="1" cols="100"></textarea> 
				</td>
			</tr>		        
			<tr> 
				<td class="coluna1">   
					<label>Fórmula de Cálculo </label>
				</td>
			</tr>
			<tr>
				<td class="coluna2">						
					<textarea class="form-control" name="calculo" rows="5" cols="100"></textarea>
				</td>
			</tr>
			<tr> 
				<tr> 
					<td class="coluna1">   
						<label>Unidade de medida </label>
					</td>
				</tr>
			<tr>
				<td class="coluna2" align="center">
					<select class="custom-select" name="unidadeMedida" style="width:300px;">
						<option value="-1">Selecione a unidade de medida</option>
						<option value="P">Percentual (%)</option>
						<option value="Q">Absoluto</option>
						<option value="R">Real (R$)</option>
						<option value="M">Metro quadrado (m²) </option>							    
					</select>		        
				</td>
			</tr>
			<tr>
				<td class="coluna1">   
					<label>Interpretação </label>
				</td>
			</tr>
			<tr>
				<td class="coluna2">            
					<div class="custom-control custom-radio">
					<input class="form-control"type="radio" class="custom-control-input" value="1" name="interpretacao" checked>
					<label class="custom-control-label" for="interpretacao">Quanto maior, melhor.</label>
					</div>
											
					<div class="custom-control custom-radio">
					<input class="form-control"type="radio" class="custom-control-input" value="2" name="interpretacao" >
					<label class="custom-control-label" for="interpretacao">Quanto menor, melhor.</label>
					</div>
				</td>
			</tr>
		</table>
		<div class="card-body">
			<input class="form-control"type="hidden" name="coddoc" value="<?php echo $coddocumento; ?>" />
			<input class="form-control"type="hidden" name="codmapa" value="<?php echo $codmapa; ?>" />
			<input type="button" value="Gravar" name="salvarindicador" class="btn btn-info btn"/>	   
			
			<span class="plus"></span>
			<input class="form-control"type="hidden" name="acao" value="A" />
		</div>
	</form>
     
</div>

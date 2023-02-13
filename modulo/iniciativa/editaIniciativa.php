<?php

$sessao = $_SESSION['sessao'];
$codUnidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();
$anobase=$sessao->getAnobase();

$daounidade = new UnidadeDAO();
$grupos=$sessao->getGrupo();

$interno = false;
foreach ($grupos as $grupo){
	if ($grupo == "18"){
		$interno = true;
	}
}

if(!$interno){
	$codunidade = ($sessao->getCodunidsel())?$sessao->getCodunidsel():$sessao->getCodUnidade();
	$arrayUnidade = $daounidade->unidadeporcodigo($codunidade)->fetch();
	$nomeUnidade=$arrayUnidade['NomeUnidade'];
}


if (!$aplicacoes[36]) {
    print "Erro ao acessar esta aplicação";
    exit();
}
if (!empty($_GET['codIniciativa'])){
   $codini=$_GET['codIniciativa'];
}else 	$codini=$_SESSION['idIniciativa'];

$daodocumento = new DocumentoDAO();
$daoiniciativa = new IniciativaDAO();
$arrayiniciativa = $daoiniciativa->BuscaIniciativa($codini,$anobase)->fetch();
$querryDocumento = $daodocumento->buscadocumentoporunidade($arrayiniciativa['codUnidade'],$anobase);

$cont=0;
foreach ($querryDocumento as $document){
	$arraydocumento[$cont++]=$document;
}

?>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active">
			<a href="<?php echo Utils::createLink('mapaestrategico', 'listamapapdu'); ?>">Painel Tático</a> 
			<i class="fas fa-long-arrow-alt-right"></i>
			<a href="<?php echo Utils::createLink('iniciativa', 'listaIniciativa'); ?>">Lista Iniciativas</a> 
			<i class="fas fa-long-arrow-alt-right"></i>
			<a href="#" >Editar Iniciativa</a></li>  
		</ul>
	</div>
</head>

<div class="card card-info">
	<div class="card-header">
		<h3 class="card-title">Editar Iniciativa</h3>
	</div>
	<form class="form-horizontal" id="adicionar2" name="adicionar2"  method="POST" >
		<table class="card-body">
			<div id="msg"></div>
			<div id="resultado"></div>
			<tr class="subscribe">
				<td class="coluna1">	        	
					<input class="form-control"type="hidden" name="codIniciativa" value="<?php print $_GET['codIniciativa']; ?>">
					Nome
				</td>
			</tr>
			<tr>
				<td class="coluna2">	
					<input class="form-control"type="text" name="nome" value="<?php print $arrayiniciativa["nome"]; ?>"/>
				</td>
			</tr>
				
			<!-- <tr class="subscribe">
				<td><label>Ano de início:</label></td>
				<td><input class="form-control"type="text" maxlength="4"  name="anoinicio" value="<?php // print $arrayiniciativa["anoInicio"]; ?>" style="width:60%" /></td>
			</tr> -->  	
			<tr class="subscribe">
				<td class="coluna1"> Situação</td>
			</tr>
			<tr>
				<td class="coluna2">	
					<select class="custom-select" name="situacao">
						<option value="0">Selecione a situação...</option>
						<option value="1" <?php $arrayiniciativa["Situacao"]=='Normal'?print "selected":"";?>>Normal</option>
						<option value="2" <?php $arrayiniciativa["Situacao"]=='Cancelada'?print "selected":"";?>>Cancelada</option>
					</select> 
				
				</td>
			</tr>	
			<tr>
				<td align="center">
					<div class="subscribe">
						<br>
						<input class="form-control"type="hidden" name="cxunidade" value="<?php print $arrayiniciativa['codIniciativa'];?>" />
						<input class="form-control"type="hidden" name="funcao" value="editar" />	
						<input class="form-control"id="gravar" type="button" value="Atualizar" class="btn btn-info btn"/>
					</div>
				</td>
			</tr>
		</table>
	</form>
</div>

<script>

	/* $('#adicionar2').submit(function(){
		
	alert($(this).serialize());
		$.ajax({
			url:  
			type: 'POST', 
			data: $(this).serialize(), 
			success: function(data) {
				alert(data);
				$('div#msg').empty();
				$('div#msg').html(data);
			}}); 
	}); */

	$('#gravar').click(function() {
		$('div#resultado').empty();		
		$.ajax({
			url: 'ajax/iniciativa/gravainiciativa.php', 
			type: 'POST', 
			data: $('form[name=adicionar2]').serialize(), 
			success: function(data) {
				$('div#resultado').html(data);
			}
		});
	});
</script>


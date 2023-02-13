<?php
$sessao = $_SESSION['sessao'];
$codUnidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();

$daounidade = new UnidadeDAO();
$grupos=$sessao->getGrupo();

$interno = false;

$c=new Controlador();
if ($c->getProfile($sessao->getGrupo())) {
   		$interno = true;
}

if(!$interno){
	$codunidade = ($sessao->getCodunidsel())?$sessao->getCodunidsel():$sessao->getCodUnidade();
	$arrayUnidade = $daounidade->unidadeporcodigo($codunidade)->fetch();
	$nomeUnidade=$arrayUnidade['NomeUnidade'];
}


if (!$aplicacoes[36]) {
    print "Você não tem permissão para acessar esta aplicação";
    exit();
}


$daodocumento = new DocumentoDAO();
$arraydocumento = $daodocumento->buscadocumentoporunidadePeriodoSemPDI($codUnidade, $sessao->getAnobase());

$edicao=0;

?>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active">
				<a href="<?php echo Utils::createLink('mapaestrategico', 'listamapapdu');?>"><?php if($codUnidade != 938){ echo "Painel Tático";}else{echo "Painel Estratégico";}?></a> 
				<i class="fas fa-long-arrow-alt-right"></i>
				<a href="<?php echo Utils::createLink("iniciativa", "listaIniciativa"); ?>" >Consulta</a>
				<i class="fas fa-long-arrow-alt-right"></i>
				<a href="#">Registra Iniciativa</a>
			</li>

		</ul>
	</div>
</head>

<div class="card card-info">
	<div class="card-header">
		<h3 class="card-title">Registrar/Editar Iniciativa</h3>
	</div>
	<form class="form-horizontal" name="adicionar" id="adicionar" method="POST" action="ajax/iniciativa/gravainiciativa.php" >
        <div id="msg"></div>
		
		<table class="card-body"> 
			<tr class="subscribe">
				<td class="coluna1">Nome da Iniciativa</td>
			</tr>
			<tr>
				<td class="coluna2"><input class="form-control"id="nome" class="form-control" type="text" name="nome" value="" /></td>
			</tr>
			<tr class="subscribe">
				<td class="coluna1">Ano de início</td>
			</tr>
			<tr>
				<td class="coluna2"><input class="form-control"id="anoinicio" class="form-control" type="text" disabled name="anoinicio" value="<?php echo $sessao->getAnobase();?>" /></td>
			</tr>
			<?php
			if($codUnidade == 938){ 
				echo '<tr><td><label> Informe a Unidade</label> </td>
						<td><input class="form-control"class="inputs form-control" type="text" id="cxunidade" name="cxunidade" placeholder="Unidade" autocomplete="off"/>
						<div id="suggesstion-box"></div> </td>                    
					</tr>';
				}else{
				echo '<input class="form-control"type="hidden" value="" id="cxunidade" name="cxunidade"/>';
				}
			?>
			<tr>
				<td align="center">
					<br>
					<div class="subscribe">
						<input class="form-control"type="hidden" name="funcao" id="funcao" value="gravar" />	
						<input class="form-control"id="gravar" type="btn btn-info" value="Gravar" name="salvar" class="btn btn-info btn"/>
					</div>
				</td>
			</tr>
		</table>
	</form>
</div>
<script>
	$('#gravar').click(function(){       
		//alert($('#adicionar').serialize());	
        $.ajax({
            url: 'ajax/iniciativa/gravainiciativa.php', 
            type: 'POST', 
            data: $('#adicionar').serialize(), 
            success: function(data) {
           		//alert(data);
				$('div#msg').empty();
		              $('div#msg').html('<div class="alert alert-success" role="alert">Iniciativa cadastrada com sucesso</div>');
		    }
		});
	});

	//////////////////
	//Buscar unidade
	$("#cxunidade").keyup(function(){	
		$.ajax({
		type: "POST",
		url: "sistema/ajax/resultadopdi/lerUnidade.php",
		data:'keyword='+$(this).val(),
		beforeSend: function(){
			$("#autocomp").css("background","#FFF url(img/LoaderIcon.gif) no-repeat 165px");
		},
		success: function(data){
			$("#suggesstion-box").show();
			$("#suggesstion-box").html(data);
			$("#cxunidade").css("background","#FFF");
		}
		}) ;

		$("#cxunidade").click(function(){
			$(this).val("");
		});
	});
</script>



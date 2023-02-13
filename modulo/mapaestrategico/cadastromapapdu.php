<?php
/* Sessão utilizada para esta aplicação é a PDI_PAINEL */
//session_start();
$sessao = $_SESSION['sessao'];
$anogestao=$sessao->getAnobase();
$aplicacoes = $sessao->getAplicacoes();
if (!isset($sessao) && !$aplicacoes[37]) {
    exit();
}

$daodoc = new DocumentoDAO();
$daopespect = new PerspectivaDAO();
$daoobjetivo = new ObjetivoDAO();
$codunidade = $sessao->getCodUnidade();

$c=new Controlador();

if ($codunidade==938){
	$rowsobj=$daoobjetivo->lista();
	$rowsdoc = $daodoc->buscadocumentoporunidadePeriodoSemPDI($codunidade, $sessao->getAnobase());
}else {
	if ($c->getProfile($sessao->getGrupo())) {//se grupo for 18  
		$rowsdoc = $daodoc->buscadocumentoPrazo($sessao->getAnobase());
		$rowsobj = $daoobjetivo-> buscaobjsPDI1($sessao->getAnobase(),$codunidade);
		
	}else{
		$rowsdoc = $daodoc->buscaporRedundancia($codunidade,$sessao->getAnobase());
		$rowsobj = $daoobjetivo-> buscaobjsPDI1($sessao->getAnobase(),$codunidade);
		
	}
}
$rowspes = $daopespect->lista();

?>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active">
				<a href="<?php echo Utils::createLink('mapaestrategico', 'listamapapdu'); ?>">
					Painel <?php print $sessao->getCodunidade()==938?"Estratégico":"Tático"; ?>
				</a>
				<i class="fas fa-long-arrow-alt-right"></i>
				<a href="#" >Adicionar Objetivo Estratégico</a>
			</li>  
		</ul>
	</div>
</head>

<div class="card card-info">
	<div class="card-header">
		<h3 class="card-title">Elaborar Mapa
			<?php if ($c->getProfile($sessao->getGrupo())) {?>
				Estratégico
			<?php } else{ ?>
					Tático
			<?php } ?>
		</h3>
	</div>
    <form class="form-horizontal" name="adicionar" method="POST" action="ajax/mapa/registramapapdu.php" id="pdi-painelres">
        <div id="resultado"></div>
        
        <table class="card-body">
        	<tbody>
        		<tr>
        			<td class="coluna1">
			            Documento
			        </td>
				</tr>
				<tr>
			        <td class="coluna2">
			            <select class="custom-select" name="codDocumento" id="documento-painel" class="sel1">
			                <option value="0">Selecione o documento...</option>
			                <?php foreach ($rowsdoc as $row) : ?>
			                	<?php $ano = $row['anoinicial']; ?>
			                	<option selected value=<?php print $row["codigo"]; $coddoc=$row["codigo"];?>><?php print $row['nome'] ?><?php print ' (' . $row['anoinicial'] . '-' . $row['anofinal'] . ')'; ?></option>
			                <?php endforeach; ?>
			            </select><br>
			        </td>
		        </tr>
		        
		        <?php if ($codunidade==938){?> 
				<tr>
					<td class="coluna1">	
						Pespectiva do PDI
					</td>
				</tr>
				<tr>
					<td class="coluna2">
						<select id="selperspectiva" name="codPerspectiva">            
							<option value="0">Selecione a perspectiva...</option>
			                <?php foreach ($rowspes as $row) : ?>
			                	<option value=<?php  print $row["codPerspectiva"]; ?>><?php  print $row['nome'] ?></option>
			                <?php  endforeach; ?>
			            </select>
			        </td>
		        </tr>
		        <?php } ?>
		        <tr> 
					<td class="coluna1">   
		            	Objetivo do PDI
		            </td>
				</tr>
				<tr>
		            <td class="coluna2">
						<select class="custom-select" name="codObjetivo">            
							<option value="0" >Selecione um objetivo...</option>
			                <?php foreach ($rowsobj as $row) : ?>
			                	<option value=<?php print $row["Codigo"]; ?> style = "width: 500px"><?php print $row['Objetivo'] ?></option>
			                <?php endforeach; ?>
			            </select>
			        </td>
		        </tr>
			</tbody>
			
			<tfoot>
				<td align="center">
					<br>
					<input type="button" value="Adicionar" id="gravar" name="salvarmapa" class="btn btn-info"/>
					<a href="<?php echo Utils::createLink('iniciativa', 'listaIniciativa'); ?>" >
					<button id="listainic" type="button" class="btn btn-info">Iniciativa</button></a>  	
	 				<input class="form-control"type="hidden" name="action" value="I" />
				</td>
			</tfoot>
  		</table>
    </form>
		  <div id="caixaselecaopdu"></div>      
</div>

<script>
    $('input[name=salvarmapa]').click(function() {

    	
		$('div#resultado').empty();		
        $.ajax({url: 'ajax/mapa/registramapapdu.php', type: 'POST', data: $('form[name=adicionar]').serialize(), success: function(data) {                
                $('div#resultado').html(data);
            }});
    	
    	
    	
        	setTimeout(function(){ 
    		
    		$('#caixaselecaopdu').empty();
        	$.ajax({url: "ajax/mapa/caixadeselecaopdu.php", type: 'POST', data:$('form[name=adicionar]').serialize(), success: function(result){
        		$("div#caixaselecaopdu").html(result);
        	}});
    		
    	}, 30);
    	
        
    });
</script>





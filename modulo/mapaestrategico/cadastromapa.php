<?php
/* Sessão utilizada para esta aplicação é a PDI_PAINEL */
session_start();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
if (!isset($sessao) && !$aplicacoes[37]) {
    exit();
}

?>

<?php
	$daodoc = new DocumentoDAO();
	$daopespect = new PerspectivaDAO();
	$daoobjetivo = new ObjetivoDAO();
	$codunidade = ($sessao->getCodunidsel())?$sessao->getCodunidsel():$sessao->getCodUnidade();
    $c=new Controlador();
    if ($c->getProfile($sessao->getGrupo())) {//se grupo for 18  
      $rowsdoc = $daodoc->buscadocumentoPrazo($sessao->getAnobase());
    }else{
      $rowsdoc = $daodoc->buscaporRedundancia($codunidade,$sessao->getAnobase());
    }
	$rowspes = $daopespect->lista();
	$rowsobj = $daoobjetivo->lista();
	
?>

<fieldset>
    <legend>Elaborar Mapa
    <?php if ($sessao->getCodunidade()==938) {?>
         Estratégico
  <?php  }else{ ?>
         Tático
    <?php    } ?>
    </legend>
    <form class="form-horizontal" name="adicionar" method="POST" action="ajax/mapa/registramapa.php" id="pdi-painelres">
        <div id="resultado"></div>
        
        <table>
        	<tbody>
        		<tr>
        			<td>
			            <label>Documento </label>
			        </td>
			        <td>
			            <select class="custom-select" name="codDocumento" id="documento-painel" class="sel1">
			                <option value="0">Selecione o documento...</option>
			                <?php foreach ($rowsdoc as $row) : ?>
			                	<?php $ano = $row['anoinicial']; ?>
			                	<option value=<?php print $row["codigo"]; ?>><?php print $row['nome'] ?><?php print ' (' . $row['anoinicial'] . '-' . $row['anofinal'] . ')'; ?></option>
			                <?php endforeach; ?>
			            </select><br>
			        </td>
		        </tr>
				<tr>
					<td>	
						<label>Pespectiva do PDI </label>
					</td>
					<td>
						<select id="selperspectiva" name="codPerspectiva">            
							<option value="0">Selecione a perspectiva...</option>
			                <?php foreach ($rowspes as $row) : ?>
			                	<option value=<?php print $row["codPerspectiva"]; ?>><?php print $row['nome'] ?></option>
			                <?php endforeach; ?>
			            </select>
			        </td>
		        </tr>
		        
		        <tr> 
		        	<td>   
		            	<label>Objetivo do PDI </label>
		            </td>
		            <td>
						<select class="custom-select" name="codObjetivo" style = "width: 300px">            
							<option value="0" >Selecione um objetivo...</option>
			                <?php foreach ($rowsobj as $row) : ?>
			                	<option value=<?php print $row["Codigo"]; ?> style = "width: 500px"><?php print $row['Objetivo'] ?></option>
			                <?php endforeach; ?>
			            </select>
			        </td>
		        </tr>
		        
		       
		</tbody>
  </table>
  <input type="button" value="Adicionar" name="salvarmapa" class="btn btn-info"/>
	   
		
  <input class="form-control"type="hidden" name="action" value="I" />
		    
    </form>
		  <div id="caixaselecao"></div>      
</fieldset>

<script>
    $('input[name=salvarmapa]').click(function() {

    	
		$('div#resultado').empty();
        $.ajax({url: $('form').attr('action'), type: 'POST', data: $('form[name=adicionar]').serialize(), success: function(data) {
                $('div#resultado').html(data);
            }});
    	
    	
    	
    	
    	setTimeout(function(){ 
    		
    		$('#caixaselecao').empty();
        	$.ajax({url: "ajax/mapa/caixadeselecao.php", type: 'POST', data:$('form[name=adicionar]').serialize(), success: function(result){
        		$("div#caixaselecao").html(result);
        	}});
    		
    	}, 30);
    	
    	
        
        
    });
</script>






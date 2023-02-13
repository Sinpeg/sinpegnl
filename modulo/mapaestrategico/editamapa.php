<?php
/* Sessão utilizada para esta aplicação é a PDI_PAINEL */
session_start();
$sessao = $_SESSION['sessao'];
if (!isset($sessao)) {
    exit();
}
?>

<?php
	$mapadao = new MapaDAO();
	$mapas = $mapadao->buscamapa($_POST['codigo']);
	foreach ($mapas as $mapa){}
	$daodoc = new DocumentoDAO();
	$daopespect = new PerspectivaDAO();
	$daoobjetivo = new ObjetivoDAO();
	$codunidade = ($sessao->getCodunidsel())?$sessao->getCodunidsel():$sessao->getCodUnidade();
	$rowsdoc = $daodoc->buscadocumentoporunidade($codunidade);
	$rowspes = $daopespect->lista();
	$rowsobj = $daoobjetivo->lista();
	
?>

<fieldset>
    <legend>Edição de Mapa Estratégico</legend>
    <form class="form-horizontal" name="adicionar" method="POST" action="ajax/mapa/registramapa.php" id="pdi-painelres">
        <div id="resultado"></div>
        
        <table>
        	<tbody>
        	
        		<tr>
        			<td>
		            	<label>Documento: </label>
		            </td>
		            <td>
			            <select class="custom-select" name="codDocumento" id="documento-painel" class="sel1">
			                <option value="0">Selecione o documento...</option>
			                <?php foreach ($rowsdoc as $row) : ?>
			                	<?php $ano = $row['anoinicial']; ?>
			                	<option value=<?php print $row["codigo"]; ?>  <?php if($mapa['CodDocumento'] == $row["codigo"]){print 'selected';}?>><?php print $row['nome'] ?><?php print ' (' . $row['anoinicial'] . '-' . $row['anofinal'] . ')'; ?></option>
			                <?php endforeach; ?>
			            </select><br>
			        </td>
				</tr>
				<tr>
					<td>	
						<label>Pespectiva: </label>
					</td>
					<td>
						<select class="custom-select" name="codPerspectiva">            
							<option value="0">Selecione a perspectiva...</option>
			                <?php foreach ($rowspes as $row) : ?>
			                	<option value=<?php print $row["codPerspectiva"]; ?> <?php if($mapa['codPerspectiva'] == $row["codPerspectiva"]){print 'selected';}?>><?php print $row['nome'] ?></option>
			                <?php endforeach; ?>
			            </select>
			        </td>
		        </tr>
		        <tr>
		        	<td> 
		            	<label>Objetivo: </label>
		           	</td>
		           	<td>
						<select class="custom-select" name="codObjetivo" style = "width: 300px">            
							<option value="0" >Selecione um objetivo...</option>
			                <?php foreach ($rowsobj as $row) : ?>
			                	<option value=<?php print $row["Codigo"]; ?> style = "width: 500px" <?php if($mapa['codObjetivoPDI'] == $row["Codigo"]){print 'selected';}?>><?php print $row['Objetivo'] ?></option>
			                <?php endforeach; ?>
			            </select> 
			        </td>
		        </tr>
			</tbody>            
        </table>
		         
		<input type="button" value="Salvar" name="salvar" class="btn btn-info"/>
		      	
		<input class="form-control"type="hidden" name="action" value="A" />
		<input class="form-control"type="hidden" name="codmapa" value="<?php print $_POST['codigo'];?>" />
		
    </form>
</fieldset>
<div id="mapa">
</div>
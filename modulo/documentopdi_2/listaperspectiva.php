<?php
$sessao = $_SESSION['sessao'];
$codUnidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();
?>

<?php 
	
	$perspectivadao = new PerspectivaDAO();
	$querryPerspectica = $perspectivadao->lista();
	$cont = 0;
	foreach ($querryPerspectica as $perspectiva){
		$arrayPerspectiva[$cont++] = $perspectiva;
	}
	
	$documentodao = new DocumentoDAO();
	$documento = $documentodao->buscadocumentoporunidadePeriodoSemPDI($codUnidade, $sessao->getAnoBase())->fetch();
	
	
?>




	<head>
		<div class="bs-example">
			<ul class="breadcrumb">
				<li class="active"><a href="#">Lista Perspectivas</a></li>  
			</ul>
		</div>
	</head>



		<table id="tablesorter" class="table table-bordered table-hover" >
		
			<tfoot>
		        <tr>
		            <th colspan="7" class="ts-pager form-horizontal">
		                <button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
		                <button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
		                <span class="pagedisplay"></span> <!-- this can be any element, including an input class="form-control"-->
		                <button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
		                <button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
		                <select class="custom-select" title="Select page size">
		                    <option selected="selected" value="10">10</option>
		                    <option value="20">20</option>
		                    <option value="30">30</option>
		                    <option value="40">40</option>
		                </select>
		                <select class="pagenum input-mini" title="Select page number"></select>
		            </th>
		        </tr>
		    </tfoot>
		
			<thead>
				<tr>
					<th>Perspectiva</th>
					<th>Editar</th>
				</tr>
			</thead>	
			<tbody>
				<?php foreach ($arrayPerspectiva as $perspectiv){?>
				<tr>
					<td><?php print $perspectiv['nome'] ?></td>
					<td><a href="<?php print Utils::createLink("documentopdi", "editarperspectiva", array('codPerspectiva'=> $perspectiv['codPerspectiva'])) ?>"><img  src="webroot/img/editar.gif"></a></td>
				</tr>
				<?php }?>
			</tbody>
		</table>

		
		<a href="<?php echo Utils::createLink('documentopdi', 'inserirperspectiva'); ?>" >
	    <button id="mostraTelaCadastro" type="button" class="btn btn-info btn">Nova Perspectiva</button></a>
		<br/>
		
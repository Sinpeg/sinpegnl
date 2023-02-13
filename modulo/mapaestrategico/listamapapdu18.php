<?php
//session_start ();
$_SESSION['mensagem']='';

$sessao = $_SESSION['sessao'];

if (!isset ( $sessao )) {
	exit();
}

$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
$anobase = $sessao->getAnobase();

$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$codunidade1=$unidade->getCodunidade();


$mapadao = new MapaDAO ();
$daodoc = new DocumentoDAO();
$daoobj=new ObjetivoDAO();

$c=new Controlador();

$rowsdoc = $daodoc->lista($anobase);

if ($rowsdoc->rowCount()==0){?>
    <div class="erro">
        <img src="webroot/img/error.png" width="30" height="30"/>
        <?php echo "É necessário Definir o Documento!"; ?>
    </div>
<?php  } else { 
	// $propmapa = $sessao->getCodUnidade ();able>
	// $mapa = $mapadao->buscaMapaByUnidadeDocumento ( 1, $propmapa );
	echo Utils::deleteModal('Remover ', 'Você tem certeza que deseja remover objetivo selecionado?'); ?>

	<head>
		<div class="bs-example">
			<ul class="breadcrumb">
				<li class="active"><a href="#" >Painel <?php print $sessao->getCodunidade()==938?"Estratégico":"Tático"; ?></a></li>  
			</ul>
		</div>
	</head>

	<div id="message"></div>

	<div class="card card-info">
		<form class="form-horizontal" name="chamaTabela">
			<table>
				<tr>
					<td class="coluna1">
						<label>Documento </label>
					</td>
				</tr>
				<tr>
					<td class="coluna1">
						<select class="custom-select" name="codDocumento" id="selectDocument" class="sel1">
						
							<?php foreach ($rowsdoc as $row){ 
								$ano = $row['anoinicial']; ?>
								<option value=<?php print $row["codigo"]; ?>><?php print $row['nome'] ?><?php print ' (' . $row['anoinicial'] . '-' . $row['anofinal'] . ')'; ?></option>
							<?php } ?>
						
						</select></td></tr></table>

	<?php
	

	$mapadao = new MapaDAO ();

	$codunidade = ($sessao->getCodunidsel())?$sessao->getCodunidsel():$sessao->getCodUnidade();


	//realiza filtro de mapa por documento ou por calendario.
	
	$mapa = $mapadao->buscaMapaByUnidadeDocumentoOuCalendario($codDocumento, $codunidade); 	
	
		
	?>

	<!--abriga mensagem de resposta quando o usuário tentar deletar pespectiva/objetivo-->
	<div id="m"></div>


	<!-- tabela gerada apartir do o bjeto mapa -->

	</form>
	</div>
	<?php 
	$daoResul=new ResultadoDAO();

	$rowsres=$daoResul->verResultados($codDocumento,$codunidade);
			$flag1=0;
			foreach ($rowsres as $r){
			$flag1=1; break;	
			}
			
			if ($flag1==0){ ?>
	<a href="<?php echo Utils::createLink('mapaestrategico', 'cadastromapapdu'); ?>" >
		<button id="mostraTelaCadastro" type="button" class="btn btn-info btn">Adicionar novo objetivo estratégico</button></a>  
		<?php } ?>


		<a href="<?php echo Utils::createLink('iniciativa', 'listaIniciativa'); ?>" >
		<button id="listainic" type="button" class="btn btn-info btn">Iniciativa</button></a>  
		
		&ensp; <a href="relatorio/painelTaticopdf.php?unidade=<?php echo $codunidade1;?>&anoBase=<?php echo $anobase;?>"><button type="button" class="btn btn-info">Exportar Painel
		<?php echo $sessao->getCodunidade()==938?"Estratégico":"Tático";?>
		</button></a>

	<br><br><br>
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
			<th>Objetivo</th>
			<th>Deletar</th>
					<th>Indicador</th>

			<!--<th>Editar</th>-->
		</tr>
		</thead>
		<tbody>
		<?php
		$cont = 1;
		foreach ($mapa as $row):?>
		<tr>
			<td><?php echo $row['nomeperspectiva'] ?></td>
			<td><?php echo $row['nomeobjetivo'] ?></td>
			<td align="center">
				<!--<button  id="<?php//echo "btn{$cont}"; $cont++;?>" value="<?php //echo $row['Codigo'];?>" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" >
	<img src ="webroot/img/delete.png"/></button>-->
				<?php $cont++;
				$dao=new MapaIndicadorDAO();
				$rowsi=$dao->verSeMapaTemIndicador($row['Codigo']);
				$passou=false;
				foreach ($rowsi as $r){
					$passou=true;
				}
				if ($passou){
				$img="webroot/img/delete.no.png";
				$disabled = "disabled";
					$evento="";
					$ajuda = "title='Não é possível excluir, pois o Objetivo possui Indicador vinculado.'' data-trigger='hover'";
				}else{
					$img="webroot/img/delete2.png";
					$evento='onclick="deletamapa(this);"';
					$disabled = "";
					$ajuda = "title='Deletar Objetivo.' data-trigger='hover'";
				}
				?>
			<button <?php echo $disabled;?> id="<?php echo "btn{$cont}"; $cont++;?>" value="<?php echo $row['Codigo'];?>" <?php echo $evento;?> <?php echo $ajuda;?>> <img src='<?php echo $img;?>' title="Ajuda" data-trigger='hover' alt="Possui indicador vinculado!" width="19" height="19"></button>

				
				
			</td>
			<td>
		<form class="form-horizontal" method="post" action="<?php echo Utils::createLink('indicadorpdi', 'consultaindicador'); ?>">
		<input class="form-control"type="hidden" name="codigo" value="<?php echo $row['Codigo']; ?>" /><input class="form-control"type='image' value="editar" src="webroot/img/add.png" />
		</form>
		</td>
		
		</tr>
		<?php
		$cont++;
		endforeach;?>
		<tbody>
	</table>

			

	<div id="dialog-confirm" title="Confirmação" style="display: none">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Você tem certeza que deseja eliminar o objetivo do seu painel tático?</p>
	</div>
	

<?php } //rowcount?>
 
<script>
     /* $.ajax({url: "ajax/mapa/deletamapa.php", type: 'POST', data: { codmapa: button.value, action : "D"}, success: function(data) {
        	 $('#m').html(data);
        	 var teste = "#"+button.id;
        	 teste = $(teste).parent().parent();
        	 teste.remove();
            }});*/
    
function deletamapa(button){

$(function() {
	$( "#dialog-confirm" ).dialog({
		resizable: false,
		height: "auto",
		width: 400,
		modal: true,
		buttons: {
			"Deletar": function() {
			$( this ).dialog( "close" );

			 	$.ajax({
                        url: "ajax/mapa/deletamapa.php", type: 'POST', data: { codmapa: button.value, action : "D"},
                        
                        async: true,
                        success: function(data) {

					
		        		$('div#message').html("<br><img src='webroot/img/accepted.png' width='30' height='30'/>Objetivo deletado com sucesso!");
		        	 	var teste = "#"+button.id;
		        	 	teste = $(teste).parent().parent();
		        	 	teste.remove();
                        },
					error:function(data) {

						$('div#message').html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'></button><img src='webroot/img/error.png' width='30' height='30'/><strong>Falha ao deletar!! Objetivo está vinculado à um ou mais Indicadores.</strong></div>");
                }
					
	         	});
				
        },
        Cancelar: function() {
            $( this ).dialog( "close" );
        }
      }
    });
  });
}

</script>
    
    
    
  
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

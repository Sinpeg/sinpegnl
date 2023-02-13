<?php 
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();

require_once 'classes/topico.php';
require_once 'dao/topicoDAO.php';

//Busca Unidades
$daoUnidade = new UnidadeDAO();
$rowU = $daoUnidade->ListaRes2();
$rowU2 = $daoUnidade->ListaRes2();

//Busca os tópicos da unidade
$daoTopicos = new RaaDAO();
$rowsT = $daoTopicos->buscartopicos($codunidade,$anobase);

//Buscar tópicos padroes
$rowsTopicosP = $daoTopicos->buscartopicospadroes($anobase);

//Buscar tópicos padroes
$rowConsideracoes = $daoTopicos->buscarconsideracoes($anobase);

//Declaração de variáveis
$vetTopicos = array(); // Vetor para guardar os registros de tópicos
$count = 0;	//Contador
$countTP = 0;//Quantidade de tópicos padrões
$countTU = 0;//Quantidade de tópicos da unidade

/*
foreach ($rowsT as $row){
	$count++;
}
echo $count;
*/
?>

<head>
	<link rel="stylesheet" type="text/css" href="webroot/css/multi-select.css">
	<script src="webroot/js/jquery.multi-select.js"></script>
	<script src="webroot/js/jquery.quicksearch.js"></script>

	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active">Consultar Tópicos</li>
		</ul>
	</div>
</head>

<div class="card card-info">
		<div class="card-header"><h3 class="card-title">Consultar Tópicos</h3></div>
        
		<div class="card-body">
			<table class="table table-bordered table-hover" style="width:800px;vertical-align:middle;text-align:center" align="center">
				<tr class="ui-state-default">
					<th><span class="glyphicon glyphicon-sort"></span>&ensp;&ensp;Ordem</th>
					<th>Tópicos</th>
					<?php if($codunidade == 100000){?>
					<th>Unidades</th>
					<?php }?>
					<th>Editar</th>                          
					<th>Subtopicos</th> 
					<th>Criar Modelo</th>            
				</tr>
				
				<tbody id="ordenarCorpoTopico">
					<?php
					if($codunidade != 100000){
						//Exibe os tópicos padrões 
						foreach ($rowsTopicosP as $row){		 
							if($row['situacao'] == 1){ $class = "ui-state-default";}else{ $class = "ui-state-default ";}
							echo "<tr data-name='".$row['codigo']."' class='ui-state-default desabilitar'><td>".$row['ordem']."</td><td width='50%'>".$row['titulo']."</td><td></td><td><img src='webroot/img/recuo.png' onClick='modalSubPadrao(".$row['codigo'].",".$anobase.")' data-toggle='modal' data-target='#subtopicosPadroes' /> </td><td></td></tr>";
							$countTP++;
						}
					}
					//Exibe os tópicos da unidade
					foreach ($rowsT as $row){
						if($codunidade != 100000){		 
							echo "<tr  data-name='".$row['codigo']."' class='ui-state-default info'><td>".$row['ordem']."</td><td width='50%'>".$row['titulo']."</td><td><img src='webroot/img/editar.gif' onClick='modalEditar(".$row['codigo'].")' data-toggle='modal' data-target='#editarTopico' /></td><td><a href='".Utils::createLink('raa', 'inserirSubtopico', array('topico' => $row['codigo'] ))."' target='_self' ><img src='webroot/img/recuo.png' /> </a></td><td><a href='".Utils::createLink('raa', 'criarModelo', array('topico' => $row['codigo'] ))."' target='_self' ><img src='webroot/img/modelo.png' /></a></td></tr>";
						}else{
							echo "<tr  data-name='".$row['codigo']."' class='ui-state-default info'><td>".$row['ordem']."</td><td width='50%'>".$row['titulo']."</td><td>";
							$rowUT = $daoTopicos->buscarUnidadesdoTopico($row['codigo']);
							if($rowUT->rowCount()>0){
								foreach($rowUT as $rows){
									echo "<font size='-3'>&#9679; ".$rows['NomeUnidade']."</font><br/>";
								}
							}else if($rowUT->rowCount()==0){
								echo "<font size='-3'>&#9679; Todas</font><br/>";
							}
							echo "</td><td><img src='webroot/img/editar.gif' onClick='modalEditar(".$row['codigo'].")' data-toggle='modal' data-target='#editarTopico' /></td><td><a href='".Utils::createLink('raa', 'inserirSubtopico', array('topico' => $row['codigo'] ))."' target='_self' ><img src='webroot/img/recuo.png' /> </a></td><td><a href='".Utils::createLink('raa', 'criarModelo', array('topico' => $row['codigo'] ))."' target='_self' ><img src='webroot/img/modelo.png' /></a></td></tr>";
						}
						$countTU++;
					}?>	 
				</tbody>
			</table>
		</div>
		<div class="card-body">
			<table class="table table-bordered table-hover" style="width:800px;vertical-align:middle;text-align:center" align="center">
				<thead>
					<tr class="ui-state-default">
						<th>Ordem</th>
						<th>Tópico Padrão</th>  
						<?php 
							if($codunidade == 100000){
								echo	"<th>Editar</th>";
							}  
						?>               
						
						<th>Subtopicos</th>
						<?php 
							if($codunidade == 100000){
								echo	"<th>Criar Modelo</th>";
							}  
						?>      
					</tr>
				</thead>
				<tbody>
					<?php 
					if($codunidade != 100000){
						foreach ($rowConsideracoes as $row){		 
							$countCF = $countTP+$countTU+1;		    	 	
							echo "<tr class='ui-state-default'><td>".$countCF."</td><td width='50%'>".$row['titulo']."</td><td><img src='webroot/img/recuo.png' onClick='modalSubPadrao(".$row['codigo'].",".$anobase.")' data-toggle='modal' data-target='#subtopicosPadroes' /></td></tr>";
						}
							
					}else{
						foreach ($rowConsideracoes as $row){
							$countCF = $countTP+$countTU+1;
							
							echo "<tr class='ui-state-default success'><td>".$countCF."</td><td width='50%'>".$row['titulo']."</td><td><img src='webroot/img/editar.gif' onClick='modalEditar(".$row['codigo'].")' data-toggle='modal' data-target='#editarTopico' /></td><td><a href='".Utils::createLink('raa', 'inserirSubtopico', array('topico' => $row['codigo'] ))."' target='_self' ><img src='webroot/img/recuo.png' /> </a></td><td><a href='".Utils::createLink('raa', 'criarModelo', array('topico' => $row['codigo'] ))."' target='_self' ><img src='webroot/img/modelo.png' /> </a></td></tr>";
						}
					}
					?>	
				</tbody>	 
			</table>
		</div>	

		<div class="card-body" align=center>
			<button type="button" id="botao" class="btn btn-info" data-toggle="modal" data-target="#cadastrarTopico">Criar Tópico</button> 
		</div>    
</div>



<!-- Modal Inserir-->
<div class="modal fade" id="cadastrarTopico" tabindex="-1" role="dialog" aria-labelledby="cadastrarTopico" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      	<div class="modal-header">
          <h4 class="modal-title">Criar Tópico</h4>
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
    	</div>
      <div class="modal-body">
      
         <div id="msg"></div>
         
        <form class="form-horizontal" name="form-criarTopico" id="form-criarTopico" method="POST"  >
		    <fieldset>		       
		        <table>
		        <tr><td>Título do Tópico</td><td><input class="form-control"type="text"  class="form-control" name="titulo" id="titulo"/></td></tr>
		        <input class="form-control"type="hidden" name="situacao" value="1" id="situacao"/>
		        </table>
		        
		        <?php if($codunidade == 100000){?>	
		        <!-- start -->
				  <h4>Víncular Unidades</h4>
				  <select id='pre-selected-options' name='topico_unidade[]' multiple='multiple'>
				   <option value='0' selected>Todas</option>
				    <?php 
				    foreach ($rowU as $row){
				    	echo '<option value="'.$row['CodUnidade'].'">'.$row['NomeUnidade'].'</option>';
				    }
				    ?>
				  </select>
				  <!-- ends -->
				<?php }?>
				  
		   </fieldset>
		   <!-- Dados hidden -->
		   <input class="form-control"type="hidden" name="operacao" value="I">
		   <input class="form-control"type="hidden" name="codUnidade" value="<?php echo $codunidade?>">
		   <input class="form-control"type="hidden" name="anobase" value="<?php echo $anobase?>">
		</form>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" id="btnCriarTopico" class="btn btn-info">Criar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal Editar-->
<div class="modal fade" id="editarTopico" tabindex="-1" role="dialog" aria-labelledby="editarTopico" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">          
          <h4 class="modal-title">Editar Tópico</h4>
        </div>
      	<div class="modal-body">
      
			<div id="msg2"></div>
			
			<form class="form-horizontal" name="form-editarTopico" id="form-editarTopico" method="POST"  >
				<div>		       
					<table>
						<tr>
							<td class="coluna1">
								Título do Tópico
							</td>
						</tr>
						<tr>
							<td class="coluna2">	
								<input class="form-control" type="text" name="titulo" id="editarTitulo"/>
							</td>
						</tr>
						<tr>
							<td class="form-check">
								<input class="form-check-input" type="checkbox" name="situacao" value="1" id="editarSituacao"/>
								<label class="form-check-label" for="editarSituacao"> Válido </label>
							</td>
						</tr>
					</table>
					<?php if($codunidade == 100000){?>	
					<!-- start -->
					<h4>Víncular Unidades</h4>
					<select id='pre-selected-options2' name='topico_unidade[]' multiple='multiple'>
					<option value='0'>Todas</option>				   
						<?php 
						foreach ($rowU2 as $row2){						
							echo '<option value="'.$row2['CodUnidade'].'">'.$row2['NomeUnidade'].'</option>';
						}
						?>
					</select>
					<!-- ends -->
					<?php }?>
				</div>
			<!-- Dados hidden -->
			<input class="form-control"type="hidden" name="operacao" value="A">
			<input class="form-control"type="hidden" name="codUnidade" value="<?php echo $codunidade?>">
			<input class="form-control"type="hidden" name="codTopico" id="codTopico" value="">
			<input class="form-control"type="hidden" name="anobase" value="<?php echo $anobase?>">
			</form>
      
      	</div>
		<div class="modal-footer">
			<button type="button" id="btnFecharTopico" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
			<button type="button" id="btnSalvarTopico" class="btn btn-info">Salvar</button>
		</div>
    </div>
  </div>
</div>


<!-- Modal Excluir-->
<div class="modal fade" id="excluirTopico" tabindex="-1" role="dialog" aria-labelledby="excluirTopico" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Excluir Tópico</h4>
        </div>
      <div class="modal-body">         
        <form class="form-horizontal" name="form-excluirTopico" id="form-excluirTopico" method="POST"  >
		    <fieldset>		       
		          <p>Deseja realmente excluir o tópico "<span id="tituloTopicoExcluir"></span>" ?</p>
		   </fieldset>
		   <input class="form-control"type="hidden" name="codTopico" id="codTopico" value="">
		</form>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
        <button type="button" id="btnExcluirTopico" class="btn btn-info">Sim</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Exibir Subtopicos padroes-->
<div class="modal fade" id="subtopicosPadroes" tabindex="-1" role="dialog" aria-labelledby="subtopicosPadroes" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">Subtopicos</h4>
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>  
      <div class="modal-body">      
      	<div id="divSubtopicosPadroes"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  // run pre selected options
  $('#pre-selected-options').multiSelect({
	  selectableHeader: "<input class='form-control' type='text' style='width: 254px;' class='search-input' autocomplete='off' placeholder='Unidades'>",
	  selectionHeader: "<input class=' form-control' type='text' style='width: 254px;' class='search-input' autocomplete='off' placeholder='Unidades Vínculadas'>",
	  afterInit: function(ms){
		    var that = this,
		        $selectableSearch = that.$selectableUl.prev(),
		        $selectionSearch = that.$selectionUl.prev(),
		        selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
		        selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

		    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
		    .on('keydown', function(e){
		      if (e.which === 40){
		        that.$selectableUl.focus();
		        return false;
		      }
		    });

		    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
		    .on('keydown', function(e){
		      if (e.which == 40){
		        that.$selectionUl.focus();
		        return false;
		      }
		    });
		  },
		  afterSelect: function(){
		    this.qs1.cache();
		    this.qs2.cache();
		  },
		  afterDeselect: function(){
		    this.qs1.cache();
		    this.qs2.cache();
		  }
 });
</script>
<script>

</script>
<style>
.modal {
  text-align: center;
  padding: 0!important;
}

.modal:before {
  content: '';
  display: inline-block;
  height: 100%;
  vertical-align: middle;
  margin-right: -4px;
}

.modal-dialog {
  display: inline-block;
  text-align: left;
  vertical-align: middle;
}
</style>


<?php 
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();

require_once 'classes/modelo.php';
require_once 'dao/modeloDAO.php';
require_once 'dao/topicoDAO.php';
require_once 'dao/subtopicoDAO.php';

//Busca os modelos
$modelosDAO = new ModeloDAO();
$rowsM = $modelosDAO->buscarmodelos($codunidade,$anobase); 

//Buscar Tópicos para Ordenação
$daoTopicos = new RaaDAO();
$rowsOrdenarT = $daoTopicos->buscartopicos($codunidade,$anobase);//Buscar Tópicos

//Buscar Subtópicos para Ordenação
$subtopicoDAO = new SubtopicoDAO();
$rowsOrdenarS = $subtopicoDAO->buscarsubtopicosunidade($codunidade,$anobase);

//Declaração de variáveis
$vetModelos = array(); // Vetor para guardar os registros de modelos
$count = 0;	//Contador

$uniDAO = new UnidadeDAO();

foreach ($rowsM as $row){
	$count++;
	$vetModelos[$count] = new Modelo(); 
	$vetModelos[$count]->criaModeloIni($row['codigo'], $row['legenda'], $row['descModelo'], $row['anoInicio'], $row['anofinal'], $row['codUnidade'], $row['codTopico'], $row['situacao']);
}
if(count($vetModelos)==0){
	//echo "teste:".count($vetModelos);
	Utils::redirect('raa','criarModelo2');
}{	?>
	<head>
		<div class="bs-example">
			<ul class="breadcrumb">
				<li class="active">
					<a href="<?php echo Utils::createLink("raa", "consultarTopicos"); ?>">Consultar Tópicos</a>
					<i class="fas fa-long-arrow-alt-right"></i>
					Consultar Modelos
				</li>
			</ul>
		</div>
	</head>
	<div class="card card-info">
		<div class="card-header">
			<h3 class="card-title">Consultar Modelos</h3>
		</div>
		
		<div class="card-body">
			<table class="table table-bordered table-hover">        
				<thead align="center">
					<th>Legenda</th>
					<th>Tópico / Subtopico</th>
					<th>Unidade</th>
					<th>Visualizar</th>
					<th>Editar</th>                          
				</thead>       
				<tbody>
					<?php for ($i = 1; $i <= count($vetModelos); $i++) {
						//Buscar dados do tópico
						$topicoDAO = new RaaDAO();
						$rowTopico = $topicoDAO->buscarTopicoCod($vetModelos[$i]->gettopico());

						foreach ($rowTopico as $row){
							$tituloTopico = $row['titulo'];		 				 		
						}
						
						if($vetModelos[$i]->getunidade()==NULL){
							$uni = "Todas";
						}else{
							$dadosUni = $uniDAO->buscaUnidade($vetModelos[$i]->getunidade())->fetch();
							$uni = $dadosUni['NomeUnidade'];
						}

						//if($vetModelos[$i]->getSituacao() == 1){ $class = "ui-state-default success";}else{ $class = "ui-state-default danger";}
						$descModelo = $vetModelos[$i]->getmodelo();
						echo "<tr data-name='".$vetModelos[$i]->getCodigo()."' class='ui-state-default'><td>".$vetModelos[$i]->getlegenda()."</td><td>".$tituloTopico."</td><td>".$uni."</td>
							<td align=\"center\"><a><img src='webroot/img/busca.png' onClick='modalVisualizar(".$vetModelos[$i]->getCodigo().")' data-toggle='modal' data-target='#visualizarModelo' />
							</a></td><td align=\"center\"><a href='".Utils::createLink('raa', 'editarModelo', array('modelo' => $vetModelos[$i]->getCodigo() ))."' target='_self' >
							<img src='webroot/img/editar.gif'/></a></td></tr>";
					}?>		 
				</tbody>
			</table>
		</div>
		<table class="card-body">
			<tfoot>
				<td colspan="4" align="center">
					<a href="<?php echo Utils::createLink("raa", "criarModelo2")?>"><button type="button" id="criar" class="btn btn-info">Criar Modelo</button></a>
					<button type="button" class="btn btn-info" data-toggle="modal" id="ordenar" data-target="#ordenarModelo">Ordenar Modelos</button>    
				</td>
			</tfoot>
		</table>

	</div>
<?php }?>

<!-- Modal Visualizar Modelo-->
<div class="modal fade" id="visualizarModelo" tabindex="-1" role="dialog" aria-labelledby="visualizarModelo" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">Modelo - <span id="legendaVerModelo"></span> </h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
      <div class="modal-body">
      
         <div id="divModelo"></div>         
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal">Fechar</button>        
      </div>
    </div>
  </div>
</div>

<!-- Modal Excluir Modelo-->
<div class="modal fade" id="excluirModelo" tabindex="-1" role="dialog" aria-labelledby="excluirModelo" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Excluir Modelo</h4>
        </div>
      <div class="modal-body">         
        <form class="form-horizontal" name="form-excluirModelo" id="form-excluirModelo" method="POST"  >
		    <fieldset>		       
		          <p>Deseja realmente excluir o modelo "<span id="legendaModeloExcluir"></span>" ?</p>
		   </fieldset>
		   <input class="form-control"type="hidden" name="codModeloExcluir" id="codModeloExcluir" value="">
		</form>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
        <button type="button" id="btnExcluirModelo" class="btn btn-info">Sim</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal para realizar a ordenação dos modelos dentro do tópico/subtopico -->
<div class="modal fade" id="ordenarModelo" tabindex="-1" role="dialog" aria-labelledby="ordenarModelo" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">Ordenar Modelo</h4>
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
      <div class="modal-body"> 
      	<div id="msgOrdenar"></div>        
        <form class="form-horizontal" name="form-ordenarModelo" id="form-ordenarModelo" method="POST"  >
		    <table >
		    	<tr><td>Tópico / Subtopico:</td>
		    	<td><select class="custom-select" name="topico" id="topicoOrdenar">
        		<option value="0"><i>-- Selecione </i></option>
	        	<?php 
	        	foreach ($rowsOrdenarT as $row){
	        		echo '<option value="'.$row['codigo'].'">'.$row['titulo'].'</option>';
	        	}
	        	
	        	foreach ($rowsOrdenarS as $row){
	        		echo '<option value="'.$row['codigo'].'">'.$row['titulo'].'</option>';
	        	}
	        	?>
        		</select></td></tr>		    
		    </table>		   
		</form>
		<div id="tableConteudo" style="display:none;">
	      	<table id="tablesorter" class="table table-bordered table-hover" >
			<thead>
	            <tr class="ui-state-default">
	           		<th><span class="glyphicon glyphicon-sort"></span>&ensp;&ensp;Ordem</th>
	                <th>Legenda</th>                            
	            </tr>
	        </thead>
			 <tbody id="ordenarCorpoModelos">
			</tbody>
			</table>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<style>
<!--
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
  width:55%;
}
-->

 ul {
     list-style-type:none;
 }
</style>

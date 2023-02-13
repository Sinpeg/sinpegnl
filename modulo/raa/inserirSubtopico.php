<?php 
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();

require_once 'classes/topico.php';
require_once 'dao/subtopicoDAO.php';
require_once 'dao/topicoDAO.php';

$topico = $_GET['topico'];

$subtopicoDAO = new  SubtopicoDAO();
$rowsSubtopicos = $subtopicoDAO->buscarsubtopicos($codunidade, $topico,$anobase);

//Buscar Tópico
$topicoDAO = new RaaDAO();
$rowTopico = $topicoDAO->buscarTopicoCod($topico);
foreach ($rowTopico as $row){
	$tituloTopico = $row['titulo'];
}

?>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
		<li class="active"><a href="<?php echo Utils::createLink("raa", "consultarTopicos"); ?>">Consultar Tópicos</a>
      <i class="fas fa-long-arrow-alt-right"></i>
			Inserir/Ordenar Subtopicos
    </li>
		</ul>
	</div>
</head>

<div class="card card-info">
  <div class="card-header">
    <h3 class="card-title">Inserir/Ordenar Subtopicos</h3>
  </div>
    
  <div class="card-body">
    <p><b>Tópico: <?php echo $tituloTopico;?></b></p>
    <table id="tablesorter" class="table table-bordered table-hover">
        <thead align="center">
          <tr class="ui-state-default">
            <th>Ordem</th>
            <th>Título do Subtopico</th>
            <th>Editar</th>
            <th>Inserir / Ordenar Subtopico</th>
            <th>Criar Modelo</th>            
          </tr>
        </thead>
      <tbody id="ordenarCorpoSubtopico">
        <?php //Exibir Subtópicos
        $count = 1;
        foreach ($rowsSubtopicos as $row){
          if($row['situacao'] == 1){ 
            $class = "ui-state-default success";
          }else{ 
            $class = "ui-state-default danger";
          }
          echo '<tr data-name="'.$row['codigo'].'" class="'.$class.'"><td>'.$row['ordem'].'</td><td width="50%">'
              .$row['titulo'].'</td><td align="center"><img src="webroot/img/editar.gif" onClick="modalEditar('.$row['codigo'].')" 
              data-toggle="modal" data-target="#editarSubtopico" /></td><td align="center"><a href="'.Utils::createLink("raa", "inserirSubtopico", 
              array("topico" => $row["codigo"] )).'" target="_self" ><img src="webroot/img/recuo.png" /> </a></td><td align="center"><a href="'
              .Utils::createLink("raa", "criarModelo", array("topico" => $row["codigo"] )).'" target="_self" >
              <img src="webroot/img/modelo.png" /> </a></td></tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
    <table class="card-body">
      <tfoot>
        <td align="center" colspan="5">
          <button type="button" class="btn btn-info" id="botao" data-toggle="modal" data-target="#inserirSubtopico">Inserir Subtopico</button> 
        </td>
      </tfoot>
    </table>
</div>

<!-- Modal Inserir-->
<div class="modal fade" id="inserirSubtopico" tabindex="-1" role="dialog" aria-labelledby="inserirSubtopico" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">Criar Subtopico</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
      <div class="modal-body">
      
         <div id="msg"></div>
         
        <form class="form-horizontal" name="form-criarSubtopico" id="form-criarSubtopico" method="POST"  >
		    <fieldset>		       
		        <table>
		        <tr><td>Título do Subtpico</td><td><input class="form-control"type="text" name="titulo" id="titulo"/></td></tr>
		        <input class="form-control"type="hidden" name="situacao" value="1" id="situacao"/>
		        </table>
		   </fieldset>
		   
		   <!-- Dados hidden -->
		   <input class="form-control"type="hidden" name="operacao" value="I">
		   <input class="form-control"type="hidden" name="codUnidade" value="<?php echo $codunidade;?>">
		   <input class="form-control"type="hidden" name="topico" id="topico" value="<?php echo $_GET['topico'];?>">
		   <input class="form-control"type="hidden" name="anobase" value="<?php echo $anobase;?>">
		
		</form>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" id="btnCriarSubtopico" class="btn btn-info">Criar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal Editar-->
<div class="modal fade" id="editarSubtopico" tabindex="-1" role="dialog" aria-labelledby="editarSubtopico" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Editar Subtopico</h4>
        </div>
      <div class="modal-body">
      
         <div id="msg2"></div>
         
        <form class="form-horizontal" name="form-editarTopico" id="form-editarTopico" method="POST" >
		    <fieldset>		       
		        <table>
		        <tr><td>Título do Subtopico</td><td><input class="form-control"type="text" name="titulo" id="editarTitulo"/></td></tr>
		        <tr><td>Válido</td><td><input class="form-check-input" type="checkbox" name="situacao" value="1" id="editarSituacao"/></td></tr>
		        </table>
		   </fieldset>
		   <!-- Dados hidden -->
		   <input class="form-control"type="hidden" name="operacao" value="A">
		   <input class="form-control"type="hidden" name="codUnidade" value="<?php echo $codunidade?>">
		   <input class="form-control"type="hidden" name="codTopico" id="codTopico" value="">
		   <input class="form-control"type="hidden" name="anobase" value="<?php echo $anobase;?>">
		</form>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" id="btnSalvarTopico" class="btn btn-info">Salvar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal Excluir-->
<div class="modal fade" id="excluirSubtopico" tabindex="-1" role="dialog" aria-labelledby="excluirSubtopico" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Excluir Subtopico</h4>
        </div>
      <div class="modal-body">         
        <form class="form-horizontal" name="form-excluirTopico" id="form-excluirTopico" method="POST"  >
		    <fieldset>		       
		          <p>Deseja realmente excluir o subtopico "<span id="tituloTopicoExcluir"></span>" ?</p>
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
}
-->

 ul {
     list-style-type:none;
 }
</style>
  
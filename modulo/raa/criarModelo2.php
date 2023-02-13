<?php
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();

require_once 'classes/modelo.php';
require_once 'dao/modeloDAO.php';
require_once 'dao/topicoDAO.php';
require_once 'dao/subtopicoDAO.php';

//Busca Unidades
$daoUnidade = new UnidadeDAO();
$rowU = $daoUnidade->Lista();

//Buscar Tópicos
$daoTopicos = new RaaDAO();
$rowsT = $daoTopicos->buscartopicos($codunidade, $anobase);//Buscar Tópicos

//Buscar Subtópicos
$subtopicoDAO = new SubtopicoDAO();
$rowsS = $subtopicoDAO->buscarsubtopicosunidade($codunidade,$anobase);

//Declaração de variáveis
$vetTopicos = array(); // Vetor para guardar os registros de tópicos
$count = 0;	//Contador

?>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
		<li><a href="<?php echo Utils::createLink("raa", "consultarModelos"); ?>">Consultar Modelos</a></li>
			<li class="active">Criar Modelo</li>
		</ul>
	</div>
</head>

<div class="card card-info">
	<div class="card-header">
		<h3 class="card-title">Criar Modelo</h3>
	</div>
    <form class="form-horizontal" name="form-criarModelo" id="form-criarModelo" method="POST">
        <table class="card-body">
			<div id="msg"></div>
        	<tr><td class="coluna1">Tópico / Subtopico</td></tr>
        	<tr>
				<td class="coluna2">
					<select class="custom-select" name="topico" id="topico">
						<option value="0"><i>-- Selecione --</i></option>
						<?php 
						foreach ($rowsT as $row){
							echo '<option value="'.$row['codigo'].'">'.$row['titulo'].'</option>';
						}
						
						foreach ($rowsS as $row){
							echo '<option value="'.$row['codigo'].'">'.$row['titulo'].'</option>';
						}
						?>
        			</select>
				</td>
			</tr>
        	<?php
        		if($codunidade == 100000){ 
        			echo '<tr><td class="coluna1">Unidade</td></tr><tr><td class="coluna2"><select class="custom-select" name="codUnidade" class="form-control"><option value="0">Todas as Unidades</option>';
	        		foreach ($rowU as $row){
	        			echo '<option value="'.$row['CodUnidade'].'">'.$row['NomeUnidade'].'</option>';
	        		}
	        		echo '</select></td></tr>';
	        	}else{
	        		echo '<input class="form-control"name="codUnidade" value="'.$codunidade.'" type="hidden" />';
	        	}
        	?>
        	<tr>
				<td class="coluna1">Legenda</td>
			</tr>
			<tr>
				<td class="coluna2">
					<input class="form-control"type="text" class="form-control" name="legenda" id="legenda"/>
				</td>
			</tr>
        	<input class="form-control"type="hidden"  name="situacao" value="1" id="situacao"/>
        	<tr>
				<td class="coluna1">Modelo</td>
				<td></td>
			</tr>
        	<tr>
				<td colspan="2">
					<textarea cols="10" name="modelo" id="editorModelo" name="editordata"></textarea> 
				</td>
			</tr>
        </table>
		<table class="card-body">
			<tfoot>
				<td align="center">
					<!-- Campos Hidden -->
					<input class="form-control"name="operacao" value="I" type="hidden" />
					<input class="form-control"name="codUnidadeSessao" value="<?php echo $codunidade;?>" type="hidden" />
					<input class="form-control"name="anobase" value="<?php echo $anobase;?>" type="hidden" /> 
					<button type="button" id="btnCriarModelo" class="btn btn-info">Criar Modelo</button>  
				</td>
			</tfoot>
		</table>     	
    </form>    
</div>      

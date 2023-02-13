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
$rowsT = $daoTopicos->buscartopicos($codunidade,$anobase);//Buscar Tópicos

//Buscar Subtópicos
$subtopicoDAO = new SubtopicoDAO();
$rowsS = $subtopicoDAO->buscarsubtopicosunidade($codunidade,$anobase);

//Buscar dados do modelo
$codModelo = $_GET['modelo'];
$modeloDAO = new ModeloDAO();
$rowModelo = $modeloDAO->buscarModeloCod($codModelo);

foreach ($rowModelo as $row){
	$legenda = $row['legenda'];
	if($row['situacao'] == 1){
		$check = "checked";
	}else{
		$check = "";
	}
	$modelo = $row['descModelo'];
	$codTopico = $row['codTopico'];
	$codUnidadeModelo = $row['codUnidade'];
}

//Buscar Tópico Selecionado
$rowTopicoSelecionado = $daoTopicos->buscarTopicoCod($codTopico);
foreach ($rowTopicoSelecionado as $row){
	$codigoTopicoSel = $row['codigo'];
	$tituloTopicoSel = $row['titulo'];
 	$codUnidade = $row['codUnidade'];
	
}
$codUnidade;
//Declaração de variáveis
$vetTopicos = array(); // Vetor para guardar os registros de tópicos
$count = 0;	//Contador
?>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active">
				<a href="<?php echo Utils::createLink("raa", "consultarModelos"); ?>">Consultar Modelos</a>
				<i class="fas fa-long-arrow-alt-right"></i>
				Editar Modelo
			</li>
		</ul>
	</div>
</head>

<div class="card card-info">
	<div class="card-header">
		<h3 class="card-title">Editar Modelo</h3>
	</div>	
    <form class="form-horizontal" name="form-editarModelo" id="form-editarModelo" method="POST">
		<table class="card-body">
			<div id="msg"></div>
        	<tr>
				<td class="coluna1">Tópico / Subtopico</td>
			</tr>
			<tr>
        		<td class="coluna2">
					<select class="custom-select" name="topico" id="topico">
        				<option value="<?php echo $codigoTopicoSel?>"><?php echo $tituloTopicoSel;?></option>
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
        	<?php if($codunidade == 100000){ 
        			echo '<tr><td class="coluna1">Unidade</td></tr><tr><td class="coluna2"><select class="custom-select" name="codUnidade" class="form-control">';
	        		if($codUnidade == NULL){
	        			echo '<option  value="0">Todas as Unidades</option>';
	        		}
	        		
	        		foreach ($rowU as $row){
						if($codUnidadeModelo == $row['CodUnidade']){
							
							echo '<option selected value="'.$row['CodUnidade'].'">'.$row['NomeUnidade'].'</option>';
						}else{
							echo '<option value="'.$row['CodUnidade'].'">'.$row['NomeUnidade'].'</option>';
						}
	        			
	        			
	        		}
	        		echo '</select></td></tr>';
	        	}else{
	        		echo '<input class="form-control"name="codUnidade" value="'.$codunidade.'" type="hidden" />';
	        	}
        	?>
        	<tr><td class="coluna1">Legenda</td></tr><tr><td class="coluna2"><input class="form-control"type="text" value="<?php echo $legenda;?>" class="form-control" name="legenda" id="legenda"/></td></tr>
        	<tr>
				<td class="coluna1">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="situacao" value="1"  id="situacao"/>
						<label class="form-check-label" for="editarSituacao"> Válido </label>

						
					</div>
				</td>
			</tr>
        	<tr><td class="coluna1" align="center">Modelo</td></tr><tr><td></td></tr>
        	<tr><td colspan="2"><textarea cols="10" name="modelo"  id="editorModelo" name="editordata"><?php echo $modelo;?></textarea> </td></tr>
        </table>
		
		<div class="card-body" align="center">
			<!-- Campos Hidden -->
			<input class="form-control"name="operacao" value="A" type="hidden" />
			<input class="form-control"name="codUnidadeSessao" value="<?php echo $codunidade;?>" type="hidden" />
			<input class="form-control"name="codModelo" value="<?php echo $_GET['modelo'];?>" type="hidden" />
			<input class="form-control"name="anobase" value="<?php echo $anobase;?>" type="hidden" />
			<button type="button" id="btnEditarModelo" class="btn btn-info">Gravar</button>
		</div>
    </form>      
</div>      

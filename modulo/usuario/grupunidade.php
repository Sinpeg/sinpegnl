<?php
/*
 * Inclui unidade no grupo
 */

$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeUnidade();
$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
$loginsessao = $sessao->getLogin();
$aplicacoes = $sessao->getAplicacoes();

if (!$aplicacoes[47]) {
	exit();
}

$BASE_DIR = dirname(__FILE__) . '/';
require_once $BASE_DIR . '../../dao/grupoDAO.php';
require_once $BASE_DIR . '../../dao/unidadeDAO.php';

$codunidade = $sessao->getCodUnidade();
$daocat = new UnidadeDAO();
/*
$hierarquia = $daocat->buscahierarquia($codunidade);
foreach($hierarquia as $hiera){
	$hier =  addslashes($hiera["hierarquia"]);
}
$subunidades = $daocat->buscalunidade("", $hier);*/
$grupo=$sessao->getGrupo();
$dao = new GrupoDAO();
$grupos = $dao->Lista($loginsessao);

//Diogo - Não foi encontrado ultilidade no trecho abaixo
//$ct = 0;
//foreach ($_SESSION['codunidade'] as $a){
//	++$ct;
//}

//---------------
for($i=1; $i < 50; ++$i){
	$apgrupo[$i] = $dao->Listagrupo($i);
}

/*
for($i=1; $i < 3; ++$i){
	foreach($apgrupo[$i] as $a){
		echo $a["NomeUnidade"];
	}
}*/
/*foreach($apgrupo[1] as $as){
$vet = $apgrupo["NomeUnidade"];
}
$aux;*/
//---------------"<?php echo addslashes($_POST['cad-consulta']) 


?>

<script>
	function direciona() {
		selectBox = document.getElementById("select3");
		for (var i = 0; i < selectBox.options.length; i++) 
		{ 
			selectBox.options[i].selected = true;
		}
		document.getElementById('form-cadunidade').action = "<?php echo Utils::createLink('usuario', 'incgrupo'); ?>";
		document.getElementById('form-cadunidade').submit();
	}

	$(function() {
		$("select[name=cad-consulta]").change(function() {
			$("#aplicgrupo").html("");
				$("#aplicgrupo").addClass("ajax-loader");
				$.ajax({
					url: "ajax/usuario/aplicacaogrupo.php",
					type: "POST",
					data: "cad-consulta=" + $("select[name=cad-consulta]").val(),
					success: function(data) {
						$("#aplicgrupo").html(data);
						$("#aplicgrupo").removeClass("ajax-loader");           
						segundaf();
					}
				});
		});
		
	});

	//unidades selecionadas
	function terceiraf(){
		$("#select2").html("");
		$("#select2").addClass("ajax-loader");
		$.ajax({
			url: "ajax/usuario/unidgrupo.php",
			type: "POST",
			data: "cad-consulta=" + $("select[name=cad-consulta]").val(),
			success: function(data) {
				$("#select2").html(data);
				$("#select2").removeClass("ajax-loader");
			}
		});
	}

	//unidades selecionadas
	function segundaf(){
		$("#selectu").html("");//selectu
		$("#selectu").addClass("ajax-loader");
		$.ajax({
			url: "ajax/usuario/subunidades.php",
			type: "POST",
			data: "cad-consulta=" + $("select[name=cad-consulta]").val(),
			success: function(data) {
				$("#selectu").html(data);
				$("#selectu").removeClass("ajax-loader");
				terceiraf();
			}
		});
	}

	$(document).ready(function(){
		$( "#cad-consulta" )
		.change(function() {
			$( "select option:selected" ).each(function() {
				// $("#select2").load("ajax/usuario/unidgrupo.php?grupo="+$("#cad-consulta").val());

			});
		})
		.trigger( "change" );
	}) 

	$(document).ready(function(){ //Faz as seleção das unidades.
		$('#add').click(function() {//Adiciona da lista1 para a lista2
			return !$('#select1 option:selected').remove().appendTo('#select3');  
		});
		//remove todos
		$('#remove').click(function() { //Exclui todas as entradas da lista2
			selectBox = document.getElementById("select3");
			for (var i = 0; i < selectBox.options.length; i++){ 
				selectBox.options[i].selected = true;
			}
			return !$('#select3 option:selected').remove().appendTo('#select1');
		});
		
		//  $("#lis1").click(function(){//remove item
			//   $('#select3 option:selected').remove();
			//});
		$('#lis1').click(function() {//Adiciona da lista1 para a lista2
			return !$('#select3 option:selected').remove().appendTo('#select1');  
		});
	});
</script>

<div class="bs-example">
    <ul class="breadcrumb">
        <li class="active">Vincular subunidade do usuário a um grupo</li>
    </ul>
</div>

<div class="card card-info">
	<div class="card-header">
		<h3 class="card-title">Vincular subunidade do usuário a um grupo</h3>		
	</div>	
	<form class="form-horizontal" name="table" id="form-cadunidade" method="post">
		<table class="card-body">
			<div class="msg" id="msg"></div>
			<tr>
				<td class="coluna1">Grupo </td>
			</tr>
			<tr>
				<td class="coluna2" colspan=2>
					<select class="custom-select" name="cad-consulta" id="cad-consulta">
						<?php foreach($grupos as $g){ ?>
							<option 
								<?php if($g["Codigo"] == $grupo) {
									echo "selected"; }?> value="<?php echo $g["Codigo"]; ?>">
								<?php echo $g["Grupo"];?>
							</option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="coluna1" style="text-align:left;"><br>Aplicações do grupo</td>
			</tr>
			<tr>
				<td class="coluna2">
					<div id="aplicgrupo" style="text-align:left;"></div><br>
				</td>
			</tr>
			<tr>
				<td class="coluna1">Unidades</td>
			</tr>
			<tr>
				<td class="coluna2"> 
					<div id="selectu"></div>
				</td>
				<td>
					<input type="button" id="add" value="Incluir" class="btn btn-info"/>
				</td>	
			</tr>
			<tr>
				<td class="coluna1"><br>Unidades Selecionadas</td>
			</tr>
			<tr>
				<td class="coluna2">
					<div  id="select2"></div>
				</td>
				<td>
					<input class="form-control"type='button' id='lis1' value='Excluir' class="btn btn-info"/>
				</td>	
			</tr>
		</table>
		<table class="card-body">
			<tr>
				<td align = "center" colspan=3>
					<br>
					<input type="button" id="remove" value="Limpar" class="btn btn-info"/> 
					<input class="form-control"name="operacao" type="hidden" readonly="readonly" value="I" />
					<input type="button" onclick='direciona();' value="Gravar" id="gravar" class="btn btn-info" />
				</td>
			</tr>
		</table>
	</form>
</div>
<?php $_SESSION['codunidade'] = null; ?>


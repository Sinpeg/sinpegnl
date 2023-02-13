<?php
require '../../classes/unidade.php';
require '../../dao/PDOConnectionFactory.php';
require '../../classes/sessao.php';
require '../../modulo/mapaestrategico/classes/Mapa.php';
require '../../modulo/mapaestrategico/dao/MapaDAO.php';
require '../../util/Utils.php';

session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];

$mapadao = new MapaDAO ();

$codunidade = ($sessao->getCodunidsel())?$sessao->getCodunidsel():$sessao->getCodUnidade();

extract($_POST);

//realiza filtro de mapa por documento ou por calendario.
$mapa = $mapadao->buscaMapaByUnidadeDocumentoOuCalendario( $codDocumento, $codunidade);

?>

<!--abriga mensagem de resposta quando o usuário tentar deletar pespectiva/objetivo-->
<div id="message"></div>

<!-- tabela gerada apartir do o bjeto mapa -->
<table>
	<tr>
		<th>Perspectiva</th>
		<th>Objetivo</th>
		<th>Deletar</th>
		<th>Editar</th>
        <th>Indicador</th>
	</tr>
	
	<?php
	$cont = 1;
	foreach ($mapa as $row){?>
	<tr>
		<td><?php echo $row['nomeperspectiva'];?></td>
		<td><?php echo $row['nomeobjetivo']; ?></td>
		<td>
			<button style="border: none" onclick="deletamapa(this)" id="<?php echo "btn{$cont}"; ?>" value="<?php echo $row['Codigo']; ?>"><img src ="webroot/img/delete.png"/></button>
		</td>
		<td>
			<form method="post" action="<?php echo Utils::createLink('mapaestrategico', 'editamapa'); ?>">
				<input type="hidden" name="codigo"value="<?php echo $row['Codigo']; ?>" /><input type='image' value="editar" src="webroot/img/editar.gif" />
			</form>
		</td>
        <td>
			<form method="post" action="<?php echo Utils::createLink('indicadorpdi', 'consultaindicador'); ?>">
				<input type="hidden" name="codigo" value="<?php echo $row['Codigo']; ?>" /><input type='image' value="editar" src="webroot/img/add.png" />
			</form>
		</td>
	</tr>
	<?php
	$cont++;
	}?>
</table>



<script>
function deletamapa(button){
		var confirmacao = confirm("você realmente deseja excluir esta linha?");
		if(confirmacao == true){
        $.ajax({url: "ajax/mapa/deletamapa.php", type: 'POST', data: { codmapa: button.value, action : "D"}, success: function(data) {
        	 $('div#message').html(data);
        	 var teste = "#"+button.id;
        	 teste = $(teste).parent().parent();
        	 teste.remove();
            }});
		}
}
</script>

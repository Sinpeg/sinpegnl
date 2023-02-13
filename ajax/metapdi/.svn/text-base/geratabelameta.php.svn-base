<?php
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/sessao.php';
require_once '../../classes/unidade.php';
require_once '../../dao/unidadeDAO.php';
require_once '../../modulo/documentopdi/dao/DocumentoDAO.php';
require_once '../../modulo/mapaestrategico/dao/MapaDAO.php';
require_once '../../modulo/indicadorpdi/dao/IndicadorDAO.php';
require_once '../../modulo/metapdi/dao/MetaDAO.php';
/* Model */
require_once '../../modulo/documentopdi/classe/Documento.php';
require_once '../../modulo/mapaestrategico/classes/Mapa.php';
require_once '../../modulo/indicadorpdi/classe/Indicador.php';
require_once '../../modulo/metapdi/classe/Meta.php';
require_once '../../util/Utils.php';
?>
<?php
session_start();
$sessao = $_SESSION['sessao'];
$codmapaindicador = $_SESSION['codmapaindicador'];
$codunidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();
?>
<?php
$metadao = new MetaDAO();

//realiza filtro da meta pelo mapaindicador.
$meta = $metadao->buscarmetaindicador($codmapaindicador, "9")

?>

<!--abriga mensagem de resposta quando o usuário tentar deletar pespectiva/objetivo-->
<div id="message"></div>

<!-- tabela gerada apartir do o bjeto meta -->
<table>
	<tr>
		<th>Meta</th>
		<th>Aano</th>
		<th>Deletar</th>
	</tr>
	
	<?php
	$cont = 1;
	foreach ($meta as $row):?>
	<tr>
		<td><?php echo $row['meta'] ?></td>
		<td><?php echo $row['ano'] ?></td>
		<td>
			<button style="border: none" onclick="deletameta(this)" id="<?php echo "btn{$cont}"; ?>" value="<?php echo $row['Codigo']; ?>"><img src ="webroot/img/delete.png"/></button>
		</td>
	</tr>
	<?php
	$cont++;
	endforeach;?>
</table>



<script>
function deletameta(button){
		var confirmacao = confirm("você realmente deseja excluir esta linha?");
		if(confirmacao == true){
        $.ajax({url: "ajax/metapdi/deletameta.php", type: 'POST', data: { codmeta: button.value, action : "D"}, success: function(data) {
        	 $('div#message').html(data);
        	 var teste = "#"+button.id;
        	 teste = $(teste).parent().parent();
        	 teste.remove();
            }});
		}
}
</script>
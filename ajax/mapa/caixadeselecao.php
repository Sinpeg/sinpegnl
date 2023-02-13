<?php
require '../../classes/unidade.php';
require '../../dao/PDOConnectionFactory.php';
require '../../classes/sessao.php';
require '../../modulo/mapaestrategico/classes/Mapa.php';
require '../../modulo/mapaestrategico/dao/MapaDAO.php';
require '../../modulo/documentopdi/classe/Documento.php';
require '../../modulo/mapaestrategico/classes/Objetivo.php';
require '../../modulo/mapaestrategico/dao/ObjetivoDAO.php';
require '../../modulo/documentopdi/classe/Perspectiva.php';
require '../../modulo/calendarioPdi/classes/Calendario.php';

session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];

$daomapa = new MapaDAO();
$daoobjetivo = new ObjetivoDAO();
extract($_POST);

$codDocumento;
$codPerspectiva;
$stmtobjetivo = $daoobjetivo->lista();

$stmtmapa = $daomapa->buscaMapaDocumentoPerspectiva($codDocumento, $codPerspectiva);
// echo "<pre><br>";
$i=0;
foreach ($stmtmapa as $row){
    $objetivo = $daoobjetivo->buscaobjetivo($row['codObjetivoPDI'])->fetch();
    $objetivo = $objetivo['Objetivo'];
    $objobjetivo = new Objetivo();
    $objmapa = new Mapa(); 
    $objdocumento = new Documento();
    $objdocumento->setCodigo($row['CodDocumento']);
// 	$arraycodobjetivosel[$i++] = $row['codObjetivoPDI'];
	$objobjetivo->setObjetivo($objetivo);
	$objobjetivo->setCodigo($row['codObjetivoPDI']);
	$objmapa->setObjetivoPDI($objobjetivo);
	$objmapa->setDocumento($objdocumento);
	$objmapa->setCodigo($row['Codigo']);
	$arramapa[$i++] = $objmapa;
}

//  echo "<pre><br>";var_dump($arramapa);die;
// $i=0;
// foreach ($stmtobjetivo as $row){
// 	$arraycodobjetivo[$i++] = $row;
// }

?>
<div id="message"></div>
<table class="table table-hover">

<thead>
        <tr>
          <th>Objetivos Selecionados:</th>
          <th></th>
        </tr>
</thead>
<?php $cont = 0; ?>
<tbody>
<?php foreach ($arramapa as $obmapa){ ?>
	<tr>
		
				<td>
					<?php echo "<div>{$obmapa->getObjetivoPDI()->getObjetivo()}</div>";?>
				</td>
				<td>
					<?php //echo "<input type='image' id='{$objetivo['Codigo']}' src='webroot/img/delete.png'>";?>
					<button style="border: none" onclick="deletamapa(this)" id="<?php echo "btn{$cont}"; $cont++;?>" value="<?php echo $obmapa->getCodigo(); ?>"><img src ="webroot/img/delete.png"/></button>
				</td>
		
	</tr>
<?php } ?>
</tbody>

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




      

<?php
require '../../classes/unidade.php';
require '../../dao/PDOConnectionFactory.php';
require '../../classes/sessao.php';
require '../../modulo/mapaestrategico/classes/Mapa.php';
require '../../modulo/mapaestrategico/dao/MapaDAO.php';
require '../../modulo/documentopdi/classe/Documento.php';
require '../../modulo/mapaestrategico/classes/Objetivo.php';
require '../../modulo/mapaestrategico/dao/ObjetivoDAO.php';
require '../../modulo/documentopdi/dao/PerspectivaDAO.php';
require '../../modulo/documentopdi/classe/Perspectiva.php';
require '../../modulo/calendarioPdi/classes/Calendario.php';
require '../../modulo/indicadorpdi/dao/MapaIndicadorDAO.php';
require '../../util/Utils.php';

session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];

$daomapa = new MapaDAO();
$daoobjetivo = new ObjetivoDAO();
$daopers=new PerspectivaDAO();
$codDocumento=$_POST['codDocumento'];
$codObjetivo=$_POST['codObjetivo'];
$stmtobjetivo = $daoobjetivo->lista();


$stmtmapa = $daomapa->buscamapadocumento($codDocumento,$sessao->getAnobase());
 //echo "<pre><br>";
$i=0;
foreach ($stmtmapa as $row){
    $pers = $daopers->buscaperspectiva($row['codPerspectiva'])->fetch();
    $persnome = $pers['nome'];
    $objpers=new Perspectiva();
    $objpers->setCodigo($row['codPerspectiva']);
    $objpers->setNome($persnome);
    
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
    $objmapa->setPerspectiva($objpers);
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




	<table id="tablesorter" class="table table-bordered table-hover" >
		
		
			<thead>
        <tr>
          <th>Perspectiva</th>
          <th>Objetivos</th>
          <th>Deletar</th>
          <th>Indicador</th>
          
        </tr>
</thead>
<?php $cont = 0; ?>
<tbody>
<?php foreach ($arramapa as $obmapa){ ?>
	<tr>
		        <td>
					<?php echo $obmapa->getPerspectiva()->getNome();?>
				</td>
				<td>
					<?php echo $obmapa->getObjetivoPDI()->getObjetivo();?>
				</td>
				<td>
<?php //echo "<input type='image' id='{$objetivo['Codigo']}' src='webroot/img/delete.png'>";
            $dao=new MapaIndicadorDAO();
            $rowsi=$dao->verSeMapaTemIndicador($obmapa->getCodigo(), $sessao->getAnobase());
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
                $img="webroot/img/delete.png";
                $evento='onclick="deletamapa(this);"';
                $img="webroot/img/delete2.png";
                $evento='onclick="deletamapa(this);"';
                $disabled = "";
                $ajuda = "title='Deletar Objetivo.' data-trigger='hover'";
            }
                    
                    
                    ?>
				 <button <?php echo $disabled;?>  <?php echo $evento;?> <?php echo $ajuda;?> id="<?php echo "btn{$cont}"; $cont++;
                            ?>" value="<?php echo $obmapa->getCodigo(); ?>"><img src ='<?php echo $img;?>' width="19" height="19"/></button>
			</td>
			<td>
				<form method="post" action="<?php echo Utils::createLink('indicadorpdi', 'consultaindicador'); ?>">
					<input type="hidden" name="codigo" value="<?php echo $obmapa->getCodigo(); ?>" /><input type='image' value="editar" src="webroot/img/add.png" />
				</form>
			</td>
	</tr>
	
<?php } ?>

    
</tbody>

</table>



<div id="dialog-confirm" title="Confirmação" style="display: none">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Você tem certeza que deseja eliminar o objetivo do seu painel tático?</p>
</div>


<script>

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




      
    

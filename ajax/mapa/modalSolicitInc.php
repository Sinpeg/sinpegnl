<?php
require '../../classes/unidade.php';
require '../../dao/PDOConnectionFactory.php';
require '../../classes/sessao.php';
require '../../modulo/mapaestrategico/classes/Mapa.php';
require '../../modulo/mapaestrategico/dao/MapaDAO.php';
require '../../modulo/documentopdi/classe/Documento.php';
require '../../modulo/documentopdi/dao/DocumentoDAO.php';
require '../../modulo/indicadorpdi/dao/IndicadorDAO.php';
require '../../modulo/mapaestrategico/classes/Solicitacao.php';
require '../../modulo/mapaestrategico/classes/SolicitacaoInsercaoObjetivo.php';
require '../../modulo/mapaestrategico/dao/SolicitacaoInsercaoObjetivoDAO.php';
require '../../modulo/mapaestrategico/classes/Objetivo.php';
require '../../modulo/mapaestrategico/dao/ObjetivoDAO.php';
require_once('../../util/Utils.php');


session_start();
$sessao = $_SESSION['sessao'];
if (!isset($_SESSION["sessao"])) {
    echo "Sua sessão expirou, faça login novamente!";
    exit();
}
$daodoc=new DocumentoDAO();
$daomapa=new MapaDAO();
$daoobj=new ObjetivoDAO();
$daoind=new IndicadorDAO();
$anobase=$sessao->getAnobase();
$daosol=new SolicitacaoInsercaoObjetivoDAO();
$sol=new SolicitacaoInsercaoObjetivo();
$codDocumento=$_POST['codDocumento'];
$rowsdoc=$daodoc->buscadocumento($codDocumento);
foreach ($rowsdoc as $d){
	$unidade=new Unidade();
	$unidade->setCodunidade($d['CodUnidade']);
	$unidade->criaDocumento($d['codigo'], $d['nome'], $d['anoinicial'], $d['anofinal'], null, null, null, null, null, null);
	$docobj=$unidade->getDocumento();
	 
}
//pega os objetivo do PDU
$rowsmapa=$daomapa->buscaMapaByUnidadeDocumentoOuCalendario1($codDocumento,$unidade->getCodunidade(),$anobase);
$arrayobjetivosDoPDU=array();
$cont=0;
foreach ($rowsmapa as $r){
	$o=new Objetivo();
	$o->setCodigo($r['codObjetivoPDI']);
	$arrayobjetivosDoPDU[$cont]=$o;
	$cont++;

}


?>


<table id="tabIncObjetivo">

	<tr>
		<td>Documento:</td>
		<td colspan="2"><input type='text' disabled class='form-control'
			value="<?php echo $docobj->getNome();?>" name='nomeDoc'> <input
			type='hidden' value="<?php echo $docobj->getCodigo();?>"
			name='coddoc' id='coddoc'>
		</td>
		
	</tr>
	<tr>
		<td>Objetivos:</td>
		<td colspan="2"><?php  $rowsobj = $daoobj-> buscaobjsPDI1($sessao->getAnobase(),$unidade->getCodunidade());?>
			<select name="codObjetivo"  class='form-control' id="codObjetivo" style="width: 400px">
				<option value="0">Selecione um objetivo...</option>
				<?php foreach ($rowsobj as $row) :
				$jatem=0;
				for ($i=0;$i<$cont;$i++) {
					if ($row["Codigo"]==$arrayobjetivosDoPDU[$i]->getCodigo()){
						$jatem=1;
						break;
					}
				}
				if ($jatem==0){
					$rowssol=$daosol->buscaSolicitacaoInsObjetivoUnidAno($anobase, $row["Codigo"], 3)->fetchall();
					if (count($rowssol) == 0) {   //nao tem solicitacao, exibe
						?>

				<option value=<?php print $row["Codigo"]; ?> style="width: 500px">
				<?php print $row['Objetivo'] ?>
				</option>
<?php
					
					}
				}//if !jatem

				endforeach; ?>
		</select></td>
	</tr>
<tr><td>Indicador:</td><td>
<?php 
	$rowsind = $daoind->listaIndicadorNaoVinculado1($sessao->getAnobase(),$docobj->getCodigo(),$sessao->getCodunidade());
?>
<select style="width: 400px" name="indicadortotal" id="indicadortotal"  class='form-control'    > <!--onchange="AddTableRow()"-->           
							<option value="0" >Selecione um indicador...</option>
			                <?php 	foreach ($rowsind as $row) : ?>
			                	<option value=<?php print($row["Codigo"]); ?>><?php print $row['nome'] ?></option>
			                <?php endforeach; ?>
</select>
</td>
<td    style="margin:0;"> 
<button  onclick="AddTableRow()" style="float:right" class="btn btn-primary" type="button" >Adicionar Indicador</button>
</td>

</tr>		



	<tr>
		<td colspan="3">




			<table id="tabaddindicador">
				<tbody>
					<tr>
						<th style="text-align: center">Indicador</th>
						
	            <?php for ($ano=$anobase;$ano<=$docobj->getAnofinal();$ano++){?>
						<th style="text-align: center">Meta - <?php echo $ano; ?> </th>
							<?php } ?>
						<th style="text-align: center">Métrica</th>
                        <th></th>
					</tr>

				</tbody>
				<tfoot>
					<tr>
						<td colspan="5" style="text-align: left;">
						  <!--   <button  class="btn btn-secondary" type="button">Adicionar Metas</button>-->


						</td>
					</tr>
				</tfoot>
			</table>
			<!--<a href="<?php echo Utils::createLink('indicadorpdi', 'incluiindicador1'); ?>" >Incluir novo indicador</a> -->
			
		</td>
	</tr>
	<tr>
		<td>Justificativa:</td>
		<td colspan="3"><textarea class="form-control" id="justificativa"
				name="justificativa" rows="9" cols="7"></textarea></td>
	</tr>
	<tr>
		<td>Anexo RAT:</td>
		<td colspan="3">
			<div id="teste">
				<input type="file" name="arquivo" accept=".rar,.zip" id="arquivo"
					onchange="verificaExtensao(this)" /> <input type="text" id="texto" />
				<input type="button" id="botao" value="Selecionar..."
					class="btn btn-primary" />
			</div>
		</td>
	</tr>
</table>


<style>
#teste {
	position: relative;
}

#arquivo {
	position: absolute;
	top: 0;
	left: 0;
	border: 1px solid #ff0000;
	opacity: 0.01;
	z-index: 1;
}
</style>
<script>


$(function(){
	  AddTableRow = function() {
		var  valor= $('#indicadortotal').children("option:selected").val();
		var  texto= $('#indicadortotal').children("option:selected").text();
		
		//alert($("#indicadortotal option:selected").text());
		
	    if (valor!=0){
	    	 var newRow = $("<tr>");
	 	    var cols = "";

	 	    cols += '<td>'+texto+'<input type="hidden" name="indsel[]"  value="'+valor+'"></td>';

            <?php for ($ano=$anobase;$ano<=$docobj->getAnofinal();$ano++){?>
	 	    cols += '<td><input type="text" maxlength=8 class="form_control" size=7 onKeyPress="tecla()" autocomplete="off" value=""  name="meta'+<?php echo $ano;?>+'[]"></td>';
	 	    <?php } ?>
	 	   
	 	    cols += '<td><select class="form-control" name="metrica[]" id="metrica"  style = "width:170px" >'+           
			'<option value="" >Selecione a métrica...</option>'+
            '<option value="P" >Percentual</option>'+
            '<option value="Q" >Quantitativo</option>'+
            '</select></td>';
	 	    
	 	    cols += '<td>';
	 	    cols += '<button class="btn btn-secondary" onclick="RemoveTableRow(this)" type="button">Remover</button>';
	 	    cols += '</td>';

	 	    newRow.append(cols);
	         $( '.classemeta' ).unbind();
	 	    
	 	    $("#tabaddindicador").append(newRow);
           
	 	    return false;

	    }
	  };
	});

RemoveTableRow = function(handler) {
	var tr = $(handler).closest('tr');

	tr.fadeOut(400, function(){
	tr.remove();
	});

	return false;
	};
	
$(function(){
    $('#arquivo').on('change',function(){
        var numArquivos = $(this).get(0).files.length;
       
	        $('#texto').val( $(this).val() );
      
    });
});




/*$("#cindicador").change(function() {
	var valor= $(this).children("option:selected").val();
	var texto= $(this).children("option:selected").text();
	var hasOption = $('#indicadoresel option[value="' + valor + '"]');
  if (hasOption.length>0){
     alert("Indicador já selecionado!");
  } else if (valor!="0"){	
	$('#indicadoresel').append(new Option(texto, valor, true, true));
  }
});

$("#btnExcluiOption").click(function() {
	var valor= $('#indicadoresel').children("option:selected").val();
	$("#indicadoresel option[value='"+valor+"']").remove();
	   
});*/


$('#btnEnviarSol1').click(function(event) {
	$("#msg1").css("display","none");
	$("#opsucesso").css("display","none");
	
    $("#msg1").html("");
   

	var codobj = $("#codObjetivo").val();

    if (codobj==0){
    	$("#msg1").css("display","");
		$("#msg1").html("Selecione um objetivo!");
		return;

    }

    var  valor= $('#indicadortotal').children("option:selected").val();

    if (valor==0){
    	$("#msg1").css("display","");
		$("#msg1").html("Selecione o(s) indicador(es) que deverá (ão) ser vinculado (s) ao objetivo!");
		return;
	 }

    var erro='1';

    $('input[type=text]').each(
    	    function(index){  
    	       // alert("fora  "+$(this).attr('name'));
    	        
    	        var entrada=$(this).attr('name');
    	        if (typeof entrada==="undefined"){ }

    	        else if ((entrada.substring(0,4)=='meta') && ($(this).val()=="")){
	        	        //alert("dentro "+entrada.substring(0,4)+"-"+$(this).val());
	        	        
	        	        erro=2;
    	          	    }
    	    }
    	        
    	);
	 if (erro==2){
		 $("#msg1").css("display","");
	     $("#msg1").html("Infome meta!");
	     return;
	 }
	 
    $('select').each(
    	    function(index){  
    	        var input = $(this);
    	        var entrada=input.attr('name');
    	        if ((entrada=='metrica[]') && (input.val()=="")){
    	        	erro='2';
    	        
    	    }
    	    }
    	);


    if (erro=='2'){
    	$("#msg1").css("display","");
	    $("#msg1").html("Infome uma métrica!");
	    return;
    }

   
    
     if (!$.trim($("#justificativa").val())){
        		$("#msg1").css("display","");
        	    $("#msg1").html("Por favor, insira uma justificativa!");
        	    return;
    }
     if ($("#arquivo").val() == ''){
        		$("#msg1").css("display","");
        	    $("#msg1").html("Por favor, selecione um arquivo!");
        	    return;
    } 	

    
	$("#msg1").empty();

	
  	/*  $.ajax({
        url: "ajax/mapa/registrarSolicitInc.php",
        type: "POST",
        data: $("form[name=form-criarSol1]").serialize(),
        success: function(data) {
        	$("#msg1").html(data);

        
        },
    });*/

    var file_data = $('#arquivo').prop('files')[0];  //arquivo 
    var formData = new FormData($("#form-criarSolicitacao1")[0]);//cria objeto form
    formData.append('file', file_data);//adiciona o arquivo ao objeto form
    
	$.ajax({
        url: "ajax/mapa/registrarSolicitInc.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
                alert(data);
        	$("#form1").css("display","none");
        	$("#form-criarSol2").css("display","none");
        	$("#opsucesso").css("display","");
            $("#btnEnviarSol1").css("display","none");    	
        
        },
    });
    
    
	
});




</script>




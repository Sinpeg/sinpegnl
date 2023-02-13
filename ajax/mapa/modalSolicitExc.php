<?php 

require '../../classes/unidade.php';
require '../../dao/PDOConnectionFactory.php';
require '../../classes/sessao.php';
require '../../modulo/mapaestrategico/classes/Mapa.php';
require '../../modulo/mapaestrategico/dao/MapaDAO.php';
require '../../modulo/documentopdi/classe/Documento.php';
require '../../modulo/documentopdi/dao/DocumentoDAO.php';
require '../../modulo/mapaestrategico/classes/Objetivo.php';
require '../../modulo/mapaestrategico/dao/ObjetivoDAO.php';
require '../../modulo/mapaestrategico/classes/Solicitacao.php';
require '../../modulo/mapaestrategico/classes/SolicitacaoEditObjetivo.php';
require '../../modulo/documentopdi/classe/Perspectiva.php';
require '../../modulo/mapaestrategico/dao/SolicitacaoEditObjetivoDAO.php';
require '../../modulo/indicadorpdi/dao/IndicadorDAO.php';

session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
$anobase=$sessao->getAnobase();
$daoind=new IndicadorDAO();
$daoobj=new ObjetivoDAO();
$daodoc=new DocumentoDAO();
$daomapa=new MapaDAO();
$daosol=new SolicitacaoEditObjetivoDAO();
$sol=new SolicitacaoEditObjetivo();
$codmapa=$_POST['mapa'];

$doccorreto=$_POST['doccorreto'];
$rowsdoc=$daodoc->buscadocumento($doccorreto);
//Pegar o documento separado do mapa porque o mapa pode pertencer ao PDI
foreach ($rowsdoc as $d){
		$unidade=new Unidade();
        $unidade->setCodunidade($d['CodUnidade']);
	    $unidade->criaDocumento($d['codigo'], $d['nome'], null, null, null, null, null, null, null);
	    $docobj=$unidade->getDocumento();
}
$rowsmapa=$daomapa->buscaDadosMapaPorCodigo($codmapa);
foreach ($rowsmapa as $d){	    
	    $objetivo=new Objetivo();
	    $objetivo->setCodigo($d['codObjetivoPDI']);
	    $objetivo->setObjetivo($d['Objetivo']);
	    $pers=new Perspectiva();
	    $docobj->criaMapa($codmapa, $pers, $objetivo, NULL, NULL,NULL);
	    
}

$contlinhas=0;
$solexcobj=NULL;
$rowsol=$daosol->buscaSolicitacaoEditObjetivoAno($codmapa, $anobase, 5);
$passou='0';
foreach ($rowsol as $r){
	      $passou='1';
}	
	
if ($passou=='1'){?>
<div id="SolExiste"> Este objetivo já possui uma solicitação. Verifique no Quadro de Solicitações... </div>
		
<?php
}else{
?>

 <table><tr><td>Documento:</td><td>
  <input type='text' disabled class='form-control' value="<?php echo $docobj->getNome();?>" name='nomeDoc'>
 <input type='hidden'  class='form-control' value="<?php echo $doccorreto;?>" name='coddoc'></td></tr>
 <tr><td>Objetivo:</td><td style="padding-left=10%;"><?php echo $objetivo->getObjetivo(); ?>
   <input type='hidden'  class='form-control' value="<?php echo $objetivo->getCodigo();?>" name='codObjetivo'></td></tr>    
	        
		     
<tr><td>Justificativa:</td><td><textarea class="form-control" id="justificativa" name="justificativa"></textarea></td></tr>
 <tr><td>Anexo RAT:</td><td>
    <div id="teste">
    <input type="file"  name="arquivo" accept=".rar,.zip"   id="arquivo" onchange="verificaExtensao(this)" />
    <input type="hidden" name="MAX_FILE_SIZE" value="10485760">
    <input type="text" id="texto" />
    <input type="button" id="botao" value="Selecionar..." class="btn btn-primary"/>
</div>
	</td></tr>
</table>

<?php } ?>
<script>

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


$(function(){
    $('#arquivo').on('change',function(){
        var numArquivos = $(this).get(0).files.length;
       
	        $('#texto').val( $(this).val() );
      
    });
});


$('#btnEnviarSol2').click(function(event) {
	$("#msg2").css("display","none");
	$("#confirmacaoSol").css("display","none");
	
    if(!$.trim($("#justificativa").val())){
        		$("#msg2").css("display","");
        	    $("#msg2").html("Insira uma justificativa!");
        	    return;
    }
     if($("#arquivo").val() == ''){
        		$("#msg2").css("display","");
        	    $("#msg2").html("Informe um arquivo!");
        	    return;
    } 	

    
	$("#msg2").empty();

	
 

    var file_data = $('#arquivo').prop('files')[0];  //arquivo 
    var formData = new FormData($("#form-criarSolicitacao2")[0]);//cria objeto form
    formData.append('file', file_data);//adiciona o arquivo ao objeto form
    
	$.ajax({
        url: "ajax/mapa/registrarSolicitExc.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
           //alert(data);
        	//$("#msg1").html(data);
        	$("#confirmacaoSol").css("display","");
        	$("#formEO").css("display","none");
        	$("#btnEnviarSol2").css("display","none");
            
        },
    });
    
    
	
});




</script>
<style>
#teste { position:relative; }
#arquivo { position:absolute; top:0;left:0; border:1px solid #ff0000; opacity:0.01; z-index:1; }
</style>
 	
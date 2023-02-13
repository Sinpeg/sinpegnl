<?php 
require '../../classes/unidade.php';
require '../../dao/PDOConnectionFactory.php';
require '../../classes/sessao.php';
require '../../classes/usuario.php';
require '../../modulo/mapaestrategico/classes/Mapa.php';
require '../../modulo/mapaestrategico/dao/MapaDAO.php';
require '../../modulo/documentopdi/classe/Documento.php';
require '../../modulo/documentopdi/dao/DocumentoDAO.php';
require '../../modulo/mapaestrategico/classes/Solicitacao.php';
require '../../modulo/indicadorpdi/dao/IndicadorDAO.php';
require '../../modulo/indicadorpdi/classe/Indicador.php';
require '../../modulo/indicadorpdi/dao/MapaIndicadorDAO.php';
require '../../modulo/indicadorpdi/classe/Mapaindicador.php';
require '../../modulo/metapdi/dao/MetaDAO.php';
require '../../modulo/metapdi/classe/Meta.php';
require '../../modulo/metapdi/dao/SolicitacaoRepactuacaoDAO.php';
require '../../modulo/metapdi/classe/SolicitacaoRepactuacao.php';
session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
$daoind=new IndicadorDAO();
$daomi=new MapaIndicadorDAO();
$daodoc=new DocumentoDAO();
$daometa=new MetaDAO();
$anobase=$sessao->getAnobase();
$daosol=new SolicitacaoRepactuacaoDAO();
$sol=new SolicitacaoRepactuacao();
$codmeta=$_POST['meta'];
$doccorreto=$_POST['doc'];
$rowsdoc=$daodoc->buscadocumento($doccorreto);
//Pegar o documento separado do mapa porque o mapa pode pertencer ao PDI
foreach ($rowsdoc as $d){
		$unidade=new Unidade();
        $unidade->setCodunidade($d['CodUnidade']);
	    $unidade->criaDocumento($d['codigo'], $d['nome'], null, null, null, null, null, null, null);
	    $docobj=$unidade->getDocumento();
}
$rowsmeta=$daometa->buscarmeta($codmeta);
foreach ($rowsmeta as $m){	    
	    $meta=new Meta();
	    $meta->setCodigo($m['Codigo']);
	    $meta->setMetrica($m['metrica']);
	    $meta->setMeta($m['meta']);
	    $meta->setAno($m['ano']);
	    $mapind=new Mapaindicador();
	    $mapind->setCodigo($m['CodMapaInd']);	 
	    $meta->setMapaindicador($mapind);   
}
$rowsmapaind=$daomi->buscaMapaIndicador($mapind->getCodigo());
foreach ($rowsmapaind as $i){
	$ind = new Indicador();
	$ind->setCodigo(null);
	$ind->setNome($i['nome']);
	$mapind->setIndicador($ind);
}
$u=new Usuario();
$u->setCodusuario(228);


$contlinhas=0;
$sol=NULL;
$passou=0;
$rowssol=$daosol->buscaSolicitacaoRepactuacao($anobase,$meta->getCodigo(),4);//situacao diferente de concluída
foreach ($rowssol as $r){
	      $meta->criaSolicitacaoRepactuacao($r['codigo'], $unidade, NULL, NULL, NULL, NULL, NULL, NULL, $u,NULL);
	      $sol= $meta->getSolicitacaorepactuacao();
	      $passou=1; 
	}	
	
	

$metaobj=new Meta();
$metaobj->setCodigo($codmeta);
if ($passou==0){
?><table><tr><td>Documento:</td><td>
 
 <input type='text' disabled class='form-control' value="<?php echo $docobj->getNome();?>" name='nomeDoc'></td></tr>
  <tr><td>Indicador:</td><td><?php  echo $ind->getNome(); ?>
  <input type="hidden" name="codmeta" value=<?php echo $meta->getCodigo();?>>
    <input type="hidden" name="coddoc" value=<?php echo $docobj->getCodigo();?>>
  
  </td></tr>
  <tr><td>Ano da meta:</td><td style="padding-left=10%;">
        	 
        	 	     <?php echo $meta->getAno(); ?>

              </td></tr>
        <tr><td>Meta atual:</td><td style="padding-left=10%;">
        	 
        	 	     <?php echo str_replace('.', ',',$meta->getMeta()); ?>

              </td></tr>
	        <tr><td>Nova Meta:</td><td><input type="text" id="novameta" onKeyPress="tecla()"  data-mask="99999999,99" data-mask-reverse="true" value="" name="novameta"/></td></tr>
	        
		     
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

<?php }else{?>
<div id="SolExiste"> Este meta já possui uma solicitação de repactuação. Verifique no Quadro de Solicitações... </div>
	
<?php }?>
<script>


$(function(){
    $('#arquivo').on('change',function(){
        var numArquivos = $(this).get(0).files.length;
       
	        $('#texto').val( $(this).val() );
      
    });
});


$('#btnEnviarSol1').click(function(event) {
	$("#msg1").css("display","none");
	$("#opsucesso").css("display","none");
//alert("Entrou"+$("#novameta").val());
	if(!$.trim($("#novameta").val())){
  		$("#msg1").css("display","");
  	    $("#msg1").html("Por favor, informe a nova meta!");
  	    return;
		}

	  if(!$.trim($("#justificativa").val())){
  		$("#msg1").css("display","");
  	    $("#msg1").html("Por favor, informe a justificativa!");
  	    return;
		}
		if($("#arquivo").val() == ''){
		  		$("#msg1").css("display","");
		  	    $("#msg1").html("Por favor, informe o arquivo da ata da RAT!");
		  	    return;
		} 	
		
		
		$("#msg1").empty();



	
	 var file_data = $('#arquivo').prop('files')[0];  //arquivo 
	 var formData = new FormData($("#form-criarSolicitacao")[0]);//cria objeto form
	 formData.append('file', file_data);//adiciona o arquivo ao objeto form

	$.ajax({
        url: "ajax/metapdi/registrarSolicitRep.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
       // data: $("form[name=form-criarSol]").serialize(),
        success: function(data) {
        	//$("#msg2").html(data);
        	$("#form1").css("display","none");
        	
        	$("#opsucesso").css("display","");


        	$("#btnEnviarSol1").css("display","none");
        	
        },
    });
		
	
});







</script>
<style>
#teste { position:relative; }
#arquivo { position:absolute; top:0;left:0; border:1px solid #ff0000; opacity:0.01; z-index:1; }
</style>
 	
<?php
include 'funcoesraa.php';
$mdao= new ModeloDAO();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
$anobase=$sessao->getAnobase();
/*
if (!$aplicacoes[1]) {
    header("Location:index.php");
}  else {
*/
    $nomeunidade = $sessao->getNomeunidade();
    $codunidade = $sessao->getCodunidade();
    $anobase = $sessao->getAnobase();
    $lista = array();
    $cont = 0;
    $dao = new TextoDAO();   
    $rowsl = $dao->buscaTopicosPreenchidos_Pendentes($anobase, $codunidade);
   	$topico=array();    
    $topico=incluirTopicoNoTexto($rowsl,$topico,$cont,$codunidade,$anobase);
    $rowsl = $dao->buscaSubTopicosPreenchidos_Pendentes($anobase, $codunidade);
    $topico=incluirTopicoNoTexto($rowsl,$topico,$cont,$codunidade,$anobase);

//}//aplicacoes

    //Verificar se o relatório foi finalizado
    $disabled = "";
    $frows = $dao->buscaFinalizacaoRel($codunidade, $anobase);
    foreach ($frows as $row){
    	$disabled = "disabled";
    }   
    
    
    
?>
<style>
/* The Modal (background) */
#modalLoading {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}


.loader {
  left:50%;
  position:absolute;
  top:40%;
  left:45%;	
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 100px;
  height: 100px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}
<!--
.modal {
  text-align: center;
  padding: 0!important;
  
}

.modal:before {
  content: '';
  display: inline-block;
  height: 100%;
  vertical-align: middle;
  margin-right: -4px;
}

.modal-dialog {
  display: inline-block;
  text-align: left;
  vertical-align: middle;
  width:55%;
}
-->


/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

<head>

	<div class="bs-example">
		<ul class="breadcrumb">
			<a href="#">Redigir texto RAA</a>
		</ul>
	</div>
</head>

<legend>Redigir RAA</legend><br>
<div id="accordion">	    
<?php 	  
    $cont=0;
    $nivel=0;
	if (count($topico)>0)  {
	  $anterior=NULL; 
	  foreach ($topico as $t){ 
      
      ?>
	  
	  
   <h3 <?php print "onclick=carregaModelo(".$t->getCodigo().")"; ?> >
<?php 
   if($t->getNivel() != NULL){
      $nivel++;
      print $nivel.". ".$t->getNome();
    }else{
      print "".$t->getNome();
    }

    //print $t->getNivel().$t->getNome(); ?>

</h3>
<div  id="ac<?php print $t->getCodigo();?>"></div>	      

<?php 
	  	 	if ($t->getSubtopicos()!=NULL){
		  	   $cont=subtopico($t->getSubtopicos(),$codunidade,$anobase,$nivel);
	  	  	}
	  	  	
	   }
	}else{?>
		  <div class="erro">
        <img src="webroot/img/error.png" width="30" height="30"/>
        <?php print "É necessário definir tópicos para o Relatório Anual de Atividades de ".$anobase."!"; ?>
    </div>
<?php 	}  ?>
</div>
	<br>
<div>	
<div  style ="display:inline-block;">
<form class="form-horizontal" method="post" action="<?php echo Utils::createLink('raa', 'consultarTopicos');?>" name="frm" >
	<input class="btn btn-info" type="submit"  name="novo1" value="Novo Tópico..."  id="topico" class="btn btn-info btn">
</form>
</div>
<div style ="display:inline-block;">
	
	<a href="modulo/raa/relatorioRaaPDF.php"><input type="button" name="novo2" value="Gerar RAA"  id="raa" class="btn btn-info "></a>	
    <a href="<?php echo Utils::createLink("uparquivo", "consultaarqs"); ?>"><input type="button" value="Enviar Anexo"  id="raa" class="btn btn-info"></a>
       
    <input type="button" name="btnModal" <?php echo $disabled;?> value="Finalizar Relatório" class="btn btn-info" data-toggle="modal" data-target="#confirmaFinal">
</div>
</div>



<!-- Modal Loading-->
<div class="" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="modalLoading" aria-hidden="true">  
    <div class="loader"></div> 
</div>



<!-- Modal Confirmar Finalização do relatório-->
<div class="modal fade modalFim" id="confirmaFinal" tabindex="2" role="dialog" aria-labelledby="confirmaFinal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Confirmar Finalização </h4>
        </div>
      <div class="modal-body">         
        <form class="form-horizontal" name="form-finalizacao" id="form-finalizacao" method="POST"  >
		    <fieldset>		       
		          <p>Deseja realmente finalizar a elaboração do Relatório de Atividades ? Após confirmação não será possível a edição.</p>
		   </fieldset>
		   <input class="form-control"type="hidden" name="codUnidade" value=" <?php echo $codunidade; ?> ">
		   <input class="form-control"type="hidden" name="ano" value=" <?php echo $anobase; ?> ">		   
		</form>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
        <button type="button" id="btnConfirmaFim" class="btn btn-info">Sim</button>
      </div>
    </div>
  </div>
</div>



<script>
 $( function() {
	    $( "#accordion" ).accordion({
            collapsible: true,
            active: false,
            heightStyle: "content"
        });
	  } );

 function carregaModelo(codTopico){
	//alert(codTopico);
	$("#modalLoading").css("display","block");
	
	var area = "#area"+codTopico;
	var codtexto = "codtexto";

	//alert(area);
	$.ajax({
	     url:"ajax/raa/retornaModelo.php",
	      type: 'POST',
	      data: {codtexto:codtexto,codTopico:codTopico},
	      success: function(data) {
				//alert(data);
				$("#modalLoading").css("display","none")
	           $("#ac"+codTopico).html(data);
	      }
	 });	
}

</script>


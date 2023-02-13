<input class="form-control"type="hidden" id="tipoSolicitacaoB" value="" ><!-- input class="form-control"para armazenar o tipo de solicitação na busca dos dados-->
<input class="form-control"type="hidden" id="codUsuario" value="<?php echo $sessao->getCodusuario();?>" ><!-- input class="form-control"para armazenar o tipo de solicitação na busca dos dados-->


<!-- Modal Loading-->
<div class="" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="modalLoading" aria-hidden="true">  
    <div class="loader"></div> 
</div>

<!-- Modal Analisar Solicitação-->
<div class="modal fade" id="incluirObjetivo" tabindex="-1" role="dialog" aria-labelledby="incluirObjetivo" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Solicitação de Inserção de Novo Objetivo</h4>
        </div>
      <div class="modal-body">
        <div id="form1">
        <form class="form-horizontal" name="form-criarSol1" id="form-criarSolicitacao1" method="POST" enctype='multipart/form-data'  >
		    <fieldset>	
		          <div id="msg1"></div>
		          <div id="resposta111"></div>
			</fieldset>	   		 		   
		
		</form>
		</div>
		<table id="table_delegarIO" style="display:none;">
		<tr ><td>Delegar para:</td><td><select id="usuarioDelegadoIO" class="form-control">
		       								<option value="">Selecione unidade...</option>
		       								<option value="52">DINFI</option>
		       								<option value="159">DIAVI</option>
		       								</select></td></tr>
		</table>
		<div id="loading" style="display: none;">  
		    <div class="loader"></div> 
		</div>
      </div>
      <div  class="alert alert-danger" role="alert" id="alert" style="display:none;"></div>
                 
       <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button"  id="btn_enviarIO"   class="btn btn-info">Enviar Comentário</button>
    <?php 
    
    if ($c->identificarAnalistas($sessao->getCodusuario())) {?>
        	    <button type="button"  id="btn_delegarIO"   class="btn btn-info" >Delegar</button>
        	     <button type="button"   id="btn_gravarDelegarIO"  class="btn btn-info">Gravar</button>
        	     
	    <?php }?>
        
          <button type="button" style="display: none;"  id="btn_gravarSolDadosIO"  class="btn btn-info">Gravar</button>
          
          
      </div>
    </div>
  </div>
</div>



<!-- Modal Analisar Solicitação-->

<div class="modal fade" id="repactuarMeta" tabindex="-1" role="dialog" aria-labelledby="repactuarMeta" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Solicitação de Repactuação de Meta</h4>
        </div>
      <div class="modal-body">
        <div id="form1">
        <form class="form-horizontal" name="form-criarSol2" id="form-criarSolicitacao2" method="POST" enctype='multipart/form-data'  >
		    <fieldset>	
		          <div id="msg2"></div>
		    	       
        <table>
 <tr><td>Documento:</td><td><input class="form-control"type='text' disabled class='form-control' value="" name="documentorm" id="documentorm">
 <input class="form-control"type='hidden'  value="" name='coddoc' id='coddoc'>
 <input class="form-control"type="hidden" id="tipoSolicitacaoB" value="" name="tipoSolicitacaoB">
 </td></tr>
<tr><td>Indicador da meta:</td><td style="padding-left=10%;"><input class="form-control"type='text' disabled class='form-control' value="" name='nomeindicadorrm' id='nomeindicadorrm'></td></tr>
         
 <tr><td>Ano da meta:</td><td style="padding-left=10%;"><input class="form-control"type='text' disabled class='form-control' value="" name='anorm' id='anorm'></td></tr>
 <tr><td>Meta:</td><td style="padding-left=10%;"><input class="form-control"type='text' disabled class='form-control' value="" name='metarm' id='metarm'></td></tr>
 
<tr><td>Nova meta:</td><td>
<input class="form-control"type='text' disabled class='form-control' value="" name='novameta' id='novameta'></td></tr>
		     
<tr><td>Justificativa:</td><td><textarea disabled  class="form-control" id="justificativarm" name="justificativarm" rows="3" cols="10"></textarea></td></tr>
<tr><td>Anexo RAT:</td><td>
   <a href="" id="arquivorm"><img width="30" src="webroot/img/download.gif"/></a>
</td></tr>
     
		    
   <?php   if ($c->identificarAnalistas($sessao->getCodusuario())) {?>
              		        
<tr id="tr_analisa11"><td>Situação:</td><td><select class="custom-select" id="situacaoRM" name="situacaoRM">
		        		                          <option value="">Selecione a situação...</option>

		        		                          <option value="A">Aberta</option>
		                                          <option value="D">Deferida</option>
		        								  <option value="I">Indeferida</option></select></td></tr>
		 <?php }else{?>
<tr id="tr_cancela11"><td>Situação:</td><td><select class="custom-select" id="situacaoRM" name="situacaoRM">
<option value="">O usuário pode cancelar a solicitação</option>
<option value="A">Aberta</option>
<option value="C">Cancelada</option></select></td></tr>
<?php } ?>
<tr id="tr_deferido11" style="display: none;"><td>Situação Atual:</td><td><input class="form-control"name="sitfinal" disabled class="form-control" id="situacaoF" type="text"  value=""></td></tr>
		        								  
<tr><td>Comentários:</td><td><div id="iframeRM" style="overflow: auto;height: 130px; border:solid 1px;border-color: #E6E6E6;"></div></td></tr>
<tr id="tr_comentario1"><td>Comentar:</td><td><textarea class="form-control" id="comentarioRM"> </textarea></td></tr>			        								  
		       
   		       		
</table>

    	          
		   </fieldset>	   		 		   
		</form>
		</div>
		<table id="table_delegarRM" style="display:none;">
		<tr ><td>Delegar para:</td><td><select id="usuarioDelegado2" class="form-control">
		       								<option value="">Selecione unidade...</option>
		       								<option value="52">DINFI</option>
		       								<option value="159">DIAVI</option>
		       								</select></td></tr>
		</table>
		<div id="loading" style="display: none;">  
		    <div class="loader"></div> 
		</div>
      </div>
      <div  class="alert alert-danger" role="alert" id="alert" style="display:none;"></div>
      <div class="modal-footer">

          
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      	<button type="button" style="display: none;" id="btn_enviarRM"  class="btn btn-info">Enviar Comentário</button>  

    <?php if ($c->identificarAnalistas($sessao->getCodusuario())) {?>
    
	    <button type="button"  id="btn_delegarRM"  class="btn btn-info" >Delegar</button>
	     <button type="button" style="display: none;"  id="btn_gravarDelegarRM"  class="btn btn-info">Gravar</button>
	    
        <?php } ?>  
          <button type="button" style="display: none;"  id="btn_gravarSolDadosR"  class="btn btn-info">Gravar</button>
        
      </div>
    </div>
  </div>
</div>





<!-- Modal Analisar Solicitação-->
<div class="modal fade" id="excluirObjetivo" tabindex="-1" role="dialog" aria-labelledby="excluirObjetivo" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Solicitação de Exclusão de Objetivo</h4>
        </div>
      <div class="modal-body">
        <div id="form1">
        <form class="form-horizontal" name="form-criarSol1" id="form-criarSolicitacao3" method="POST" enctype='multipart/form-data'  >
		    <fieldset>	
		          <div id="msg3"></div>
		    	       
        <table>
 <tr><td>Documento:</td><td><input class="form-control"type='text' disabled class='form-control' value="" name="documentoE" id="documentoE">
 <input class="form-control"type='hidden'  value="" name='coddoc' id='coddoc'>
 </td></tr>
 <tr><td>Objetivo:</td><td style="padding-left=10%;"><input class="form-control"type='text' disabled class='form-control' value="" name='nomeObjE' id='nomeObjE'></td></tr>
 
<tr><td>Indicadores vinculados:</td><td><textarea disabled  class="form-control" id="indicadoresobj" name="indicadoresobj" rows="9" cols="10"></textarea></td></tr>
		     
<tr><td>Justificativa:</td><td><textarea disabled  class="form-control" id="justificativaE" name="justificativaE" rows="3" cols="10"></textarea></td></tr>
<tr><td>Anexo RAT:</td><td>
   <a href="" id="arquivoDadosE1"><img width="30" src="webroot/img/download.gif"/></a>
</td></tr>
     
   <?php   if ($c->identificarAnalistas($sessao->getCodusuario())) {?>
		    
            
  <tr id="tr_analisa21" ><td>Situação:</td><td>
  
  <select class="custom-select" id="situacaoE" name="situacaoE">
  		        		                          <option value="">Selecione a situação...</option>
		        		                          <option value="A">Aberta</option>
		                                          <option value="D">Deferido</option>
		        								  <option value="I">Indeferido</option></select></td></tr>
		 <?php } else {?>       								  
<tr id="tr_cancela21" ><td>Situação:</td><td>
<select class="custom-select" id="situacaoE" name="situacaoE" >
<option value="">O usuário pode cancelar a solicitação...</option>
<option value="A">Aberta</option>
<option value="C">Cancelada</option></select></td></tr>
<?php } ?>
<tr id="tr_deferido21" >
<td>Situação Atual:</td><td><input class="form-control"name="sitfinalE" class="form-control" id="sitfinalE" type="text" disabled></td></tr>
		        								  
<tr><td>Comentários:</td><td><div id="iframeEO" style="overflow: auto;height: 130px; border:solid 1px;border-color: #E6E6E6;"></div></td></tr>
<tr id="tr_comentario1"><td>Comentar:</td><td><textarea class="form-control" id="comentarioEO"> </textarea></td></tr>			        								  
</table>

                
                
                
               
		          
		   </fieldset>	   		 		   
		</form>
		</div>
		<table id="table_delegarEO" style="display:none;">
		<tr ><td>Delegar para:</td><td><select id="usuarioDelegado3" class="form-control">
		       								<option value="">Selecione unidade...</option>
		       								<option value="52">DINFI</option>
		       								<option value="159">DIAVI</option>
		       								</select></td></tr>
		</table>
		<div id="loading" style="display: none;">  
		    <div class="loader"></div> 
		</div>
      </div>
      <div  class="alert alert-danger" role="alert" id="alert" style="display:none;"></div>
      <div class="modal-footer">

          
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" style="display: none;" id="btn_enviarEO"  class="btn btn-info">Enviar Comentário</button>      	
        
    <?php if ($c->identificarAnalistas($sessao->getCodusuario())) {
    ?>
        	    <button type="button"  id="btn_delegarEO"  class="btn btn-info" >Delegar</button>
                 <button type="button"   id="btn_gravarDelegarEO" style="display: none;" class="btn btn-info">Gravar</button>
        
      	<?php }?>
          
         <button type="button"   id="btn_gravarSolDadosEO" style="display: none;" class="btn btn-info">Gravar</button>
          
   
          
          
          
          
          
      </div>
    </div>
  </div>
</div>



<style>

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
}
.modal-content{
	width:700px;
}

/* The Modal (background) */
#modalLoading {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    /*z-index: 4; /* Sit on top */
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
<script>
/*$("#cindicador1").change(function() {
	$("#resultadoind").empty();
    $.ajax({
        url: "ajax/mapa/indicadoresselect.php",
        type: "POST",
        data: $("#form-criarSol").serialize(),
        success: function(data) {
            $("#resultadoind").html(data);
        },
    });
});

$("#selectOp1").change(function() {
	var valor= $(this).children("option:selected").val();
	
	$("#solobjetivo").empty();
    $.ajax({
        url: "ajax/mapa/modalSolicit.php",
        type: "POST",
        data: $("form[name=form-criarSol1]").serialize(),
        success: function(data) {
        	$("#solobjetivo").html(data);
        },
    });
});*/

</script>




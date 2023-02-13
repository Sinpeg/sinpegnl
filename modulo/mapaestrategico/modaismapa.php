<!-- Modal Loading-->
<div class="" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="modalLoading" aria-hidden="true">  
    <div class="loader"></div> 
</div>

<!-- Modal Inserir Solicitação-->
<div class="modal fade" id="cadastrarSol1" tabindex="-1" role="dialog" aria-labelledby="cadastrarSol1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Cadastrar Solicitação para Inserir Novo Objetivo</h4>
        </div>
      <div class="modal-body">
        <div id="form1">
        <form class="form-horizontal" name="form-criarSol1" id="form-criarSolicitacao1" method="POST" enctype='multipart/form-data'  >
		    <fieldset>	
	<div id="msg1" class="alert alert-danger" role="alert" style="display:none;">
      </div>
		     <div id="tabIncObj"></div>	       
       
		          
		   </fieldset>	   		 		   
		</form>
		</div>
			<div id="opsucesso" style="display: none;">
			<p>
		    <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
		   Cadastro realizado com sucesso. Sua solicitação foi enviada para análise.
		  </p>
		</div>
		<div id="loading" style="display: none;">  
		    <div class="loader"></div> 
		</div>
      </div>
      <div  class="alert alert-danger" role="alert" id="alert" style="display:none;"></div>
      <div class="modal-footer">

        <button type="button" name="btnfechar" class="btn btn-secondary" onclick="btnFechar();" data-dismiss="modal">Fechar</button>
        <button type="button" name="btnEnviarSol1" id="btnEnviarSol1" class="btn btn-info">Solicitar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal Excluir Solicitação-->
<div class="modal fade" id="cadastrarSol2" tabindex="-1" role="dialog" aria-labelledby="cadastrarSol2" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Cadastrar Solicitação para Excluir de Objetivo</h4>
        </div>
      <div class="modal-body">
     
     <div id="msg2" class="alert alert-danger" role="alert" style="display:none;">
      </div>
        <div id="formEO">
        <form class="form-horizontal" name="form-criarSol2" id="form-criarSolicitacao2" method="POST" enctype='multipart/form-data'  >
		    <fieldset>		       
		        <div id="tabExcObj"></div>	       
		           
		   </fieldset>	   		 		   
		</form>
		</div>
			<div id="confirmacaoSol" style="display: none;">
			<p>
		    <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
		   Cadastro realizado com sucesso. Sua solicitação foi enviada para análise.
		  </p>
		</div>
		<div id="possuiSolicitacao1" style="display: none;">
			<p>
		    <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
		    Este Objetivo já possui uma solicitação enviada.
		  </p>
		</div>
		<div id="loading" style="display: none;">  
		    <div class="loader"></div> 
		</div>
      </div>
      <div  class="alert alert-danger" role="alert" id="alert" style="display:none;"></div>
      <div class="modal-footer">

        <button type="button" id="btnFecharIO" class="btn btn-secondary" onclick="btnFechar();" data-dismiss="modal">Fechar</button>
        <button type="button" id="btnEnviarSol2"  class="btn btn-info">Solicitar</button>
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
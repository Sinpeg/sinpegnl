<!-- Modal Loading-->
<div class="" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="modalLoading" aria-hidden="true">  
    <div class="loader"></div> 
</div>

<!-- Modal Enviar Solicitação de Validação-->
<div class="modal fade" id="enviarSoliValidacao" tabindex="-1" role="dialog" aria-labelledby="enviarSoliValidacao" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Enviar Solicitação de Validação do Painel Tático</h4>
        </div>
      <div class="modal-body">
      	<div id="form-validar">
		<form class="form-horizontal" name="form-validarPT" id="form-validarPT" method="POST"  >		            
		      <div id="msg-alert" style="display: none;"></div>   
		    	<table>
		    	<tr>
		    	<td>Nome</td><td><input class="form-control"type="text" name="nome_responsavel" id="nome_responsavel" class="form-control"> </td>
		    	</tr>
		    	<tr><td>Telefone</td><td><input class="form-control"type="text" data-mask="(00) 0000-0000" name="telefone_responsavel" id="telefone_responsavel" class="form-control"></td></tr>
		    	<tr><td>E-mail</td><td><input class="form-control"type="email"  name="email_responsavel" id="email_responsavel" class="form-control"></td></tr>  
		    	<tr><td>Comentário</td><td><textarea  name="comentario" id="comentario" class="form-control"></textarea></td></tr>    	
		    	</table>
		    	<input class="form-control"type="hidden" name="unidade" value="<?php echo $codunidade;?>" />
		    	<input class="form-control"type="hidden" name="autor" value="<?php echo $sessao->getCodusuario();?>" />	    
		</form>
		</div>      
     <div id="msg2" class="alert alert-danger" role="alert" style="display:none;">
      </div>
       
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="btnFechar();" data-dismiss="modal">Fechar</button>
        <button type="button" id="btn_salvar_plano" onclick="enviarValidacaoPT()"  class="btn btn-info">Solicitar</button>
      </div>
    </div>
  </div>
</div>
</div>

<!-- Modal registrar Plano de Ação-->
<div class="modal fade" id="enviarPlanoAcao" tabindex="-1" role="dialog" aria-labelledby="enviarSoliValidacao" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      	<div class="modal-header">
          <h4 class="modal-title">Registrar Plano de Ação</h4>
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
      <div class="modal-body">
      <div id="msg-alertPlano" style="display:none;"></div>
      	<div id="form-plano">
			<form class="form-horizontal" enctype="multipart/form-data" name="adicionarPlano" id="adicionarPlano" method="POST"  >
				<?php
				if($temPlano == 1){
					print '<div class="alert alert-danger" role="alert">A Unidade possui um Plano de Ação cadastrado para o ano base.<br/><br/><b>Comentário: </b>'.$comentarioPlano.'<br/><b>Download:</b> <a href="../public/plano_de_acao/'.$arquivoPlano.'"><img src="webroot/img/download-2.png" alt="Download" width="25" height="25"></a></div>';
				}		         
				?>
				<table>		    	
				<tr>
				<td>Ano</td><td><input class="form-control"disabled="disabled" type="text" value="<?php echo $anobase; ?>" class="form-control"> </td>
				</tr>
				<tr><td>Comentário</td><td><textarea name="comentario_plano" id="comentario_plano" class="form-control" rows="" cols=""></textarea></td></tr>
				<tr><td>Arquivo</td><td>
				<div id="teste">
					<input class="form-control" class="custom-file-input" type="file" id="upload" name="upload" />
					<input class="form-control"type="hidden" name="MAX_FILE_SIZE" value="10485760">				    
					<input class="form-control"type="text" id="texto" />
					<input type="button" id="botao" value="Selecionar..." class="btn btn-info"/>
				</div>
				</td></tr>    	
				</table>
				<input class="form-control"type="hidden" name="codarquivo" value="1"/>
				<input class="form-control"type="hidden" name="codunidade" value="<?php echo $codunidade;?>"/>
				<input class="form-control"type="hidden" name="anobase" value="<?php echo $anobase;?>"/>		    			    			   
			</form>
		</div>      
     	<div id="" class="alert alert-danger" role="alert" style="display:none;">
      </div>
       
      <div id="divBtn" class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="btnFechar();" data-dismiss="modal">Fechar</button>
        <button type="button" id="btn_salvar_plano" onclick="salvarPlanoDeAcao()"  class="btn btn-info">Registrar</button>
      </div>
       <div id="divBtn2" style="display:none;" class="modal-footer">
        <button type="button" class="btn btn-info" onclick="window.history.go(0);" data-dismiss="modal">OK</button>        
      </div>
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

#teste { position:relative; }
#upload { position:absolute; top:0;left:0; border:1px solid #ff0000; opacity:0.01; z-index:1; }
</style>


<script type="text/javascript">
function enviarValidacaoPT(){
	$("#msg-alertPlano").css("display","none");
	$("#msg-alert").html('');
	if($("#nome_responsavel").val() == ''){
		$("#msg-alert").css("display","");
	    $("#msg-alert").append('<div class="alert alert-warning" role="alert">É necessário inserir o <b>nome</b> do responsável!</div>');	    
	    return;
	}
	if($("#telefone_responsavel").val() == ''){
		$("#msg-alert").css("display","");
	    $("#msg-alert").append('<div class="alert alert-warning" role="alert">É necessário inserir o <b>telefone</b> do responsável!</div>');	    
	    return;
	}
	//Validar e-mail
	var sEmail	= $("#email_responsavel").val();
	// filtros
	var emailFilter=/^.+@.+\..{2,}$/;
	var illegalChars= /[\(\)\<\>\,\;\:\\\/\"\[\]]/
	// condição
	if(!(emailFilter.test(sEmail))||sEmail.match(illegalChars)){
		$("#msg-alert").css("display","");
	    $("#msg-alert").append('<div class="alert alert-warning" role="alert">Insira um <b>e-mail</b> válido para o responsável!</div>');
		return;
	}

	var form = document.getElementById('adicionarPlano');
    var dados = new FormData(form);		
	$.ajax({
        url: "ajax/mapa/registraSoliValidacaoPT.php",
        type: 'POST',
        data: dados,
        success: function(data) {
        	//alert(data);
        	if(data ==1){
        		//$("#loadingInclusao").css("display","none");
        		$("#msg-alert").css("display","");
        		$("#form-plano").css("display","none");
        		$("#msg-alert").html('<div class="alert alert-success" role="alert">Dados gravados com sucesso!.</div>');
        		return;		        	
        	}else{
        		$("#msg-alert").css("display","");
        		$("#msg-alert").html('<div class="alert alert-danger" role="alert">Não foi possível gravar os dados, por favor acione o suporte.</div>');
				alert(data);
				return;
	        	}      
        }
    });		
}


$(function(){
    $('#upload').on('change',function(){
        var numArquivos = $(this).get(0).files.length;
       
	        $('#texto').val( $(this).val() );
      
    });
});

function salvarPlanoDeAcao(){	
	$("#msg-alertPlano").css("display","none");
	$("#msg-alertPlano").html('');
	//Validar envio de arquivo	
	if($("#texto").val() == ''){
		$("#msg-alertPlano").css("display","");
		$("#msg-alertPlano").append('<div class="alert alert-warning" role="alert">Por favor, selecione um <b>arquivo</b>!</div>');	    
		return;
	}	
	var extPermitidas = ['zip', 'rar'];
	var extArquivo = document.getElementById("texto").value.split('.').pop();
	
	if(typeof extPermitidas.find(function(ext){ return extArquivo == ext; }) == 'undefined') {
		$("#msg-alertPlano").css("display","");
		$("#msg-alertPlano").html('<div class="alert alert-warning"  role="alert">Tipo de arquivo não permitido, são aceitos arquivos somente com extenção ".rar" ou ".zip".</div>');		
		return;	
	}else {
		//$("#loadingInclusao").css("display","");
		//Enviar Formulário
		var form = document.getElementById('adicionarPlano');
		var dados = new FormData(form);		
		$.ajax({
		    url: "ajax/mapa/registraPlanoDeAcao.php",
		    type: 'POST',
		    data: dados,
		    success: function(data) {
		    	//alert(data);
		    	if(data ==1){		    		
		    		$("#divBtn").css("display","none");
		    		$("#msg-alertPlano").css("display","");
		    		$("#msg-alertPlano").html('<div class="alert alert-success" role="alert">Dados gravados com sucesso!.</div>');	    		
		    		$("#form-plano").css("display","none");	
		    		$("#divBtn2").css("display","");	    		
		    		return;		        	
		    	}else{
		    		$("#msg-alertPlano").css("display","");
		    		$("#msg-alertPlano").html('<div class="alert alert-danger" role="alert">Não foi possível gravar os dados, por favor acione o suporte.</div>');
					//alert(data);
					return;
		        }	        	
		    }, 
		    //cache: false,
		    contentType: false,
		    processData: false,
		    xhr: function() { // Custom XMLHttpRequest
		        var myXhr = $.ajaxSettings.xhr();
		        if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
		            myXhr.upload.addEventListener('progress', function() {
		                // faz alguma coisa durante o progresso do upload 
		            	//alert("Enviando...");
		            }, false);
		        }
		        return myXhr;
		    }
		});	
	}
}
</script>
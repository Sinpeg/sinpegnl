<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
?>
 <style>
			#unid-listl{float:left;list-style:none;margin-top:-3px;padding:0;width:520px;position: absolute;height: 50px;}
			#unid-listl li{padding: 5px;salvar background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
			#unid-listl li:hover{background:#059c8ce6;cursor: pointer;}
			#libunidade{padding: 5px;border: #000000 1px solid;border-radius:4px;width: 520px;height: 25px;}

.modal {
	text-align: center;
	padding: 0 !important;
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

.modal-content {
	width: 700px;
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
	background-color: rgb(0, 0, 0); /* Fallback color */
	background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
}

.loader {
	left: 50%;
	position: absolute;
	top: 40%;
	left: 45%;
	border: 16px solid #f3f3f3;
	border-radius: 50%;
	border-top: 16px solid #3498db;
	width: 100px;
	height: 100px;
	-webkit-animation: spin 2s linear infinite; /* Safari */
	animation: spin 2s linear infinite;
}
/* Safari */
@
-webkit-keyframes spin { 0% {
	-webkit-transform: rotate(0deg);
}

100%
{
-webkit-transform
:
 
rotate
(360deg);
 
}
}
@
keyframes spin { 0% {
	transform: rotate(0deg);
}

100%
{
transform
:
 
rotate
(360deg);
 
}
}
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

#testeInclusao {
	position: relative;
}
			
</style>
<form class="form-horizontal" name="formlibunidade" id="formlibunidade" method="post">
    <h3 class="card-title">Liberar Atividade para uma Unidade</h3>
    <div class="msg" id="msg"></div>
    
     <table>
            <tbody>
    
    
       <tr>
                <td><label>Nome da unidade</label></td>
               <td> 
                 <input class="form-control" type="text" id="libunidade"  name="libunidade" placeholder="Unidade" autocomplete="off"/> 
		           			<div id="cxsugestao0"></div> </td> 
		           			
		           			     
            </tr>
          
            <tr><td></td><td></td></tr>    
           <tr><td><label>Atividade</label></td><td><select class="custom-select" name="tipo" id="tipo">
                 <option value="0">Selecione tipo...</option>
                            <option  value="1">Liberar RAA </option>
                            <option value="2">Liberar Solicitação de Alteração do PDU</option>
                             <option value="3">Liberar Elaboração do PDU</option>
                                <option value="4">Liberar Elaboração do Painel Tático</option>
                                     <option value="5">Liberar Lançamento Final do Painel Tático</option>
                                
                      </select></td></tr>  
                      
        
     </tbody>
    </table>
    <?php //data-toggle="modal" data-target="#liberarunidade" ?>
             
                 
        <button type="button"  onClick="botliberacao();" data-toggle="modal" data-target="#liberarunidade"  class="btn btn-info" id="botlibera" >
        
Liberar atividade</button>
         
</form>
<script>
  $( function() {
   $( "#databloqueio" ).datepicker({
    	    dateFormat: 'dd/mm/yy',
    	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
    	    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
    	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
    	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    	    nextText: 'Próximo',
    	    prevText: 'Anterior',
    	    minDate: new Date()
   		 }); 
  } );
 
   function validarData() {	
       	DAY = 1000 * 60 * 60  * 24
   
   	    data1 =  $("#datadesbloqueio").prop("value");
    	data2 =  $("#databloqueio").prop("value");
        var nova1 = data1.toString().split('/');
    	Nova1 = nova1[1]+"/"+nova1[0]+"/"+nova1[2];
    	var nova2 = data2.toString().split('/');
    	Nova2 = nova2[1]+"/"+nova2[0]+"/"+nova2[2];

    	d1 = new Date(Nova1);
    	d2 = new Date(Nova2);

    	days_passed = Math.round((d2.getTime() - d1.getTime()) / DAY);
     	if (days_passed<0){
    		   return false;
    	}
    	}		
   	
   		 
function botliberacao(){
 
	$("div#msg").empty();	
	if($("#libunidade").val() == ""){
	    $("div#msg").html("O campo unidade é obrigatório!");
	    return ;
	}else if ($("#tipo").val() == "0"){
	    $("div#msg").html("O campo atividade é obrigatório!");
	    return ;
	}else {
  	
  	//jQuery.noConflict(); 
  	//$('#liberarunidade').modal('toggle');
     $("div#msg").empty();
		var unid=document.getElementById("libunidade").value ;
		var ativ=document.getElementById("tipo").value;
		 $.ajax({
			    type: "POST",
			    url: "ajax/unidade/escolheAtividade.php",
		        data: {unid:unid,ativ:ativ},
		        
				 
			    success: function(data){
				   // $("div#msg1").html(data);
				    
				   var obj = $.parseJSON(data);
				    $("#unidade").val(obj.unidade);
				    $("#atividade").val(obj.atividade);
				    $("#databloqueio").val(obj.databloqueio);
				    $("#datafinaliz").val(obj.datafinaliz);
				    $("#datadesbloqueio").val(obj.datadesbloqueio);
				    $("#codUnidade").val(obj.coduni);
				    
				    $("#codAtividade").val(obj.codAtividade);
				    $("#codigolib").val(obj.codigolib);
				    
				    
			       }
	      });	
    	
	}
	}



      



	          
	

 

</script>





<!-- Modal Loading-->
<div class="" id="modalLoading" tabindex="-1" role="dialog"
	aria-labelledby="modalLoading" aria-hidden="true">
	<div class="loader"></div>
</div>

<!-- Modal Inserir Solicitação-->
<div class="modal fade" id="liberarunidade" tabindex="-1" role="dialog"
	aria-labelledby="libAtiv" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Liberar atividade</h4>
			</div>
			<div class="modal-body">
				<div id="form">
					<form class="form-horizontal" name="fa" id="fa" method="POST">
					<div id="msg2"></div>
						<fieldset>
							<table>
<tr>
	<td> <label>Unidade</label></td>
	<td>
		<input class="form-control"placeholder="unidade"  type="text" readonly="readonly" id="unidade" name="unidade"
													 class="short"  maxlength="20" size="50" 
													 value=""/>
	</td>
	</tr>	
<tr>
	<td> <label>Atividade</label></td>
	<td>
		<input class="form-control"placeholder="Atividade" readonly="readonly"  type="text" id="atividade" name="atividade"
													 class="short"  maxlength="20" size="40" 
													 value=""/>
	</td>
	</tr>						
<tr>
	<td> <label>Data de Finalização da Atividade</label></td>
	<td>
		<input class="form-control"placeholder="Data Finalização"  readonly="readonly" type="text" id="datafinaliz" name="datafinaliz"
													 class="short"  maxlength="20" size="15" 
													 value=""/>
		
	</td>
	</tr>
	<tr>
	<td> <label>Período de liberação</label></td>
	<td>
		<input class="form-control"placeholder="Início da liberação do formulário" readonly="readonly" type="text" id="datadesbloqueio" name="datadesbloqueio"
													 class="short"  maxlength="20" size="15" 
													 value=""/>
	
	a
		<input class="form-control"placeholder="Data de bloqueio do formulário" type="text" id="databloqueio" name="databloqueio"
													 class="short"  maxlength="10" size="15" 
													 value=""/>
		
	</td>
	</tr>
							
							</table>
							 <input class="form-control"type="hidden"
								name="codUnidade" id="codUnidade"
								value="" /> 
								
								<input class="form-control"type="hidden"
								name="codAtividade" id="codAtividade"
								value="" /> 
								<input class="form-control"type="hidden"
								name="codigolib" id="codigolib"
								value="" /> 
						</fieldset>
					</form>
				</div>
				<div id="confirmacaoLib" style="display: none;">
					<p>
						<span class="ui-icon ui-icon-circle-check"
							style="float: left; margin: 0 7px 50px 0;"></span> Liberação
						realizada com sucesso.
					</p>
				</div>
				
				<div id="loading" style="display: none;">
					<div class="loader"></div>
				</div>
			</div>
			<div class="alert alert-danger" role="alert" id="alert"
				style="display: none;"></div>
			<div class="modal-footer">

				<button type="button" class="btn btn-secondary"
					onclick="btnFecharLib();" data-dismiss="modal">Fechar</button>
				<button type="button" id="btnLibera" onclick="liberaAtividade();" class="btn btn-info">Gravar</button>
			</div>
		</div>
	</div>
</div>



<script>
function btnFecharLib(){

$("#confirmacaoLib").css("display","none");

}
function liberaAtividade() {
	$("#confirmacaoLib").css("display","none");
	 $("#alert").css("display","none");
     $("#alert").html("");
	 if ($("#databloqueio").val()==""){
	     $("#alert").css("display","");
		 $("#alert").html("Data final do período de liberação é obrigatória!");
	 } else	 if (validarData()){
		//Validar Dados
		$("#alert").css("display","");
		$("#alert").html("Data final do período de liberação deve ser posterior à data inicial!");
		return;
	}else{
	   
    
    
    
    	
        $.ajax({
	        url: "ajax/unidade/gravaLiberacao.php",
	        type: 'POST',
	        data: $("form[name=fa]").serialize(),
	        success: function(data) {
	        	//alert(data);
	        //	if (data =="1"){
	        		//$("#loading").css("display","none");
		        	//$("#form").css("display","none");
		        	//$("#btnEnviarSol").css("display","none");
		        	$("#confirmacaoLib").css("display","");
	        /*	}else{
	        		alert(data);
	        	}	*/        	
	        }, 
	       
	
	    });
    
    
    
    
    
    
    
    
    
    
      
	    
	}//else meu
	
	 
}

</script>


/* inclui topicos */
$(function() {
    $("#btnCriarTopico").click(function() {
    	$("#msg").html("");
    	if($("#titulo").val() != ""){ //Verifica se o título está vazio
	    	$.ajax({
	            url: "ajax/raa/raaajax.php",
	            type: "POST",
	            data: $("form[name=form-criarTopico]").serialize(),
	            success: function(data) {                
	            	if(data == 0){
	            		$("#msg").html("<div class='alert alert-danger' role='alert'>Já existe um Tópico com o mesmo Título!</div>");
	            	}else{
	            		//alert(data);
		            	window.location.href = "?modulo=raa&acao=consultarTopicos";
	            	}
	            	
	            }
	        });
    	}else{
    		$("#msg").html("<div class='alert alert-warning' role='alert'>É necessário inserir um <b>Título</b>!</div>");
    	}
    });    
    
});
/*Fim*/


/* Editar topico */
$(function() {
    $("#btnSalvarTopico").click(function() {
    	$("#msg2").html("");
    	if($("#editarTitulo").val() != ""){ //Verifica se o título está vazio
	    	$.ajax({
	            url: "ajax/raa/raaajax.php",
	            type: "POST",
	            data: $("form[name=form-editarTopico]").serialize(),
	            success: function(data) {                
	            	//alert(data);
	            	if(data == 0){
	            		$("#msg2").html("<div class='alert alert-danger' role='alert'>Já existe um Tópico com o mesmo Título!</div>");
	            	}else if(data == 2){
	            		//alert(data);
		            	//window.location.href = "?modulo=raa&acao=consultarTopicos";
	            		$("#msg2").html("<div class='alert alert-danger' role='alert'>Não é possível desabilitar o TÓPICO, pois possui SUBTOPICO(S) válido(s)!</div>");
	            	}else{	            	
	            		//history.go(0);
	            		window.location.reload();            		
	            	}
	            	
	            }
	        });
    	}else{
    		$("#msg2").html("<div class='alert alert-warning' role='alert'>É necessário inserir um <b>Título</b>!</div>");
    	}
    });    
    
});
/*Fim*/

/* excluir topico */
$(function() {
    $("#btnExcluirTopico").click(function() {
    		codTopico = $("#codTopico").val();
    		//alert(codTopico);
    		  	$.ajax({
	            url: "ajax/raa/excluirTopicoAjax.php",
	            type: "POST",
	            data: { codTitulo: codTopico  },
	            success: function(data) {           	
	            		//alert(data);
		            	//window.location.href = "?modulo=raa&acao=consultarTopicos";	
	            		history.go(0);
	            }
	        });    	
    });    
    
});
/*Fim*/


//Função que busca os dados do tópico para exibir no modal de edição
function modalEditar(codTopico){
	$("#codTopico").val(codTopico);		
	$.ajax({
        url: "ajax/raa/buscarTopicoAjax.php",
        type: "POST",
        data: { codTitulo: codTopico  },
        success: function(data) {                
        	var obj = $.parseJSON(data);        	
        	$("#editarTitulo").val(obj['titulo']);        	
        	$( "#editarSituacao" ).prop( "checked", true );        	
        	var unidades = obj['arrayUni'];
        	if(unidades!=''){
        		$("#pre-selected-options2").val(unidades);//Seleciona as unidades
        	}else{
        		$("#pre-selected-options2").val(0);//Seleciona as unidades
        	}
        	// run pre selected options        	
      	  $('#pre-selected-options2').multiSelect({
      		  selectableHeader: "<input type='text' style='width: 254px;' class='search-input' autocomplete='off' placeholder='Unidades'>",
      		  selectionHeader: "<input type='text' style='width: 254px;' class='search-input' autocomplete='off' placeholder='Unidades Vínculadas'>",
      		  afterInit: function(ms){
      			    var that = this,
      			        $selectableSearch = that.$selectableUl.prev(),
      			        $selectionSearch = that.$selectionUl.prev(),
      			        selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
      			        selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

      			    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
      			    .on('keydown', function(e){
      			      if (e.which === 40){
      			        that.$selectableUl.focus();
      			        return false;
      			      }
      			    });

      			    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
      			    .on('keydown', function(e){
      			      if (e.which == 40){
      			        that.$selectionUl.focus();
      			        return false;
      			      }
      			    });
      			  },
      			  afterSelect: function(){
      			    this.qs1.cache();
      			    this.qs2.cache();
      			  },
      			  afterDeselect: function(){
      			    this.qs1.cache();
      			    this.qs2.cache();
      			  }
      	 });        	        	
        }
    });	
}

//Função que busca os dados do tópico para exibir no modal
function modalExcluir(codTopico){
	$("#codTopico").val(codTopico);	
	//alert("teste");
	$.ajax({
        url: "ajax/raa/buscarTopicoAjax.php",
        type: "POST",
        data: { codTitulo: codTopico  },
        success: function(data) {                
        	var obj = $.parseJSON(data);
        	$("#tituloTopicoExcluir").html(obj['titulo']);
        }
    });	
}

/////////////////////////////////////Ordenação subtopico
$( function() {
    $( "#ordenarCorpoTopico" ).sortable( {
    	items: 'tr:not(.desabilitar)',
    	update: function( event, ui ) {
        $(this).children().each(function(index) { //Loop que obtem as linhas da tabela
    			$(this).find('td').first().html(index + 1)
    			arrayTr = $(this).find('td');
    			var ordem = arrayTr[0].innerHTML;
    			var topico = $(this).attr("data-name")
    			//alert($(this).attr("data-name")+" ---"+ arrayTr[0].innerHTML);   
    			//Atualisa a ordem no banco
    			
    			$.ajax({
    		        url: "ajax/raa/ordenarTopicoAjax.php",
    		        type: "POST",
    		        data: { ordem: ordem, topico: topico},
    		        success: function(data) {                
    		        	//alert(data);
    		        	//var obj = $.parseJSON(data);
    		        	//$("#tituloTopicoExcluir").html(obj['titulo']);
    		        }
    		    });
    		        			
        });
      }
    });

});




///////////////////////////////////////////////SUBTOPICO
//Ordenação subtopico
$( function() {
    $( "#ordenarCorpoSubtopico" ).sortable( {
    	update: function( event, ui ) {
        $(this).children().each(function(index) { //Loop que obtem as linhas da tabela
    			$(this).find('td').first().html(index + 1)
    			arrayTr = $(this).find('td');
    			var ordem = arrayTr[0].innerHTML;
    			var topico = $(this).attr("data-name")
    			//alert($(this).attr("data-name")+" ---"+ arrayTr[0].innerHTML);   
    			//Atualisa a ordem no banco
    			$.ajax({
    		        url: "ajax/raa/ordenarSubtopicoAjax.php",
    		        type: "POST",
    		        data: { ordem: ordem, topico: topico},
    		        success: function(data) {                
    		        	//alert(data);
    		        	//var obj = $.parseJSON(data);
    		        	//$("#tituloTopicoExcluir").html(obj['titulo']);
    		        }
    		    });    			
        });
      }
    });

});



/* incluir Subtopicos */
$(function() {
    $("#btnCriarSubtopico").click(function() {
    	$("#msg").html("");    	
    	if($("#titulo").val() != ""){ //Verifica se o título está vazio	    	
    		$.ajax({
	            url: "ajax/raa/subtopicoAjax.php",
	            type: "POST",
	            data: $("form[name=form-criarSubtopico]").serialize(),
	            success: function(data) {                
	            	if(data == 0){
	            		$("#msg").html("<div class='alert alert-danger' role='alert'>Já existe um Subtopico com o mesmo Título!</div>");
	            	}else{
	            		//alert(data);
		            	window.location.href = "?modulo=raa&acao=inserirSubtopico&topico="+$("#topico").val();
	            	}
	            	
	            }
	        });
    	}else{
    		$("#msg").html("<div class='alert alert-warning' role='alert'>É necessário inserir um <b>Título</b>!</div>");
    	}
    });    
    
});
/*Fim*/

//Editor textarea da criação do modelo
$(document).ready(function() {
	  $('#editorModelo').summernote({
	        placeholder: 'Defina o modelo!',
	        height: 450, 
	        lang: 'pt-BR',
	  });
	  
	  
});




///////////////////////////////////////MODELO
/* incluir Modelo */
$(function() {
    $("#btnCriarModelo").click(function() {
    	$("#msg").html("");    	
    	var validacao = 1;    	
    	
    	if($("#topico").val() == 0){
    		validacao=0;
    		$("#msg").html("<div class='alert alert-warning' role='alert'>É necessário selecionar o <b>Tópico</b>!</div>");
    		$('html, body').animate({scrollTop:0}, 'slow');    	
    	}   	    	
    	if(validacao == 1){
	    	$.ajax({
		            url: "ajax/raa/modeloAjax.php",
		            type: "POST",
		            data: $("form[name=form-criarModelo]").serialize(),
		            success: function(data) { 		            			            		
			            	alert(data);
		            		window.location.href = "?modulo=raa&acao=consultarModelos";
		            		//history.go(0);    		            	
		            }
		      });
    	}	    	
    });       
});
/*Fim*/

//Visualizar modelo no modal
function modalVisualizar(modelo){
	//$("#divModelo").html(modelo);
	$.ajax({
        url: "ajax/raa/buscarModeloAjax.php",
        type: "POST",
        data: { codModelo: modelo  },
        success: function(data) {                
        	var obj = $.parseJSON(data);
        	$("#divModelo").html(obj['descModelo']);
        	$("#legendaVerModelo").html(obj['legenda']);
        }
    });
}


/* editar Modelo */
$(function() {
    $("#btnEditarModelo").click(function() {
    	$("#msg").html("");    	
    	var validacao = 1;    	
    	
    	if($("#topico").val() == 0){
    		validacao=0;
    		$("#msg").html("<div class='alert alert-warning' role='alert'>É necessário selecionar o <b>Tópico</b>!</div>");
    		$('html, body').animate({scrollTop:0}, 'slow');
    	
    	}   	
    	
    	if(validacao == 1){
	    	$.ajax({
		            url: "ajax/raa/modeloAjax.php",
		            type: "POST",
		            data: $("form[name=form-editarModelo]").serialize(),
		            success: function(data) {
		            		//alert(data);
			            	window.location.href = "?modulo=raa&acao=consultarModelos";
		            		//history.go(0);    		            	
		            }
		      });
    	}	    	
    });       
});
/*Fim*/

//Função que busca os dados do modelo para exibir no modal de exclusão
function modalExcluirModelo(codModelo){	
	$("#codModeloExcluir").val(codModelo);	
	$.ajax({
        url: "ajax/raa/buscarModeloAjax.php",
        type: "POST",
        data: { codModelo: codModelo  },
        success: function(data) {                
        	var obj = $.parseJSON(data);
        	$("#legendaModeloExcluir").html(obj['legenda']);
        }
    });	
}

/* excluir modelo */
$(function() {
    $("#btnExcluirModelo").click(function() {
    		codModelo = $("#codModeloExcluir").val();
    		$.ajax({
	            url: "ajax/raa/excluirModeloAjax.php",
	            type: "POST",
	            data: { codModelo: codModelo  },
	            success: function(data) {           	
	            		history.go(0);
	            }
	        });    	
    });    
    
});
/*Fim*/

/* ordenar modelo dentro do tópico (exibição dos modelos) */
$(function() {
    $("#topicoOrdenar").change(function() {
    		$("#msgOrdenar").html("");
    		var codTopico = $("#topicoOrdenar").val();
    		if($("#topicoOrdenar").val() == 0){
    			$("#msgOrdenar").html("<div class='alert alert-warning' role='alert'>É necessário selecionar o <b>Tópico / Subtopico</b>!</div>");
    			$("#tableConteudo").css("display","none");
    		}else{    			
	    		$.ajax({
		            url: "ajax/raa/buscarModelosTopicoAjax.php",
		            type: "POST",
		            data: { codTopico: codTopico  },
		            success: function(data) { 		            	
		            	//alert(data);
		            	$("#ordenarCorpoModelos").html(data);	
		            }
		        });
	    		$("#tableConteudo").css("display","block");
    		}	
    });    
    
});
/*Fim*/

//Ordenação modelo ao mudar na tabela 
$( function() {
  $( "#ordenarCorpoModelos" ).sortable( {
  	update: function( event, ui ) {
      $(this).children().each(function(index) { //Loop que obtem as linhas da tabela
  			$(this).find('td').first().html(index + 1)
  			arrayTr = $(this).find('td');
  			var ordem = arrayTr[0].innerHTML;
  			var modelo = $(this).attr("data-name")
  			//alert($(this).attr("data-name")+" ---"+ arrayTr[0].innerHTML);   
  			//Atualisa a ordem no banco  			
  			$.ajax({
  		        url: "ajax/raa/ordenarModeloAjax.php",
  		        type: "POST",
  		        data: { ordem: ordem, modelo: modelo},
  		        success: function(data) {                
  		        	//alert(data);
  		        	//var obj = $.parseJSON(data);
  		        	//$("#tituloTopicoExcluir").html(obj['titulo']);
  		        }
  		    });    			
      });
    }
  });

});

//Visualizar subtopicos padrões
function modalSubPadrao(topico,anobase){
	//$("#divModelo").html(modelo);
	$.ajax({
        url: "ajax/raa/buscarSubPadroesAjax.php",
        type: "POST",
        data: { codTopico: topico ,anobase: anobase},
        success: function(data) {
        	//alert(anobase);
        	$("#divSubtopicosPadroes").html(data);
        }
    });
}


/* Finalizar texto */
$(function() {
    $("#btnConfirmaFim").click(function() {   	confirmaFinal
	    	//alert("teste");
    		$("#div_form_confirme").css("display","none");
    		$("#modalLoading").css("display","block");
    		$.ajax({
	            url: "ajax/raa/finalizarRelatorio.php",
	            type: "POST",
	            data: $("form[name=form-finalizacao]").serialize(),
	            success: function(data) {
	            	$("#modalLoading").css("display","none");
	            	if(data==1){	            		
	            		//alert(data);
		            	window.location.href = "?modulo=raa&acao=listaTexto";
	            	}else{
	            		//alert(data);
	            		$("#span_pendencia").html(data);
	            		$("#div_pendencia").css("display","block");
	            		$("#footer_1").css("display","none");
	            		$("#footer_2").css("display","block");
	            	}
	            }	            	
	            
	        });    	
    });     
});
/*Fim*/

$(function() {
    $("#btnFecharTopico").click(function() {   	
    	window.location.reload();   	
    });
    $("#btnFecharPendencia").click(function() {   	
    	window.location.reload();   	
    });
});




function selectOperacao(valor) { 

	    	//alert(valor);
    		$("#conteudoSolicitacao").html("");
    		//$("#modalLoading").css("display","block");
    		
    		if(valor==0 || valor==7){    			
    			//Ocultar dados
    			$("#nomeEditar").css("display","none");
    			$("#formulaEditar").css("display","none");
    			$("#interEditar").css("display","none");
    		}
    		
    		if(valor==1){//Opção de substituição do indicador
    			//Ocultar dados
    			$("#nomeEditar").css("display","none");
    			$("#formulaEditar").css("display","none");
    			$("#interEditar").css("display","none");    			   		
    			
    			//Busca os indicadores
	    		$.ajax({
		            url: "ajax/indicadorpdi/buscaIndSolicitacao.php",
		            type: "POST",
		            data: $("form[name=form-criarSol]").serialize(),
		            success: function(data) {           	
		            	//alert(data);
		            	$("#conteudoSolicitacao").html(data);
		            	
		                $.extend($.tablesorter.themes.bootstrap, {
		                    // these classes are added to the table. To see other table classes available,
		                    // look here: http://twitter.github.com/bootstrap/base-css.html#tables
		                    table: 'table table-bordered',
		                    caption: 'caption',
		                    header: 'bootstrap-header', // give the header a gradient background
		                    footerRow: '',
		                    footerCells: '',
		                    icons: '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
		                    sortNone: 'bootstrap-icon-unsorted',
		                    sortAsc: 'icon-chevron-up glyphicon glyphicon-chevron-up', // includes classes for Bootstrap v2 & v3
		                    sortDesc: 'icon-chevron-down glyphicon glyphicon-chevron-down', // includes classes for Bootstrap v2 & v3
		                    active: '', // applied when column is sorted
		                    hover: '', // use custom css here - bootstrap class may not override it
		                    filterRow: '', // filter row class
		                    even: '', // odd row zebra striping
		                    odd: ''  // even row zebra striping
		                });

		                // call the tablesorter plugin and apply the uitheme widget
		                $("table").tablesorter({
		                    // this will apply the bootstrap theme if "uitheme" widget is included
		                    // the widgetOptions.uitheme is no longer required to be set
		                    theme: "bootstrap",
		                    widthFixed: true,
		                    headerTemplate: '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

		                    // widget code contained in the jquery.tablesorter.widgets.js file
		                    // use the zebra stripe widget if you plan on hiding any rows (filter widget)
		                    widgets: ["uitheme", "filter", "zebra"],
		                    widgetOptions: {
		                        // using the default zebra striping class name, so it actually isn't included in the theme variable above
		                        // this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
		                        zebra: ["even", "odd"],
		                        // reset filters button
		                        filter_reset: ".reset"

		                                // set the uitheme widget to use the bootstrap theme class names
		                                // this is no longer required, if theme is set
		                                // ,uitheme : "bootstrap"

		                    }
		                })
		                        .tablesorterPager({
		                            // target the pager markup - see the HTML block below
		                            container: $(".ts-pager"),
		                            // target the pager page select dropdown - choose a page
		                            cssGoto: ".pagenum",
		                            // remove rows from the table to speed up the sort of large tables.
		                            // setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
		                            removeRows: false,
		                            
		                            // output string - default is '{page}/{totalPages}';
		                            // possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
		                            output: '{startRow} - {endRow} / {filteredRows} ({totalRows})'
		                            
		                        });
		            	              	        
		            }        
		        });	    		
	    		
    		}
    		
    		if(valor==2){    			
    			$("#nomeEditar").css("display","");
    			$("#formulaEditar").css("display","");
    			$("#interEditar").css("display","");
    			
    			/*
    			$.ajax({
    		        url: "ajax/indicadorpdi/buscarDadosInd.php",
    		        type: "POST",
    		        data: {mapaInd:mapa},
    		        success: function(data) {          	
    		        	alert(data['nome']);
    		        	$("#nomeInd").val(data['nome']);
    		        }
    			})*/
    		}    		  		    		
}

//Verifica se já possui solicitação cadastrada
function buscarDadosModal(mapaInd,anogestao){
	//alert(mapaInd);
	
	//alert(mapaInd);
	//verifica se já existe uma solicitação para o indicador
	$("#possuiSolicitacao").css("display","none");
	$("#form").css("display","");
	$("#btnEnviarSol").css("display","");
	
	$("#codMapaInd").val(mapaInd); //Define o mapa indicador no formulário para o reespectico indicador selecionado	
	$.ajax({
        url: "ajax/indicadorpdi/buscarDadosInd.php",
        type: "POST",
        data: {mapaInd:mapaInd,ano:anogestao},
        success: function(data) {         	
        	//alert(data);
        	
        	var obj = $.parseJSON(data);
        	
        	if(obj.possui==0){
	        	//alert(obj.nome);
	        	$("#nomeInd").val(obj.nome);
	        	$("#nomeIndEdit").val(obj.nome);
	        	$("#formulaIndEdit").val(obj.formula);
	        	$("#interpretacaoIndEdit").val(obj.interpreatacao);
	        	$("#codInd").val(obj.codInd);
        	}else if(obj.possui==1){
        		$("#possuiSolicitacao").css("display","");
        		$("#form").css("display","none");
        		$("#btnEnviarSol").css("display","none");
        	}
        }
	})
	
}

//Evento para cadastrar solicitação
$("#btnEnviarSol").click(function() {
	
	$("#alert").css("display","none");
    $("#alert").html("");
	
	var op = $("#selectOp").val();
	
	if(op==0){//selecionou nenhum tipo de opção 
		//Validar Dados
		$("#alert").css("display","");
		$("#alert").html("Por favor, selecione um tipo de operação!");
		return;
	}
	
	if(op==1){//substituição do indicador
		//Validar Dados
		var isChecked = jQuery("input[name=subsInd]:checked").val();
		if (!isChecked){
		    $("#alert").css("display","");
		    $("#alert").html("Por favor, selecione um indicador substituto!");
		    return;
		}
	}
		
	//Validar justificativa
	if(!$.trim($("#justificativa").val())){
		$("#alert").css("display","");
	    $("#alert").html("Por favor, insira uma justificativa!");
	    return;
	}
	
	//Validar envio de arquivo
	if($("#arquivo").val() == ''){
		$("#alert").css("display","");
	    $("#alert").html("Por favor, selecione um arquivo!");
	    return;
	}	
	var extPermitidas = ['zip', 'rar'];
	var extArquivo = document.getElementById("arquivo").value.split('.').pop();
	  
	if(typeof extPermitidas.find(function(ext){ return extArquivo == ext; }) == 'undefined') {
		$("#alert").css("display","");
		$("#alert").html("Tipo de arquivo não permitido, são aceitos arquivos somente com extenção '.rar' ou '.zip'.");		
		return;
		
	} else {		
	    //Formulário Validado e pronto pra ser enviado/////////////////////////////////////
		var form = document.getElementById('form-criarSolicitacao');
        var dados = new FormData(form);
        $("#loading").css("display","");
        //alert($("#formulaIndEdit").val());
        dados.set('justificativa', $("#justificativa").val());
        dados.set('nomeIndEdit', $("#nomeIndEdit").val());
        dados.set('formulaIndEdit', $("#formulaIndEdit").val());
        dados.set('interpretacaoIndEdit', $("#interpretacaoIndEdit").val());
        $.ajax({
	        url: "ajax/indicadorpdi/registraSolicitacao.php",
	        type: 'POST',
	        data: dados,
	        success: function(data) {
	        	if(data ==1){
	        		$("#loading").css("display","none");
		        	$("#form").css("display","none");
		        	$("#btnEnviarSol").css("display","none");
		        	$("#confirmacaoSol").css("display","");
	        	}else{
	        		alert(data);
	        	}	        	
	        }, 
	        //cache: false,
	        contentType: false,
	        processData: false,
	        xhr: function() { // Custom XMLHttpRequest
	            var myXhr = $.ajaxSettings.xhr();
	            if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
	                myXhr.upload.addEventListener('progress', function() {
	                    /* faz alguma coisa durante o progresso do upload */
	                	//alert("Enviando...");
	                }, false);
	            }
	            return myXhr;
	        }
	    });
	  ////////////////////////////////////////////////////////////////////////////////////
	    
	}
	
	 
})

//Evento para cadastrar solicitação de inclusão
$("#btnEnviarSolInclusao").click(function() {
	
	$("#alertInclusao").css("display","none");
    $("#alertInclusao").html("");
   	
  //Validar Dados
	var isChecked = jQuery("input[name=subsInd]:checked").val();
	if (!isChecked){
	    $("#alertInclusao").css("display","");
	    $("#alertInclusao").html("Por favor, selecione um indicador!");
	    return;
	}
    
	//Validar justificativa
	if(!$.trim($("#justificativaInclusao").val())){
		$("#alertInclusao").css("display","");
	    $("#alertInclusao").html("Por favor, insira uma justificativa!");
	    return;
	}
	 
	//Validar envio de arquivo
	if($("#arquivoInclusao").val() == ''){
		$("#alertInclusao").css("display","");
	    $("#alertInclusao").html("Por favor, selecione um arquivo!");
	    return;
	}	
	var extPermitidas = ['zip', 'rar'];
	var extArquivo = document.getElementById("arquivoInclusao").value.split('.').pop();
	  
	if(typeof extPermitidas.find(function(ext){ return extArquivo == ext; }) == 'undefined') {
		$("#alertInclusao").css("display","");
		$("#alertInclusao").html("Tipo de arquivo não permitido, são aceitos arquivos somente com extenção '.rar' ou '.zip'.");		
		return;
		
	}else {
		
	    //Formulário Validado e pronto pra ser enviado/////////////////////////////////////
		var form = document.getElementById('form-criarSolicitacaoInclusao');
        var dados = new FormData(form);
        $("#loadingInclusao").css("display","");
        
        dados.set('justificativaInclusao', $("#justificativaInclusao").val());        
        $.ajax({
	        url: "ajax/indicadorpdi/registraSolicitacaoInclusaoInd.php",
	        type: 'POST',
	        data: dados,
	        success: function(data) {
	        	//alert(data);
	        	if(data ==1){
	        		$("#loadingInclusao").css("display","none");
		        	$("#formInclusao").css("display","none");
		        	$("#btnEnviarSolInclusao").css("display","none");
		        	$("#confirmacaoSolInclusao").css("display","");
	        	}else{
	        		alert(data);
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
	  ////////////////////////////////////////////////////////////////////////////////////
	    
	}
	
	 
})



//Validar tipo de arquivo
function verificaExtensao($input) {
  var extPermitidas = ['zip', 'rar'];
  var extArquivo = $input.value.split('.').pop();
  $("#alert").css("display","none");
  if(typeof extPermitidas.find(function(ext){ return extArquivo == ext; }) == 'undefined') {
	  $("#alert").css("display","");
	  $("#alert").html("Tipo de arquivo não permitido, são aceitos arquivos somente com extenção '.rar' ou '.zip'.");
  } else {
    //alert('Ok!');
  }
}


function btnFechar(){
	$("#form").css("display","");
	$("#btnEnviarSol").css("display","");
	$("#selectOp").val("0");
	$("#justificativa").val("");
	$("#arquivo").val("");
	$("#confirmacaoSol").css("display","none");
	//Ocultar dados
	$("#nomeEditar").css("display","none");
	$("#formulaEditar").css("display","none");
	$("#interEditar").css("display","none");
	$("#conteudoSolicitacao").html("");
	
	$("#opsucesso").css("display","none");


	
}

function buscarDadosSolicitacao(codSolicitacao,tipo){
	//alert(codSolicitacao+","+tipo);
	
	if(tipo==1){//Substituir indicador
		$("#btn_enviarCS").css("display","");
		$("#table_dados1").css("display","");
		$("#table_delegar1").css("display","none");
		$("#btn_gravarSolDadosS").css("display","");
		$("#btn_delegarSS").css("display","");
		$("#btn_gravarDelegar1").css("display","none");
	}else if(tipo==2){ //Editar Indicador
		$("#btn_enviarCE").css("display","");
		$("#table_dados2").css("display","");
		$("#table_delegar2").css("display","none");
		$("#btn_gravarSolDadosE").css("display","");
		$("#btn_delegarSE").css("display","");
		$("#btn_gravarDelegar2").css("display","none");		
	}else if (tipo==3){
		$("#form-criarSolicitacao1").css("display","");
		$("#btn_enviarIO").css("display","");
		$("#btn_gravarSolDadosIO").css("display","");
		$("#btn_delegarIO").css("display","");
		$("#btn_gravarDelegarIO").css("display","none");
		$("#table_delegarIO").css("display","none");
		
	}else if (tipo==4){
		$("#form-criarSolicitacao2").css("display","");
		$("#btn_enviarRM").css("display","");
		$("#btn_gravarSolDadosS").css("display","");
		$("#btn_delegarRM").css("display","");
		$("#btn_gravarDelegarRM").css("display","none");
		$("#table_delegarRM").css("display","none");		
	}else if (tipo==5){
		$("#form-criarSolicitacao3").css("display","");
		$("#btn_enviarEO").css("display","");
		$("#btn_gravarSolDadosEO").css("display","");
		$("#btn_delegarEO").css("display","");
		$("#btn_gravarDelegarEO").css("display","none");	
		$("#table_delegarEO").css("display","none");
	}else if(tipo==6){ //Incluir Indicador
		$("#btn_enviarCIn").css("display","");
		$("#table_dados6").css("display","");
		$("#table_delegar6").css("display","none");
		$("#btn_gravarSolDadosIn").css("display","");
		$("#btn_delegarSIn").css("display","");
		$("#btn_gravarDelegar6").css("display","none");		
	}else if (tipo==7){ //Excluir Indicador
		$("#btn_enviarCEx").css("display","");
		$("#table_dados7").css("display","");
		$("#table_delegar7").css("display","none");
		$("#btn_gravarSolDadosEx").css("display","");
		$("#btn_delegarSEx").css("display","");
		$("#btn_gravarDelegar7").css("display","none");
	
	}
	
	$("#tr_situacaoDadosS").css("display", "");
	$("#tr_situacaoDadosE").css("display", "");
	$("#tr_situacaoDadosIn").css("display", "");
	$("#tr_situacaoDadosEx").css("display", "");
	
	$("#tr_deferir1").css("display","");
	$("#tr_deferir2").css("display","");
	$("#tr_deferir6").css("display","");
	$("#tr_deferir7").css("display","");
	
	$("#tipoSolicitacaoB").val(tipo);//set do tipo de solicitação

	$.ajax({
        url: "ajax/indicadorpdi/buscarDadosSolicitacao.php",
        type: "POST",
        data: {codSolicitacao:codSolicitacao,tipo:tipo},
        success: function(data) {        	
        	//alert(data);
            if (tipo!=3){
	        	var obj = $.parseJSON(data);	
		        	if(obj.situacao!="Aberta" && obj.situacao!="Delegado"){
		        		$("#tr_deferir1").css("display","none");
		        		$("#tr_deferir2").css("display","none"); 
		        		$("#tr_deferir6").css("display","none"); 
		        		$("#tr_deferir7").css("display","none"); 
		        	}        	      	     	
	        	}
        	
            	
        	if (tipo==1){
        		//alert("x"+codSolicitacao+"-"+tipo);

        		var btn = "gravarComentario("+codSolicitacao+")";           	
            	$("#btn_enviarCS").attr("onClick", btn);
            	
        		if(obj.situacao!="Aberta"){
        			$("#btn_delegarSS").css("display","none");
        			$("#tr_cancela1").css("display", "none");
        			if(obj.situacao!="Delegado"){
        				$("#btn_gravarSolDadosS").css("display", "none");        				
        			}else{        				
        				var onc = "gravarDadosS("+codSolicitacao+")";           	
                    	$("#btn_gravarSolDadosS").attr("onClick", onc);
                    	
                    	$("#tr_situacaoDadosS").css("display", "none");
                    	
        			}           		
        		}else{//se solicitação estiver como aberta
        			$("#tr_situacaoDadosS").css("display", "none");
        			
        			var onc = "gravarDadosS("+codSolicitacao+")";           	
                	$("#btn_gravarSolDadosS").attr("onClick", onc);
                	
                	//Define onclick no botão de delegar
                	var onc = "formDelegar("+codSolicitacao+")";           	
                	$("#btn_delegarSS").attr("onClick", onc);
        		}
        		$("#docDadosS").val(obj.documento);
        		$("#nomeA").val(obj.nomeA);
            	$("#nomeN").val(obj.nomeN);
            	$("#objetivoS").val(obj.nomeObjetivo);
            	$("#situacaoDadosS").val(obj.situacao);
            	$("#justDadosS").val(obj.justificativa);
            	var href = "../public/solicitacoes/"+obj.arquivo;
            	$("#arquivoDadosS").attr("href", href);         	
            	$("#iframeCS").html(obj.comentarios);
            		
        	}else if(tipo==2){
        		//alert("teste");
        		
        		var btn = "gravarComentario("+codSolicitacao+")";           	
            	$("#btn_enviarCE").attr("onClick", btn);
        		
            	if(obj.situacao!="Aberta"){
        			$("#btn_delegarSE").css("display","none");
        			$("#tr_cancela2").css("display", "none");
        			if(obj.situacao!="Delegado"){
        				$("#btn_gravarSolDadosE").css("display", "none");
        				
        			}else{
        				//Define onclick no botão de delegar
                    	var onc = "formDelegar("+codSolicitacao+")";           	
                    	$("#btn_delegarSE").attr("onClick", onc);
                    	
                    	var onc = "gravarDadosS("+codSolicitacao+")";           	
                    	$("#btn_gravarSolDadosE").attr("onClick", onc);
        			}
            	}else{//Se estiver aberta
            		$("#tr_situacaoDadosE").css("display", "none");
            		
            		var onc = "gravarDadosS("+codSolicitacao+")";           	
                	$("#btn_gravarSolDadosE").attr("onClick", onc);
                	
                	//Define onclick no botão de delegar
                	var onc = "formDelegar("+codSolicitacao+")";           	
                	$("#btn_delegarSE").attr("onClick", onc);
            	}
        		$("#docDadosE").val(obj.documento);
        		$("#nomeDadosE").val(obj.nome);
        		$("#objetivoE").val(obj.nomeObjetivo);
            	$("#nomeNDadosE").val(obj.nomeN);
            	$("#formulaNDadosE").val(obj.formula);
            	if(obj.interpretacao==1){
            		$("#interpretacaoNDadosE").val("Quanto maior,melhor.");
            	}else if(obj.interpretacao==2){
            		$("#interpretacaoNDadosE").val("Quanto menor,melhor.");
            	}            	
            	$("#situacaoDadosE").val(obj.situacao);
            	$("#justDadosE").val(obj.justificativa);
            	var href = "../public/solicitacoes/"+obj.arquivo;
            	$("#arquivoDadosE").attr("href", href);           	
            	
            	$("#iframeCE").html(obj.comentarios);            	

        }else if (tipo==3){//inserir obj
        	
        	var btn = "gravarComentario("+codSolicitacao+")";           	
        	$("#btn_enviarIO").attr("onClick", btn);
        	var onc = "formDelegar("+codSolicitacao+")";           	
        	$("#btn_delegarIO").attr("onClick", onc);
        	var onc1 = "gravarDadosS("+codSolicitacao+")";           	
        	$("#btn_gravarSolDadosIO").attr("onClick", onc1);
        	
        	//alert(data.search("tr_analisa13")+"ccfc");
        	if (data.search("tr_analisa13")>0 && (data.search("tr_deferido13")>0)){
        		$("#btn_gravarSolDadosIO").css("display","");
        		$("#btn_delegarIO").css("display","none");

        	}
        		//alert(data);
        	$("#resposta111").html(data);
        	
       
        } else if (tipo==5){//excluir obj
        	var btn = "gravarComentario("+codSolicitacao+")";           	
        	$("#btn_enviarEO").attr("onClick", btn);
    		$("#btn_enviarEO").css("display","");
    		$("#btn_gravarSolDadosEO").css("display", "none");
        	
        	$("#tr_cancela21").css("display","none");
            $("#tr_deferido21").css("display","none");
            $("#tr_analisa21").css("display","none");
            if (obj.tipousuario=="C" ){
            	if (obj.situacaoE=="G"){
                  $("#sitfinalE").val("Delegado");
                  $("#tr_deferido21").css("display","");
          		}else if (obj.situacaoE=="D"){
                  $("#sitfinalE").val("Deferida");
                  $("#tr_deferido21").css("display","");
          		}else if (obj.situacaoE=="I" ){
          		  $("#sitfinalE").val("Indeferido");
                  $("#tr_deferido21").css("display","");
          		}else if (obj.situacaoE=="C" ){
            		  $("#sitfinalE").val("Cancelada");
                      $("#tr_deferido21").css("display","");
                }else  if(obj.situacaoE=="A"){
                	$("#tr_cancela21").css("display","");
                	$('#situacaoE option[value='+obj.situacaoE+']').prop('selected', true);
            		$("#btn_gravarSolDadosEO").css("display", "");
            		var onc = "gravarDadosS("+codSolicitacao+")";           	
                	$("#btn_gravarSolDadosEO").attr("onClick", onc);
                	
            	}
            }else {
            	if (obj.situacaoE=="D"){
                    $("#sitfinalE").val("Deferida");
                    $("#tr_deferido21").css("display","");
          			$("#btn_delegarEO").css("display","none");

          		}else if (obj.situacaoE=="G"){
          			$("#tr_analisa21").css("display","");
          		//	$("#tr_deferido21").css("display","");
                	$('#situacaoE option[value='+obj.situacaoE+']').prop('selected', true);
                	 $("#sitfinalE").val("Delegada");
                     $("#tr_deferido21").css("display","");	
                     $("#btn_gravarSolDadosEO").css("display", "");
             		var onc = "gravarDadosS("+codSolicitacao+")";           	
                 	$("#btn_gravarSolDadosEO").attr("onClick", onc);
          		}else if (obj.situacaoE=="I"){
                	$("#tr_deferido21").css("display","");
               	    $("#sitfinalE").val("Indeferida");
          			$("#btn_delegarEO").css("display","none");

          		}else if (obj.situacaoE=="C" ){
          		    $("#sitfinalE").val("Cancelada");
                    $("#tr_deferido21").css("display","");
                }else  if (obj.situacaoE=="A" ) {
          			$("#tr_analisa21").css("display","");
          			$("#tr_deferido21").css("display","");
                	$('#situacaoE option[value='+obj.situacaoE+']').prop('selected', true);
            		
                	var onc = "formDelegar("+codSolicitacao+")";           	
                 	$("#btn_delegarEO").attr("onClick", onc);
                	
            		$("#btn_gravarSolDadosEO").css("display", "");
            		var onc = "gravarDadosS("+codSolicitacao+")";           	
                	$("#btn_gravarSolDadosEO").attr("onClick", onc);
              	}
            }
        	
    		$("#documentoE").val(obj.documentoE);
    		$("#nomeObjE").val(obj.nomeObjE);
        	$("#justificativaE").val(obj.justificativa);
        	$("#indicadoresobj").val(obj.indicadoresobj);
	
        	var href = "../public/solicitacoes/"+obj.arquivo;
        	$("#arquivoDadosE1").attr("href", href);           	
        	
        	$("#iframeEO").html(obj.comentarios);       
        }else if (tipo==4){//repactuacao
        	var btn = "gravarComentario("+codSolicitacao+")";           	
        	$("#btn_enviarRM").attr("onClick", btn);
    		$("#btn_enviarRM").css("display","");
    	
    		$("#tr_cancela11").css("display","none");
            $("#tr_deferido11").css("display","none");
            $("#tr_analisa11").css("display","none");

    		$("#btn_gravarSolDadosR").css("display", "none");
          
        		//alert("xkkkk"+codSolicitacao+"-"+tipo+"-"+obj.situacao+"-"+obj.situacaoRM);

            if (obj.tipousuario=="C" ){
            	if (obj.situacao=="G"){
                    $("#situacaoF").val("Delegado");
                    $("#tr_deferido11").css("display","");
          		}else if (obj.situacao=="D"){
                    $("#situacaoF").val("Deferida");
                    $("#tr_deferido11").css("display","");
          		}else if (obj.situacao=="I"){
          		  $("#situacaoF").val("Indeferido");
                  $("#tr_deferido11").css("display","");
          		}else if (obj.situacao=="C"){
            		  $("#situacaoF").val("Cancelada");
                      $("#tr_deferido11").css("display","");
                 } else  if(obj.situacao=="A"){
          			 $("#tr_cancela11").css("display","");
                 	$('#situacaoRM option[value='+obj.situacao+']').prop('selected', true);                   
                    $("#btn_gravarSolDadosR").css("display", "");
            		var onc = "gravarDadosS("+codSolicitacao+")";           	
                	$("#btn_gravarSolDadosR").attr("onClick", onc);
            	}
            }else {
            	if (obj.situacao=="D"){
                    $("#situacaoF").val("Deferida");
                    $("#tr_deferido11").css("display","");
                 	$("#btn_delegarRM").css("display", "none");

          		}else if (obj.situacao=="G"){
                    $("#situacaoF").val("Delegado");
                    $("#tr_deferido11").css("display","");
                    $("#tr_analisa11").css("display","");
                 	$('#situacaoRM option[value='+obj.situacao+']').prop('selected', true); 
                 	$("#btn_gravarSolDadosR").css("display", "");
            		var onc = "gravarDadosS("+codSolicitacao+")";           	
                	$("#btn_gravarSolDadosR").attr("onClick", onc);
          		}else if (obj.situacao=="I"){
                    $("#tr_deferido11").css("display","");
            		$("#situacaoF").val("Indeferido");
                 	$("#btn_delegarRM").css("display", "none");

          		}else if (obj.situacao=="C"){
          			$("#tr_analisa11").css("display","");
            		$("#situacaoF").val("Cancelada");
          		}else {
          			$("#tr_analisa11").css("display","");
                 	$('#situacaoRM option[value='+obj.situacao+']').prop('selected', true);                   
            		
                 	$("#btn_delegarRM").css("display", "");
                    var onc = "formDelegar("+codSolicitacao+")";           	
                  	$("#btn_delegarRM").attr("onClick", onc); 
                     
            		$("#btn_gravarSolDadosR").css("display", "");
            		var onc = "gravarDadosS("+codSolicitacao+")";           	
                	$("#btn_gravarSolDadosR").attr("onClick", onc);
              	}
             }
            
            $("#documentorm").val(obj.documentorm);
    	    $("#nomeindicadorrm").val(obj.nomeindicadorrm);
         	$("#metarm").val(obj.metarm);
         	$("#anorm").val(obj.anorm);

            $("#novameta").val(obj.novameta);

        	$("#justificativarm").val(obj.justificativarm);
        	var href = "../public/solicitacoes/"+obj.arquivorm;
        	$("#arquivorm").attr("href", href);           	
        	
        	$("#iframeRM").html(obj.comentarios);       

        	}else if(tipo == 6){//incluir indicador        		
        		//alert(tipo);
        		
        		var btn = "gravarComentario("+codSolicitacao+")";           	
            	$("#btn_enviarCIn").attr("onClick", btn);
        		
            	if(obj.situacao!="Aberta"){
        			$("#btn_delegarSIn").css("display","none");
        			if(obj.situacao!="Delegado"){
        				$("#btn_gravarSolDadosIn").css("display", "none");
        				
        			}else{//Quando estiver delegada
        				var onc = "gravarDadosS("+codSolicitacao+")";           	
                    	$("#btn_gravarSolDadosIn").attr("onClick", onc);
        				 
        				//Define onclick no botão de delegar
                    	var onc = "formDelegar("+codSolicitacao+")";           	
                    	$("#btn_delegarSIn").attr("onClick", onc); 
                    	
                    	$("#tr_situacaoDadosIn").css("display", "none");
                    	$("#btn_gravarSolDadosIn").css("display", "");
        			}
            	}else{ //Se estiver aberta
            		$("#tr_situacaoDadosIn").css("display", "none");
            		
            		var onc = "gravarDadosS("+codSolicitacao+")";           	
                	$("#btn_gravarSolDadosIn").attr("onClick", onc);
                	
                	//Define onclick no botão de delegar
                	var onc = "formDelegar("+codSolicitacao+")";           	
                	$("#btn_delegarSIn").attr("onClick", onc);
            	}
        		$("#docDadosIn").val(obj.documento);
        		$("#objetivoIn").val(obj.nomeObjetivo);
        		$("#nomeindicadorIn").val(obj.nomeN);        		            	            	
            	$("#situacaoDadosIn").val(obj.situacao);
            	
            	if(obj.situacao != "Aberta"){
            		$("#tr_cancela6").css("display", "none");
            		//$("#btn_gravarSolDadosIn").css("display", "none");
            	}
            	
            	$("#justDadosIn").val(obj.justificativa);
            	var href = "../public/solicitacoes/"+obj.arquivo;
            	$("#arquivoDadosIn").attr("href", href);           	
            	
            	$("#iframeCIn").html(obj.comentarios);
        		
        	}else if(tipo == 7){//excluir indicador
        		//alert(tipo);
        		
        		var btn = "gravarComentario("+codSolicitacao+")";           	
            	$("#btn_enviarCEx").attr("onClick", btn);
        		
            	if(obj.situacao!="Aberta"){
            		$("#tr_cancela7").css("display", "none");
        			$("#btn_delegarSEx").css("display","none");
        			if(obj.situacao!="Delegado"){
        				$("#btn_gravarSolDadosEx").css("display", "none");
        				
        			}else{
        				var onc = "gravarDadosS("+codSolicitacao+")";           	
                    	$("#btn_gravarSolDadosEx").attr("onClick", onc);
        				
        				//Define onclick no botão de delegar
                    	var onc = "formDelegar("+codSolicitacao+")";           	
                    	$("#btn_delegarSEx").attr("onClick", onc);
                    	$("#tr_situacaoDadosEx").css("display", "none");
                    	$("#btn_gravarSolDadosEx").css("display", "");
        			}
            	}else{//Se estiver aberta
            		$("#tr_situacaoDadosEx").css("display", "none");
            		
            		var onc = "gravarDadosS("+codSolicitacao+")";           	
                	$("#btn_gravarSolDadosEx").attr("onClick", onc);
                	
                	//Define onclick no botão de delegar
                	var onc = "formDelegar("+codSolicitacao+")";           	
                	$("#btn_delegarSEx").attr("onClick", onc);
            	}
        		$("#docDadosEx").val(obj.documento);
        		$("#objetivoEx").val(obj.nomeObjetivo);
        		$("#nomeindicadorEx").val(obj.nomeN);        		            	            	
            	$("#situacaoDadosEx").val(obj.situacao);
            	$("#justDadosEx").val(obj.justificativa);
            	var href = "../public/solicitacoes/"+obj.arquivo;
            	$("#arquivoDadosEx").attr("href", href);           	
            	
            	$("#iframeCEx").html(obj.comentarios);       	
        	}  
	}})
}

//Parecer solicitacao de substituição
function gravarDadosS(codSolicitacao){
	var tipo = $("#tipoSolicitacaoB").val();
	var texto;
	var situacao;
	var user = $("#codUsuario").val(); //Busca do código do autor do comentário
	
	if(tipo==1){
		 texto = $("#comentarioPSubs").val();
		 situacao = $("#situacaoPSubs").val();
	}else if(tipo==2){
		 texto = $("#comentarioPEdit").val();
		  situacao = $("#situacaoPEdit").val();
	}else if (tipo==3){
		 texto = $("#comentarioIO").val();
		 situacao = $('select[name=situacaoIO]').val();
	}else if (tipo==4){
		 texto = $("#comentarioRM").val();
		 situacao = $('select[name=situacaoRM]').val();
	}else if (tipo==5){
		 texto = $("#comentarioEO").val();
		 situacao =$("#situacaoE option:selected").val()
    }else if (tipo==6){
		 texto = $("#comentarioPIn").val();
		 situacao = $("#situacaoPIn").val();
	}else if (tipo==7){
		 texto = $("#comentarioPEx").val();
		 situacao = $("#situacaoPEx").val();
	}
	//alert('situacao'+situacao);
	$.ajax({
        url: "ajax/indicadorpdi/editarSolicitacao.php",
        type: "POST",
        data: {codSolicitacao:codSolicitacao,texto:texto,tipo:tipo,situacao:situacao,user:user},
        success: function(data) { 
        	//var obj = $.parseJSON(data);
        	//alert(data);
        	window.location.reload();

        }
	})
}

//Enviar Comentário
function gravarComentario(codSolicitacao){
	
	var tipo = $("#tipoSolicitacaoB").val();
	//alert(codSolicitacao+"codsol"+tipo+" tipo"+$("#comentarioRM").val());
	var user = $("#codUsuario").val(); //Busca do código do autor do comentário
	//alert(user);
	if(tipo==1){
		var texto = $("#comentarioPSubs").val();
	}else if(tipo==2){
		var texto = $("#comentarioPEdit").val();		
	}else if(tipo==3){
		var texto = $("#comentarioIO").val();		
		if(!$.trim($("#comentarioIO").val())){
   		 $("#msg1").css("display","");
   	     $("#msg1").html("Informe o comentário a ser enviado para o analista!");
   	    return;
        }	
	}else if(tipo==4){
		var texto = $("#comentarioRM").val();
		if(!$.trim($("#comentarioRM").val())){
    		 $("#msg2").css("display","");
    	     $("#msg2").html("Informe o comentário a ser enviado para o analista!");
    	    return;
         }	
	}else if(tipo==5){
		var texto = $("#comentarioEO").val();		
		 if(!$.trim($("#comentarioEO").val())){
			 $("#msg2").css("display","");
	         $("#msg2").html("Informe o comentário a ser enviado para o analista!");
	         return;
		}
	}else if(tipo==6){
		var texto = $("#comentarioPIn").val();		
	}else if(tipo==7){
		var texto = $("#comentarioPEx").val();		
	}	
	
	$.ajax({
        url: "ajax/mapa/enviarComentario.php",
        type: "POST",
        data: {codSolicitacao:codSolicitacao,texto:texto,user:user},
        success: function(data) { 
        	//alert(data);
        	//var obj = $.parseJSON(data);        	
        	window.location.reload();
        }
	})
}

function formDelegar(codSolicitacao){
	//alert("teste");
	var tipo = $("#tipoSolicitacaoB").val();
	if(tipo==1){//Substituir indicador
		$("#btn_enviarCS").css("display","none");
		$("#table_dados1").css("display","none");
		$("#table_delegar1").css("display","");
		$("#btn_gravarSolDadosS").css("display","none");
		$("#btn_delegarSS").css("display","none");
		$("#btn_gravarDelegar1").css("display","");
		//Define onclick no botão de delegar
    	var onc = "delegarS("+codSolicitacao+")";           	
    	$("#btn_gravarDelegar1").attr("onClick", onc);
	}else if(tipo==2){ //Editar Indicador
		$("#btn_enviarCE").css("display","none");
		$("#table_dados2").css("display","none");
		$("#table_delegar2").css("display","");
		$("#btn_gravarSolDadosE").css("display","none");
		$("#btn_delegarSE").css("display","none");
		$("#btn_gravarDelegar2").css("display","");
		//Define onclick no botão de delegar
    	var onc = "delegarS("+codSolicitacao+")";           	
    	$("#btn_gravarDelegar2").attr("onClick", onc);
	}else if(tipo==3){ //Incluir objetivo
		$("#btn_enviarIO").css("display","none");
		$("#form-criarSolicitacao1").css("display","none");
		$("#table_delegarIO").css("display","");
		$("#btn_gravarSolDadosIO").css("display","none");
		$("#btn_delegarIO").css("display","none");
		$("#btn_gravarDelegarIO").css("display","");
		//Define onclick no botão de delegar
    	var onc = "delegarS("+codSolicitacao+")";           	
    	$("#btn_gravarDelegarIO").attr("onClick", onc);
	}else if(tipo==4){ //Repactuação
		$("#btn_enviarRM").css("display","none");
		$("#form-criarSolicitacao2").css("display","none");
		$("#table_delegarRM").css("display","");
		$("#btn_gravarSolDadosR").css("display","none");
		$("#btn_delegarRM").css("display","none");
		$("#btn_gravarDelegarRM").css("display","");
		//Define onclick no botão de delegar
    	var onc = "delegarS("+codSolicitacao+")";           	
    	$("#btn_gravarDelegarRM").attr("onClick", onc);
	}else if(tipo==5){ //exclusão do objetivo
		$("#btn_enviarEO").css("display","none");
		$("#form-criarSolicitacao3").css("display","none");
		$("#table_delegarEO").css("display","");
		$("#btn_gravarSolDadosEO").css("display","none");
		$("#btn_delegarEO").css("display","none");
		$("#btn_gravarDelegarEO").css("display","");
		//Define onclick no botão de delegar
    	var onc = "delegarS("+codSolicitacao+")";           	
    	$("#btn_gravarDelegarEO").attr("onClick", onc);
	}else if(tipo==6){ //Editar Indicador
		$("#btn_enviarCIn").css("display","none");
		$("#table_dados6").css("display","none");
		$("#table_delegar6").css("display","");
		$("#btn_gravarSolDadosIn").css("display","none");
		$("#btn_delegarSIn").css("display","none");
		$("#btn_gravarDelegar6").css("display","");
		//Define onclick no botão de delegar
    	var onc = "delegarS("+codSolicitacao+")";           	
    	$("#btn_gravarDelegar6").attr("onClick", onc);
	}else if(tipo==7){ //Editar Indicador
		$("#btn_enviarCEx").css("display","none");
		$("#table_dados7").css("display","none");
		$("#table_delegar7").css("display","");
		$("#btn_gravarSolDadosEx").css("display","none");
		$("#btn_delegarSEx").css("display","none");
		$("#btn_gravarDelegar7").css("display","");
		//Define onclick no botão de delegar
    	var onc = "delegarS("+codSolicitacao+")";           	
    	$("#btn_gravarDelegar7").attr("onClick", onc);
	}	
}

//Encaminhar solicitação
function delegarS(codSolicitacao){	
	var tipo = $("#tipoSolicitacaoB").val();
	if(tipo==1){
		var user = $("#usuarioDelegadoSS").val();
	}else if(tipo==2){
		var user = $("#usuarioDelegadoSE").val();
	}else if(tipo==3){
		var user = $("#usuarioDelegadoIO").val();
	}else if(tipo==4){
		var user = $("#usuarioDelegado2").val();
	}else if(tipo==5){
		var user = $("#usuarioDelegado3").val();
	}else if(tipo==6){
		var user = $("#usuarioDelegadoSIn").val();
	}else if(tipo==7){
		var user = $("#usuarioDelegadoSEx").val();
	}
	//alert("fdgdfgdfjglk "+codSolicitacao+"-"+$("#usuarioDelegado2").val());
	$.ajax({
        url: "ajax/mapa/delegarSolicitacao.php",
        type: "POST",
        data: {codSolicitacao:codSolicitacao,user:user,codigo:codSolicitacao},
        success: function(data) { 
        	//alert(data);
        	window.location.reload();
        }
	})
	
}

function buscarIndicadoresInclusao(){
	
	//Busca os indicadores
	$.ajax({
        url: "ajax/indicadorpdi/buscaIndSolicitacao.php",
        type: "POST",
        data: $("form[name=form-criarSol]").serialize(),
        success: function(data) {           	
        	//alert(data);
        	$("#conteudoSolicitacaoInclusao").html(data);
        	
            $.extend($.tablesorter.themes.bootstrap, {
                // these classes are added to the table. To see other table classes available,
                // look here: http://twitter.github.com/bootstrap/base-css.html#tables
                table: 'table table-bordered',
                caption: 'caption',
                header: 'bootstrap-header', // give the header a gradient background
                footerRow: '',
                footerCells: '',
                icons: '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
                sortNone: 'bootstrap-icon-unsorted',
                sortAsc: 'icon-chevron-up glyphicon glyphicon-chevron-up', // includes classes for Bootstrap v2 & v3
                sortDesc: 'icon-chevron-down glyphicon glyphicon-chevron-down', // includes classes for Bootstrap v2 & v3
                active: '', // applied when column is sorted
                hover: '', // use custom css here - bootstrap class may not override it
                filterRow: '', // filter row class
                even: '', // odd row zebra striping
                odd: ''  // even row zebra striping
            });

            // call the tablesorter plugin and apply the uitheme widget
            $("table").tablesorter({
                // this will apply the bootstrap theme if "uitheme" widget is included
                // the widgetOptions.uitheme is no longer required to be set
                theme: "bootstrap",
                widthFixed: true,
                headerTemplate: '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

                // widget code contained in the jquery.tablesorter.widgets.js file
                // use the zebra stripe widget if you plan on hiding any rows (filter widget)
                widgets: ["uitheme", "filter", "zebra"],
                widgetOptions: {
                    // using the default zebra striping class name, so it actually isn't included in the theme variable above
                    // this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
                    zebra: ["even", "odd"],
                    // reset filters button
                    filter_reset: ".reset"

                            // set the uitheme widget to use the bootstrap theme class names
                            // this is no longer required, if theme is set
                            // ,uitheme : "bootstrap"

                }
            })
                    .tablesorterPager({
                        // target the pager markup - see the HTML block below
                        container: $(".ts-pager"),
                        // target the pager page select dropdown - choose a page
                        cssGoto: ".pagenum",
                        // remove rows from the table to speed up the sort of large tables.
                        // setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
                        removeRows: false,
                        
                        // output string - default is '{page}/{totalPages}';
                        // possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
                        output: '{startRow} - {endRow} / {filteredRows} ({totalRows})'
                        
                    });
        	              	        
        }        
    });	
	
}

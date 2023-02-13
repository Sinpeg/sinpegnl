//Função para validação dos campos para alteração
function direcionaAlt(botao) {
    var cont = 0;
    var passou = false;
    if (document.fgravar.cat.value == 0) {
        document.getElementById('msg').innerHTML = "O campo Categoria &eacute; obrigat&oacute;rio!";
        passou = true;
        document.fgravar.cat.focus();
    }
    else if (document.fgravar.nome.value == "") {
        document.getElementById('msg').innerHTML = "O campo Nome &eacute; obrigat&oacute;rio!";
        passou = true;
        document.fgravar.nome.focus();
    } else if (document.fgravar.capacidade.value == "") {
        document.getElementById('msg').innerHTML = "O campo Capacidade &eacute; obrigat&oacute;rio!";
        passou = true;
        document.fgravar.capacidade.focus();
    }
    else if (document.fgravar.area.value == "") {
        document.getElementById('msg').innerHTML = "O campo &aacute;rea &eacute; obrigat&oacute;rio e deve ser um n&uacute;mero!";
        passou = true;
        document.fgravar.area.focus();
    } else if (document.fgravar.aulapratica.checked) {
        for (var i = 0; i < document.fgravar.elements.length; i++) {
            if ((document.fgravar.elements[i].type == "radio") && (document.fgravar.elements[i].checked))
                cont = cont + 1;
        }

        if (cont == 0) {
            passou = true;
            document.getElementById('msg').innerHTML = "Para laborat&oacute;rios de aulas pr&aacute;ticas, &eacute; obrigat&oacute;rio responder a quest&atilde;o!";
        }
    }

    if ((document.fgravar.cat.value == 48) &&
            ((document.fgravar.so.value == 0) ||
                    (document.fgravar.local.value == "") ||
                    (document.fgravar.nestacoes.value == ""))) {
        passou = true;
        document.getElementById('msg').innerHTML = "Informe o n&uacute;mero de esta&ccedil;&otilde;es de trabalho, o sistema operacional e o local de laborat&oacute;rios de inform&aacute;tica!";
    }
    
    //Verificar 
    if (document.fgravar.areaAnterior.value != "" && document.fgravar.area.value != document.fgravar.areaAnterior.value && document.fgravar.justificativa.value == "" ) {
        passou = true;
        document.getElementById('msg').innerHTML = "É necessário inserir xx sdfsf uma justificativa para a mudança de área.<br/>";
    }

    if (!passou) {//Enviar formulário com os dados do laboratório
        /* Comentado - 29/08/18
    	document.fgravar.action = "index.php?modulo=labor&acao=oplab";
        document.fgravar.submit();
		*/ 
    	   //alert("teste");
    	   url = "ajax/labor/alteraLab.php"; //Caminho 
    	   data = $('form').serialize(); //Obtem os dados do formulário
    	   // requisição ajax
    	   $.ajax({
    	       type: "POST",
    	       url: url,
    	       data: data,
    	       success: function(data) {
    	    	   window.location="?modulo=labor&acao=consultalab";
    	    	   //alert(data);
    	       },
    	       error: function (request, status, error) {
    	           //alert(request.responseText);
    	    	   alert("Deu erro");
    	       }
    	   });    	
    }else{
    	
    	$('html,body').scrollTop(0);
    }
}

//Função para validação dos campos para a inserção
function direciona(botao) {
    var cont = 0;
    var passou = false;
    if (document.fgravar.cat.value == 0) {
        document.getElementById('msg').innerHTML = "O campo Categoria &eacute; obrigat&oacute;rio!";
        passou = true;
        document.fgravar.cat.focus();
    }
    else if (document.fgravar.nome.value == "") {
        document.getElementById('msg').innerHTML = "O campo Nome &eacute; obrigat&oacute;rio!";
        passou = true;
        document.fgravar.nome.focus();
    } else if (document.fgravar.capacidade.value == "") {
        document.getElementById('msg').innerHTML = "O campo Capacidade &eacute; obrigat&oacute;rio!";
        passou = true;
        document.fgravar.capacidade.focus();
    }
    else if (document.fgravar.area.value == "") {
        document.getElementById('msg').innerHTML = "O campo &aacute;rea &eacute; obrigat&oacute;rio e deve ser um n&uacute;mero!";
        passou = true;
        document.fgravar.area.focus();
    } else if (document.fgravar.aulapratica.checked) {
        for (var i = 0; i < document.fgravar.elements.length; i++) {
            if ((document.fgravar.elements[i].type == "radio") && (document.fgravar.elements[i].checked))
                cont = cont + 1;
        }

        if (cont == 0) {
            passou = true;
            document.getElementById('msg').innerHTML = "Para laborat&oacute;rios de aulas pr&aacute;ticas, &eacute; obrigat&oacute;rio responder a quest&atilde;o!";
        }
    }

    if ((document.fgravar.cat.value == 48) &&
            ((document.fgravar.so.value == 0) ||
                    (document.fgravar.local.value == "") ||
                    (document.fgravar.nestacoes.value == ""))) {
        passou = true;
        document.getElementById('msg').innerHTML = "Informe o n&uacute;mero de esta&ccedil;&otilde;es de trabalho, o sistema operacional e o local de laborat&oacute;rios de inform&aacute;tica!";
    }

    if (!passou) {
        //document.fgravar.action = "index.php?modulo=labor&acao=oplab";
        //document.fgravar.submit();
    	url = "ajax/labor/alteraLab.php"; //Caminho 
 	   data = $('form').serialize(); //Obtem os dados do formulário
 	   // requisição ajax
 	   $.ajax({
 	       type: "POST",
 	       url: url,
 	       data: data,
 	       success: function(data) {
 	    	   window.location="?modulo=labor&acao=consultalab";
 	    	   //alert(data);
 	       },
 	       error: function (request, status, error) {
 	           //alert(request.responseText);
 	    	   alert("Deu erro");
 	       }
 	   });

    }

}

function SomenteNumero(e) {
    var tecla = (window.event) ? event.keyCode : e.which;
    // 0 a 9 em ASCII
    if ((tecla > 47 && tecla < 58)) {
        document.getElementById('msg').innerHTML = " ";
        return true;
    } else {
        if (tecla == 8 || tecla == 0 || tecla == 44) {
            document.getElementById('msg').innerHTML = " ";
            return true;// Aceita tecla tab
        } else {
            document.getElementById('msg').innerHTML = "O campo Capacidade e N. de esta&ccedil;&otilde;es  deve conter n&uacute;mero.";
            return false;
        }
    }
}

function direcionacurso(valor,codLab) {
    if (valor == 1) {
        document.fconsultar.action = "?modulo=labor&acao=inccursolab&codlab="+codLab;
        document.fconsultar.submit();
    }
}

function exibeQuestao() {
    if (document.fgravar.aulapratica.checked) {
        document.getElementById('questao').style.display = "block";
    }
    else {
        document.getElementById('questao').style.display = "none";
        for (var i = 0; i < document.fgravar.elements.length; i++) {
            if ((document.fgravar.elements[i].type == "radio")) {
                document.fgravar.elements[i].checked = false;
            }
        }
    }
}

function exibediv() {
    document.getElementById('exibe').style.display = "none";
}

function mascaradec(valor1) {
    //var texto = document.getElementById("valor1").value;
    var texto = valor1;
    var RegExp = /^\d{1,10}(\.\d{3})*\,\d{2}$/;
    ;

    if (RegExp.test(texto) == true) {
        document.getElementById('msg').innerHTML = "";

    } else {
        document.getElementById('msg').innerHTML = "Formato inv&aacute;lido para o campo Área!";
        document.pf.preco.value = "";
        document.pf.preco.focus();

    }
}
function TABEnter(oEvent) {
    var oEvent = (oEvent) ? oEvent : event;
    var oTarget = (oEvent.target) ? oEvent.target : oEvent.srcElement;
    if (oEvent.keyCode == 13)
        oEvent.keyCode = 9;
    if (oTarget.type == "text" && oEvent.keyCode == 13)
        //return false;
        oEvent.keyCode = 9;
    if (oTarget.type == "radio" && oEvent.keyCode == 13)
        oEvent.keyCode = 9;
}

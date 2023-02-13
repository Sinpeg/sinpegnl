function SomenteNumero(e) {
    var tecla = (window.event) ? event.keyCode : e.which;
    //0 a 9 em ASCII
    if ((tecla > 47 && tecla < 58)) {
        document.getElementById('msg').innerHTML = " ";
        return true;
    }
    else {
        if (tecla == 8 || tecla == 0 || tecla == 44) {
            document.getElementById('msg').innerHTML = " ";
            return true;//Aceita tecla tab
        }
        else {
            document.getElementById('msg').innerHTML = "Todos os campos devem conter apenas números.";
            return false;
        }
    }
}

function Soma() {
    var soma = 0;
    qtde = new Array(document.gravar.qtdDVD.value, document.gravar.qtdAudio.value, document.gravar.qtdAr.value,
            document.gravar.qtdPC.value, document.gravar.qtdVideoconferencia.value, document.gravar.qtdEspecificos.value,
            document.gravar.qtdEletronico.value, document.gravar.qtdMoveis.value, document.gravar.qtdOutrosequipamentos.value,
            document.gravar.qtdProjetores.value, document.gravar.qtdTV.value, document.gravar.qtdInovacoes.value);
    for (var i = 0; i < qtde.length; i++) {
        if (!isNaN(parseInt(qtde[i]))) {
            soma += parseInt(qtde[i]);
        }
    }
    document.getElementById('totalgeral').innerHTML = soma;
}


function valida() {
    var passou = true;
    if (document.gravar.qtdDVD.value == "") {
        document.getElementById('msg').innerHTML = "O campo Aparelho de DVD &eacute; obrigat&oacute;rio.";
        document.gravar.qtdDVD.focus();
    }
    else if (document.gravar.qtdAudio.value == "") {
        document.getElementById('msg').innerHTML = "O campo Equipamento de &Aacute;udio &eacute; obrigat&oacute;rio.";
        document.gravar.qtdAudio.focus();
    }
    else if (document.gravar.qtdAr.value == "") {
        document.getElementById('msg').innerHTML = "O campo Equipamento de Climatiza&ccedil;&atilde;o &eacute; obrigat&oacute;rio.";
        document.gravar.qtdAr.focus();
    }
    else if (document.gravar.qtdPC.value == "") {
        document.getElementById('msg').innerHTML = "O campo Equipamento de Computa&ccedil;&atilde;o &eacute; obrigat&oacute;rio.";
        document.gravar.qtdPC.focus();
    }
    else if (document.gravar.qtdVideoconferencia.value == "") {
        document.getElementById('msg').innerHTML = "O campo Equipamento de Videoconferência &eacute; obrigat&oacute;rio.";
        document.gravar.qtdVideoconferencia.focus();
    }
    else if (document.gravar.qtdEspecificos.value == "") {
        document.getElementById('msg').innerHTML = "O campo  Equipamentos Específicos-Microsc&oacute;pio, Roteador, etc. &eacute; obrigat&oacute;rio.";
        document.gravar.qtdEspecificos.focus();

    }
    else if (document.gravar.qtdEletronico.value == "") {
        document.getElementById('msg').innerHTML = "O campo Equipamentos Eletrônico-Inform&Aacute;ticos &eacute; obrigat&oacute;rio.";
        document.gravar.qtdEletronico.focus();
    }
    else if (document.gravar.qtdMoveis.value == "") {
        document.getElementById('msg').innerHTML = "O campo M&oacute;veis Altamente Relevantes &eacute; obrigat&oacute;rio.";
        document.gravar.qtdMoveis.focus();
    }
    else if (document.gravar.qtdOutrosequipamentos.value == "") {
        document.getElementById('msg').innerHTML = "O campo Outros Equipamentos Relevantes &eacute; obrigat&oacute;rio.";
        document.gravar.qtdOutrosequipamentos.focus();
    }
    else if (document.gravar.qtdProjetores.value == "") {
        document.getElementById('msg').innerHTML = "O campo Projetor Multim&iacute;dia-Data Show, Projetores,etc. &eacute; obrigat&oacute;rio.";
        document.gravar.qtdProjetores.focus();
    }
    else if (document.gravar.qtdTV.value == "") {
        document.getElementById('msg').innerHTML = "O campo Retroprojetor e Televis&atilde;o &eacute; obrigat&oacute;rio.";
        document.gravar.qtdTV.focus();
    }
    else if (document.gravar.qtdInovacoes.value == "") {
        document.getElementById('msg').innerHTML = "O campo Inova&ccedil;&otilde;es Tecnol&oacute;gicas Significativas &eacute; obrigat&oacute;rio.";
        document.gravar.qtdInovacoes.focus();
    } else
        passou = false;

    if (passou) {
        return false;
    }
    else {
        return true;
    }
}

function direciona(botao) {
    if (valida()) {
        document.getElementById('gravar').action = "?modulo=infraensino&acao=opinfraensino";
        document.getElementById('gravar').submit();
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
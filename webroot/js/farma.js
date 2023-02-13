function valida() {
    var passou = false;
    if (document.pf.quantidade.value == "") {
        document.getElementById('msg').innerHTML = "Quantidade é campo obrigatório.";
        passou = true;
    }
    else if (document.pf.preco.value == "") {
        document.getElementById('msg').innerHTML = "Preço é campo obrigatório.";
        passou = true;
    }


    if (passou) {
        return false;
    }
    else {
        return true;
    }
}
function SomenteNumero(e) {
    var tecla = (window.event) ? event.keyCode : e.which;
    //0 a 9 em ASCII
    if ((tecla > 47 && tecla < 58)) {
        document.getElementById('msg').innerHTML = " ";
        return true;
    }
    else {
        if (tecla == 8 || tecla == 0 || tecla == 13 || tecla == 44) {
            document.getElementById('msg').innerHTML = " ";
            return true;//Aceita tecla tab 13 e enter 44
        }
        else {
            document.getElementById('msg').innerHTML = "O campo deve ser num&eacute;rico.";
            return false;
        }
    }
}


function direciona(botao) {
    switch (botao) {
        case 1:
            if (valida()) {
                document.pf.action = "?modulo=produto&acao=opprodfarma";
                document.pf.submit();
            }
            break;
        case 2:
            document.pf.action = "opproduto.php";
            document.pf.submit();
            break;

        case 3:
            document.pf.action = "?modulo=produto&acao=consultapfarma";
            document.pf.submit();
            break;

        case 4:
            document.pf.action = "?modulo=produto&acao=incprodfarma";
            document.pf.submit();
            break;
    }
}

function mascaradec(valor1) {
    //var texto = document.getElementById("valor1").value;
    var texto = valor1;
    var RegExp = /^\d{1,3}(\.\d{3})*\,\d{2}$/;
    ;

    if (RegExp.test(texto) == true) {
        document.getElementById('msg').innerHTML = "";

    } else {
        document.getElementById('msg').innerHTML = "Formato inv&aacute;lido para o campo Pre&ccedil;o!";
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
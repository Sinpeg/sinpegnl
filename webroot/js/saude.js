function valida() {
    var passou = false;
    //ics

    if (document.getElementById("subunidade_busca").options[0].selected) {
        document.getElementById('msg').innerHTML = "Selecione a subunidade...";
        passou = true;
    }

    if ((document.getElementById("subunidade_busca").options[1].value == "2015") && (!passou)) {
        if (document.getElementById("local").options[0].selected) {
            document.getElementById('msg').innerHTML = "Selecione o local...";
            passou = true;
        } else if (document.getElementById("servico").options[0].selected) {
            document.getElementById('msg').innerHTML = "Selecione a servi&ccedil;o...";
            passou = true;
        }
    }

    if ((document.getElementById("procedimento").options[0].selected) && (!passou)) {
        document.getElementById('msg').innerHTML = "Selecione o procedimento...";
        passou = true;
    }

    if ((document.getElementById("subunidade_busca").options[1].value == "2015") && (!passou)) {
        if (document.fpsaude4.npesq.value == "") {
            document.getElementById('msg').innerHTML = "Preencha o campo N&uacute;mero de pesquisadores";
            passou = true;
        } else if (document.fpsaude4.ndoc.value == "") {
            document.getElementById('msg').innerHTML = "Preencha o campo N&uacute;mero de docentes";
            passou = true;
        } else if (document.fpsaude4.ndisc.value == "") {
            document.getElementById('msg').innerHTML = "Preencha o campo N&uacute;mero de discentes";
            passou = true;
        }
    }

    if (((document.getElementById("subunidade_busca").options[1].value == "2015")
            || (document.getElementById("subunidade_busca").options[1].value == "1900")) && (!passou)) {
        if (document.fpsaude4.npaten.value == "") {
            document.getElementById('msg').innerHTML = "Preencha o campo N&uacute;mero de pessoas atendidas";
            passou = true;
        }
    }

    if ((document.getElementById("subunidade_busca").options[1].value == "6000") && (!passou)) {
        if (document.fpsaude4.nexames.value == "") {
            document.getElementById('msg').innerHTML = "Preencha o campo N&uacute;mero de exames realizados";
            passou = true;
        }
    }
    if (!passou)
        return true;
    else
        return false;
}

function zeraproced() {
    document.getElementById("procedimento").options[0].selected = true;
    if (document.getElementById("subunidade_busca").options[1].value == "2015") {
        document.getElementById("local").options[0].selected = true;
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
        if (tecla == 8 || tecla == 0) {
            document.getElementById('msg').innerHTML = " ";
            return true;//Aceita tecla tab
        }
        else {
            document.getElementById('msg').innerHTML = "Os campos n&uacute;mero de discentes, pesquisadores, docentes e pessoas atendidas  devem conter apenas n&uacute;mero.";
            return false;
        }
    }
}


function direciona(botao) {
    switch (botao) {
        case 1:
            if (valida()) {
                document.getElementById('fpsaude4').action = "?modulo=prodsaude4&acao=opprodsaude4";
                document.getElementById('fpsaude4').submit();
            }
            break;
        case 3:
            if (valida1()) {
                document.getElementById('fpsaude4').action = "?modulo=prodsaude4&acao=conspsaude4";
                document.getElementById('fpsaude4').submit();
            }
            break;
        case 4:
            if (valida2()) {
                document.getElementById('fpsaude4').action = "?modulo=prodsaude4&acao=opprodsaude4";
                document.getElementById('fpsaude4').submit();
            }
            break;
    }
}

function valida1() {
    var passou = false;
    //ics

    if (document.getElementById("subunidade_busca").options[0].selected) {
        document.getElementById('msg').innerHTML = "Selecione a subunidade...";
        passou = true;
    }

    if ((document.getElementById("subunidade_busca").options[1].value == "2015") && (!passou)) {
        if (document.getElementById("local").options[0].selected) {
            document.getElementById('msg').innerHTML = "Selecione o local...";
            passou = true;
        } else if (document.getElementById("servico").options[0].selected) {
            document.getElementById('msg').innerHTML = "Selecione a servi&ccedil;o...";
            passou = true;
        }
    }

    if ((document.getElementById("procedimento").options[0].selected) && (!passou)) {
        document.getElementById('msg').innerHTML = "Selecione o procedimento...";
        passou = true;
    }


    if (!passou)
        return true;
    else
        return false;
}


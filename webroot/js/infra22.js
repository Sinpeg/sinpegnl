function Mascara_Hora1(Hora) {
    var hora01 = '';
    hora01 = hora01 + Hora;
    if (hora01.length == 2) {
        hora01 = hora01 + ':';
        document.forms[0].nphi.value = hora01;  
    }
    if (hora01.length == 5) {
        Verifica_Hora1();
    }
}

function Verifica_Hora1() {
    hrs = (document.forms[0].nphi.value.substring(0, 2));
    min = (document.forms[0].nphi.value.substring(3, 5));

    estado = "";
    if ((hrs < 00) || (hrs > 23) || (min < 00) || (min > 59)) {
        estado = "errada";
    }

    if (document.forms[0].nphi.value == "") {
        estado = "errada";
    }

    if (estado == "errada") {
        document.getElementById('msg').innerHTML = "Hora inicial inv&aacute;lida!";
        document.forms[0].nphi.focus();
    }
}

function Mascara_Hora2(Hora) {
    var hora01 = '';
    hora01 = hora01 + Hora;
    if (hora01.length == 2) {
        hora01 = hora01 + ':';
        document.forms[0].nphf.value = hora01;
    }
    if (hora01.length == 5) {
        Verifica_Hora2();
    }
}

function Verifica_Hora2() {
    hrs = (document.forms[0].nphf.value.substring(0, 2));
    min = (document.forms[0].nphf.value.substring(3, 5));
    hrs0 = (document.forms[0].nphi.value.substring(0, 2));
    min0 = (document.forms[0].nphi.value.substring(3, 5));

    estado = "";
    if ((hrs < 00) || (hrs > 23) || (min < 00) || (min > 59)) {
        estado = "errada";
    }
    if (hrs0 > hrs) {
        estado = "errada";
    }

    if (document.forms[0].nphf.value == "") {
        estado = "errada";
    }

    if (estado == "errada") {
        document.getElementById('msg').innerHTML = "Hora final inv&aacute;lida!";
        document.forms[0].nphf.focus();
    }
}

function valida() {
    var passou = false;

    if (document.finfra.codtinfra.value == "0") {
        document.getElementById('msg').innerHTML = "O campo Tipo de infraestrutura &eacute; obrigat&oacute;rio!";
        passou = true;
        document.finfra.codtinfra.focus();
    } else
    if (document.finfra.npn.value == "") {
        document.getElementById('msg').innerHTML = "O campo Nome &eacute; obrigat&oacute;rio!";
        passou = true;
        document.finfra.npn.focus();
    } else if ((document.finfra.npc.value == "") || (document.finfra.npc.value == "0")) {
        document.getElementById('msg').innerHTML = "O campo Capacidade/Quantidade &eacute; obrigat&oacute;rio!";
        passou = true;
        document.finfra.npc.focus();
    }
    else if ((document.finfra.npr.value == "") || (document.finfra.npr.value == "0") || (document.finfra.npr.value == "0,00")) {
        document.getElementById('msg').innerHTML = "O campo &aacute;rea &eacute; obrigat&oacute;rio e deve ser um n&uacute;mero!";
        passou = true;
        document.finfra.npr.focus();
    } else if (document.finfra.pad.value == 0) {
        document.getElementById('msg').innerHTML = "Selecione o tipo de utiliza&ccedil;&atilde;o da infraestrutura: &agrave; dist&acirc;ncia ou presencial!";
        passou = true;
        document.finfra.pad.focus();
    }
    if (passou)
        return false;
    else
        return true;

}

function direciona(botao) {
    switch (botao) {
        case 1:
            if (valida()) {
                document.getElementById('msg').innerHTML = " ";
                document.finfra.action = "?modulo=infra&acao=opinfra";
                document.finfra.submit();
            }
            break;
        case 2:
            document.finfra.action = "?modulo=infra&acao=incluiinfra";
            document.finfra.submit();
            break;
    }

}
function mascaradec(valor1) {
    //var texto = document.getElementById("valor1").value;
    var texto = valor1;
    var RegExp = /^\d{1,4}(\.\d{4})*\,\d{2}$/;

    if (RegExp.test(texto) == true) {
        document.getElementById('msg').innerHTML = "";

    } else {
        document.finfra.npr.value = "";
        document.finfra.npr.focus();
        document.getElementById('msg').innerHTML = "Formato inv&aacute;lido para o campo &Aacute;rea!";


    }
}
function SomenteNumero(e) {
    var tecla = (window.event) ? event.keyCode : e.which;
    // 0 a 9 em ASCII
    if ((tecla > 47 && tecla < 58)) {
        document.getElementById('msg').innerHTML = " ";
        return true;
    } else {
        if (tecla == 8 || tecla == 0) {
            document.getElementById('msg').innerHTML = " ";
            return true;// Aceita tecla tab
        } else {
            document.getElementById('msg').innerHTML = "O campo deve conter n&uacute;mero.";
            return false;
        }
    }
}

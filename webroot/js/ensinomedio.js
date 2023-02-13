function valida() {
    var passou = false;
    var em1 = document.emedio.elements["em[]"];
    var ea1 = document.emedio.elements["ea[]"];
    var er1 = document.emedio.elements["er[]"];
    var pe1 = document.emedio.elements["p1[]"];
    var pe2 = document.emedio.elements["p2[]"];
    for (var i = 0; i < em1.length; i++) {
        var m = parseInt(em1[i].value, 10);
        var a = parseInt(ea1[i].value, 10);
        var r = parseInt(er1[i].value, 10);
        var temp1 = pe1[i].value;
        var temp2 = pe2[i].value;

        var p1 = parseFloat(temp1.replace(",", "."));
        var p2 = parseFloat(temp2.replace(",", "."));



        if ((isNaN(a)) || (isNaN(r)) || (isNaN(m))) {
            document.getElementById('msg').innerHTML = "Preencha todos os campos com n&uacute;meros!";
            passou = true;
        } else if ((p1 < 0) || (p2 < 0)) {
            document.getElementById('msg').innerHTML = "H&aacute; campos de percentuais negativos.";
            passou = true;
        }
        if (passou) {
            break;
        }
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
        if (tecla == 8 || tecla == 0) {
            document.getElementById('msg').innerHTML = " ";
            return true;//Aceita tecla tab
        }
        else {
            document.getElementById('msg').innerHTML = "O campo deve conter apenas n&uacute;mero.";
            return false;
        }
    }
}
function direciona(botao) {
    switch (botao) {
        case 1:
            if (valida()) {
                document.getElementById('emedio').action = "?modulo=ensinomedio&acao=opemedio";
                document.getElementById('emedio').submit();
            }
            break;
        case 2:
            document.getElementById('emedio').action = "../saida/saida.php";
            document.getElementById('emedio').submit();
            break;
        case 3:
            document.getElementById('emedio').action = "?modulo=ensinomedio&acao=altemedio";
            document.getElementById('emedio').submit();
            break;
    }
}


function calcula(v1, v2) {
    if (v1 == 0) {
        resultado = 0;
    } else if ((v1 == 0) && (v2 == 0)) {
        resultado = 0;
    } else {
        resultado = (v2 / v1) * 100;
    }
    return resultado.toFixed(2);
}

function percentagem() {
    var em1 = document.emedio.elements["em[]"];
    var ea1 = document.emedio.elements["ea[]"];
    var er1 = document.emedio.elements["er[]"];
    var p1 = document.emedio.elements["p1[]"];
    var p2 = document.emedio.elements["p2[]"];
    for (var i = 0; i < em1.length; i++) {
        var m = parseInt(em1[i].value, 10);
        var a = parseInt(ea1[i].value, 10);
        var r = parseInt(er1[i].value, 10);
        if ((!isNaN(m)) && (!isNaN(a))) {
            if (parseInt(ea1[i].value, 10) > parseInt(em1[i].value, 10)) {
                document.getElementById('msg').innerHTML = "N&uacute;mero de aprovados deve ser menor ou igual ao n&uacute;mero de matriculados!";
            }
        }
        if ((!isNaN(a)) && (!isNaN(a)) && (!isNaN(r))) {
            if (a + r > m) {
                document.getElementById('msg').innerHTML = "O n&uacute;mero de matriculados deve ser menor ou igual a soma do n&uacute;mero de aprovados e de reprovados!";

            }
            //else {
            var resultado = calcula(m, a);
            var temp = resultado.replace(".", ",");
            p1[i].value = temp;
            resultado = calcula(m, a + r);

            //if (resultado>0){
            evasao = 100 - resultado;
            var temp1 = evasao.toFixed(2);
            p2[i].value = temp1.replace(".", ",");
            //}

            //}	
        }
    }
}
function direciona(botao) {
    switch (botao) {//Sair
        case 1:
            document.iprodsaude.action = "../saida/saida.php";
            document.iprodsaude.submit();
            break;
        case 2:
            //Gravar
            qtde = new Array(document.iprodsaude.q1.value, document.iprodsaude.q2.value, document.iprodsaude.q3.value,
                    document.iprodsaude.q4.value, document.iprodsaude.q5.value, document.iprodsaude.q6.value,
                    document.iprodsaude.q7.value, document.iprodsaude.q8.value, document.iprodsaude.q9.value,
                    document.iprodsaude.q10.value, document.iprodsaude.q11.value, document.iprodsaude.q12.value,
                    document.iprodsaude.q13.value, document.iprodsaude.q14.value, document.iprodsaude.q15.value,
                    document.iprodsaude.q16.value, document.iprodsaude.q17.value, document.iprodsaude.q18.value,
                    document.iprodsaude.q19.value, document.iprodsaude.q20.value, document.iprodsaude.q21.value,
                    document.iprodsaude.q22.value, document.iprodsaude.q23.value, document.iprodsaude.q24.value,
                    document.iprodsaude.q25.value, document.iprodsaude.q26.value, document.iprodsaude.q27.value,
                    document.iprodsaude.q28.value, document.iprodsaude.q29.value, document.iprodsaude.q30.value,
                    document.iprodsaude.q31.value, document.iprodsaude.q32.value, document.iprodsaude.q33.value,
                    document.iprodsaude.q34.value, document.iprodsaude.q35.value, document.iprodsaude.q36.value,
                    document.iprodsaude.q37.value, document.iprodsaude.q38.value, document.iprodsaude.q39.value,
                    document.iprodsaude.q40.value, document.iprodsaude.q41.value, document.iprodsaude.q42.value,
                    document.iprodsaude.q43.value, document.iprodsaude.q44.value, document.iprodsaude.q45.value,
                    document.iprodsaude.q46.value, document.iprodsaude.q47.value, document.iprodsaude.q48.value,
                    document.iprodsaude.q49.value, document.iprodsaude.q50.value, document.iprodsaude.q51.value,
                    document.iprodsaude.q52.value, document.iprodsaude.q53.value, document.iprodsaude.q54.value,
                    document.iprodsaude.q55.value, document.iprodsaude.q56.value);

            passou = false;
            soma = 0;
            for (var i = 0; i < qtde.length; i++) {
                if (!isNaN(parseInt(qtde[i])))
                    soma += parseInt(qtde[i]);
                else
                    passou = true;
            }//for

            if (!passou) {
                document.iprodsaude.action = "?modulo=prodsaude&acao=oppsaude";
                document.iprodsaude.submit();
            } else {
                document.getElementById('msg').innerHTML = "Preencha os campos com números. Não deixe campos em branco.";
            }
            break;
        case 3:
            document.iprodsaude.action = "?modulo=prodsaude&acao=altpsaude";
            document.iprodsaude.submit();
            break;

    }

}

function soma1(tabela) {
    var soma = 0, total = 0;

    if (tabela == 1) {
        qtde = new Array(document.iprodsaude.q1.value, document.iprodsaude.q2.value, document.iprodsaude.q3.value,
                document.iprodsaude.q4.value, document.iprodsaude.q5.value, document.iprodsaude.q6.value,
                document.iprodsaude.q7.value);
    }
    if (tabela == 2) {
        qtde = new Array(document.iprodsaude.q8.value, document.iprodsaude.q9.value, document.iprodsaude.q10.value,
                document.iprodsaude.q11.value, document.iprodsaude.q12.value, document.iprodsaude.q13.value,
                document.iprodsaude.q14.value, document.iprodsaude.q15.value, document.iprodsaude.q16.value,
                document.iprodsaude.q17.value, document.iprodsaude.q18.value);
    }
    if (tabela == 3) {
        qtde = new Array(document.iprodsaude.q19.value, document.iprodsaude.q20.value, document.iprodsaude.q21.value,
                document.iprodsaude.q22.value, document.iprodsaude.q23.value, document.iprodsaude.q24.value,
                document.iprodsaude.q25.value);
    }
    if (tabela == 4) {
        qtde = new Array(document.iprodsaude.q26.value, document.iprodsaude.q27.value, document.iprodsaude.q28.value,
                document.iprodsaude.q29.value, document.iprodsaude.q30.value, document.iprodsaude.q31.value,
                document.iprodsaude.q32.value);
    }

    if (tabela == 5) {
        qtde = new Array(document.iprodsaude.q33.value, document.iprodsaude.q34.value, document.iprodsaude.q35.value,
                document.iprodsaude.q36.value, document.iprodsaude.q37.value, document.iprodsaude.q38.value,
                document.iprodsaude.q39.value, document.iprodsaude.q40.value, document.iprodsaude.q41.value,
                document.iprodsaude.q42.value, document.iprodsaude.q43.value, document.iprodsaude.q44.value,
                document.iprodsaude.q45.value, document.iprodsaude.q46.value, document.iprodsaude.q47.value);
    }
    for (var i = 0; i < qtde.length; i++) {
        if (!isNaN(parseInt(qtde[i]))) {
            soma += parseInt(qtde[i]);
        }
    }


    if (tabela == 1) {
        document.getElementById('totaltab1').innerHTML = soma;
        document.iprodsaude.t1.value = soma;
        qtde = new Array(document.iprodsaude.t1.value, document.iprodsaude.t2.value, document.iprodsaude.t3.value,
                document.iprodsaude.t4.value, document.iprodsaude.t5.value);
        for (var i = 0; i < qtde.length; i++) {
            if (!isNaN(parseInt(qtde[i]))) {
                total += parseInt(qtde[i]);
            }
        }
        document.getElementById('totalgeral').innerHTML = total;
    }
    if (tabela == 2) {
        document.getElementById('totaltab2').innerHTML = soma;
        document.iprodsaude.t2.value = soma;
        qtde = new Array(document.iprodsaude.t1.value, document.iprodsaude.t2.value, document.iprodsaude.t3.value,
                document.iprodsaude.t4.value, document.iprodsaude.t5.value);
        for (var i = 0; i < qtde.length; i++) {
            if (!isNaN(parseInt(qtde[i]))) {
                total += parseInt(qtde[i]);
            }
        }
        document.getElementById('totalgeral').innerHTML = total;
    }
    if (tabela == 3) {
        document.getElementById('totaltab3').innerHTML = soma;
        document.iprodsaude.t3.value = soma;
        qtde = new Array(document.iprodsaude.t1.value, document.iprodsaude.t2.value, document.iprodsaude.t3.value,
                document.iprodsaude.t4.value, document.iprodsaude.t5.value);
        for (var i = 0; i < qtde.length; i++) {
            if (!isNaN(parseInt(qtde[i]))) {
                total += parseInt(qtde[i]);
            }
        }
        document.getElementById('totalgeral').innerHTML = total;
    }

    if (tabela == 4) {
        document.getElementById('totaltab4').innerHTML = soma;
        document.iprodsaude.t4.value = soma;
        qtde = new Array(document.iprodsaude.t1.value, document.iprodsaude.t2.value, document.iprodsaude.t3.value,
                document.iprodsaude.t4.value, document.iprodsaude.t5.value);
        for (var i = 0; i < qtde.length; i++) {
            if (!isNaN(parseInt(qtde[i]))) {
                total += parseInt(qtde[i]);
            }
        }
        document.getElementById('totalgeral').innerHTML = total;
    }

    if (tabela == 5) {
        document.getElementById('totaltab5').innerHTML = soma;
        document.iprodsaude.t5.value = soma;
        qtde = new Array(document.iprodsaude.t1.value, document.iprodsaude.t2.value, document.iprodsaude.t3.value,
                document.iprodsaude.t4.value, document.iprodsaude.t5.value);
        for (i = 0; i < qtde.length; i++) {
            if (!isNaN(parseInt(qtde[i]))) {
                total += parseInt(qtde[i]);
            }
        }
        document.getElementById('totalgeral').innerHTML = total;
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
            document.getElementById('msg').innerHTML = "Todos os campos devem conter apenas n�meros.";
            return false;
        }
    }
}

function exibe() {
    if (document.iprodsaude.check1.checked) {
        document.getElementById('labmico').style.display = "block";

        document.getElementById('labderma').style.display = "none";
        document.getElementById('servamb').style.display = "none";
        document.getElementById('enfer').style.display = "none";
        document.getElementById('pato').style.display = "none";
        document.iprodsaude.check1.checked = false;
    } else {
        document.getElementById('labmico').style.display = "none";
    }

    if (document.iprodsaude.check2.checked) {
        document.getElementById('labderma').style.display = "block";

        document.getElementById('labmico').style.display = "none";
        document.getElementById('servamb').style.display = "none";
        document.getElementById('enfer').style.display = "none";
        document.getElementById('pato').style.display = "none";
        document.iprodsaude.check2.checked = false;

    } else {
        document.getElementById('labderma').style.display = "none";
    }

    if (document.iprodsaude.check3.checked) {
        document.getElementById('servamb').style.display = "block";

        document.getElementById('labmico').style.display = "none";
        document.getElementById('labderma').style.display = "none";
        document.getElementById('enfer').style.display = "none";
        document.getElementById('pato').style.display = "none";

        document.iprodsaude.check3.checked = false;
    } else {
        document.getElementById('servamb').style.display = "none";
    }

    if (document.iprodsaude.check4.checked) {
        document.getElementById('enfer').style.display = "block";

        document.getElementById('labmico').style.display = "none";
        document.getElementById('labderma').style.display = "none";
        document.getElementById('servamb').style.display = "none";
        document.getElementById('pato').style.display = "none";

        document.iprodsaude.check4.checked = false;
    } else {
        document.getElementById('enfer').style.display = "none";
    }

    if (document.iprodsaude.check5.checked) {
        document.getElementById('pato').style.display = "block";

        document.getElementById('labmico').style.display = "none";
        document.getElementById('labderma').style.display = "none";
        document.getElementById('servamb').style.display = "none";
        document.getElementById('enfer').style.display = "none";

        document.iprodsaude.check5.checked = false;
    } else {
        document.getElementById('pato').style.display = "none";
    }
}

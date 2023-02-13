<?php
$sessao = $_SESSION["sessao"];
//if ($sessao->getUnidadeResponsavel()>1) { 
//    echo Utils::makeBlockWarning();
//} else { ?>
<?php 
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[6]) {
    header("Location:index.php");
} else {
    $sessao = $_SESSION["sessao"];
    $nomeunidade = $sessao->getNomeunidade();
    $codunidade = $sessao->getCodunidade();
    $anobase = $sessao->getAnobase();
    require_once('dao/microsDAO.php');
    require_once('classes/micros.php');
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);
    $micros = array();
    $micros = new Micros();
    $cont = 0;
    $daom = new microsDAO();
    $rows_m = $daom->buscamicrosunidade($codunidade, $anobase);
    foreach ($rows_m as $row) {
        $cont++;
        $unidade->adicionaItemMicros($row['Codigo'], $row['QtdeAcad'], $row['QtdeAcadInt'], $row['QtdeAdm'], $row['QtdeAdmInt'], $anobase);
    }
    $daom->fechar();
    if ($cont > 0) {
        Utils::redirect('micros', 'consultamicros');
        $cadeia = "location:consultamicros.php";
    }
}
?>

<script language='javascript'>
    function Soma() {
        var soma = 0;
        var somaadm = 0;
        qtde = new Array(document.gravarMicros.qtdAdm.value, document.gravarMicros.qtdAdmi.value,
                document.gravarMicros.qtdAca.value, document.gravarMicros.qtdAcai.value);

        for (var i = 0; i < qtde.length; i++) {
            if (!isNaN(parseInt(qtde[i]))) {
                soma += parseInt(qtde[i]);
                if (i == 1) {
                    document.getElementById('totaladm').innerHTML = soma;
                    somaadm = soma;
                }
                if (i == 3) {
                    document.getElementById('totalacad').innerHTML = soma - somaadm;
                }
            }
        }
        document.getElementById('totalgeral').innerHTML = soma;
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

    function direciona() {
        if (valida()) {
            document.getElementById('gravarMicros').action = "?modulo=micros&acao=opmicros";
            document.getElementById('gravarMicros').submit();
        }
    }

    function teste() {
        if (document.gravarMicros.AI.checked) {
            document.gravarMicros.acesso.value = "S";
        }
        else
            document.gravarMicros.acesso.value = "N";
    }

    function valida() {
        var passou = true;
        if (document.gravarMicros.qtdAca.value == "") {
            document.getElementById('msg').innerHTML = "O campo Micros de uso acadêmico é obrigatório.";
            document.gravarMicros.qtdAca.focus();
        }
        else if (document.gravarMicros.qtdAdm.value == "") {
            document.getElementById('msg').innerHTML = "O campo Micros de uso administrativo é obrigatório.";
            document.gravarMicros.qtdAdm.focus();
        } else
            passou = false;

        if (passou) {
            return false;
        }
        else {
            return true;
        }
    }
</script>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
		    <li class="active">Computadores</li>
		</ul>
	</div>
    <div class="ui-widget">
        <div class="ui-state-highlight ui-corner-all" style="padding:  .7em;">
            <p>
                <span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
                <strong>Dicas:</strong>
            </p>
            <p>(*) A unidade que utilizar notebooks de propriedade dos alunos como material de acompanhamento dos cursos, deverá incluí-los neste campo</p>
            <p> O número de microcomputadores de uso acadêmico + número de microcomputadores na administração deverá ser igual ao número total de microcomputadores da unidade. Observe também que o número de micros com a cesso à internet já está contido no número total de microcomputador</p>
            <p>A digitação do número de microcomputadores de uso acadêmico e o nº de micros na administração deverá ser obrigatória, neste caso até o zero é válido e deverá ser informado.</p>
        </div>
    </div>
    <br>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"> Computadores com/sem acesso &agrave; Internet</h3>         
    </div>
    <form class="form-horizontal" name="gravarMicros" id="gravarMicros" method="post">
        <table class="card-body">
            <div class="msg" id="msg"></div>
            <tr>
                <th></th>
                <th>Qtde. com Internet</th>
                <th>Qtde. sem Internet</th>
                <th>Totais</th>
            </tr>
            <tr>
                <td>Uso Acad&ecirc;mico</td>
                <td align="center"><input class="form-control"type="text" name="qtdAcai" size="5" maxlength="5" value='' onchange="Soma()" onkeypress="return SomenteNumero(event);" /></td>
                <td align="center"><input class="form-control"type="text" name="qtdAca" size="5" maxlength="5" value='' onchange="Soma()" onkeypress="return SomenteNumero(event);" /></td>
                <td align="center"><b id='totalacad'></b></td>
            </tr>
            <tr>
                <td>Uso Administrativo</td>
                <td align="center"><input class="form-control"type="text" name="qtdAdmi" onchange="Soma();"
                        maxlength="5" size="5" value=''
                        onkeypress="return SomenteNumero(event);" /></td>
                <td align="center"><input class="form-control"type="text" name="qtdAdm" onchange="Soma();"
                        maxlength="5" size="5" value=''
                        onkeypress="return SomenteNumero(event);" /></td>
                <td align="center"><b id='totaladm'></b></td>

            </tr>
            <tr>
                <td>Total Geral</td>
                <td></td><td></td>
                <td align="center"><b id='totalgeral'></b></td>
            </tr>
            <tr>
                <td colspan="4" align="center">
                    <br> 
                    <input class="form-control"name="operacao" type="hidden" value="I" />
                    <input type="button" class="btn btn-info" onclick="direciona();" id="gravar" value="Gravar" />
                </td>
            </tr>
        </table>
    </form>
</div>

<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[13]) {
    header("Location:index.php");
} else {
//    $sessao = $_SESSION["sessao"];
    $nomeunidade = $sessao->getNomeunidade();
    $codunidade = $sessao->getCodunidade();
//	$responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();

//	require_once('../../includes/dao/PDOConnectionFactory.php');

    require_once('dao/praticajuridicaDAO.php');
    require_once('classes/praticajuridica.php');

    require_once('dao/tipopraticajuridicaDAO.php');
    require_once('classes/tipopraticajuridica.php');

//	require_once('../../includes/classes/unidade.php');
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);

    $tipospj = array();
    $daotpj = new TipopraticajuridicaDAO();
    $daopj = new PraticajuridicaDAO();
    $cont = 0;
    $rows_tpj = $daotpj->Lista();
    foreach ($rows_tpj as $row) {
        $cont++;
        $tipospj[$cont] = new Tipopraticajuridica();
        $tipospj[$cont]->setCodigo($row['Codigo']);
        $tipospj[$cont]->setNome($row['Nome']);
    }

    $tamanho = count($tipospj);
    $cont1 = 0;
    $rows_pj = $daopj->buscapjunidade($codunidade, $anobase);
    foreach ($rows_pj as $row) {
        //foreach ($rows_tea as $row1){
        $tipo = $row['Tipo'];
        for ($i = 1; $i <= $tamanho; $i++) {
            if ($tipospj[$i]->getCodigo() == $tipo) {
                $tipospj[$i]->criaPraticajuridica($row["Codigo"], $unidade, $anobase, $row["Quantidade"]);
                $cont1++;
            }
        }
    }

    $daopj->fechar();

    if ($cont1 == 0) {
        $cadeia = "location:incluipjuridica.php";
        header($cadeia);
    }
}
//ob_end_flush();
?>
<script type="text/javascript">
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
                document.getElementById('msg').innerHTML = "O campo deve conter apenas n&uacute;mero.";
                return false;
            }
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

    function Soma() {
        var soma = 0;
        qtde = new Array(document.pj.qtdAcoes.value, document.pj.qtdAudiencias.value,
                document.pj.qtdAtendimentos.value, document.pj.qtdOutros.value);
        for (var i = 0; i < qtde.length; i++) {
            if (!isNaN(parseInt(qtde[i]))) {
                soma += parseInt(qtde[i]);
            }
        }

        document.getElementById('totalgeral').innerHTML = soma;
    }
    function direciona(botao) {
        if (valida()) {
            document.getElementById('pj').action = "?modulo=praticajuridica&acao=oppraticajuridica";
            document.getElementById('pj').submit();
        }
    }
    function valida() {
        var passou = false, teste;
        if (document.pj.qtdAcoes.value == "") {
            passou = true;
            document.getElementById('msg').innerHTML = "O campo A&ccedil;&otilde;es &eacute; obrigat&oacute;rio.";
        } else if (document.pj.qtdAudiencias.value == "") {
            passou = true;
            document.getElementById('msg').innerHTML = "O campo Audi&ecirc;ncias &eacute; obrigat&oacute;rio.";
        } else if (document.pj.qtdAtendimentos.value == "") {
            passou = true;
            document.getElementById('msg').innerHTML = "O campo Atendimentos &eacute; obrigat&oacute;rio.";
        } else if (document.pj.qtdOutros.value == "") {
            passou = true;
            document.getElementById('msg').innerHTML = "O campo Outros &eacute; obrigat&oacute;rio.";
        }

        if (passou) {
            return false;
        }
        else {
            return true;
        }
    }
    window.onload = Soma();
</script>
<form class="form-horizontal" name="pj" id="pj" method="post">
    <br /><h3 class="card-title"> Pr&aacute;ticas Jur&iacute;dicas </h3><br />
    <div class="msg" id="msg"></div>
    <table>
            <tr style="font-style:italic;">
                <td>Itens</td>
                <td>Quantidade</td>
            </tr>
        <tbody>
            <tr>
                <td>A&ccedil;&otilde;es Ajuizadas</td>
                <td><input class="form-control"type="text" name="qtdAcoes" size="5" maxlength="4" onkeydown="TABEnter();"
                           value='<?php print $tipospj[1]->getPraticajuridica()->getQuantidade(); ?>'
                           onchange="Soma();" onkeypress='return SomenteNumero(event)' />
                </td>
            </tr>
            <tr>
                <td>Audi&ecirc;ncias</td>
                <td><input class="form-control"type="text" name="qtdAudiencias" size="5" maxlength="4" onkeydown="TABEnter();"
                           value='<?php print $tipospj[2]->getPraticajuridica()->getQuantidade(); ?>'
                           onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
            </tr>
            <tr>
                <td>Atendimento &agrave; Comunidade Carente</td>
                <td><input class="form-control"type="text" name="qtdAtendimentos" size="5" maxlength="4" onkeydown="TABEnter();"
                           value='<?php print $tipospj[3]->getPraticajuridica()->getQuantidade(); ?>'
                           onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
            </tr>
            <tr>
                <td>Outros Atendimentos</td>
                <td><input class="form-control"type="text" name="qtdOutros" size="5" maxlength="4" onkeydown="TABEnter();"
                           value='<?php print $tipospj[4]->getPraticajuridica()->getQuantidade(); ?>'
                           onchange="Soma();" onkeypress='return SomenteNumero(event)' /><br />
                </td>
            </tr>
            <tr style="font-style:italic;">
                <td>Total Geral</td>
                <td><b id='totalgeral'></b>
                </td>
            </tr>
        </tbody>
    </table>
    <input type="button" onclick='direciona(1);' value="Gravar" class="btn btn-info"/>
</form>
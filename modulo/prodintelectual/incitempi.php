<?php
//require_once('../../includes/classes/sessao.php');
session_start();
if (!isset($_SESSION["sessao"])) {
    header("location:../../index.php");
    exit();
}
$sessao = $_SESSION["sessao"];
//$nomeunidade = $sessao->getNomeUnidade();
//$codunidade = $sessao->getCodUnidade();
//$responsavel = $sessao->getResponsavel();
//$anobase = $sessao->getAnoBase();
?>
<script type="text/javascript">
    function valida() {
        var passou = true;
        if (document.pre.itempi.value == "") {
            document.getElementById('msg').innerHTML = "O campo Nome do item &eacute; obrigat&oacute;rio.";
            document.pre.Nome.focus();
            return false;
        }
        else {
            return true;
        }
    }

    function direciona(botao) {
        switch (botao) {
            case 1:
                if (valida()) {
                    document.forms[0].action = "?modulo=prodintelectual&acao=opitempi";
                    document.forms[0].submit();
                }
                break;
            case 2:
                document.forms[0].action = "?modulo=prodintelectual&acao=incitempi";
                document.forms[0].submit();
                break;
        }
    }
</script>
<form class="form-horizontal" name="pre" method="post">
    <h3 class="card-title"> Produ&ccedil;&atilde;o intelectual</h3>
    <div class="msg" id="msg"></div>
    <table width="700px">
        <tr>
            <td>Nome do Item</td>
            <td><input class="form-control"type="text" name="itempi" maxlength="90" size="90" value='' /></td>
        </tr>
    </table>
    <input class="form-control"type="hidden" name="operacao" value="I" />
    <input type="button" onclick="direciona(1);" value="Gravar" class="btn btn-info"/>
</form>
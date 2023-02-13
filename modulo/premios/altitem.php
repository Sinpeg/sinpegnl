<?php
//require_once('../../includes/classes/sessao.php');
session_start();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[5]) {
    header("Location:index.php");
} else {
//	require_once('../../includes/dao/PDOConnectionFactory.php');
    require_once('classes/tppremios.php');
    require_once ('dao/tppremiosDAO.php');;
    $sessao = $_SESSION["sessao"];
    $codigo = $_GET["codigo"];
    if (is_numeric($codigo) && $codigo != "") {
        $cont = 0;
        $daop = new TppremiosDAO();
        $rows_p = $daop->buscaPorCodigo($codigo);
        foreach ($rows_p as $row) {
            $item = new Tppremios();
            $item->setCodpremio($codigo);
            $item->setNome($row['Nome']);
        }
    }

    $daop->fechar();
}
//ob_end_flush();
?>
<script type="text/javascript">
    function valida() {
        var passou = true;
        if (document.pre.itempi.value == "") {
            document.getElementById('msg').innerHTML = "O campo Nome de Item &eacute; obrigat&oacute;rio.";
            document.pre.Nome.focus();
            return false;
        }
        else {
            return true;
        }
    }
    function direciona(botao) {

        if (valida()) {
            document.forms[0].action = "?modulo=premios&acao=opitem";
            document.forms[0].submit();
        }
    }
</script>
</head>
<form class="form-horizontal" name="pre" method="post">
    <h3 class="card-title"> Produ&ccedil;&atilde;o intelectual</h3>
    <table>
        <tr>
            <td>Nome do Item</td>
            <td><input class="form-control"type="text" name="itempi" maxlength="90" size="90"
                       value='<?php echo $item->getNome(); ?>' /></td>
        </tr>

    </table>
    <input class="form-control"name="operacao" type="hidden" value="A" />
     <input class="form-control"type="hidden"   name="codigo" value="<?php print $codigo; ?>" /> 
     <input type="button"   onclick='direciona(1);' value="Gravar" />
</form>
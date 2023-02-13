<?php
session_start();
if (!isset($_SESSION["sessao"])) {
    header("location:index.php");
}
$sessao = $_SESSION["sessao"];
$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
$codusuario = $sessao->getCodusuario();
$data = $sessao->getData();
$codigo = htmlentities($_GET['codigo']);
$permissao = true;

if (is_numeric($codigo) && $codigo != "") {
    $dao = new ArquivoDAO();
    $usuario = new Usuario();
    $usuario->setCodusuario($codusuario);
    $usuario->setResponsavel($responsavel);
    $i = 0;
    $rows = $dao->buscaporCodigo($codigo);
    foreach ($rows as $row) {
        if ($row["Codusuario"] != $codusuario) {
            $permissao = false;
        }
        $i++;
        $usuario->criaArquivo($codigo, $row['Assunto'], null, $row['Nome'], null, null, $row['Comentario'], $row['DataAlteracao'], $row['DataInclusao'], $anobase);
    }
    $selecionado1 = "";
    $selecionado2 = "";
    if ($usuario->getArquivo()->getAssunto() == 1) {
        $selecionado1 = "selected=selected";
    } else {
        $selecionado2 = "selected=selected";
    }
}
if ($permissao==false) {
    Error::addErro("Você não possui permissão para acessar este arquivo ou este não existe!");
    Utils::redirect("uparquivo", "consultaarqs");
}
else {
?>
<script language="Javascript">
    function direciona(botao) {

        document.getElementById('farquivo').action = "index.php?modulo=uparquivo&acao=oparquivo";
        document.getElementById('farquivo').submit();


    }
    function verificaextensao() {
        var extensoesOk = ",.zip,";

        var extensao = "," + document.farquivo.userfile.value.substr(document.farquivo.userfile.value.length - 4).toLowerCase() + ",";
        if (document.farquivo.userfile.value == "")
        {
            document.getElementById('msg').innerHTML = "O campo do arquivo est&aacute; vazio!!";
            return false;
        }
        else if (extensoesOk.indexOf(extensao) == -1)
        {
            document.getElementById('msg').innerHTML = "Envie arquivos compactados (extens&atilde;o .zip).";
            return false;
        }
        return true;
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
</script>
<form class="form-horizontal" name="farquivo" id="farquivo" enctype="multipart/form-data" method="post">
    <input class="form-control"name="codigo" type="hidden"	value="<?php echo $usuario->getArquivo()->getCodigo(); ?>" />
    <h3 class="card-title">Envio de arquivo</h3>
    <table >
        <tr>
            <td witdh="200px">Assunto</td>
            <td witdh="400px"><select class="custom-select" name="assunto">
                    <option	   <?php print $selecionado1; ?> value="1">
                        Apresenta&ccedil;&atilde;o do Relat&oacute;rio de Atividades</option>
                    <option <?php print $selecionado2; ?> value="2">
                        Outros</option>
                </select>
            </td>
        </tr>
        <tr>

            <?php if ($usuario->getArquivo()->getDataalteracao() == "") { ?>
                <td>Data de Inclus&atilde;o</td>
                <td> <?php
                    $data1 = explode('-', $usuario->getArquivo()->getDatainclusao());
                    $data = $data1[2] . '/' . $data1[1] . '/' . $data1[0];

                    print $data;
                } else {
                    ?></td>
                <td>Data de Altera&ccedil;&atilde;o</td><td>
                    <?php
                    $data1 = explode('-', $usuario->getArquivo()->getDataalteracao());
                    $data = $data1[2] . '/' . $data1[1] . '/' . $data1[0];

                    print $data;
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>Coment&aacute;rio</td>
            <td><input class="form-control"type="text" name="comentario"
                       value="<?php print $usuario->getArquivo()->getComentario(); ?>"
                       size="73" maxlenght="70"/></td>
        </tr>

        <tr>
            <td>
                Arquivo</td>
            <td><input class="form-control" class="custom-file-input" type="file" name="userfile" id="userfile" /></td>
        </tr>
    </table>
    <input class="form-control"name="operacao" type="hidden" value="A" />
    <input	type="button" onclick="direciona(1);" value="Enviar" />
</form>
<?php } ?>
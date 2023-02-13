<?php
ob_start();
echo ini_get('display_errors');
if (!ini_get('display_errors')) {
 ini_set('display_errors', 1);
 ini_set('error_reporting', E_ALL & ~E_NOTICE);
}
?>
<?php
if (!isset($_SESSION["sessao"])) {
    header("location:index.php");
}
$sessao = $_SESSION["sessao"];
//$nomeunidade = $sessao->getNomeUnidade();
$codunidade = $sessao->getCodUnidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();

$usuarios = array();
$dao = new ArquivoDAO();
$i = 0;
$rows = $dao->buscaUnidade($anobase, $codunidade);
foreach ($rows as $row) {
    $i++;
    $usuarios[$i] = new Usuario();
    $usuarios[$i]->setCodusuario($row['CodUsuario']);
    $usuarios[$i]->setResponsavel($row['Responsavel']);
    $usuarios[$i]->adicionaItemArquivo($row['Codigo'], null, null, $row['Nome'], null, null, $row['Comentario'], null, $row['DataInclusao'], $anobase);
}
//$i ? o tamanho do vetor usuarios
$dao->fechar();


if ($i == 2) {
    $cadeia = "location:consultaarqs.php";
    header($cadeia);
}
?>
<script language="JavaScript">
            function direciona(botao) {
                switch (botao) {
                    case 1:
                        if (verificaextensao()) {
                            document.getElementById('farquivo').action = "index.php?modulo=uparquivo&acao=oparquivo";
                            document.getElementById('farquivo').submit();
                        }
                        break;
                    case 2:
                        document.getElementById('farquivo').action = "../saida/saida.php";
                        document.getElementById('farquivo').submit();
                        break;
                }
            }
            function verificaextensao() {
                var extensoesOk = ",.rar,.zip,";

                var extensao = "," + document.farquivo.userfile.value.substr(document.farquivo.userfile.value.length - 4).toLowerCase() + ",";
                if (document.farquivo.userfile.value == "")
                {
                    document.getElementById('msg').innerHTML = "O campo do arquivo est&aacute; vazio!!";
                    return false;
                }
                else if (extensoesOk.indexOf(extensao) == -1)
                {
                    document.getElementById('msg').innerHTML = "Envie arquivos compactados (extens&atilde;o .rar ou .zip).";
                    return false;
                }
                return true;
            }
        </script>
        <form class="form-horizontal" name="farquivo" id="farquivo" enctype="multipart/form-data"
              method="post">
            <h3 class="card-title">Envio de arquivo</h3>
            <div class="msg" id="msg"></div>

            <table>
                <tr>
                    <td>Assunto</td>
                    <td><select class="custom-select" name="assunto">
                            <option value="1">Apresentação do Relatório de Atividades</option>
                            <option value="2">Outros</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Comentário</td>
                    <td><input class="form-control"type="text" name="comentario" maxlength="70" size="73" />
                    </td>
                </tr>
                <tr>
                    <td>Arquivo</td>
                    <td><input class="form-control" class="custom-file-input" type="file" name="userfile" id="userfile" /></td>
                </tr>
            </table>

            <input type="button" onclick="direciona(1);" value="Enviar arquivo" class="btn btn-info"/>
            <input class="form-control"name="operacao" type="hidden" value="I" />
        </form>

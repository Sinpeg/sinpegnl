<?php
ob_start();
// ini_get('display_errors','on');
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

<style>
    #teste { position:relative; }
    #upload { position:absolute; top:0;left:0; border:1px solid #ff0000; opacity:0.01; z-index:1; }
</style>

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

<script>  
    $(function(){
        $('#upload').on('change',function(){
            var numArquivos = $(this).get(0).files.length;
            
                $('#texto').val( $(this).val() );
            
        });
    });
</script>
<div class="bs-example">
    <ul class="breadcrumb">
        <li class="active">Enviar arquivo</li>
    </ul>
</div>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Envio de arquivo</h3>
    </div>
    <form class="form-horizontal" name="farquivo"  enctype="multipart/form-data" id="farquivo" method="post">
        <table class="card-body">
            <div class="msg" id="msg"></div>
            <tr>
                <td class="coluna1">Assunto</td>
            </tr>
            <tr>
                <td class="coluna2"><select class="custom-select" name="assunto">
                        <option value="1">Anexo</option>
                        <option value="2">Outros</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="coluna1">Comentário</td>
            </tr>
            <tr>
                <td class="coluna2">
                    <input class="form-control"type="text" name="comentario" maxlength="70" size="73" />
                </td>
            </tr>
            <tr>
                <td class="coluna1">Arquivo</td>
            </tr>
            <tr>
                <td>
                    <div class="input-group">
                        <div class="custom-file">
                            <input class="custom-file-input" type="file" id="upload" name="userfile" />
                            <input class="form-control" type="hidden" name="MAX_FILE_SIZE" value="10485760">
                            <input class="form-control" type="text" id="texto" />
                            <input type="button" id="botao" value="Selecionar..." class="custom-file-button"/>
                        </div>
                    </div>
                </td>
                <!-- <p style="font-style:italic; color:red;"> Modelo para o nome do arquivo pdf: ra_siglaDaUnidadeAnoBase.pdf</p></td> -->
            </tr>
            <tr>
                <td align="center" colspan=2>
                    <br>
                    <input type="button" id="enviar" onclick="direciona(1);" value="Enviar arquivo" class="btn btn-info"/>
                    <input class="form-control"name="operacao" type="hidden" value="I" />
                </td>
            </tr>
        </table>
    </form>
</div>
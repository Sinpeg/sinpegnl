<?php
//include_once ('../../../includes/dao/PDOConnectionFactory.php');
//include_once '../../../includes/classes/sessao.php';
include_once ('dao/DocumentoDAO.php');
include_once ('classe/Documento.php');
session_start();
$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();
?>
<?php
// Aplicação: Documento PDU
if (!$aplicacoes[36]) {
    print "Erro ao acessar esta aplicação";
    exit();
}
?>
<?php
$coddoc = $_GET['doc'];
$daodoc = new DocumentoDAO();
$rows = $daodoc->buscadocumento($coddoc);
$objdoc = new Documento();

foreach ($rows as $row) {
    if ($row['CodUnidade'] == $codunidade) {
        $objdoc->setCodigo($row['codigo']);
        $objdoc->setAnoFinal($row['anofinal']);
        $objdoc->setAnoInicial($row['anoinicial']);
        $objdoc->setNome($row['nome']);
        $objdoc->setMissao($row['missao']);
        $objdoc->setVisao($row['visao']);
        $objdoc->setSituacao($row['situacao']);
        $objdoc->setCodigoPDI($row['CodDocumento']);
    }
}
?>
<!--<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="../../../estilo/estilos.css" />
        <link rel="stylesheet" href="../../../estilo/msgs.css"/>
        <script type="text/javascript" src="../../../includes/scripts/jquery-1.7.2.js">
        </script>
        <script type="text/javascript" src="../../../includes/scripts/pdi.js">
        </script>-->
<script>
    $(function() {
        $('#missao').hide();
        $('#visao').hide();
    });
    var aberto = false;
    $(function() {
        $('.missao').click(function() {
            $('a.missao').text('[-] Missão');
            $('a.visao').text('[+] Visão');
            $('#visao').hide();
            if (aberto == false) {
                $('#missao').fadeIn(600, function() {
                    aberto = true;
                })
            }
        });
    });
    $(function() {
        $('.visao').click(function() {
            if (aberto == true) {
                $('a.visao').text('[-] Visão');
                $('a.missao').text('[+] Missão');
                aberto = false;
                $('#missao').hide(600, function() {
                    $('#visao').fadeIn(600, function() {
                    });
                });
            }
        });
    });
</script>
<!--        <style>
    a.missao, a.visao, a.missao:visited, a.visao:visited {
        text-decoration: none;
        color: blue;
        font-family: "Lucida Grande", Verdana, Arial, Helvetica, sans-serif;
        font-size: 13px;
    }
</style>
</head>
<body>-->
<fieldset>
    <legend>Atualizar documento</legend>
    <div id="resultado"></div>
    <form class="form-horizontal" name="adicionar" method="POST" action="ajax/documentopdu/registradoc.php">
        <div>
            <label>PDI:</label>
            <select class="custom-select" name="pdi" class="sel1">
                <option value="0">Selecione o documento...</option>
                <?php
                $daodoc = new DocumentoDAO();
                $rows = $daodoc->listanull();
                ?>
                <?php foreach ($rows as $row) { ?>
                    <option value="<?php print $row['codigo']; ?>" <?php if ($objdoc->getCodigoPDI() == $row['codigo']) {
                    print "selected";
                } ?>><?php print $row['nome']; ?></option>
<?php } ?>
                ?>
            </select>
        </div>
        <div>
            <label>Nome:</label>
            <input class="form-control"type="text" class="txt" name="nomedoc" value="<?php print $objdoc->getNome(); ?>" size="60" />
        </div>
        <div>
            <label>Ano Inicial:</label>
            <input class="form-control"type="text" class="short" name="anoinicial" value="<?php print $objdoc->getAnoInicial(); ?>" size="4" />
        </div>
        <div>
            <label>Ano Final:</label>
            <input class="form-control"type="text" class="short" name="anofinal" value="<?php print $objdoc->getAnoFinal(); ?>" size="4" />
        </div>
        <div>                     
            <span class="intermediario">Situação:</span>
            <span class="intermediario"><input class="form-control"type="radio" value="A" name="situacao" <?php
                                               if ($objdoc->getSituacao() == 'A') {
                                                   print "checked";
                                               }
                                               ?>/>ativado</span>
            <span class="intermediario"><input class="form-control"type="radio" value="D" name="situacao" <?php
                                               if ($objdoc->getSituacao() == 'D') {
                                                   print "checked";
                                               }
                                               ?> />desativado</span>
        </div>
        <div>
            <a href="#" class="missao">[+] Missão</a>
        </div>
        <div id="missao">
            <label>Missão:</label>
            <textarea class="area" name="missao" rows="5" cols="60"><?php print ($objdoc->getMissao()); ?></textarea>
        </div>
        <div>
            <a href="#" class="visao">[+] Visão</a>
        </div>
        <div id="visao">
            <label>Visão:</label>
            <textarea class="area" name="visao" rows="5" cols="60"><?php print ($objdoc->getVisao()); ?></textarea>
        </div>
        <div>
            <input type="button" value="Atualizar" name="salvar" class="btn btn-info"/>
        </div>
        <input class="form-control"type="hidden" value="<?php print $objdoc->getCodigo(); ?>" name="coddoc" />
    </form>
</fieldset>
<!--    </body>
</html>-->
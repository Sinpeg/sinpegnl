<?php
session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade();
$daometa = new MetaDAO();
$codmapa = addslashes($_GET['codmapa']);
$rows = $daometa->buscarmeta($codmapa);

$daodoc = new DocumentoDAO();
$rows = $daodoc->buscadocumentoporunidade($codunidade);
$objdoc = array();
$cont = 0;

foreach ($rows as $row) {
    $objdoc[$cont] = new Documento();
    $objdoc[$cont]->setCodigo($row['codigo']);
    $objdoc[$cont]->setNome($row['nome']);
    $cont++;
}
$daomapa = new MapaDAO();
$objmapa = new Mapa();

for ($i = 0; $i < $cont; $i++) {
    $rows = $daomapa->buscamapadocumento($objdoc[$i]->getCodigo());
    foreach ($rows as $row) {
        if ($row['Codigo'] == $codmapa) {
            $objmapa->setDocumento($objdoc[$i]);
            $objmapa->setCodigo($row['Codigo']);
            $objmapa->setOrdem($row['Ordem']);
            $objmapa->setObjetivo($row['Objetivo']);
            $objmapa->setPerspectiva($row['perspectiva']);
            $objmapa->setdescricaoObjetivo($row['DescricaoObjetivo']);
        }
    }
}
?>

<script type="text/javascript">
    $(function() {
        $('#perspectiva').hide();
        $('#descricao').hide();
    });
    var aberto = false;
    $(function() {
        $('.descricao').click(function() {
            $('a.descricao').text('[-] Descrição');
            $('a.perspectiva').text('[+] Perspectiva');
            $('#perspectiva').hide();
            if (aberto == false) {
                $('#descricao').fadeIn(600, function() {
                    aberto = true;
                });
            }
        });
    });
    $(function() {
        $('.perspectiva').click(function() {
            if (aberto == true) {
                $('a.perspectiva').text('[-] Perspectiva');
                $('a.descricao').text('[+] Descrição');
                aberto = false;
                $('#descricao').hide(600, function() {
                    $('#perspectiva').fadeIn(600, function() {
                    });
                });
            }
        });
    });
    var content_desc = '';
    var maxlength = 255;
    $(function() {
        $('textarea[name=descricao]').bind('keydown keyup', function(event) {
            lengthDesc = $('textarea[name=descricao]').val().length;
            if (maxlength - lengthDesc >= 0) {
                $('#numdesc').html("Caracteres restantes: " + (maxlength - lengthDesc));
                content_desc = $('textarea[name=descricao]').val();
            }
            $('textarea[name=descricao]').val(content_desc);
        });
    });
    var content_persp = '';
    $(function() {
        $('textarea[name=perspectiva]').bind('keydown keyup', function(event) {
            lengthPersp = $('textarea[name=perspectiva]').val().length;
            if (maxlength - lengthPersp >= 0) {
                $('#numpersp').html("Caracteres restantes: " + (maxlength - lengthPersp));
                content_persp = $('textarea[name=perspectiva]').val();
            }
            $('textarea[name=perspectiva]').val(content_persp);
        });
    });
</script>

<fieldset>
    <legend>Atualizar Objetivos Estratégicos</legend>
    <div id="resultado"></div>
    <form class="form-horizontal" name="adicionar" method="POST" action="ajax/objetivopdi/registramapa_pdi.php">
        <div>
            <label>Ordem:</label>
            <input class="form-control"type="text" class="short" name="ordem" value="<?php print $objmapa->getOrdem(); ?>" size="4"/>
        </div>
        <div>
            <label>Objetivo: </label>
            <input class="form-control"type="text" class="txt" name="objetivo_txt" value="<?php print ($objmapa->getObjetivo()); ?>" size="60"/>
        </div>
        <div>
            <label>Documento: </label>
            <select class="sel1" name="documento">
                <option value="0">Selecione o documento...</option>
                <?php for ($i = 0; $i < $cont; $i++) { ?>
                    <option value="<?php print $objdoc[$i]->getCodigo(); ?>" <?php if ($objdoc[$i]->getCodigo() == $objmapa->getDocumento()->getCodigo()) {
                    print "selected";
                } ?>><?php print $objdoc[$i]->getNome(); ?></option>
<?php } ?>

            </select>
        </div>
        <div>
            <a class="descricao" href="#">Descrição [+]</a>
        </div>
        <div id="descricao">
            <label>Descrição do objetivo: </label>
            <textarea name="descricao" class="area" rows="5" cols="60"><?php print $objmapa->getDescricaoObjetivo(); ?></textarea>
            <div id="numdesc"></div>
        </div>
        <div>
            <a class="perspectiva" href="#">Perspectiva [+]</a>
        </div>
        <div id="perspectiva">
            <label>Perspectiva: </label>
            <textarea name="perspectiva" class="area" rows="5" cols="60"><?php print $objmapa->getPerspectiva(); ?></textarea>
            <div id="numpersp"></div>
        </div>
        <div>
            <input type="button" value="Salvar" name="salvar" class="btn btn-info"/>
        </div>
        <input class="form-control"type="hidden" name="codmapa" value="<?php print $codmapa; ?>" />

    </form>
</fieldset>

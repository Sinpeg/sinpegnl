<?php
session_start();
$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();
?>
<?php
if (!$aplicacoes[41]) {
    print "Erro ao acessar esta aplicação";
    exit();
}
//exit();
?>
<?php
// BUSCA OS PDUs ASSOCIADOS
$daodoc = new DocumentoDAO();
$rows = $daodoc->buscadocumentoporunidade($codunidade);
$objdoc = array(); // documento do pdi
$cont = 0;

foreach ($rows as $row) {
    if ($row['CodDocumento'] != NULL) {
        $objdoc[$cont] = new Documento();
        $objdoc[$cont]->setCodigo($row['codigo']);
        $objdoc[$cont]->setNome($row['nome']);
        $cont++;
    }
}
// FIM
// BUSCA OS PDIs ASSOCIADOS
$daodoc1 = new DocumentoDAO();
$rows1 = $daodoc1->buscaPDI();
$objdoc1 = array(); // documento do pdi
$cont1 = 0;
foreach ($rows1 as $row1) {
    $objdoc1[$cont1] = new Documento();
    $objdoc1[$cont1]->setCodigo($row1['codigo']);
    $objdoc1[$cont1]->setNome($row1['nome']);
    $cont1++;
}
// FIM
$daodoc->fechar();
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
                })
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
    <legend>Cadastro de Objetivos Estratégicos - PDU</legend>
    <div id="resultado"></div>
    <form class="form-horizontal" name="adicionar" method="POST" action="ajax/objetivopdu/registramapa_pdu.php">
        <div>
            <label>PDU: </label>
            <select class="sel1" name="pdu">
                <option value="0">Selecione o PDU</option>
<?php for ($i = 0; $i < $cont; $i++): ?>
                    <option value="<?php print $objdoc[$i]->getCodigo(); ?>"><?php print $objdoc[$i]->getNome(); ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div>
            <label>PDI:</label>
            <select class="sel1" name="documento" id="documento">
                <option value="0">Selecione o documento...</option>
<?php for ($i = 0; $i < $cont1; $i++) { ?>
                    <option value="<?php print $objdoc1[$i]->getCodigo(); ?>"><?php print $objdoc1[$i]->getNome(); ?></option>
                <?php } ?>
            </select>
        </div>
        <!-- OBJETIVOS RELACIONADOS AO DOCUMENTO -->
        <div class="objetivo">
            <label>Objetivo: </label>
            <select class="custom-select" name='objetivo' class="sel1">
                <option value="0">Selecione o objetivo do PDI...</option>
            </select>
        </div>
        <div>
            <label>Ordem:</label>
            <input class="form-control"type="text" class="short" name="ordem" size="4" />
        </div>
        <div>
            <label>Objetivo: </label>
            <input class="form-control"type="text" class="txt" name="objetivo_txt" size="60"/>
        </div>
        <div>
            <a class="descricao" href="#">Descrição [+]</a>
        </div>
        <div id="descricao">
            <label>Descrição do objetivo: </label>
            <textarea name="descricao" class="area" rows="5" cols="60"></textarea>
            <div id="numdesc"></div>
        </div>
        <div>
            <a class="perspectiva" href="#">Perspectiva [+]</a>
        </div>
        <div id="perspectiva">
            <label>Perspectiva: </label>
            <textarea name="perspectiva" class="area" rows="5" cols="60"></textarea>
            <div id="numpersp"></div>
            <input class="form-control"type="hidden" name="action" value="I" />
        </div>
        <div>
            <input type="button" value="Salvar" name="salvar" class="btn btn-info"/>
        </div>
    </form>
</fieldset>

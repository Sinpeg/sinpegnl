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
?>
<?php
// Busca dos PDUs
$daodoc = new DocumentoDAO();
$rows = $daodoc->buscadocumentoporunidade($codunidade);
$cont = 0;
foreach ($rows as $row) {
    $objdoc[$cont] = new Documento();
    $objdoc[$cont]->setCodigo($row['codigo']);
    $objdoc[$cont]->setNome($row['nome']);
    $cont++;
}
// Fim

$codmapa = addslashes($_GET['codmapa']);
$daomapa = new MapaDAO();
$objmapa = new Mapa();
for ($i = 0; $i < $cont; $i++) {
    $rows = $daomapa->buscamapa($codmapa);
    foreach ($rows as $row) {
        if ($objdoc[$i]->getCodigo() == $row['CodigoDocumento'] && (isset($row['codObjetivoPDI']))) {
            $objmapa->setCodigo($row['Codigo']);
            $objmapa->setObjetivo($row['Objetivo']);
            $objmapa->setOrdem($row['Ordem']);
            $objmapa->setPerspectiva($row['perspectiva']);
            $objmapa->setdescricaoObjetivo($row['DescricaoObjetivo']);
            $objmapa->setDocumento($objdoc[$i]);
            $objmapa->setCodObjetivoPDI($row['codObjetivoPDI']);
        }
    }
}

//// BUSCA OS PDUs ASSOCIADOS
//$daodoc = new DocumentoDAO();
//$rows = $daodoc->buscadocumentoporunidade($codunidade);
//$objdoc = array(); // documento do pdi
//$cont = 0;
//foreach ($rows as $row) {
//    $objdoc[$cont] = new Documento();
//    $objdoc[$cont]->setCodigo($row['codigo']);
//    $objdoc[$cont]->setNome($row['nome']);
//    $cont++;
//}
//// FIM
//
// BUSCA OS PDIs ASSOCIADOS
$daodoc1 = new DocumentoDAO();
$rows1 = $daodoc1->buscaPDI();
$objdoc1 = array(); // documento do pdi
$cont1 = 0;
foreach ($rows1 as $row1) {
//    if ($row['CodDocumento'] != NULL) {
    $objdoc1[$cont1] = new Documento();
    $objdoc1[$cont1]->setCodigo($row1['codigo']);
    $objdoc1[$cont1]->setNome($row1['nome']);
    $cont1++;
//    }
}
//$codmapa = addslashes($_GET['codmapa']);
//$daomapa = new MapaDAO();
//$objmapa = new Mapa();
//$rows = $daomapa->buscamapa($codmapa);
//for ($i = 0; $i < $cont; $i++) {
//    foreach ($rows as $row) {
//        if ($objdoc[$i]->getCodigo() == $row['CodigoDocumento'] && (isset($row['codObjetivoPDI']))) {
//            $objmapa->setCodigo($row['Codigo']);
//            $objmapa->setObjetivo($row['Objetivo']);
//            $objmapa->setOrdem($row['Ordem']);
//            $objmapa->setPerspectiva($row['perspectiva']);
//            $objmapa->setdescricaoObjetivo($row['DescricaoObjetivo']);
//            $objmapa->setDocumento($objdoc[$i]);
//            $objmapa->setCodObjetivoPDI($row['codObjetivoPDI']);
//        }
//    }
//}
$daodoc->fechar();
$daomapa->fechar();
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
                <?php if ($objmapa->getCodigo() == NULL) { ?>
    <div class="erro"><?php print "O objetivo estratégico não existe ou não pertence a unidade"; ?></div>
                <?php } else { ?>
    <fieldset>
        <legend>Cadastro de Objetivos Estratégicos - PDU</legend>
        <div id="resultado"></div>
        <form class="form-horizontal" name="adicionar" method="POST" action="ajax/objetivopdu/registramapa_pdu.php">
            <div>
                <label>PDU: </label>
                <select class="sel1" name="pdu">
                    <option value="0">Selecione o PDU</option>
    <?php for ($i = 0; $i < $cont; $i++): ?>
                        <option value="<?php print $objdoc[$i]->getCodigo(); ?>"<?php
        if ($objdoc[$i]->getCodigo() == $objmapa->getDocumento()->getCodigo()) {
            print " selected";
        }
        ?>><?php print $objdoc[$i]->getNome(); ?></option>
    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label>PDI:</label>
                <select class="sel1" name="documento" id="documento">
                    <option value="0">Selecione o documento...</option>
    <?php for ($i = 0; $i < $cont1; $i++) { ?>
        <?php
        $string = "";
        $daomapa = new MapaDAO();
        $rows = $daomapa->buscamapa($objmapa->getCodObjetivoPDI());
        foreach ($rows as $row) {
            if ($row['CodigoDocumento'] == $objdoc1[$i]->getCodigo()) {
                $string = "selected";
                $codigo = $row['CodigoDocumento'];
            }
        }
        ?>
                        <option value="<?php print $objdoc1[$i]->getCodigo(); ?>" <?php print $string; ?>><?php print $objdoc1[$i]->getNome(); ?></option>
    <?php } ?>
    <?php
    $daomapa->fechar();
    ?>
                </select>
            </div>
            <div class="objetivo">
                <label>Objetivo: </label>
                <select class="custom-select" name='objetivo' class="sel1">
                    <option value="0">Selecione o objetivo do PDI...</option>
    <?php
    $daomapa = new MapaDAO();
    $rows = $daomapa->buscamapadocumento($codigo);
    ?>
    <?php foreach ($rows as $row) { ?>
                        <option value="<?php print $row['Codigo']; ?>" <?php
        if ($row['Codigo'] == $objmapa->getCodObjetivoPDI()) {
            print " selected";
        }
        ?>><?php print $row['Objetivo']; ?></option>
    <?php } ?>
                </select>
            </div>
            <div>
                <label>Ordem:</label>
                <input class="form-control"type="text" class="short" name="ordem" value="<?php print $objmapa->getOrdem(); ?>" size="4"/>
            </div>
            <div>
                <label>Objetivo: </label>
                <input class="form-control"type="text" class="txt" name="objetivo_txt" value="<?php print $objmapa->getObjetivo(); ?>" size="60" />
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
            <input class="form-control"type="hidden" name="action" value="A" />
            <input class="form-control"type="hidden" name="codmapa" value="<?php print $_GET['codmapa'] ?>" />
            <div>
                <input type="button" value="Salvar" name="salvar" class="btn btn-info"/>
            </div>
        </form>
    </fieldset>
<?php } ?>
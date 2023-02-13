<?php
session_start();
$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade(); // código da unidade
$aplicacoes = $sessao->getAplicacoes(); // código das aplicações
$anobase = $sessao->getAnobase();       // ano base
?>
<?php
session_start();
$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();
?>
<?php
if (!$aplicacoes[37]) {
    print "Erro ao acessar esta aplicação";
    exit();
}
?>
<?php
$daodoc = new DocumentoDAO();
$rows = $daodoc->buscadocumentoporunidade($codunidade);
$objdoc = array();
$cont = 0;
foreach ($rows as $row) {
    if ($row['CodDocumento'] == NULL) { // somente os documentos que estão no PDI
        $objdoc[$cont] = new Documento();
        $objdoc[$cont]->setCodigo($row['codigo']);
        $objdoc[$cont]->setNome($row['nome']);
        $cont++;
    }
}
$daomapa = new MapaDAO();
$objmapa = array();
$cont1 = 0;
for ($i = 0; $i < $cont; $i++) {
    $rows = $daomapa->buscamapadocumento($objdoc[$i]->getCodigo());
    foreach ($rows as $row) {
        $objmapa[$cont1] = new Mapa();
        $objmapa[$cont1]->setCodigo($row['Codigo']);
        $objmapa[$cont1]->setOrdem($row['Ordem']);
        $objmapa[$cont1]->setObjetivo($row['Objetivo']);
        $objmapa[$cont1]->setDocumento($objdoc[$i]);
        $cont1++;
    }
}
if ($cont1 == 0) {
    header("Location:incluimapapdi.php");
}
$daomapa->fechar();
$daodoc->fechar();
?>
<script>
    $(function() {
        $("#tablesorter").tablesorter({widthFixed: true, headers: {3: {sorter: false}}, widgets: ['zebra']}).tablesorterPager({container: $("#pager"), positionFixed: false, size: 10});
    });
</script>

<h3 class="card-title">Objetivos Estratégicos - PDI</h3>
<hr style="border-top: 1px solid #0b559b;"/>
<table id="tablesorter" class="table table-bordered table-hover">
    <tfoot>
        <tr>
            <th colspan="7" class="ts-pager form-horizontal">
                <button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
                <button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
                <span class="pagedisplay"></span> <!-- this can be any element, including an input class="form-control"-->
                <button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
                <button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
                <select class="custom-select" title="Select page size">
                    <option selected="selected" value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                </select>
                <select class="pagenum input-mini" title="Select page number"></select>
            </th>
        </tr>
    </tfoot>
    <thead>
        <tr>
            <th>Ordem</th>
            <th>Objetivo</th>
            <th>Documento</th>
            <th>Editar</th>
        </tr>
    </thead>
    <tbody>
        <?php for ($i = 0; $i < $cont1; $i++): ?>
            <tr>
                <td><?php print ($objmapa[$i]->getOrdem()); ?></td>
                <td><?php print ($objmapa[$i]->getObjetivo()); ?></td>
                <td><?php print ($objmapa[$i]->getDocumento()->getNome()); ?></td>
                <td><a href="<?php echo Utils::createLink('objetivopdi', 'editarobjetivo', array("codmapa" => $objmapa[$i]->getCodigo())); ?>" title="Editar o valor da meta"><img src="webroot/img/editar.gif"/></a></td>
            </tr>
        <?php endfor; ?>
    </tbody>
</table>
<div class="incluir">
    <span class="plus"></span><a href="<?php echo Utils::createLink('objetivopdi', 'incluimapapdi'); ?>">Incluir objetivo estratégico - PDI</a>
</div>
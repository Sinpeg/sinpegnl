<?php
session_start();
$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade(); // código da unidade
$aplicacoes = $sessao->getAplicacoes(); // código das aplicações
$anobase = $sessao->getAnobase();       // ano base
$codestruturado = $sessao->getCodestruturado();
?>
<?php
if (!$aplicacoes[39]) {
    print "Erro ao acessar esta aplicação";
    exit();
}
?>
<?php
$daounidade = new UnidadeDAO();
$rows = $daounidade->buscasubunidades00($codestruturado);
$unidade = array();
$cont = 0;
foreach ($rows as $row) {
    $unidade[$cont] = new Unidade();
    $unidade[$cont]->setCodunidade($row['CodUnidade']);
    $unidade[$cont]->setNomeunidade($row['NomeUnidade']);
    $cont++;
}
$daodoc = new DocumentoDAO();
$objdoc = array();
$cont1 = 0;
for ($i = 0; $i < $cont; $i++) {
    $daodoc = new DocumentoDAO();
    $rows = $daodoc->buscadocumentoporunidade($unidade[$i]->getCodunidade());
    foreach ($rows as $row) {
        $objdoc[$cont1] = new Documento();
        $objdoc[$cont1]->setCodigo($row['codigo']);
        $objdoc[$cont1]->setNome($row['nome']);
        $cont1++;
    }
}
$daomapa = new MapaDAO();
$objmapa = array();
$cont2 = 0;
for ($i = 0; $i < $cont1; $i++) {
    $rows = $daomapa->buscamapadocumento($objdoc[$i]->getCodigo());
    foreach ($rows as $row) {
        $objmapa[$cont2] = new Mapa();
        $objmapa[$cont2]->setCodigo($row['Codigo']);
        $objmapa[$cont2]->setObjetivo($row['Objetivo']);
        $objmapa[$cont2]->setDocumento($objdoc[$i]);
        $cont2++;
    }
}
$daoind = new IndicadorDAO();
$objind = array();
$cont3 = 0;
for ($i = 0; $i < $cont2; $i++) {
    $rows = $daoind->buscaindicadorpormapa2($objmapa[$i]->getCodigo());
    foreach ($rows as $row) {
        $unidades_pdi = array(945, 948, 949, 950, 951, 952, 953, 954, 957, 962, 964,
            966, 2504);
        if ($row['PropIndicador'] == $codunidade || in_array($codunidade, $unidades_pdi)) {
            $objind[$cont3] = new Indicador();
            $objind[$cont3]->setMapa($objmapa[$i]);
            $objind[$cont3]->setCodigo($row['Codigo']);
            $objind[$cont3]->setIndicador($row['indicador']);
            $objind[$cont3]->setValidade($row['validade']);
            $cont3++;
        }
    }
}
$daometa = new MetaDAO();
$objmeta = array();
$cont4 = 0;
for ($i = 0; $i < $cont3; $i++) {
    $rows = $daometa->buscarmetaindicador($objind[$i]->getCodigo());
    foreach ($rows as $row) {
        if ($anobase == $row['ano']) {
            $objmeta[$cont4] = new Meta();
            $objmeta[$cont4]->setCodigo($row['Codigo']); // código da meta
            $objmeta[$cont4]->setMeta($row['meta']);    // valor da meta
            $objmeta[$cont4]->setMetrica($row['metrica']); // métrica da meta
            $objmeta[$cont4]->setIndicador($objind[$i]);
            $cont4++;
        }
    }
}
if ($objmeta == NULL) {
    header("Location: incluimeta.php");
}
?>
<h3 class="card-title">Metas</h3>
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
            <th>Indicador</th>
            <th>Valor da Meta</th>
            <th>Métrica</th>
            <th>Editar</th>
        </tr>
    </thead>
    <tbody>
        <?php for ($i = 0; $i < $cont4; $i++): ?>
            <tr>
                <td><?php print ($objmeta[$i]->getIndicador()->getIndicador()); ?></td>
                <td><?php print (str_replace('.', ',', $objmeta[$i]->getMeta())); ?></td>
                <td><?php print ($objmeta[$i]->getMetrica() == 'Q') ? ('quantitativo') : ('percentual'); ?></td>
                <?php
                $today = new DateTime('now');
                $validade = new DateTime($objmeta[$i]->getIndicador()->getValidade());
                ?>
                <?php if ($today < $validade) : ?>
                <td><a href="<?php echo Utils::createLink('metapdi', 'editarmeta', array('codmeta'=>$objmeta[$i]->getCodigo())); ?>" title="Editar o valor da meta"><img src="webroot/img/editar.gif"/></a></td>
                <?php else: ?>
                    <td><img src="../../../imagens/edit-not-validated.png" width="20" height="20"/></a></td>
                <?php endif; ?>
            </tr>
        <?php endfor; ?>
    </tbody>
</table>
<div class="incluir">
    <span class="plus"></span><a href="<?php echo Utils::createLink('metapdi', 'incluimeta');?>">Incluir nova meta</a>
</div>
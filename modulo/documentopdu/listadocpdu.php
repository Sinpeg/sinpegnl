<?php
include_once ('dao/DocumentoDAO.php');
include_once ('classe/Documento.php');
session_start();
$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();
?>
<?php
if (!$aplicacoes[36]) {
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
    if ($row['CodDocumento'] != NULL) {
        $objdoc[$cont] = new Documento();
        $objdoc[$cont]->setCodigo($row['codigo']);
        $objdoc[$cont]->setAnoInicial($row['anoinicial']);
        $objdoc[$cont]->setAnoFinal($row['anofinal']);
        $objdoc[$cont]->setNome($row['nome']);
        $cont++;
    }
}
if ($cont == 0) {
    Utils::redirect('documentopdu', 'inserirdocpdu');
}
?>
<script>
    $(function() {
        $("#tablesorter")
                .tablesorter({
                    widthFixed: true,
                    headers: {
                        4: {
                            sorter: false
                        }
                    },
                    widgets: ['zebra']
                }
                );
    });
</script>
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
            <th>Documento</th>
            <th>Ano de Início</th>
            <th>Ano de Fim</th>
            <th>Editar</th>
        </tr>
    </thead>
    <tbody>
        <?php for ($i = 0; $i < $cont; $i++): ?>
            <tr>
                <td><?php print ($objdoc[$i]->getNome()); ?></td>
                <td><?php print $objdoc[$i]->getAnoInicial(); ?></td>
                <td><?php print $objdoc[$i]->getAnoFinal(); ?></td>
                <td><a href="<?php echo Utils::createLink('documentopdu', 'editardocpdu', array('doc'=> $objdoc[$i]->getCodigo())); ?>"><img src="webroot/img/editar.gif"/></a></td>
            </tr>
        <?php endfor; ?>
    </tbody>
</table>
<div class="incluir">
    <span class="plus"></span><a href="<?php echo Utils::createLink('documentopdu', 'inserirdocpdu'); ?>">Incluir novo documento do PDU</a>
</div>
<?php
if (!$aplicacoes[43]) {
    Error::addErro("Você não possui permissão para acessar a aplicação solicitada!");
    Utils::redirect();
}
?>
<?php
if (!$aplicacoes[5]) {
    header("Location:index.php");
} else {
    $sessao = $_SESSION["sessao"];
    $nomeunidade = $sessao->getNomeunidade();
    $codunidade = filter_input(INPUT_GET, 'codunidade', FILTER_DEFAULT);
    $anobase = $sessao->getAnobase();
    $codestruturado = $sessao->getCodestruturado();
   // require_once('modulo/premios/dao/premiosDAO.php');
   // require_once('modulo/premios/classes/premios.php');
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);
    // Recupera todos os prêmios da unidade por ano
    $daopre = new PremiosDAO();
    $rows = $daopre->buscapremiosSubunidade($codunidade, $anobase);
    $premios = array();
    $cont = 0; // contador de prêmios
    foreach ($rows as $row) {
        $pre = new Premios();
        $pre->setAno($row["Ano"]);
        $pre->setCodigo($row["Codigo"]);
        $pre->setOrgao($row["OrgaoConcessor"]);
        $pre->setNome($row["Nome"]);
        $pre->setQtde($row["Quantidade"]);
        $premios[$cont++] = $pre;
    }
}
if ($cont == 0) {
    Utils::redirect('premios', 'incluipremios');
}
?>
<h3 class="card-title">Pr&ecirc;mios</h3>
<?php if ($cont > 0) { ?>
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
                <th>&Oacute;rg&atilde;o Concessor</th>
                <th>Pr&ecirc;mio/distin&ccedil;&atilde;o,etc.</th>
            </tr>
        </thead>
        <?php for ($i = 0; $i < $cont; $i++): ?>
            <?php $pp = $premios[$i]; ?>
            <tr>
                <td> <?php echo ($pp->getOrgao()); ?></td>
                <td><?php echo ($pp->getNome()); ?></td>
            </tr>
        <?php endfor; ?>
    </table>
<?php } ?>

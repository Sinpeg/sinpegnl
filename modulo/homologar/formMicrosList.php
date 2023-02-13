<?php
if (!$aplicacoes[43]) {
    Error::addErro("Você não possui permissão para acessar a aplicação solicitada!");
    Utils::redirect();
}
?>
<?php
if (!$aplicacoes[6]) {
    header("Location:index.php");
} else {
    $codunidade = filter_input(INPUT_GET, "codunidade", FILTER_DEFAULT);
    $anobase = $sessao->getAnobase();
    require_once('modulo/micros/dao/microsDAO.php');
    require_once('modulo/micros/classes/micros.php');
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);
    $micros = array();
    $micros = new Micros();
    $cont = 0;
    $somaacad = 0;
    $somaadm = 0;
    $somaint = 0;
    $soma = 0;
    $daom = new microsDAO();
    $rows_m = $daom->buscamicrosunidade($codunidade, $anobase);
    foreach ($rows_m as $row) {
        $cont++;
        $unidade->adicionaItemMicros($row['Codigo'], $row['QtdeAcad'], $row['QtdeAcadInt'], $row['QtdeAdm'], $row['QtdeAdmInt'], $anobase);
        $somaacad = $row['QtdeAcadInt'] + $row['QtdeAcad'];
        $somaadm = $row['QtdeAdm'] + $row['QtdeAdmInt'];
        $somaint = $row['QtdeAcadInt'] + $row['QtdeAdmInt'];
        $somasemint = $row['QtdeAcad'] + $row['QtdeAdm'];
        $soma = $row['QtdeAcad'] + $row['QtdeAdm'];
    }
    $daom->fechar();
}
?>
<h3 class="card-title">Computadores com/sem acesso &agrave; Internet</h3>
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
            <tr align="center" style="font-weight: bold;">
                <th></th>
                <th>Qtde. com Internet</th>
                <th>Qtde. sem Internet</th>
                <th> Totais </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Uso Acad&ecirc;mico</td>
                <td align="center"><?php echo $unidade->getMicros()->getAcadi(); ?></td>
                <td align="center"><?php echo $unidade->getMicros()->getAcad(); ?></td>
                <td align="center"><?php echo $somaacad; ?></td>
            </tr>
            <tr>
                <td>Uso Administrativo</td>
                <td align="center"><?php echo $unidade->getMicros()->getAdmi(); ?></td>
                <td align="center"><?php echo $unidade->getMicros()->getAdm(); ?></td>
                <td align="center"><?php echo $somaadm; ?></b></td>
            </tr>
        </tbody>
        <tfoot>
            <tr align="center"><td>Total Geral</td><td><?php echo $somaint; ?></td><td><?php echo $somasemint; ?></td><td><?php echo $somaacad + $somaadm; ?></td></tr>
        </tfoot>
    </table>
<?php } ?>
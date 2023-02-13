<?php
if (!$aplicacoes[43]) {
    Error::addErro("Você não possui permissão para acessar a aplicação solicitada!");
    Utils::redirect();
}
?>
<?php
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[9]) {
    header("Location:index.php");
} else {
    $codunidade = filter_input(INPUT_GET, "codunidade", FILTER_DEFAULT);
    require_once('modulo/infraensino/dao/infraensinoDAO.php');
    require_once('modulo/infraensino/classes/infraensino.php');
    require_once('modulo/infraensino/dao/tipoinfraensinoDAO.php');
    require_once('modulo/infraensino/classes/tipoinfraensino.php');
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);
    $tiposie = array();
    $cont = 0;
    $daoie = new InfraensinoDAO();
    $daotie = new TipoinfraensinoDAO();
    $rows_tie = $daotie->Lista();
    foreach ($rows_tie as $row) {
        $cont++;
        $tiposie[$cont] = new Tipoinfraensino();
        $tiposie[$cont]->setCodigo($row['Codigo']);
        $tiposie[$cont]->setNome($row['Nome']);
    }
    $cont1 = 0;
    $soma = 0;
    $tamanho = count($tiposie);
    $rows_ie = $daoie->buscaieunidade($codunidade, $anobase);
    foreach ($rows_ie as $row) {
        $tipo = $row['Tipo'];
        for ($i = 1; $i <= $tamanho; $i++) {
            if ($tiposie[$i]->getCodigo() == $tipo) {
                $cont1++;
                $tiposie[$i]->criaInfraensino($row["Codigo"], $unidade, $anobase, $row["Quantidade"]);
                $soma += $row["Quantidade"];
            }
        }
    }
}
$daoie->fechar();
if ($cont1 == 0) {
    Utils::redirect('infraensino', 'incluiinfraensino');
}
?>
<h3 class="card-title">Infraestrutura de Ensino</h3>
<?php if ($cont > 0) { ?>
    <div class="card-body">
        <table id="tablesorter" class="table table-bordered table-hover">
            <tfoot>
                <tr>
                    <td colspan="7" class="pager tablesorter-pager">
                        <button type="button" class="first">
                            <i class="fas fa-step-backward"></i>
                        </button>
                        <button type="button" class="prev">
                            <i class="fas fa-arrow-left"></i>
                        </button> 
                        <span class="pagedisplay" data-pager-output-filtered="{startRow:input} &ndash; {endRow} / {filteredRows} of {totalRows} total rows"></span>
                        <button type="button" class="next">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                        <button type="button" class="last">
                            <i class="fas fa-step-forward"></i>
                        </button> 
                        <select class="pagesize"
                            title="Select page size">
                            <option selected="selected" value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="40">40</option>
                        </select> 
                        <select class="pagenum"
                            title="Select page number">
                        </select>
                    </td>
                </tr>
            </tfoot>
            <thead>
                <tr>
                    <th>Itens</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 1; $i <= $cont1; $i++) { ?>
                    <tr>
                        <td><?php print ($tiposie[$i]->getNome()); ?></td>
                        <td><?php print ($tiposie[$i]->getInfraensino()->getQuantidade()); ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php}?>
<table class="card-body">
    <tfoot>
        <tr>
            <td>Total</td>
            <td><?php print $soma; ?></td>
        </tr>
    </tfoot>
</table>
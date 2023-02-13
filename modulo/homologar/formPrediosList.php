<?php
if (!$aplicacoes[43]) {
    Error::addErro("Você não possui permissão para acessar a aplicação solicitada!");
    Utils::redirect();
}
?>
<?php
$sessao = $_SESSION["sessao"];
$codunidade = filter_input(INPUT_GET, 'codunidade', FILTER_DEFAULT);
require_once('modulo/infra/dao/infraDAO.php');
require_once('modulo/infra/classes/infraestrutura.php');
require_once('modulo/infra/dao/tipoinfraDAO.php');
require_once('modulo/infra/classes/tipoinfraestrutura.php');
$tiposti = array();
$cont = 0;
$daotipoinfra = new TipoinfraDAO();
$daoin = new InfraDAO();
$rows_ti = $daotipoinfra->Lista();
foreach ($rows_ti as $row) {
    $cont++;
    $tiposti[$cont] = new Tipoinfraestrutura();
    $tiposti[$cont]->setCodigo($row['Codigo']);
    $tiposti[$cont]->setNome($row['Nome']);
}
$tamanho = $cont;
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$conti = 0;
$rows_ti = $daoin->buscainfraunidade($codunidade);
foreach ($rows_ti as $row1) {
    $tipo = $row1['Tipo'];
    for ($i = 1; $i < $tamanho; $i++) {
        if ($tiposti[$i]->getCodigo() == $tipo) {
            $conti++;
            $tiposti[$i]->adicionaItemInfraestrutura($row1["CodInfraestrutura"], $unidade, $row1['AnoAtivacao'], $row1['Nome'], $row1['Sigla'], $row1['HoraInicio'], $row1['HoraFim'], $row1['Adistancia'], $row1['PCD'], $row1['Area'], $row1['Capacidade'], $row1['AnoDesativacao'], $row1['Situacao']);
        }
    }
}
$daoin->fechar();
?>
<h3 class="card-title">Infraestrutura da Unidade</h3>
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
                <th>Tipo da Infraestrutura</th>
                <th>Nome</th>
            </tr>
        </thead>
        <tbody>
            <?php
            for ($i = 1; $i <= $tamanho; $i++) {
                if ($tiposti[$i]->getInfraestrutura() != null) {
                    foreach ($tiposti[$i]->getInfraestrutura() as $in) {
                        ?>
                        <tr>
                            <td><?php print ($in->getTipo()->getNome()); ?></td>
                            <td><?php print ($in->getNome()); ?></td>
                        </tr>
                        <?php
                    }
                }
            }
            ?>
        </tbody>
    </table>

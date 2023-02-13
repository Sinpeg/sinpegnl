<?php
if (!$aplicacoes[43]) {
    Error::addErro("Você não possui permissão para acessar a aplicação solicitada!");
    Utils::redirect();
}
?>
<?php
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . "/../labor/");
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
$codunidade = filter_input(INPUT_GET, 'codunidade', FILTER_DEFAULT);
if (!$aplicacoes[7]) {
    header("Location:index.php");
} else {
    require_once('dao/laboratorioDAO.php');
    require_once('classes/laboratorio.php');
    require_once('dao/tplaboratorioDAO.php');
    require_once('classes/tplaboratorio.php');
    $daolab = new LaboratorioDAO();
    $daotipolab = new TplaboratorioDAO();
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);
    $cont = 0;
    $rows_tlab = $daotipolab->Lista();
    foreach ($rows_tlab as $row) {
        $tiposlab[$cont] = new Tplaboratorio();
        $tiposlab[$cont]->setCodigo($row['Codigo']);
        $tiposlab[$cont]->setNome($row['Nome']);
        $cont++;
    }
    $cont1 = 0;
    $rows = $daolab->buscaLaboratoriosUnidade($codunidade);
    foreach ($rows as $row) {
        $tipo = $row['Tipo'];
        foreach ($tiposlab as $tipolab) {
            if ($tipolab->getCodigo() == $tipo) {
                $cont1++;
                $tplab = $tipolab;
            }
        }
        $unidade->adicionaItemLabs($row['CodLaboratorio'], $tplab, $row['Nome'], $row['Capacidade'], $row['Sigla'], $row['LabEnsino'], str_replace(".", ",", $row['Area']), $row['Resposta'], $row['CabEstruturado'], $row['Local'], $row['SisOperacional'], $row['Nestacoes'], $row['Situacao'], $row['AnoAtivacao'], $row['AnoDesativacao']);
    }
    $daolab->fechar();
    if ($cont1 == 0) {
        Utils::redirect('labor', 'incluilab');
    }
}
?>
<?php require_once 'notificacaolabor.php'; ?>
<?php echo Utils::deleteModal('Remover Laboratório', 'Você tem certeza que deseja remover o laboratório selecionado?'); ?>
<h3 class="card-title">Laboratórios</h3>
<?php if ($cont > 0) { ?>
    <div class="card-body">
        <table id="tablesorter" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Capacidade</th>
                </tr>
            </thead>
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
            <tbody>
                <tr>
                    <?php foreach ($unidade->getLabs() as $lab1) { ?>
                        <td><?php print ($lab1->getNome()); ?></td>
                        <td><?php print ($lab1->getTipo()->getNome()); ?></td>
                        <td><?php print ($lab1->getCapacidade()); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
<?php
} else {
    print "Nenhum laboratório registrado.";
}
?>

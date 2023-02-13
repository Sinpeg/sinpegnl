<?php
if (!isset($aplicacoes[8])) {
    header("Location:index.php");
}

require_once('dao/infraDAO.php');
require_once('classes/infraestrutura.php');
require_once('dao/tipoinfraDAO.php');
require_once('classes/tipoinfraestrutura.php');

$tiposti = array();
$button = true;
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
$conti = 0;
$locks = array();

// busca todas as infraestruturas
for ($j = 0; $j < count($array_codunidade); $j++) {
    $rows_ti = $daoin->buscainfraunidade($array_codunidade[$j]);
    foreach ($rows_ti as $row1) {
         
    	$tipo = $row1["Tipo"];
        
        for ($i = 1; $i <= $tamanho; $i++) {        	
            if ($tiposti[$i]->getCodigo() == $tipo) {            	
            	$in = new Infraestrutura();
                $in->setCodinfraestrutura($row1["CodInfraestrutura"]);
                $in->setTipo($tiposti[$i]);
                $in->setNome($row1["Nome"]);
                $in->setAnoativacao($row1["AnoAtivacao"]);
                $l = new Lock();
                $l->setData($in);
                // Se o laboratório não pertencer a unidade ou subunidade
                if ($row1["CodUnidade"] != $codunidade) {
                    $l->setLocked(true);
                }
                // fim
                // Se é subunidade
                // Bloqueio dos dados
                if (!$sessao->isUnidade()) {
                    // Teste se a subunidade possui dados homologados
                    $l->setLocked(Utils::isApproved(8, $codunidadecpga, $array_codunidade[$j], $in->getAnoativacao()));
                }
                // Teste se já existe dados cadastrados naquele anobase 
                // Se há dados de subunidades e o ano base é igual ao ano de ativação
                // bloqueia o botão de inserção
                if ($sessao->isUnidade() && $in->getAnoativacao() == $anobase && $codunidade != $array_codunidade[$j]) {
                    $button = false;
                }
                // Subunidade com dados homologados no ano base
                else if (!$sessao->isUnidade() && Utils::isApproved(8, $codunidadecpga, $codunidade, $in->getAnoativacao()) && $anobase == $in->getAnoativacao()) {
                    $button = false;
                }
                // configura os bloqueios
                $locks[] = $l;
                $conti++; // contador  
            }
        }
    }
}

$daoin->fechar();

if ($conti == 0) {
    Utils::redirect('infra', 'incluiinfra');
}
?>

<div class="bs-example">
    <ul class="breadcrumb">
        <li class="active">Infraestrutura</li>
    </ul>
</div>

<?php echo Utils::deleteModal('Remover infraestrutura', 'Você tem certeza que deseja remover a infraestrutura selecionada?'); ?>

<!--<form class="form-horizontal" name="pdf" id="pdf" method="POST" action="relatorio/infra/exportarpdf.php"></form>-->

<script>
    $(function () {
        $("#exportar_pdf").click(function () {
            $("#pdf").submit();
        });
    });
</script>
  
<div class="row">
    <div class="col-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Infraestrutura da Unidade</h3>
            </div> 
            <form class="form-horizontal" name="pdf" id="pdf" method="POST" action="relatorio/infra/exportarpdf.php">
                <div class="download">
                    <ul class="pdf">
                        <li><a href="#" id="exportar_pdf">Exportar em PDF</a></li>
                    </ul>
                </div>
            </form> 
            <form class="form-horizontal" name="finfra" method="POST">
                <div class="card-body">
                    <table id="tabelaInfra" class="table table-bordered table-hover">
                        <!--<div class="card-footer clearfix" >
                            <ul class="pagination pagination-sm m-0 float-right">
                                <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                            </ul>
                        </div>-->
                        <thead>
                            <tr>
                                <th>Tipo da Infraestrutura</th>
                                <th>Nome</th>
                                <th>Alterar</th>
                                <th>Excluir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < count($locks); $i++){ ?>
                                <?php
                                $l = $locks[$i];
                                $data = $l->getData();
                                ?>
                                <tr>
                                    <td><?php print $data->getTipo()->getNome(); ?></td>
                                    <td><?php print $data->getNome(); ?></td>
                                    <td align="center">
                                        <a href="<?php echo Utils::createLink('infra', 'altinfra', array('codin' => $data->getCodinfraestrutura(), 'operacao' => 'A')); ?>" ><img src="webroot/img/editar.gif" alt="Alterar" width="19" height="19" /> </a>
                                    </td>
                                    <?php if (!$l->getLocked()) { ?>
                                        <td align="center">
                                            <a href="<?php echo Utils::createLink('infra', 'delinfra', array('codin' => $data->getCodinfraestrutura(), 'operacao' => 'A')); ?>" ><img src="webroot/img/delete.png" alt="Delete" width="19" height="19" /> </a>
                                        </td>
                                    <?php } ?>

                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <table class="card-body">
                        <tr>
                            <td align="center" colspan="4">
                                <?php if ($button): ?>    
                                <br/> <input class="form-control"<?php echo $button; ?> type="button" id="gravar" onclick="direciona(2);" value="Incluir" class="btn btn-info" />
                                <?php endif; ?>
                                <br>                        
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('#tabelaInfra').DataTable({
        "paging": true,
        "sort": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "responsive": true,
        });
    });
</script>
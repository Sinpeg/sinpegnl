<?php

$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[5]) {
    header("Location:index.php");
} else {
    $premios = array();
    $cont = 0; // contador
    $locks = array();
    $button = true; // botão habilitado
    $daou = new UnidadeDAO();
    $unid = $daou->unidadeporcodigo($sessao->getCodUnidade());
    foreach ($unid as $u){
    	$unidade_responsavel=$u['unidade_responsavel'];
    }
    $daop = new PremiosDAO();
    // para todos os códigos das unidades presentes no array de códigos
    for ($j = 0; $j < count($array_codunidade); $j++) {
        if ($unidade_responsavel==1)
        $rows = $daop->buscapremiosunidade($array_codunidade[$j], $anobase);
        else
        $rows = $daop->buscapremiosSubunidade($array_codunidade[$j],$anobase);
        //$sessao->getCodUnidade()
        $string=NULL;
        foreach ($rows as $row) {
            $p = new Premios();
            $p->setAno($anobase);
            $p->setCodigo($row["Codigo"]);
            $p->setOrgao($row["OrgaoConcessor"]);
            $p->setNome($row["Nome"]);
            $p->setQtde($row["Quantidade"]);
            $l = new Lock();
            $l->setData($p);
            // configura os dados que serão bloqueados
            // por padrão se o objeto não pertence a unidade, bloqueia
            if ($codunidade != $row["CodUnidade"]) {
                $l->setLocked(true);
            }
            // Bloqueio da subunidade
            // Se já existe dados homologados para a subunidade
            // Bloqueia o formulário e o botão
            if (!$sessao->isUnidade()) {
                $l->setLocked(Utils::isApproved(5, $codunidadecpga, $array_codunidade[$j], $p->getAno()));
                $button = !(Utils::isApproved(5, $codunidadecpga, $array_codunidade[$j], $p->getAno()));
            }
            // Bloqueio da Unidade: botão
            // Se existem dados de subunidades naquele ano base
            // independente de homologação, bloqueia o acesso à inserção
            $string=NULL;
            if ($sessao->isUnidade() && $rows->rowCount() != 0 && $row["CodUnidade"] != $codunidade) {
                $string = "Já existem dados cadastrados das subunidades para o ano base de $anobase";
                $button = false;
            }
            $locks[] = $l;
            $cont++;
        }
    }
    if (!is_null($string)) {
        Flash::message($string);
    } else if ($cont == 0) {
        Utils::redirect('premios', 'incluipremios');
    }
    ?>

    <script type="text/javascript">
        function direciona(botao) {
            document.getElementById('pre').action = "?modulo=premios&acao=incluipremios";
            document.getElementById('pre').submit();

        }
    </script>

    <?php echo Utils::deleteModal('SisRAA', 'Você deseja excluir o registro selecionado?'); ?>
    
    <head>
        <div class="bs-example">
            <ul class="breadcrumb">
                    <li class="active">
                       Consultar prêmios
                    </li>
            </ul>
        </div>
    </head>
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Pr&ecirc;mios </h3>
        </div>
        <form class="form-horizontal" name="pre" id="pre" method="post">
            
            <?php if ($cont > 0) { ?>
                <div class="card-body">
                    <table id="tabelaPremio" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>&Oacute;rg&atilde;o Concessor</th>
                                <th>Pr&ecirc;mio/distin&ccedil;&atilde;o,etc.</th>
                                <th>Alterar</th>
                                <th>Excluir</th>
                            </tr>
                        </thead>
                        <?php for ($i = 0; $i < $cont; $i++){?>
                            <?php $l = $locks[$i]; 
                            $p = $l->getData(); ?>
                            <tr>
                                <td> <?php echo $p->getOrgao(); ?></td>
                                <td><?php echo $p->getNome(); ?></td>
                                <td align="center">
                                    <a href="<?php echo Utils::createLink('premios', 'alterapremios', array("codigo" => $p->getCodigo(), 'operacao' => "A")); ?>"
                                    target="_self" ><img src="webroot/img/editar.gif" alt="Alterar" width="19" height="19" /> </a>
                                </td>
                                <?php // if (!$l->getDisabled()): ?>
                                    <td align="center">
                                        <a href="<?php echo Utils::createLink('premios', 'delpremio', array("codigo" => $p->getCodigo())); ?>"
                                        target="_self" class="delete-link"><img src="webroot/img/delete.png" alt="Excluir" width="19" height="19" /> </a>
                                    </td>

                            </tr>
                        <?php } ?>
                    </table> <br/>
                </div>
                <table class="card-body">
                    <tr>
                        <td>
                            <?php if ($button){ ?>
                                <input type="button" onclick='direciona(1);' value="Incluir" class="btn btn-info" />
                                
                                &ensp;<a href="relatorio/relatorio_premios2.php?anoBase=<?php echo $anobase;?>&codUnidade=<?php echo $codunidade;?>" > 
                                <input type="button"  value="Relatorio de Prêmios" class="btn btn-info" /></a>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
            <?php
            } else {
                Utils::redirect('premios', 'incluipremios');
            }
        ?></form>  
    </div>
<?php } ?>

<script>
    $(function () {
        $('#tabelaPremio').DataTable({
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
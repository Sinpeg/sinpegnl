<?php
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
echo "teste";
if (!$aplicacoes[5]) {
    header("Location:index.php");
    echo "teste";
} else {
	echo "teste";
   require_once('dao/premiosDAO.php');
    require_once('classes/premios.php');
    $premios = array();
    $cont = 0; // contador
    $locks = array();
    $button = true; // botão habilitado
    // para todos os códigos das unidades presentes no array de códigos
    for ($j = 0; $j < count($array_codunidade); $j++) {
        $daop = new premiosDAO();
        $rows = $daop->buscapremiosunidade($array_codunidade[$j], $anobase);
        foreach ($rows as $row) {
            $p = new Premios();
            $p->setAno($anobase);
            $p->setCodigo($row["Codigo"]);
            $p->setOrgao($row["OrgaoConcessor"]);
            $p->setNome($row["Nome"]);
            $p->setQtde($row["Quantidade"]);
            $p->setCategoria($row["Categoria"]);
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
                $l->setLocked(Utils::isApproved(5, $cpga, $array_codunidade[$j], $p->getAno()));
                $button = !(Utils::isApproved(5, $cpga, $array_codunidade[$j], $p->getAno()));
            }
            // Bloqueio da Unidade: botão
            // Se existem dados de subunidades naquele ano base
            // independente de homologação, bloqueia o acesso à inserção
            if ($sessao->isUnidade() && $rows->rowCount() != 0 && $row["CodUnidade"] != $codunidade) {
                $string = "J&aacute; existem dados cadastrados das subunidades para o ano base de $anobase";
                $button = false;
            }
            $locks[] = $l;
            $cont++;
        }
    }
    if ($cont>0)
    	echo "cont";
    
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
    <form class="form-horizontal" name="pre" id="pre" method="post">
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
                        <th>Pr&ecirc;mio</th>
                        <th>Alterar</th>
                        <th>Excluir</th>
                    </tr>
                </thead>
                <?php for ($i = 0; $i < $cont; $i++): ?>
                    <?php $l = $locks[$i]; ?>
                    <?php $p = $l->getData(); ?>
                    <tr>
                        <td> <?php echo utf8_encode($p->getOrgao()); ?></td>
                        <td><?php echo utf8_encode($p->getNome()); ?></td>
                        <td align="center">
                            <a href="<?php echo Utils::createLink('premios', 'alterapremios', array("codigo" => $p->getCodigo(), 'operacao' => "A")); ?>"
                               target="_self" ><img src="webroot/img/editar.gif" alt="Alterar" width="19" height="19" /> </a>
                        </td>
                        <?php if (!$l->getDisabled()): ?>
                            <td align="center">
                                <a href="<?php echo Utils::createLink('premios', 'delpremio', array("codigo" => $p->getCodigo())); ?>"
                                   target="_self" class="delete-link"><img src="webroot/img/delete.png" alt="Excluir" width="19" height="19" /> </a>
                            </td>
                        <?php else: ?>
                            <td></td>
                        <?php endif; ?>
                    </tr>
                <?php endfor; ?>
            </table> <br/>
            <?php if ($button): ?>
                <input type="button" onclick='direciona(1);' value="Incluir" class="btn btn-info" />
            <?php endif; ?>
            <?php
        } else {
            Utils::redirect('premios', 'incluipremios');
        }
    }
    ?>
</form>

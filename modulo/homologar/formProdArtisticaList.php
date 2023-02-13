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
if (!$aplicacoes[18]) {
    header("Location:index.php");
}
//$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeUnidade();
$codunidade = $sessao->getCodUnidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
require_once('modulo/prodartistica/dao/producaoartisticaDAO.php');
require_once('modulo/prodartistica/classes/producaoartistica.php');
require_once('modulo/prodartistica/dao/tipoproducaoartisticaDAO.php');
require_once('modulo/prodartistica/classes/tipoproducaoartistica.php');
//require_once('../../includes/classes/unidade.php');
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$tipospa = array();
$cont = 0;
$daotpa = new TipoproducaoartisticaDAO();
$daopa = new ProducaoartisticaDAO();
$rows_tpa = $daotpa->Lista();
foreach ($rows_tpa as $row) {
    $cont++;
    $tipospa[$cont] = new Tipoproducaoartistica();
    $tipospa[$cont]->setCodigo($row['Codigo']);
    $tipospa[$cont]->setNome($row['Nome']);
}
$tamanho = count($tipospa);
$cont1 = 0;
$codsub = filter_input(INPUT_GET, 'codunidade', FILTER_DEFAULT);
$rows_pa = $daopa->buscapaunidade($codsub, $anobase);
foreach ($rows_pa as $row) {
    $tipo = $row['Tipo'];
    for ($i = 1; $i <= $tamanho; $i++) {
        if ($tipospa[$i]->getCodigo() == $tipo) {
            $cont1++;
            $tipospa[$i]->criaProdartistica($row["Codigo"], $unidade, $anobase, $row["Quantidade"]);
        }
    }
}

$daopa->fechar();
//ob_end_flush();
?>


    <h3 class="card-title"> Produ&ccedil;&atilde;o Art&iacute;stica </h3>

    <table>
        <tr>
            <th>Itens</th>
            <th>Quantidade</th>
        </tr>
        <?php for ($i = 1; $i <= $tamanho; $i++) { ?>
            <tr><td>
                    <?php print ($tipospa[$i]->getNome()); ?></td><td align="center">
                    <?php
                    print $tipospa[$i]->getProdartistica()->getQuantidade();
                }
                ?></td></tr>

    </table>
    <br />

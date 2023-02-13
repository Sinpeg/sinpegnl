<?php
//session_start();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[2]) {
    header("Location:index.php");
}
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
$anobase = $sessao->getAnobase();

$BASE_DIR = dirname(__FILE__) . '/';

require_once($BASE_DIR . 'dao/acaoDAO.php');
require_once($BASE_DIR . 'classes/acao.php');
require_once($BASE_DIR . 'classes/programa.php');
require_once($BASE_DIR . 'dao/programaDAO.php');

$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);

$programas = array();
$daop = new ProgramaDAO();
$daoa = new AcaoDAO();
$cont = 0;
$rowsp = $daop->Lista();
foreach ($rowsp as $row1) {
    $cont++;
    $programas[$cont] = new Programa();
    $programas[$cont]->setCodigo($row1["Codigo"]);
    $programas[$cont]->setNome($row1["Nome"]);
    $programas[$cont]->setCodprograma($row1["CodigoPrograma"]);
}
$passou = false;

$rowsa = $daoa->buscaAcao($codunidade, $anobase);

foreach ($rowsa as $row1) {
    $p = $row1["pcodigo"];
    for ($i = 1; $i <= $cont; $i++) {
        if ($programas[$i]->getCodigo() == $p) {
            $passou = true;
            $programas[$i]->adicionaItemAcoes($row1["acodigo"], $unidade, $row1["CodigoAcao"], $row1["Nome"], $row1["Finalidade"], $row1["Descricao"], $row1["AnaliseCritica"], $anobase);
        }
    }
}
$daoa->fechar();

ob_end_flush();
?>        <script language="javascript">
    function direciona(botao) {
        switch (botao) {
            case 1:
                document.getElementById('facao').action = "altacao.php";
                document.getElementById('facao').submit();
                break;
            case 2:
                document.getElementById('facao').action = "../../saida/saida.php";
                document.getElementById('facao').submit();
                break;
        }
    }
</script>
<form class="form-horizontal" name="facao" id="facao" method="post">
    <h3 class="card-title">A&ccedil;&otilde;es do SIMEC</h3>
    <div id="txtHint"></div>
    <?php
    if (!$passou) {
//        echo "<p style='color: red; font-weight: bold;' >A unidade não possui ação no SIMEC!</p>";
    } else {
        ?>
        <table class="tab_resultado" id="tablesorter">
            <thead>
                <tr>
                    <th>Programa</th>
                    <th>A&ccedil;&atilde;o</th>
                    <th>Alterar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($programas as $p) {
                    $tamanho = count($p->getAcoes());
                    if ($tamanho != 0) {
                        ?>
                        <tr>
                            <td rowspan="<?php print $tamanho++; ?>"><?php print ($p->getNome()); ?>
                            </td>
                            <?php foreach ($p->getAcoes() as $a) { ?>
                                <td><?php print ($a->getNome()); ?></td>
                                <td><a href="<?php echo Utils::createLink('simec', 'altacao', array('codigo' => $a->getCodigo())); ?>"   
                                       target=_self><img src="../sistema/webroot/img/editar.gif" alt="Alterar" width=19 height=19 />
                                    </a></td>
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
            </tbody>
        </table>
    <?php } ?>
</form>
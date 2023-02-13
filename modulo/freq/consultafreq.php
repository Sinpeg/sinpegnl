<?php
//ob_start();
//
//echo ini_get('display_errors');
//
//if (!ini_get('display_errors')) {
//	ini_set('display_errors', 1);
//	ini_set('error_reporting', E_ALL & ~E_NOTICE);
//}
?>
<?php
//require_once('../../includes/classes/sessao.php');


$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[17]) {
    header("Location:index.php");
} else {
//	$codunidade=0;
    $sessao = $_SESSION["sessao"];
//	$nomeunidade = $sessao->getNomeunidade();
//	$login = $sessao->getLogin();
//	$codunidade = $sessao->getCodunidade();
//	$responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();

//		require_once('../../includes/dao/PDOConnectionFactory.php');

    require_once('dao/freqfarmaciaDAO.php');
    require_once('classes/freqfarmacia.php');

    $cont = 0;
    $daofreq = new FreqfarmaciaDAO();
    $rows_freq = $daofreq->buscaporano($anobase);
    foreach ($rows_freq as $row) {
        $cont++;
        $f[$cont] = new Freqfarmacia();
        $f[$cont]->setCodigo($row['Codigo']);
        $f[$cont]->setAno($anobase);
        $f[$cont]->setMes($row['Mes']);
        $f[$cont]->setNAlunos($row['NAlunos']);
        $f[$cont]->setNProfessores($row['NProfessores']);
        $f[$cont]->setNVisitantes($row['NVisitantes']);
        $f[$cont]->setNPesquisadores($row['NPesquisadores']);
    }
    $tamanho = count($f);
    $daofreq->fechar();
    if ($cont == 0) {
        $cadeia = "location:?modulo=freq&acao=incluifreq";
        header($cadeia);
        exit();
    }
}

//ob_end_flush();
?>
<script language='javascript'>
    function direciona() {
        document.freq.action = "?modulo=freq&acao=incluifreq";
        document.freq.submit();
    }
</script>

<?php echo Utils::deleteModal('SisRAA', 'Você deseja remover o item selecionado?'); ?>

<form class="form-horizontal" method="post" name="freq">
    <h3 class="card-title">Frequentadores da F&aacute;rmacia</h3><br/>
    <table class="table">
        <tr align="center" style="font-style: italic;" >
            <th>M&ecirc;s</th>
            <th>N&uacute;mero de Discentes</th>
            <th>N&uacute;mero de Docentes</th>
            <th>N&uacute;mero de Pesquisadores</th>
            <th>N&uacute;mero de Visitantes</th>
            <th>Alterar</th>
            <th>Excluir</th>
        </tr>
        <?php for ($i = 1; $i <= $tamanho; $i++) { ?>
            <tr>
                <td>
                    <?php
                    if ($f[$i]->getMes() == 1)
                        print "janeiro";
                    if ($f[$i]->getMes() == 2)
                        print "fevereiro";
                    if ($f[$i]->getMes() == 3)
                        print "mar&ccedil;o";
                    if ($f[$i]->getMes() == 4)
                        print "abril";
                    if ($f[$i]->getMes() == 5)
                        print "maio";
                    if ($f[$i]->getMes() == 6)
                        print "junho";
                    if ($f[$i]->getMes() == 7)
                        print "julho";
                    if ($f[$i]->getMes() == 8)
                        print "agosto";
                    if ($f[$i]->getMes() == 9)
                        print "setembro";
                    if ($f[$i]->getMes() == 10)
                        print "outubro";
                    if ($f[$i]->getMes() == 11)
                        print "novembro";
                    if ($f[$i]->getMes() == 12)
                        print "dezembro";
                    ?>
                </td>
                <td><?php print $f[$i]->getNAlunos(); ?></td>
                <td><?php print $f[$i]->getNProfessores(); ?></td>
                <td><?php print $f[$i]->getNPesquisadores(); ?></td>
                <td><?php print $f[$i]->getNVisitantes(); ?></td>

                <td align="center">
                    <a href="?modulo=freq&acao=alterafreq&codigo=<?php print $f[$i]->getCodigo(); ?>"
                       target="_self" ><img src="webroot/img/editar.gif" alt="Alterar" width="19" height="19" /> </a>
                </td>
                <td align="center">
                    <a href="?modulo=freq&acao=delfreq&AMP;codigo=<?php print $f[$i]->getCodigo() ?>"
                       target="_self" class="delete-link" ><img src="webroot/img/delete.png" alt="Excluir" width="19" height="19" /> </a>
                </td>
            </tr>
        <?php } ?>
    </table><br/>
    <input type="button" class="btn btn-info" onclick="direciona();" value="Incluir" />
</form>
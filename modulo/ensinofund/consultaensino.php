<?php
if (!isset($_SESSION["sessao"])) {
    header("location:index.php");
}

$sessao = $_SESSION["sessao"];
$anoBase = $sessao->getAnoBase();

$tiposea = array();
$daoea = new ensinoeaDAO();
//a partir do 1a 8
$contt = 0;
$rows_tea = $daoea->ListaEf($anoBase);
foreach ($rows_tea as $row) {
    $tiposea[$contt] = new Tdmensinoea();
    $tiposea[$contt]->setCodigo($row['Codigo']);
    $tiposea[$contt]->setModalidade($row['Modalidade']);
    $tiposea[$contt]->setSerie($row['Serie']);
    $tiposea[$contt]->setEnsino($row['Ensino']);
    $contt++;
};
$tamanho = $contt;
$cont = 0;
$rows_ea = $daoea->buscaensinofund($anoBase);
//echo $rows_ea->rowCount();
foreach ($rows_ea as $row) {
    $tipo = $row['Codtdmensinoea'];
    for ($i = 0; $i < $tamanho; $i++) {
        if ($tiposea[$i]->getCodigo() == $tipo) {
            $tiposea[$i]->criaEnsinoea($row["Codigo"], $row["Matriculados"], $row["Aprovados"], $row["Reprovados"], $anoBase);
            $cont++;
        }
    }
}
$daoea->fechar();

if ($cont == 0) {
    Utils::redirect('ensinofund', 'incluiensino');
}
//ob_end_flush();
?>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li><a href="<?php echo Utils::createLink("ensinofund", "altensino"); ?>">Quantitativo do ensino fundamental</a></li>
			<li class="active">Consulta</li>
		</ul>
	</div>
</head>
<form class="form-horizontal" name="ensino" id="ensino" method="post">
    <h3 class="card-title">Ensino Fundamental - Escola de Aplica&ccedil;&atilde;o</h3>
    <div class="msg" id="msg"></div>
    <table>
        <tr>
            <th align="left" width="200px">Modalidade</th>
            <th>Matriculados</th>
            <th>Aprovados</th>
            <th>Reprovados</th>
            <th>%Aprov./Matr.</th>
            <th>%Evas&atilde;o/Matr.</th>
        </tr>
        <?php
        $str = '';
        $ok = false;
        foreach ($tiposea as $t) {
            ?>
            <?php if ($t->getEnsinoea() != null): ?>
                <?php
                if ($str != $t->getModalidade()) {
                    $str = $t->getModalidade();
                    $ok = true;
                }
                ?>
                <?php if ($ok): $ok = false; ?>
                    <tr>
                        <th colspan="6" align="left"><?php print($str); ?>
                        </th>
                    </tr>

                <?php endif; ?>
               <?php if (($anoBase<2018) || (($anoBase>=2018) 
               && ((preg_match('/ano/', $t->getSerie()) || (preg_match('/Pré/', $t->getSerie())))))){?>
                
                <tr>
                    <td>
                        <?php print ($t->getSerie());
                        ?>
                    </td>
                    <td><?php print $t->getEnsinoea()->getMatriculados(); ?></td>
                    <td><?php print $t->getEnsinoea()->getAprovados(); ?></td>
                    <td><?php print $t->getEnsinoea()->getReprovados(); ?></td>
                    <?php 
                        $result = ($t->getEnsinoea()->getMatriculados()==0)?0:($t->getEnsinoea()->getAprovados() / $t->getEnsinoea()->getMatriculados()) * 100;
                    ?>
                    <td><?php print str_replace(".", ",", round($result, 2)); ?></td>
                    <td><?php
                        $evadidos = $t->getEnsinoea()->getMatriculados() - ($t->getEnsinoea()->getAprovados() + $t->getEnsinoea()->getReprovados());
                        $evasao = ($t->getEnsinoea()->getMatriculados()==0)?0:($evadidos / $t->getEnsinoea()->getMatriculados()) * 100;
                        print str_replace(".", ",", round(($evasao), 2));
                        ?></td></tr>
 <?php } ?>
            <?php endif; ?>
        <?php } ?>
    </table>
    <input class="form-control"name="operacao" type="hidden" value="A" /> <input type="button" onclick="direciona(3);" value="Alterar" class="btn btn-info" />
</form>
</body>
</html>
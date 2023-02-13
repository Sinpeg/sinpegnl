<?php
/*ob_start();
echo ini_get('display_errors');
if (!ini_get('display_errors')) {
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL & ~E_NOTICE);
}*/
?>
<?php
//set_include_path(';../../includes');
//require_once('../../includes/dao/PDOConnectionFactory.php');
//require_once('../../includes/classes/sessao.php');
if (!isset($_SESSION["sessao"])) {
    header("location:index.php");
}

$sessao = $_SESSION["sessao"];
//$nomeUnidade = $sessao->getNomeUnidade();
//$codUnidade = $sessao->getCodUnidade();
//$responsavel = $sessao->getResponsavel();
$anoBase = $sessao->getAnoBase();

//require_once('../../includes/dao/ensinoeaDAO.php');
//require_once('../../includes/classes/ensinoea.php');
//require_once('../../includes/classes/tdmensinoea.php');

$tiposea = array();
$daoea = new ensinoeaDAO();
//a partir do 1a 8
$contt = 0;
$rows_tea = $daoea->ListaEf($anoBase);
foreach ($rows_tea as $row) {
    $contt++;
    $tiposea[$contt] = new Tdmensinoea();
    $tiposea[$contt]->setCodigo($row['Codigo']);
    $tiposea[$contt]->setModalidade($row['Modalidade']);
    $tiposea[$contt]->setSerie($row['Serie']);
    $tiposea[$contt]->setEnsino($row['Ensino']);
}

$tamanho = $contt;
$cont = 0;
$rows_ea = $daoea->buscaensinofund($anoBase);
foreach ($rows_ea as $row) {
    $tipo = $row['Codtdmensinoea'];
    for ($i = 1; $i <= $tamanho; $i++) {
        if ($tiposea[$i]->getCodigo() == $tipo) {
            $cont++;
            $tiposea[$i]->criaEnsinoea($row["Codigo"], $row["Matriculados"], $row["Aprovados"], $row["Reprovados"], $anoBase);
        }
    }
}
$daoea->fechar();
//ob_end_flush();
?>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li><a href="<?php echo Utils::createLink("ensinofund", "incluiensino"); ?>">Infraestrutura de ensino na unidade</a></li>
			<li><a href="<?php echo Utils::createLink("ensinofund", "consultaensino") ?>">Consulta</a></li>
			<li class="active">Alterar</li>
		</ul>
	</div>
</head>
<form class="form-horizontal" name="ensino" id="ensino" method="post">
    <h3 class="card-title">Ensino Fundamental - Escola de Aplica&ccedil;&atilde;o</h3>
    <div class="msg" id="msg"></div>
    <table  border="0" style="font-size:13px;">
        <tr>
            <th>Modalidade</th>
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
            <?php if ($t->getEnsinoea() != null) : ?>
                <?php
                if ($str != $t->getModalidade()) {
                    $str = $t->getModalidade();
                    $ok = true;
                }
                ?>
                <?php if ($ok): $ok = false; ?>
                    <tr>
                        <th colspan="6" align="left"><?php print ($str); ?>
                        </th>
                    </tr>
                <?php endif; ?>
                    <?php if (($anoBase<2018) || (($anoBase>=2018) 
               && ((preg_match('/ano/', $t->getSerie()) || (preg_match('/Pré/', $t->getSerie())))))){?>
                <tr>
                    <td>
                        <?php print ($t->getSerie()); ?>
                    </td>
                    <td><input class="form-control" type="text" onblur="percentagem();"
                                onkeypress="return SomenteNumero(event)" name="em[]" size="5" maxlength="4"
                                value="<?php print $t->getEnsinoea()->getMatriculados(); ?>" /></td>
                    <td><input class="form-control" type="text"
                                onkeypress='return SomenteNumero(event)' onblur="percentagem();"
                                name="ea[]" size="5" maxlength="4" value="<?php print $t->getEnsinoea()->getAprovados(); ?>" /></td>
                    <td><input class="form-control"type="text"
                               onkeypress='return SomenteNumero(event)' onblur="percentagem();"
                               name="er[]" size="5" maxlength="4" value="<?php print $t->getEnsinoea()->getReprovados(); ?>" />

                        <input class="form-control"type="hidden"
                               onkeypress='return SomenteNumero(event)' onblur="percentagem();"
                               name="cod[]" size="5" maxlength="4" value="<?php print $t->getEnsinoea()->getCodigo(); ?>" />
                    </td>
                    <td><input class="form-control"type="text" name="p1[]" size="5" readonly="readonly" style="border: none;background-color: transparent"/></td>
                    <td><input class="form-control"type="text" name="p2[]" size="5" readonly="readonly" style="border: none;background-color: transparent"/></td>
                </tr>
                 <?php  } ?>
            <?php endif; ?>   
           
        <?php } ?>
    </table>
    <input class="form-control"name="operacao" type="hidden" value="A" /> <input type="button" onclick="direciona(1);" value="Gravar" />
</form>
<script>
window.onload = percentagem();
</script>
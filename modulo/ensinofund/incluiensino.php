<?php
//set_include_path(';../../includes');
//require_once('../../includes/dao/PDOConnectionFactory.php');
//require_once('../../includes/classes/sessao.php');
//session_start();
if (!isset($_SESSION["sessao"])) {
    header("location:index.php");
}

$sessao = $_SESSION["sessao"];
//$nomeUnidade = $sessao->getNomeUnidade();
//$codUnidade = $sessao->getCodUnidade();
//$responsavel = $sessao->getResponsavel();
$anoBase = $sessao->getAnoBase();
//
//require_once('../../includes/dao/ensinoeaDAO.php');
//require_once('../../includes/classes/ensinoea.php');
//require_once('../../includes/classes/tdmensinoea.php');
$tiposea = array();
$daoea = new ensinoeaDAO();
//a partir do 17
$contt = 0;
//$rows_tea = $daoea->ListaEf1($anoBase);
$rows_tea = $daoea->ListaEf($anoBase);
foreach ($rows_tea as $row) {
    $contt++;
    $tiposea[$contt] = new Tdmensinoea();
    if ($anoBase<2018)
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
            $tiposea[$i]->criaEnsinoea($row["Codigo"], $row["Matriculados"], $row["Aprovados"], $row["Reprovados"], $anoBase);
            $cont++;
        }
    }
}
$daoea->fechar();
if ($cont > 0) {
    Utils::redirect('ensinofund', 'consultaensino');
//	$cadeia = "location:consultaensino.php";
//	header($cadeia);
    //exit();
}
//ob_end_flush();
?>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active">Quantitativo do ensino fundamental</li>
		</ul>
	</div>
</head>
<form class="form-horizontal" name="ensino" id="ensino" method="POST">
    <div id="msg"></div>
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
                     
                    <td><?php echo ($t->getSerie()); ?></td>
            <td><input class="form-control" type="text" onblur="percentagem();"
                         onkeypress="return SomenteNumero(event)" name="em[]" size="5" maxlength="4"
                         value="" /></td>
            <td><input class="form-control" type="text"
                        onkeypress='return SomenteNumero(event)' onblur="percentagem();"
                        name="ea[]" size="5" maxlength="4" value="" /></td>
            <td><input class="form-control"type="text"
                       onkeypress='return SomenteNumero(event)' onblur="percentagem();"
                       name="er[]" size="5" maxlength="4" value="" /></td>
            <td><input class="form-control"type="text" name="p1[]" size="5" readonly="readonly" style="border: none;background-color: transparent"/></td>
            <td><input class="form-control"type="text" name="p2[]" size="5" readonly="readonly" style="border: none;background-color: transparent"/></td>
        </tr>

        <?php } 
                
                } ?>

</table>
<input class="form-control"name="operacao" type="hidden" value="I" /> <input type="button" class="btn btn-info" onclick="direciona(1);" value="Gravar" />
</form>

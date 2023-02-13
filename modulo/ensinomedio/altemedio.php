<?php
//set_include_path(';../../includes');
//require_once('../../includes/dao/PDOConnectionFactory.php');
//require_once('../../includes/classes/sessao.php');
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[21]) {
    echo "Você não tem permissão para acessar esta opção de menu do sistema. Contate o administrador: sisraa@ufpa.br...";die;
}
//$sessao = $_SESSION["sessao"];
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
$cont = 0;
$rows_tea = $daoea->ListaEm($anoBase);
foreach ($rows_tea as $row) {
    $cont++;
    $tiposea[$cont] = new Tdmensinoea();
    $tiposea[$cont]->setCodigo($row['Codigo']);
    $tiposea[$cont]->setModalidade($row['Modalidade']);
    $tiposea[$cont]->setSerie($row['Serie']);
    $tiposea[$cont]->setEnsino($row['Ensino']);
}
$tamanho = $cont;
$cont = 0;
$rows_ea = $daoea->buscaensinomedio($anoBase);
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
			<li><a href="<?php echo Utils::createLink("ensinomedio", "incluiemedio"); ?>">Quantitativo do ensino m&eacute;dio</a></li>
			<li><a href="<?php echo Utils::createLink("ensinomedio", "consultaemedio"); ?>">Consulta</a></li>
			<li class="active">Alterar</li>
		</ul>
	</div>
</head>
<form class="form-horizontal" name="emedio" id="emedio" method="post">
    <h3 class="card-title"> Ensino M&eacute;dio - Escola de Aplica&ccedil;&atilde;o</h3>
    <div class="msg" id="msg"></div>
    <table>
        <tr>
            <th align="left" width="200px"><b><?php print ($tiposea[1]->getModalidade()); ?>
                </b></th>
            <th style="font-style: italic;" align="center">Matriculados</th>
            <th style="font-style: italic;" align="center">Aprovados</th>
            <th style="font-style: italic;" align="center">Reprovados</th>
            <th style="font-style: italic;" align="center">%Aprov./Matr.</th>
            <th style="font-style: italic;" align="center">%Evas&atilde;o/Matr.</th>
        </tr>
        <?php
        foreach ($tiposea as $t) {
            if (substr($t->getEnsino(), 1, 1) == "E") {
                ?>
                <tr>
                    <td>  <?php print ($t->getSerie()); ?> </td>
                    <td><input class="form-control" type="text" onblur="percentagem();"
                                onkeypress="return SomenteNumero(event)" name="em[]" size="5" maxlength="4"
                                value="<?php print $t->getEnsinoea()->getMatriculados(); ?>" /></td>
                    <td><input class="form-control" type="text"
                                onkeypress='return SomenteNumero(event)' onblur="percentagem();" maxlength="4"
                                name="ea[]" size="5" value="<?php print $t->getEnsinoea()->getAprovados(); ?>" /></td>
                    <td><input class="form-control"type="text"
                               onkeypress='return SomenteNumero(event)' onblur="percentagem();" maxlength="4"
                               name="er[]" size="5" value="<?php print $t->getEnsinoea()->getReprovados(); ?>" />

                        <input class="form-control"type="hidden"
                               onkeypress='return SomenteNumero(event)' onblur="percentagem();"
                               name="cod[]" size="5" value="<?php print $t->getEnsinoea()->getCodigo(); ?>" />
                    </td>
                    <td><input class="form-control"type="text" name="p1[]" readonly="readonly" style="border: none;background-color: transparent"/></td>
                    <td><input class="form-control"type="text" name="p2[]" readonly="readonly" style="border: none;background-color: transparent"/></td>
                </tr>

            <?php
            }//if
        }//for
        
         if ($anoBase<2018){
        ?>

        <tr>
            <th colspan="6" align="left"><?php print ($tiposea[4]->getModalidade()); ?>
            </th>
        </tr>
        <?php
        foreach ($tiposea as $t) {
            if (substr($t->getEnsino(), 1, 1) == "R") {
                ?>
                <tr>
                    <td>
                        <?php print ($t->getSerie());
                        ?>
                    </td>
                    <td><input class="form-control" type="text" onblur="percentagem();" maxlength="4"
                                onkeypress="return SomenteNumero(event)" name="em[]" size="5"
                                value="<?php print $t->getEnsinoea()->getMatriculados(); ?>" /></td>
                    <td><input class="form-control" type="text"
                                onkeypress='return SomenteNumero(event)' onblur="percentagem();" maxlength="4"
                                name="ea[]" size="5" value="<?php print $t->getEnsinoea()->getAprovados(); ?>" /></td>
                    <td><input class="form-control"type="text"
                               onkeypress='return SomenteNumero(event)' onblur="percentagem();" maxlength="4"
                               name="er[]" size="5" value="<?php print $t->getEnsinoea()->getReprovados(); ?>" />

                        <input class="form-control"type="hidden"	onkeypress='return SomenteNumero(event)' onblur="percentagem();"
                               name="cod[]" size="5" value="<?php print $t->getEnsinoea()->getCodigo(); ?>" /></td>
                    <td><input class="form-control"type="text" name="p1[]" readonly="readonly" style="border: none;background-color: transparent"/></td>
                    <td><input class="form-control"type="text" name="p2[]" readonly="readonly" style="border: none;background-color: transparent"/></td>
                </tr>

            <?php
            }
        }
        ?>
        <tr>
            <th colspan="6" align="left"><?php print ($tiposea[7]->getModalidade()); ?>
            </th>
        </tr>
        <?php
        foreach ($tiposea as $t) {
            if (substr($t->getEnsino(), 1, 1) == "J") {
                ?>
                <tr>
                    <td>
        <?php print $t->getSerie();
        ?>

                    </td>
                    <td><input class="form-control" type="text" onblur="percentagem();" maxlength="4"
                                onkeypress="return SomenteNumero(event)" name="em[]" size="5"
                                value="<?php print $t->getEnsinoea()->getMatriculados(); ?>" /></td>
                    <td><input class="form-control" type="text"
                                onkeypress='return SomenteNumero(event)' onblur="percentagem();" maxlength="4"
                                name="ea[]" size="5" value="<?php print $t->getEnsinoea()->getAprovados(); ?>" /></td>
                    <td><input class="form-control"type="text"
                               onkeypress='return SomenteNumero(event)' onblur="percentagem();" maxlength="4"
                               name="er[]" size="5" value="<?php print $t->getEnsinoea()->getReprovados(); ?>" />

                        <input class="form-control"type="hidden"	onkeypress='return SomenteNumero(event)' onblur="percentagem();"
                               name="cod[]" size="5" value="<?php print $t->getEnsinoea()->getCodigo(); ?>" /></td>
                    <td><input class="form-control"type="text" name="p1[]" readonly="readonly" style="border: none;background-color: transparent"/></td>
                    <td><input class="form-control"type="text" name="p2[]" readonly="readonly" style="border: none;background-color: transparent"/></td>
                </tr>

            <?php
            }
        }
         }else{
         	
         ?>
         	
                <tr>
            <th colspan="6" align="left"><?php print ($tiposea[4]->getModalidade()); ?>
            </th>
        </tr>
        <?php
        foreach ($tiposea as $t) {
            if (substr($t->getEnsino(), 2, 1) == "I") {
                ?>
                <tr>
                    <td>
                        <?php print ($t->getSerie());
                        ?>
                    </td>
                    <td><input class="form-control" type="text" onblur="percentagem();" maxlength="4"
                                onkeypress="return SomenteNumero(event)" name="em[]" size="5"
                                value="<?php print $t->getEnsinoea()->getMatriculados(); ?>" /></td>
                    <td><input class="form-control" type="text"
                                onkeypress='return SomenteNumero(event)' onblur="percentagem();" maxlength="4"
                                name="ea[]" size="5" value="<?php print $t->getEnsinoea()->getAprovados(); ?>" /></td>
                    <td><input class="form-control"type="text"
                               onkeypress='return SomenteNumero(event)' onblur="percentagem();" maxlength="4"
                               name="er[]" size="5" value="<?php print $t->getEnsinoea()->getReprovados(); ?>" />

                        <input class="form-control"type="hidden"	onkeypress='return SomenteNumero(event)' onblur="percentagem();"
                               name="cod[]" size="5" value="<?php print $t->getEnsinoea()->getCodigo(); ?>" /></td>
                    <td><input class="form-control"type="text" name="p1[]" readonly="readonly" style="border: none;background-color: transparent"/></td>
                    <td><input class="form-control"type="text" name="p2[]" readonly="readonly" style="border: none;background-color: transparent"/></td>
                </tr>

            <?php
            }
        }
        ?>
        <tr>
            <th colspan="6" align="left"><?php print ($tiposea[5]->getModalidade()); ?>
            </th>
        </tr>
        <?php
        foreach ($tiposea as $t) {
            if (substr($t->getEnsino(), 2, 1) == "L") {
                ?>
                <tr>
                    <td>
        <?php print $t->getSerie();
        ?>

                    </td>
                    <td><input class="form-control" type="text" onblur="percentagem();" maxlength="4"
                                onkeypress="return SomenteNumero(event)" name="em[]" size="5"
                                value="<?php print $t->getEnsinoea()->getMatriculados(); ?>" /></td>
                    <td><input class="form-control" type="text"
                                onkeypress='return SomenteNumero(event)' onblur="percentagem();" maxlength="4"
                                name="ea[]" size="5" value="<?php print $t->getEnsinoea()->getAprovados(); ?>" /></td>
                    <td><input class="form-control"type="text"
                               onkeypress='return SomenteNumero(event)' onblur="percentagem();" maxlength="4"
                               name="er[]" size="5" value="<?php print $t->getEnsinoea()->getReprovados(); ?>" />

                        <input class="form-control"type="hidden"	onkeypress='return SomenteNumero(event)' onblur="percentagem();"
                               name="cod[]" size="5" value="<?php print $t->getEnsinoea()->getCodigo(); ?>" /></td>
                    <td><input class="form-control"type="text" name="p1[]" readonly="readonly" style="border: none;background-color: transparent"/></td>
                    <td><input class="form-control"type="text" name="p2[]" readonly="readonly" style="border: none;background-color: transparent"/></td>
                </tr>

            <?php
            }
        }
         	
         	
         	
         	
         	
         	
         }
        ?>
    </table>
    <input class="form-control"name="operacao" type="hidden" value="A" />
    <input type="button" onclick="direciona(1);" value="Gravar" />
</form>
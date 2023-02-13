<?php
//set_include_path(';../../includes');
//require_once('../../includes/dao/PDOConnectionFactory.php');
//require_once('../../includes/classes/sessao.php');
//session_start();
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[21]) {
    echo "Você não tem permissão para acessar esta opção de menu do sistema. Contate o administrador: sisraa@ufpa.br...";die;
}
//echo "teste";
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
if ($cont == 0) {
    Utils::redirect('ensinomedio', 'incluiemedio');

}
//echo "teste";
//ob_end_flush();
?>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li><a href="<?php echo Utils::createLink("ensinomedio", "incluiemedio"); ?>">Quantitativo do ensino m&eacute;dio</a></li>
			<li class="active">Consulta</li>
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
            <th align="center">Matriculados</th>
            <th align="center">Aprovados</th>
            <th align="center">Reprovados</th>
            <th align="center">%Aprov./Matr.</th>
            <th align="center">%Evas&atilde;o/Matr.</th>
        </tr>
        <?php
        foreach ($tiposea as $t) {
            if (substr($t->getEnsino(), 1, 1) == "E") {
                ?>
                <tr>
                    <td>
                        <?php print ($t->getSerie());
                        ?>
                    </td>
                    <td align="center"><?php print $t->getEnsinoea()->getMatriculados(); ?></td>
                    <td align="center"><?php print $t->getEnsinoea()->getAprovados(); ?></td>
                    <td align="center"><?php print $t->getEnsinoea()->getReprovados(); ?></td>
                    <td align="center"><?php if ($t->getEnsinoea()->getMatriculados()!=0){
                    	           print str_replace(".", ",", round(($t->getEnsinoea()->getAprovados() / $t->getEnsinoea()->getMatriculados()) * 100, 2));
                    }else print "0"; ?></td>
                    <td align="center"><?php
                        $evadidos = $t->getEnsinoea()->getMatriculados() - ($t->getEnsinoea()->getAprovados() + $t->getEnsinoea()->getReprovados());
                        if ($t->getEnsinoea()->getMatriculados()!=0){
                          $evasao = ($evadidos / $t->getEnsinoea()->getMatriculados()) * 100;
                        }else $evasao=0;
                        print str_replace(".", ",", round(($evasao), 2));
                        ?></td>			</tr>

            <?php
            }
        }
        if ($anoBase<2018){  ?>

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
                    <td align="center"><?php print $t->getEnsinoea()->getMatriculados(); ?></td>
                    <td align="center"><?php print $t->getEnsinoea()->getAprovados(); ?></td>
                    <td align="center"><?php print $t->getEnsinoea()->getReprovados(); ?></td>
                    <td align="center"><?php
                    if ($t->getEnsinoea()->getMatriculados()!=0){
                    print str_replace(".", ",", round(($t->getEnsinoea()->getAprovados() / $t->getEnsinoea()->getMatriculados()) * 100, 2)); 
            }else print "0";?></td>
                    
                    <td align="center"><?php
                        $evadidos = $t->getEnsinoea()->getMatriculados() - ($t->getEnsinoea()->getAprovados() + $t->getEnsinoea()->getReprovados());
                        if ($t->getEnsinoea()->getMatriculados()!=0){
                        $evasao = ($evadidos / $t->getEnsinoea()->getMatriculados()) * 100;
                        } else $evasao=0;
                        print str_replace(".", ",", round(($evasao), 2));
                        ?></td>
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
                    <td align="center"><?php print $t->getEnsinoea()->getMatriculados(); ?></td>
                    <td align="center"><?php print $t->getEnsinoea()->getAprovados(); ?></td>
                    <td align="center"><?php print $t->getEnsinoea()->getReprovados(); ?></td>
                    <td align="center"><?php 
                    if ($t->getEnsinoea()->getMatriculados()!=0){
                                   print str_replace(".", ",", round(($t->getEnsinoea()->getAprovados() / $t->getEnsinoea()->getMatriculados()) * 100, 2)); 
                    }else print "0";
            ?></td>
                                   
                    <td align="center"><?php
                        $evadidos = $t->getEnsinoea()->getMatriculados() - ($t->getEnsinoea()->getAprovados() + $t->getEnsinoea()->getReprovados());
                        if ($t->getEnsinoea()->getMatriculados()!=0){
                        $evasao = ($evadidos / $t->getEnsinoea()->getMatriculados()) * 100;
                        }else $evasao=0;
                        print str_replace(".", ",", round(($evasao), 2));
                        ?></td>			</tr>

            <?php
            }
        }
        }else{
         	
        	/*
        	 * Quando a cada ano novo ofertado deve-se modificar o indicae de $tiposea[4] e $tiposea[5]
        	 */
        	
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
                    <td align="center"><?php print $t->getEnsinoea()->getMatriculados(); ?></td>
                    <td align="center"><?php print $t->getEnsinoea()->getAprovados(); ?></td>
                    <td align="center"><?php print $t->getEnsinoea()->getReprovados(); ?></td>
                    <td align="center"><?php
                    if ($t->getEnsinoea()->getMatriculados()!=0){
                    print str_replace(".", ",", round(($t->getEnsinoea()->getAprovados() / $t->getEnsinoea()->getMatriculados()) * 100, 2)); 
            }else print "0";?></td>
                    
                    <td align="center"><?php
                        $evadidos = $t->getEnsinoea()->getMatriculados() - ($t->getEnsinoea()->getAprovados() + $t->getEnsinoea()->getReprovados());
                        if ($t->getEnsinoea()->getMatriculados()!=0){
                        $evasao = ($evadidos / $t->getEnsinoea()->getMatriculados()) * 100;
                        } else $evasao=0;
                        print str_replace(".", ",", round(($evasao), 2));
                        ?></td>
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
                    <td align="center"><?php print $t->getEnsinoea()->getMatriculados(); ?></td>
                    <td align="center"><?php print $t->getEnsinoea()->getAprovados(); ?></td>
                    <td align="center"><?php print $t->getEnsinoea()->getReprovados(); ?></td>
                    <td align="center"><?php 
                    if ($t->getEnsinoea()->getMatriculados()!=0){
                                   print str_replace(".", ",", round(($t->getEnsinoea()->getAprovados() / $t->getEnsinoea()->getMatriculados()) * 100, 2)); 
                    }else print "0";
            ?></td>
                                   
                    <td align="center"><?php
                        $evadidos = $t->getEnsinoea()->getMatriculados() - ($t->getEnsinoea()->getAprovados() + $t->getEnsinoea()->getReprovados());
                        if ($t->getEnsinoea()->getMatriculados()!=0){
                        $evasao = ($evadidos / $t->getEnsinoea()->getMatriculados()) * 100;
                        }else $evasao=0;
                        print str_replace(".", ",", round(($evasao), 2));
                        ?></td>			</tr>

            <?php
            }
        }
        
        
        
        
         }?>
    </table>
    <input class="form-control"name="operacao" type="hidden" value="A" />
    <input type="button" onclick="direciona(3);" value="Alterar" />

</form>
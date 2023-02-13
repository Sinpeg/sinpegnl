<?php
header('Content-Type: text/html; charset=utf-8');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Preenchimento' . date("d-m-Y") . '_' . date("H:m:s") . '.xls"');
header('Cache-Control: max-age=0');

require_once('../../dao/PDOConnectionFactory.php');
require_once('../../classes/sessao.php');
require('dao/UtilizacaoDAO.php');
require('classes/Utilizacao.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[42]) {
    exit();
}
$ano = $sessao->getAnobase();
$daoutil = new UtilizacaoDAO();
$rows = $daoutil->listaAplicacoes();
$unidades = NULL;
//$ano = 2011;
foreach ($rows as $row) {
//    if ($codUnidade == $row["CodUnidade"]) {
    $unidades[$row['NomeUnidade']][] = array(
        'aplicacao' => $row['Nome'],
        'codap' => $row['Codigo'],
        'unidade' => $row['NomeUnidade'],
        'codunidade' => $row['CodUnidade']
    );
}
//var_dump($unidades);
//}
$daoutil->fechar();
?>
<table>
    <tr>
        <th>Unidade</th>
        <th><?php echo ("Aplicação"); ?></th>
        <th><?php echo ("Situação"); ?></th>
        <th>Ano</th>
    </tr>
    <?php
    foreach ($unidades as $unidade => $uni) {
        $objut = new Utilizacao();
        // Ações do SIMEC
        $objut->condfigure(2, 'acao', array('`Ano` = ' => $ano, '`AnaliseCritica`' => '!=" "', '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Produção Intelectual
        $objut->condfigure(4, 'prodintelectual', null, "SELECT * FROM `prodintelectual` pi, `curso` c WHERE pi.`CodCurso` = c.`CodCurso` AND c.`CodUnidade` = " . $uni[0]['codunidade'] . " AND pi.`Ano` = $ano");
        // Prêmios
        $objut->condfigure(5, 'premios', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Computadores
        $objut->condfigure(6, 'micros', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Laboratorio
        $objut->condfigure(7, 'laboratorio', array('(`AnoAtivacao` = ' => $ano . " OR `AnoDesativacao` = $ano)", '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Infraestrutura de ensino
        $objut->condfigure(9, 'infraensino', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Estrutura de Acessibilidade
        $objut->condfigure(10, 'estrutura_acessibilidade', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Tecnologia Assistiva
        $objut->condfigure(11, 'tecnologia_assistiva', null, "SELECT * FROM `tecnologia_assistiva` ta, `curso` c WHERE ta.`CodCurso` = c.`CodCurso` AND c.`CodUnidade` = " . $uni[0]['codunidade'] . " AND ta.`Ano` = $ano");
        // Libras
        $objut->condfigure(12, 'librascurriculo', null, "SELECT * FROM `librascurriculo` lc, `curso` c WHERE lc.`CodCurso` = c.`CodCurso` AND c.`CodUnidade` = " . $uni[0]['codunidade'] . " AND lc.`Ano` = $ano");
        // Práticas Jurídicas
        $objut->condfigure(13, 'praticajuridica', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Produção das Clínicas
        $objut->condfigure(14, 'qprodsaude', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Patologia Tropical e Dermatologia
        $objut->condfigure(15, 'qprodsaude', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Produção Artística
        $objut->condfigure(18, 'prodartistica', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Atividades de Extensão
        $objut->condfigure(19, 'atividadeextensao', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
        //Quadro RH ICA
        $objut->condfigure(24, 'rhetemufpa', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
        //Produção da Farmácia
        $objut->condfigure(16, 'prodfarmacia', array('`Ano` = ' => $ano));
        // Frequentadores da Farmácia
        $objut->condfigure(17, 'freqfarmacia', array('`Ano` = ' => $ano));
        // Projetos de Extensão
        $objut->condfigure(25, 'ea_projextensao', array('`Ano` = ' => $ano));
        // Projetos de Pesquisa
        $objut->condfigure(20, 'ea_projpesquisa', array('`Ano` = ' => $ano));
        // Educação Profissional e Livre
        $objut->condfigure(26, 'edprofissionallivre', array('`Ano` = ' => $ano));
        // Portadores de necessidades especiais
        $objut->condfigure(27, 'pnd', array('`Ano` = ' => $ano));
        // Práticas de Intervenções Metodológicas
        $objut->condfigure(28, 'pi_metodologicas', array('`Ano` = ' => $ano));
        // Incubadora
        $objut->condfigure(30, 'prodincubadora', array('`Ano` = ' => $ano));
        // Ensino Fundamental
        $objut->condfigure(21, 'ensino_ea', array('`Codtdmensinoea` >= ' => '9', '`Codtdmensinoea` <= ' => '25', '`Ano` = ' => $ano));
        // Ensino Médio
        $objut->condfigure(22, 'ensino_ea', array('`Codtdmensinoea` >= ' => '1', '`Codtdmensinoea` <= ' => '8', '`Ano` = ' => $ano));
        // Infraestrutura
        $objut->condfigure(8, 'infraestrutura', array('`CodUnidade` = ' => $uni[0]['codunidade'], '(`AnoAtivacao` = ' => $ano . " OR `AnoDesativacao` = $ano)"));
        // PND
        $objut->condfigure(27, 'pnd', null, "SELECT * FROM `pnd` p, `curso` c WHERE p.`CodCurso` = c.`CodCurso` AND c.`CodUnidade` = " . $uni[0]['codunidade'] . " AND p.`Ano` = $ano");
       
        $utilReport = $objut->getConfig();
        $daout = new UtilizacaoDAO();
        $cont = 0;
        echo "<tr>";
        echo '<td style="vertical-align: middle;" rowspan='.count($unidades[$unidade]).">".$unidade."</td>";
        for ($i = 0; $i < count($uni); $i++) {
            if ($utilReport[$uni[$i]['codap']]['query'] != null) {
                $rows = $daout->consulta($utilReport[$uni[$i]['codap']]['query']);
                if ($rows->rowCount() >= 1) {
                    $status = 'possui informação';
                } else if ($rows->rowCount() == 0) {
                    $status = 'sem informação';
                }
            } else {
                $status = "Não apurado";
            }  
            echo "<td>" . $uni[$i]['aplicacao'] . "</td>\n";
            echo "<td>" . ($status) . "</td>\n";
            echo "<td>" . $ano . "</td>\n";
            echo "</tr>\n";
            $cont++;
        }
        echo "</tr>\n";;
        unset($objut);
        $daout->fechar();
    }
    ?>
</table>
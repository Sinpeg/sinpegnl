<?php
require_once('../../dao/PDOConnectionFactory.php');
require_once('../../modulo/utilizacao/dao/UtilizacaoDAO.php');
require_once('../../modulo/utilizacao/classes/Utilizacao.php');
require_once('../../classes/sessao.php');
require_once('../../dao/unidadeDAO.php');
require_once('../../classes/unidade.php');
require_once('../../util/Utils.php');
// include do banco
require_once '../../banco/include_dao.php';

session_start();
$sessao = $_SESSION["sessao"];
if (!isset($sessao)) {
    exit();
}
$codUnidade = $sessao->getCodUnidade(); /* código da unidade */
$anobase = $sessao->getAnoBase(); /* ano base de consulta */
$codsub = filter_input(INPUT_POST, 'subunidades', FILTER_DEFAULT);

$daoun = new UnidadeDAO();
$rows = $daoun->unidadeporcodigo($codUnidade);

$isSub = false; // indica se a subunidade é da unidade logada
foreach ($rows as $row) {
    $id_unidade = $row["id_unidade"]; // id da unidade responsável
}
// procura as subunidades que estão disponíveis para aquela unidade
$rows1 = $daoun->queryByUnidadeResponsavel($id_unidade);
foreach ($rows1 as $row) {
    if ($row["CodUnidade"] == $codsub) {
        $isSub = true;
    }
}
$ano = $sessao->getAnobase();
$daoutil = new UtilizacaoDAO();
$rows = $daoutil->listaAplicacoes();
$unidade = NULL;


foreach ($rows as $row) {
    if ($codsub == $row["CodUnidade"]) {
        $unidades[$row['NomeUnidade']][] = array(
            'aplicacao' => $row['Nome'],
            'codap' => $row['Codigo'],
            'unidade' => $row['NomeUnidade'],
            'codunidade' => $codsub // subunidade
        );
    }
}
$daoutil->fechar();
?>
<br/><table id="tablesorter" class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Formulário</th>
            <th>Ano</th>
            <th>Homologar</th>
            <th>Solicitar nova homologação</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(!isset($unidades)){

        	echo "<tr><td colspan='4' ><b>Não possui itens para homologar!</b></td></tr>";
        }else{        	
        foreach ($unidades as $unidade => $uni) {
            $objut = new Utilizacao();
            // Prêmios
            $objut->condfigure(5, 'premios', array('`Ano` = ' => $ano, '`CodSubunidade` = ' => $uni[0]['codunidade']));
            // Laboratorio
            $objut->condfigure(7, 'laboratorio', array('(`AnoAtivacao` <= ' => $ano . " OR `AnoDesativacao` <= $ano)", '`CodUnidade` = ' => $uni[0]['codunidade']));
            // Infraestrutura
            //$objut->condfigure(8, 'infraestrutura', array('`CodUnidade` = ' => $uni[0]['codunidade'], '(`AnoAtivacao` = ' => $ano . " OR `AnoDesativacao` = $ano)"));
            // PND
//          $objut->condfigure(27, 'pnd', null, "SELECT * FROM `pnd` p, `curso` c WHERE p.`CodCurso` = c.`CodCurso` AND c.`CodUnidade` = " . $uni[0]['codunidade'] . " AND p.`Ano` = $ano");
            // Computadores
            $objut->condfigure(6, 'micros', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade'])); 
            // Infraestrutura de ensino
         //   $objut->condfigure(9, 'infraensino', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
            // Estrutura de Acessibilidade
            $objut->condfigure(10, 'estrutura_acessibilidade', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
            // Práticas Jurídicas
            $objut->condfigure(13, 'praticajuridica', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
            //Produção Artística
            $objut->condfigure(18, 'prodartistica', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));            
            //Atividades de Extensão
            $objut->condfigure(19, 'atividadeextensao', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
            //Quadro RH ICA
            $objut->condfigure(24, 'rhetemufpa', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
            //Educação Profissional e Cursos Livres
            //$objut->condfigure(26, 'edprofissionallivre', array('`Ano` = ' => $ano));
          // echo $sessao->getUnidadeResponsavel()."teste";
           // if ($codUnidade==7092){//codigo da unidade nitae2
            	//estrutura de polos
             $objut->condfigure(51, 'poloEstrutura', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
            	
          //  }
            
            $utilReport = $objut->getConfig();
            $daout = new UtilizacaoDAO();
            for ($i = 0; $i < count($uni); $i++) {
            	
                if ($utilReport[$uni[$i]['codap']]['query'] != null) {
                    $codaplicacao = $uni[$i]["codap"]; // códigos das aplicacoes
                    /** O trecho abaixo trata se já existe homologação ou solicitação de reversão */
                    
                    $arr = DAOFactory::getHomologacaoDAO()->queryByCodAplicacao($codaplicacao);
                    
                    $revert = "";
                    for ($j = 0; $j < count($arr); $j++) {
                        $h = $arr[$j];
                        
                        if ($h->ano == $anobase && $h->codUnidade == $codUnidade && $h->codSub == $codsub && $h->codAplicacao == $codaplicacao
                        
                        && ($h->situacao == "H")) {
                            $aleatory = hash("sha256", "sisraaSalt" . rand(0, 100000));
                            $id = " id = $aleatory ";
                            $str = "?x=$aleatory&codap=$h->codAplicacao&codsub=$h->codSub&anobase=$anobase";
                            $revert = '<a class="novahomolog" href="#novahomologacao' . $str . '"><img src="webroot/img/unlock.png"' . $id . '/></a>';
                        } else if ($h->ano == $anobase && $h->codUnidade == $codUnidade && $h->codSub == $codsub && $h->codAplicacao == $codaplicacao && $h->situacao == "S") {
                            $revert = '<img src="webroot/img/lock.png"' . $id . '/></a>';
                        }
                    }                         
                    
                    $rows = $daout->consulta($utilReport[$uni[$i]['codap']]['query']);
                    if ($rows->rowCount() >= 1) {
                        $status = '<i class="glyphicon glyphicon-ok-sign" style="color: green"></i>';
                        $link = Utils::createLink('homologar', 'datalist', array("codunidade" => $codsub, "codapp" => $uni[$i]["codap"]));
                        $string = "<a href=" . $link . '><img src="webroot/img/busca.png"/></a>';
                    } else if ($rows->rowCount() == 0) {
                        $status = '<i class="glyphicon glyphicon-exclamation-sign" style="color:red"></i>';
                        $string = '<img src="webroot/img/warning.png"/>';
                    }
                    echo "<tr>\n";
                    echo "<td>" . $uni[$i]['aplicacao'] . "</td>\n";
                    echo "<td>" . $ano . "</td>\n";
                    echo "<td>" . $string . "</td>\n";
                    echo "<td>" . $revert . "</td>\n";
                    echo "</tr>\n";
                }
            }
            echo "</tr>\n";
            unset($objut);
            $daout->fechar();
        }
        } //fim do else
        ?>
    </tbody>
</table>

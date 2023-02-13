<?php
require_once '../../classes/sessao.php';
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../dao/servprocDAO.php';
require_once '../../dao/unidadeDAO.php';

//require_once '';
session_start();
$sessao = $_SESSION["sessao"];
//$nomeunidade = $sessao->getNomeUnidade();
$codunidade = $sessao->getCodUnidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
//$codestruturado = $sessao->getCodestruturado();
if ($sessao->getUnidadeResponsavel()>1){
    $udao= new UnidadeDAO();
    $rows=$udao->buscaidunidade($sessao->getUnidadeResponsavel());
    foreach ($rows as $r){
        $codunidade=$r['CodUnidade'];// quandoo usuario for local
    }
    
}
// Se os valores passados pelo método POST, unset $_SESSION['download']
if (($codunidade == 270 && !empty($_POST['local']) && !empty($_POST['subunidade']) && !empty($_POST['anobase'])) 
|| (($codunidade != 270) && !empty($_POST['subunidade']) && !empty($_POST['anobase']))) {
    $subunidade = $_POST['subunidade']; /* código da subunidade */
    $anobase = $_POST['anobase']; /* ano base */
    $local =$codunidade == 270?$_POST['local']:""; /* local */
   
}
if (($codunidade == 270 && !empty($_POST['local']) && !!empty($_POST['subunidade']) && $anobase==2018 )) {
    $subunidade = NULL; /* código da subunidade */
    $anobase = $_POST['anobase']; /* ano base */
    $local = $_POST['local']; /* local */
}
$daoserv = new ServprocDAO();

if ($codunidade == 270) { // ICS
	
	    $row = $daoserv->buscaservproced2($subunidade, $local, $anobase);
	
} else {
    $row = $daoserv->buscaservproced3($subunidade, $anobase);
}
?>

<?php if ($row->rowCount() > 0) { ?>
    <table id="tablesorter" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Serviço</th>
                <th>Procedimento</th>
                <th>Visualizar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $cont = 0;
            $servico = '';
            foreach ($row as $r) {
                if ($r['nomeServico'] != $servico) {
                    $servico = $r['nomeServico'];
                    $procedimeto = "";
                }
                if ($r['nomeProcedimento'] != $procedimeto) {
                    $procedimeto = $r['nomeProcedimento'];
                    ?>
                    <tr>
                        <td>
                            <?php print ($servico); ?>
                        </td>
                        <td>
                            <?php print ($procedimeto); ?>
                        </td>
                        <td>
                            <a href="?modulo=prodsaude4&acao=mostraprod&servico=<?php print $r['Codigo']; ?>&proced=<?php print $r['CodProcedimento']; ?>&sub=<?php echo $subunidade; ?>&local=<?php echo $local; ?>"><img src="webroot/img/busca.png" alt="txt"/></a>
                        </td>
                    </tr>
                <?php } ?>

                <?php
            }
            ?>
        </tbody>
    </table>
<?php } else { ?>
    <div id="error">
        <?php print "A pesquisa não retornou resultado"; ?>
    </div>
<?php } ?>

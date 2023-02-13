<?php
//$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
$codestruturado = $sessao->getCodestruturado();
$aplicacoes = $_SESSION["sessao"]->getAplicacoes();
$sessao = $_SESSION["sessao"];
//$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[14]) {
    header("Location:index.php");
}
//require_once '../../dao/PDOConnectionFactory.php';
//require_once('../../includes/classes/unidade.php');
require_once('classes/local.php');

//require_once('../../includes/classes/servico.php');
//require_once('../../includes/classes/procedimento.php');
//require_once('../../includes/classes/servproced.php');
//require_once('../../includes/dao/unidadeDAO.php');
//require_once('../../includes/dao/servprocDAO.php');

$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$unidade->setCodestruturado($codestruturado);
if ($sessao->getUnidadeResponsavel()>1){
    $udao= new UnidadeDAO();
    $rows=$udao->buscaidunidade($sessao->getUnidadeResponsavel());
    foreach ($rows as $r){
        $codunidade=$r['CodUnidade'];// quandoo usuario for local
    }
    
}

//$dados = $_SESSION['download'];
//require_once('../../dao/psaudemensalDAO.php');
//require_once('../../includes/classes/psaudemensal.php');
$erro = false;
$codservico = $_GET["servico"];
$codprocedimento = $_GET["proced"];
$codsubunidade = $_GET['sub'];
$codlocal = 0;

if ($codunidade == 270) {
    $codlocal = $_GET['local'];
    if (!is_numeric($codlocal) && $codlocal == "") {
        $erro = true;
    }
}

if (is_numeric($codservico) && is_numeric($codprocedimento) && is_numeric($codsubunidade)) {
    $daou = new UnidadeDAO();
    $rows = $daou->unidadeporcodigo($codsubunidade);
    foreach ($rows as $row) {
        $nomesubunidade = $row['NomeUnidade'];
    }

    $local = new Local();
    $local->setCodigo($codlocal);
    $unidade->criaSubunidade($codsubunidade, $nomesubunidade, null);
    $serv = new Servico();
    $proc = new Procedimento();
    $proc->setCodigo($codprocedimento);
    $unidade->getSubunidade()->criaServico($codservico, null);
    $daosp = new ServprocDAO();
    $rows = $daosp->buscacodservproced($codservico, $codsubunidade, $codprocedimento);
    foreach ($rows as $row) {
        $codservproced = $row['CodServProc'];
    }
    $unidade->getSubunidade()->getServico()->criaSp($codservproced, $proc);
    //rodapé
    $totalpatendidas = 0;
    $ndiscentes = 0;
    $ndocentes = 0;
    $nproc= 0;
    $npesq = 0;
    //=======
    $dao = new PsaudemensalDAO();
    if ($codunidade == 270) {
        $rows = $dao->ListaMensal1($anobase, $codlocal, $codservico, $codprocedimento);
    } else {
    	
        $rows = $dao->ListaMensal12($anobase, $codservico, $codprocedimento);
        
    }
    
    $cont = 0;
    foreach ($rows as $row) {
        $cont++;
        if ($codunidade == 270) {
            $local->setNome($row['local']);
        } else {
            $local->setNome("");
        }
        $proc->setNome($row['procedimento']);
        $unidade->getSubunidade()->getServico()->setNome($row['servico']);
        $unidade->getSubunidade()->getServico()->getSp()->adicionaItemPsaudemensal($row['Codigo'], $proc, $local, $anobase, $row['Mes'], $row['ndocentes'], $row['ndiscentes'], $row['npesquisadores'], $row['npessoasatendidas'],$row['nprocedimentos'], $row['nexames']);
    }
    $dao->fechar();
    
} else {
   echo "Os dados fornecidos não estão corretos!";
}



//ob_end_flush();
?>
<?php echo Utils::deleteModal('SinPEG', "Voce deseja remover o item selecionado?");?>
<div class="bs-example">
    <ul class="breadcrumb">
        <li class="active"><a href="<?php echo Utils::createLink('prodsaude4', 'incluipsaude5'); ?>">Consultar Produção da Área da Saúde</a> 
        <i class="fas fa-long-arrow-alt-right"></i>
        <a href="#" >Consultar Produção Mensal</a></li>  
    </ul>
</div>

<div class="card card-info">            
    <div class="card-header">
        <h3 class="card-title">Produ&ccedil;&atilde;o da &Aacute;rea da Sa&uacute;de</h3><br/>
    </div>
    <div class="card-body">
        <form class="form-horizontal" name="fpsaude4" id="fpsaude4" method="post">
        <div class="msg" id="msg"></div>

        <?php
        $mes = array(
            "janeiro", "feveireiro", "marco", "abril",
            "maio", "junho", "julho", "agosto", "setembro",
            "outubro", "novembro", "dezembro");
        if ($cont == 0) {
            echo "<p style='font-style: red;'>N&atilde;o h&aacute; registros!</p>";
        } else {
            ?>   

        
            <table class="table table-striped table-bordered">
                <tr>
                    <td width="70"><b>Subunidade:</b></td>
                    <td><?php print ($nomesubunidade); ?></td>
                </tr>
                <?php if ($codunidade == 270) { ?>
                    <tr>
                        <td width="70"><b>Local:</b></td>
                        <td><?php print  ($local->getNome()); ?></td>
                    </tr>
                <?php } else if ($codunidade == 202) { ?>
                    <tr>
                        <td width="70"><b>Local:</b></td>
                        <td><b>Cl&iacute;nica de Psicologia</b></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td width="70"><b>Servi&ccedil;o:</b></td>
                    <td><?php print ($unidade->getSubunidade()->getServico()->getNome()); ?></td>
                </tr>
                <tr>
                    <td width="70"><b>Procedimento:</b></td>
                    <td><?php print  ($proc->getNome()); ?></td>
                </tr>

            </table>
        </div>
            <br/>
            <!--  table table-striped table-bordered table-condensed table-hover-->
            <div class="card-body">
                <table width="200px" class="table table-bordered table-hover" id="tablesorter">
                    <tr align="center">
                        <th>M&ecirc;s</th>
                        <?php if ($codunidade == 270) { ?>
                            <th>Discentes</th>
                            <th>Docentes</th>
                            <th>Pesquisadores</th>
                            <th>Nº de Procedimentos</th>

                        <?php } ?>
                        <?php if ($codunidade == 270 || $codunidade == 202) { ?>
                            <th>Pessoas Atendidas</th>
                        <?php } else { ?>
                            <th>Exames</th>
                        <?php } ?>
                        <th>Alterar</th>
                        <th>Excluir</th>
                    </tr>
                    <?php foreach ($unidade->getSubunidade()->getServico()->getSp()->getPsaudeufpa() as $p) { ?>
                        <tr>
                            <td><?php
                                print $mes[$p->getMes() - 1];
                                ?>
                            </td>
                            <?php if ($codunidade == 270) { ?>
                                <td align="center"><?php
                                    print $p->getNdiscentes();
                                    $ndiscentes += $p->getNdiscentes();
                                    ?></td>
                                <td align="center"><?php
                                    print $p->getNdocentes();
                                    $ndocentes += $p->getNdocentes();
                                    ?></td>
                                <td align="center"><?php
                                    print $p->getNpesquisadores();
                                    $npesq += $p->getNpesquisadores();
                                    ?></td>
                                <td align="center"><?php
                                        print $p->getNprocedimentos();
                                        $nproc += $p->getNprocedimentos();
                                    ?></td>
                            <?php } ?>

                            <?php if ($codunidade == 270 || $codunidade == 202) { ?>
                                <td align="center"><?php
                                    print $p->getNpessoasatendidas();
                                    $totalpatendidas+=$p->getNpessoasatendidas();
                                    ?></td>
                            <?php } else { ?>
                                <td align="center"><?php
                                                    
                                
                                    print $p->getNexames();
                                    $totalpatendidas+=$p->getNexames();
                                    ?></td>
                            <?php } ?>

                            <td align="center">
                                <a href="?modulo=prodsaude4&acao=altpsaude4&codigo=<?php print $p->getCodigo(); ?>" target="_self" ><img src="webroot/img/editar.gif" alt="Alterar" title="Alterar" width="19" height="19" /> </a>
                                
                            </td>
                            <td align="center">
                            
                                
                        <img src="webroot/img/delete.png"   
                        data-toggle="modal"
                        class="imagemprod"
                        onclick="<?php echo 'opdelete('.$p->getCodigo().")";  ?>";
                        
                        data-target="#confirmDelete" alt="Excluir" width="19" height="19" />        
                
                                    </td>
                                
                                
                        </tr>
                    <?php } ?>
                <tr><td><b>Total</b></td>
                    <?php if ($codunidade == 270) { ?>
                        <td><u><?php echo $ndiscentes; ?></u></td>
                        <td><u><?php echo $ndocentes; ?></u></td>
                        <td><u><?php echo $npesq; ?></u></td>
                        <td><u><?php echo $nproc; ?></u></td>

                    <?php } ?>
                    <td align="center"><u><?php echo $totalpatendidas; ?></u></td><td></td><td></td></tr>
                </table>
            </div>
        <?php } ?>
    </form>
    <div class="card-body">
        <br/><a href="<?php echo Utils::createLink('prodsaude4', 'incluipsaude4'); ?>">
        <input type="button" class="btn btn-info" value="Incluir novo procedimento" /></a>
    </div>
</div>



<?php  require_once('util/delete-confirm.php');?>

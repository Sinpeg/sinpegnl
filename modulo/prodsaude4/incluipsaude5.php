<head>
	<div class="bs-example">
		<ul class="breadcrumb">
		    <li>Produção da área da saúde</li>
		</ul>
	</div>
</head>

<?php
//require_once('../../includes/classes/sessao.php');
//session_start();
//if (!isset($_SESSION["sessao"])) {
//    header("location:../../index.php");
//    exit();
//}
$sessao = $_SESSION["sessao"];

$anobase = $sessao->getAnoBase();
$hierarquia = $sessao->getCodestruturado();
//$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[14]) {
    echo "Você nao tem permissão para acessar este formulario!";
    die;
}

require_once('classes/local.php');

$daou = new UnidadeDAO();

if ($sessao->getUnidadeResponsavel()>1){
    $rows=$daou->buscaidunidade($sessao->getUnidadeResponsavel());
    foreach ($rows as $r){
        $codunidade=$r['CodUnidade'];// quandoo usuario for subunidade
        $nomeunidade=$r['NomeUnidade'];
        $codestruturado = $r['hierarquia_organizacional'];
        $resp=$r['unidade_responsavel'];
    }
    
}else{
    $rs = $daou->unidadeporcodigo($sessao->getCodUnidade());
    foreach ($rs as $r) {
        $nomeunidade=$r['NomeUnidade'];
    	$codestruturado = $r['hierarquia_organizacional'];
    	$resp=$r['unidade_responsavel'];
    }
}

$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$unidade->setCodestruturado($codestruturado);
$unidade->setUnidaderesponsavel($resp);
//require_once('../../includes/dao/psaudemensalDAO.php');
//require_once('../../includes/dao/servprocDAO.php');
//require_once('../../includes/classes/psaudemensal.php');

$tipounidade = ($codunidade == 1644)?"L":"P";
$rows = $daou->buscaSubunidadesCodestruturado($tipounidade, $codestruturado);

foreach ($rows as $row) {
    $unidade->adicionaItemSubunidade($row['CodUnidade'], $row['NomeUnidade'], $row['hierarquia_organizacional']);
}

$locais = array();
$cont = 0;
//echo $codestruturado; die;
$rows = $daou->buscalocais($codestruturado);

foreach ($rows as $row) {
    $cont++;
    $locais[$cont] = new Local();
    $locais[$cont]->setCodigo($row['CodUnidade']);
    $locais[$cont]->setNome($row['NomeUnidade']);
    $locais[$cont]->setTipo($row['TipoUnidade']);
}
?>        

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Produ&ccedil;&atilde;o da &Aacute;rea da Sa&uacute;de</h3>
    </div>
    <form class="form-horizontal" name="fpsaude4" id="fpsaude4" method="post" action="ajax/prodsaude4/buscaprodsaude.php">
        <table class="card-body">
            <tr>
                <td class="coluna1">  
                    <label>Subunidade</label>
                </td>
            </tr>
            <tr>  
                <td class="coluna2">  
                    <select class="custom-select" name="subunidade"  id="subunidade" class="sel1">
                        <option selected="selected" value="0"> Selecione uma subunidade...</option>
                        <?php if ($sessao->getUnidadeResponsavel()==1 || $codunidade !=1644 ){ ?>
                            <?php foreach ($unidade->getSubunidades() as $sub) { ?>
                                <option value="<?php print $sub->getCodunidade(); ?>">
                                    <?php print ($sub->getNomeunidade()); ?>
                                </option>
                            <?php }//foreach
                        } else {?>
                            <option value="<?php print $sessao->getCodUnidade(); ?>">
                            <?php print ($sessao->getNomeUnidade()); ?></option>
                
                        <?php } ?>
                    </select>
                </td>
            </tr>
        
            <?php if ($codunidade != 1644) {//nmt?>
                <tr>   
                    <td class="coluna1">   
                        <label>Local</label>
                    </td>
                </tr>
                <tr>
                    <td class="coluna2"> 
                        <?php // if ($sessao->getUnidadeResponsavel()==1){?>
                        <select id="local" name="local" class="sel1">
                            <option value="0">Selecione local...</option>
                            <?php foreach ($locais as $l) { ?>
                                <option  value="<?php print $l->getCodigo(); ?>">
                                    <?php print ($l->getNome()); ?>
                                </option>
                            <?php } ?>
                        </select>
                        <?php //} ?> 
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan=2>
                        <br/>
                        <input type="button" value="Consultar" class="btn btn-info" id="buscaprodsaude"></input>
                        <a href="<?php echo Utils::createLink('prodsaude4', 'incluipsaude4'); ?>" >
                        <button id="mostraTelaCadastro" type="button" class="btn btn-info btn">Incluir novo procedimento</button></a>  
                        <div>
                            <input class="form-control"type="hidden" value="<?php print $anobase; ?>" name="anobase"/>
                        </div>
                        <br>
                        <div>
                            Relatório Produção de Saúde: <a href="relatorio/relatorio_prodSaude.php?anoBase=<?php echo $anobase;?>&cod_unidade=<?php echo $codunidade;?>"><img src="webroot/img/pdf.png"></a>&nbsp;&nbsp;&nbsp;<a href="relatorio/relatorio_prodSaudeExcel.php?anoBase=<?php echo $anobase;?>&cod_unidade=<?php echo $codunidade;?>"><img width="20px" height="20px" src="webroot/img/excel.png"></a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </form>
    <div class="card-body">
        <div id="resultado">
        </div>          
    </div>                  
</div>
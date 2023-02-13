<?php
require_once('../../classes/sessao.php');
require_once('../../dao/PDOConnectionFactory.php');
require_once('../../modulo/infra/dao/infraDAO.php');
require_once(dirname(__FILE__) . '/../../classes/validacao.php');
session_start();
if (!isset($_SESSION["sessao"])) {
    header("location:../../index.php");
}
?> 
<?php
$sessao = $_SESSION["sessao"];
$aplicacoes = $_SESSION["sessao"]->getAplicacoes();
if (!$aplicacoes[33]) {
    exit();
}
?> 
<?php
$unidade = $_POST['selunidade']; // código da unidade 
$situacao = $_POST['situacao']; // situacao da unidade 
$ano = addslashes($_POST['ano']); // ano 

/* * ******************************* VALIDAÇÃO DOS DADOS ******************************************* */
// 1 - Ano 
$validacao = new Validacao(); // objeto para o módulo de validação 
if ($validacao->is_yearempty($ano)) {
    $error = "Por favor, informe o ano!";
} else if (!$validacao->is_validyear($ano)) {
    $error = "Ano inválido, por favor informe outro valor para este campo";
}
// 2 - Situação 
else if ($situacao != "A" && $situacao != "D") {
    $error = "A situação informada não existe!";
}
// 3 - Unidade 
else if (($unidade != "todas" && $unidade != "institutos" && $unidade != "campus" && $unidade != "nucleos") && (!preg_match("/^[1-9][0-9]{0,}/", $unidade))) {
    $error = "Unidade não encontrada! " . $unidade;
}
/* * *********************************************************************************************** */ else {
    $daoin = new InfraDAO(); // objeto de acesso aos dados da infraestrutura 
    switch ($unidade) {
        case "todas":
            $sql_param = "";
            break;
        case "institutos":
            $sql_param = "AND `NomeUnidade` LIKE 'instituto%'";
            break;
        case "campus":
            $sql_param = "AND `NomeUnidade` LIKE 'campus%'";
            break;
        case "nucleos":
            $sql_param = "AND `NomeUnidade` LIKE 'nucleo%'";
            break;
        default:
            $sql_param = "AND u.`CodUnidade` = " . $unidade;
            break;
    }
    $colunas = array('Nome da Unidade', 'Nome da Infraestrutura', 'Tipo', 'Forma', 'PCD', 'Área', 'Capacidade',
        'Hora de Início', 'Hora de Fim');
    $row = $daoin->buscaInfraAdmin($ano, $situacao, $sql_param);
    ?> 
    <?php
    if ($row->rowCount() == 0) {
        $error = "Nenhum resultado foi encontrado!";
        ?> 
    <?php } else {
        ?> 
        <table id="tablesorter" class="table table-bordered table-hover"> 
            <thead> 
                <tr> 
                    <?php foreach ($colunas as $c) { ?> 
                        <th><?php echo $c ?> 
                        </th> 
                    <?php } ?> 
                </tr> 
            </thead> 
            <tfoot> 
                <tr> 
                    <th colspan="9" class="ts-pager form-horizontal"> 
                        <button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button> 
                        <button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button> 
                        <span class="pagedisplay"></span> <!-- this can be any element, including an input --> 
                        <button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button> 
                        <button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button> 
                        <select class="pagesize input-mini" title="Select page size"> 
                            <option selected="selected" value="10">10</option> 
                            <option value="20">20</option> 
                            <option value="30">30</option> 
                            <option value="40">40</option> 
                        </select> 
                        <select class="pagenum input-mini" title="Select page number"></select> 
                    </th> 
                </tr> 
            </tfoot> 
            <tbody> 
                <?php foreach ($row as $r) { ?> 
                    <tr> 
                        <td><?php echo ($r['NomeUnidade']); ?> 
                        </td> 
                        <td><?php echo ($r['NomeInfra']); ?> 

                        <td><?php echo ($r['NomeTipo']); ?> 
                        </td> 
                        <td><?php echo ($r['FORMA']); ?> 
                        </td> 
                        <td><?php echo $r['PCD']; ?> 
                        </td> 
                        <td><?php echo str_replace(".", ",", $r['Area']); ?> 
                        </td> 
                        <td><?php echo $r['Capacidade']; ?> 
                        </td> 
                        <td><?php echo $r['HoraInicio']; ?> 
                        </td> 
                        <td><?php echo $r['HoraFim']; ?> 
                        </td> 
                    </tr> 
                <?php } ?> 
            </tbody> 
        </table> 
        <div> 
            <form name="form1" method="POST" action="relatorio/infra/exportinfra.php" id="xls"> 
                <input type="hidden" name="ano" <?php echo "value=" . $ano; ?> /> <input 
                    type="hidden" name="situacao" <?php echo "value=" . $situacao; ?> /> <input 
                    type="hidden" name="unidade" <?php echo "value=" . $unidade; ?> /> 
            </form> 
        </div> 
        <ul class="excel"> 
            <li><a href="relatorio/infra/exportinfra.php?ano=<?php echo $ano;?>&situacao=<?php echo $situacao;?>&unidade=<?php echo $unidade;?>">Planilha para download</a></li> 
        </ul> 
        <?php
		//Inicia uma sessão para download, isto é, somente é permitido realizar o download da planilha 
        session_start();
        $dados = array("ano" => $ano, "unidade" => $unidade, "situacao" => $situacao);
        $_SESSION["download"] = $dados;
        ?><?php } ?> 
<?php } ?> 
<div id="error"> 
    <?php print $error; ?> 
</div> 

<?php
require_once('../../classes/sessao.php');
require_once('../../dao/PDOConnectionFactory.php');
require_once '../../modulo/infraensino/dao/tipoinfraensinoDAO.php';
require_once('../../classes/validacao.php');
?> 
<?php
$ano = addslashes($_POST['ano']); // ano inicial 
$ano1 = addslashes($_POST['ano1']); // ano final 
$unidade = addslashes($_POST['selunidade']); // unidade selecionada 
$tipo = $_POST['tipo']; // tipo da infraestrutura de ensino 
if ($ano1 == "") {
    $ano1 = $ano;
}
/* * ***************************** VALIDAÇÃO DOS DADOS RECEBIDOS *********************************** */
$validacao = new Validacao(); // objeto para validação dos dados 
// 1 - Ano 
if ($validacao->is_yearempty($ano)) {
    $error = "Por favor, informe o ano inicial!";
} else if (!$validacao->is_validyear($ano)) {
    $error = "Ano inicial inválido!";
}
// 2 - Ano1 
else if (!$validacao->is_validyear($ano1) && !$validacao->is_yearempty($ano1)) {
    $error = "Ano final inválido!";
} elseif ($validacao->is_validyear($ano1) && $validacao->is_validyear($ano) && ($ano1 < $ano)) {
    $error = "O ano final deve ser maior ou igual ao inicial.";
}
// 3 - Unidade 
else if (($unidade != "todas" && $unidade != "institutos" && $unidade != "campus" && $unidade != "nucleos") && (!preg_match("/^[1-9][0-9]{0,}/", $unidade))) {
    $error = "Unidade não encontrada! ";
}
// 4 - Tipo 
else if (($tipo > 12 || $tipo < 0)) {
    $error = "Tipo inválido!";
} else {
    /*     * ************************************* FIM DA VALIDAÇÃO **************************************** */
    switch ($unidade) {
        case "todas":
            $sql_param = "";
            break;
        case "institutos":
            $sql_param = " AND u.`NomeUnidade` LIKE 'instituto%'";
            break;
        case "campus":
            $sql_param = " AND u.`NomeUnidade` LIKE 'campus%'";
            break;
        case "nucleos":
            $sql_param = " AND u.`NomeUnidade` LIKE 'nucleo%'";
            break;
        default:
            $sql_param = " AND u.`CodUnidade` = " . $unidade;
            break;
    }
    $daotie = new TipoinfraensinoDAO();
    $row = $daotie->buscatipo1($tipo, $ano, $ano1, $sql_param);
    if ($row->rowCount() == 0) {
        $error = "Nenhum resultado foi encontrado!";
    } else {
        ?> 
        <table id="tablesorter" class="table table-bordered table-hover"> 
            <thead> 
                <tr> 
                    <th>Nome da Unidade</th> 
                    <th>Tipo da Infraestrutura</th> 
                    <th>Quantidade</th> 
                    <th>Ano</th> 
                </tr> 
            </thead> 
            <tfoot> 
                <tr> 
                    <th colspan="7" class="ts-pager form-horizontal"> 
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
                        <td><?php echo $r['NomeUnidade']; ?></td> 
                        <td><?php echo ($r['Nome']); ?></td> 
                        <td><?php echo $r['Quantidade']; ?></td> 
                        <td><?php echo $r['Ano']; ?></td> 
                    </tr> 
                <?php } ?> 
            </tbody> 
        </table> 
        <div> 
            <form name="form1" method="POST" 
                  action="relatorio/infraensino/exportinfraensino.php" id="xls"> 
                <input type="hidden" name="tipo" <?php echo "value=" . $tipo; ?> /> <input 
                    type="hidden" name="ano" <?php echo "value=" . $ano; ?> /> <input 
                    type="hidden" name="ano1" <?php echo "value=" . $ano1; ?> /> <input 
                    type="hidden" name="unidade" <?php echo "value=" . $unidade; ?> /> 
            </form> 
        </div> 
        <div> 
            <ul class="excel"> 
                <li><a href="#" id="relatorioXLS">Planilha (versão completa)</a></li> 
            </ul> 
        </div> 
    <?php } ?> 
<?php } ?> 
</div> 
<?php if (isset($error)) { ?> 
    <div id="error"> 
        <?php print $error; ?> 
    </div> 
<?php } ?> 


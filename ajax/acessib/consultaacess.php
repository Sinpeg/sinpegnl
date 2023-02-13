<?php 
//require_once('../../includes/classes/sessao.php'); 
require_once '../../classes/sessao.php';
session_start();
if (!isset($_SESSION["sessao"])) {
// header("location:../../index.php"); 
    exit();
}

?>

 <style>
/* The Modal (background) */
#modalLoading {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}


.loader {
  left:50%;
  position:absolute;
  top:40%;
  left:45%;	
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 100px;
  height: 100px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}
<!--
.modal {
  text-align: center;
  padding: 0!important;
  
}

.modal:before {
  content: '';
  display: inline-block;
  height: 100%;
  vertical-align: middle;
  margin-right: -4px;
}

.modal-dialog {
  display: inline-block;
  text-align: left;
  vertical-align: middle;
  width:55%;
}
-->


/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

<!-- Modal Loading-->
<div class="" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="modalLoading" aria-hidden="true">  
    <div class="loader"></div> 
</div>


<?php
$sessao = $_SESSION["sessao"];
if ($sessao->getGrupo() != 1) {
   // header("location:../../index.php");
}

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../modulo/acessib/dao/tpacessibilidadeDAO.php';
require_once '../../classes/validacao.php';

$ano = $_POST['ano']; // ano inicial 
$ano1 = $_POST['ano1']; // ano final 
$tipo = $_POST['tipo']; // tipo da infraestrutura de ensino 
$unidade = $_POST['selunidade']; // unidade 
if ($ano1 == "") {
    $ano1 = $ano;
}
// print $tipo; 
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
}
// 3 - Ano1 não deve ser menor que ano 
elseif ($validacao->is_validyear($ano) && $validacao->is_validyear($ano1) && ($ano1 < $ano)) {
    $error = "O ano final deve ser maior ou igual ao inicial.";
}
// 4 - Unidade 
else if (($unidade != "todas" && $unidade != "institutos" && $unidade != "campus" && $unidade != "nucleos") && (!preg_match("/^[1-9][0-9]{0,}/", $unidade))) {
    $error = "Unidade não encontrada! ";
}
// 5 - Tipo 
//else if ($tipo != "AR" && $tipo != "BA" && $tipo != "EE" && $tipo != "MA" && $tipo != "RA" && $tipo != "todos") {
//    $error = "Tipo inválido!";
//}
/* * ************************************* FIM DA VALIDAÇÃO **************************************** */ else {
    switch ($unidade) {
        case "todas":
            break;
        case "institutos":
            $sqlparam = "AND `NomeUnidade` LIKE 'instituto%'";
            break;
        case "campus":
            $sqlparam = "AND `NomeUnidade` LIKE 'campus%'";
            break;
        case "nucleos":
            $sqlparam = "AND `NomeUnidade` LIKE 'nucleo%'";
            break;
        default:
            $sqlparam = "AND u.`CodUnidade` = " . $unidade;
            break;
    }
    $daoacess = new TpacessibilidadeDAO();
    $row = $daoacess->buscaestruturaacess($ano, $ano1, $tipo, $sqlparam);
    $colunas = array("Unidade Acadêmica", "Tipo da Infraestrutura", "Quantidade", "Ano");
    if ($row->rowCount() == 0) {
        $error = "Nenhum resultado foi encontrado!";
    } else {
        ?> 
        <table id="tablesorter" class="table table-bordered table-hover"> 
            <thead> 
                <tr> 
                    <?php foreach ($colunas as $c) { ?> 
                        <th><?php print $c; ?></th> 
                    <?php } ?> 
                </tr> 
            </thead>
           
            <tbody> 
                <?php foreach ($row as $r) { ?> 
                    <tr> 
                        <td><?php print ($r['NomeUnidade']); ?></td> 
                        <td><?php print ($r['Nome']); ?></td> 
                        <td><?php print $r['Quantidade']; ?></td> 
                        <td><?php print $r['Ano']; ?></td> 
                    </tr> 
                <?php } ?> 
            </tbody> 
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
        </table> 
        <div> 
            <form name="form1" method="POST" action="relatorio/acessib/exportacessib.php" 
                  id="xls"> 
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
        <?php
    }
}

 if (isset($error)) { ?> 
    <div id="error"> 
        <?php print $error; ?> 
    </div> 
<?php } ?> 

<?php 
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
require_once '../../classes/sessao.php'; 
require_once '../../classes/validacao.php'; 
session_start(); 
$aplicacoes = $_SESSION["sessao"]->getAplicacoes(); 
if (!$aplicacoes[32]) { 
 header("Location:../../index.php"); 
 exit; 
} 
if (!isset($_SESSION["sessao"])) { 
 header("Location:../../index.php"); 
} 


$sessao = $_SESSION["sessao"]; 

$ano_inicio = addslashes($_POST['ano']); // ano de inicio 
$ano_fim = addslashes($_POST['ano1']); // ano final 
$unidade = addslashes($_POST['selunidade']); // unidade 
$situacao = $_POST['situacao']; // situacao 
 
if(isset($_POST["curso"])){
	$curso = "curso"; // curso
}else{
	$curso = "";
}
/* * *************************************VALIDAÇÃO************************************************* */ 

$valida = new Validacao(); 
if (($unidade != "todas" && $unidade != "institutos" && $unidade != "campus" && $unidade != "nucleos") && (!preg_match("/^[1-9][0-9]{0,}/", $unidade))) { 
 $error = "Unidade não encontrada! "; 
} 
if ($valida->is_yearempty($ano_inicio)) { 
 $error = "<div class='alert alert-warning' role='alert'>Preencha o ano de Início!</div>"; 
} else if (!$valida->is_validyear($ano_inicio)) { 
 $error = "<div class='alert alert-warning' role='alert'>Por favor, informe corretamente o ano de Início!</div>"; 
} else if ($valida->is_yearempty($ano_fim)){ 
 $error = "<div class='alert alert-warning' role='alert'>Por favor, informe corretamente o ano de Término!</div>"; 
} else if (($ano_fim < $ano_inicio) && $valida->is_yearempty($ano_fim)) { 
 $error = "<div class='alert alert-warning' role='alert'>O ano final deve ser maior ou igual ao inicial.</div>"; 
} 

if ($unidade=="") {
    $error = "<div class='alert alert-warning' role='alert'>Por favor, informe as <b>Unidades</b>.</div>";
}

/* * *********************************************************************************************** */ else { 

 if ($ano_fim == "") 
 $ano_fim = $ano_inicio; 
 
 require_once '../../dao/PDOConnectionFactory.php'; 
 require_once '../../modulo/labor/dao/laboratorioDAO.php'; 
 
 $sql_param="";
 switch ($unidade) { 
 case "todas": 
 break; 
 case "institutos": 
 $sql_param .= " AND ul.`NomeUnidade` LIKE 'instituto%'"; 
 break; 
 case "campus": 
 $sql_param .= " AND ul.`NomeUnidade` LIKE 'campus%'"; 
 break; 
 case "nucleos": 
 $sql_param .= " AND ul.`NomeUnidade` LIKE 'nucleo%'"; 
 break; 
 default: 	
 	// caso selecione a consulta para o curso 
 $sql_param .= " AND l.`CodUnidade` = " . $unidade;
 
 } 
 
 
 $daolab = new LaboratorioDAO(); 
 $row = $daolab->buscaLabUnid($sql_param, $curso, $situacao, $ano_inicio, $ano_fim); 
 if ($row->rowCount() == 0) 
 $error = "Nenhum resultado encontrado para a versão completa do relatório!"; 
 else {
?> 
 <hr/>
 <center>
 <a href="#" id="anuarioXLS"><button class="btn btn-primary" >Planilha (versão do anuário)</button></a>&nbsp;&nbsp;&nbsp;
 <a href="#" id="relatorioXLS"><button class="btn btn-primary" >Planilha (versão completa)</button></a>
 </center> 
 <br/><br/>
 <table id="tablesorter" class="table table-bordered table-hover"> 
 <thead> 
 <tr> 
 <th>Unidade do Laboratório</th> 
 <th>Laboratório</th> 
 <?php if ($curso == "curso") { ?> 
 <th><?php print "Curso" ?></th> 
 <?php } ?> 
 <th>Categoria</th> 
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
 <td><?php print $r['NomeUnidade']; ?></td> 
 <td><?php print ($r['Laboratorio']); ?></td> 
 <?php if ($curso == "curso") { ?> 
 <td><?php print $r['NomeCurso']; ?></td> 
 <?php } ?> 
 <td><?php print ($r['Categoria']); ?></td> 
 <td><?php print $r['AnoAtivacao']; ?></td> 
 </tr> 
 <?php } ?> 
 </tbody> 
 </table> 
 
 <div> 
 <form name="form1" method="POST" action="relatorio/labor/exportlab.php" id="xls"> 
 <input type="hidden" name="curso" <?php print "value=" . '"' . $curso . '"'; ?> /> 
 <input type="hidden" name="ano" <?php print "value=" . $ano_inicio; ?> /> 
 <input type="hidden" name="situacao" <?php print "value=" . '"' . $situacao . '"'; ?> /> 
 <input type="hidden" name="unidade" <?php print "value=" . $unidade; ?> /> 
 <input type="hidden" name="ano1" <?php print "value=" . $ano_fim; ?> /> 
 <?php $_SESSION["download"] = array('ano' => $ano_inicio, 'ano1' => $ano_fim,'unidade' => $unidade, 'situacao' => $situacao); ?> 
 </form> 
 </div> 
 
 <!-- Formulario para a disponibilização da planilha para o anuário --> 
 <form name="form2" method="POST" action="relatorio/labor/exportlab_anuario.php" id="xls_anuario"> 
	 <input type="hidden" name="ano_anuario" <?php print "value=" . $ano_inicio; ?> /> 
	 <input type="hidden" name="situacao_anuario" <?php print "value=" . '"' . $situacao . '"'; ?> /> 
	 <input type="hidden" name="unidade_anuario" <?php print "value=" . $unidade; ?> />
	 <input type="hidden" name="ano1_anuario" <?php print "value=" . $ano_fim; ?> /> 
 </form> 
 <!-- Fim -->   
 <?php }  
} ?> 
<?php if (isset($error)) { ?> 
 <div id="error"> 
 <p> 
 <?php print $error; ?> 
 </p> 
 </div> 
 
<?php }?> 
 

<?php
//ob_start();
//require_once('../../includes/classes/sessao.php');

//session_start(); - Sessão já inicializada

$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[7]) {
  exit();
} else {
 $sessao = $_SESSION["sessao"];
 $nomeunidade = $sessao->getNomeunidade(); 
 $codunidade = $sessao->getCodunidade();
// $responsavel = $sessao->getResponsavel();
// $anobase = $sessao->getAnobase();
// require_once('../../includes/dao/PDOConnectionFactory.php');
 require_once('dao/labcursoDAO.php');
 require_once('classes/labcurso.php');
// require_once('../../includes/classes/campus.php');
// require_once('../../includes/classes/curso.php');
// require_once('../../includes/classes/unidade.php');
 require_once('classes/laboratorio.php');
 require_once('classes/tplaboratorio.php');
 require_once('dao/laboratorioDAO.php');
 $daolabcurso = new LabcursoDAO();
 $daolab = new LaboratorioDAO();
 $codlab = $_GET["codlab"];
 $tipolab = new Tplaboratorio();
 
 if ($codlab != "" && is_numeric($codlab)) {
 //busca unidade
 $unidade = new Unidade();
 $unidade->setCodunidade($codunidade);
 $unidade->setNomeunidade($nomeunidade);
 $rows = $daolab->buscaLaboratorio($codlab,$sessao->getAnobase());
 foreach ($rows as $row) {
 //cria tipo do lab para criar lab
 $tipolab->setCodigo($row["Tipo"]);
 $lab = $unidade->criaLabv2($codlab,  $row["nomelab"], null, null, null, null, null, null,
     null, null, null, null);
 }
 
 $cont = 0;
 //busca cursos do lab
 $rows = $daolabcurso->buscaCursosLaboratorio($lab);
 foreach ($rows as $row) {
 $campus = new Campus();
 $campus->setCodigo($row['CodCampus']);
 $campus->setNome($row['Campus']);
 $curso = $campus->criaCurso($row["CodCursoSis"], $unidade, $row["CodCurso"], $row["NomeCurso"], $row["DataInicio"], $row["CodEmec"]);
 $lab->adicionaItemLabcursos($row["CodLabCurso"], $curso);
 $cont++;
 }
 //var_dump($lab->getLabcursos());
 }
 $daolab->fechar();
}
//ob_end_flush();
?>
<?php echo Utils::deleteModal('Curso', 'Você tem certeza que deseja remover o curso vinculado a este laboratório?'); ?>
<head>
<div class="bs-example">
		<ul class="breadcrumb">
		    <li><a href="<?php echo Utils::createLink("laborv2", "consultalab"); ?>">Consultar laboratórios</a></li>
			<li class="active">Laboratório por Curso</li>
		</ul>
	</div>
</head>

<form class="form-horizontal" name="fconsultar" method="post">
 <h3 class="card-title">Cursos - Laborat&oacute;rio</h3><br/>
 <b>Nome do laborat&oacute;rio:</b>
 <?php print ($lab->getNome()); ?>
 <br /> <input class="form-control"type="hidden" name="codlab"
 value="<?php print $lab->getCodlaboratorio(); ?>" /> <input
 type="hidden" name="nomelab" value="<?php print $lab->getNome(); ?>" />
 <br />
 <!-- Busca laboratório por nome:<input class="form-control"type="text" size=50 name="parametro"/><input type="button" onchange="ajaxExibelabs();" value="ok"/><br/> -->
 <b>Cursos vinculados ao laborat&oacute;rio:</b><br />
 <?php if ($cont > 0) { ?>
 <table id="tablesorter" class="table table-bordered table-hover">
 <tr align="center" style="font-weight: bold;">
 <td>Campus</td>
 <td>C&oacute;digo Emec</td>
 <td>Curso</td>
 <td>Excluir</td>
 </tr>
 <?php foreach ($lab->getLabcursos() as $lab1) { ?>
 <tr>
 <td><?php print $lab1->getCurso()->getCampus()->getNome(); ?></td>
 <td><?php print $lab1->getCurso()->getCodemec(); ?></td>
 <td><?php print $lab1->getCurso()->getNomecurso(); ?></td>
 <td align="center">
 <a href="<?php echo Utils::createLink('labor', 'dellabcurso', array('codlabcurso'=>$lab1->getCodlabcurso(),'codlab'=>$lab1->getLaboratorio()->getCodlaboratorio()));?>" target="_self" class="delete-link" alt="Excluir"><img src="webroot/img/delete.png" width="19" height="19" /> </a>
 </td>
 </tr>
 <?php } ?>
 </table>
 <?php
 } else {
 print "Nenhum curso vinculado ao laboratório.";
 }
 ?><br/>
 <input class="btn btn-info" value="Vincular curso" type="button" onclick="direcionacurso(1,<?php echo $codlab;?>);" />&ensp;&ensp;
    <a href="<?php echo Utils::createLink("labor", "consultalab"); ?>">
    </a>
</form>
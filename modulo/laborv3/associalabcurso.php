<?php
ob_start();
echo ini_get('display_errors');
if (!ini_get('display_errors')) {
 ini_set('display_errors', 1);
 ini_set('error_reporting', E_ALL & ~E_NOTICE);
}
?>
<?php
//ob_start();
//set_include_path(';../../includes');
require_once('../../includes/classes/sessao.php');
session_start();
if (!isset($_SESSION["sessao"])){
 header("location:index.php");
}
else {
 $sessao = $_SESSION["sessao"];
 $nomeunidade = $sessao->getNomeunidade();
 $codunidade = $sessao->getCodunidade();
 $responsavel = $sessao->getResponsavel();
 $anobase = $sessao->getAnobase();
 require_once('../../includes/dao/PDOConnectionFactory.php');
 // var_dump($usuario);
 require_once('dao/laboratorioDAO.php');
 require_once('classes/laboratorio.php');
 require_once('dao/tplaboratorioDAO.php');
 require_once('classes/tplaboratorio.php');
 require_once('../../includes/classes/unidade.php');
 $daolab= new LaboratorioDAO();
 $daotipolab= new TplaboratorioDAO();
 $unidade=new Unidade();
 $unidade->setCodunidade($codunidade);
 $unidade->setNomeunidade($nomeunidade);
 $cont = 0;
 $rows_tlab=$daotipolab->Lista();
 foreach ($rows_tlab as $row){
 $tiposlab[$cont]=new Tplaboratorio();
 $tiposlab[$cont]->setCodigo($row['Codigo']);
 $tiposlab[$cont]->setNome($row['Nome']);
 $cont++;
 }
 $rows = $daolab->buscaLaboratoriosUnidade($codunidade);
 foreach ($rows as $row){
 $tipo=$row['Tipo'];
 foreach ($tiposlab as $tipolab){
 if ($tipolab->getCodigo() == $tipo){
 $tplab = $tipolab;
 }
 }
 $unidade->adicionaItemLabs($row['CodLaboratorio'],$unidade,$tplab,$row['Nome'],$row['Capacidade'],$row['Sigla'],$row['LabEnsino'],$row['Area'],$row['Resposta'],$row['CabEstruturado'],$row['Local'],$row['SisOperacional'],$row['Nestacoes'], $row['Situacao'],$row['AnoAtivacao'],$row['AnoDesativacao']);
 $cont++;
 }
 //var_dump($unidade->getLabs());
 $daolab->fechar();
}
ob_end_flush();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.or/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
<title>Relatório de Gestão</title>
</head>
<body style="font-family:arial,helvetica,sans-serif;font-size:14px;">
 <form class="form-horizontal" name="fconsultar" method="post" action="incluilab.php">
 <!-- Busca laborat�rio por nome:<input class="form-control"type="text" size=50 name="parametro"/><input type="button" onchange="ajaxExibelabs();" value="ok"/><br/> -->
 <h3 class="card-title">Laborat&oacute;rios</h3>
 <div id="conparcial"></div>
 <div id="contotal">
 <?php if ($cont>0) {?>
 <table border="1">
 <tr>
 <td width="15px">Nome</td>
 <td width="400px">Tipo</td>
 <td width="170px">Sigla</td>
 <td width="130px">Capacidade</td>
 <td>Alterar</td>
 <td>Excluir</td>
 <td>Cursos</td>
 </tr>
<?php foreach ($unidade->getLabs() as $lab1) {?>
 <tr><td width="200px"><?php print $lab1->getNome(); ?></td>
 <td width="15px"><?php print $lab1->getTipo()->getNome(); ?></td>
 <td width="15px"><?php print $lab1->getSigla(); ?></td>
 <td width="15px"><?php print $lab1->getCapacidade(); ?></td>
 <td align="center">
 <a href="altlab.php?codlab=<?php print $lab1->getCodlaboratorio();?>&operacao=<?php print "A";?>"
 target="_self" ><img src="http://localhost/rgestao/includes/images/editar.gif" alt="Alterar" width="19" height="19" /> </a>
 </td>
 <td align="center">
 <a href="dellab.php?codlab=<?php print $lab1->getCodlaboratorio();?>" target="_self" ><img src="http://localhost/rgestao/includes/images/dellab.gif" alt="Excluir" width="19" height="19" /> </a>
 </td>
 <td align="center">
 <a href="associacursolab.php?codlab=<?php print $lab1->getCodlaboratorio();?>" target="_self" >Cursos</a>
 </td>
 </tr>
<?php } ?>
</table>
 </div>
<?php } else {print "Nenhuma laborat�rio registrado.";} ?>
<input class="form-control"value="Incluir novo laborat�rio" class="btn btn-info" type="submit"  /><input type="button" value="Sair"/>
</form>
</body>
</html>

<?php
ob_start();
echo ini_get('display_errors');
if (!ini_get('display_errors')) {
 ini_set('display_errors', 1);
 ini_set('error_reporting', E_ALL & ~E_NOTICE);
}
?>
<?php
//set_include_path(';../../includes');
require_once('../../includes/classes/sessao.php');
session_start();
if (!isset($_SESSION["sessao"])){
 header("location:index.php");
}
$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade ();
$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('dao/infraDAO.php');
require_once('classes/infraestrutura.php');
require_once('../../includes/classes/unidade.php');
require_once('dao/tipoinfraDAO.php');
require_once('classes/tipoinfraestrutura.php');
/////////////////////////////////////////////////////////////////////////////////
$tiposti = array();
$cont = 0;
$daotipoinfra= new TipoinfraDAO();
$daoin = new InfraDAO();
$rows_ti=$daotipoinfra->Lista();
foreach ($rows_ti as $row){
 $cont++;
 $tiposti[$cont]=new Tipoinfraestrutura();
 $tiposti[$cont]->setCodigo($row['Codigo']);
 $tiposti[$cont]->setNome($row['Nome']);
}
$tamanho=$cont;
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$conti=0;
$rows_ti = $daoin->buscainfraunidade($codunidade);
foreach ($rows_ti as $row1){
 $tipo = $row1['Tipo'];
 for ($i=1;$i<$tamanho;$i++){
 if ($tiposti[$i]->getCodigo() == $tipo){
 $conti++;
 $tiposti[$i]->adicionaItemInfraestrutura($row1["CodInfraestrutura"],$unidade, $row1['AnoAtivacao'],
 $row1['Nome'], $row1['Sigla'], $row1['HoraInicio'], $row1['HoraFim'], $row1['Adistancia'],
 $row1['PCD'], $row1['Area'], $row1['Capacidade'],$row1['AnoDesativacao'],$row1['Situacao']);
 }
 }
}
$daoin->fechar();
if ($conti==0){
 $cadeia = "location:incluiinfra.php";
 header($cadeia);
}
ob_end_flush();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.or/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
<title>Sistema de Registro de Atividades Anuais</title>
<script language="JavaScript" src="../../includes/scripts/infra22.js"></script>
<link rel="stylesheet" href="../../estilo/msgs.css" />
</head>
<body style="font-family:arial,helvetica,sans-serif;font-size:14px;">
 <form class="form-horizontal" method="post" name="finfra">
 <h3 class="card-title"> Infraestrutura da Unidade</h3>
 <table width="600px" border="1">
 <tr align="center" style="font-weight: bold;">
 <td>Tipo de Infraestrutura</td>
 <td>Nome</td>
 <td>Alterar</td>
 <td>Excluir</td>
 </tr>
<?php
for ($i=1;$i<=$tamanho;$i++) {
 if ($tiposti[$i]->getInfraestrutura()!=null){
 foreach ($tiposti[$i]->getInfraestrutura() as $in){
 ?>
 <tr>
 <td><?php print $in->getTipo()->getNome(); ?></td>
 <td><?php print $in->getNome(); ?></td>
 <td align="center">
 <a href="altinfra.php?codin=<?php print $in->getCodinfraestrutura();?>&operacao=<?php print "A";?>"
 target="_self" ><img src="../../includes/images/editar.gif" alt="Alterar" width="19" height="19" /> </a>
 </td>
 <td align="center">
 <a href="delinfra.php?codin=<?php print $in->getCodinfraestrutura();?>"
 target="_self" ><img src="../../includes/images/delete.gif" alt="Excluir" width="19" height="19" /> </a>
 </td></tr>
<?php }
 }
}
?>
</table>
 <br /> <input type="button" onclick="direciona(2);" value="Incluir" />
 </form>
</body>
</html>

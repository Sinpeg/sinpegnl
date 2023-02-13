<?php
ob_start();
echo ini_get('display_errors');
if (!ini_get('display_errors')) {
 ini_set('display_errors', 1);
 ini_set('error_reporting', E_ALL & ~E_NOTICE);
}
?>
<?php
require_once('../../includes/classes/sessao.php');
session_start();
if (!isset($_SESSION["sessao"])){
 header("location:index.php");
}
$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade =$sessao->getCodunidade();
$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
require_once('../../includes/dao/PDOConnectionFactory.php');
// var_dump($usuario);
require_once('dao/infraDAO.php');
require_once('classes/infraestrutura.php');
require_once('../../includes/classes/unidade.php');
require_once('dao/tipoinfraDAO.php');
require_once('classes/tipoinfraestrutura.php');
$tiposti = array();
$cont = 1;
$daotipoinfra= new TipoinfraDAO();
$daoin = new InfraDAO();
$rows_tin=$daotipoinfra->Lista();
//$rows_tta=$daotipotecno->Lista();
foreach ($rows_tin as $row){
 $tiposti[$cont]=new Tipoinfraestrutura();
 $tiposti[$cont]->setCodigo($row['Codigo']);
 $tiposti[$cont]->setNome($row['Nome']);
 $cont++;
}
$conttin=0;
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$conti=0;
$rows_ti = $daoin->buscainfraunidade($codunidade);
foreach ($rows_ti as $row1){
 $tipo = $row1['Tipo'];
 foreach ($tiposti as $tipoti){
 if ($tipoti->getCodigo() == $tipo){
 $conti++;
 $tiposi[$conti]=$tipoti;
 $tiposi[$conti]->adicionaItemInfraestrutura($row1["CodInfraestrutura"],$unidade, $row1['AnoAtivacao'],
 $row1['Nome'], $row1['Sigla'], $row1['HoraInicio'], $row1['HoraFim'], $row1['Adistancia'],
 $row1['PCD'], $row1['Area'], $row1['Capacidade'],$row1['AnoDesativacao'],$row1['Situacao']);
 }
 }
}
$daoin->fechar();
ob_end_flush();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.or/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
<title>Sistema de Registro de Atividades Anuais</title>
<script language="JavaScript" src="../../includes/scripts/infra.js"></script>
<link rel="stylesheet" href="../../estilo/msgs.css" />
</head>
<body style="font-family:arial,helvetica,sans-serif;font-size:14px;">
 <form class="form-horizontal" name="finfra" method="post">
 <input class="form-control"name="operacao" type="hidden" value="I" />
<h3 class="card-title">Infraestrutura </h3>
 <div class="msg" id="msg"></div>
 <table>
 <tr>
 <td>Tipos de Infraestrutura</td>
 <td><select class="custom-select" name="codtinfra">
 <option value="0">Selecione um tipo...</option>
 <?php
 foreach ($tiposti as $tti){
 ?>
 <option value="<?php print $tti->getCodigo();?>">
 <?php print $tti->getNome(); ?></option>
 <?php
}
?>
</select>
 </td>
 </tr>
 <tr>
 <td>Nome</td>
 <td><input class="form-control"type="text" name="npn" maxlength="100" size=80 /></td>
 </tr>
 <tr>
 <td>Sigla</td>
 <td><input class="form-control"type="text" name="npa" maxlength="20" size=30 /></td>
 </tr>
 <tr>
 <td>Capacidade/Quantidade</td>
 <td><input class="form-control"type="text" name="npc" maxlength="4"
 onkeypress='return SomenteNumero(event);' size="4" /></td>
 </tr>
 <tr>
 <td>&Aacute;rea</td>
 <td><input class="form-control"type="text" name="npr" size="4" maxlength="6" value="" onchange="mascaradec(this.value);" />m2</td>
 </tr>
 <tr>
 <td>Hor&aacute;rio</td>
 <td><input class="form-control"name="nphi" type="text" id="nphi"
 onkeyup="Mascara_Hora1(this.value)" size="5" maxlength="5" /> &agrave; <input
 type="text" name="nphf" id="nphf"
 onkeyup="Mascara_Hora2(this.value)" size="5" maxlength="5" />horas
 </td>
 </tr>
 <tr>
 <th align="left" colspan="2"><input class="form-check-input" type="checkbox" value="1" style="font-weight: normal;"
 name="pcd[]" id="pcd" style="font-weight: normal;" />Atende ao Discente Portador de
 Necessidade Especiais
 </th>
 </tr>
 <tr>
 <td>Utiliza&ccedil;&atilde;o</td>
 <td><select class="custom-select" name="pad">
 <option value="0">Selecione forma de utiliza&ccedil;&atilde;o...</option>
 <option value="1">Presencial</option>
 <option value="2">&Agrave; dist&acirc;ncia</option>
 <option value="3">Presencial e &Agrave; dist&acirc;ncia</option>
 </select></td>
 </tr>
 </table>
 <input type="button" onclick="direciona(1);" value="Gravar" />
 </form>
</body>
</html>

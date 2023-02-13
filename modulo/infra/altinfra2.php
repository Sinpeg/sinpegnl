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
$codunidade = $sessao->getCodunidade();
$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('dao/infraDAO.php');
require_once('classes/infraestrutura.php');
require_once('../../includes/classes/unidade.php');
require_once('dao/tipoinfraDAO.php');
require_once('classes/tipoinfraestrutura.php');
$tiposti = array();
$daotipoinfra= new TipoinfraDAO();
$daoin = new InfraDAO();
$cont=1;
$rows_ti=$daotipoinfra->Lista();
foreach ($rows_ti as $row){
 $tiposti[$cont]=new Tipoinfraestrutura();
 $tiposti[$cont]->setCodigo($row['Codigo']);
 $tiposti[$cont]->setNome($row['Nome']);
 $cont++;
}
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$rows_ti=$daoin->buscainfra($_GET["codin"]);
$conti = 0;
foreach ($rows_ti as $row1){
 $tipo = $row1['Tipo'];
 foreach ($tiposti as $tipoti){
 if ($tipoti->getCodigo() == $tipo){
 $conti++;
 $tiposi=$tipoti;
 $tiposi->criaInfraestrutura($row1["CodInfraestrutura"],$unidade, $row1['AnoAtivacao'],
 $row1['Nome'], $row1['Sigla'], $row1['HoraInicio'], $row1['HoraFim'], $row1['Adistancia'],
 $row1['PCD'], $row1['Area'], $row1['Capacidade'],$row1['AnoDesativacao'],$row1['Situacao']);
 }
 }
}
$selecionado1=" ";$selecionado2=" ";$selecionado3=" ";$selecionado4=" ";$selecionado5=" ";$selecionado6=" ";
if ($tiposi->getInfra()->getPcd()=="S"){
 $selecionado5 = "checked";
}
if ($tiposi->getInfra()->getAdistancia()=="1"){
 $selecionado1 = "selected";
}elseif ($tiposi->getInfra()->getAdistancia()=="2") {
 $selecionado2 = "selected";
}elseif ($tiposi->getInfra()->getAdistancia()=="3") {
 $selecionado6 = "selected";
}
if ($tiposi->getInfra()->getSituacao()=="A"){
 $selecionado3 = "selected";
}else {
 $selecionado4 = "selected";
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
 <input class="form-control"name="codti" type="hidden"
 value="<?php print $tiposi->getInfra()->getCodinfraestrutura();?>" />
 <input class="form-control"name="operacao" type="hidden" value="A" />
<h3 class="card-title">Infraestrutura</h3>
 <div class="msg" id="msg"></div>
 <table>
 <tr>
 <td>Situa&ccedil;&atilde;o</td>
 <td><select class="custom-select" name="situacao">
 <option value="A" <?php print $selecionado3; ?>>Ativado</option>
 <option value="D" <?php print $selecionado4; ?>>Desativado</option>
 </select>
 </td>
 </tr>
 <tr>
 <td>Tipo de Infraestrutura</td>
 <td><select class="custom-select" name="codtinfra">
 <?php foreach ($tiposti as $tti){
 if ($tiposi->getCodigo()==$tti->getCodigo()){
 ?>
 <option selected="selected"
 value="<?php print $tti->getCodigo();?>">
 <?php print $tti->getNome(); ?></option>
 <?php }else{?>
 <option value="<?php print $tti->getCodigo();?>">
 <?php print $tti->getNome(); ?></option>
 <?php
 }
 }
 ?>
 </select>
 </td>
 </tr>
 <tr>
 <td>Nome</td>
 <td><input class="form-control"type="text" name="npn" maxlength="100" size=80
 value="<?php print $tiposi->getInfra()->getNome();?>" />
 </td>
 </tr>
 <tr>
 <td>Sigla</td>
 <td><input class="form-control"type="text" name="npa" maxlength="20" size=30
 value="<?php print $tiposi->getInfra()->getSigla();?>" />
 </td>
 </tr>
 <tr>
 <td>Capacidade</td>
 <td><input class="form-control"type="text" name="npc" onkeypress='return SomenteNumero(event);' maxlength="4"
 value="<?php print $tiposi->getInfra()->getCapacidade();?>" />
 </td>
 </tr>
 <tr>
 <td>&Aacute;rea</td>
 <td><input class="form-control"type="text" name="npr" size="4" onchange="mascaradec(this.value);" maxlength="6"
 value="<?php
 $npr= addslashes( str_replace(".", ",",$tiposi->getInfra()->getArea()));
 print $npr;?>" />m2</td>
 </tr>
 <tr>
 <td>Hor&aacute;rio</td>
 <td><input class="form-control"type="text" name="nphi" size="5" maxlength="5" onkeyup="Mascara_Hora1(this.value)"
 value="<?php print $tiposi->getInfra()->getHorainicio()?>" />
 &agrave;s
 <input class="form-control"type="text" name="nphf" size="5" maxlength="5" onkeyup="Mascara_Hora2(this.value)"
 value="<?php print $tiposi->getInfra()->getHorafim();?>"/>
 horas
 </td>
 </tr>
 <tr>
 <th align="left" colspan="2"><input class="form-check-input"type="checkbox"
 onchange="teste();" name="pcd[]" <?php print $selecionado5; ?> />Atende
 ao Discente Portador de Necessidade Especiais
 </th>
 </tr>
 <tr>
 <td>Utiliza&ccedil;&atilde;o</td>
 <td><select class="custom-select" name="pad">
 <option value="0">Selecione Forma de Utiliza&ccedil;&atilde;o...</option>
 <option value="1" <?php print $selecionado1; ?>>Presencial</option>
 <option value="2" <?php print $selecionado2; ?>>&Agrave; dist&acirc;ncia</option>
 <option value="3" <?php print $selecionado6; ?>>Presencial e &Agrave; dist&acirc;ncia</option>
 </select></td>
 </tr>
 </table>
 <input type="button" onclick="direciona(1);" value="Gravar" />
 </form>
</body>
</html>

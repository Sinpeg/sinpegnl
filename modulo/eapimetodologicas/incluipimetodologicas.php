<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[28]) {
    header("Location:index.php");
} 
//$sessao = $_SESSION["sessao"];
//$nomeunidade = $sessao->getNomeUnidade();
//$codunidade = $sessao->getCodUnidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
//$aplicacoes = $_SESSION["sessao"]->getAplicacoes();
//if (!$aplicacoes[28]) {
//    $mensagem = urlencode(" ");
//    $cadeia = "location:../saida/erro.php?codigo=2&mensagem=" . $mensagem;
//    header($cadeia);
//    exit();
//}
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('dao/eapimetodologicasDAO.php');
require_once('classes/eapimetodologicas.php');
$pimetodologicas = array();
$cont = 0;
$daopim = new EApimetodologicasDAO();
$pimetodologicas = new EApimetodologicas();
$rows_pim = $daopim->buscapim($anobase);
foreach ($rows_pim as $row) {
    $cont++;
    $pimetodologicas->setCodigo($row['Codigo']);
    $pimetodologicas->setExecucao($row['EmExecucao']);
    $pimetodologicas->setTramitacao($row['EmTramitacao']);
    $pimetodologicas->setCancelado($row['Cancelado']);
    $pimetodologicas->setSuspenso($row['Suspenso']);
    $pimetodologicas->setConcluido($row['Concluido']);
    $pimetodologicas->setDocentes($row['Qdocentes']);
    $pimetodologicas->setTecnicos($row['Qtecnicos']);
    $pimetodologicas->setBolsistas($row['Qgradbolsistas']);
    $pimetodologicas->setNBolsistas($row['Qgradnbolsistas']);
    $pimetodologicas->setPosgraduao($row['Qposgrad']);
    $pimetodologicas->setOutras($row['QoutrasInstituicoes']);
    $pimetodologicas->setAno($row['Ano']);
}

$daopim->fechar();

if ($cont > 0) {
    Utils::redirect('eapimetodologicas', 'consultapimetodologicas');
//	$cadeia = "location:consultapimetodologicas.php";
//	header($cadeia);
//	exit();
}
//ob_end_flush();
?>
<script language='JavaScript'>
    function SomenteNumero(e){
    var tecla = (window.event)?event.keyCode:e.which;
    //0 a 9 em ASCII
    if((tecla>47 && tecla<58)){
    document.getElementById('msg').innerHTML =" ";
    return true;
    }
    else{
    if (tecla==8 || tecla==0) {
    document.getElementById('msg').innerHTML =" ";
    return true;//Aceita tecla tab
    }
    else {
    document.getElementById('msg').innerHTML = "Todos os campos devem conter apenas n&uacute;meros.";
    return false;
    }
    }
    }

    function Soma1(){
    var soma = 0;
    qtde = new Array (document.pe.qtdExe.value,document.pe.qtdTra.value,
    document.pe.qtdCan.value,document.pe.qtdSusp.value, document.pe.qtdConc.value);
    for (var i = 0;i < qtde.length; i++){
    if (!isNaN(parseInt(qtde[i]))){
    soma += parseInt(qtde[i]);
    }
    }

    document.getElementById('totalgeral1').innerHTML = soma;
    }

    function Soma2(){
    var soma = 0;
    qtde = new Array (document.pe.qtdDoc.value,document.pe.qtdTec.value,
    document.pe.qtdBols.value,document.pe.qtdNBols.value, document.pe.qtdPos.value,
    document.pe.qtdOutras.value);
    for (var i = 0;i < qtde.length; i++){
    if (!isNaN(parseInt(qtde[i]))){
    soma += parseInt(qtde[i]);
    }
    }

    document.getElementById('totalgeral2').innerHTML = soma;
    }
    function direciona(botao){
    switch (botao){
    case 1:
    if (valida()){
    document.getElementById('pe').action = "?modulo=eapimetodologicas&acao=oppimetodologicas";
    document.getElementById('pe').submit();
    }
    break;
    case 2:
    document.getElementById('pe').action = "../saida/saida.php";
    document.getElementById('pe').submit();
    break;
    }

    }
    function valida(){
    var passou=false;
    if ((document.pe.qtdExe.value =="")|| (document.pe.qtdTra.value=="")
    && (document.pe.qtdCan.value=="") || (document.pe.qtdSusp.value=="")
    && (document.pe.qtdConc.value=="") ){
    document.getElementById('msg').innerHTML ="As quantidades de pr&aacute;ticas s&atilde;o obrigat&oacute;rias.";
    passou=true;
    }else if ((document.pe.qtdDoc.value =="") || (document.pe.qtdTec.value=="")
    && (document.pe.qtdBols.value=="") || (document.pe.qtdNBols.value=="")
    && (document.pe.qtdPos.value=="") || (document.pe.qtdOutras.value=="")){
    document.getElementById('msg').innerHTML ="As quantidades de participantes s&atilde;o obrigat&oacute;rias.";
    passou=true;
    }
    if (passou){return false;}
    else {return true;}
    }
</script>
<head>
    	<div class="bs-example">
    		<ul class="breadcrumb">
    			<li class="active">Pr&aacute;ticas de interven&ccedil;&otilde;es metodol&oacute;gicas da escola de aplica&ccedil;&atilde;o</li>
    		</ul>
    	</div>
</head>
<form class="form-horizontal" name="pe" id="pe" method="post">


    <h3 class="card-title">Pr&aacute;ticas de Interven&ccedil;&otilde;es Metodol&oacute;gicas da Escola de Aplica&ccedil;&atilde;o</h3>
    <div class="msg" id="msg"></div>

    <table width="600px" style="font-size:14px;">
        <tr style="font-style: italic;">
            <td>Itens</td>
            <td>Quantidade</td>
        </tr>
        <tr>
            <td>Em Execu&ccedil;&atilde;o</td>
            <td><input class="form-control"type="text" onchange="Soma1();" name="qtdExe" size="5" maxlength="5"
                       value='' onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Em Tramita&ccedil;&atilde;o</td>
            <td><input class="form-control"type="text" onchange="Soma1();" name="qtdTra" size="5" maxlength="5"
                       value='' onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Cancelados</td>
            <td><input class="form-control"type="text" onchange="Soma1();" name="qtdCan" size="5" maxlength="5"
                       value='' onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Suspensos</td>
            <td><input class="form-control"type="text" onchange="Soma1();" name="qtdSusp" size="5" maxlength="5"
                       value='' onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Conclu&iacute;dos</td>
            <td><input class="form-control"type="text" onchange="Soma1();" name="qtdConc" size="5" maxlength="5"
                       value='' onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr style="font-style: italic;">
            <td>Total de Projetos</td>
            <td><b id='totalgeral1'></b>
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>N&uacute;mero de Participantes</td>
            <td></td>
        </tr>
        <tr>
            <td>Docentes</td>
            <td><input class="form-control"type="text" name="qtdDoc" onchange="Soma2();" size="5" maxlength="5"
                       value='' onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>T&eacute;cnicos-Administrativos</td>
            <td><input class="form-control"type="text" name="qtdTec" size="5" onchange="Soma2();" maxlength="5"
                       value='' onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Discentes Bolsistas da Gradua&ccedil;&atilde;o</td>
            <td><input class="form-control"type="text" name="qtdBols" size="5" onchange="Soma2();" maxlength="5"
                       value='' onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Discentes da Gradua&ccedil;&atilde;o N&atilde;o-Bolsista</td>
            <td><input class="form-control"type="text" name="qtdNBols" size="5" onchange="Soma2();" maxlength="5"
                       value='' onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Discentes da P&oacute;s-Gradua&ccedil;&atilde;o</td>
            <td><input class="form-control"type="text" name="qtdPos" size="5" onchange="Soma2();" maxlength="5"
                       value='' onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Pessoas de Outras Institui&ccedil;&otilde;es</td>
            <td><input class="form-control"type="text" name="qtdOutras" onchange="Soma2();" size="5" maxlength="5"
                       value='' onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr style="font-style: italic;">
            <td>Total de Participantes</td>
            <td><b id='totalgeral2'></b>
            </td>
        </tr>
    </table>
    <input class="form-control"name="operacao" type="hidden" value="I" /> <input type="button" onclick='direciona(1);' value="Gravar" />
</form>

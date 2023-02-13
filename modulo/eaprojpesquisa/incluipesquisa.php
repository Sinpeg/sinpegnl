<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[20]) {
    header("Location:index.php");
}
//$nomeunidade = $sessao->getNomeUnidade();
//$codunidade = $sessao->getCodUnidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('dao/eaprojpesquisaDAO.php');
require_once('classes/eaprojpesquisa.php');
$projpesquisa=array();
$cont = 0;
$daopp= new EAprojpesquisaDAO();
$projpesquisa= new EAprojpesquisa();
$rows_pp = $daopp->buscapp($anobase);
foreach ($rows_pp as $row){
	$cont++;
	$projpesquisa->setExecucao($row['EmExecucao']);
	$projpesquisa->setTramitacao($row['Emtramitacao']);
	$projpesquisa->setCancelado($row['Cancelado']);
	$projpesquisa->setSuspenso($row['Suspenso']);
	$projpesquisa->setConcluido($row['Concluido']);
	$projpesquisa->setDocentes($row['Qdocentes']);
	$projpesquisa->setTecnicos($row['Qtecnicos']);
	$projpesquisa->setDiscentes($row['Qdiscentes']);
	$projpesquisa->setOutras($row['QoutrasInstituicoes']);
	$projpesquisa->setAno($row['Ano']);
}
$daopp->fechar();
if ($cont>0){
    Utils::redirect('eaprojpesquisa', 'consultapesquisa');
//	header("location:consultapesquisa.php");
   // exit();
}
//ob_end_flush();
?>
<script language='javascript'>
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
			document.pe.qtdDisc.value,document.pe.qtdOutras.value);
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
		document.getElementById('pe').action = "?modulo=eaprojpesquisa&acao=opprojpesquisa";
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
	if ((document.pe.qtdExe.value=="") || (document.pe.qtdTra.value=="")
	|| (document.pe.qtdCan.value=="") || (document.pe.qtdSusp.value=="")
	|| (document.pe.qtdConc.value=="")){
	 document.getElementById('msg').innerHTML ="Todas as quantidades relativas a projetos s&atilde;o obrigat&oacute;rias.";
	 passou=true;
	}else if ((document.pe.qtdDoc.value=="") || (document.pe.qtdTec.value=="")
			|| (document.pe.qtdDisc.value=="") || (document.pe.qtdOutras.value=="")){
			 document.getElementById('msg').innerHTML ="Todas as quantidades relativas aos participantes s&atilde;o obrigat&oacute;rias.";
			 passou=true;
	}

		if (passou){return false;}
		else {return true;}
	}
</script>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active">Projetos de pesquisa</li>
		</ul>
	</div>
</head>
	<form class="form-horizontal" name="pe" id="pe" method="post">
		<h3 class="card-title"> Projetos de Pesquisa da Escola de Aplica&ccedil;&atilde;o</h3>
		<div class="msg" id="msg"></div>

		<table>
			<tr style="font-style: italic;" align="center">
				<th>Itens</th>
				<th>Quantidade</th>
			</tr>
			<tr>
				<td>Projetos em Execu&ccedil;&atilde;o</td>
				<td><input class="form-control"type="text" name="qtdExe" size="5" value='' maxlength="4"
					onchange="Soma1();" onkeypress='return SomenteNumero(event)' /></td>
			</tr>
			<tr>
				<td>Em Tramita&ccedil;&atilde;o</td>
				<td><input class="form-control"type="text" name="qtdTra" size="5" value='' maxlength="4"
					onchange="Soma1();" onkeypress='return SomenteNumero(event)' /></td>
			</tr>
			<tr>
				<td>Cancelados</td>
				<td><input class="form-control"type="text" name="qtdCan" size="5" value='' maxlength="4"
					onchange="Soma1();" onkeypress='return SomenteNumero(event)' /></td>
			</tr>
			<tr>
				<td>Suspensos</td>
				<td><input class="form-control"type="text" name="qtdSusp" size="5" value='' maxlength="4"
					onchange="Soma1();" onkeypress='return SomenteNumero(event)' /></td>
			</tr>
			<tr>
				<td>Conclu&iacute;dos</td>
				<td><input class="form-control"type="text" name="qtdConc" size="5" value='' maxlength="4"
					onchange="Soma1();" onkeypress='return SomenteNumero(event)' /></td>
			</tr>
			<tr>
				<td>Total de Projetos</td>
				<td><b id='totalgeral1'></b></td>
			</tr>
			<tr>
				<th colspan="2" align="left">N&uacute;mero de Participantes</th>
			</tr>

			<tr>
				<td>Docentes</td>
				<td><input class="form-control"type="text" name="qtdDoc" size="5" value='' maxlength="4"
					onchange="Soma2();" onkeypress='return SomenteNumero(event)' /></td>
			</tr>
			<tr>
				<td>T&eacute;cnicos Administrativos</td>
				<td><input class="form-control"type="text" name="qtdTec" size="5" value='' maxlength="4"
					onchange="Soma2();" onkeypress='return SomenteNumero(event)' /></td>
			</tr>
			<tr>
				<td>Discentes</td>
				<td><input class="form-control"type="text" name="qtdDisc" size="5" value='' maxlength="4"
					onchange="Soma2();" onkeypress='return SomenteNumero(event)' /></td>
			</tr>
			<tr>
				<td>Pessoas de Outras Institui&ccedil;&otilde;es</td>
				<td><input class="form-control"type="text" name="qtdOutras" onchange="Soma2();" size="5" maxlength="4"
					value='' onkeypress='return SomenteNumero(event)' /></td>
			</tr>

			<tr>
				<td>Total de Participantes</td>
				<td><b id='totalgeral2'></b>
				</td>
			</tr>
		</table>
		<input class="form-control"name="operacao" type="hidden" value="I" /> <input type="button"
			onclick="direciona(1);" value="Gravar" />
	</form>


<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[18]) {
    header("Location:index.php");
}
//$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeUnidade();
$codunidade = $sessao->getCodUnidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('dao/producaoartisticaDAO.php');
require_once('classes/producaoartistica.php');
require_once('dao/tipoproducaoartisticaDAO.php');
require_once('classes/tipoproducaoartistica.php');
//require_once('../../includes/classes/unidade.php');
$lock = new Lock();
$unidade = new Unidade();
$daounid = new UnidadeDAO();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$tipospa = array();
$cont = 0;
$daotpa = new TipoproducaoartisticaDAO();
$daopa = new ProducaoartisticaDAO();
$rows_tpa = $daotpa->Lista();
foreach ($rows_tpa as $row) {
    $cont++;
    $tipospa[$cont] = new Tipoproducaoartistica();
    $tipospa[$cont]->setCodigo($row['Codigo']);
    $tipospa[$cont]->setNome($row['Nome']);
}
$tamanho = count($tipospa);
$cont1 = 0;
$rows_pa = $daopa->buscapaunidade($codunidade, $anobase);
foreach ($rows_pa as $row) {
    $tipo = $row['Tipo'];
    for ($i = 1; $i <= $tamanho; $i++) {
        if ($tipospa[$i]->getCodigo() == $tipo) {
            $cont1++;
            $tipospa[$i]->criaProdartistica($row["Codigo"], $unidade, $anobase, $row["Quantidade"]);
        }
    }
}


// verifica se a CPGA já entrou com os dados
$rowscodsup = $daounid->RetornaCodUnidadeSuperior($cpga);
foreach ($rowscodsup as $row)
{
	$codunidadesup = $row['CodUnidade'];
}


if (!$sessao->isUnidade())
{
	$rows_verifica = $daopa->buscapaunidade($codunidadesup, $anobase);
	foreach ($rows_verifica as $row)
	{
		if ($rows_verifica->rowCount() > 0)
		{
			$lock->setLocked(true);
		}
	}
}

$daopa->fechar();
/*
if ($cont1 > 0) {
    $cadeia = "location:consultaprodartistica.php";
    header($cadeia);
    exit();
}*/
ob_end_flush();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.or/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
        <title>Relat�rio Anual de Atividades</title>
        <script language='JavaScript'>
            function SomenteNumero(e) {
                var tecla = (window.event) ? event.keyCode : e.which;
                //0 a 9 em ASCII
                if ((tecla > 47 && tecla < 58)) {
                    document.getElementById('msg').innerHTML = " ";
                    return true;
                }
                else {
                    if (tecla == 8 || tecla == 0) {
                        document.getElementById('msg').innerHTML = " ";
                        return true;//Aceita tecla tab
                    }
                    else {
                        document.getElementById('msg').innerHTML = "Todos os campos devem conter apenas n&uacute;meros.";
                        return false;
                    }
                }
            }
            function valida() {
                var passou = false;
                if ((document.pa.qtdPTeatro.value == "")
                        || (document.pa.qtdConcertos.value == "")
                        || (document.pa.qtdPerformances.value == "")) {
                    passou = true;
                }
                if (passou) {
                    return false;
                }
                else {
                    return true;
                }
            }
            function Soma() {
                var soma = 0;
                qtde = new Array(document.pa.qtdPTeatro.value, document.pa.qtdConcertos.value,
                        document.pa.qtdPerformances.value);
                for (var i = 0; i < qtde.length; i++) {
                    if (!isNaN(parseInt(qtde[i]))) {
                        soma += parseInt(qtde[i]);
                    }
                }
                document.getElementById('totalgeral').innerHTML = soma;
            }
            function direciona(botao) {
                switch (botao) {
                    case 1:
                        if (valida()) {
                            document.getElementById('pa').action = "?modulo=prodartistica&acao=opprodartistica";
                            document.getElementById('pa').submit();
                        }
                        break;
                    case 2:
                        document.getElementById('pa').action = "../saida/saida.php";
                        document.getElementById('pa').submit();
                        break;
                }
            }
        </script>
        <head>
		<div class="bs-example">
			<ul class="breadcrumb">
			    <li class="active">Produção art&iacute;stica</li>
			</ul>
		</div>
		</head>
        <form class="form-horizontal" name="pa" id="pa" method="post">
            <h3 class="card-title"> Produ&ccedil;&atilde;o Art&iacute;stica </h3>
            <div class="msg" id="msg"></div>
            <table>
                <tr align="center" style="font-style: italic;">
                    <th>Itens</th>
                    <th>Quantidade</th>
                </tr>
                <tr>
                    <td>Pe&ccedil;as de teatro</td>
                    <td align="center"><input class="form-control"type="text" name="qtdPTeatro" size="9" value='' maxlength="5"
                                              onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
                </tr>
                <tr>
                    <td>Concertos</td>
                    <td align="center"><input class="form-control"type="text" name="qtdConcertos" size="9" value='' maxlength="5"
                                              onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
                </tr>
                <tr>
                    <td>Performances</td>
                    <td align="center"><input class="form-control"type="text" name="qtdPerformances" onchange="Soma();" maxlength="5"
                                              size="9"  value=''	onkeypress='return SomenteNumero(event)' /><br />
                    </td>
                </tr>
                <tr>
                    <td>Total Geral</td>
                    <td align="center"><b id='totalgeral'></b></td>
                </tr>
            </table>
           <?php if (!$lock->getLocked()){ ?>
            <input class="form-control"name="operacao" type="hidden" readonly="readonly" value="I" />
            <input type="button" onclick='direciona(1);' value="Gravar" />
           <?php } ?>
        </form>

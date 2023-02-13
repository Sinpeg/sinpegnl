<?php
//ob_start();
//echo ini_get('display_errors');
//if (!ini_get('display_errors')) {
//    ini_set('display_errors', 1);
//    ini_set('error_reporting', E_ALL & ~E_NOTICE);
?>
<?php
//require_once('../../includes/classes/sessao.php');
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[24]) {
    header("Location:index.php");
}
//$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeUnidade();
$codunidade = $sessao->getCodUnidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('dao/rhetemufpaDAO.php');
require_once('classes/rhetemufpa.php');
//require_once('../../includes/classes/unidade.php');
$lock = new Lock();
$unidade = new Unidade();
$daounid = new UnidadeDAO();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$rhetemufpa = array();
$rhetemufpa = new Rhetemufpa();
$cont = 0;
$daorh = new rhetemufpaDAO();
$rows_rh = $daorh->buscarhunidade($codunidade, $anobase);
foreach ($rows_rh as $row) {
    $cont++;
}

$rowscodsup = $daounid->RetornaCodUnidadeSuperior($cpga);
foreach ($rowscodsup as $row)
{
	$codunidadesup = $row['CodUnidade'];
}


if (!$sessao->isUnidade())
{
	$rows_verifica = $daorh->buscarhunidade($codunidadesup, $anobase);
	foreach ($rows_verifica as $row)
	{
		if ($rows_verifica->rowCount() > 0)
		{
			$lock->setLocked(true);
		}
	}
}

$daorh->fechar();
if ($cont == 2) {
    Utils::redirect('edprofrh', 'consultarh');
//	$cadeia = "location:consultarh.php";
//	header($cadeia);
//	exit();
}
//ob_end_flush();
?>
<script language='javascript'>
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
    function Soma() {
        var soma = 0;
        qtde = new Array(document.pe.qtdDoc.value, document.pe.qtdMes.value, document.pe.qtdNt.value,
                document.pe.qtdEsp.value, document.pe.qtdGra.value, document.pe.qtdTem.value, document.pe.qtdTec.value);
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
                    document.getElementById('pe').action = "?modulo=edprofrh&acao=oprh";
                    document.getElementById('pe').submit();
                }
                break;
            case 2:
                break;
        }
    }
    function valida() {
        if (document.pe.qtdDoc.value == "" || document.pe.qtdMes.value == ""
                || document.pe.qtdNt.value == "" || document.pe.qtdEsp.value == ""
                || document.pe.qtdGra.value == "" || document.pe.qtdTem.value == ""
                || document.pe.qtdTec.value == "") {
            document.getElementById('msg').innerHTML = "Todos os campos s&atilde;o obrigat&oacute;rios.";
            return false;
        }
        return true;
    }
</script>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
		    <li class="active">Quadro de pessoal</li>
		</ul>
	</div>
</head>
<form class="form-horizontal" name="pe" id="pe" method="post">
    <h3 class="card-title">Quadro de pessoal</h3>
    <div class="msg" id="msg"></div>
    <table>
        <tr>
            <th>Subunidade</th>
            <th><select class="custom-select" name="subunidade">
                    
             <?php if ($sessao->isUnidade()) { ?>
                    <option value="1001">Escola de M&uacute;sica</option>
                    <option value="1004">Escola de Teatro e Dan&ccedil;a</option>
             <?php } else {?>
                    <option value="<?php echo $codunidade ?>"><?php echo $nomeunidade ?></option>
                    <?php } ?>            
                </select></th>
        </tr>
        <tr>
            <td>Quantidade de Docentes Efetivos - Doutores</td>
            <td><input class="form-control"type="text" name="qtdDoc" size="5" value='' maxlength="4"
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Quantidade de Docentes Efetivos - Mestres</td>
            <td><input class="form-control"type="text" name="qtdMes" size="5" value='' maxlength="4"
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Quantidade de Docentes Efetivos - Especialistas</td>
            <td><input class="form-control"type="text" name="qtdEsp" size="5" value='' maxlength="4"
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Quantidade de Docentes Efetivos - Graduados</td>
            <td><input class="form-control"type="text" name="qtdGra" size="5" value='' maxlength="4"
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Quantidade de Docentes Efetivos - N&iacute;vel T&eacute;cnico</td>
            <td><input class="form-control"type="text" name="qtdNt" size="5" value='' maxlength="4"
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Quantidade de Docentes Tempor&aacute;rios</td>
            <td><input class="form-control"type="text" name="qtdTem" size="5" value='' maxlength="4"
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Quantidade de T&eacute;cnicos-administrativos</td>
            <td><input class="form-control"type="text" name="qtdTec" onchange="Soma();" size="5"
                       maxlength="4" value='' onkeypress='return SomenteNumero(event)' />
            </td>
        </tr>
        <tr style="font-style:italic;">
            <td>Total Geral</td>
            <td><b id='totalgeral'></b>
            </td>
        </tr>
    </table>
    <?php if (!$lock->getLocked()){ ?>
    <input class="form-control"name="operacao" type="hidden" value="I" />
    <input type="button" onclick='direciona(1);' value="Gravar" />
    <?php } ?>
</form>

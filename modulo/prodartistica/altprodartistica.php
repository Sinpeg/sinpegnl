<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
session_start();
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
$unidade = new Unidade();
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
$daopa->fechar();
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
</script>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li><a href="<?php echo Utils::createLink("prodartistica", "incluiprodartistica"); ?>" >Produção art&iacute;stica</a></li>
			<li><a href="<?php echo Utils::createLink("prodartistica", "consultaprodartistica"); ?>" >Consulta</a></li>
		    <li class="active">Alterar</li>
		</ul>
	</div>
</head>
<form class="form-horizontal" name="pa" id="pa" method="post" action="<?php echo Utils::createLink('prodartistica', 'opprodartistica'); ?>">
    <h3 class="card-title"> Produ&ccedil;&atilde;o Art&iacute;stica </h3>
    <div class="msg" id="msg"></div>

    <table width="300px" >
        <tr  align="center" style="font-style: italic;">
            <td>Itens</td>
            <td>Quantidade</td>
        </tr>
        <tr>
            <td>Pe&ccedil;as de teatro</td>
            <td align="center"><input class="form-control"type="text" name="qtdPTeatro" size="9" maxlength="5"
                                      value='<?php print $tipospa[1]->getProdartistica()->getQuantidade(); ?>'
                                      onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Concertos</td>
            <td align="center"><input class="form-control"type="text" name="qtdConcertos" size="9" maxlength="5"
                                      value='<?php print $tipospa[2]->getProdartistica()->getQuantidade(); ?>'
                                      onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Performances</td>
            <td align="center"><input class="form-control"type="text" name="qtdPerformances" onchange="Soma();"
                                      size="9" maxlength="5"	value='<?php print $tipospa[3]->getProdartistica()->getQuantidade(); ?>'
                                      onkeypress='return SomenteNumero(event)' /><br />
            </td>
        </tr>
        <tr>
            <td>Total Geral</td>
            <td align="center"><b id='totalgeral'></b></td>
        </tr>
    </table>
    <input class="form-control"name="operacao" type="hidden" readonly="readonly" value="A" />
    <input class="btn btn-info" type="submit"  value="Gravar" />
</form>
<script>
    window.onload = Soma();
</script>



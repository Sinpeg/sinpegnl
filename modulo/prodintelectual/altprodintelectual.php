<?php
//require_once('../../includes/classes/sessao.php');
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[4]) {
    header("Location:index.php");
}
//$nomeunidade = $sessao->getNomeUnidade();
//$codunidade = $sessao->getCodUnidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('dao/prodintelectualDAO.php');
require_once('classes/prodintelectual.php');
require_once('dao/tipoprodintelectualDAO.php');
require_once('classes/tipoprodintelectual.php');
//require_once('../../includes/classes/curso.php');
$tiposie = array();
$cont = 0;
$daotpi = new TipoprodintelectualDAO();
$daopi = new prodintelectualDAO();

$validade=2014;
if ($anobase<=2013)
	$validade=2013;

$rows_tpi = $daotpi->Lista($validade);
foreach ($rows_tpi as $row) {
    if (($anobase>=2018 && $row['Codigo']<>120 && $row['Codigo']<>132 && $row['Codigo']<>133 && $row['Codigo']<>134 && $validade==2014) || ($anobase<2018)) {
	    $cont++;
	    $tipospi[$cont] = new Tipoprodintelectual();
	    $tipospi[$cont]->setCodigo($row['Codigo']);
	    $tipospi[$cont]->setNome($row['Nome']);
	}
}
$cont1 = 0;

$uni = new Unidade();
$uni->setCodunidade($codunidade);

$tamanho = count($tipospi);

    $rows_pi = $daopi->buscapiunidade($codunidade, $anobase,$validade);
    $cont = 0;
    foreach ($rows_pi as $row) {
        $tipo = $row['Tipo'];
        for ($i = 1; $i <= $tamanho; $i++) {
            if ($tipospi[$i]->getCodigo() == $tipo) {
                $cont1++;
                $tipospi[$i]->criaProdintelectual($row["Codigo"], $uni, $anobase, $row["Quantidade"]);
            }
        }
    }

$daotpi->fechar();
//ob_end_flush();
?>

<script language="javascript">
    function Soma() {
        var multi_prodi = document.pi.elements["prodi[]"];
        var soma = 0;
        for (var i = 0; i < multi_prodi.length; i++)
        {
            if (!isNaN(parseInt(multi_prodi[i].value, 10))) {
                soma += parseInt(multi_prodi[i].value, 10);
            }
        }
        document.getElementById('totalgeral').innerHTML = soma;
    }
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
                document.getElementById('msg').innerHTML = "Todos os campos devem conter apenas números.";
                return false;
            }
        }
    }
    function direciona(botao) {
        switch (botao) {
            case 1:
                if (valida()) {
                    document.getElementById('pi').action = "?modulo=prodintelectual&acao=opprodintelectual";
                    document.getElementById('pi').submit();
                }
                break;
            case 2:
                document.getElementById('pi').action = "../saida/saida.php";
                document.getElementById('pi').submit();
                break;
        }

    }
    function valida() {
        var multi_prodi = document.pi.elements["prodi[]"];
        var soma = 0;
        var passou = false;
        for (var i = 0; i < multi_prodi.length; i++) {
            if (isNaN(parseInt(multi_prodi[i].value, 10))) {
                document.getElementById('msg').innerHTML = '<div class="alert alert-danger" role="alert">Todos os campos são obrigatórios. Não deixe campos em branco, digite 0.</div>';
                passou = true;
                window.scrollTo(0, 0);
            }
        }
        if (passou) {
            return false;
        }
        else {
            return true;
        }
    }
</script>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
            <li class="active">
                <a href="<?php echo Utils::createLink("prodintelectual", "consultaprodintelectual"); ?>">Produção Intelectual</a>
                <i class="fas fa-long-arrow-alt-right"></i>
			    Alterar
            </li>
		</ul>
	</div>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"> Produ&ccedil;&atilde;o Intelectual </h3>
    </div>
    <form class="form-horizontal" name="pi" id="pi" method="post">
        
        <div class="card-body">
            <div class="msg" id="msg"></div>
            <table class="table table-bordered table-hover" id="tabelaAltProd">
                <thead>
                    <tr style="font-style:italic;font-weight:bold;">
                        <td>Itens</td>
                        <td>Quantidade</td>
                    </tr>
                </thead>
                <?php
                foreach ($tipospi as $t) {
                    if ($t->getProdintelectual() != NULL) {
                        ?>

                        <tr>
                            <td><?php print ($t->getNome()); ?></td>
                            <td><input class="form-control"type="text" name="prodi[]" size="7"
                                    onkeypress='return SomenteNumero(event)' maxlength="4"
                                    value='<?php print $t->getProdintelectual()->getQuantidade(); ?>' onchange="Soma();"  />
                            </td>
                        </tr>
                    <?php
                    }
                }
                ?>
                <tfoot style="font-style:italic;"><td><strong>Total Geral</strong></td><td><b id='totalgeral'></b></td></tfoot>
            </table>
        </div>
        <table class="card-body">
            <tr>
                <td colspan="2" align="center">
                    <br>
                    <input class="form-control"name="operacao" type="hidden" value="A" /> 
                    <input	type="hidden" name="codunidade" value="<?php print $codunidade; ?>" />
                    <input type="button"  onclick="direciona(1);" value="Gravar" id="gravar" class="btn btn-info" />
                </td>
            </tr>
        </table>
    </form>
</div>

<script>
 $(function () {
    $('#tabelaAltProd').DataTable({
      "paging": true,
      "sort": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });
});
</script>
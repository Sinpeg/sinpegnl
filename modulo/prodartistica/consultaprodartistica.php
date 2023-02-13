<?php
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
require_once('dao/producaoartisticaDAO.php');
require_once('classes/producaoartistica.php');
require_once('dao/tipoproducaoartisticaDAO.php');
require_once('classes/tipoproducaoartistica.php');
//require_once('../../includes/classes/unidade.php');
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$tipospa = array();
$somatipo1 =0;
$somatipo2 =0;
$somatipo3 =0;
$cont = 0;
$lock = new Lock();
$daounid = new UnidadeDAO();
$daotpa = new TipoproducaoartisticaDAO();
$daopa = new ProducaoartisticaDAO();

$rowscodsup = $daounid->RetornaCodUnidadeSuperior($cpga);
foreach ($rowscodsup as $row) 
{	
  $codunidadesup = $row['CodUnidade'];
}

$rows_tpa = $daotpa->Lista();
foreach ($rows_tpa as $row) {
    $cont++;
    $tipospa[$cont] = new Tipoproducaoartistica();
    $tipospa[$cont]->setCodigo($row['Codigo']);
    $tipospa[$cont]->setNome($row['Nome']);
}
$tamanho = count($tipospa);
$cont1 = 0;

if (!$sessao->isUnidade()) {
	// verifica se possui homologação
	$lock->setLocked(Utils::isApproved(18, $codunidadesup, $codunidade, $anobase));

}

for ($i = 0; $i < count($array_codunidade); $i++) {
$rows_pa = $daopa->buscapaunidade($array_codunidade[$i], $anobase);

if ($sessao->isUnidade() && $array_codunidade[$i] != $codunidade && $rows_pa->rowCount() > 0) {
	$lock->setLocked(true);
}

foreach ($rows_pa as $row) {
    $tipo = $row['Tipo'];
    for ($j = 1; $j <= $tamanho; $j++) {
        if ($tipospa[$j]->getCodigo() == $tipo) {
            $cont1++;
            switch ($tipo)
            {            	
            	case 1:
            		$somatipo1 += $row["Quantidade"];
            		$tipospa[$j]->criaProdartistica($row["Codigo"], $unidade, $anobase,$somatipo1);
            		break;
            	case 2:
            		$somatipo2 += $row["Quantidade"];
            		$tipospa[$j]->criaProdartistica($row["Codigo"], $unidade, $anobase,$somatipo2);
            		break;
            	case 3:
            		$somatipo3 += $row["Quantidade"];
            		$tipospa[$j]->criaProdartistica($row["Codigo"], $unidade, $anobase,$somatipo3);
            		break;
          }           
        }
     }
  }
}
if ($cont1 == 0) {
    Utils::redirect('prodartistica', 'incluiprodartistica');
}
$daopa->fechar();
//ob_end_flush();
?>

<script type="text/javascript">
        function direciona(botao) {
            document.getElementById('pa').action = "?modulo=prodartistica&acao=opprodartistica";
            document.getElementById('pa').submit();

        }
</script>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li><a href="<?php echo Utils::createLink("prodartistica", "incluiprodartistica"); ?>" >Produção art&iacute;stica</a></li>
		    <li class="active">Consulta</li>
		</ul>
	</div>
</head>
<form class="form-horizontal" name="pa" id="pa" method="post" action="<?php echo Utils::createLink('prodartistica', 'altprodartistica'); ?>">
    <h3 class="card-title"> Produ&ccedil;&atilde;o Art&iacute;stica </h3>

    <table>
        <tr>
            <th>Itens</th>
            <th>Quantidade</th>
        </tr>
        <?php for ($i = 1; $i <= $tamanho; $i++) { ?>
            <tr><td>
                    <?php print ($tipospa[$i]->getNome()); ?></td><td align="center">
                    <?php
                    print $tipospa[$i]->getProdartistica()->getQuantidade();
                }
                ?></td></tr>

    </table>
    <br />
    <?php if (!$lock->getLocked()){ ?>
    <input class="btn btn-info" type="submit"  value="Alterar" />
    <input class="form-control"name="operacao" type="hidden" readonly="readonly" value="Excluir" />
    <input type="button" onclick="direciona(1);" value="Excluir" />
    <?php } ?>
</form>

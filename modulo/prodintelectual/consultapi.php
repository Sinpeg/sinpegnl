<?php
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[4]) {
    header("Location:index.php");
}
//$sessao = $_SESSION["sessao"];
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
    $daopi = new ProdintelectualDAO();
    
    $validade=2014;
     
    if ($anobase<=2013){
    	$validade=2013;
    }
    
    $rows_tpi = $daotpi->Lista($validade);
    foreach ($rows_tpi as $row) {
        $cont++;
        $tipospi[$cont] = new Tipoprodintelectual();
        $tipospi[$cont]->setCodigo($row['Codigo']);
        $tipospi[$cont]->setNome($row['Nome']);
    }
    $cont1 = 0;

   $uni = new Unidade();
   $uni->setCodunidade($codunidade);
   $soma = 0;
   $tamanho = count($tipospi);
   
   $rows_pi = $daopi->buscapiunidade($codunidade, $anobase,$validade);

    foreach ($rows_pi as $row) {
        $tipo = $row['Tipo'];
        for ($i = 1; $i <= $tamanho; $i++) {
            if ($tipospi[$i]->getCodigo() == $tipo) {
                $cont1++;
                $tipospi[$i]->criaProdintelectual($row["Codigo"], $uni, $anobase, $row["Quantidade"]);
                $soma +=$row["Quantidade"];
            }
        }
    }

$daotpi->fechar();
//ob_end_flush();
?>
<script language="JavaScript">
    function direciona(botao) {
        switch (botao) {
            case 1:
                document.getElementById('pi').action = "?modulo=prodintelectual&acao=altprodintelectual";
                document.getElementById('pi').submit();
                break;
            case 2:
                document.getElementById('pi').action = "?modulo=prodintelectual&acao=consultaitempi";            
                document.getElementById('pi').submit();
                break;
        }
    }

</script>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
		    <li><a href="<?php echo Utils::createLink("prodintelectual", "cursosunidade"); ?>">Produção Intelectual</a></li>
		    <li><a href="<?php echo Utils::createLink("prodintelectual", "incluiprodintelectual"); ?>">Incluir</a></li>
			<li class="active">Consulta</li>
		</ul>
	</div>
</head>
<form class="form-horizontal" name="pi" id="pi" method="post">
    <h3 class="card-title"> Produ&ccedil;ao Intelectual </h3> 
    <div class="msg" id="msg"></div>
    <br />

    <table>
        <tr align="center" style="font-style: italic;">
            <td width="600px">Itens</td>
            <td width="100px">Quantidade</td>
        </tr>
        <?php foreach ($tipospi as $t) {
            if ($t->getProdintelectual() != NULL) {
                ?>
                <tr>
                    <td><?php print ($t->getNome()); ?></td>
                    <td><?php print $t->getProdintelectual()->getQuantidade(); ?></td>
                </tr>
            <?php }
        }
        ?>
        <tr><td>Total Geral</td><td><b><?php print $soma; ?></b></td></tr>
    </table>
    <input class="form-control"name="operacao" type="hidden" value="A" />
    <input type="button" onclick="direciona(1);" value="Alterar" class="btn btn-info"/>
  <!--  <input type="button" onclick="direciona(2);" value="Incluir novo item" class="btn btn-info" /> -->
    <input	type="hidden" name="codunidade" value="<?php print $codunidade; ?>" />
</form>
</body>
</html>


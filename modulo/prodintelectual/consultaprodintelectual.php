<?php
//require_once('../../includes/classes/sessao.php');
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
require_once('./classes/curso.php');


//Instancia a classe
$proDAO = new prodintelectualDAO();

if($codunidade == 100000){
    $rows = $proDAO->producaoAdm($anobase);
}else{
    $rows = $proDAO->producaoUni($anobase, $codunidade);
}



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
        if (($anobase>=2018 && $row['Codigo']<>120 && $row['Codigo']<>132 && $row['Codigo']<>133 && $row['Codigo']<>134 && $validade==2014) || ($anobase<2018)) {
    	    	$tipospi[$cont] = new Tipoprodintelectual();
                $tipospi[$cont]->setCodigo($row['Codigo']);
                $tipospi[$cont]->setNome($row['Nome']);
                $cont++;
        }
    }
    $cont1 = 0;
    $uni = new Unidade();
    $uni->setCodunidade($codunidade);
   
    $soma = 0;
    $tiposnaoinseridos=0;
   if ($validade<=2013){
      $rows_pi = $daopi->tiponaoinserido($codunidade, $anobase,$validade);
      $tiposnaoinseridos=$rows_pi->rowCount() ;
     }
    
    if (($tiposnaoinseridos== 0) || ($validade>2013)){
        $rows_pi = $daopi->buscapiunidade($codunidade, $anobase);
    //}
    $tamanho = count($tipospi);
    foreach ($rows_pi as $row) {
    	 
        $tipo = $row['Tipo'];
        for ($i = 0; $i < $tamanho; $i++) {
        	
            if ($tipospi[$i]->getCodigo() == $tipo) {
                $tipospi[$i]->criaProdintelectual($row["Codigo"], $uni, $anobase, $row["Quantidade"]);
                $soma +=$row["Quantidade"];
                $cont1++;
            }
        }
    }
    }//
$daotpi->fechar();
if ($cont1 == 0) {
   Utils::redirect('prodintelectual', 'incluiprodintelectual');
}
?>

<script language="javascript">
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
		    <li class="active">
                <a href="<?php echo Utils::createLink("prodintelectual", "consultaprodintelectual"); ?>">Produção Intelectual</a>
			    <i class="fas fa-long-arrow-alt-right"></i>
                Consulta</li>
		</ul>
	</div>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Produção Intelectual</h3> 	
    </div>
    <form class="form-horizontal" name="pi" id="pi" method="POST">

        <div class="card-body">
            <div class="msg" id="msg"></div>
            <table class="table table-bordered table-hover" id="tabelaConsultaProd">
                <thead>
                    <tr style="font-style: italic; font-weight:bold;">
                        <th>Itens</th>
                        <th>Quantidade</th>
                    </tr>
                </thead>
                 <tbody>    
                    <?php foreach ($tipospi as $t) { ?>
                        <tr>
                            <td><?php print ($t->getNome()); ?></td>
                            <td align="center"><?php print $t->getProdintelectual()->getQuantidade(); ?></td>
                        </tr>
                    <?php } ?>
                    <tfoot>
                        <td>
                            <strong>Total Geral</strong>
                        </td>
                        <td align="center"><b><?php print $soma; ?></b></td>
                    </tfoot>
                </tbody>
            </table>
        </div>
        <table class="card-body">
            <td align="center" colspan="2">
                <input class="form-control"name="operacao" type="hidden" value="A" />
                <input type="button" onclick="direciona(1);" value="Alterar" id="gravar" class="btn btn-info" />
                <!--   <input type="button" onclick="direciona(2);" value="Incluir novo item" /> -->
            </td>
        </table>
    </form>
</div>

<script>
 $(function () {
    $('#tabelaConsultaProd').DataTable({
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
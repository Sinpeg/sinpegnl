<?php
//require_once('../../includes/classes/sessao.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[28]) {
    header("Location:index.php");
}  else {
    $sessao = $_SESSION["sessao"];
//	$nomeunidade = $sessao->getNomeunidade();
//	$codunidade = $sessao->getCodunidade();
//	$responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();
//    $aplicacoes = $_SESSION["sessao"]->getAplicacoes();
//    if (!$aplicacoes[28]) {
//        $mensagem = urlencode(" ");
//        $cadeia = "location:../saida/erro.php?codigo=2&mensagem=" . $mensagem;
//        header($cadeia);
////        exit();
//    }
//	require_once('../../includes/dao/PDOConnectionFactory.php');
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
}
?>        <script type="text/Javascript">
            function send(action)
            {
            switch(action) {
            case 'alterar':
            url = '?modulo=eapimetodologicas&acao=alterapimetodologicas';
            break;
            case 'incluir':
            url = '?modulo=eapimetodologicas&acao=incluipimetodologicas';
            break;
            case 'sair':
            url = "../saida/saida.php";
            break;
            }

            document.forms[0].action = url;
            document.forms[0].submit();
            }
        </script>
        <head>
	    	<div class="bs-example">
	    		<ul class="breadcrumb">
	    			<li><a href="<?php echo Utils::createLink("eapimetodologicas", "incluipimetodologicas"); ?>">Pr&aacute;ticas de interven&ccedil;&otilde;es metodol&oacute;gicas da escola de aplica&ccedil;&atilde;o</a></li>
	    			<li class="active">Consulta</li>
	    		</ul>
	    	</div>
		</head>
        <form class="form-horizontal" name="gravar" method="post">
            <h3 class="card-title">Pr&aacute;ticas de Interven&ccedil;&otilde;es Metodol&oacute;gicas da Escola de Aplica&ccedil;&atilde;o</h3>
<?php if ($cont > 0) { ?>
                <table>
                    <tr style="font-style: italic;" align="center">
                        <td>Itens</td>
                        <td>Quantidade</td>
                    </tr>
                    <tr><td>Em Execu&ccedil;&atilde;o</td><td> <?php echo $pimetodologicas->getExecucao(); ?></td> </tr>
                    <tr><td>Em Tramita&ccedil;&atilde;o</td><td> <?php echo $pimetodologicas->getTramitacao(); ?></td> </tr>
                    <tr><td>Cancelados</td><td> <?php echo $pimetodologicas->getCancelado(); ?></td> </tr>
                    <tr><td>Suspensos</td><td> <?php echo $pimetodologicas->getSuspenso(); ?></td> </tr>
                    <tr><td>Conclu&iacute;dos</td><td> <?php echo $pimetodologicas->getConcluido(); ?></td> </tr>
                    <tr><th colspan="2" align="left">N&uacute;mero de Participantes</th></tr>
                    <tr><td>Docentes</td><td> <?php echo $pimetodologicas->getDocentes(); ?></td> </tr>
                    <tr><td>T&eacute;cnicos</td><td> <?php echo $pimetodologicas->getTecnicos(); ?></td> </tr>
                    <tr><td>Discentes Bolsistas da Gradua&ccedil;&atilde;o</td><td> <?php echo $pimetodologicas->getBolsistas(); ?></td> </tr>
                    <tr><td>Discentes Não Bolsistas da Gradua&ccedil;&atilde;o</td><td> <?php echo $pimetodologicas->getNBolsistas(); ?></td> </tr>
                    <tr><td>Discentes da P&oacute;s-Gradua&ccedil;&atilde;o</td><td> <?php echo $pimetodologicas->getPosgraduacao(); ?></td> </tr>
                    <tr><td>Pessoas de Outras Institui&ccedil;&otilde;es</td><td> <?php echo $pimetodologicas->getOutras() ?></td> </tr>
                </table> <br/>

                <input type="button" value="Alterar" onclick="send('alterar');" />

<?php
} else {
    Utils::redirect('eapimetodologicas', 'incluipimetodologicas');
//    $cadeia = "location:incluipimetodologicas.php";
//    header($cadeia);
//    exit();
}
//ob_end_flush();
?>
        </form>

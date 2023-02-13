<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[25]) {
    header("Location:index.php");
}else {
    $sessao = $_SESSION["sessao"];
//	$nomeunidade = $sessao->getNomeunidade();
//	$codunidade = $sessao->getCodunidade();
//	$responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();
//	require_once('../../includes/dao/PDOConnectionFactory.php');
    require_once('dao/eaprojextensaoDAO.php');
    require_once('classes/eaprojextensao.php');
    $projextensao = array();
    $cont = 0;
    $daope = new EAprojextensaoDAO();
    $projextensao = new EAprojextensao();
    $rows_pe = $daope->buscape($anobase);
    foreach ($rows_pe as $row) {
        $cont++;
        $projextensao->setCodigo($row['Codigo']);
        $projextensao->setExecucao($row['EmExecucao']);
        $projextensao->setTramitacao($row['Emtramitacao']);
        $projextensao->setCancelado($row['Cancelado']);
        $projextensao->setSuspenso($row['Suspenso']);
        $projextensao->setConcluido($row['Concluido']);
        $projextensao->setDocentes($row['Qdocentes']);
        $projextensao->setTecnicos($row['Qtecnicos']);
        $projextensao->setBolsistas($row['Qgradbolsistas']);
        $projextensao->setNBolsistas($row['Qgradnbolsistas']);
        $projextensao->setPosgraduao($row['Qposgrad']);
        $projextensao->setOutras($row['QoutrasInstituicoes']);
        $projextensao->setAno($row['Ano']);
    }
    $daope->fechar();
    if ($cont == 0) {
        Utils::redirect('eaprojextensao', 'incluiextensao');
//	   	header("location:incluiextensao.php");
//	   	exit();
    }
}
//ob_end_flush();
?>
<script type="text/javascript">
    function send(action)
    {
    switch(action) {
    case 'alterar':
    url = '?modulo=eaprojextensao&acao=alteraextensao';
    break;
    case 'incluir':
    url = '?modulo=eaprojextensao&acao=incluiextensao';
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
    			<li><a href="<?php echo Utils::createLink("eaprojextensao", "incluiextensao"); ?>">Projetos de extens&atilde;o</a></li>
    			<li class="active">Consulta</li>
    		</ul>
    	</div>
</head>
<form class="form-horizontal" name="gravar" method="post" action="incluiextensao.php">

    <h3 class="card-title">Projetos de Extens&atilde;o da Escola de Aplica&ccedil;&atilde;o</h3>

<?php if ($cont > 0) { ?>
        <table>
            <tr style="font-style: italic;" align="center">
                <td>Itens</td>
                <td>Quantidade</td>
            </tr>
            <tr><td>Em Execu&ccedil;&atilde;o</td><td> <?php echo $projextensao->getExecucao(); ?></td> </tr>
            <tr><td>Em Tramita&ccedil;&atilde;o</td><td> <?php echo $projextensao->getTramitacao(); ?></td> </tr>
            <tr><td>Cancelados</td><td> <?php echo $projextensao->getCancelado(); ?></td> </tr>
            <tr><td>Suspensos</td><td> <?php echo $projextensao->getSuspenso(); ?></td> </tr>
            <tr><td>Conclu&iacute;dos</td><td> <?php echo $projextensao->getConcluido(); ?></td> </tr>
            <tr><th colspan="2" align="left">N&uacute;mero de Participantes</th></tr>
            <tr><td>Docentes</td><td> <?php echo $projextensao->getDocentes(); ?></td> </tr>
            <tr><td>T&eacute;cnicos</td><td> <?php echo $projextensao->getTecnicos(); ?></td> </tr>
            <tr><td>Discentes Bolsistas da Gradua&ccedil;&atilde;o</td><td> <?php echo $projextensao->getBolsistas(); ?></td> </tr>
            <tr><td>Discentes Não Bolsistas da Gradua&ccedil;&atilde;o</td><td> <?php echo $projextensao->getNBolsistas(); ?></td> </tr>
            <tr><td>Discentes da P&oacute;s-Gradua&ccedil;&atilde;o</td><td> <?php echo $projextensao->getPosgraduacao(); ?></td> </tr>
            <tr><td>Pessoas de Outras Institui&ccedil;&otilde;es</td><td> <?php echo $projextensao->getOutras() ?></td> </tr>
        </table> <br/>

        <input type="button" value="Alterar" onclick="send('alterar');" class="btn btn-info" />

<?php
} else {
    Utils::redirect('eaprojextensao', 'incluiextensao');
}
?>
</form>

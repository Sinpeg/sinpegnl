<?php
//require_once('../../includes/classes/sessao.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[5]) {
    header("Location:index.php");
} else {
    require_once('classes/tppremios.php');
    require_once ('dao/tppremiosDAO.php');
    $itens = array();
    $cont = 0;
    $dao = new TppremiosDAO();
    $rows = $dao->lista();
    foreach ($rows as $row) {
        $cont++;
        $itens[$cont] = new Tppremios();
        $itens[$cont]->setCodpremio($row['CodPremio']);
        $itens[$cont]->setNome($row['Nome']);
    }

    $dao->fechar();
}
if ($cont == 0) {
    Utils::redirect('premios', 'incitem');
}
?>
<script>

    function direciona(botao) {
        if (botao == 1) {
            document.getElementById('pre').action = "?modulo=premios&acao=incitem";
            document.getElementById('pre').submit();
        }

    }
</script>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
		    <li><a href="<?php echo Utils::createLink("premios", "incluipremios"); ?>">Pr&ecirc;mios</a></li>
			<li class="active">Consulta</li>
		</ul>
	</div>
</head>
<form class="form-horizontal" id="pre" name="pre" method="POST">
<h3 class="card-title">Itens de Produ&ccedil;&atilde;o Intelectual</h3>
<?php if ($cont > 0) { ?>
    <table>
        <tr align="center" style="font-style:italic;">
            <th>Itens</th>
            <th width="50px">Alterar</th>
            <th width="50px">Excluir</th>
        </tr>
        <?php foreach ($itens as $p) { ?>
            <tr><td><?php echo ($p->getNome()); ?></td>
                <td align="center">
                    <a href="<?php echo Utils::createLink('premios', 'altitem', array('codigo' => $p->getCodigo(), 'operacao' => "A")); ?>"
                       target="_self" ><img src="webroot/img/editar.gif" alt="Alterar" width="19" height="19" /> </a>
                </td>
                <td align="center">
                    <a href="<?php echo Utils::createLink('premios', 'delitem', array('codigo' => $p->getCodigo())); ?>"
                       target="_self" ><img src="webroot/img/delete.png" alt="Excluir" width="19" height="19" /> </a>
                </td>
            </tr>
        <?php } ?>
    </table> <br/>
    <input type="button" onclick='direciona(1);' value="Incluir" />
    <input class="form-control"type="hidden" name="codigo" value="<?php print $p->getCodigo(); ?>" />
    <?php
} else {
    Utils::redirect("premios", "incitem");
//            $cadeia = "location:incitempi.php";
//		header($cadeia);
}
?>
</form>


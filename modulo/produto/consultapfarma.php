<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');

$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[16]) {
    header("Location:index.php");
}else {

    $sessao = $_SESSION["sessao"];
//	$nomeunidade = $sessao->getNomeunidade();
//	$codunidade = $sessao->getCodunidade();
//	$responsavel = $sessao->getResponsavel();
//	$anobase = $sessao->getAnobase();
//	$codestruturado = $sessao->getCodestruturado();
//	require_once('../../includes/dao/PDOConnectionFactory.php');
    require_once('classes/produtos.php');
    require_once('classes/prodfarmacia.php');
    require_once('dao/produtosDAO.php');
    require_once('dao/prodfarmaciaDAO.php');
    $daoproduto = new ProdutosDAO();
    $rows_prod = $daoproduto->Lista();
    $cont = 0;
    foreach ($rows_prod as $row) {
        $cont++;
        $pro[$cont] = new Produtos();
        $pro[$cont]->setCodigo($row['Codigo']);
        $pro[$cont]->setNome($row['Nome']);
    }
    $daoproduto->fechar();
//ob_end_flush();
}
?>
<form class="form-horizontal" name="pf" id="pf" method="post">
    <h3 class="card-title">Consulta Produ&ccedil;&atilde;o da Farm&aacute;cia</h3><br/>
    <table class="table table-striped table-bordered table-condensed table-hover">
        <tr>
            <td>Produto:</td>
            <td><select class="custom-select" name="produto" onchange="ajaxBusca();">
                    <?php foreach ($pro as $p) { ?>
                        <option value="<?php print $p->getCodigo(); ?>">
                            <?php print ($p->getNome()); ?></option>
                        <?php } ?>
                </select></td>
        </tr>
    </table>
    <div id="txtHint"></div>
    <br/>
    <input type="button" onclick="direciona(4);" class="btn btn-info" value="Incluir nova produ&ccedil;&atilde;o" />
</form>
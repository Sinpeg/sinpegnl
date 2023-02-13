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
//	require_once('../../includes/dao/PDOConnectionFactory.php');
    // var_dump($usuario);
    require_once('dao/produtosDAO.php');
    require_once('classes/produtos.php');
    require_once('dao/prodfarmaciaDAO.php');
    require_once('classes/prodfarmacia.php');
    $daoproduto = new ProdutosDAO();
    $rows_prod = $daoproduto->Lista();
    $cont = 0;
    foreach ($rows_prod as $row) {
        $cont++;
        $produto[$cont] = new Produtos();
        $produto[$cont]->setCodigo($row['Codigo']);
        $produto[$cont]->setNome($row['Nome']);
    }


    $daoproduto->fechar();
}
//ob_end_flush();
?>
<form class="form-horizontal" name="pf" method="post" >
    <h3 class="card-title">Produ&ccedil;&atilde;o da Farm&aacute;cia</h3>
    <div class="msg" id="msg"></div>
    <table>
        <tr>
            <td>M&ecirc;s</td>
            <td><select class="custom-select" name="mes" onkeydown="TABEnter();">
                    <option value="1">janeiro</option>
                    <option value="2">fevereiro</option>
                    <option value="3">mar&ccedil;o</option>
                    <option value="4">abril</option>
                    <option value="5">maio</option>
                    <option value="6">junho</option>
                    <option value="7">julho</option>
                    <option value="8">agosto</option>
                    <option value="9">setembro</option>
                    <option value="10">outubro</option>
                    <option value="11">novembro</option>
                    <option value="12">dezembro</option>
                </select></td>
        </tr>
        <tr>
            <td >Produto</td>
            <td><select onkeydown="TABEnter();" name="produto">
                    <?php foreach ($produto as $p) { ?>
                        <option value="<?php print $p->getCodigo(); ?>">
                            <?php print ($p->getNome()); ?></option>
                    <?php } ?>
                </select></td>
        </tr>
        <tr><td>Pre&ccedil;o</td>
            <td>R$<input class="form-control"onkeydown="TABEnter();" type="text"  onblur="mascaradec(this.value);" name="preco" value="" maxlength="6" size="10"/></td></tr>
        <tr><td>Quantidade</td>
            <td><input class="form-control"type="text" onkeydown="TABEnter();" name="quantidade" onkeypress="return SomenteNumero(event);" value="" maxlength="4" size="10"/></td></tr>
    </table>
    <input class="form-control"type="hidden" name="operacao" value="I" readonly="readonly"  />
    <input type="button" onclick="direciona(1);" class="btn btn-info" value="Gravar" />&ensp;
    <input type="button" onclick="javascript:history.go(-1)" class="btn btn-info" value="Voltar" />
</form>
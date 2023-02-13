<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');

$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[16]) {
    header("Location:index.php");
}  else {
    $sessao = $_SESSION["sessao"];
//	$nomeunidade = $sessao->getNomeunidade();
//	$codunidade = $sessao->getCodunidade();
//	$responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();
//    require_once('../../includes/dao/PDOConnectionFactory.php');

    // var_dump($usuario);
    require_once('dao/produtosDAO.php');
    require_once('classes/produtos.php');
    require_once('dao/prodfarmaciaDAO.php');
    require_once('classes/prodfarmacia.php');


    //producao da farmacia
    $codigo = $_GET['codigo'];
    if (is_numeric($codigo) && $codigo != "") {
        //produtos
        $daoproduto = new ProdutosDAO();

        $rows_prod = $daoproduto->Lista();

        $cont = 0;
        foreach ($rows_prod as $row) {
            $cont++;
            $produto[$cont] = new Produtos();
            $produto[$cont]->setCodigo($row['Codigo']);
            $produto[$cont]->setNome($row['Nome']);
        }

        $dao = new ProdfarmaciaDAO();
        $rows = $dao->busca($codigo);
        $ind = 0;
        foreach ($rows as $row) {
            for ($i = 1; $i <= $cont; $i++) {
                if ($produto[$i]->getCodigo() == $row['Tipoproduto']) {
                    $produto[$i]->criaProdfarmacia($row['Codigo'], $row['Quantidade'], $anobase, $row['Preco'], $row['Mes']);
                    $ind = $i;
                }
            }
        }
        $dao->fechar();
        $selecionado = "selected='selected'";
    } else {
        $mensagem = urlencode(" ");
        $cadeia = "location:../saida/erro.php?codigo=1&mensagem=" . $mensagem;
        header($cadeia);
        //exit();
    }
}
//ob_end_flush();
?>
<form class="form-horizontal" name="pf" method="post">
    <h3 class="card-title">Produ&ccedil;&atilde;o da Farm&aacute;cia</h3>
    <div class="msg" id="msg"></div>
    <table>
        <tr>
            <td width="100px">M&ecirc;s</td>
            <td><select class="custom-select" name="mes" onkeydown="TABEnter();">
                    <option

                        <?php if ($produto[$ind]->getProdfarmacia()->getMes() == 1) {
                            print $selecionado;
                        } ?>
                        value="1">janeiro</option>
                    <option

<?php if ($produto[$ind]->getProdfarmacia()->getMes() == 2) {
    print $selecionado;
} ?>
                        value="2">fevereiro</option>
                    <option

                        <?php if ($produto[$ind]->getProdfarmacia()->getMes() == 3) {
                            print $selecionado;
                        } ?>
                        value="3">mar&ccedil;o</option>
                    <option

<?php if ($produto[$ind]->getProdfarmacia()->getMes() == 4) {
    print $selecionado;
} ?>
                        value="4">abril</option>
                    <option

                        <?php if ($produto[$ind]->getProdfarmacia()->getMes() == 5) {
                            print $selecionado;
                        } ?>
                        value="5">maio</option>
                    <option

<?php if ($produto[$ind]->getProdfarmacia()->getMes() == 6) {
    print $selecionado;
} ?>
                        value="6">junho</option>
                    <option

                        <?php if ($produto[$ind]->getProdfarmacia()->getMes() == 7) {
                            print $selecionado;
                        } ?>
                        value="7">julho</option>
                    <option

<?php if ($produto[$ind]->getProdfarmacia()->getMes() == 8) {
    print $selecionado;
} ?>
                        value="8">agosto</option>
                    <option

<?php if ($produto[$ind]->getProdfarmacia()->getMes() == 9) {
    print $selecionado;
} ?>
                        value="9">setembro</option>
                    <option

                        <?php if ($produto[$ind]->getProdfarmacia()->getMes() == 10) {
                            print $selecionado;
                        } ?>
                        value="10">outubro</option>
                    <option

                        <?php if ($produto[$ind]->getProdfarmacia()->getMes() == 11) {
                            print $selecionado;
                        } ?>
                        value="11">novembro</option>
                    <option

<?php if ($produto[$ind]->getProdfarmacia()->getMes() == 12) {
    print $selecionado;
} ?>
                        value="12">dezembro</option>
                </select></td>
        </tr>
        <tr>
            <td>Produto</td>
            <td><select onkeydown="TABEnter();" name="produto">
<?php foreach ($produto as $p) { ?>
                        <option

    <?php
    if ($p->getProdfarmacia() != NULL) {
        print ($selecionado);
    }
    ?>
                            value="<?php print $p->getCodigo(); ?>">
    <?php print ($p->getNome()); ?></option>
<?php } ?>
                </select></td>
        </tr>
        <tr>
            <td>Pre&ccedil;o</td>
            <td>R$<input class="form-control"onkeydown="TABEnter();" type="text"
                         onblur="mascaradec(this.value);" name="preco"
                         value="<?php print str_replace(".", ",", $produto[$ind]->getProdfarmacia()->getPreco()); ?>"
                         maxlength="6" size="10" /></td>
        </tr>
        <tr>
            <td>Quantidade</td>
            <td>
                <input class="form-control"type="text" onkeydown="TABEnter();" name="quantidade"
                       onkeypress="return SomenteNumero(event);"
                       value="<?php print $produto[$ind]->getProdfarmacia()->getQuantidade(); ?>" maxlength="4"   size="10"/>
            </td></tr>
    </table><br/>
    <input class="form-control"type="hidden" name="codigo" value="<?php echo $_GET['codigo'];?>" class="btn btn-info" readonly="readonly"  />
    <input class="form-control"type="hidden" name="operacao" value="A" class="btn btn-info" readonly="readonly"  />
    <input type="button" onclick="direciona(1);" class="btn btn-info" value="Gravar" />&ensp;
    <!-- <input type="button" onclick="direciona(3);" class="btn btn-info" value="Consultar" />-->
    <input type="button" onclick="javascript:history.go(-1)" class="btn btn-info" value="Voltar" />

</form>

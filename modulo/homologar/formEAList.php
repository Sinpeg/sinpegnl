<?php
if (!$aplicacoes[43]) {
    Error::addErro("Você não possui permissão para acessar a aplicação solicitada!");
    Utils::redirect();
}
?>
<?php
/**
 * Atualizado em 02/09/2014
 * Por: Diego do Couto
 */
if (!isset($_SESSION["sessao"])) {
    header("location:index.php");
    exit();
} else {
    $codunidade = filter_input(INPUT_GET, 'codunidade', FILTER_DEFAULT);
    require_once('modulo/acessib/dao/acessibilidadeDAO.php');
    require_once('modulo/acessib/classes/acessibilidade.php');
    require_once('modulo/acessib/dao/tpacessibilidadeDAO.php');
    require_once('modulo/acessib/classes/tpacessibilidade.php');
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $tiposea = array();
    $daotea = new TpacessibilidadeDAO();
    $daoea = new AcessibilidadeDAO();
    $cont = 0;
    $parametro = 2012;
    $str = "Quantidade";
    if ($anobase > 2012) {
        $parametro = 2013;
        $str = "Situação";
    }
    $rows_tea = $daotea->Lista($parametro);
    foreach ($rows_tea as $row) {
        $cont++;
        $tiposea[$cont] = new Tpacessibilidade();
        $tiposea[$cont]->setCodigo($row['Codigo']);
        $tiposea[$cont]->setNome($row['Nome']);
    }
    $tamanho = count($tiposea);
    $cont1 = 0;
    $soma = 0;
    $rows_ea = $daoea->buscaeaunidade($codunidade, $anobase);
    foreach ($rows_ea as $row) {
        $tipo = $row['Tipo'];
        for ($i = 1; $i <= $tamanho; $i++) {
            if ($tiposea[$i]->getCodigo() == $tipo) {
                $tiposea[$i]->criaAcessib($row["CodigoEstrutura"], $unidade, $anobase, $row["Quantidade"]);
                $soma += $row["Quantidade"];
                $cont1++;
            }
        }
    }
    $daoea->fechar();
    if ($cont1 == 0) {
        Utils::redirect("acessib", "incluiacess");
    }
}
?>
<h3 class="card-title">Estruturas de Acessibilidade</h3>
<table class="card-body">
    <tr align="center" style="font-style:italic;">
        <td>Itens</td>
        <td><?php echo $str; ?></td>
    </tr>
    <?php for ($i = 1; $i <= $tamanho; $i++) { ?>
        <tr>
            <td> <?php print ($tiposea[$i]->getNome()); ?></td>
            <td align="center"> 
                <?php
                if ($parametro <= 2012) {
                    print $tiposea[$i]->getAcessib()->getQuantidade();
                } else {
                    print $tiposea[$i]->getAcessib()->getQuantidade() == "1" ? "Sim" : "Não";
                }
            }
            ?></td>
    </tr>
    <?php
    if ($anobase <= 2012) {
        ?>
        <tr style="font-style:italic;"><td align="center" >Total geral</td><td align="center"><?php print $soma; ?></td></tr>
        <?php }
        ?>
</table>

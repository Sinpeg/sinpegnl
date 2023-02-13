<?php
if (!$aplicacoes[9]) {
    header("Location:index.php");
} else {
    require_once('dao/infraensinoDAO.php');
    require_once('classes/infraensino.php');
    require_once('dao/tipoinfraensinoDAO.php');
    require_once('classes/tipoinfraensino.php');
    $tiposie = array(); // tipo associado a Infraestrutura de Ensino
    $cont = 0;
    $daoie = new InfraensinoDAO();
    $daotie = new TipoinfraensinoDAO();
    $rows_tie = $daotie->Lista();
    $ie_array = array(); // array de infraestrutura de ensino
    // itera entre todas os tipos de infraestrutura de ensino
    foreach ($rows_tie as $row) {
        $cont++;
        $tiposie[$cont] = new Tipoinfraensino();
        $tiposie[$cont]->setCodigo($row['Codigo']);
        $tiposie[$cont]->setNome($row['Nome']);
    }
    // fim 
    $cont1 = 0;
    $soma = 0; // soma das quantidades das infraestrutura de ensino
    $tamanho = count($tiposie);
    for ($j = 0; $j < count($array_codunidade); $j++) {
        $codunidade = $array_codunidade[$j]; // código da unidade em questão
        $rows_ie = $daoie->buscaieunidade($codunidade, $anobase);
        foreach ($rows_ie as $row) {
            $tipo = $row['Tipo'];
            for ($i = 1; $i <= $tamanho; $i++) {
                if ($tiposie[$i]->getCodigo() == $tipo) {
                    $cont1++;
                    $quant = (is_null($ie_array[$i]))?$row["Quantidade"]:$ie_array[$i]->getQuantidade()+$row["Quantidade"];
                    $ie = new Infraensino();
                    $ie->setQuantidade($quant); // 
                    $ie->setAno($anobase);
                    $ie->setTipo($tiposie[$i]);
                    $ie_array[$i] = $ie;
                    $soma += $row["Quantidade"];
                    $cont1++;
                }
            }
        }
    }
}
$daoie->fechar();
if ($cont1 == 0) {
    Utils::redirect('infraensino', 'incluiinfraensino');
}
?>
<script type="text/javascript">
    function direciona(botao) {
        switch (botao) {
            case 3:
                document.getElementById('gravar').action = "<?php echo Utils::createLink('infraensino', 'altinfraensino') ?>";
                document.getElementById('gravar').submit();
                break;
            case 2:
                document.getElementById('gravar').action = "../saida/saida.php";
                document.getElementById('gravar').submit();
                break;
        }
    }
</script>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li><a href="<?php echo Utils::createLink("infraensino", "incluiinfraensino"); ?>">Infraestrutura de ensino na unidade</a>
			<li class="active">Consulta</li>
		</ul>
	</div>
</head>
<form class="form-horizontal" name="gravar" id="gravar" method="post">
    <h3 class="card-title">Infraestrutura de Ensino</h3>
    <?php if ($cont > 0) { ?>
        <table id="tablesorter" class="table table-bordered table-hover">
            <tfoot>
                <tr>
                    <th colspan="7" class="ts-pager form-horizontal">
                        <button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
                        <button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
                        <span class="pagedisplay"></span> <!-- this can be any element, including an input class="form-control"-->
                        <button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
                        <button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
                        <select class="custom-select" title="Select page size">
                            <option selected="selected" value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="40">40</option>
                        </select>
                        <select class="pagenum input-mini" title="Select page number"></select>
                    </th>
                </tr>
            </tfoot>
            <thead>
                <tr>
                    <th>Itens</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 1; $i <= count($tiposie); $i++) { ?>
                    <tr>
                        <td><?php print ($tiposie[$i]->getNome()); ?></td>
                        <td><?php print ($ie_array[$i]->getQuantidade()); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
    </table>
    <?php } ?>
    <br/> <input type="button" onclick='direciona(3);' value="Alterar" class="btn btn-info" />
</form>

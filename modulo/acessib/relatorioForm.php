<?php
require_once('dao/tpacessibilidadeDAO.php');
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();

if (!$aplicacoes[10]) {
    header("Location:index.php");
}
?>
<div class="card card-info">
    <div class="card-header"><h3 class="card-title">Buscar Estruturas de Acessibilidade</h3></div>
    <form class="form-horizontal" name="us" id="us" method="POST" action="ajax/acessib/relatorioTab.php">
        <label for="ano">Ano/Período: </label>
        <input class="form-control"type="text" size="4" maxlength="4" id="ano" name="ano" value="" class="ano" /> 
        <span>a</span> 
        <input class="form-control"type="text" size="4" maxlength="4" name="ano1" value="" class="ano" />
        <?php
        $daotea = new TpacessibilidadeDAO();
        $resultado = $daotea->Lista();
        ?>
        <label for="tipo">Tipo da Estrutura: </label> 
        <select class="sel1" id="tipo" name="tipo">
            <option value="todos">Todos</option>
            <?php foreach ($resultado as $r) { ?>
                <option value="<?php print ($r['Codigo']); ?>">
                    <?php print ($r['Nome']); ?>
                </option>
            <?php } ?>
        </select>
        <label for="txtUnidade">Unidade: </label> 
        <input class="form-control"type="text" size="60" name="txtUnidade" value="" id="txtUnidade" class="txt" /> 
        <input type="button" id="buscaAjax" name="buscaAjax" value="Buscar" class="btn btn-default" />
        <div id="selecionar"></div>
        <input class="form-control"type="hidden" name="modulo" value="acessib"/>
    </form>
</div>
<div id="resultado"></div>

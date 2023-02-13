<?php
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[9]) {
 header("Location:index.php");
}
?>
<?php require_once 'dao/tipoinfraensinoDAO.php'; ?>
<form class="form-horizontal" name="us" id="us" method="POST" action="ajax/infraensino/relatorioTab.php">
 <fieldset>
 <legend>Buscar Infraestrutura de ensino</legend>
 <div>
 <label for="ano">Ano/Período: </label><input class="form-control"type="text" size="4" maxlength="4" id="ano" name="ano" value="" class="ano" />
 <span>a</span> <input class="form-control"type="text" size="4" maxlength="4" name="ano1" value="" class="ano" />
 </div>
 <div>
 <?php
 $daotin = new TipoinfraensinoDAO();
 $row = $daotin->Lista();
 ?>
 <label for="tipo">Tipo da infraestrutura de ensino: </label> <select
 name="tipo" class="sel1">
 <option value=0>Todas</option>
 <?php foreach ($row as $r) { ?>
 <option value=<?php print $r['Codigo']; ?>>
 <?php print ($r['Nome']); ?>
 </option>
 <?php } ?>
 </select>
 </div>
 <div>
 <label for="unidade">Unidade: </label> 
 <input class="form-control"type="text" size="60" id="txtUnidade" name="txtUnidade" value="" class="txt" />
 <input class="form-control"type="hidden" name="modulo" value="infraensino"/>
 <input type="button" name="botao" id="buscaAjax" value="Buscar" class="btn btn-default" />
 </div>
 </fieldset>
</form>
<div class="msg" id="msg1"></div>
<div id="resultado"></div>
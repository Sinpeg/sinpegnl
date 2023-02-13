<?php
ob_start();
echo ini_get('display_errors');
if (!ini_get('display_errors')) {
 ini_set('display_errors', 1);
 ini_set('error_reporting', E_ALL & ~E_NOTICE);
}
?>
<?php
if (!$aplicacoes[47]) {
    header("Location:../../index.php");
    exit;
}
if (!isset($_SESSION["sessao"])) {
    header("Location:../../index.php");
}
?>

<form class="form-horizontal" name="us" id="us" method="post" 
      action="ajax/biblio/consultabiblio.php"> 
    <fieldset> 
        <legend>Buscar Biblioteca</legend> 
        <div> 
            <label for="ano">Ano/Período: </label><input class="form-control"type="text" size="4" 
                                                         maxlength="4" id="ano" name="ano" value="" class="ano" /> <span 
                                                         class="intermediario">a</span> <input class="form-control"type="text" size="4" 
                                                         maxlength="4" name="ano1" value="" class="ano" /> 
        </div> 

        <div> 
            <label for="unidade">Biblioteca: </label> <input class="form-control"type="text" size="60" id="search" name="unidade" value="" class="txt" />
             <input type="button" name="botao" id="search_unidade" value="Buscar" class="btn" />
             <input class="form-control"type="hidden" size="1" name="oculto" value="b" class="txt" />
             
        </div> 
        <div id="selecionar"> 
        </div> 
    </fieldset> 
</form> 
<div class="msg" id="msg1"></div> 
<div id="resultado" class="resultado"></div> 
<?php require_once 'dao/microsDAO.php'; ?> 
<form class="form-horizontal" name="us" id="us" method="post" action="ajax/micros/consultamicros.php"> 
    <fieldset> 
        <legend>Buscar Micros</legend> 
        <div> 
            <label for="ano">Ano/Período: </label><input class="form-control"type="text" size="4" 
                                                         maxlength="4" id="ano" name="ano" value="" class="ano" /> <span 
                                                         class="intermediario">a</span> <input class="form-control"type="text" size="4" 
                                                         maxlength="4" name="ano1" value="" class="ano" /> 
        </div> 

        <div> 
            <label for="unidade">Unidade: </label> <input class="form-control"type="text" size="60" id="search" name="unidade" value="" class="txt" /> <input type="button" name="botao" id="search_unidade" value="Buscar" class="btn" /> 
        </div> 
        <div id="selecionar"> 
        </div> 
    </fieldset> 
</form> 
<div class="msg" id="msg1"></div> 
<div id="resultado" class="resultado"></div> 
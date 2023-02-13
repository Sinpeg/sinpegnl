<?php
if (!$aplicacoes[34]) {
    header("Location:../../index.php");
    exit;
}
if (!isset($_SESSION["sessao"])) {
    header("Location:../../index.php");
}
?>

 <style>
/* The Modal (background) */
#modalLoading {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}


.loader {
  left:50%;
  position:absolute;
  top:40%;
  left:45%;	
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 100px;
  height: 100px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}
<!--
.modal {
  text-align: center;
  padding: 0!important;
  
}

.modal:before {
  content: '';
  display: inline-block;
  height: 100%;
  vertical-align: middle;
  margin-right: -4px;
}

.modal-dialog {
  display: inline-block;
  text-align: left;
  vertical-align: middle;
  width:55%;
}
-->


/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

<!-- Modal Loading-->
<div class="" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="modalLoading" aria-hidden="true">  
    <div class="loader"></div> 
</div>


<?php require_once 'dao/tipoinfraensinoDAO.php'; ?> 
<form class="form-horizontal" name="us" id="us" method="post" 
      action="ajax/infraensino/consultainfraens.php"> 
    <fieldset> 
        <legend>Buscar Infraestrutura de ensino</legend> 
        <table>
        
        <tr><td><label for="ano">Ano/Período: </label></td>
        <td><div class="col-xs-2"><input class="form-control"type="text" size="4" maxlength="4" id="ano" placeholder="Início" name="ano" value="" class="ano form-control" /></div><div class="col-xs-2"> <input class="form-control"placeholder="Término" type="text" size="4" maxlength="4" name="ano1" value="" class="ano form-control" /></div> 
        </td></tr>
         
        <tr><td>
            <?php
            $daotin = new TipoinfraensinoDAO();
            $row = $daotin->Lista();
            ?> 
            <label for="tipo">Tipo da infraestrutura de ensino: </label></td>
            <td><select 
                name="tipo" class="sel1 form-control"> 
                <option value=0>Todas</option> 
                <?php foreach ($row as $r) { ?> 
                    <option value=<?php print $r['Codigo']; ?>> 
                        <?php print ($r['Nome']); ?> 
                    </option> 
                <?php } ?> 
            </select>
        </td>     
        </tr> 
        <tr><td> 
            <label for="unidade">Unidade: </label></td>
            <td><input class="form-control"type="text" size="60" id="search" name="unidade" value="" class="txt form-control" /><br/>
            <input type="button" name="botao" id="search_unidade" value="Buscar" class="btn btn-info" /> 
        </td></tr>
        </table> 
        <div id="selecionar"> 
        </div> 
    </fieldset> 
</form> 
<div class="msg" id="msg1"></div> 
<div id="resultado" class="resultado"></div> 

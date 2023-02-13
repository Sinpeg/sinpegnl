<?php 
//session_start(); 
if ($_SESSION['sessao'] == NULL) { 
 print 'Sessão não foi iniciada'; 
 exit(); 
} 
?> 
<?php 
$aplicacoes = $_SESSION["sessao"]->getAplicacoes(); 
if (!$aplicacoes[33]) { 
 exit(); 
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

<form class="form-horizontal" name="us" id="us" method="post" action="ajax/infra/consultainfra.php"> 
 <fieldset> 
 <legend>Buscar Infraestrutura</legend> 
 <table>
 <tr><td> 
 <label for="ano">Ano: </label></td><td><input class="form-control"type="text" class="form-control" size="4" maxlength="4" id="ano" name="ano" value="" class="ano"/> 
 </td></tr> 
 <tr><td> 
 <label for="situacao">Situação da Infraestrutura:</label></td><td> <select 
 class="sel1 form-control"  id="situacao" name="situacao"> 
 <option value="A">Ativo</option> 
 <option value="D">Desativado</option> 
 </select> 
  </td></tr> 
 <tr><td> 
 <label for="unidade">Unidade: </label></td><td> <input class="form-control"type="text" size="60" 
 id="selunidade" name="selunidade" value="" class="txt form-control" /> <br/>
 <input type="button" value="Buscar" class="btn btn-info" id="search_unidade" /> 
  </td></tr> 
 </table> 
 <div id="selecionar"></div> <br/>
 </fieldset> 
</form> 
<div class="msg" id="msg1"></div> 
<div id="resultado" class="resultado"></div>
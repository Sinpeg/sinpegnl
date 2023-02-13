<?php 

//session_start(); 
if ($_SESSION['sessao'] == NULL) { 
 print 'Sessão não foi iniciada'; 
 exit(); 
} 
?> 
<?php 
$aplicacoes = $_SESSION["sessao"]->getAplicacoes(); 
if (!$aplicacoes[32]) { 
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
 
<form class="form-horizontal" name="us" id="us" method="post" action="ajax/labor/consultalabor.php"> 
 <fieldset> 
 <legend>Relatório de Laboratório</legend> 

 <table >
 <tr><td>
 <label for="ano">Ano/Período: </label></td><td><div class="col-xs-2" style="margin-left:-13px;"><input class="form-control"type="text" size="7" maxlength="4" id="ano" placeholder="Início" name="ano" value="" class="ano col-xs-2 form-control" /> 
 </div> <div  class="col-xs-2"><input class="form-control"placeholder="Término" type="text" size="18" maxlength="4" name="ano1" value="" class="ano form-control" /></div></td> 
 </tr>
  
 <tr><td> 
 <label for="curso">Incluir curso:</label></td><td><input class="form-check-input"name="curso" type="checkbox" class="form-check-input" value="curso" /></td></tr>  
 
 <tr><td> <label for="situacao">Situação do laboratório:</label></td>
 <td>
 <select class="sel1 form-control" id="situacao" name="situacao" > 
	 <option value="A">Ativado</option> 
	 <option value="D">Desativado</option> 
 </select> 
 </td></tr> 
 <tr><td>
 <label for="unidade">Unidades: </label>  
 </td><td>
 <!--  <input class="form-control"type="text" size="60" class="form-control" id="search" name="unidade" value="" class="txt" />-->
<select  class="form-control" id="selunidade" name="selunidade" >
 <option value="" class="opt">Selecione uma Categoria</option>
 <option value="todas" class="opt">TODAS</option>
 <option value="institutos" class="opt">INSTITUTO</option>
 <option value="nucleos" class="opt">NÚCLEOS</option>
 <option value="campus" class="opt">CAMPUS</option> 
</select>  
 </td></tr>
 </table>
   <input type="button" name="botao" id="search_unidade" value="Buscar" class="btn btn-info" /> 
 </fieldset>   
</form> 
<div id="resultado" class="resultado"></div>
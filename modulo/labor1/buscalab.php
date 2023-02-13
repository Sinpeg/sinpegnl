<?php 
session_start(); 
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
 
<form class="form-horizontal" name="us" id="us" method="post" action="ajax/labor/consultalabor.php"> 
 <fieldset> 
 <legend>Buscar Laboratório</legend> 
 <div> 
 <label for="ano">Ano/Período: </label><input class="form-control"type="text" size="4" 
 maxlength="4" id="ano" name="ano" value="" class="ano" /> <span 
 class="intermediario">a</span> <input class="form-control"type="text" size="4" 
 maxlength="4" name="ano1" value="" class="ano" /> 
 </div> 
 <div> 
 <label for="curso">Incluir curso:</label><input class="form-control"name="curso" 
 type="checkbox" value="curso" /> 
 </div> 
 <div> 
 <label for="situacao">Situação do laboratório:</label> <select 
 class="sel1" id="situacao" name="situacao"> 
 <option value="A">Ativado</option> 
 <option value="D">Desativado</option> 
 </select> 
 </div> 
 <div> 
 <label for="unidade">Unidade: </label> <input class="form-control"type="text" size="60" 
 id="search" name="unidade" value="" class="txt" /> <input class="form-control"
 type="button" name="botao" id="search_unidade" value="Buscar" 
 class="btn" /> 
 </div> 
 <div id="selecionar"></div> 
 </fieldset> 
 <div class="msg" id="msg1"></div> 
</form> 
<div id="resultado" 
 class="resultado"></div>
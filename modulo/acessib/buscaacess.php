<?php
require_once 'dao/tpacessibilidadeDAO.php';
//session_start();
$aplicacoes = $_SESSION["sessao"]->getAplicacoes();
if (!$aplicacoes[35]) {
    header("Location:../../index.php");
    exit;
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

<?php
if (!isset($_SESSION["sessao"])) {
  header("Location:../../index.php");
}

$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
?> 

<div class="card card-info">
  <div card="card-header"><h3 class="card-title">Buscar Estruturas de Acessibilidade</h3></div>
  <form class="form-horizontal" name="us" id="us" method="post" method="post" action="ajax/acessib/consultaacess.php"> 
    <table>
      <tr>
        <td>Ano/Período: </td>
        <td>
          <div class="col-xs-2">
            <input class="form-control"type="text" size="4" maxlength="4" placeholder="Início" id="ano" name="ano" value="" class="ano form-control" /> 
          </div>
          <div class="col-xs-2"> 
            <input class="form-control"type="text" size="4" maxlength="4" name="ano1" value="" placeholder="Término" class="ano form-control" />
          </div> 
        </td>
      </tr> 
      <?php
      $daotea = new TpacessibilidadeDAO();
      $resultado = $daotea->Lista1();
      ?> 
      <tr>
        <td>Tipo da Estrutura: </td><td> <select class="sel1 form-control" id="tipo" name="tipo"> 
              <option value="todos">Todos</option> 
              <?php foreach ($resultado as $r) { ?> 
                  <option value="<?php print ($r['Codigo']); ?>"> 
                      <?php print ($r['Nome']); ?> 
                  </option> 
              <?php } ?> 
          </select> 
        </td>
      </tr> 
      <tr>
        <td>Unidade: </td>
        <td> 
          <input class="form-control"type="text" size="60" name="unidade" value="" id="search" class="form-control" /> <br/>
          <input type="button" name="botao" id="buscar" value="Buscar" class="btn btn-info" /> 
        </td>
      </tr>
    </table> 
    <div id="selecionar"></div> 
  </form> 
</div>
<div class="msg" id="msg1"></div> 
<div id="resultado" class="resultado"></div>
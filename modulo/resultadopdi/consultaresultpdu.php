<?php
/* models */
require_once MODULO_DIR . 'documentopdi/classe/Documento.php';
require_once MODULO_DIR . 'objetivopdi/classe/Mapa.php';
require_once MODULO_DIR . 'indicadorpdi/classe/Indicador.php';
require_once MODULO_DIR . 'metapdi/classe/Meta.php';
require_once MODULO_DIR . 'resultadopdi/classes/Resultado.php';
/* DAO */
require_once MODULO_DIR . 'documentopdi/dao/DocumentoDAO.php';
require_once MODULO_DIR . 'objetivopdi/dao/MapaDAO.php';
require_once MODULO_DIR . 'indicadorpdi/dao/IndicadorDAO.php';
require_once MODULO_DIR . 'metapdi/dao/MetaDAO.php';
require_once MODULO_DIR . 'resultadopdi/dao/ResultadoDAO.php';
/* fim */
$c=new Controlador();
$sessao=$_SESSION["sessao"];
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$codunidade1=$unidade->getCodunidade();

?>
<style>
#unid-list{float:left;list-style:none;margin-top:-3px;padding:0;width:520px;position: absolute;height: 50px;}
#unid-list li{padding: 5px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
#unid-list li:hover{background:#ece3d2;cursor: pointer;}
#cxunidade{padding-right: 5px;border: #a8d4b1 1px solid;border-radius:4px;width: 520px;height: 25px;}
label {margin-right: 10px;padding-bottom: 20px}

label.curto {padding-right: 120px;}

input class="form-control"{ display:inline-block;  }
select { display:inline-block;  padding-left: 5px;}
</style>
    

<fieldset>
    <legend>Lançar Resultados</legend>
    <form class="form-horizontal" id="pdi-resultado" name="pdi-resultado" method="POST" action="<?php echo AJAX_DIR . 'resultadopdi/buscaresult.php'; ?>">

    <?php if ($c->getProfile($sessao->getGrupo())) {//se grupo for 18 ?>  
        <label for="cxunidade" class="curto">Unidade </label> <input class="form-control" type="text" id="cxunidade"  name="cxunidade" placeholder="Unidade" autocomplete="off"/>
       	<div id="suggesstion-box"></div>

        <div id="plano"> <label for="pdi-result-bind">Plano de Desenvolvimento</label>
            <select class="custom-select" name="doc" id="pdi-result-bind" >
                <option value="0">Informe unidade a qual pertence o plano de desenvolvimento</option>
            </select>

        </div> 
        <div id="resposta-ajax">
             <label for="indic" class="curto">Indicador </label>
        <select class="custom-select" name="indicador" id="indic">
        <option value="0">Informe unidade a qual pertence o plano de desenvolvimento</option>
    
    </select>
        
        </div>
<?php } ?>
        
    </form>
    <div id="resposta-ajax-result"></div>
    <div id="l-resultado"></div>
</fieldset>
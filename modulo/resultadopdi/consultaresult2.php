<?php
/* models */
/*require_once MODULO_DIR . 'documentopdi/classe/Documento.php';
require_once MODULO_DIR . 'mapaestrategico/classe/Mapa.php';
require_once MODULO_DIR . 'indicadorpdi/classe/Indicador.php';
require_once MODULO_DIR . 'metapdi/classe/Meta.php';
require_once MODULO_DIR . 'resultadopdi/classes/Resultado.php';*/
/* DAO */
/*require_once MODULO_DIR . 'documentopdi/dao/DocumentoDAO.php';
require_once MODULO_DIR . 'mapaestrategico/dao/MapaDAO.php';
require_once MODULO_DIR . 'indicadorpdi/dao/IndicadorDAO.php';
require_once MODULO_DIR . 'metapdi/dao/MetaDAO.php';
require_once MODULO_DIR . 'resultadopdi/dao/ResultadoDAO.php';*/
/* fim */
$c=new Controlador();
$sessao=$_SESSION["sessao"];
$unidade = new Unidade();
$unidade->setCodunidade($sessao->getCodunidade());
$unidade->setNomeunidade($nomeunidade);
$codunidade1=$unidade->getCodunidade();
$daocal=new CalendarioDAO();
$rows=$daocal->verificaPrazoCalendarioDoDocumento($anobase);
foreach ($rows as $row) {
      $habilita=$row['habilita'];
}
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
  
  
  
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active"><a href="#" >Lançar Resultados</a></li>  
		</ul>
	</div>
</head>
  
    

<fieldset>
    <legend>Lançar Resultados</legend>
    <form class="form-horizontal" id="pdi-resultado" name="pdi-resultado" method="POST" action="<?php echo AJAX_DIR . 'resultadopdi/tabresultado.php'; ?>">

    <?php if ($c->getProfile($sessao->getGrupo())) {//se grupo for 18 ?>  
         <label for="cxunidade" class="curto">Unidade </label> 
        <input class="form-control" type="text" id="cxunidade"  name="cxunidade" placeholder="Unidade" autocomplete="off"/>
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
<?php } else {
         $dao1=new DocumentoDAO();
         
         
         $result = $dao1->buscaporRedundancia($codunidade1, $sessao->getAnobase());
         if(!empty($result)) { ?>
                 <div>
                <label for="doc">Documento</label>
                <select class="custom-select" name="doc" >                 
                <option value="0">Selecione PD...</option>    
                <?php foreach ($result as $row) { 
                        $coddoc=$row['codigo']; ?>
                        <option  selected value="<?php echo $row["codigo"] ?>"><?php echo $row["nome"]." - " .$row['anoinicial'].' a '.$row['anofinal']; ?></option>
                    
                
                <?php } //for ?>
                </select>
                      </div>
        <div>
                <label for="speriodo">Período</label> <select class="custom-select" name="speriodo" >
                <option  value="0">Lançamento de Resultados NÃO libera!</option>                 
                <option  <?php print $habilita=="Pacial"?"selected":"";?> value="1">Parcial</option>
                <option  <?php print $habilita=="Final"?"selected":"";?> value="2">Final</option>
               </select>
             </div>        
                 <input class="btn btn-info" type="submit"  value="Buscar" id="resposta-ajax-doc1" class="btn btn-info btn" />

               
          <br/><br/> Exportar Resultado do Painel Tático: <a href="relatorio/resultadosPainelTatico.php?unidade=<?php echo $codunidade1;?>&anoBase=<?php echo $anobase;?>"><img src="webroot/img/pdf.png"></a>&nbsp;&nbsp;&nbsp;<a href="relatorio/resultadosPainelTaticoExcel.php?unidade=<?php echo $codunidade1;?>&anoBase=<?php echo $anobase;?>"><img width="20px" height="20px" src="webroot/img/excel.png"></a>
          
        
  

        <div id="resultado" >
            
        </div>
        
       
                 
<?php }//if result
    } ?>
    
 <!--   <div id="resposta-ajax-result"></div>
    <div id="l-resultado"></div>-->
        </form>
</fieldset>
<script>
    /*  $('div#resultado').empty();
        $.ajax({url: "ajax/resultadopdi/tabresultado.php", type: 'POST', data:$('form[name=pdi-resultado]').serialize(), success: function(result){
            $("div#resultado").html(result);
        }});*/
        
    $('#resposta-ajax-doc1').click(function(event) {
                        event.preventDefault();
                        $("#resultado").html("");
                        $("#resultado").addClass("ajax-loader");
                        $.ajax({
                            url: $("form").attr("action"),
                            type: "POST",
                            data: $("form").serialize(),
                            success: function(data) {
                                $("#resultado").html(data);
                                $("#resultado").removeClass("ajax-loader");
                            }
                        });
                    });
        

</script>
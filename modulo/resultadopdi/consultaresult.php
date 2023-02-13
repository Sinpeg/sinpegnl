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
$anogestao=$sessao->getAnobase();
$daocal=new CalendarioDAO();
$rows=$daocal->verificaPrazoCalendarioDoDocumento($anobase);

foreach ($rows as $row) {
    $habilita=$row['habilita'];
}
//$grupo =  $sessao->getGrupo()[0];

$dao1=new DocumentoDAO();
?>
  
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active"><a href="#" ><?php echo $codunidade1==100000 ? "Consultar Resultados" : "Lançar Resultados";?></a></li>  
		</ul>
	</div>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"><?php echo $codunidade1==100000 ? "Consultar Resultados das Unidades" : "Lançar Resultados";?></h3>
    </div>
    <form class="form-horizontal" id="pdi-resultado" name="pdi-resultado" method="POST" action="<?php echo AJAX_DIR . 'resultadopdi/tabresultado.php'; ?>">

        <?php if ($c->getProfile($sessao->getGrupo())) {//se grupo for 18 ?>  
            <!--
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
            -->
        
            <table class="card-body">
                <tr>
                    <td class="coluna1">
                        <label for="doc">Documento</label>
                    </td>
                </tr>
                <tr>
                    <td class="coluna2">
                        <!-- -->
                        <select class="custom-select" name="doc" id="doc"  class="sel1 form-control">
                            <option value="0">Selecione o documento...</option>
                            <option value="-1">TODAS AS UNIDADES</option> 
                            <?php $docufpa = $dao1->buscadocumentoPrazo($anogestao);
                            foreach ($docufpa as $row){ ?>
                                <?php  $ano = $row['anoinicial']; ?>
                                <option value=<?php print $row["codigo"]; ?>><?php print $row['nome'] ?><?php print ' (' . $row['anoinicial'] . '-' . $row['anofinal'] . ')'; ?></option>
                            <?php } ?>
                        </select>
            	    </td>
                </tr>
                <tr>
                    <td class="coluna1">      
		                <label for="speriodo">Período</label> 
		            </td>
                    <td class="coluna2">        
		                <select class="custom-select" name="speriodo" class="form-control" >
		                <option  value="0">Lançamento de Resultados NÃO liberado!</option>                 
		                <option  <?php print $habilita=="Parcial"?"selected":"";?> value="1">Parcial</option>
		                <option  <?php print $habilita=="Final"?"selected":"";?> value="2">Final</option>
		               </select>		                
		            </td>
                </tr>        
                <tr>
					<td colspan="2" align="center">
						<br>
                        <input class="btn btn-info" type="submit"  value="Buscar" id="resposta-ajax-doc1" class="btn btn-info btn" />	 
                    </td>
                </tr>
		    </table>        
		    
            <br>
		    <div id="resultado"></div>      
        <?php }else{
            $result = $dao1->buscaporRedundancia($codunidade1, $sessao->getAnobase());
            if(!empty($result)) { ?>
                <table class="card-body">
                    <tr>
                        <td class="coluna1">
                            <label for="doc">Documento</label>
                        </td>
                    </tr>
                    <tr>
                        <td class="coluna2">
                            <select class="custom-select" name="doc" id="doc" >                 
                                <option value="0">Selecione PD...</option>    
                                <?php foreach ($result as $row) { 
                                    $coddoc=$row['codigo']; ?>
                                    <option  selected value="<?php echo $row["codigo"] ?>"><?php echo $row["nome"]." - " .$row['anoinicial'].' a '.$row['anofinal']; ?></option>
                                <?php } //for ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="coluna1">
                            <label for="speriodo">Período</label> 
                        </td>
                    </tr>
                    <tr>
                        <td class="coluna2">  
                            <select class="custom-select" name="speriodo" >
                                <option  value="0">Lançamento de Resultados NÃO liberado!</option>                 
                                <option  <?php print $habilita=="Parcial"?"selected":"";?> value="1">Parcial</option>
                                <option  <?php print $habilita=="Final"?"selected":"";?> value="2">Final</option>
                            </select>
                        </td>
                    </tr>  
                    <tr>
                        <td colspan="2" align="center">
                            <br> 
                            <input class="btn btn-info" type="submit"  id="gravar" value="Buscar" class="btn btn-info btn" />
                        </td>
                    </tr>     
                </table>
                
                <br>
                <div id="resultado"></div>
            <?php }//if result
        } ?>
        <!--   <div id="resposta-ajax-result"></div>
        <div id="l-resultado"></div>-->
    </form>
</div>

<script>
    /*  $('div#resultado').empty();
        $.ajax({url: "ajax/resultadopdi/tabresultado.php", type: 'POST', data:$('form[name=pdi-resultado]').serialize(), success: function(result){
            $("div#resultado").html(result);
        }});*/
    $('#gravar').click(function(event) {
        event.preventDefault();
        if($("#doc").val()!= 0){
            $("#resultado").html("");
            $("#resultado").addClass("ajax-loader");
            $.ajax({
                url: $("#pdi-resultado").attr("action"),
                type: "POST",
                data: $("form").serialize(),
                success: function(data) {
                    $("#resultado").html(data);
                    $("#resultado").removeClass("ajax-loader");
                }
            });
        }else{
            alert("Por Favor! Selecione o Documento.");
        }
    });
</script>
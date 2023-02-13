<?php
session_start();
$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade(); // código da unidade
$aplicacoes = $sessao->getAplicacoes(); // código das aplicações
$anobase = $sessao->getAnobase();       // ano base
$codestruturado = $sessao->getCodestruturado(); // código estruturado
?>
<?php
if (!$aplicacoes[38]) {
    print "Erro ao acessar esta aplicação";
    exit();
}

/*
$daounidade = new UnidadeDAO();
$rows = $daounidade->buscasubunidades00($codestruturado);
$unidade = array();
$cont = 0;
foreach ($rows as $row) {
	$unidade[$cont] = new Unidade();
	$unidade[$cont]->setCodunidade($row['CodUnidade']);
	$unidade[$cont]->setNomeunidade($row['NomeUnidade']);
	$cont++;
}

$daodoc = new DocumentoDAO();
$objdoc = array();
$cont1 = 0;
for ($i = 0; $i < $cont; $i++) {
	$daodoc = new DocumentoDAO();
	$rows = $daodoc->buscadocumentoporunidade($unidade[$i]->getCodunidade());
	foreach ($rows as $row) {
		$objdoc[$cont1] = new Documento();
		$objdoc[$cont1]->setCodigo($row['codigo']);
		$objdoc[$cont1]->setNome($row['nome']);
		$objdoc[$cont1]->setUnidade($unidade[$i]);
		$objdoc[$cont1]->setAnoInicial($row['anoinicial']);
		$objdoc[$cont1]->setAnoFinal($row['anofinal']);
		$cont1++;
	}
}
*/

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
    <legend>Vincular indicador</legend>
    <form class="form-horizontal" name="cadindicador" method="POST" action="" id="indicadores-cadastro">
        <div id="resultado"></div>
        
        <table>
        	<tbody>
        	 </tr>				
				
				
				<tr>
				<td><label for="cxunidade" class="curto">Unidade proprietária do indicador </label> 
     
				</td>
				<td>   <input class="form-control" type="text" id="cxunidade"  name="cxunidade" placeholder="Unidade" autocomplete="off"/>
       	<div id="suggesstion-box"></div></td>
				</tr>
        		<tr>
        			      
		</tbody>
  </table>
  <input class="form-control"type="hidden" name="ind" value="<?php echo addslashes( $_GET['ind']); ?>" />
  <input class="form-control"type="hidden" name="mapa" value="<?php echo addslashes($_GET['mapa']); ?>" />
  
  <input type="button" value="Vincular" name="vincindicador"  class="btn btn-info btn"/>	   
    
    <a href="<?php echo Utils::createLink('indicadorpdi', 'consultaindicadorproprio'); ?>" >
    <button  type="button" class="btn btn-info btn">Consultar Indicadores</button></a>  
  
  
  <input class="form-control"type="hidden" name="acao" value="A" />
		    
  </form>
     
</fieldset>
<script>
$("#cxunidade").keyup(function(){
	$.ajax({
	type: "POST",
	url: "ajax/resultadopdi/lerUnidade.php",
	data:'keyword='+$(this).val(),
  /*  beforeSend: function(){
		$("#autocomp").css("background","#FFF url(img/LoaderIcon.gif) no-repeat 165px");
	},*/
	success: function(data){
		$("#suggesstion-box").show();
		$("#suggesstion-box").html(data);
		$("#cxunidade").css("background","#FFF");
	}
	}) ;


$("#cxunidade").click(function(){
    $(this).val("");
});
});

$(function() {
    $('input[name=vincindicador]').click(function() {
            
    	
		$('div#resultado').empty();
        $.ajax({url: "ajax/indicadorpdi/vincularindicador.php", type: 'POST', data:$('form[name=cadindicador]').serialize(), success: function(data) {
                $('div#resultado').html(data);
            }});
    	
    	});   
});

</script>
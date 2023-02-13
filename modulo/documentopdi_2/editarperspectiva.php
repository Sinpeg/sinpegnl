<?php

$sessao = $_SESSION['sessao'];
$codUnidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();
?>

<?php

$daounidade = new UnidadeDAO();
$grupos=$sessao->getGrupo();

$interno = false;
foreach ($grupos as $grupo){
	if ($grupo == "18"){
		$interno = true;
	}
}

if(!$interno){
	$codunidade = ($sessao->getCodunidsel())?$sessao->getCodunidsel():$sessao->getCodUnidade();
	$arrayUnidade = $daounidade->unidadeporcodigo($codunidade)->fetch();
	$nomeUnidade=$arrayUnidade['NomeUnidade'];
}


if (!$aplicacoes[36]) {
    print "Erro ao acessar esta aplicação";
    exit();
}




?>
<?php
$daodocumento = new DocumentoDAO();
$daoperspectiva = new PerspectivaDAO();
$arrayperspectiva = $daoperspectiva->buscaperspectiva($_GET['codPerspectiva'])->fetch();


?>


<style>
#unid-list{float:left;list-style:none;margin-top:-3px;padding:0;width:520px;position: absolute;height: 50px;}
#unid-list li{padding: 5px;salvar background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
#unid-list li:hover{background:#ece3d2;cursor: pointer;}
#cxunidade{padding: 5px;border: #a8d4b1 1px solid;border-radius:4px;width: 520px;height: 25px;}
</style>


<script>

function direciona() {
    var erro=false;
          
//     var conceptName = $('#unidadeIni').find(":selected").text();
	if($("input[name='Unidade']").val() == ""){
        document.getElementById('msg').innerHTML = "Selecione uma Unidade!";
        erro= true;
	}else if ($("input[name='nome']").val() == "")
    {
        document.getElementById('msg').innerHTML = "Preencha o campo nome!";
        erro= true;
    }/*else if ($("input[name='finalidade']").val() == ""){
        document.getElementById('msg').innerHTML = "Informe a finalidade!";
        erro=true ;
    }else if ($("input[name='coordenador']").val() == "")
    {
        document.getElementById('msg').innerHTML = "Informe o nome do coordenador!";
        erro= true;
    }*/
    
    


    if ( !erro){
        document.getElementById('adicionar').action = "?modulo=documentopdi&acao=inserirperspectiva";
        document.getElementById('adicionar').submit();
    }
}

</script> 


<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active"><a href="<?php echo Utils::createLink('documentopdi', 'listaperspectiva'); ?>">Lista Perspectiva</a>  
		</ul>
	</div>
</head>



<form class="form-horizontal"  name="adicionar" id="adicionar" method="POST" >
    <fieldset>
    <div id="msg"></div>
    
        <legend>Editar Perspectiva</legend>
        <div id="resultado"></div>
	 		
	 		
	 	<div id="tabela">	
	        <table>
	        	<input class="form-control"type="hidden" name="codPerspectiva" value="<?php print $_GET['codPerspectiva']; ?>">
	        	
	           	<tr class="subscribe">
		        	<td><label>Nome:</label></td>
		            <td><input class="form-control"type="text" name="perspectiva" value="<?php print $arrayperspectiva["nome"]; ?>" style="width:60%"/></td>
		        </tr>
		       	
	       </table>
	       
	        <div class="subscribe">
	        	<input class="form-control"type="text" value="Atualizar" name="salvarperspectiva" class="btn btn-info"/>
            	<input class="form-control"type="hidden" name="acao" value="A" />	
        	</div>

	    </div>

    </fieldset>
    
	
</form>
<script>
$(function() {
    $('input[name=salvarperspectiva]').click(function() {
            
    	
		$('div#resultado').empty();
        $.ajax({url: "ajax/documentopdi/registraperspectiva.php", type: 'POST', data:$('form[name=adicionar]').serialize(), success: function(data) {
                $('div#resultado').html(data);
            }});
    	
    	});   
});
</script>



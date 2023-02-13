<?php
$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade();
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

?>


<style>
#unid-list{float:left;list-style:none;margin-top:-3px;padding:0;width:520px;position: absolute;height: 50px;}
#unid-list li{padding: 5px;salvar background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
#unid-list li:hover{background:#ece3d2;cursor: pointer;}
#cxunidade{padding: 5px;border: #a8d4b1 1px solid;border-radius:4px;width: 520px;height: 25px;}
</style>





<script>

function direciona(botao) {
    var erro=false;
          
//     var conceptName = $('#unidadeIni').find(":selected").text();
	if($("input[name='Unidade']").val() == ""){
        document.getElementById('msg').innerHTML = "Selecione uma Unidade!";
        erro= true;
	}else if ($("input[name='nome']").val() == "")
    {
        document.getElementById('msg').innerHTML = "Preencha o campo nome!";
        erro= true;
    }else if ($("input[name='finalidade']").val() == ""){
        document.getElementById('msg').innerHTML = "Informe a finalidade!";
        erro=true ;
    }else if ($("input[name='coordenador']").val() == "")
    {
        document.getElementById('msg').innerHTML = "Informe o nome do coordenador!";
        erro= true;
    }
    
    


    if (botao==1 && !erro){
        document.getElementById('adicionar').action = "?modulo=iniciativa&acao=gravaIniciativa";
        document.getElementById('adicionar').submit();
    }else if (botao==2 && !erro){
        document.getElementById('adicionar').action = "?modulo=iniciativa&acao=gravaIniciativa";
        document.getElementById('adicionar').submit();
    }
    
}

</script>

<div id="msg"></div>

<form class="form-horizontal" enctype="multipart/form-data" name="adicionar" id="adicionar" method="POST" >
    <fieldset>
        <legend>Registrar / Editar Iniciativa</legend>
        <div id="resultado"></div>
	 		
	 		
	 	<div id="tabela">	
	        <table>
	        
	        
	        	<?php if($interno){ ?>
			        <tr>
			        	<td><label for="cxunidade" class="curto">Unidade: </label></td>
					    <td>
	        				<input class="form-control" type="text" id="cxunidade"  name="unidade" placeholder="Unidade" autocomplete="off"/>
	        				<input class="form-control"type="hidden" id="hiddencx"  name="codUnidade" />
	       					<div id="suggesstion-box"></div>
						</td>
					</tr>
				<?php }else {?>
					<tr>
						<td><label>Unidade: </label></td>
						<td><?php print $nomeUnidade ?></td>
						<td><input class="form-control"type ="hidden" name ="codUnidade" value="<?php print $sessao->getCodUnidade();?>"></td>
					</tr>
				<?php }?>
				
				
		       	<tr>
		        	<td><label>Nome:</label></td>
		            <td><input class="form-control"type="text" name="nome"/></td>
		        </tr>
		             
		       	<tr>
		       		<td><label>Finalidade:</label></td>
		       		<td><input class="form-control"type="text" name="finalidade"/></td>
		       	</tr>
		       	
		       	<tr>
		       		<td><label>Coordenador:</label></td>
		       		<td><input class="form-control"type="text" name="coordenador"/></td>
		       	</tr>
		       	
	       </table>
	       
	        <div>
				<input class="form-control"type="hidden" name="funcao" value="gravar" />	
            	<input type="button" value="<?php if($edicao == 1){ print "Atualizar"; }else{ print "Cadastrar";}?>" onclick="direciona(1);" name="salvarr" class="btn btn-info"/>
        	</div>
	       
	       
	    </div>
	       



    </fieldset>
    
</form>

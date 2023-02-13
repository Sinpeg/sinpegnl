<?php
$sessao = $_SESSION["sessao"];
 
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[36]) {
    header("Location:index.php");
} else {
    $sessao = $_SESSION["sessao"];
    $nomeunidade = $sessao->getNomeunidade();
    $codunidade = $sessao->getCodunidade();  
    $anobase = $sessao->getAnobase();
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);   
}

?>
<fieldset>
    <legend> Cadastrar Perspectiva</legend>
    <form class="form-horizontal" name="gravar12" method="POST"  id="perspectiva-cadastro">
        <div id="resultado"></div>
	    <div class="msg" id="msg"></div>
	    <table>
	        <tr>
	        	<td>
					<label>Perspectiva: </label>
				</td>
				<td>
					<textarea name="perspectiva" rows="1" cols="100"></textarea> 
				</td>
			</tr>		
	    </table>
	    <input type="button" value="Gravar" name="salvarperspectiva" class="btn btn-info"/>	
	    <input class="form-control"type="hidden" name="acao" value="I" />
	    <a href="<?php echo Utils::createLink('documentopdi', 'listaperspectiva'); ?>" >
	    <button id="listainic" type="button" class="btn btn-info btn">Perspectivas</button></a> 
	</form>
</fieldset>
<script>
$(function() {
    $('input[name=salvarperspectiva]').click(function() {
            
    	
		$('div#resultado').empty();
        $.ajax({url: "ajax/documentopdi/registraperspectiva.php", type: 'POST', data:$('form[name=gravar12]').serialize(), success: function(data) {
                $('div#resultado').html(data);
            }});
    	
    	});   
});
</script>

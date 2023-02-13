<?php
require_once('dao/qprodsaudeDAO.php');
require_once('dao/prodsaudeDAO.php');

if (!isset($_SESSION["sessao"])) {
    header("location:../../index.php");
    exit();
} else {
    $sessao = $_SESSION["sessao"];
    $anobase = $sessao->getAnoBase();
    $aplicacoes = $_SESSION["sessao"]->getAplicacoes();

    if (!$aplicacoes[15]) {
        header("Location:index.php");
    }

    $qpsDAO = new QprodsaudeDAO();
    $qtde = array();
    for ($i = 1; $i <= 47; $i++) {
        $qtde[$i] = 0;
    }
    $total = 0;
    $cont = 0;
    $consulta = $qpsDAO->buscaQpsAno($anobase);

    foreach ($consulta as $linha) {
        $cont++;
        if ($linha["Tipo"] == $cont) {
            $qtde[$cont] = $linha["Quantidade"];
            $total += $qtde[$cont];
        }
    }

    $qpsDAO->fechar();
    if ($cont > 0) {
        Utils::redirect('prodsaude', 'consultapsaude');
//        header("location:consultapsaude.php");
    }
}

$daopsaude = new ProdsaudeDAO();
$querrysecao = $daopsaude->ListaSecao();
// foreach ($querrysecao as $nomesessao){
// 	$querypsaude = $daopsaude->ListaPorSecao($nomesessao['Secao']);
// 	foreach ($querypsaude as $psauderow){
// 		$psauderow;
// 	}
// }

//ob_end_flush();
?>


<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active"><a href="#" >Cadastro quantitativo de procedimentos</a></li>  
		</ul>
	</div>
</head>


<script>
    window.onload = exibe();
</script>
<form class="form-horizontal" name="iprodsaude" method="post" action = "<?php echo Utils::createLink('prodsaude', 'oppsaude'); ?>">
    <h3 class="card-title">Servi&ccedil;o de Patologia Tropical e Dermatologia</h3>
    <div class="msg" id="msg"></div>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <?php 
    	$cont2 = 0;
    	foreach ($querrysecao as $nomesessao){
    	$cont2++;
		$querypsaude = $daopsaude->ListaPorSecao($nomesessao['Secao']);?>
		
	  	<div class="panel panel-default">
		<div class="panel-heading" role="tab" id="headingThree">
	      <h4 class="panel-title">
	        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $cont2; ?>" aria-expanded="false" aria-controls="collapseThree">
	          <?php echo $nomesessao['Subunidade']; ?> - <?php echo $nomesessao['Secao']; ?>
	        </a>
	      </h4>
	    </div>
		<div id="collapse<?php echo $cont2; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
	      <div class="panel-body">	
			
	   		
	   		<div id="labmico">
	        <table>
				<tr>
	                <td>Especifica&ccedil;&atilde;o (Procedimentos)</td>
	                <td>Quantidade</td>
	            </tr>
				<?php foreach ($querypsaude as $psauderow){?>
				<tr>
	                <td><?php echo $psauderow['Procedimento']; ?></td>
	                <td align="center"><input class="form-control"type="text" name="<?php echo $psauderow['Codigo']; ?>" size="5" value=''
	                                          maxlength="5" onkeypress='return SomenteNumero(event)'
	                                          onchange='soma1(1);' /></td>
	            </tr>
				<?php }?>
			</table>
	        Total: <b id='totaltab1'></b> <input class="form-control"type="hidden" name="t1" /> <br />
	    	</div>
	   	 </div>
	   	</div>
	   </div>
		
	<?php }?>
  </div>  
    
    
    
    
    
    
    
    
    
   

    Total Geral: <b id='totalgeral'></b> <br /> <input class="form-control"type='submit'  value='Gravar' class="btn btn-info" />
</form>
<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('dao/qprodsaudeDAO.php');
require_once('dao/prodsaudeDAO.php');


if (!isset($_SESSION["sessao"])) {
    header("location:../../index.php");
//    exit();
}

$sessao = $_SESSION["sessao"];
//$nomeUnidade = $sessao->getNomeUnidade();
//$codUnidade = $sessao->getCodUnidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
$aplicacoes = $_SESSION["sessao"]->getAplicacoes();

if (!$aplicacoes[15]) {
    header("Location:index.php");
}
$qpsDAO = new qprodsaudeDAO();
$qtde = array();
for ($i = 1; $i <= 47; $i++) {
    $qtde[$i] = 0;
}
$cont = 0;
$consulta = $qpsDAO->buscaQpsAno($anobase);

foreach ($consulta as $linha) {
    $cont++;
    if ($linha["Tipo"] == $cont) {
        $qtde[$cont] = $linha["Quantidade"];
    }
}

$qpsDAO->fechar();
if ($cont == 0) {
    Utils::redirect('prodsaude', 'incluipsaude');
}

$daopsaude = new ProdsaudeDAO();
$querrysecao = $daopsaude->ListaSecao();


?>

<head>
<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active"><a href="<?php echo Utils::createLink('prodsaude', 'consultapsaude'); ?>">Consulta</a> / <a href="#" >Alterar</a></li>  
		</ul>
	</div>
</head>

<form class="form-horizontal" name="iprodsaude" method="post" action="<?php echo Utils::createLink('prodsaude', 'oppsaude'); ?>">

	


    <div class="msg" id="msg"></div>

    <h3 class="card-title">Servi&ccedil;o de Patologia Tropical e Dermatologia</h3>
  
  	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">  
     <?php $cont2 = 1; ?>
     <?php foreach ($querrysecao as $nomesessao){
     	$queryqpsaude = $qpsDAO->buscaQPorAnoSecao($sessao->getAnoBase(),$nomesessao['Secao']);
     	$cont2++;
     	?>
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
			                <td align="center">Quantidade</td>
			            </tr>
						<?php foreach ($queryqpsaude as $psauderow){?>
						<tr>
			                <td><?php echo $psauderow['Procedimento']; ?></td>
			                <td align="center"><input class="form-control"type="text" name="<?php echo $psauderow['Codigo']; ?>" size="5" value='<?php echo $psauderow['Quantidade'];?>'
			                                          maxlength="5" onkeypress='return SomenteNumero(event)'
			                                          onchange='soma1(1);' /></td>
			            </tr>
						<?php }?>
					</table>
			       
			    	</div>
	    		</div>
	    	</div>
		</div>
	<?php }?>
	</div>
     <br /> <input class="form-control"name="operacao" type="hidden" value="I" /> 
     <input class="btn btn-info" type="submit"  value="Gravar" class="btn btn-info"/>
</form>
<script>
    window.onload = exibe();
    soma1(1);
    soma1(2);
    soma1(3);
    soma1(4);
    soma1(5);
</script>
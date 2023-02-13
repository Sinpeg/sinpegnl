<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('dao/qprodsaudeDAO.php');
require_once('dao/prodsaudeDAO.php');

if (!isset($_SESSION["sessao"])) {
    header("location:../../index.php");
}
$sessao = $_SESSION["sessao"];
//$nomeUnidade = $sessao->getNomeUnidade();
//$codUnidade = $sessao->getCodUnidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
$aplicacoes = $_SESSION["sessao"]->getAplicacoes();

if (!$aplicacoes[15]) {
    header("Location:index.php");
//	exit();
}

$qpsDAO = new qprodsaudeDAO();

$resultado = $qpsDAO->buscaQpsAno($anobase)->fetchAll();

if(empty($resultado)){
	Utils::redirect('prodsaude', 'incluipsaude');
}

 $total = 0;
// $qtde = array();
// for ($i = 1; $i <= 47; $i++) {
//     $qtde[$i] = 0;
// }
$daopsaude = new ProdsaudeDAO();
$querypsaude = $daopsaude->ListaSecao();






?>


<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active"><a href="#" >Consulta Patologia Tropical e Dermatologia</a></li>  
		</ul>
	</div>
</head>

<form class="form-horizontal" name="iprodsaude" method="post">
    <h3 class="card-title">Servi&ccedil;o de Patologia Tropical e Dermatologia</h3>


<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<?php 
	$cont2 = 0;
	foreach ($querypsaude as $psaudee){
	$cont2++;
	$queryqpsaude = $qpsDAO->buscaQPorAnoSecao($sessao->getAnoBase(), $psaudee['Secao']); 
	?>
	
	
	<div class="panel panel-default">
		<div class="panel-heading" role="tab" id="headingThree">
			<h4 class="panel-title">
				<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $cont2; ?>" aria-expanded="false" aria-controls="collapseThree">
					<?php echo $psaudee['Subunidade']; ?> - <?php echo $psaudee['Secao']; ?>
				</a>
			</h4>
		</div>
		<div id="collapse<?php echo $cont2; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
	      	<div class="panel-body">	
	
		    	<table>
		        	<tr>
		            	<td>Especificação (Procedimentos)</td>
		            	<td>Quantidade</td>
		        	</tr>
			
				<?php foreach ($queryqpsaude as $qpsa){ ?>
		        	<tr>
		           		 <td><?php echo $qpsa['Procedimento']?></td>
		           	 	<td align="center"><?php print $qpsa['Quantidade']; $total+=$qpsa['Quantidade']; ?></td>
		       		 </tr>
				<?php }?>
				</table>
			</div>
		</div>		    
	</div>
	
<?php } ?>
</div>
    <!--  </div>-->
    Total Geral: <b id='totalgeral'><?php print $total; ?> </b> 
    </br>
    </br> 
    <input type="button" onclick='direciona(3);' value="Alterar" class="btn btn-info" />
        
</form>


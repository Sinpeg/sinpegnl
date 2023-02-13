<?php
$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
//$anobase = $sessao->getAnobase();
$aplicacoes = $_SESSION["sessao"]->getAplicacoes();
if (!$aplicacoes[14]) {
    header("Location:index.php");
} 
require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('../../includes/classes/unidade.php');
require_once('../../includes/dao/servicoDAO.php');
require_once('../../includes/classes/servico.php');
//require_once('dao/procedimentoDAO.php');
//require_once('classes/procedimento.php');
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);

$daos =  new ServicoDAO();
//$daop =  new ProcedimentoDAO();

$codsubunidade = $_POST["subunidade"];
if (is_numeric($codsubunidade) || $codsubunidade!=""){
	$unidade->criaSubunidade($codsubunidade, null, null);
	$rows1 = $daos->buscaservicos($codunidade,$codsubunidade);
	$passou=false;
	$display="<table width=800px><td width=200px>Servi&ccedil;o</td>";
	foreach ($rows1 as $row1){
		$passou=true;
		$unidade->adicionaItemServico($row1['Codigo'], $unidade, $unidade->getSubunidade(), $row1['Nome']);
	}
	    $cont=0;
		$display.="<td><select class="custom-select" name=servico id=servico onchange=ajaxBuscaSP1();>";
		foreach ($unidade->getServicos() as $s){
			$cont++;
			if ($cont==1){
				$categoria =$s->getCodigo();
			}
			$display .="<option  value=";
			$display .=$s->getCodigo().">";
			$display .= htmlentities($s->getNome());
		}
		$display.="</select>";
	$display.="</td></tr></table>";

	$daos->fechar();
	$daos =  new ServprocDAO();
	$display="<table width=800px><td width=200px>Procedimento</td><td>";

	require_once('dao/servprocDAO.php');
	$display.="<select class="custom-select" name=procedimento>";
	$rows = $daos->buscaservproced($categoria,$subunidade);
	foreach ($rows as $row){
		$display .="<option  value=";
		$display .=$row['CodProced'].">";
		$display .=htmlentities($row['Nome']);
	}
	$display.="</select></td></tr></table>";

	$daos->fechar();

	echo $display;

}
ob_end_flush();
?>
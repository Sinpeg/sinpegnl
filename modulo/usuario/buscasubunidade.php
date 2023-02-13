<?php
ob_start();

echo ini_get('display_errors');

if (!ini_get('display_errors')) {
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL & ~E_NOTICE);
}
?>
<?php
//set_include_path(';../../includes');
require_once('../../includes/classes/sessao.php');

session_start();
if (!isset($_SESSION["sessao"])){
	header("location:../../index.php");
	exit();
}
else {
	$sessao = $_SESSION["sessao"];
	$nomeunidade = $sessao->getNomeunidade();
	$codunidade = $sessao->getCodunidade();
	$responsavel = $sessao->getResponsavel();
	$anobase = $sessao->getAnobase();

	require_once('../../includes/dao/PDOConnectionFactory.php');
	require_once('dao/unidadeDAO.php');
	require_once('../../includes/classes/unidade.php');

	$cont = 1;
	$daocat= new UnidadeDAO();
	$parametro =addslashes($_POST["parametro"]);
	if (is_string($parametro)){
		$rows=$daocat->buscacodestruturado($parametro);
		$unidades=array();
		foreach ($rows as $row){
			$cont++;
			$unidades[$cont]=new Unidade();
			$unidades[$cont]->setCodunidade($row["CodUnidade"]);
			$unidades[$cont]->setNomeunidade($row["NomeUnidade"]);

		}
		$display="<select class="custom-select" name='subunidade>";
		foreach ($unidades as $u){
			$display .="<option  value=";
			$display .=$u->getCodunidade().">";
			$display .=$u->getNomeunidade()."</option>";
		}
		$display.="</select>";
		$daocat->fechar();
		echo $display;
	}
}
ob_end_flush();
?>

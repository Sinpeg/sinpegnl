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
	header("location:i ndex.php");
}
else {
	$sessao = $_SESSION["sessao"];
	$nomeunidade = $sessao->getNomeunidade();
	$codunidade = $sessao->getCodunidade();
	$responsavel = $sessao->getResponsavel();
	$anobase = $sessao->getAnobase();



	require_once('../../includes/dao/PDOConnectionFactory.php');

	// var_dump($usuario);
	require_once('dao/produtosDAO.php');
	require_once('classes/produtos.php');
	require_once('../../includes/classes/unidade.php');

	require_once('dao/prodfarmaciaDAO.php');
	require_once('classes/prodfarmacia.php');


	$daoproduto = new ProdutosDAO();

	$produto = new Produtos();
	$produto->setNome($_POST["npa"]);
	$daoproduto->insere($produto);
ob_end_flush();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.or/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
<title>Relatório de Gestão</title>
</head>
<body>

</body>
</html>

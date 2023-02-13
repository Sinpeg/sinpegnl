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

	$rows_prod=$daoproduto->Lista();

	$conttproduto=0;
	foreach ($rows_prod as $row){
		$produto = new Produtos();
		$produto->setNome($row['Nome']);

		// foreach ($tiposta as $tipota){
		// if ($tipota->getCodigo() == $tipo){
		/// $tacurso->setTipota($tipota);
		$conttproduto++;
	}

	//  $curso->adicionaItemTacursos($tacurso);

}
$daoproduto->fechar();
ob_end_flush();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.or/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
<title>Sistema de Registro de Atividades Anuais</title>
</head>
<body>
	<form class="form-horizontal" name="gravar" method="post" action="opproduto.php">


			 Incluir Produtos <br />
			 Nome do Produto : <input
				type="text" name="npa" />

		<p>
			<br /> <input class="btn btn-info" type="submit"  value="Gravar" />
		</p>
	</form>
</body>
</html>

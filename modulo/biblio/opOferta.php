<?php
//session_start();//aqui
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
$codunidade = $sessao->getCodUnidade();

if (!$aplicacoes[45]) {
	header("Location:index.php");
	exit();
}
/*require_once('dao/blofertaDAO.php');
require_once('classes/bloferta.php');
require_once('classes/bibliemec.php');
require_once('classes/biblicenso.php');
require_once('classes/localoferta.php');
*/
require_once('dao/blofertaDAO.php');
require_once('classes/bloferta.php');
require_once('classes/bibliemec.php');
require_once('classes/biblicenso.php');
require_once('classes/localoferta.php');


$cont=0;
$daoblo=new BlofertaDAO();
echo $_POST['idBibliemec']."psot";
$idbibliemec=$_POST['idBibliemec'];
//DELETA os locais de oferta de idbibliemec
$daoblo->deleta($idbibliemec);
$bli=new Bibliemec();
$bli->setIdBibliemec($idbibliemec);
$blo=array();
if ((!empty($_POST['loferta'])) && (is_numeric($idbibliemec))) {
	foreach($_POST['loferta'] as $i=>$_POST['loferta']) {
		$cont++;
		$lo=new Localoferta();
		$lo->setIdLocal($i);
		$bli->criaBloferta($lo);
		
		$cont++;
		$blo[$cont]=$bli->getBloferta();
	}
    $bli->setBlofertas($blo);
	$daoblo->insere($bli);	
	Flash::addFlash('Opera&ccedil;&atilde;o realizada com sucesso.');
	Utils::redirect('biblio', 'altoferta',array(idbiblicenso=>$_POST['idbiblicenso']));
	
}

?>

<?php
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[4]) {
    header("Location:index.php");
} else {
    require_once('classes/tipoprodintelectual.php');
    require_once ('dao/tipoprodintelectualDAO.php');
    $codigo = $_GET["codigo"];
    if (is_numeric($codigo)) {
        $dao = new TipoprodintelectualDAO();
        $p = new tipoprodintelectual();
        $p->setCodigo($codigo);
        $dao->deleta($p);
        $dao->fechar();
    }
//        exit;
    Utils::redirect('prodintelectual', 'consultaitempi');
//	$cadeia="location:consultaitempi.php";
//	header($cadeia);
//exit();
//    ob_end_flush();
}
?>




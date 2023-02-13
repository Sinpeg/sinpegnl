<?php
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[5]) {
    header("Location:index.php");
} else {
    
    $codigo = $_GET["codigo"];
    if (is_numeric($codigo)) {
        $dao = new TppremiosDAO();
        $p = new Tppremios();
        $p->setCodpremio($codigo);
        $dao->deleta($p);
        $dao->fechar();
    }
//        exit;
    Utils::redirect('premios', 'consultaitem');
//	$cadeia="location:consultaitempi.php";
//	header($cadeia);
//exit();
//    ob_end_flush();
}
?>




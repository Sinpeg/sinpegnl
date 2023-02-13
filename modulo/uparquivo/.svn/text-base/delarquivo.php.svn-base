<?php

session_start();
if (!isset($_SESSION["sessao"])) {
    header("location:index.php");
}
$sessao = $_SESSION["sessao"];
$codusuario = $sessao->getCodusuario();
$codigo = $_GET['codigo'];
$daoarq = new ArquivoDAO();
$rows = $daoarq->buscaporCodigo($codigo);
$permite = false;
foreach ($rows as $row) {
    if ($row['Codusuario'] == $codusuario) {
        $permite = true;
    }
}
if ($permite) {
    $dao = new ArquivoDAO();
    $dao->deleta($codigo);
    $dao->fechar();
} else {
//    echo "Ok";
//    exit(0);
    Error::addErro('O arquivo não existe ou você não possui permissão para executar esta operação!');
}
Utils::redirect('uparquivo', 'consultaarqs');
?>

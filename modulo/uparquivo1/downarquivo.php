<?php
ini_set('display','on');
if (!isset($_SESSION["sessao"])) {
    header("location:index.php");
}
$sessao = $_SESSION["sessao"];
$codigo = $_GET['codigo'];
if (is_numeric($codigo) && $codigo != "") {
    $dao = new ArquivoDAO();
    $row = $dao->buscaCodigo($codigo);
if (isset($row)){
                   // Print headers
                    header("Content-Type: ". $row['Tipo']);
                    header("Content-Length: ". $row['Tamanho']);
                    header("Content-Disposition: attachment; filename=". $row['Nome']);

                    // Print data
                    echo $row['Conteudo'];
                }
                else {
                    echo 'Error! No image exists with that ID.';
                }
                }
                ?>
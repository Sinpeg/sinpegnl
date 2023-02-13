<?php
ini_set('display','on');
if (!isset($_SESSION["sessao"])) {
    header("location:index.php");
    exit();
}
$sessao = $_SESSION["sessao"];
$codigo = $_GET['codigo'];
if (is_numeric($codigo) && $codigo != "") {
    $dao = new DocumentoDAO();
    $rows = $dao->buscaArquivoDoc($codigo);


    if ($rows->num_rows > 0) {
    	// output data of each row
      while ($row = $rows->fetch_assoc()) {
    		$nome= $row["nomearq"];
    		$tipo=$row["tipoarq"];
    		$conteudo=$row["anexo"];
    	}
      
        
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Type: filesize($conteudo)');
        header('Content-Type:$tipo');
        header("Content-Disposition: attachment; filename=".$nome);

        print($conteudo);

    } else {
    	echo "0 results";
    }

   
   
    
   


//echo $row['Tipo'];
/*header("Content-Type: ". $row['Tipo']); // informa o tipo do arquivo ao navegador
header("Content-Length: ".$row['Tamanho']); // informa o tamanho do arquivo ao navegador
header("Content-Disposition: attachment; filename=".$row['Nome']); // informa ao navegador que Ã© tipo anexo e faz abrir a janela de download, tambem informa o nome do arquivo
echo($row['Conteudo']); // lÃª o arquivo
	  */

}
?>
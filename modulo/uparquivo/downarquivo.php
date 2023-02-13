<?php
ini_set('display','on');
//echo "entrou";
if (!isset($_SESSION["sessao"])) {
    header("location:index.php");
}
//$sessao = $_SESSION["sessao"];
$codigo = $_GET['codigo'];
if (is_numeric($codigo) && $codigo != "") {
    $dao = new ArquivoDAO();
    $rows = $dao->buscaCodigo($codigo);


    if ($rows->num_rows > 0) {
    	// output data of each row
    	while($row = $rows->fetch_assoc()) {
    		$nome= $row["Nome"];
    		$tipo=$row["Tipo"];
    		$conteudo=$row["Conteudo"];
    		$size=$row["Tamanho"];
    	}
    } else {
    	echo "0 results";
    }

    header('Content-Type: application/xml');
    header('Content-Type: text/xml; charset=utf-8');
    header('Content-Type: filesize('.$conteudo.')');
    header('Content-Type: filesize('.$size.')');
    header('Content-Type: filecontent('.$conteudo.')');
    //header('Content-Type: '.$tipo);
    //header('Content-Disposition: attachment; filename="'.$nome.'"');    
    
    
    header("Content-length: $size");
    header("Content-type: $tipo");
    header("Content-Disposition: attachment; filename=$nome");
    ob_clean();//importante para gerar o arquivo 
    flush();  //importante para gerar o arquivo
    
    print($conteudo);
    
    



//echo $row['Tipo'];
/*header("Content-Type: ". $row['Tipo']); // informa o tipo do arquivo ao navegador
header("Content-Length: ".$row['Tamanho']); // informa o tamanho do arquivo ao navegador
header("Content-Disposition: attachment; filename=".$row['Nome']); // informa ao navegador que Ã© tipo anexo e faz abrir a janela de download, tambem informa o nome do arquivo
echo($row['Conteudo']); // lÃª o arquivo
	  ob_flush;*/

}
?>
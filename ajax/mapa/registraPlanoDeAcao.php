<?php 
//Exibir Erros
//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

require '../../dao/PDOConnectionFactory.php';
require '../../modulo/mapaestrategico/dao/MapaDAO.php';

//Recebe variáveis
$comentario = $_POST["comentario_plano"];
$codarquivo = $_POST["codarquivo"];
$codunidade = $_POST["codunidade"];
$anobase = $_POST["anobase"];
$situacao = "";

//Verifica situação do plano se hover outro enviado anteriormente


//Arquivo
$erro = NULL;
$extensoesOk = ",.rar,.zip,";
$arquivo = $_FILES['upload']['tmp_name'];
$tamanho = $_FILES['upload']['size'];
$tipoarq = "application/octet-stream";//$_FILES['userfile']['type']; //
$nome = $_FILES['upload']['name'];
$erroarq = $_FILES['upload']['error'];
$tmpName = $_FILES['upload']['tmp_name'];
$passou=0;$string="";
$extensao = "," . strtolower(substr($nome,(strlen($nome) - 4))) . ",";
//Validar envio de arquivo
if($_FILES['upload']['error'] != 0){
	$erro = 'Um erro ocorreu enquanto o arquivo estava sendo importado. '. 'Código de Erro: '. intval($_FILES['userfile']['error']);
}else if  (strpos($extensoesOk, $extensao)<0){
	$erro = "Envie arquivos compactados (extens&atilde;o .rar ou .zip).";
}else if ($tamanho < 0 || $tamanho['size'] > 10485760  ) {
	$erro='Tamanho do arquivo deve ser maior que 0 e menor do que 2 Mbytes!';
}else if (is_executable($_FILES['upload']['tmp_name'])){
	$erro='O arquivo não pode ser um executável';
}
$extensao=substr($nome, strlen($nome)-4,strlen($nome)+1);

$index = rand();
$nomeAnexo = $codunidade."_".$index."_".$codarquivo."Plano_de_Acao".$extensao;

//Mover arquivo
$uploaddir = '../../../public/plano_de_acao/';
$uploadfile = $uploaddir . $nomeAnexo;

if (move_uploaded_file($_FILES['upload']['tmp_name'], $uploadfile)) {
	//Arquivo válido e enviado com sucesso	
	//Gravar dados do novo plano de ação
	$mapadao = new MapaDAO();
	$mapadao->inserePlanoDeAcao($codarquivo, $comentario, $nomeAnexo, $situacao,$codunidade,$anobase);
	
}else{
	$string.= "Possível problema de upload de arquivo!\n";
	$string.= 'Aqui está mais informações de debug:';	
}

if (!is_null($erro)) {
	echo $erro;
}else if ($string!="") {
	echo $string;	
}else {
	echo 1;
}
?>
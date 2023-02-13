<?php 
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/unidade.php';
require_once '../../classes/usuario.php';
require_once '../../modulo/mapaestrategico/classes/Solicitacao.php';
require_once '../../modulo/mapaestrategico/classes/Objetivo.php';
require_once '../../modulo/mapaestrategico/classes/SolicitacaoInsercaoIndicador.php';
require_once '../../modulo/mapaestrategico/dao/SolicitacaoInsercaoIndicadorDAO.php';
require_once '../../modulo/indicadorpdi/classe/Mapaindicador.php';
require_once '../../modulo/indicadorpdi/classe/SolicitacaoEditIndicador.php';
require_once '../../modulo/indicadorpdi/classe/SolicitacaoVersaoIndicador.php';
require_once '../../modulo/indicadorpdi/classe/Indicador.php';
require_once '../../modulo/indicadorpdi/dao/SolicitacaoEditIndicadorDAO.php';
require_once '../../modulo/indicadorpdi/dao/SolicitacaoVersaoIndicadorDAO.php';
require '../../modulo/documentopdi/classe/Documento.php';
require '../../modulo/documentopdi/dao/DocumentoDAO.php';
//require_once '../../vendors/phpmailer/class.phpmailer.php';
require '../../classes/EnviarMail.php';

//print_r($_POST);
//Receber dados
$ano = $_POST['anobase'];
$codUnidade = $_POST['codUnidade']; //Código da unidade
$codmapa = $_POST['codmapa']; //Código mapa 
$justificativa = strip_tags(addslashes($_POST['justificativaInclusao']));
$codObjetivo = $_POST['codobjet'];
$codDocumento = $_POST['coddoc'];
$situacao = 'A';

//Arquivo
$erro = NULL;
$extensoesOk = ",.rar,.zip,";
$arquivo = $_FILES['arquivoInclusao']['tmp_name'];
$tamanho = $_FILES['arquivoInclusao']['size'];
$tipoarq = "application/octet-stream";//$_FILES['userfile']['type']; //
$nome = $_FILES['arquivoInclusao']['name'];
$erroarq = $_FILES['arquivoInclusao']['error'];
$tmpName = $_FILES['arquivoInclusao']['tmp_name'];
$passou=0;
$extensao = "," . strtolower(substr($nome,(strlen($nome) - 4))) . ",";

//Validar envio de arquivo
if($_FILES['arquivoInclusao']['error'] != 0){
	$erro = 'Um erro ocorreu enquanto o arquivo estava sendo importado. '. 'Código de Erro: '. intval($_FILES['userfile']['error']);
}else if  (strpos($extensoesOk, $extensao)<0){
	$erro = "Envie arquivos compactados (extens&atilde;o .rar ou .zip).";
}else if ($tamanho < 0 || $tamanho['size'] > 10485760  ) {
	$erro='Tamanho do arquivo deve ser maior que 0 e menor do que 2 Mbytes!';
}else if (is_executable($_FILES['arquivoInclusao']['tmp_name'])){
	$erro='O arquivo não pode ser um executável';
}
	/*--*/
		if (strlen($nome)>9){//Pega apenas os 5 primeiros caracteres do nome se contiver mais do que 5 caracteres
			$extensao=substr($nome, strlen($nome)-4,strlen($nome)+1);
			$splitname=str_split(trim($nome),5);
			$nome=$splitname[0].$extensao;
		}
        /*--*/

$nomeAnexo = $ano."_".$codUnidade."_0_6_".date('d-m-Y-H-i')."_".$nome;

//Buscar nome da unidade
$daodoc=new DocumentoDAO();
$rowsdoc=$daodoc->buscadocumento($codDocumento);
foreach ($rowsdoc as $r){	
	$uni = new Unidade();
	$uni->setCodunidade($codUnidade);
	$uni->setNomeunidade($r['NomeUnidade']);	
}

$obj = new Objetivo();
$obj->setCodigo($codObjetivo);

$ind = new Indicador();
$ind->setCodigo($_POST['subsInd']); //Indicador a ser incluido

$user = new Usuario();
$user->setCodusuario(228);

//$mi->criaSolicitacaoeditindicador(null, $uni, $ind, $justificativa, $nomeAnexo, $situacao, null, 228, '6',$ano);	 
$sol = new SolicitacaoInsereIndicador(); 
$sol->setUnidade($uni);
$sol->setObjetivo($obj);
$sol->setJustificativa($justificativa);
$sol->setAnexo($nomeAnexo);
$sol->setSituacao($situacao);
$sol->setAnogestao($ano);
$sol->setUsuarioanalista($user);
$sol->setMapa($codmapa);
$sol->setIndicador($ind);

$daoSolInsert = new SolicitacaoInsercaoIndicadorDAO(); 
$daoSolInsert->insere($sol);     

//Enviar e-mail 
$email=new EnviarMail();
$mensagem= 'A unidade '.$uni->getNomeunidade().' solicitou inclusão de um novo Indicador em seu PDU.';
$email->criaEnviarMail("SInPeG - Solicitação de Inclusão no PDU" ,$mensagem, "cmadm@ufpa.br");
$enviado=$email->enviarEmail();
if ($enviado==0){
	print ('Toda a operação foi realizada, menos o envio do e-mail para o analista. Entre em contato com a PROPLAN.');
	die;
}

$string="";
//Mover arquivo
$uploaddir = '../../../public/solicitacoes/';
$uploadfile = $uploaddir . $nomeAnexo;

if (move_uploaded_file($_FILES['arquivoInclusao']['tmp_name'], $uploadfile)) {	
	//$string.= "Arquivo válido e enviado com sucesso.\n";
}else{
	$string.= "Possível problema de upload de arquivo!\n";
	$string.= 'Aqui está mais informações de debug:';
	//print_r($_FILES);
}

if (!is_null($erro)) {
	echo $erro;
}else if ($string!="") {
	echo $string;
	;
}else {
	echo 1;
}
//echo $string;  
?>
<?php 
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/unidade.php';
require_once '../../dao/unidadeDAO.php';
require_once '../../classes/usuario.php';
require_once '../../modulo/mapaestrategico/classes/Solicitacao.php';
require_once '../../modulo/indicadorpdi/classe/Mapaindicador.php';
require_once '../../modulo/indicadorpdi/classe/SolicitacaoEditIndicador.php';
require_once '../../modulo/indicadorpdi/classe/SolicitacaoVersaoIndicador.php';
require_once '../../modulo/indicadorpdi/classe/Indicador.php';
require_once '../../modulo/indicadorpdi/dao/SolicitacaoEditIndicadorDAO.php';
require_once '../../modulo/indicadorpdi/dao/SolicitacaoVersaoIndicadorDAO.php';
//require_once '../../vendors/phpmailer/class.phpmailer.php';
require '../../classes/EnviarMail.php';

//print_r($_POST);
//Receber dados
$codInd = $_POST['codInd'];
$ano = $_POST['anobase'];
$codUnidade = $_POST['codUnidade']; //Código da unidade
//$codmapa = $_POST['codmapa']; //Código mapa 
$tipo = $_POST['tipo']; //Tipo de solicitação
$codMapaInd = $_POST['codMapaInd']; // Código do mapa indicador
$justificativa = strip_tags(addslashes($_POST['justificativa']));
$situacao = 'A';

//receber códigos de metas e novas metas
$aux_codMetas = $_POST['arrayCodMetas'];
$arrayCodMetas= explode(",",$aux_codMetas[0]);
$aux_novasMetas = $_POST['arrayNovasMetas'];
$arrayNovasMetas = explode(",",$aux_novasMetas[0]);

//Arquivo
$erro = NULL;
$extensoesOk = ",.rar,.zip,";
$arquivo = $_FILES['arquivo']['tmp_name'];
$tamanho = $_FILES['arquivo']['size'];
$tipoarq = "application/octet-stream";//$_FILES['userfile']['type']; //
$nome = $_FILES['arquivo']['name'];
$erroarq = $_FILES['arquivo']['error'];
$tmpName = $_FILES['arquivo']['tmp_name'];
$passou=0;
$extensao = "," . strtolower(substr($nome,(strlen($nome) - 4))) . ",";

//Validar envio de arquivo
if($_FILES['arquivo']['error'] != 0){
	$erro = 'Um erro ocorreu enquanto o arquivo estava sendo importado. '. 'Código de Erro: '. intval($_FILES['userfile']['error']);
}else if  (strpos($extensoesOk, $extensao)<0){
	$erro = "Envie arquivos compactados (extens&atilde;o .rar ou .zip).";
}else if ($tamanho < 0 || $tamanho['size'] > 10485760  ) {
	$erro='Tamanho do arquivo deve ser maior que 0 e menor do que 2 Mbytes!';
}else if (is_executable($_FILES['arquivo']['tmp_name'])){
	$erro='O arquivo não pode ser um executável';
}

/*--*/
if (strlen($nome)>9){//Pega apenas os 5 primeiros caracteres do nome se contiver mais do que 5 caracteres
	$extensao=substr($nome, strlen($nome)-4,strlen($nome)+1);
	$splitname=str_split(trim($nome),5);
	$nome=$splitname[0].$extensao;
}
/*--*/

$nomeAnexo = $ano."_".$codUnidade."_".$codInd."_".$tipo."_".date('d-m-Y-H-i')."_".$nome;


$mi = new Mapaindicador();
$mi->setCodigo($codMapaInd);

$unidadeDAO = new UnidadeDAO();
$rowUniDAO = $unidadeDAO->buscaidunidadeRel($codUnidade)->fetch();

$uni = new Unidade();
$uni->setCodunidade($codUnidade);
$uni->setNomeunidade($rowUniDAO['NomeUnidade']);

$user = new Usuario();
$user->setCodusuario(228);

$ind = new Indicador();

//set valores 
if ($tipo==1) {//substituir indicador	
	
	$ind->setCodigo($_POST['subsInd']);
			
	$mi->criaSolicitacaoeditindicador(null, $uni, $ind, $justificativa, $nomeAnexo, $situacao, null, $user, $tipo,$ano);	 

     $daoSolEdit = new SolicitacaoEditIndicadorDAO(); 
     $daoSolEdit->insere($mi->getsolicitacaoeditindicador());

     //Enviar e-mail
     $email=new EnviarMail();
     $mensagem= 'A unidade '.$uni->getNomeunidade().' solicitou a Substituição de um Indicador em seu PDU.';
     $email->criaEnviarMail("SInPeG - Solicitação de Substituição de Indicador no PDU" ,$mensagem, "cmadm@ufpa.br");
     $enviado=$email->enviarEmail();
     if ($enviado==0){
     	print ('Toda a operação foi realizada, menos o envio do e-mail para o analista. Entre em contato com a PROPLAN.');
     	die;
     }
          
}else if($tipo==2){//editar indicador
	
	$ind->setCodigo($codInd);

	$mi->criaSolicitacaoversaoindicador($mi, $tipo,null, $uni, $ind, $_POST['nomeIndEdit'], $_POST['formulaIndEdit'], $_POST['interpretacaoIndEdit'], $justificativa, $nomeAnexo, $situacao,null, null,$user,$ano); 

	$daoSolVers = new  SolicitacaoVersaoIndicadorDAO();
	$idSolicitacao = $daoSolVers->insere($mi->getsolicitacaoversaoindicador());
	
	//Cadastrar novas metas da solcitação
	for ($i=0; $i < count($arrayCodMetas); $i++) { 
		$daoSolVers->insereMetasSolicitacoes($idSolicitacao,$arrayNovasMetas[$i],$arrayCodMetas[$i]);
	}
	
	//Enviar e-mail
	$email=new EnviarMail();
	$mensagem= 'A unidade '.$uni->getNomeunidade().' solicitou a Edição de um Indicador em seu PDU.';
	$email->criaEnviarMail("SInPeG - Solicitação de Edição de Indicador no PDU" ,$mensagem, "cmadm@ufpa.br");
	//$enviado=$email->enviarEmail();
	//if ($enviado==0){
	//	print ('Toda a operação foi realizada, menos o envio do e-mail para o analista. Entre em contato com a PROPLAN.');
	//	die;
	//}
	
}else if ($tipo==7){ //Excluir indicador
	$ind->setCodigo($codInd);
	
	$mi->criaSolicitacaoversaoindicador($mi, $tipo,null, $uni, $ind, null, null, null, $justificativa, $nomeAnexo, $situacao,null, null,$user,$ano);
	
	$daoSolVers = new  SolicitacaoVersaoIndicadorDAO();
	$daoSolVers->insere($mi->getsolicitacaoversaoindicador());
	
	//Enviar e-mail
	$email=new EnviarMail();
	$mensagem= 'A unidade '.$uni->getNomeunidade().' solicitou a Exclusão de um Indicador em seu PDU.';
	$email->criaEnviarMail("SInPeG - Solicitação de Exclusão de Indicador no PDU" ,$mensagem, "cmadm@ufpa.br");
	$enviado=$email->enviarEmail();
	if ($enviado==0){
		print ('Toda a operação foi realizada, menos o envio do e-mail para o analista. Entre em contato com a PROPLAN.');
		die;
	}
}

$string="";
//Mover arquivo
$uploaddir = '../../../public/solicitacoes/';
$uploadfile = $uploaddir . $nomeAnexo;

if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)) {	
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
	//print_r($aux_codMetas);
}
//echo $string;  
?>
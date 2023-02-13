<?php
/*ini_set( 'display_errors', true );
error_reporting( E_ALL );
if (is_dir("../../../public/solicitacoes/")){
    var_dump( is_writable("../../../public/solicitacoes/")); //informe oque retornar desse dump
}else {echo "Não É dir";}
die;


// Faz o upload do anexo para seu respectivo caminho
 move_uploaded_file($anexo["tmp_name"], $caminho_anexo);die;*/

require '../../classes/unidade.php';
require '../../classes/usuario.php';
require '../../classes/EnviarMail.php';
require '../../dao/PDOConnectionFactory.php';
require '../../classes/sessao.php';
require '../../modulo/metapdi/classe/Meta.php';
require '../../modulo/metapdi/dao/MetaDAO.php';
require '../../modulo/documentopdi/classe/Documento.php';
require '../../modulo/documentopdi/dao/DocumentoDAO.php';
require '../../modulo/mapaestrategico/classes/Solicitacao.php';
require '../../modulo/metapdi/classe/SolicitacaoRepactuacao.php';
require '../../modulo/metapdi/dao/SolicitacaoRepactuacaoDAO.php';
require '../../modulo/indicadorpdi/dao/IndicadorDAO.php';
require '../../modulo/indicadorpdi/classe/Indicador.php';
require_once '../../vendors/phpmailer/class.phpmailer.php';


session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
$anobase=$sessao->getAnobase();

$codmeta=$_POST['codmeta'];
$codDocumento=$_POST['coddoc'];
$justificativa = strip_tags(addslashes($_POST['justificativa']));

//echo "ddd ".$codDocumento;die;
 
 //Arquivo
$erro = NULL;
$extensoesOk = ",.rar,.zip,";
$arquivo = $_FILES['arquivo']['tmp_name'];
$tamanho = $_FILES['arquivo']['size'];
$tipoarq = "application/octet-stream";//$_FILES['userfile']['type']; //
$anexo = $_FILES['arquivo']['name'];
$erroarq = $_FILES['arquivo']['error'];
$tmpName = $_FILES['arquivo']['tmp_name'];
$passou=0;
$extensao = "," . strtolower(substr($anexo,(strlen($anexo) - 4))) . ",";


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

$docobj=new Documento();
$docobj->setCodigo($codDocumento);

$daodoc=new DocumentoDAO();
$rowsdoc=$daodoc->buscadocumento($codDocumento);
foreach ($rowsdoc as $r){
	$unidade=new Unidade();
	$unidade->setCodunidade($r['CodUnidade']);
	$unidade->setNomeunidade($r['NomeUnidade']);
}
/*--*/
		$nome=$anexo;
		if (strlen($nome)>9){//Pega apenas os 5 primeiros caracteres do nome se contiver mais do que 5 caracteres
			$extensao=substr($nome, strlen($nome)-4,strlen($nome)+1);
			$splitname=str_split(trim($nome),5);
			$nome=$splitname[0].$extensao;
		}
        /*--*/

$nomeAnexo = $anobase."_".$unidade->getCodunidade()."_".$codmeta."_".date('d-m-Y-H-i')."_".$nome;

$analista=new Usuario();
$analista->setCodusuario(228);
$daosol=new SolicitacaoRepactuacaoDAO();
$rowssol=$daosol->buscaSolicitacaoRepactuacao($anobase,$codmeta,4);
$metaobj=new Meta();
$metaobj->setCodigo($codmeta);
if ($rowssol->rowcount()==0){


    $metaobj->criaSolicitacaoRepactuacao(NULL, $unidade, $justificativa,str_replace(',','.', $_POST['novameta']), $nomeAnexo, 'A',
     NULL, NULL, $analista,$anobase);
    
    
	$solrep=$metaobj->getSolicitacaorepactuacao();
	//---- upload de arquivo -------
	$uploaddir = '../../../public/solicitacoes/';
	$uploadfile = $uploaddir . $nomeAnexo;
	
	if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)) {
			
	     $string= "Arquivo válido e enviado com sucesso.\n";
	} else {
			     $string= "Possível problema de upload de arquivo!\n";
			     $string.= 'Aqui está mais informações de debug:';
			     print $string;
			     print_r($_FILES);die;
	}
	
	//-----------------------------
	
	
	
		
	$codnovasol=$daosol->insere($solrep);
	
	//Envia o e-mail 
	$email=new EnviarMail();
	$mensagem= 'A unidade '.$unidade->getNomeunidade().' solicitou repactuação de meta em seu PDU.';
	$email->criaEnviarMail("SInPeG - Solicitação de repactuação da meta no PDU" ,$mensagem, "cmadm@ufpa.br");
	$enviado=$email->enviarEmail();
	if ($enviado==0){
		print ('Toda a operação foi realizada, menos o envio do e-mail para o analista. Entre em contato com a PROPLAN.');
		die;
	}
	

	
	
}
?>



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
require '../../modulo/mapaestrategico/classes/Mapa.php';
require '../../modulo/mapaestrategico/dao/MapaDAO.php';
require '../../modulo/documentopdi/classe/Documento.php';
require '../../modulo/documentopdi/dao/DocumentoDAO.php';
require '../../modulo/mapaestrategico/classes/Objetivo.php';
require '../../modulo/mapaestrategico/dao/ObjetivoDAO.php';
require '../../modulo/mapaestrategico/classes/Solicitacao.php';
require '../../modulo/mapaestrategico/classes/SolicitacaoInsercaoObjetivo.php';
require '../../modulo/mapaestrategico/classes/SolicitItensIndicadoresDeObjetivo.php';
require '../../modulo/mapaestrategico/dao/SolicitItensIndicadoresDeObjetivoDAO.php';
require '../../modulo/mapaestrategico/dao/SolicitacaoInsercaoObjetivoDAO.php';
require '../../modulo/indicadorpdi/dao/IndicadorDAO.php';
require '../../modulo/indicadorpdi/classe/Indicador.php';
require_once '../../vendors/phpmailer/class.phpmailer.php';


session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
if (!isset($_SESSION["sessao"])) {
    echo "Sua sessão expirou, faça login novamente!";
    exit();
}

	 


$codDocumento=$_POST['coddoc'];
$codObjetivo=$_POST['codObjetivo'];

$justificativa = strip_tags(addslashes($_POST['justificativa']));

//indicadores selecionados
$cont=0;
$indicadores=array();
 foreach ($_POST['indsel'] as $item){
   $indicadores[$cont]=$item;
   $cont++;
 }
 //metricas dos indicadores
 $metricas=array();
 $cont=0;
 $errometrica=0;
 foreach ($_POST['metrica'] as $item){
   $metricas[$cont]=$item;
   if ($item==0){
   	$errometrica=1;
   }
   $cont++;
 } 
 
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
if($_FILES['arquivo']['error'] != 0){?>
		<div> Um erro ocorreu enquanto o arquivo estava sendo importado. Código de Erro:<?php print(intval($_FILES['userfile']['error']));?> </div>	
<?php }else if  (strpos($extensoesOk, $extensao)<0){?>
			<div> Envie arquivos compactados (extens&atilde;o .rar ou .zip). </div>	
<?php }else if ($tamanho < 0 || $tamanho['size'] > 10485760  ) {?>
	<div>Tamanho do arquivo deve ser maior que 0 e menor do que 2 Mbytes! </div>	
<?php }else if (is_executable($_FILES['arquivo']['tmp_name'])){?>
	<div>O arquivo não pode ser um executável</div>	
<?php }else {

$anobase=$sessao->getAnobase();
$docobj=new Documento();
$docobj->setCodigo($codDocumento);
$objetivo=new Objetivo();
$objetivo->setCodigo($codObjetivo);
$daodoc=new DocumentoDAO();
$rowsdoc=$daodoc->buscadocumento($codDocumento);
foreach ($rowsdoc as $r){
	$unidade=new Unidade();
	$unidade->setCodunidade($r['CodUnidade']);
	$unidade->setNomeunidade($r['NomeUnidade']);
	$docobj->setAnofinal($r['anofinal']);
	$docobj->setAnoinicial($r['anoinicial']);
}

	/*--*/
		$nome=$anexo;
		if (strlen($nome)>9){//Pega apenas os 5 primeiros caracteres do nome se contiver mais do que 5 caracteres
			$extensao=substr($nome, strlen($nome)-4,strlen($nome)+1);
			$splitname=str_split(trim($nome),5);
			$nome=$splitname[0].$extensao;
		}
        /*--*/
$nomeAnexo = $anobase."_".$unidade->getCodunidade()."_".$codObjetivo."_".date('d-m-Y-H-i')."_".$nome;




$analista=new Usuario();
$analista->setCodusuario(228);
$daosol=new SolicitacaoInsercaoObjetivoDAO();
$rowssol=$daosol->buscaSolicitacaoInsObjetivoUnidAno($anobase, $objetivo->getCodigo(), 3)->fetchall();
if (count($rowssol) == 0){

    $docobj->criaSolicitacaoInsercaoObjetivo(null, $unidade, $objetivo, $justificativa, $nomeAnexo, 'A', NULL, NULL, $analista,$anobase);
    
    
	$solinsobj=$docobj->getSolicitacaoinsertobjetivo();
	
	
	//-----------------------------
	

	
	$vmeta1=array();//1o ano
	$vmeta2=array();//2o ano
	$vmeta3=array();//3o ano
	$vmeta4=array();//4o ano
	for ($ano=$anobase;$ano<=$docobj->getAnofinal();$ano++){
        $cont=0;
	    $diferenca = ($docobj->getAnofinal()-$ano)+1;
	    $teste='meta'.$ano;//pega dinamicamente o nome das variaveis post de meta
	    foreach ($_POST[$teste] as $c){
	      switch ($diferenca){
	      	case 1:
	              $vmeta4[$cont]=str_replace(',','.',$c);
	              break;
	        case 2:
	              $vmeta3[$cont]=str_replace(',','.',$c);
	              break;
	        case 3:
	              $vmeta2[$cont]=str_replace(',','.',$c);
	              break;
	        case 4:
	              $vmeta1[$cont]=str_replace(',','.',$c);
	              break;
	    }//switch
	    $cont++;	   
	   }
	   	
   }
	
	
	
	//---- upload de arquivo -------
	$uploaddir = '../../../public/solicitacoes/';
	$uploadfile = $uploaddir.$nomeAnexo;
	
	if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)) {
			
	     $string= "Arquivo válido e enviado com sucesso.\n";
	} else {
			     $string= "Possível problema de upload de arquivo!\n";
			     $string.= 'Aqui está mais informações de debug:';
			     print $string;
			     print_r($_FILES);die;
	}
	
 
   
   
    for ($i=0;$i<count($indicadores);$i++){
		$ind=new Indicador();
		$ind->setCodigo($indicadores[$i]);	
		$solinsobj->adicionaItemSolicitaIndicadores(null, $ind,count($vmeta1)==0?NULL:$vmeta1[$i],count($vmeta2)==0?NULL:$vmeta2[$i],
		count($vmeta3)==0?NULL:$vmeta3[$i],count($vmeta4)==0?NULL:$vmeta4[$i],$metricas[$i], $i);
	}
		

		
	$codnovasol=$daosol->insere($solinsobj);
	$solinsobj->setCodigo($codnovasol);
	$daoinds=new SolicitItensIndicadoresDeObjetivoDAO();
	$daoinds->inseretodos($solinsobj->getArrayitensindicadores());
// Envia o e-mail para o SISRAA
	$email=new EnviarMail();
	$mensagem= 'A unidade '.$unidade->getNomeunidade().' solicitou inclusão de objetivo em seu PDU.';
	$email->criaEnviarMail("SiNPeG - Solicitação de inclusão de objetivo no PDU" ,$mensagem, "cmadm@ufpa.br");
	$enviado=$email->enviarEmail();
	if ($enviado==0){
		print ('Toda a operação foi realizada, menos o envio do e-mail para o analista. Entre em contato com a PROPLAN.');
		die;
	}
	

	
	
}
}//else
?>



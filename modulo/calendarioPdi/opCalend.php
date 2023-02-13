<?php
/*ob_start();

echo ini_get('display_errors');

if (!ini_get('display_errors')) {
	ini_set('display_errors', 1);
	ini_set('error_reporting', E_ALL & ~E_NOTICE);
}*/
?>
<?php
/*require_once('classes/Calendario.php');
require_once ('dao/CalendarioDAO.php');
require_once ('modulo/documentopdi/dao/DocumentoDAO.php');
require_once ('modulo/metapdi/dao/MetaDAO.php');*/

// session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
/*if (!$aplicacoes[50]) {
    header("Location:index.php");
}
$codcalendario = $_GET["codcalend"];
if (isset($_GET["op"])){
$op =$_GET["op"];
}else{
$op =$_POST["op"];
}*/
$daocal= new CalendarioDAO();
$daouni= new UnidadeDAO();
$daodoc= new DocumentoDAO();

$op =$_POST["op"];


$codDocumento = $_POST["codDocumento"];
$anogestao = $_POST["anogestao"];


//Parcial
$datainianalise = isset($_REQUEST['calendario1']) ? $_REQUEST['calendario1'] : null;
$dia = substr($datainianalise,0,2);
$mes = substr($datainianalise,3,2);
$ano = substr($datainianalise,6,4);
$datainianalise = $ano."-".$mes."-".$dia;
$datafimanalise = isset($_REQUEST['calendario2']) ? $_REQUEST['calendario2'] : null;
$dia = substr($datafimanalise,0,2);
$mes = substr($datafimanalise,3,2);
$ano = substr($datafimanalise,6,4);
$datafimanalise = $ano."-".$mes."-".$dia;

//Final
$datainianalisefinal = isset($_REQUEST['calendario3']) ? $_REQUEST['calendario3'] : null;
$dia = substr($datainianalisefinal,0,2);
$mes = substr($datainianalisefinal,3,2);
$ano = substr($datainianalisefinal,6,4);
$datainianalisefinal = $ano."-".$mes."-".$dia;
$datafimanalisefinal = isset($_REQUEST['calendario4']) ? $_REQUEST['calendario4'] : null;
$dia = substr($datafimanalisefinal,0,2);
$mes = substr($datafimanalisefinal,3,2);
$ano = substr($datafimanalisefinal,6,4);
$datafimanalisefinal = $ano."-".$mes."-".$dia;


//RAA
$datainiRAA = isset($_REQUEST['calendario5']) ? $_REQUEST['calendario5'] : null;
$dia = substr($datainiRAA,0,2);
$mes = substr($datainiRAA,3,2);
$ano = substr($datainiRAA,6,4);
$datainiRAA = $ano."-".$mes."-".$dia;
$datafimRAA = isset($_REQUEST['calendario6']) ? $_REQUEST['calendario6'] : null;
$dia = substr($datafimRAA,0,2);
$mes = substr($datafimRAA,3,2);
$ano = substr($datafimRAA,6,4);
$datafimRAA = $ano."-".$mes."-".$dia;

$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
$anobase = $sessao->getAnobase();

if ($codDocumento>0  &&
$anogestao>0){
    //Verifica se ano de gestão está no prazo do plano
    //busca unidade do documento
	$rows=$daodoc->buscaunidadedocumento($codDocumento);
	foreach ($rows as $row){
       $unidadesel = new Unidade();
	   $unidadesel->setCodunidade($row['CodUnidade']);
	   $unidadesel->setNomeunidade($row['NomeUnidade']);		
	}
    //busca se o calendario já existe
    $rows=$daocal->buscaCalendarioporUniDocAno($unidadesel->getCodunidade(),$anogestao,$codDocumento);
	foreach ($rows as $row){  
      		$op='A';
            $codcalendario=$row['codCalendario'];
	}
     $unidadesel->criaDocumento($codDocumento,null,null,null,null,null,null,null,null);
     if ($op=='A'){//altera calendario
     	 
         $unidadesel->getDocumento()->criaCalendario($codcalendario,$unidadesel,$datainianalise,$datafimanalise,$datainianalisefinal,
         $datafimanalisefinal,$datainiRAA,$datafimRAA,$anogestao);     
         $daocal->altera($unidadesel->getDocumento()->getCalendario());  
     }else{//insere calendario
         $unidadesel->getDocumento()->criaCalendario('',$unidadesel,$datainianalise,$datafimanalise,$datainianalisefinal,
         $datafimanalisefinal,$datainiRAA,$datafimRAA,$anogestao);
         $daocal->insere($unidadesel);
     }
	Flash::addFlash("Operação realizada com sucesso!");
	Utils::redirect('calendarioPdi', 'finsCalend');

}else 
   {
	Flash::addFlash("Erro!");

   	Error::addErro('Erro encontrado durante o cadastro do calendário');
   	Utils::redirect('calendarioPdi', 'listaCalendario');
   }




//$daorh->fechar();
//header($cadeia);
//exit();
//ob_end_flush();
?>
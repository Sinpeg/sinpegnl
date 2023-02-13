<?php


require_once '../../classes/sessao.php';

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../dao/unidadeDAO.php';
require_once '../../classes/unidade.php';

require '../../modulo/calendarioPdi/dao/CalendarioDAO.php';
require '../../modulo/calendarioPdi/classes/Calendario.php';
require '../../modulo/documentopdi/dao/DocumentoDAO.php';
require '../../modulo/documentopdi/classe/Documento.php';

session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
$codusuario=$sessao->getCodusuario();

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

$op ="I";


$codDocumento = $_POST["codDocumento"];
$anogestao = $_POST["anogestao"];


//Parcial
$datainianalise=prepararData($_REQUEST['calendario1']);
$datafimanalise = prepararData($_REQUEST['calendario2']);
//Final
$datainianalisefinal = prepararData($_REQUEST['calendario3']);    
$datafimanalisefinal =  prepararData($_REQUEST['calendario4']);   
//RAA
$datainiRAA =  prepararData($_REQUEST['calendario5']);
$datafimRAA =prepararData($_REQUEST['calendario6']) ;

//PDU
$datainiPDU = prepararData($_REQUEST['calendario7']) ;
$datafimPDU = prepararData($_REQUEST['calendario8']) ;

//PT
$datainiPT =  prepararData($_REQUEST['calendario9']) ;
$datafimPT =  prepararData($_REQUEST['calendario10']) ;

//Altera PDU
$datainialtPDU = prepararData($_REQUEST['calendario11']) ;
$datafimaltPDU = prepararData($_REQUEST['calendario12']) ;


/*$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
$anobase = $sessao->getAnobase();*/

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
     	 
         $unidadesel->getDocumento()->criaCalendario($codcalendario,$unidadesel,
          $datainianalise,$datafimanalise,
          $datainianalisefinal,$datafimanalisefinal,
             $datainiRAA,$datafimRAA,      
             $datainiPDU ,       $datafimPDU ,   
             $datainiPT ,         $datafimPT ,   
             $datainialtPDU,         $datafimaltPDU,
             $anogestao,        $codusuario);   
         $daocal->altera($unidadesel->getDocumento()->getCalendario());  
     }else{//insere calendario
         $unidadesel->getDocumento()->criaCalendario(null,$unidadesel,
         $datainianalise,$datafimanalise,
         $datainianalisefinal,         $datafimanalisefinal,
         $datainiRAA,$datafimRAA,
         $datainiPDU ,         $datafimPDU ,
         $datainiPT ,         $datafimPT ,
         $datainialtPDU,         $datafimaltPDU,
         $anogestao,$codusuario);
         
         $daocal->insere($unidadesel);
     }
	

}



//$daorh->fechar();
//header($cadeia);
//exit();
//ob_end_flush();

function prepararData($data){
    if (!empty($data) ){
        $dia = substr($data,0,2);
        $mes = substr($data,3,2);
        $ano = substr($data,6,4);
        $data = $ano."-".$mes."-".$dia;
    }else{
        $data =null;
    }
    return $data;
}

?>
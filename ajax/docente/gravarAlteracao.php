<?php

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/sessao.php';

session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
$anobase=$sessao->getAnobase();

$erro="";

$p=new Pessoa();


$rowsd31=$daodoc->buscaDocente31($idpessoa,$codtempo);
foreach ($rowsd31 as $r){
   
  if (  $_FILES['arquivo']['error'] == 0  || $_FILES['arquivo']['name']=="") {
    
      include 'preparaDadosPessoais.php';
      
      include 'preparaDadosIes.php';
      
      
  }
  echo $erro;
   
}//for

















if ($_POST['sitdocente']==1){
    
}

// curso32
/*$contentrados=0;
foreach($_POST['idcursocenso'] as $item){
     $contentrados++;
}
$cont=0;
$itemnovo=[];
foreach ($d32 as $a){
    $itemantigo=0;
    foreach($_POST['idcursocenso'] as $item){
   
        if ($item==$a['idcursoinep']){
            $itemantigo++;
        }
       
    }
    if ($itemantigo>0){
        $itemnovo[$cont++]=$_POST['idcursocenso'];
    }
    
 }
 */

?>
<?php
//session_start();
//trecho de código para exibir erro
/*ob_start();
echo ini_get('display_errors');

if (!ini_get('display_errors')) {
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL & ~E_NOTICE);
}*/
//--------------------

$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();

if (!$aplicacoes[45]) {
	header("Location:index.php");
	exit();
}


require_once('classes/bibliemec.php');
require_once('classes/biblicenso.php');
require_once('fachBCenso.php');
require_once('classes/cVetForm.php');
require_once('FachOferta.php');


/*require_once('classes\bibliemec.php');
require_once('classes\biblicenso.php');
require_once('fachBCenso.php');
require_once('classes\cVetForm.php');
require_once('fachOferta.php');*/


$fachada2=new FachBCenso();
$fachada3=new FachOferta(); 


$nass=trim($_POST['nass']);
$empd=trim($_POST['empd']);
$empb=trim($_POST['empb']);
$freq=trim($_POST['freq']);
$consp=trim($_POST['consp']);
$consl=trim($_POST['consl']);
$pcap=trim($_POST['pcap']);
$iai=trim($_POST['iai']);
$iaeletr=trim($_POST['iaeletr']);
$anobase=$sessao->getAnobase();



if ((is_numeric($nass)) && (is_numeric($empd)) && (is_numeric($empb)) &&
	(is_numeric($freq)) && (is_numeric($consp)) && (is_numeric($consl)) &&
	(is_numeric($pcap)) && (is_numeric($iai)) && (is_numeric($iaeletr))){
	$c = new cVetForm();
	$aspectos=$c->inicializa(6);
	
	if(!empty($_POST['aab']))//serivcos da biblioteca 
	   $aspectos=$c->obtemCheckbox(6,$_POST['aab'] );	
	
	$laccont=$c->inicializa(3);
	if(!empty($_POST['laccont']))
		$laccont=$c->obtemCheckbox(3,$_POST['laccont'] );
	
	
	$lactecn=$c->inicializa(3);
	if(!empty($_POST['lactecn']))
		$lactecn=$c->obtemCheckbox(3,$_POST['lactecn'] );
	
	$adic=$c->inicializa(4);
	if (!empty($_POST['adic']))
		$adic=$c->obtemCheckbox(4,$_POST['adic'] );
    
	if (empty($_POST['loferta'])) {
		Flash::addFlash('A sele&ccedil;&atilde;o do local de oferta &eacute; obrigat&oacute;ria!');
		Utils::redirect('biblio', 'altbiblicenso');
	}
    else if ((!is_null($_POST['idBibliemec'])) && ($_POST['idBibliemec']!="") && (!empty($_POST['loferta']))){
	      $be=new Bibliemec();
	      $be->setIdBibliemec($_POST['idBibliemec']);
          if ((is_null($_POST['idbiblicenso'])) || ($_POST['idbiblicenso']=="")){      
                $be->criaBiblicenso1( $nass,$empd,$empb, $freq, $consp, $consl,
        		$aspectos[1],$aspectos[2],$aspectos[3],$_POST['pcap'],$aspectos[4],$aspectos[5],$iaeletr,$iai,$aspectos[6],$laccont[1],
                        $laccont[2],$laccont[3],$lactecn[1],$lactecn[3],$lactecn[2],$adic[1],$adic[2],$adic[3],$adic[4],$anobase);
                
                 $fachada2->insere($be);
                 
                 $ok=$fachada3->trataOferta($_POST['idBibliemec'],$_POST['loferta']);
                 
                 if ($ok){
                    Flash::addFlash('Opera&ccedil;&atilde;o realizada com sucesso!');
                    Utils::redirect('biblio', 'altbiblicenso');
                 }else{
                 	Flash::addFlash('Ocorreu um problema com a inclus&atilde;o do local de oferta! Tente novamente...');
                 	Utils::redirect('biblio', 'altbiblicenso');
                 }
          }else {
      	        $be->criaBiblicenso2( $_POST['idbiblicenso'],$nass,$empd,$empb, $freq, $consp, $consl,
        		$aspectos[1],$aspectos[2],$aspectos[3],$pcap,$aspectos[4],$aspectos[5],$iaeletr,$iai,$aspectos[6],$laccont[1],
                        $laccont[2],$laccont[3],$lactecn[1],$lactecn[3],$lactecn[2],$adic[1],$adic[2],$adic[3],$adic[4]);
                 $fachada2->altera($be);
                 $ok= $fachada3->trataOferta($_POST['idBibliemec'],$_POST['loferta']);
                 
                 if ($ok){
                    Flash::addFlash('Opera&ccedil;&atilde;o realizada com sucesso!');
                    Utils::redirect('biblio', 'altbiblicenso');
                 }else{
                 	Flash::addFlash('Ocorreu um problema com a altera&ccedil;&atilde;o do local de oferta! Tente novamente...');
                 	Utils::redirect('biblio', 'altbiblicenso');
                 }
          }//if 3
    
      
      
    }//if 2
   
}//if 1



?>

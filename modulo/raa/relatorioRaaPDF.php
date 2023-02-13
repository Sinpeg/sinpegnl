<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

set_time_limit(0);
ini_set('memory_limit', '-1');

//require_once '../../vendors/PHPWord/bootstrap.php';

include 'funcoesraa.php';

require_once '../../classes/sessao.php';
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../vendors/dompdf/autoload.inc.php';
require_once 'classes/topico.php';
require_once 'classes/texto.php';
require_once 'classes/modelo.php';
require_once '../../classes/unidade.php';
require_once '../../dao/unidadeDAO.php';
require_once 'dao/topicoDAO.php';
require_once 'dao/textoDAO.php';
require_once 'dao/modeloDAO.php';
require_once '../documentopdi/dao/DocumentoDAO.php';

session_start();
$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
$anobase = $sessao->getAnobase();

$lista = array();
$cont = 0;

$mdao= new ModeloDAO();

$dao = new TextoDAO();   
$rowsl = $dao->buscaTopicosPreenchidos_Pendentes($anobase, $codunidade);
$topico=array();    
$topico=incluirTopicoNoTexto($rowsl,$topico,$cont,$codunidade,$anobase);
$rowsl = $dao->buscaSubTopicosPreenchidos_Pendentes($anobase, $codunidade);
$topico=incluirTopicoNoTexto($rowsl,$topico,$cont,$codunidade,$anobase);
$aux = 0;
$nivel=0;
$cont2 = 0;
//Gerar capa
$html='<br><br><p style="text-align: center; "><img src="data:image/png;base64,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" data-filename="ufpa.png" style="width: 87px; height: 102.225px;">
		<br></p><p class="MsoNormal" style="text-align:center" align="center"><span style="font-size:12.0pt;line-height:115%;font-family:" arial","sans-serif""="">UNIVERSIDADE
FEDERAL DO PARÁ</span></p><p class="MsoNormal" style="text-align:center" align="center"><b><span style="font-size:12.0pt;line-height:115%;font-family:" arial","sans-serif""="">'.$nomeunidade.'</span></b></p><p class="MsoNormal" style="text-align:center" align="center"><b><span style="font-size:12.0pt;line-height:115%;font-family:" arial","sans-serif""=""><o:p><br></o:p></span></b></p><p class="MsoNormal" style="text-align:center" align="center"><span style="font-size:12.0pt;line-height:115%;font-family:" arial","sans-serif""=""><o:p>&nbsp;</o:p></span></p><p class="MsoNormal" style="text-align:center" align="center"><span style="font-size:12.0pt;line-height:115%;font-family:" arial","sans-serif""=""><o:p>&nbsp;</o:p></span></p><br><p class="MsoNormal" style="text-align:center" align="center"><b><span style="font-size:18.0pt;line-height:115%;font-family:" arial","sans-serif""="">RELATÓRIO
ANUAL DE ATIVIDADES&nbsp;</span></b></p><p class="MsoNormal" style="text-align:center" align="center"></p><p class="MsoNormal" style="text-align:center" align="center"><b><span arial","sans-serif""="" style="font-size: 18pt; line-height: 27.6px;">EXERCÍCIO -&nbsp;</span></b><font face="Arial, sans-serif"><span style="font-size: 24px;"><b>'.$anobase.'</b></span></font></p><p class="MsoNormal" style="text-align:center" align="center"><span style="font-size:12.0pt;line-height:115%;font-family:" arial","sans-serif""=""><o:p>&nbsp; <br></o:p></span></p><p class="MsoNormal" style="text-align:center" align="center"><span style="font-size:12.0pt;line-height:115%;font-family:" arial","sans-serif""=""><o:p>&nbsp;</o:p></span></p><p class="MsoNormal" style="text-align:center" align="center"><span style="font-size:12.0pt;line-height:115%;font-family:" arial","sans-serif""=""><o:p>&nbsp;</o:p></span></p><p class="MsoNormal" style="text-align:center" align="center"><span style="font-size:12.0pt;line-height:115%;font-family:" arial","sans-serif""=""><o:p>&nbsp;</o:p></span></p><p class="MsoNormal" style="text-align:center" align="center"></p>
		<br><br><br><br><p class="MsoNormal" style="text-align:center" align="center"><span style="font-size:12.0pt;line-height:115%;font-family:" arial","sans-serif""="">'.$anobase.'<o:p></o:p></span></p>';

if (count($topico)>0)  {
	  $anterior=NULL; 
	  foreach ($topico as $t){

	  		if(($t->getCodigo()!=3 || ($t->getCodigo()==3 && $anobase<2019))  && $t->getCodigo()!=2){
		  	 $trows = $dao->buscaTexto($t->getCodigo(), $anobase, $codunidade);
		     $codtexto='';
		  	 $passou=0;
		   	
		  	 if( $trows->rowCount()>0 ){
		  	 	//$html .= '<br clear="all" style="mso-special-character:line-break;page-break-before:always">';
		  	 	$html .= '<div style="display: block; page-break-before: always;"></div>';
		  	 	//$html .= '<h3 >'.$t->getNivel().$t->getNome().'</h3>';
		  	 	$html .= '<h3 >';
		  	 	if($t->getNivel() != NULL){
		  	 		$nivel++;
		  	 		$html .= $nivel.". ".$t->getNome();
		  	 	}else{
		  	 		$html .= "".$t->getNome();
		  	 	}	
		  	 	$html .= '</h3>';
		  	 }
		  	 $aux =1;
			 foreach ($trows as $r){			 	
			  	if ($t->getCodigo()==3) {
			  		$html .= '<div style="display: block; page-break-before: always;"></div>';
			  		$html .= $r['texto'];
			  	}else{
			  		//Carla -- Incluir tabelas dos tópicos 
			  		if ($t->getCodigo()==145 ){
			  			include 'inclusoesraa.php';
			  		} 
			  		
			  		//------------------------
			  		
			  		
			  		$html .= $r['texto'];
			  		
					$rows137 = $dao->buscaTexto(137, $anobase, $codunidade);//"Menssagem do dirigente da Unidade"
			  		if($t->getCodigo()==137 || (($t->getCodigo()==121 && $rows137->rowCount()==0))){//Quando o tópico for "Menssagem do Dirigente da Unidade"(criado em 2019)
			  			//Exibir Sumário
			  			$html .= '<div style="display: block; page-break-before: always;"></div>';
			  			//Buscar sumário
			  			$rowsl2 = $dao->buscaTopicosPreenchidos_Pendentes($anobase, $codunidade);
			  			$topico2=array();
			  			$topico2=incluirTopicoNoTexto($rowsl2,$topico2,$cont2,$codunidade,$anobase);
			  			$rowsl2 = $dao->buscaSubTopicosPreenchidos_Pendentes($anobase, $codunidade);
			  			$topico=incluirTopicoNoTexto($rowsl2,$topico2,$cont,$codunidade,$anobase);
			  			$aux=0;$nivel3=0;
			  				
			  			$html .= "<h3>Sumário</h3><br/>Relação dos Dirigentes da Administração Superior<br/>";
			  				
			  			if (count($topico)>0)  {
			  				$anterior=NULL;
			  				foreach ($topico as $tt){
			  					$trows = $dao->buscaTexto($tt->getCodigo(), $anobase, $codunidade);
			  					$codtexto='';
			  					$passou=0;
			  						
			  					if($trows->rowCount()>0){
			  			
			  						if($tt->getNivel() != NULL){
			  							$nivel3++;
			  							$html .= $nivel3.". ".$tt->getNome()."<br/>";
			  						}else{
			  							$html .= "".$tt->getNome()."<br/>";
			  						}
			  					}			  					
			  					if ($tt->getSubtopicos()!=NULL ){
			  						 $html .= subtopicoSS($tt->getSubtopicos(),$codunidade,$anobase,$nivel3);
			  					}else if($passou==0 AND $tt->getCodigo()!=2){
			  						//$html .= "Não se aplica!";			  			
			  					}
			  				}
			  			}			  		
			  			
			  		}//Fim da exibição do sumário
			  	}			 	
			  	$codtexto=$r['codigo'];
			  	$passou=1;		  	
			  	
			 }			  
		
			 if ($t->getSubtopicos()!=NULL ){
			 	   $html .= subtopicoArquivo($t->getSubtopicos(),$codunidade,$anobase,$nivel);
			 }else if($passou==0 AND $t->getCodigo()!=2){
			 	//$html .= "Não se aplica!";
			 }	  	  	
	     }elseif($t->getCodigo()==3){  //Quando o tópico for 3(lista de dirigentes)
	     	$html .= '<div style="display: block; page-break-before: always;"></div>';
	     	//Buscar modelo do tópico da relação dos dirigentes superiores
	     	$dmodelo = $mdao->buscarmodelosUniTopAno1(3, $anobase, $codunidade)->fetch();
	     	$html.= $dmodelo['legenda']."<br/>".$dmodelo['descModelo'];
	     	
	     	//Verificar se existe texto nos tópicos "Menssagem do dirigente da Unidade"(137) e "Relação dos dirigentes da Unidade"(121)
	     	$rows121 = $dao->buscaTexto(121, $anobase, $codunidade);//"Relação dos dirigentes da Unidade"
	     	$rows137 = $dao->buscaTexto(137, $anobase, $codunidade);//"Menssagem do dirigente da Unidade"
	     	if ($rows121->rowCount()==0) {
	     		if ($rows137->rowCount()==0) {
	     				//Exibir sumário
	     				$html .= '<div style="display: block; page-break-before: always;"></div>';
	     				//Exibir Sumário
	     				//Buscar sumário
	     				$rowsl2 = $dao->buscaTopicosPreenchidos_Pendentes($anobase, $codunidade);
	     				$topico2=array();
	     				$topico2=incluirTopicoNoTexto($rowsl2,$topico2,$cont,$codunidade,$anobase);
	     				$rowsl2 = $dao->buscaSubTopicosPreenchidos_Pendentes($anobase, $codunidade);
	     				$topico=incluirTopicoNoTexto($rowsl2,$topico2,$cont,$codunidade,$anobase);
	     				$aux=0;$nivel2=0;
	     				 
	     				$html .= "<h3>Sumário</h3>Relação dos Dirigentes da Administração Superior<br/>";
	     				 
	     				if (count($topico)>0)  {
	     					$anterior=NULL;
	     					foreach ($topico as $ttt){
	     						$trows = $dao->buscaTexto($ttt->getCodigo(), $anobase, $codunidade);
	     						$codtexto='';
	     						$passou=0;
	     							
	     						if($trows->rowCount()>0){
	     			
	     							if($ttt->getNivel() != NULL){
	     								$nivel2++;
	     								$html .= $nivel2.". ".$ttt->getNome()."<br/>";
	     							}else{
	     								$html .= "".$ttt->getNome()."<br/>";
	     							}
	     						}
	     						if ($ttt->getSubtopicos()!=NULL ){
	     							$html .= subtopicoSS($ttt->getSubtopicos(),$codunidade,$anobase,$nivel2);
	     						}else if($passou==0 AND $ttt->getCodigo()!=2){
	     							//$html .= "Não se aplica!";
	     						}
	     					}
	     				}     			
	     		}
	     	}elseif ($rows137->rowCount()==0){
	     		/*
	     			//Exibir sumário
	     			$html .= '<div style="display: block; page-break-before: always;"></div>';
	     			//Exibir Sumário
	     			//Buscar sumário
	     			$rowsl2 = $dao->buscaTopicosPreenchidos_Pendentes($anobase, $codunidade);
	     			$topico2=array();
	     			$topico2=incluirTopicoNoTexto($rowsl2,$topico2,$cont,$codunidade,$anobase);
	     			$rowsl2 = $dao->buscaSubTopicosPreenchidos_Pendentes($anobase, $codunidade);
	     			$topico=incluirTopicoNoTexto($rowsl2,$topico2,$cont,$codunidade,$anobase);
	     			$aux=0;$nivel2=0;
	     			 
	     			$html .= "<h3>Sumárioo</h3>Relação dos Dirigentes da Administração Superior<br/>";
	     			 
	     			if (count($topico)>0)  {
	     				$anterior=NULL;
	     				foreach ($topico as $tt){
	     					$trows = $dao->buscaTexto($tt->getCodigo(), $anobase, $codunidade);
	     					$codtexto='';
	     					$passou=0;
	     						
	     					if($trows->rowCount()>0){
	     			    
	     						if($tt->getNivel() != NULL){
	     							$nivel2++;
	     							$html .= $nivel2.". ".$tt->getNome()."<br/>";
	     						}else{
	     							$html .= "".$tt->getNome()."<br/>";
	     						}
	     					}
	     					if ($tt->getSubtopicos()!=NULL ){
	     						$html .= subtopicoSS($tt->getSubtopicos(),$codunidade,$anobase,$nivel2);
	     					}else if($passou==0 AND $tt->getCodigo()!=2){
	     						//$html .= "Não se aplica!";
	     					}
	     				}
	     			}
	     	*/}                                                                                                                                                                                                                 
	     }
	  }
	  }

//$html = '<div style="display: block; page-break-before: always;"></div>'; //Quebra de Página


//header('Content-Type: application/octet-stream; charset=utf-8');
//header("Content-type: application/vnd.ms-word; charset=utf-8");
//header("Content-Disposition: attachment;Filename=Relatorio.doc");
//header("Pragma: no-cache");
//header("Expires: 0");
//echo $html;
	  
	  // reference the Dompdf namespace
	  use Dompdf\Dompdf;
	  
	  // instantiate and use the dompdf class
	  $dompdf = new Dompdf();
	  
	  $dompdf->loadHtml($html);
	  
	  // (Optional) Setup the paper size and orientation
	  $dompdf->setPaper('A3', 'portrait');
	  //$dompdf->setPaper('A4', 'landscape');
	  
	  // Render the HTML as PDF
	  $dompdf->render();
	  
	  // Output the generated PDF to Browser
	  $dompdf->stream('Relatorio Atividades');
	  
?>
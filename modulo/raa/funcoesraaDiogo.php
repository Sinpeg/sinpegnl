<?php
function buscaTopico($row,$topico,$coduni,$ano){
//echo "buscatopoco",$row['tcodigo'];
            $naoencontrou=false;
        	foreach ($topico as $ts){
                
        		if ($ts->getCodigo()==$row['subtopico']){
        			//echo $ts->getCodigo()."==".$row['codtopico'];
                    $encontrou=1;
        		   if ($row['codunidade']!=NULL){
	                   $uni=new Unidade();
			           $uni->setCodunidade($row['codunidade']);
        		   }else{
        		   	$uni=NULL;
        		   }
        		   
        		   $ts->adicionaItemSubtopico($row['tcodigo'],$row['titulo'],NULL,NULL, NULL,NULL,$row['ordem'],$uni,($ts->getNivel()==NULL)?"":$ts->getNivel().$row['ordem']);
        		   if ($row['texto']!=NULL){
			           	foreach ($ts->getSubtopicos() as $ss){
			           		if ($ss->getCodigo()==$row['tcodigo']){
			           			$uniTexto=new Unidade();
                                 // echo "uau1".$row['texto'];if ($row['tcodigo']==7) {die;}
			                    $uniTexto->setCodunidade($row['codunidade']);
			           	        $ss->criaTexto($row['codtexto'],$row['texto'],$uniTexto,$row['ano']);
			           		}
			           	}
			        }else{
			        	foreach ($ts->getSubtopicos() as $ss){
			           		if ($ss->getCodigo()==$row['tcodigo']){
			           			/*$uniTexto=new Unidade();
                                 echo "uau1".$row['texto'];if ($row['tcodigo']==7) {die;}
			                    $uniTexto->setCodunidade($row['codunidade']);
			           	        $ss->criaTexto($row['codtexto'],$row['texto'],$uniTexto,$row['ano']);*/
			           			$ts=buscaModeloParaTopico($coduni, $ss, $ano);//ano do texto
			           			
			           		}
			        	}		        	
			        }

        		}else if ($ts->getSubtopicos()!=NULL) {
        			buscaTopico($row, $ts->getSubtopicos(),$coduni,$ano);
                 }
        	}//for
            
            
}

function buscaModeloParaTopico( $codunidade,$top, $ano ){
    $mdao= new ModeloDAO();
   
 
  	$mrows=$mdao->buscarmodelosUniTopAno($top, $ano,$codunidade);
 	foreach ($mrows as $r){
 		//echo "buscaModeloParaTopico ---passou-".$r['codigo']."-". $r['legenda']."-". $r['descModelo'];
 		
 		$top->adicionaItemModelo($r['codigo'], $r['legenda'], $r['descModelo'], $r['anoInicio'], $r['anofinal'], $r['codUnidade'], $r['situacao'],$r['ordemInTopico']);
 		
	}
	return $top;
}


function incluirTopicoNoTexto($rowsl,$topico,$cont,$codunidade,$anobase){
   	$ordemintroducao=NULL;
   	$ordem = 1;
    foreach ($rowsl as $row) {
    	   $cont++;           
           $top=new Topico();
           if ($row['titulo']=='INTRODUÇÃO' || $row['titulo']=='Introdução' || $row['titulo']=='introdução'){
           	   $ordemintroducao=$row['ordem']-1;
           		//$ordemintroducao=1;
           		//$ordemintroducao=1;
           }
           $top->setCodigo($row['tcodigo']);
           $top->setNome($row['titulo']);
           $top->setTipo($row['tipo']);
           $top->setOrdem($row['ordem']);
           
           if ($ordemintroducao==NULL){
           		$top->setNivel("");
           }else{           		           		
	           	$diferenca=$top->getOrdem()-$ordemintroducao;
	           	//$top->setNivel($diferenca.".");
	           	//$ordem = $ordem+1;
           		$top->setNivel($diferenca.".");
           }
           
           $uni=new Unidade();
		   $uni->setCodunidade($row['codunidade']);//pode ser nula, se topico for padrao
		   
           if ($row['tcodigo']!=$row['subtopico']){
             buscaTopico($row,$topico,$codunidade,$anobase);
        	}else {  
			    if ($row['texto']!=NULL){
                    //echo "psddou".$row['texto'];if ($row['tcodigo']==7) {die;}
			    	$uniTexto=new Unidade();
			    	$uniTexto->setCodunidade($row['codunidade']);
			      	$top->criaTexto($row['codtexto'],$row['texto'],$uniTexto,$row['ano']);
			    }else{//busca modelo
                    //echo "passou";die;
                   
			    	
			    	$top=buscaModeloParaTopico( $codunidade,$top, $anobase);
			    }
			    $topico[$row['tcodigo']]=$top;		    
        	}

         
}
 return $topico; 
}

 
function subtopico($topico,$codunidade,$anobase,$nivel){
    $cont=0;
	foreach ($topico as $t){ 		
		$cont++;
		?>
		<h3 <?php  print " onclick=carregaModelo(".$t->getCodigo().")";?> > <?php // print $t->getNivel()." ".$t->getNome();?>
		<?php  print $nivel.".".$cont." ".$t->getNome();?>
		</h3>	
		<div id="ac<?php print $t->getCodigo();?>"></div>
		<?php
		if ($t->getSubtopicos()!=NULL){
			$cont=subtopico($t->getSubtopicos(),$codunidade,$anobase);
		}
	}//for
    return $cont;
}

//Função ultilizada para gerar arquivo
function subtopicoArquivo($topico,$codunidade,$anobase,$nivelT){
	$html = "";
	$cont=0;
	$codtexto='';
	$passou=0;
	$sNivel=0;	
	$ttdao= new TextoDAO();
	foreach ($topico as $t){ 
		$trows=$ttdao->buscaTexto($t->getCodigo(), $anobase,$codunidade);
		if ($trows->rowCount()>0){
			//$html .= "<h3 class="card-title">".$t->getNivel()." ".$t->getNome()."</h3>";
			$sNivel++;
			$html .= "<br/><h3 class="card-title">".$nivelT.".".$sNivel." ".$t->getNome()."</h3>";		    
		    
		    foreach ($trows as $r){
			  $html .= $r['texto'];	
			  $passou = 1;
			 }		
		} 
		if ($t->getSubtopicos()!=NULL){
			$html .= subtopicoArquivo($t->getSubtopicos(),$codunidade,$anobase,$nivelT);
		}else if($passou==0){
		 	//$html .= "Não se aplica!";
		 }
	
	}// Fim for
    return $html;
}

function subtopicoS($topico,$codunidade,$anobase,$nivelT){
	//print "teste";
	
	$html = "";
	$cont=0;
	$codtexto='';
	$passou=0;
	$sNivel=0;
	$tdao= new TextoDAO();	
	foreach ($topico as $t){		
		$trows=$tdao->buscaTexto($t->getCodigo(), $anobase,$codunidade);		
		if ($trows->rowCount()>0){			
			$sNivel++;
			print $nivelT.".".$sNivel." ".$t->getNome()."<br/>";
		}
		/*
		if ($tt->getSubtopicos()!=NULL){
			print subtopicoS($tt->getSubtopicos(),$codunidade,$anobase,$nivelT);
		}else if($passou==0){
			//$html .= "Não se aplica!";
		}
		*/
	}// Fim for
	//return $html;
	
}

//Função para gerar subtópico no sumário dinâmico
function subtopicoSS($topico,$codunidade,$anobase,$nivelT){
	//print "teste";

	$html = "";
	$cont=0;
	$codtexto='';
	$passou=0;
	$sNivel=0;
	$tdao= new TextoDAO();
	foreach ($topico as $t){
		$trows=$tdao->buscaTexto($t->getCodigo(), $anobase,$codunidade);
		if ($trows->rowCount()>0){
			$sNivel++;
			$html .= $nivelT.".".$sNivel." ".$t->getNome()."<br/>";
		}
		/*
		 if ($tt->getSubtopicos()!=NULL){
		print subtopicoS($tt->getSubtopicos(),$codunidade,$anobase,$nivelT);
		}else if($passou==0){
		//$html .= "Não se aplica!";
		}
		*/
	}// Fim for
	return $html;

}

?>
<?php 	

if ($t->getCodigo()==145){//Quando o tópico for "Gestão Orçamentária e Financeira"
			$totalcp=0.0;$totalcr=0.0;$totalcl=0.0;$totalca=0.0;$totalcd=0.0;$totalcm=0.0;$totalce=0.0;$totalcq=0.0;			
			
			$tem=0;		
			include 'raainclui1004.php';
		    if ($codunidade==971){
		    	$codunidade=1004;
		    	include 'raainclui1004.php';
                $codunidade=1001;
		    	include 'raainclui1004.php';
		    	$codunidade=971;		   
		     }
		    
		    
 }	
 if ($t->getCodigo()==143 ){//quando o toṕico for analise de resultados
			$daodoc=new DocumentoDAO();
		 	$rowsdoc=$daodoc->raa_tabelaindicador(2, $codunidade,  2, $anobase);
		 	
		 	$cont=0;
		 	$corpoTable ="";
		 	$anterior="t";
		 	$soma=0.0;
		 	foreach ($rowsdoc as $d){
		 		if ($d['meta']!=0.0){		 			
		 			
		 				 		
			 		if ($anterior!=$d['nome']){
			 			$cont++;
			 			
			 			
			 			
			 			if($d['palcance']>=100){ // AQUI
			 			    $d['palcance'] = 100;
			 			}
			 			
			 			
			 			$soma+=$d['palcance'];
			 		    $resultado=$d['resultado']==NULL?"NÃO INFORMADO":number_format($d['resultado'], 2, ',', '.');
			 		    $corpoTable .= '<tr><td>'.$d['nome'].'</td><td>'.number_format($d['meta'], 2, ',', '.').'</td><td>'.$resultado.'</td><td>'.number_format($d['palcance'], 2, ',', '.').'%</td><td>'.$d['nomeiniciativa'].'</td></tr>';
			 		}else{
			 		    $corpoTable .= '<tr><td></td><td></td><td></td><td></td><td>'.$d['nomeiniciativa'].'</td></tr>';
			 			
			 		}
			 		$anterior=$d['nome'];
		 		}
		 		
			}
		 	
				$html.='<p style="font-size:16px;">Tabela 1 - Tabela de Percentual de Desempenho Geral-'.$anobase.'</p>';
				$html.='<table disabled="disabled border=1 cellspacing=0 cellpadding=2 bordercolor="1c5a7d"><tr bgcolor="#CCCCCC"><td style="text-align:center;vertical-align: middle;font-size:16px;"><b>Indicador</b></td>
						 	<td style="text-align:center;vertical-align: middle"><b>Meta</b></td>
						 	<td style="text-align:center;vertical-align: middle"><b>Resultado</b></td>
						 	<td style="text-align:center;vertical-align: middle"><b>Percentual de Desempenho</b></td>
						 	<td style="text-align:center;vertical-align: middle"><b>Iniciativa</b></td></tr>';
		 		$html.= $corpoTable;	
		 		$media=$cont==0?0:round($soma/$cont,2);
		 		//echo $cont."-".$oma."-".$media."dfsf";
		 		$html.= '<tr><td colspan="3" style="text-align:center;vertical-align: middle"><b>Percentual de Desempenho Geral</b></td><td>'.number_format($media, 2, ',', '.').'</td><td></td></tr>';
		 				   	$html.= '</table> <p style="font-size:13px;">Fonte:PDU/SiNPEG</p><br>';
		 	
		 	
		   
		   	
		   
		} 
		
		?>

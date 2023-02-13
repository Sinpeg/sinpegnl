<?php 	if($t->getCodigo()==145){//Quando o tópico for "Gestão Orçamentária e Financeira"
			$totalcp=0.0;$totalcr=0.0;$totalcl=0.0;$totalca=0.0;$totalcd=0.0;$totalcm=0.0;$totalce=0.0;$totalcq=0.0;
			
			print 'Quadro 2 - Desempenho do Orçamento de Custeio no Exercício por Plano Interno <br/>';
			$tem=0;
			include 'raainclui1004.php';
		    if ($codunidade==971){
		    	$codunidade=1004;
		    	include 'raainclui1004.php';
               // $codunidade=1001;
		    	//include 'raainclui1004.php';
		    	$codunidade=971;		   
		     }
		    
		    print '</table><br>';
		    ?>
		    
		    
		   <textarea   id="area<?php print $codTopico;?>" name="texto"  <?php print $disabled;?>>
	   		
		    <?php 
$passou=0;
		    
foreach ($trows as $r){
		print $r['texto'];	
		$codtexto=$r['codigo'];
	    $passou=1;
		
	   }
		    
		    
	if ($passou==0){	    
		    $passou=1;
		    foreach ($rowsa as $r){
	     		print($r['legenda'].$r['descModelo']);
	     		$passou=0;
		      }

		   
		   if ($passou==1){
			print 'Insira a análise crítica!<br><br>';
		   }
			  
	}?>

		    </textarea>
			<?php 
		}	
		$flagNaoResult=0;
		if ($t->getCodigo()==143 ){//quando o toṕico for analise de resultados
			$daodoc=new DocumentoDAO();
		 	$rowsdoc=$daodoc->raa_tabelaindicador(2, $codunidade,  2, $anobase);
		 	
		 	$cont=0;
		 	$corpoTable ="";
		 	$anterior="t";
		 	$soma=0.0;
		 	$contTeste=0;
		 	
		 	
		 	foreach ($rowsdoc as $d){
		 	    
		 	    if ($d['meta']!=0.0){		 			
		 	        
		 			if ($d['resultado']==NULL) {//Caso possua meta e o resultado não foi lançado
		 				$flagNaoResult=1;
		 				//   print  $d['meta']."-".$d['nome']."<br/>";
		 			}
		 			
			 		
			 		
			 		if ($anterior!=$d['nome']){
			 			$cont++;
			 			if($d['palcance']>=100){
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
			//print $flagNaoResult;
		 	if ($flagNaoResult==1) {
		 		print '<div class="alert alert-danger" role="alert">Existe algum indicador sem resultado!<br/>Somente após o lançamento de todos os resultados dos indicadores do PDU este tópico será liberado.</div>';
		 	}else{
				print('Tabela 1 - Tabela de Percentual de Desempenho Geral');
				print('<div style="font-size:11px"><table disabled="disabled border=1 cellspacing=0 cellpadding=2 bordercolor="1c5a7d"><tr bgcolor="#CCCCCC"><td style="text-align:center;vertical-align: middle;font-size:11px;"><b>Indicador</b></td>
						 	<td style="text-align:center;vertical-align: middle"><b>Meta</b></td>
						 	<td style="text-align:center;vertical-align: middle"><b>Resultado</b></td>
						 	<td style="text-align:center;vertical-align: middle"><b>Percentual de Desempenho</b></td>
						 	<td style="text-align:center;vertical-align: middle"><b>Iniciativa</b></td></tr>');
		 		print $corpoTable;	
		 		//print $soma."/".$contTeste;
		 		$media=$cont==0?0:round($soma/$cont,2);
		 		//echo $cont."-".$oma."-".$media."dfsf";
		 		print '<tr><td colspan="3" style="text-align:center;vertical-align: middle"><b>Percentual de Desempenho Geral</b></td><td>'.number_format($media, 2, ',', '.').'</td><td></td></tr>';
		 				   	print '</table> <p style="font-size:13px;">Fonte:PDU/SiNPEG</p><br></div>';
		 	
		 			   	
		 	if ($cont==0 && $codunidade!= 963){?> 
		   	 <textarea  id="area<?php print $codTopico;?>" name="texto"  <?php print $disabled;?>>
		     		
	
		     </textarea>
		      
		   	<?php  }else{
		   		
		   		
	
			   	
		   		?>
		   	
		   	   <textarea  id="area<?php print $codTopico;?>"  name="texto"  <?php print $disabled;?>>
		   	   <?php 
		   	   $passou1=0;
		   	   foreach ($trows as $r){
					print $r['texto'];	
					$codtexto=$r['codigo'];
					$passou1=1;
				} 

	   
	   if ($passou1==0){?>
		   	   
		   	   
		   	   
		   	 	Insira a análise crítica!<br>
		   	 	<?php }?>
		   	 	</textarea>
		   <?php  }
		 	}
		}
		?>

<?php
       $daoii=new IndicIniciativaDAO();
				              //  echo 'iniciativa'.$objmapa[$indicemapa]->getMapaindicador()->getCodigo();
				                $rows=$daoii->iniciativaPorMapIndicador($objmapa[$indicemapa]->getMapaindicador()->getCodigo(),$sessao->getAnobase(),$per);
				                
				                $objini=array();
				                $ind=0;
				                
				                foreach ($rows as $r){
				                   $objini[$ind]=new Iniciativa();                
				                    $objini[$ind]->setCodIniciativa($r['codIniciativa']);                 
				                    $objini[$ind]->setNome($r['nome']);
				                  //  $objini[$ind]->setFinalidade($r['finalidade']);
				                    $objini[$ind]->criaIndicIniciativa($r['codindinic'],$objmapa[$indicemapa]->getMapaindicador());
				                    $ind++;
				                }
				echo '<br>';
				
				$daorini=new ResultIniciativaDAO();
				
				for ($j=0;$j<count($objini);$j++){
				    
				     
				    $rows=$daorini->iniciativaPorResultado($objini[$j]->getCodIniciativa(), $sessao->getAnobase(),$per);
				    foreach  ($rows as $row){
				        $objini[$j]->criaResultIniciativa($row['codResultIniciativa'],$objcal->getCodigo()==$row['codCalendario']?$objcal:NULL,
				            $row['situacao'],$row['pfcapacit'],$row['pfrecti'],$row['pfinfraf'],$row['pfrecf'],$row['pfplanj'],$row['outros'],$row['periodo']
				            );
				       
				              	   
				    }  
				}
				?>
				        				
				        
	<legend>Iniciativa(s) </legend>
	<?php for ($j=0;$j<count($objini);$j++){ ?>
	        <input class="form-control"type="hidden" name="codindini<?php echo $j;?>" value="<?php print $objini[$j]->getIndicIniciativa()->getCodigo(); ?>" />
	        <input class="form-control"type="hidden" name="codini<?php echo $j;?>" value="<?php print   $objini[$j]->getCodiniciativa(); ?>" />  
				  
		<table>
                <tr>
                        <td>Iniciativa:</td>
                        <td> <?php echo $objini[$j]->getNome(); ?></td>
                    </tr>
                   <!--   <tr>
                        <td>Finalidade da iniciativa:</td>
                        <td> <?php // echo $objini[$j]->getFinalidade(); ?></td>
                    </tr>-->
                    <tr>
                        <td><strong>Situa????o</strong></td>
                        <td>
                        
                             <?php 
                            if ($objini[$j]->getResultiniciativa()!=NULL){
                              echo $objini[$j]->getResultiniciativa()->getSituacao()==1?"N??o iniciado":""; 
                              echo $objini[$j]->getResultiniciativa()->getSituacao()==2?"Em atraso":"";  
                              echo $objini[$j]->getResultiniciativa()->getSituacao()==3?"Com atrasos cr??tico":"";   
                              echo $objini[$j]->getResultiniciativa()->getSituacao()==4?"Em andamento normal":"";  
                              echo $objini[$j]->getResultiniciativa()->getSituacao()==5?"Conclu??do":"";
                            }else{
                            	echo "sem resultado";
                            }  ?> 
                           </td>
                    </tr>
                    <tr>
                        <td><strong>Fatores que influenciarama situa????o atual:</strong></td>
                        
                        <td>
                        
                        <ul>
                        <?php echo ($objini[$j]->getResultiniciativa()->getPfcapacit()==1)?  "<li>Capacita????o</li>": ""; ?>
                        <?php echo ($objini[$j]->getResultiniciativa()->getPfrecti()==1)? "<li>Recursos de Tecnologia da Informa????o</li>":""; ?>
                        <?php echo ($objini[$j]->getResultiniciativa()->getPfinfraf()==1)? "<li>Infraestrutura F??sica</li>":""; ?>
                        <?php echo ($objini[$j]->getResultiniciativa()->getPfrecf()==1)?"<li>Recursos financeiros</li>":""; ?>
                        <?php echo($objini[$j]->getResultiniciativa()->getPfplanj()==1)?"<li>Planejamento</li>":""; ?>
                        <?php echo ($objini[$j]->getResultiniciativa()->getOutros()!=NULL)?"<li>".$objini[$j]->getResultiniciativa()->getOutros()."</li>":""; ?></li>
                        
                        
                        </ul>  </td>
                      
                    </tr>
       </table>
<?php }//for objiniresult 
					
	
	?>
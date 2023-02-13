<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../classes/sessao.php';
require_once '../dao/PDOConnectionFactory.php';
require_once '../dao/painelTaticoDAO.php';
//require_once '../classes/validacao.php';
require_once '../vendors/dompdf/autoload.inc.php';

session_start();
$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeUnidade();
$codunidade = $sessao->getCodUnidade();

//Recupera os parâmetros enviados via GET
$cod_unidade = $_GET['unidade'];
$ano_base = $_GET['anoBase'];


//Quando a unidade não for a 938
if($cod_unidade != 938){
	//Banco de Dados e Query para obter o PDI
	$painelPDI = new PainelTaticoDAO();
	$rowsPDI = $painelPDI->exportarPainelPDI($ano_base, $cod_unidade);
	// Comentado by Diogo $daoserv = new ServprocDAO();
   $painelTatico = new PainelTaticoDAO();
   $rows = $painelTatico->exportarPainel($ano_base, $cod_unidade);
}else {
	$painelPDI = new PainelTaticoDAO();
	$rows = $painelPDI->exportarPainelPDI938($ano_base);
	
}



//$verifica2 =  count(get_object_vars($rows));
//echo $verifica2;

//Cabeçalho do relatório
$html_aux ='<center>
				<img src="../webroot/img/UFPA.png" height="50" width="50" />
				<h5>UNIVERSIDADE FEDERAL DO PARÁ</h5>
			</center>';

$aux = 0;$ob_aux=''; $color_tr="";$aux2=1;
$ind_aux='';$color_tr_ind="";$aux3=1;
$color_meta=0;$color_meta2=0;$aux4=1;
$linha_aux=""; //Para construir a linha da meta do indicador a cada loop
$meta_aux = "";$ano_aux="";$ini_no_rep = 0;
$quedra_linha=0;$iniciativa_aux = "";
////////////////////////////////////////////////Gera o PDU ou o PDI quando se tratar do usuário da Universidade
//while ($row = mysqli_fetch_assoc($query)){



foreach ($rows as $row) {
	
	if($aux==0){// Define o cabeçalho da tabela
		$ano_inicial = $row['anoinicial'];
		$ano_final=$row['anofinal'];
		$html_aux .= "<center><h5>".$nomeunidade."<br/>".$row['nomedocumento']." - ".$row['anoinicial']." - ".$row['anofinal'].'<br/>';
		if($cod_unidade != 938){
			$html_aux .= '<br/>PAINEL TÁTICO</h5></center>';
		}else{
			$html_aux .= '<br/>PAINEL ESTRATÉGICO</h5></center>';
		}
		
		$html_aux .= '<table style="font-size:13px;" border="0"  cellspacing="0"  rules="cols" width="100%"><tr >
		<td align="center" width="15%" bgcolor="#DCDCDC" ><b>Objetivo</b></td>
		<td align="center" width="15%" bgcolor="#DCDCDC"><b>Indicador</b></td>
		<td align="center" width="18%" bgcolor="#DCDCDC"><b>F&oacute;rmula</b></td>
		<td align="center" width="9%" bgcolor="#DCDCDC"><b>Interp.</b></td>';
		
		//Define os anos de meta no cabeçalho da tabela
		$count_metas= 1;
		for($i=$row['anoinicial'];$i<=$row['anofinal'];$i++){
			$html_aux .='<td align="center" bgcolor="#DCDCDC" width="4%"><b>Meta<br/>'.$i.'</b></td>';
			$count_metas++;
		}
		$count_metas--;
		
		$html_aux .='<td align="center" bgcolor="#DCDCDC" ><b>Iniciativa</b></td>
		</tr>';
		$aux++;
	}
	
	// Define o corpo da tabela	//////////////////////////////////////////////////////////////		
		if($ob_aux == $row['Objetivo']){//Caso o objetivo tenha mais de um indicador			
		
		}else{//Primeira ocorrência do objetivo
			if($aux2==1){$color_tr="#FFFFFF";$aux2=2;}else{$color_tr="#DCDCDC";$aux2=1;}//Define alternancia de cor do objetivo
			//$html.='<tr bgcolor="'.$color_tr.'"><td >'.utf8_encode($row['Objetivo']).'</td>';			
		}
		
		//Caso haja mais de um indicador para o mesmo objetivo
		if($ind_aux == $row['nomeindicador'] && $ob_aux == $row['Objetivo']){			
			
		if($ano_aux != $row['ano']){							
			$count_metas2= $count_metas2+1; // Conta o número de metas do retorno da consulta até o momento
		}
		if($ano_aux != $row['ano'] ){//Obs: existe apenas uma meta para cada ano	
			//Meta
			if($row['meta']!= NULL){
				$ini_no_rep = 1;
				//if($aux4==1){$color_meta="#E1F5A9";$aux4=2;}else{$color_meta="#CEF6F5";$aux4=1;}
				if($row['metrica']=="Percentual"){
					$html_aux .='<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.',',', $row['meta']).'% </td>';
				}else{
					$html_aux .='<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.',',', $row['meta']).'</td>';
				}
				
				//Armazena as iniciativas
				//$iniciativa_linha .= '- teste'.$row['nomeiniciativa'].'<br/>';
					
				if($count_metas == $count_metas2){//Verifica se atingiu o número de metas para o dado indicador
					$html_aux .='<td bgcolor="'.$color_tr_ind.'"><br/>'.$iniciativa_linha.'<br/></td></tr>';
				}
				$meta_aux = $row['meta']; //armazena a meta corrente
				$ano_aux = $row['ano'];
			}else{// Caso mesmo objetivo,mesmo indicador e meta é NULL
				//if($aux4==1){$color_meta="#E1F5A9";$aux4=2;}else{$color_meta="#CEF6F5";$aux4=1;}				
				//$html_aux .='<td bgcolor="'.$color_meta.'"></td>';
				
				$html_aux .= '<tr><td bgcolor="'.$color_tr.'"></td><td bgcolor="'.$color_tr_ind.'"></td><td bgcolor="'.$color_tr_ind.'"></td>';
				for($i=$row['anoinicial'];$i<=$row['anofinal'];$i++){
					$html_aux .='<td bgcolor="'.$color_tr_ind.'"></td>';
				}
				$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row["nomeiniciativa"].'<br/><br/></td></tr>';		
					
			}
			
		}else if($ini_no_rep ==0){//Caso seja a mesma meta para o mesmo ano e mais de uma iniciativa
			//Armazena as iniciativas
			$iniciativa_linha .= '- '.$row['nomeiniciativa'].'<br/>';
			
			$meta_aux = $row['meta']; //armazena a meta corrente
			$ano_aux = $row['ano']; //armazena a ano da meta
			//$ini_no_rep = 1;
		}	
		}else{//Primeira ocorrência do Objetivo com o indicador
			
			$ini_no_rep = 0;
			$count_metas2 = 1; //Ocorrência da primeira meta
			$iniciativa_linha = "";
			$ini_no_rep = 0;
			if($aux3==1){$color_tr_ind="#FFE4C4";$aux3=2;}else{$color_tr_ind="#FFFFE0";$aux3=1;}	
			
			//Meta
			if($row['meta']!= NULL){
				if($aux4==1){$color_meta="#E1F5A9";$aux4=2;}else{$color_meta="#CEF6F5";$aux4=1;}
				if($row['metrica']=="Percentual"){
					if($ob_aux != $row['Objetivo']){//Caso o objetivo tenha mais de um indicador	
						//Exibi o primeiro objetivo
						$html_aux .= '<tr bgcolor="'.$color_tr.'"><td >'.$row['Objetivo'].'</td>';
						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['nomeindicador'].'</td>';
						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['calculo'].'</td>';
						//Verificar valor da interpretação
						$interpretacao="";
						if($row['interpretacao'] == 1){
							$interpretacao = "Quanto maior, melhor.";
						}else if($row['interpretacao'] == 2){
							$interpretacao = "Quanto menor, melhor.";
						}
						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$interpretacao.'</td>';
					}else{
						//Exibi o objetivo						
						$html_aux .= '<tr bgcolor="'.$color_tr.'"><td></td>';
						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['nomeindicador'].'</td>';
						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['calculo'].'</td>';
						//Verificar valor da interpretação
						$interpretacao="";
						if($row['interpretacao'] == 1){
							$interpretacao = "Quanto maior, melhor.";
						}else if($row['interpretacao'] == 2){
							$interpretacao = "Quanto menor, melhor.";
						}
						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$interpretacao.'</td>';
					}					
					$html_aux .= '<td align="center" bgcolor="'.$color_tr_ind.'">'.number_format($row['meta'], 2, ',', '.').'% '.'</td>';
					
				}else{
					//$html .='<td bgcolor="'.$color_meta.'">'.$row['meta'].'</td>';
					
					if($ob_aux != $row['Objetivo']){//Caso o objetivo tenha mais de um indicador	
						//Exibi o primeiro objetivo
						$html_aux .= '<tr bgcolor="'.$color_tr.'"><td >'.$row['Objetivo'].'</td>';
						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['nomeindicador'].'</td>';
						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['calculo'].'</td>';
						//Verificar valor da interpretação
						$interpretacao="";
						if($row['interpretacao'] == 1){
							$interpretacao = "Quanto maior, melhor.";
						}else if($row['interpretacao'] == 2){
							$interpretacao = "Quanto menor, melhor.";
						}
						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$interpretacao.'</td>';
					}else{
						//Exibi o primeiro objetivo
						$html_aux .= '<tr bgcolor="'.$color_tr.'"><td></td>';
						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['nomeindicador'].'</td>';
						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['calculo'].'</td>';
						//Verificar valor da interpretação
						$interpretacao="";
						if($row['interpretacao'] == 1){
							$interpretacao = "Quanto maior, melhor.";
						}else if($row['interpretacao'] == 2){
							$interpretacao = "Quanto menor, melhor.";
						}
						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$interpretacao.'</td>';
					}					
					$html_aux .= '<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.', ',', $row['meta']).'</td>';
					
				}
				//Armazena iniciativa
				$iniciativa_linha .= '- '.$row['nomeiniciativa'].'<br/>';					
				$iniciativa_aux = $row['nomeiniciativa'];
				
				$meta_aux = $row['meta']; //armazena a meta corrente
				$ano_aux = $row['ano']; //armazena a ano da meta
				
				
			}else{//Qunado a meta está como NULL
				if($aux4==1){$color_meta="#E1F5A9";$aux4=2;}else{$color_meta="#CEF6F5";$aux4=1;}
						
					if($ob_aux != $row['Objetivo']){//Caso o objetivo tenha mais de um indicador	
						//Exibi o primeiro objetivo
						$html_aux .= '<tr bgcolor="'.$color_tr.'"><td>'.$row['Objetivo'].'</td>';
						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['nomeindicador'].'</td>';
						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['calculo'].'</td>';
						//Verificar valor da interpretação
						$interpretacao="";
						if($row['interpretacao'] == 1){
							$interpretacao = "Quanto maior, melhor.";
						}else if($row['interpretacao'] == 2){
							$interpretacao = "Quanto menor, melhor.";
						}
						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$interpretacao.'</td>';
					}else{
						//Exibi o primeiro objetivo
						$html_aux .= '<tr bgcolor="'.$color_tr.'"><td></td>';
						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['nomeindicador'].'</td>';
						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['calculo'].'</td>';
						//Verificar valor da interpretação
						$interpretacao="";
						if($row['interpretacao'] == 1){
							$interpretacao = "Quanto maior, melhor.";
						}else if($row['interpretacao'] == 2){
							$interpretacao = "Quanto menor, melhor.";
						}
						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$interpretacao.'</td>';
					}				
					for($i=$row['anoinicial'];$i<=$row['anofinal'];$i++){
						$html_aux .='<td bgcolor="'.$color_tr_ind.'"></td>';
						if($i == $row['anofinal']){
							$html_aux .='<td bgcolor="'.$color_tr_ind.'">'.$row["nomeiniciativa"].'</td></tr>';
						}						
					}					
			}
			//$html .='<td>Iniciativa</td></tr>';
			
			
		}	
		$ob_aux = $row['Objetivo'];
		$ind_aux = $row['nomeindicador'];
		$ano_aux = $row['ano'];
		
	
}
$html_aux .='</table>';

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//if(count(get_object_vars($rowsPDI)) == 0){echo "vazio";}else{echo "nao vazio";}

//$verifica =  count(get_object_vars($rowsPDI));

//echo var_dump($rowsPDI);


//Gera o PDI caso a unidade seja diferente da 938
if($cod_unidade != 938 ){
	$aux = 0;$ob_aux=''; $color_tr="";$aux2=1;
	$ind_aux='';$color_tr_ind="";$aux3=1;
	$color_meta=0;$color_meta2=0;$aux4=1;
	$linha_aux=""; //Para construir a linha da meta do indicador a cada loop
	$meta_aux = "";$ano_aux="";
	$quedra_linha=0;
	$ver = 0;
	
	foreach ($rowsPDI as $row){		
			
		if(!empty($row)){			
			$ver = 1;
		if($aux==0){// Define o cabeçalho da tabela
			$html_aux .= '<br/><br/><br/><br/><br/><center><h5>INDICADORES DO PDI</h5></center>';
			$html_aux .= '<table style="font-size:13px;" border="0"  cellspacing="0"  rules="cols" width="100%"><tr>
			<td align="center" width="15%" bgcolor="#DCDCDC" ><b>Objetivo</b></td>
			<td align="center" width="15%" bgcolor="#DCDCDC"><b>Indicador</b></td>
			<td align="center" width="18%" bgcolor="#DCDCDC"><b>F&oacute;rmula</b></td>
			<td align="center" width="9%" bgcolor="#DCDCDC"><b>Interpreta&ccedil;&atilde;o</b></td>';
	
			//Define os anos de meta no cabeçalho da tabela
			$count_metas_pdu = $count_metas;
			$count_metas = 1;
			for($i=$ano_inicial	;$count_metas<=$count_metas_pdu;$i++){
				$html_aux .='<td align="center" bgcolor="#DCDCDC" width="4%"><b>Meta<br/>'.$i.'</b></td>';
				$count_metas++;
			}
	
			$html_aux .='<td align="center" bgcolor="#DCDCDC" ><b>Iniciativa</b></td>
		</tr>';
			$aux++;
			$count_metas--;
		}
		
		
		//Verifica se o ano do PDI está entre os anos de metas do PDU
	    if($row['ano'] >= $ano_inicial && $row['ano'] <= $ano_final){
	    			
	    			//Define o corpo da tabela	
					if($ob_aux == $row['Objetivo']){//Caso o objetivo tenha mais de um indicador
				
					}else{//Primeira ocorrência do objetivo
						if($aux2==1){$color_tr="#FFFFFF";$aux2=2;}else{$color_tr="#DCDCDC";$aux2=1;}//Define alternancia de cor do objetivo
						//$html.='<tr bgcolor="'.$color_tr.'"><td >'.utf8_encode($row['Objetivo']).'</td>';
					}
				
					//Caso haja mais de um indicador para o mesmo objetivo
					if($ind_aux == $row['nomeindicador'] && $ob_aux == $row['Objetivo']){
						
						if($ano_aux != $row['ano']){							
							$count_metas2= $count_metas2+1; // Conta o número de metas do retorno da consulta até o momento
						}
						$quedra_linha=1;
						
						if($ano_aux != $row['ano'] ){	
						//Meta
						if($row['meta']!= NULL){
							$ini_no_rep = 1;
						//if($aux4==1){$color_meta="#E1F5A9";$aux4=2;}else{$color_meta="#CEF6F5";$aux4=1;}
							if($row['metrica']=="Percentual"){
									$html_aux .='<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.',',', $row['meta']).'% </td>';
							}else{
									$html_aux .='<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.',',', $row['meta']).'</td>';
							}
					
							//Armazena as iniciativas
							//$iniciativa_linha .= '- '.$row['nomeiniciativa'].'<br/>';
								
							//if($count_metas == $count_metas2 ){//Verifica se atingiu o número de metas para o dado indicador
							//	$html_aux .='<td bgcolor="'.$color_tr_ind.'">'.$iniciativa_linha.'</td></tr>';
							//}
							
							$meta_aux = $row['meta']; //armazena a meta corrente
							$ano_aux = $row['ano']; //armazena a ano da meta
							
							}else{// Caso mesmo objetivo,mesmo indicador e meta é NULL
							//if($aux4==1){$color_meta="#E1F5A9";$aux4=2;}else{$color_meta="#CEF6F5";$aux4=1;}
								//$html_aux .='<td bgcolor="'.$color_meta.'"></td>';
						
								$html_aux .= '<tr><td bgcolor="'.$color_tr.'"></td><td bgcolor="'.$color_tr_ind.'"></td><td bgcolor="'.$color_tr_ind.'"></td>';
									for($i=$row['anoinicial'];$i<=$row['anofinal'];$i++){
										$html_aux .='<td bgcolor="'.$color_tr_ind.'"></td>';
									}
								$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row["nomeiniciativa"].'<br/><br/></td></tr>';
								
							}
						 	}else if($ini_no_rep ==0){//Caso seja a mesma meta para o mesmo ano e mais de uma iniciativa								
								//Armazena as iniciativas
								$iniciativa_linha .= '- '.$row['nomeiniciativa'].'<br/>';								
								
								$meta_aux = $row['meta']; //armazena a meta corrente
								$ano_aux = $row['ano']; //armazena a ano da meta
							}	
							
							//if($count_metas == $count_metas2){//Verifica se atingiu o número de metas para o dado indicador
							//echo $quedra_linha;
							//}
					}else{//Primeira ocorrência do Objetivo com o indicador					
						
						if($quedra_linha!=0){
							$html_aux .='<td bgcolor="'.$color_tr_ind.'"><br/>'.$iniciativa_linha.'<br/></td></tr>';
							$quedra_linha = 0;
						}
						$ini_no_rep = 0;
						$count_metas2 = 1;
						$iniciativa_linha = "";
						if($aux3==1){$color_tr_ind="#FFE4C4";$aux3=2;}else{$color_tr_ind="#FFFFE0";$aux3=1;}
							
						//Meta
						if($row['meta']!= NULL){
							if($aux4==1){$color_meta="#E1F5A9";$aux4=2;}else{$color_meta="#CEF6F5";$aux4=1;}
							if($row['metrica']=="Percentual"){
									if($ob_aux != $row['Objetivo']){//Caso o objetivo tenha mais de um indicador
										//Exibi o primeiro objetivo
											$html_aux .= '<tr bgcolor="'.$color_tr.'"><td >'.$row['Objetivo'].'</td>';
											$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['nomeindicador'].'</td>';
											$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['calculo'].'</td>';
											//Verificar valor da interpretação
            						        $interpretacao="";
                    						if($row['interpretacao'] == 1){
                    							$interpretacao = "Quanto maior, melhor.";
                    						}else if($row['interpretacao'] == 2){
                    							$interpretacao = "Quanto menor, melhor.";
                    						}
                    						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$interpretacao.'</td>';
            						}else{
            								//Exibi o primeiro objetivo
            								$html_aux .= '<tr bgcolor="'.$color_tr.'"><td></td>';
            								$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['nomeindicador'].'</td>';
            								$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['calculo'].'</td>';
            								//Verificar valor da interpretação
                    						$interpretacao="";
                    						if($row['interpretacao'] == 1){
                    							$interpretacao = "Quanto maior, melhor.";
                    						}else if($row['interpretacao'] == 2){
                    							$interpretacao = "Quanto menor, melhor.";
                    						}
                    						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$interpretacao.'</td>';
									}
									$html_aux .= '<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.',',', $row['meta']).'% '.'</td>';
								
							}else{								
									if($ob_aux != $row['Objetivo']){//Caso o objetivo tenha mais de um indicador
											//Exibi o primeiro objetivo
											$html_aux .= '<tr bgcolor="'.$color_tr.'"><td >'.$row['Objetivo'].'</td>';
											$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['nomeindicador'].'</td>';
											$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['calculo'].'</td>';
											//Verificar valor da interpretação
                    						$interpretacao="";
                    						if($row['interpretacao'] == 1){
                    							$interpretacao = "Quanto maior, melhor.";
                    						}else if($row['interpretacao'] == 2){
                    							$interpretacao = "Quanto menor, melhor.";
                    						}
                    						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$interpretacao.'</td>';
            						}else{
            								//Exibi o primeiro objetivo
            								$html_aux .= '<tr bgcolor="'.$color_tr.'"><td></td>';
            								$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['nomeindicador'].'</td>';
            								$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['calculo'].'</td>';
            								//Verificar valor da interpretação
            						        $interpretacao="";
                                        	if($row['interpretacao'] == 1){
                                        		$interpretacao = "Quanto maior, melhor.";
                                        	}else if($row['interpretacao'] == 2){
                                        		$interpretacao = "Quanto menor, melhor.";
                                        	}
                                        	$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$interpretacao.'</td>';
            								}
            								$html_aux .= '<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.', ',', $row['meta']).'</td>';										
							}
							//Armazena iniciativa
							$iniciativa_linha .= '- '.$row['nomeiniciativa'].'<br/>';
							
							$meta_aux = $row['meta']; //armazena a meta corrente
							$ano_aux = $row['ano']; //armazena a ano da meta
						}else{//Qunado a meta está como NULL
								if($aux4==1){$color_meta="#E1F5A9";$aux4=2;}else{$color_meta="#CEF6F5";$aux4=1;}
				
								if($ob_aux != $row['Objetivo']){//Caso o objetivo tenha mais de um indicador
									//Exibi o primeiro objetivo
											$html_aux .= '<tr bgcolor="'.$color_tr.'"><td>'.$row['Objetivo'].'</td>';
												$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['nomeindicador'].'</td>';
									$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['calculo'].'</td>';
									//Verificar valor da interpretação
						$interpretacao="";
						if($row['interpretacao'] == 1){
							$interpretacao = "Quanto maior, melhor.";
						}else if($row['interpretacao'] == 2){
							$interpretacao = "Quanto menor, melhor.";
						}
						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$interpretacao.'</td>';
								}	else{
												//Exibi o primeiro objetivo
												$html_aux .= '<tr bgcolor="'.$color_tr.'"><td></td>';
														$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['nomeindicador'].'</td>';
													$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$row['calculo'].'</td>';
													//Verificar valor da interpretação
						$interpretacao="";
						if($row['interpretacao'] == 1){
							$interpretacao = "Quanto maior, melhor.";
						}else if($row['interpretacao'] == 2){
							$interpretacao = "Quanto menor, melhor.";
						}
						$html_aux .= '<td bgcolor="'.$color_tr_ind.'">'.$interpretacao.'</td>';
										}
										for($i=$row['anoinicial'];$i<=$row['anofinal'];$i++){
													$html_aux .='<td bgcolor="'.$color_tr_ind.'"></td>';
													if($i == $row['anofinal']){
													$html_aux .='<td bgcolor="'.$color_tr_ind.'">'.$row["nomeiniciativa"].'</td></tr>';
													}
								}
														
														
													}
																//$html .='<td>Iniciativa</td></tr>';
							}
							
							$ob_aux = $row['Objetivo'];
							$ind_aux = $row['nomeindicador'];
	     }
		}
		
	}//fim foreach
	
	//echo $ver;
	if($ver!=0){
		$html_aux .='<td bgcolor="'.$color_tr_ind.'"><br/>'.$iniciativa_linha.'<br/></td></tr>';
	}

}

$html_aux .='</table>';

//echo $html_aux;


// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

$dompdf->loadHtml($html_aux);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream('Painel_Tatico.pdf');

?>

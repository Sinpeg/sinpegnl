<?php
foreach ($rows as $row){
	// Define o corpo da tabela
	if($ob_aux == $row['Objetivo']){//Caso o objetivo tenha mais de um indicador	
	
	}else{//Primeira ocorrência do objetivo
		if($aux2==1){$color_tr="#FFFFFF";$aux2=2;}else{$color_tr="#DCDCDC";$aux2=1;}//Define alternancia de cor do objetivo		
	}
	
	//Caso haja mais de um indicador para o mesmo objetivo
	if($ind_aux == $row['nomeindicador'] && $ob_aux == $row['Objetivo']){
		//Meta
		if($row['meta']!= NULL){ 
		    if($row['ano'] != $ano_aux && $row['meta'] != $meta_aux){
				if($row['metrica']=="Percentual"){
					$html .='<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.',',', $row['meta']).'% </td>';
					$html .='<td bgcolor="'.$color_tr_ind.'"><br/>'.$row['meta_atingida'].'<br/></td>';				
				}else{
					$html .='<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.',',', $row['meta']).'</td>';
					$html .='<td bgcolor="'.$color_tr_ind.'"><br/>'.$row['meta_atingida'].'<br/></td>';
				}
				
				$html .= '<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.',',', $row['desempenho']).'</td></tr>';		
			
				$meta_aux = $row['meta']; //armazena a meta corrente
				$ano_aux = $row['ano']; //armazena o ano corrente
		    }else{	
		    	// caso de mesmo objetivo, indicador, meta e iniciativa diferente	    	
		    	$html .= '<tr><td bgcolor="'.$color_tr.'"></td><td bgcolor="'.$color_tr_ind.'"></td><td bgcolor="'.$color_tr_ind.'"></td>';
		    	$html .='<td align="center" bgcolor="'.$color_tr_ind.'"></td>';
		    	$html .= '<td align="center" bgcolor="'.$color_tr_ind.'"></td>';
		    	$html .='<td align="center" bgcolor="'.$color_tr_ind.'"></td>';
		    	$html .= '<td bgcolor="'.$color_tr_ind.'"></td></td></tr>';
		    }
		}else{// Caso mesmo objetivo,mesmo indicador e meta é NULL	
			$html .= '<tr><td bgcolor="'.$color_tr.'"></td><td bgcolor="'.$color_tr_ind.'"></td><td bgcolor="'.$color_tr_ind.'"></td>';
			
			$html .= '<td align="center" bgcolor="'.$color_tr_ind.'"></td>';
			$html .= '<td align="center" bgcolor="'.$color_tr_ind.'"></td>';
			$html .= '<td align="center" bgcolor="'.$color_tr_ind.'"></td></tr>';
				
		}
	}else{//Primeira ocorrência do Objetivo com o indicador
		$iniciativa_linha = "";
		if($aux3==1){$color_tr_ind="#FFE4C4";$aux3=2;}else{$color_tr_ind="#FFFFE0";$aux3=1;}
			
		//Meta
		if($row['meta']!= NULL){
		
			if($aux4==1){$color_meta="#E1F5A9";$aux4=2;}else{$color_meta="#CEF6F5";$aux4=1;}
		
			if($row['metrica']=="Percentual"){//Quando a meta é em porcentagem
				if($ob_aux != $row['Objetivo']){//Caso o objetivo tenha mais de um indicador
					//Exibi o primeiro objetivo
					$html .= '<tr bgcolor="'.$color_tr.'"><td >'.$row['Objetivo'].'</td>';
					$html .= '<td bgcolor="'.$color_tr_ind.'">'.$row['nomeindicador'].'</td>';
					$html .= '<td bgcolor="'.$color_tr_ind.'">'.$row['calculo'].'</td>';
				}else{
					//Exibi o primeiro objetivo
					$html .= '<tr bgcolor="'.$color_tr.'"><td></td>';
					$html .= '<td bgcolor="'.$color_tr_ind.'">'.$row['nomeindicador'].'</td>';
					$html .= '<td bgcolor="'.$color_tr_ind.'">'.$row['calculo'].'</td>';
				}
				
				$html .= '<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.', ',', $row['meta']).'% '.'</td>';
				$html .= '<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.', ',', $row['meta_atingida']).'% '.'</td>';
				$html .= '<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.', ',', $row['desempenho']).'%</td>';
																			
				
				}else{						
					if($ob_aux != $row['Objetivo']){//Caso o objetivo tenha mais de um indicador
						//Exibi o primeiro objetivo
						$html .= '<tr bgcolor="'.$color_tr.'"><td >'.$row['Objetivo'].'</td>';
						$html .= '<td bgcolor="'.$color_tr_ind.'">'.$row['nomeindicador'].'</td>';
						$html .= '<td bgcolor="'.$color_tr_ind.'">'.$row['calculo'].'</td>';
					}else{
						//Exibi o primeiro objetivo
						$html .= '<tr bgcolor="'.$color_tr.'"><td></td>';
						$html .= '<td bgcolor="'.$color_tr_ind.'">'.$row['nomeindicador'].'</td>';
						$html .= '<td bgcolor="'.$color_tr_ind.'">'.$row['calculo'].'</td>';
					}
					$html .= '<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.', ',', $row['meta']).'</td>';
					$html .= '<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.', ',', $row['meta_atingida']).'</td>';
					$html .= '<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.',',', $row['desempenho']).'%</td>';
					
							
				}
				
				$aux_pGeral = $aux_pGeral+$row['desempenho'];
				$aux_qtdInd++;

				//Definindo Sinalizador
				if ($row['desempenho'] >= 90) {
					//$sinalizacao = 'green.png';
					//$titulo = 'Resultado esperado! A meta atingida superou 90% em relaÃ§Ã£o Ã  meta definida.';
					$html .= '<td align="center" bgcolor="'.$color_tr_ind.'"><img src="../../webroot/img/bullet-green.png"/></td></tr>';
				} else if ($row['desempenho'] > 60 && $row['desempenho'] < 90) {
					//$sinalizacao = 'yellow.png';
					//$titulo = 'AtenÃ§Ã£o! A meta atingida estÃ¡ entre 60% e 90% da meta.';
					$html .= '<td align="center" bgcolor="'.$color_tr_ind.'"><img src="../../webroot/img/bullet-yellow.png"/></td></tr>';
				} else {
					//$sinalizacao = 'red.png';
					//$titulo = 'Abaixo do esperado! A meta atingida Ã© inferior a 60% do esperado.';
					$html .= '<td align="center" bgcolor="'.$color_tr_ind.'"><img src="../../webroot/img/bullet-red.png"/></td></tr>';
				}
		}else{//Qunado a meta está como NULL
			if($aux4==1){$color_meta="#E1F5A9";$aux4=2;}else{$color_meta="#CEF6F5";$aux4=1;}

			if($ob_aux != $row['Objetivo']){//Caso o objetivo tenha mais de um indicador
			
				//Exibi o primeiro objetivo
				$html .= '<tr bgcolor="'.$color_tr.'"><td>'.$row['Objetivo'].'</td>';
				$html .= '<td bgcolor="'.$color_tr_ind.'">'.$row['nomeindicador'].'</td>';
				$html .= '<td bgcolor="'.$color_tr_ind.'">'.$row['calculo'].'</td>';
			
			}else{
				//Exibi o primeiro objetivo
				$html .= '<tr bgcolor="'.$color_tr.'"><td></td>';
				$html .= '<td bgcolor="'.$color_tr_ind.'">'.$row['nomeindicador'].'</td>';
				$html .= '<td bgcolor="'.$color_tr_ind.'">'.$row['calculo'].'</td>';
			}
			
			$html .= '<td align="center" bgcolor="'.$color_tr_ind.'"></td>';
			$html .= '<td align="center" bgcolor="'.$color_tr_ind.'"></td>';
			$html .= '<td align="center" bgcolor="'.$color_tr_ind.'"></td></tr>';									
		}			
	}
	//fim da primeira ocorrência
	$ob_aux = $row['Objetivo'];
	$ind_aux = $row['nomeindicador'];
	$meta_aux = $row['meta']; //armazena a meta corrente
	$ano_aux = $row['ano']; //armazena o ano corrente
}
?>
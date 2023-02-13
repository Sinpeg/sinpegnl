<?php
foreach ($rows_p as $row){
	
	//Caso haja mais de um indicador para o mesmo objetivo
	if($ind_aux == $row['nomeindicador'] && $ob_aux == $row['Objetivo']){			
		
	}else{//Primeira ocorrência do Objetivo com o indicador
				$iniciativa_linha = "";
				if($aux3==1){$color_tr_ind="#FFE4C4";$aux3=2;}else{$color_tr_ind="#FFFFE0";$aux3=1;}
					
				//Meta
				if($row['meta']!= NULL){
				
					if($row['meta'] == "0.00"){//Quando a Meta está definida como 0
					}else{															
					    if($row['desempenho']>=100){
					        $row['desempenho'] = 100;
						}
						
						$aux_pGeral = $aux_pGeral+$row['desempenho'];
						$aux_qtdInd++;
					}											
			}			
			
		}//fim da primeira ocorrência
		$ob_aux = $row['Objetivo'];
		$ind_aux = $row['nomeindicador'];
		$meta_aux = $row['meta']; //armazena a meta corrente
		$ano_aux = $row['ano']; //armazena o ano corrente
	
}
?>
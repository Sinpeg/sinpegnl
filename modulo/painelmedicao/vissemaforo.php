<?php  $total += ($meta_atingida[$i] == '-') ? (0) : ($meta_atingida[$i]);
	        $total_array[$i] = $total;
	        /** Procura a situação da meta* */
	        $meta = $objetometa->getMeta();
	        if ($meta != 0) {
	            if ($objetometa->getCumulativo() == "1") {
	                $valor_referencia = $total_array[$i];
	            } else {
	                $valor_referencia = ($meta_atingida[$i] == '-') ? (0) : ($meta_atingida[$i]);
	            }
	            
	            //$situacao = ($valor_referencia / $meta);
	            $situacao = ($objetoind->getInterpretacao() == 1)?$valor_referencia / $meta: $meta/$valor_referencia;
	            
	            if ($situacao >= 0.9) {
	                $sinalizacao[$i] = 'green.png';
	                $title[$i] = 'Resultado esperado! O resultado superou 90% em relação a meta';
	            } else if ($situacao > 0.6 && $situacao < 0.9) {
	                $sinalizacao[$i] = 'yellow.png';
	                $title[$i] = 'Atenção! O resultado pelo menos 60% da meta';
	            } else {
	                $sinalizacao[$i] = 'red.png';
	                $title[$i] = 'Abaixo do esperado! O resultado está inferior a 60% da meta';
	            }
	            
	        } else {
	            $sinalizacao[$i] = 'grey.png';
	            $title[$i] = 'Indicador indisponível';
	        }
	        
	        
	        
?>				 
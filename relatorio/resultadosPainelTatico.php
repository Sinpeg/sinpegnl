<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

//require_once '../classes/sessao.php';
require_once '../dao/PDOConnectionFactory.php';
require_once '../dao/painelTaticoDAO.php';
//require_once '../classes/validacao.php';
require_once '../vendors/dompdf/autoload.inc.php';

//Recupera os parâmetros enviados via GET
$cod_unidade = $_GET['unidade'];
$ano_base = $_GET['anoBase'];

//Definição da classe
$painelTatico = new PainelTaticoDAO();
$rows = $painelTatico->exportarResultadoPainel($ano_base, $cod_unidade);

//Realiza a busca dos resultados dos indicadores do PDI
$rows_pdi = $painelTatico->exportarResultadoPainelPDI($ano_base, $cod_unidade);

//Cabeçalho do relatório
$html ='<center><img src="../webroot/img/logo.jpg" height="50" width="50" />
		<h5>UNIVERSIDADE FEDERAL DO PARÁ<br/></h5><h5>
		<br/><br/> ANO BASE '.$ano_base.'<br/><br/>RESULTADOS DO PAINEL TÁTICO</h5>
		</center>';

//Cabeçalho da tabela
$html .= '<table style="font-size:13px;" border="0"  cellspacing="0"  rules="cols" width="100%">
		<tr bgcolor="#BDBDBD"><td></td><td align="center"  colspan="5"><b>INDICADOR</b></td><td bgcolor="#BDBDBD" align="center" colspan="3"><b>INICIATIVA</b></td></tr>
			
		<tr >
		<td align="center" width="15%" bgcolor="#DCDCDC" ><b>Objetivo</b></td>
		<td align="center" width="15%" bgcolor="#DCDCDC"><b>Indicador</b></td>
		<td align="center" width="18%" bgcolor="#DCDCDC"><b>F&oacute;rmula</b></td>
		<td align="center" width="" bgcolor="#DCDCDC"><b>Meta</b></td>
		<td align="center" width="" bgcolor="#DCDCDC"><b>resultado</b></td>
		<td align="center" width="" bgcolor="#DCDCDC"><b>Análise</b></td>
		<td align="center" width="" bgcolor="#DCDCDC"><b>Iniciativa</b></td>
		<td align="center" width="" bgcolor="#DCDCDC"><b>Situação</b></td>
		<td align="center" width="" bgcolor="#DCDCDC"><b>Fatores</b></td></tr>';

//Definindo variáveis auxiliares
$aux = 0;$ob_aux=''; $color_tr="";$aux2=1;
$ind_aux='';$color_tr_ind="";$aux3=1;
$color_meta=0;$color_meta2=0;$aux4=1;
$linha_aux=""; //Para construir a linha da meta do indicador a cada loop
$meta_aux = "";$ano_aux="";

//Exibição dos indicadores não PDI///////////////////////////////////////////
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
				
				$html .= '<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.', ',', $row['analiseCritica']).'</td>';		
				//Armazena as iniciativas
				$iniciativa_linha .= '- '.$row['nomeiniciativa'].'<br/>';			
				
				$html .='<td bgcolor="'.$color_tr_ind.'"><br/>'.$iniciativa_linha.'<br/></td></tr>';
				$meta_aux = $row['meta']; //armazena a meta corrente
				$ano_aux = $row['ano']; //armazena o ano corrente
		    }else{	
		    	// caso de mesmo objetivo, indicador, meta e iniciativa diferente	    	
		    	$html .= '<tr><td bgcolor="'.$color_tr.'"></td><td bgcolor="'.$color_tr_ind.'"></td><td bgcolor="'.$color_tr_ind.'"></td>';
		    	$html .='<td align="center" bgcolor="'.$color_tr_ind.'"></td>';
		    	$html .= '<td align="center" bgcolor="'.$color_tr_ind.'"></td>';
		    	$html .='<td align="center" bgcolor="'.$color_tr_ind.'"></td>';
		    	$html .= '<td bgcolor="'.$color_tr_ind.'"> - '.$row["nomeiniciativa"].'<br/><br/></td>
		    			<td bgcolor="'.$color_tr_ind.'"></td><td bgcolor="'.$color_tr_ind.'"></td></tr>';
		    }
		}else{// Caso mesmo objetivo,mesmo indicador e meta é NULL	
		
			$html .= '<tr><td bgcolor="'.$color_tr.'"></td><td bgcolor="'.$color_tr_ind.'"></td><td bgcolor="'.$color_tr_ind.'"></td>';
			
			for($i=$row['anoinicial'];$i<=$row['anofinal'];$i++){
					$html .='<td bgcolor="'.$color_tr_ind.'"></td>';
			}
			$html .='<td align="center" bgcolor="'.$color_tr_ind.'"></td>';
			$html .= '<td bgcolor="'.$color_tr_ind.'">'.$row["nomeiniciativa"].'<br/><br/></td></tr>';
				
		 }
		
	}else{//Primeira ocorrência do Objetivo com o indicador
				$iniciativa_linha = "";
				if($aux3==1){$color_tr_ind="#FFE4C4";$aux3=2;}else{$color_tr_ind="#FFFFE0";$aux3=1;}
					
				//Meta
				if($row['meta']!= NULL){
				
					if($aux4==1){$color_meta="#E1F5A9";$aux4=2;}else{$color_meta="#CEF6F5";$aux4=1;}
				
					if($row['metrica']=="Percentual"){
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
						$html .= '<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.', ',', $row['analiseCritica']).'</td>';
						$html .= '<td  bgcolor="'.$color_tr_ind.'"> - '.$row['nomeiniciativa'].'</td>';																
						
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
							$html .= '<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.', ',', $row['analiseCritica']).'</td>';
							$html .= '<td  bgcolor="'.$color_tr_ind.'"> - '.$row['nomeiniciativa'].'</td>';
									
						}
						
						$html .='<td align="center" bgcolor="'.$color_tr_ind.'">'.$row['situacao'].'</td>';
						
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
				
				for($i=$row['anoinicial'];$i<=$row['anofinal'];$i++){
					$html .='<td bgcolor="'.$color_tr_ind.'"></td>';
					if($i == $row['anofinal']){
						$html .='<td bgcolor="'.$color_tr_ind.'"></td>';
						$html .='<td bgcolor="'.$color_tr_ind.'"></td>';
						$html .='<td bgcolor="'.$color_tr_ind.'"> - '.$row["nomeiniciativa"].'</td></tr>';
					}
				}						
			}
				
			$fatores = "";
			if($row['pfcapacit'] == 1){ $fatores .="<br/>- Capacitação<br/><br/>";}
			if($row['pfrecti'] == 1){ $fatores .="- Recursos de Tecnologia da Informação<br/><br/>";}
			if($row['pfinfraf'] == 1){ $fatores .="- Infraestrutura Física<br/><br/>";}
			if($row['pfrecf'] == 1){ $fatores .="- Recursos financeiros<br/><br/>";}
			if($row['pfplanj'] == 1){ $fatores .="- Planejamento<br/><br/>";}
			if($row['outros'] != NULL){ $fatores .= '- Outros: '. $row['outros'].'<br/>';}
			$html .='<td bgcolor="'.$color_tr_ind.'">'.$fatores.'<br/></td></tr>';
			
			
		}//fim da primeira ocorrência
		$ob_aux = $row['Objetivo'];
		$ind_aux = $row['nomeindicador'];
		$meta_aux = $row['meta']; //armazena a meta corrente
		$ano_aux = $row['ano']; //armazena o ano corrente
	
}

//Início dos indicadores do PDI
//Definindo variáveis auxiliares
$aux = 0;$ob_aux=''; $color_tr="";$aux2=1;
$ind_aux='';$color_tr_ind="";$aux3=1;
$color_meta=0;$color_meta2=0;$aux4=1;
$linha_aux=""; //Para construir a linha da meta do indicador a cada loop
$meta_aux = "";$ano_aux="";

//Exibição dos indicadores PDI///////////////////////////////////////////
foreach ($rows_pdi as $row){
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

				$html .= '<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.', ',', $row['analiseCritica']).'</td>';
				//Armazena as iniciativas
				$iniciativa_linha .= '- '.$row['nomeiniciativa'].'<br/>';

				$html .='<td bgcolor="'.$color_tr_ind.'"><br/>'.$iniciativa_linha.'<br/></td></tr>';
				$meta_aux = $row['meta']; //armazena a meta corrente
				$ano_aux = $row['ano']; //armazena o ano corrente
			}else{
				// caso de mesmo objetivo, indicador, meta e iniciativa diferente
				$html .= '<tr><td bgcolor="'.$color_tr.'"></td><td bgcolor="'.$color_tr_ind.'"></td><td bgcolor="'.$color_tr_ind.'"></td>';
				$html .='<td align="center" bgcolor="'.$color_tr_ind.'"></td>';
				$html .= '<td align="center" bgcolor="'.$color_tr_ind.'"></td>';
				$html .='<td align="center" bgcolor="'.$color_tr_ind.'"></td>';
				$html .= '<td bgcolor="'.$color_tr_ind.'"> - '.$row["nomeiniciativa"].'<br/><br/></td>
		    			<td bgcolor="'.$color_tr_ind.'"></td><td bgcolor="'.$color_tr_ind.'"></td></tr>';
			}
		}else{// Caso mesmo objetivo,mesmo indicador e meta é NULL

			$html .= '<tr><td bgcolor="'.$color_tr.'"></td><td bgcolor="'.$color_tr_ind.'"></td><td bgcolor="'.$color_tr_ind.'"></td>';
				
			for($i=$row['anoinicial'];$i<=$row['anofinal'];$i++){
				$html .='<td bgcolor="'.$color_tr_ind.'"></td>';
			}
			$html .='<td align="center" bgcolor="'.$color_tr_ind.'"></td>';
			$html .= '<td bgcolor="'.$color_tr_ind.'">'.$row["nomeiniciativa"].'<br/><br/></td></tr>';

		}

	}else{//Primeira ocorrência do Objetivo com o indicador
		$iniciativa_linha = "";
		if($aux3==1){$color_tr_ind="#FFE4C4";$aux3=2;}else{$color_tr_ind="#FFFFE0";$aux3=1;}
			
		//Meta
		if($row['meta']!= NULL){

			if($aux4==1){$color_meta="#E1F5A9";$aux4=2;}else{$color_meta="#CEF6F5";$aux4=1;}

			if($row['metrica']=="Percentual"){
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

				$html .= '<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.', ',', $row['meta']).'% '.'</td>';
				$html .= '<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.', ',', $row['meta_atingida']).'% '.'</td>';
				$html .= '<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.', ',', $row['analiseCritica']).'</td>';
				$html .= '<td  bgcolor="'.$color_tr_ind.'"> - '.$row['nomeiniciativa'].'</td>';

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
				$html .= '<td align="center" bgcolor="'.$color_tr_ind.'">'.str_replace('.', ',', $row['analiseCritica']).'</td>';
				$html .= '<td  bgcolor="'.$color_tr_ind.'"> - '.$row['nomeiniciativa'].'</td>';
					
			}

			$html .='<td align="center" bgcolor="'.$color_tr_ind.'">'.$row['situacao'].'</td>';

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

			for($i=$row['anoinicial'];$i<=$row['anofinal'];$i++){
				$html .='<td bgcolor="'.$color_tr_ind.'"></td>';
				if($i == $row['anofinal']){
					$html .='<td bgcolor="'.$color_tr_ind.'"></td>';
					$html .='<td bgcolor="'.$color_tr_ind.'"></td>';
					$html .='<td bgcolor="'.$color_tr_ind.'"> - '.$row["nomeiniciativa"].'</td></tr>';
				}
			}
		}

		$fatores = "";
		if($row['pfcapacit'] == 1){ $fatores .="<br/>- Capacitação<br/><br/>";}
		if($row['pfrecti'] == 1){ $fatores .="- Recursos de Tecnologia da Informação<br/><br/>";}
		if($row['pfinfraf'] == 1){ $fatores .="- Infraestrutura Física<br/><br/>";}
		if($row['pfrecf'] == 1){ $fatores .="- Recursos financeiros<br/><br/>";}
		if($row['pfplanj'] == 1){ $fatores .="- Planejamento<br/><br/>";}
		if($row['outros'] != NULL){ $fatores .= '- Outros: '. $row['outros'].'<br/>';}
		$html .='<td bgcolor="'.$color_tr_ind.'">'.$fatores.'<br/></td></tr>';
			
			
	}//fim da primeira ocorrência
	$ob_aux = $row['Objetivo'];
	$ind_aux = $row['nomeindicador'];
	$meta_aux = $row['meta']; //armazena a meta corrente
	$ano_aux = $row['ano']; //armazena o ano corrente

}
//Fim indicadores do PDI

$html .= "</table>";
//echo $html;


// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream('Resultados_Painel_Tatico.pdf');

?>
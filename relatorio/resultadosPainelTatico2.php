<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

//require_once '../classes/sessao.php';
require_once '../dao/PDOConnectionFactory.php';
require_once '../dao/painelTaticoDAO.php';
require_once '../dao/unidadeDAO.php';
//require_once '../classes/validacao.php';
require_once '../vendors/dompdf/autoload.inc.php';

//Recupera os parâmetros enviados via GET
$cod_unidade = $_GET['unidade'];
$ano_base = $_GET['anoBase'];

//Buscar nome unidade por código
$unidadeDAO = new UnidadeDAO();
$rowUni = $unidadeDAO->unidadeporcodigo($cod_unidade);
foreach ($rowUni as $row){
	$nomeUnidade = $row['NomeUnidade'];
}
/*
//Definição da classe
$painelTatico = new PainelTaticoDAO();
$rows = $painelTatico->exportarResultadoPainel($ano_base, $cod_unidade);

//Realiza a busca dos resultados dos indicadores do PDI
$rows_pdi = $painelTatico->exportarResultadoPainelPDI($ano_base, $cod_unidade);
*/
$painelPDI = new PainelTaticoDAO();

if($cod_unidade != 938){
	//Banco de Dados e Query para obter o PDI
	$rows_pdi = $painelPDI->exportarResultadoPainelPDI($ano_base, $cod_unidade);
	// Comentado by Diogo $daoserv = new ServprocDAO();
   $rows = $painelPDI->exportarResultadoPainel($ano_base, $cod_unidade);
}else {
	$rows_pdi = $painelPDI->exportarResultadoPainelPDI938($ano_base);

}




//Cabeçalho do relatório
$html2 ='<center><img src="../webroot/img/logo.jpg" height="50" width="40" />
		<h6>UNIVERSIDADE FEDERAL DO PARÁ<br/>'.$nomeUnidade.'<br/>RESULTADOS DO PAINEL TÁTICO - ANO BASE '.$ano_base.'</h6>
		</center>';

//Definindo variáveis auxiliares
$aux = 0;$ob_aux=''; $color_tr="";$aux2=1;
$ind_aux='';$color_tr_ind="";$aux3=1;
$color_meta=0;$color_meta2=0;$aux4=1;
$linha_aux=""; //Para construir a linha da meta do indicador a cada loop
$meta_aux = "";$ano_aux="";

//Definindo contadores
$count_obj = 0;
$count_ind = 0;

if ($cod_unidade!=938){

$html2 .= "<table width='100%'><tr bgcolor='#ccc' style='text-align: center;'><td><h4>INDICADORES DO PDU</h4></td></tr></table>";
$html2 .= "<table>";
//Exibição dos indicadores não PDI///////////////////////////////////////////
foreach ($rows as $row){
	// Define o conteudo
	//Caso haja mais de um indicador para o mesmo objetivo
	if($ind_aux == $row['nomeindicador'] && $ob_aux == $row['Objetivo']){
		//Meta
		if($row['meta']!= NULL){
		    if($row['ano'] != $ano_aux && $row['meta'] != $meta_aux){
				if($row['metrica']=="Percentual"){

					$html2 .= "<br/><b>Meta: </b>".str_replace('.', ',', $row['meta']).'% ';
					if($row['meta_atingida'] == ""){
						$html2 .= "<br/><b>Resultado: </b>";
					}else{
						$html2 .= "<br/><b>Resultado: </b>".str_replace('.', ',', $row['meta_atingida']).'% ';
					}

				}else{

					$html2 .= "<br/><b>Meta: </b>".str_replace('.', ',', $row['meta']).'% ';
					$html2 .= "<br/><b>Resultado: </b>".str_replace('.', ',', $row['meta_atingida']);

				}

				$html2 .= "<br/><b>Análise Crítica: </b><p align='justify'>".$row['analiseCritica']."</p>";

				$iniciativa_linha .= '- '.$row['nomeiniciativa'].'<br/>';

				$count_ini++;
				$html2 .= "</td></tr><tr><td><b>Iniciativa ".$count_obj.".".$count_ind.".".$count_ini.": </b>".$row['nomeiniciativa'];

				$meta_aux = $row['meta']; //armazena a meta corrente
				$ano_aux = $row['ano']; //armazena o ano corrente
		    }else{
		    	// caso de mesmo objetivo, indicador, meta e iniciativa diferente
		    	$count_ini++;
		    	$html2 .= "</td></tr><tr><td><b>Iniciativa ".$count_obj.".".$count_ind.".".$count_ini.": </b>".$row['nomeiniciativa'];
		    }
		}else{// Caso mesmo objetivo,mesmo indicador e meta é NULL

			$count_ini++;
			$html2 .= "</td></tr><tr><td><b>Iniciativa ".$count_obj.".".$count_ind.".".$count_ini.": </b>".$row['nomeiniciativa'];
		 }

		 $html2 .= "<br/><b>Situação: </b>".$row['situacao'];
		 $fatores = "";
		 if($row['pfcapacit'] == 1){ $fatores .="<br/>- Capacitação";}
		 if($row['pfrecti'] == 1){ $fatores .="<br/>- Recursos de Tecnologia da Informação";}
		 if($row['pfinfraf'] == 1){ $fatores .="<br/>- Infraestrutura Física<br/>";}
		 if($row['pfrecf'] == 1){ $fatores .="<br/>- Recursos financeiros<br/>";}
		 if($row['pfplanj'] == 1){ $fatores .="<br/>- Planejamento<br/>";}
		 if($row['outros'] != NULL){ $fatores .= '<br/>- Outros: '. $row['outros'];}
		 //$html .='<td bgcolor="'.$color_tr_ind.'">'.$fatores.'<br/></td></tr>';
		 $html2 .= "<br/><b>Fatores: </b>".$fatores."</td></tr>";

	}else{//Primeira ocorrência do Objetivo com o indicador
				$iniciativa_linha = "";

				//Meta
				if($row['meta']!= NULL){

					if($row['metrica']=="Percentual"){
						if($ob_aux != $row['Objetivo']){//Caso o objetivo tenha mais de um indicador
							$count_obj++;
							$count_ind = 1;
							//Exibi o primeiro objetivo
							$html2 .= "</table><br/><br/><h4> Objetivo ".$count_obj.": ".$row['Objetivo']."</h4>";
							$html2 .= "<br/><table border=1 cellspacing=0 cellpadding=2  width='100%'><tr><td><b> Indicador ".$count_obj.".".$count_ind.": </b>".$row['nomeindicador'];
							$html2 .= "<br/><b>Fórmula: </b>".$row['calculo'];

							$interpretacao="";
							if($row['interpretacao'] == 1){
								$interpretacao = "Quanto maior, melhor.";
							}else if($row['interpretacao'] == 2){
								$interpretacao = "Quanto menor, melhor.";
							}
							$html2 .= "<br/><b>Interpretação: </b>".$interpretacao;;

						}else{
							$count_ind++;
							$html2 .= "</table><br/><table border=1 cellspacing=0 cellpadding=2  width='100%'><tr><td><b> Indicador ".$count_obj.".".$count_ind.": </b>".$row['nomeindicador'];
							$html2 .= "<br/><b>Fórmula: </b>".$row['calculo'];

							$interpretacao="";
							if($row['interpretacao'] == 1){
								$interpretacao = "Quanto maior, melhor.";
							}else if($row['interpretacao'] == 2){
								$interpretacao = "Quanto menor, melhor.";
							}
							$html2 .= "<br/><b>Interpretação: </b>".$interpretacao;;
						}

						$html2 .= "<br/><b>Meta: </b>".str_replace('.', ',', $row['meta']).'%';
						if($row['meta_atingida'] == ""){
							$html2 .= "<br/><b>Resultado: </b>";
						}else{
							$html2 .= "<br/><b>Resultado: </b>".str_replace('.', ',', $row['meta_atingida']).'% ';
						}
						$html2 .= "<br/><b>Análise Crítica: </b><p align='justify'>".$row['analiseCritica']."</p> ";
						$count_ini=1;
						$html2 .= "</td></tr><tr><td><b>Iniciativa ".$count_obj.".".$count_ind.".".$count_ini.": </b>".$row['nomeiniciativa'];


						}else{//Metrica absoluta
							if($ob_aux != $row['Objetivo']){//Caso o objetivo tenha mais de um indicador

								$count_obj++;
								$count_ind = 1;
								//Exibi o primeiro objetivo
								$html2 .= "</table><br/><br/><h4> Objetivo ".$count_obj.": ".$row['Objetivo']."</h4>";
								$html2 .= "<br/><table border=1 cellspacing=0 cellpadding=2  width='100%'><tr><td><b> Indicador ".$count_obj.".".$count_ind.": </b>".$row['nomeindicador'];
								$html2 .= "<br/><b>Fórmula: </b>".$row['calculo'];

								$interpretacao="";
								if($row['interpretacao'] == 1){
									$interpretacao = "Quanto maior, melhor.";
								}else if($row['interpretacao'] == 2){
									$interpretacao = "Quanto menor, melhor.";
								}
								$html2 .= "<br/><b>Interpretação: </b>".$interpretacao;;

							}else{
								//Exibi o primeiro objetivo
								$count_ind++;
								$html2 .= "</table><br/><table border=1 cellspacing=0 cellpadding=2  width='100%'><tr><td><b> Indicador ".$count_obj.".".$count_ind.": </b>".$row['nomeindicador'];
								$html2 .= "<br/><b>Fórmula: </b>".$row['calculo'];

								$interpretacao="";
								if($row['interpretacao'] == 1){
									$interpretacao = "Quanto maior, melhor.";
								}else if($row['interpretacao'] == 2){
									$interpretacao = "Quanto menor, melhor.";
								}
								$html2 .= "<br/><b>Interpretação: </b>".$interpretacao;;

							}
							$html2 .= "<br/><b>Meta: </b>".str_replace('.', ',', $row['meta']).' ';
							if($row['meta_atingida'] == ""){
								$html2 .= "<br/><b>Resultado: </b>";
							}else{
								$html2 .= "<br/><b>Resultado: </b>".str_replace('.', ',', $row['meta_atingida']).' ';
							}
							$html2 .= "<br/><b>Análise Crítica: </b><p align='justify'>".$row['analiseCritica']."</p>";
							$count_ini=1;
							$html2 .= "</td></tr><tr><td><b>Iniciativa ".$count_obj.".".$count_ind.".".$count_ini.": </b>".$row['nomeiniciativa'];

						}

			}else{//Qunado a meta está como NULL
				//if($aux4==1){$color_meta="#E1F5A9";$aux4=2;}else{$color_meta="#CEF6F5";$aux4=1;}

				if($ob_aux != $row['Objetivo']){//Caso o objetivo tenha mais de um indicador

					$count_obj++;
					$count_ind = 1;
					//Exibi o primeiro objetivo
					$html2 .= "</table><br/><br/><h4> Objetivo ".$count_obj.": ".$row['Objetivo']."</h4>";
					$html2 .= "<br/><table border=1 cellspacing=0 cellpadding=2  width='100%'><tr><td><b> Indicador ".$count_obj.".".$count_ind.": </b>".$row['nomeindicador'];
					$html2 .= "<br/><b>Fórmula: </b>".$row['calculo'];

					$interpretacao="";
					if($row['interpretacao'] == 1){
						$interpretacao = "Quanto maior, melhor.";
					}else if($row['interpretacao'] == 2){
						$interpretacao = "Quanto menor, melhor.";
					}
					$html2 .= "<br/><b>Interpretação: </b>".$interpretacao;;

				}else{

					$count_ind++;
					$html2 .= "</table><br/><br/><b> Indicador ".$count_obj.".".$count_ind.": </b>".$row['nomeindicador'];
					$html2 .= "<br/><b>Fórmula: </b>".$row['calculo'];

					$interpretacao="";
					if($row['interpretacao'] == 1){
						$interpretacao = "Quanto maior, melhor.";
					}else if($row['interpretacao'] == 2){
						$interpretacao = "Quanto menor, melhor.";
					}
					$html2 .= "<br/><b>Interpretação: </b>".$interpretacao;;

				}

				for($i=$row['anoinicial'];$i<=$row['anofinal'];$i++){
					if($i == $row['anofinal']){

						$count_ini++;
						$html2 .= "<b>Iniciativa ".$count_obj.".".$count_ind.".".$count_ini.": </b>".$row['nomeiniciativa'];

					}
				}
			}
			$html2 .= "	<br/><b>Situação: </b>".$row['situacao'];
			$fatores = "";
			if($row['pfcapacit'] == 1){ $fatores .="<br/>- Capacitação";}
			if($row['pfrecti'] == 1){ $fatores .="<br/>- Recursos de Tecnologia da Informação";}
			if($row['pfinfraf'] == 1){ $fatores .="<br/>- Infraestrutura Física";}
			if($row['pfrecf'] == 1){ $fatores .="<br/>- Recursos financeiros";}
			if($row['pfplanj'] == 1){ $fatores .="<br/>- Planejamento";}
			if($row['outros'] != NULL){ $fatores .= '<br/>- Outros: '. $row['outros'];}

			$html2 .= "<br/><b>Fatores: </b>".$fatores."</td></tr>";


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

//Definindo contadores
//$count_obj = 0;
//$count_ind = 0;

$html2 .= "</table>";

}//if 938


$html2 .= "<br/><br/><table width='100%'><tr bgcolor='#ccc' style='text-align: center;'><td><h4>INDICADORES DO PDI</h4></td></tr></table>";
$html2.="<table>";
//Exibição dos indicadores PDI///////////////////////////////////////////
foreach ($rows_pdi as $row){
	//echo $row['nomeindicador']."xxxxxxxxxxxxxxxxxxx";

	//Caso haja mais de um indicador para o mesmo objetivo
	if($ind_aux == $row['nomeindicador'] && $ob_aux == $row['Objetivo']){

		//Meta
		if($row['meta']!= NULL){
			if($row['ano'] != $ano_aux && $row['meta'] != $meta_aux){
				if($row['metrica']=="Percentual"){

					$html2 .= "<br/><b>Meta: </b>".str_replace('.', ',', $row['meta']).'% ';
					if($row['meta_atingida'] == ""){
						$html2 .= "<br/><b>Resultado: </b>";
					}else{
						$html2 .= "<br/><b>Resultado: </b>".str_replace('.', ',', $row['meta_atingida']).'% ';
					}

				}else{

					$html2 .= "<br/><b>Meta: </b>".str_replace('.', ',', $row['meta']).'% ';
					$html2 .= "<br/><b>Resultado: </b>".str_replace('.', ',', $row['meta_atingida']);

				}

				$html2 .= "<br/><b>Análise Crítica: </b><p align='justify'>".$row['analiseCritica']."</p>";

				$count_ini++;
				$html2 .= "</td></tr><tr><td><b>Iniciativa ".$count_obj.".".$count_ind.".".$count_ini.": </b>".$row['nomeiniciativa'];

				$meta_aux = $row['meta']; //armazena a meta corrente
				$ano_aux = $row['ano']; //armazena o ano corrente
			}else{
				// caso de mesmo objetivo, indicador, meta e iniciativa diferente
				$count_ini++;
				$html2 .= "</td></tr><tr><td><b>Iniciativa ".$count_obj.".".$count_ind.".".$count_ini.": </b>".$row['nomeiniciativa'];
			}
		}else{// Caso mesmo objetivo,mesmo indicador e meta é NULL

			$count_ini++;
			$html2 .= "</td></tr><tr><td><b>Iniciativa ".$count_obj.".".$count_ind.".".$count_ini.": </b>".$row['nomeiniciativa'];

		}

		$html2 .= "<br/><b>Situação: </b>".$row['situacao'];
		$fatores = "";
		if($row['pfcapacit'] == 1){ $fatores .="<br/>- Capacitação";}
		if($row['pfrecti'] == 1){ $fatores .="<br/>- Recursos de Tecnologia da Informação";}
		if($row['pfinfraf'] == 1){ $fatores .="<br/>- Infraestrutura Física";}
		if($row['pfrecf'] == 1){ $fatores .="<br/>- Recursos financeiros";}
		if($row['pfplanj'] == 1){ $fatores .="<br/>- Planejamento";}
		if($row['outros'] != NULL){ $fatores .= '<br/>- Outros: '. $row['outros'];}
		//$html .='<td bgcolor="'.$color_tr_ind.'">'.$fatores.'<br/></td></tr>';
		$html2 .= "<br/><b>Fatores: </b>".$fatores."</td></tr>";

	}else{//Primeira ocorrência do Objetivo com o indicador
		$iniciativa_linha = "";

		//Meta
		if($row['meta']!= NULL){

			if($row['metrica']=="Percentual"){
				if($ob_aux != $row['Objetivo']){//Caso o objetivo tenha mais de um indicador

					//Exibi o primeiro objetivo
					$count_obj++;
					$count_ind = 1;
					//Exibi o primeiro objetivo
					$html2 .= "</table><br/><br/><h4> Objetivo ".$count_obj.": ".$row['Objetivo']."</h4>";
					$html2 .= "<br/><table border=1 cellspacing=0 cellpadding=2  width='100%'><tr><td><b> Indicador ".$count_obj.".".$count_ind.": </b>".$row['nomeindicador'];
					$html2 .= "<br/><b>Fórmula: </b>".$row['calculo'];

					$interpretacao="";
					if($row['interpretacao'] == 1){
						$interpretacao = "Quanto maior, melhor.";
					}else if($row['interpretacao'] == 2){
						$interpretacao = "Quanto menor, melhor.";
					}
					$html2 .= "<br/><b>Interpretação: </b>".$interpretacao;;
				}else{
					//Exibi o primeiro objetivo
					$count_ind++;
					$html2 .= "</table><br/><table border=1 cellspacing=0 cellpadding=2  width='100%'><tr><td><b> Indicador ".$count_obj.".".$count_ind.": </b>".$row['nomeindicador'];
					$html2 .= "<br/><b>Fórmula: </b>".$row['calculo'];

					$interpretacao="";
					if($row['interpretacao'] == 1){
						$interpretacao = "Quanto maior, melhor.";
					}else if($row['interpretacao'] == 2){
						$interpretacao = "Quanto menor, melhor.";
					}
					$html2 .= "<br/><b>Interpretação: </b>".$interpretacao;;
				}

				$html2 .= "<br/><b>Meta: </b>".str_replace('.', ',', $row['meta']).'% ';
				if($row['meta_atingida'] == ""){
					$html2 .= "<br/><b>Resultado: </b>";
				}else{
					$html2 .= "<br/><b>Resultado: </b>".str_replace('.', ',', $row['meta_atingida']).'% ';
				}
				$html2 .= "<br/><b>Análise Crítica: </b><p align='justify'>".$row['analiseCritica']."</p>";
				$count_ini=1;
				$html2 .= "</td></tr><tr><td><b>Iniciativa ".$count_obj.".".$count_ind.".".$count_ini.": </b>".$row['nomeiniciativa'];

			}else{
				if($ob_aux != $row['Objetivo']){//Caso o objetivo tenha mais de um indicador

					//Exibi o primeiro objetivo
					$count_obj++;
					$count_ind = 1;
					//Exibi o primeiro objetivo
					$html2 .= "</table><br/><br/><h4> Objetivo ".$count_obj.": ".$row['Objetivo']."</h4>";
					$html2 .= "<br/><table border=1 cellspacing=0 cellpadding=2  width='100%'><tr><td><b> Indicador ".$count_obj.".".$count_ind.": </b>".$row['nomeindicador'];
					$html2 .= "<br/><b>Fórmula: </b>".$row['calculo'];

					$interpretacao="";
					if($row['interpretacao'] == 1){
						$interpretacao = "Quanto maior, melhor.";
					}else if($row['interpretacao'] == 2){
						$interpretacao = "Quanto menor, melhor.";
					}
					$html2 .= "<br/><b>Interpretação: </b>".$interpretacao;;
				}else{
					//Exibi o primeiro objetivo
					$count_ind++;
					$html2 .= "</table><br/><table border=1 cellspacing=0 cellpadding=2  width='100%'><tr><td><b> Indicador ".$count_obj.".".$count_ind.": </b>".$row['nomeindicador'];
					$html2 .= "<br/><b>Fórmula: </b>".$row['calculo'];

					$interpretacao="";
					if($row['interpretacao'] == 1){
						$interpretacao = "Quanto maior, melhor.";
					}else if($row['interpretacao'] == 2){
						$interpretacao = "Quanto menor, melhor.";
					}
					$html2 .= "<br/><b>Interpretação: </b>".$interpretacao;;
				}

				$html2 .= "<br/><b>Meta: </b>".str_replace('.', ',', $row['meta']).'% ';
				if($row['meta_atingida'] == ""){
					$html2 .= "<br/><b>Resultado: </b>";
				}else{
					$html2 .= "<br/><b>Resultado: </b>".str_replace('.', ',', $row['meta_atingida']).'% ';
				}
				$html2 .= "<br/><b>Análise Crítica: </b><p align='justify'>".$row['analiseCritica']."</p>";
				$count_ini=1;
				$html2 .= "</td></tr><tr><td><b>Iniciativa ".$count_obj.".".$count_ind.".".$count_ini.": </b>".$row['nomeiniciativa'];
			}
			//$html2 .= "<br/><b>Situação: </b>".$row['situacao'];

		}else{//Qunado a meta está como NULL
			if($ob_aux != $row['Objetivo']){//Caso o objetivo tenha mais de um indicador

				$count_obj++;
				$count_ind = 1;
				//Exibi o primeiro objetivo
				$html2 .= "</table><br/><br/><h4> Objetivo ".$count_obj.": ".$row['Objetivo']."</h4>";
				$html2 .= "<br/><table border=1 cellspacing=0 cellpadding=2  width='100%'><tr><td><b> Indicador ".$count_obj.".".$count_ind.": </b>".$row['nomeindicador'];
				$html2 .= "<br/><b>Fórmula: </b>".$row['calculo'];

				$interpretacao="";
				if($row['interpretacao'] == 1){
					$interpretacao = "Quanto maior, melhor.";
				}else if($row['interpretacao'] == 2){
					$interpretacao = "Quanto menor, melhor.";
				}
				$html2 .= "<br/><b>Interpretação: </b>".$interpretacao;;

			}else{

				//Exibi o primeiro objetivo
				$count_ind++;
				$html2 .= "</table><br/><table border=1 cellspacing=0 cellpadding=2  width='100%'><tr><td><b> Indicador ".$count_obj.".".$count_ind.": </b>".$row['nomeindicador'];
				$html2 .= "<br/><b>Fórmula: </b>".$row['calculo'];

				$interpretacao="";
				if($row['interpretacao'] == 1){
					$interpretacao = "Quanto maior, melhor.";
				}else if($row['interpretacao'] == 2){
					$interpretacao = "Quanto menor, melhor.";
				}
				$html2 .= "<br/><b>Interpretação: </b>".$interpretacao;;
			}

			for($i=$row['anoinicial'];$i<=$row['anofinal'];$i++){
				$html .='<td bgcolor="'.$color_tr_ind.'"></td>';
				if($i == $row['anofinal']){

					$count_ini++;
					$html2 .= "</td></tr><tr><td><b>Iniciativa ".$count_obj.".".$count_ind.".".$count_ini.": </b>".$row['nomeiniciativa'];
				}
			}
		}
		$html2 .= "<br/><b>Situação: </b>".$row['situacao'];
		$fatores = "";
		if($row['pfcapacit'] == 1){ $fatores .="<br/>- Capacitação";}
		if($row['pfrecti'] == 1){ $fatores .="<br/>- Recursos de Tecnologia da Informação";}
		if($row['pfinfraf'] == 1){ $fatores .="<br/>- Infraestrutura Física";}
		if($row['pfrecf'] == 1){ $fatores .="<br/>- Recursos financeiros";}
		if($row['pfplanj'] == 1){ $fatores .="<br/>- Planejamento";}
		if($row['outros'] != NULL){ $fatores .= '<br/>- Outros: '. $row['outros'];}
		//$html .='<td bgcolor="'.$color_tr_ind.'">'.$fatores.'<br/></td></tr>';
		$html2 .= "<br/><b>Fatores: </b>".$fatores."</td></tr>";

	}//fim da primeira ocorrência

	$ob_aux = $row['Objetivo'];
	$ind_aux = $row['nomeindicador'];
	$meta_aux = $row['meta']; //armazena a meta corrente
	$ano_aux = $row['ano']; //armazena o ano corrente

}
//Fim indicadores do PDI

$html2 .= "</table>";
//echo $html2; // descomentar para teste


// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

$dompdf->loadHtml($html2);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream('Resultados_indicadores_'.$nomeUnidade.'.pdf');

?>
<?php 

$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
/*if (!$aplicacoes[4]) {
    header("Location:index.php");
    exit();
}*/
$daomapa = new MapaDAO();
$daomapaindi = new MapaIndicadorDAO();
$daoindiciniciativa = new IndicIniciativaDAO();

$arrayMapaIndicadores = null;

$triger = 1;
$cont=0;
foreach ($_POST as $kay => $value){
	if($triger==1){
		$codIniciativa = $value;
		$triger = 0;
	}else{
		
		if($kay != 'acao'){
			$arrayMapaIndicadores[$cont++] = $value;	
		}
		
	}
}




// $cont = 1;
// foreach ($_POST as $kay => $value){
// 	$perspec = 'idPerspectiva'.$cont;
// 	$object = 'idObjetivo'.$cont;
	
// 	if($perspec == $kay){
// 		$eleperspectiva = $value;
// 	}
	
// 	if ($object == $kay){
// 		$eleobjetivo = $value;
		
// 		$arrayPespObjet['perspectiva'] = $eleperspectiva;
// 		$arrayPespObjet['objetivo'] = $eleobjetivo;
		
// 		$arrayPespObjetivos[$cont] = $arrayPespObjet;
		
// 		$cont = $cont + 1;
// 	}
	
// }

// foreach ($arrayPespObjetivos as $kay => $value){
// 	$cont = 1;
// 	foreach ($_POST as $kay2 => $value2){
// 		$indicador = "codIndicador".$kay."-".$cont;
// 		if($indicador == $kay2){
// 			$arrayIndicad[$kay2] = $value2; 
// 			$cont++;
// 		}
		
// 	}
// 			$arrayPespObjetivos[$kay]['indicadores'] = $arrayIndicad;
// 			$arrayIndicad = null;
// }

// // echo "<pre><br>"; var_dump($arrayPespObjetivos);die;

// $i = 0;
// foreach ($arrayPespObjetivos as $arrayPerspObjet){
	
// 	foreach ($arrayPerspObjet['indicadores'] as $indic ){

	
// 	echo "codIndicador:";
// 	echo $indic['codigo'];
// 	echo " ";
// 	echo "perspectiva:";
// 	echo $arrayPerspObjet["perspectiva"];
// 	echo " ";
// 	echo "objetivo:";
// 	echo $arrayPerspObjet["objetivo"];
// 	echo " ";
// 	echo "unidade:";
// 	echo $_POST['idUnidade'];
// 	echo " ";
// 	echo "documento";
// 	echo $_POST['codDocumento'];
// 	echo "<br>";
			
		
		
	
// 	$arraymapa = $daomapa->buscaMapaObjetivoPerspectivaPorDocumento($arrayPerspObjet["objetivo"], $arrayPerspObjet["perspectiva"], $_POST['codDocumento'])->fetch();
// 	$codmapa = $arraymapa['Codigo'];
	
// 	$arrayMapaIndicador = $daomapaindi->buscaMapaIndicadorporMI($codmapa, $indic['codigo'])->fetch();
	
// // 	echo "<pre><br>"; var_dump($arrayMapaIndicador);
	
	
// 	$iniciativa = new Iniciativa();
// 	$iniciativa->setCodIniciativa($_POST['codIniciativa']);
	
	
// 	$aplicacoes = $sessao->getAplicacoes();
	
// 	$mapaindicador = new Mapaindicador();
// 	$mapaindicador->setCodigo($arrayMapaIndicador['codigo']);
	
// 	$objindicini = new IndicIniciativa();
// 	$objindicini->setIniciativa($iniciativa);
// 	$objindicini->setMapaindicador($mapaindicador);
	
// 	$arrayIndicIni[$i++]=$objindicini;

// 	}
// }

$action = $_POST['acao'];
// $action = $_POST['acao'];


if($action =="Vincular"){
	
	//parei aqui
	$daoindiciniciativa->regidtraIndicIniciativas($arrayMapaIndicadores,$codIniciativa,$sessao->getAnobase());
	Flash::addFlash("Iniciativa cadastrada com sucesso.");
	Utils::redirect('iniciativa', 'listaIniciativa');
}

$codIniciativa=$_POST['codIniciativa'];

if($action =="Concluir" && is_numeric($codIniciativa)){
	$bdmapaind = null;
	$qindicini = $daoindiciniciativa->listaPorIniciativa($codIniciativa,$sessao->getAnobase());//pega indicadores vinculados a iniciativa dada
	$cont = 0;
	
	foreach ($qindicini as $indicini){
									
		$cont++;
		$bdmapaind[$cont] = $indicini['codMapaInd'];
	}
	
	if($bdmapaind == null){ $bdmapaind  = array(); }
	if($arrayMapaIndicadores == null){ $arrayMapaIndicadores = array(); }
	
$excluidos = array_diff ( $bdmapaind , $arrayMapaIndicadores );
$adicionados = array_diff ($arrayMapaIndicadores, $bdmapaind);

$todosvinculos=array_merge($excluidos,$adicionados);

//$daoindiciniciativa->regidtraIndicIniciativas(D,$codIniciativa);
//$daoindiciniciativa->deletaIndicIniciativas($excluidos, $codIniciativa);

$daoindiciniciativa->vincularIndicIniciativas($todosvinculos, $codIniciativa, $anobase);
	Flash::addFlash("Vínculo atualizado com sucesso.");
	Utils::redirect('iniciativa', 'listaIniciativa');
}

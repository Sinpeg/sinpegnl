<?php

class IniciativaController{
	public function retornaIndicIni($codUnidade, $codPerspectiva, $codObjetivo, $codDocumento, $codIndicador){
		
		session_start();
		$sessao = new Sessao();
		$sessao = $_SESSION['sessao'];
		$daomapa = new MapaDAO();
		$daomapaindi = new MapaIndicadorDAO();
		$daoindiciniciativa = new IndicIniciativaDAO();
		
		$arraymapa = $daomapa->buscaMapaObjetivoPerspectivaPorDocumento($codObjetivo, $codPerspectiva, $codDocumento)->fetch();
		$codmapa = $arraymapa['Codigo'];
		
		$arrayMapaIndicador = $daomapaindi->buscaMapaIndicadorporMI($codmapa, $codIndicador)->fetch();
		
		$daoiniciativa = new IniciativaDAO();
		$arrayiniciativa = $daoiniciativa->buscaIniciativaUnidade($codUnidade)->fetch();
		
		$iniciativa = new Iniciativa();
		$iniciativa->setCodIniciativa($arrayiniciativa['codIniciativa']);
		
		$mapaindicador = new MapaIndicador();
		$mapaindicador->setCodigo($arrayMapaIndicador['codigo']);
		
		$objindicini = new IndicIniciativa();
		$objindicini->setIniciativa($iniciativa);
		$objindicini->setMapaindicador($mapaindicador);
		
		$result = $daoindiciniciativa->iniciativaPorIniciativaEMapIndicador($indicinciativa)->fetch();
		return $result;
		
	}
} 
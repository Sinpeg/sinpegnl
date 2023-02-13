<?php
require_once('dao/blofertaDAO.php');
require_once('classes/Bloferta.php');
require_once('classes/localoferta.php');
require_once('classes/bibliemec.php');


class FachOferta{
	
	
	
	function trataOferta($idbibliemec,$vloferta){
		$cont=0;
	
		$daoblo=new BlofertaDAO();
		//DELETA os locais de oferta de idbibliemec
		$daoblo->deleta($idbibliemec);
		$bli=new Bibliemec();
		$bli->setIdBibliemec($idbibliemec);
		$blo=array();
		if ((!empty($vloferta)) && (is_numeric($idbibliemec))) {
			foreach($vloferta as $i=>$vloferta) {
				$cont++;
				$lo=new Localoferta();
				$lo->setIdLocal($i);
				$bli->criaBloferta($lo);
				$blo[$cont]=$bli->getBloferta();
			}
			$bli->setBlofertas($blo);
			$daoblo->insere($bli);
	
		}else return false;
		return true;
	
	}
}
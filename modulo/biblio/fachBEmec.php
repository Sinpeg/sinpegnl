<?php

require_once('dao/biblioEmecDAO.php');
require_once('classes/bibliemec.php');	


class FachBEmec{
//obt�m dados do emec



public function retornaBEmec($codunidade){
    $daobe = new BiblioEmecDAO();

   //verifica se a unidade biblio j� est� associada � biblioteca do emec
   $rows = $daobe->buscaCodEmecBiblioUnidade($codunidade);
   $be=null;

	foreach ($rows as $row) {
	$be = new Bibliemec();
		$be->setIdBibliemec( $row['idBibliemec']);
		$be->setTipo( $row['tipo']);
        $be->setNome( $row['nome']);
        if ( !is_null($row['sigla']) ){
        	$be->setSigla( $row['sigla']);
        }
        $be->setCodEmec( $row['codEmec']);
        
        if ( !is_null($row['idunidade']) ){
           $unidade=new Unidade();
           $unidade->setCodunidade($codunidade);
        }
                    
	}
	return $be;

}
}
?>

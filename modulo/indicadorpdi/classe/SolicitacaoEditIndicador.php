<?php

/**
 * Enter description here ...
 * @author carla
 * Esta classe é criada por mapaindicador
 */
class SolicitacaoEditIndicador extends Solicitacao{

   
    private $mapaindicador;
    private $indicador;
    
        
//    private $tipoColeta;

    public function __construct() {
        

    }
  
    
  public function setIndicador(Indicador $indicador) {
        $this->indicador = $indicador;
    }

    public function getIndicador() {
        return $this->indicador;
    }
    
    public function setMapaIndicador($mapaIndicador) {
    	$this->mapaindicador = $mapaIndicador;
    }
    
    public function getMapaIndicador() {
    	return $this->mapaindicador;
    }
    
     
   
   
    

  

}
?>
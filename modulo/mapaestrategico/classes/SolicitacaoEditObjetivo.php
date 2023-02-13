<?php

/*
 * Esta classe é criada por mapa/documento
 */
class SolicitacaoEditObjetivo extends Solicitacao{
    private $mapa;
    private $objetivo;
 
 
    
    public function __construct() {
        

    }

    
    public function setMapa(Mapa $mapa) {
    	$this->mapa = $mapa;
    }
    
    public function getMapa() {
    	return $this->mapa;
    }
    
    public function setObjetivo(objetivo $objetivo) {
        $this->objetivo = $objetivo;
    }

    public function getObjetivo() {
        return $this->objetivo;
    }
    
  
    
    
  
}


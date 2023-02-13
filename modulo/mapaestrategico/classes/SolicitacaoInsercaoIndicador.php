<?php 
/*
 * Esta classe é criada por mapa/documento
 */
class SolicitacaoInsereIndicador extends Solicitacao{
    private $mapa;
    private $objetivo;
    private $indicador;
 
    public function __construct() {        

    }
    
    public function setMapa($mapa) {
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
    
    public function setIndicador(Indicador $indicador) {
    	$this->indicador = $indicador;
    }
    
    public function getIndicador() {
    	return $this->indicador;
    }
  
}


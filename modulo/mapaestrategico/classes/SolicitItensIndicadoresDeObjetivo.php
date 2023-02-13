<?php

/*
 * Esta classe é criada por mapa/documento
 */
class SolicitItensIndicadoresDeObjetivo {
	private $codigo;
    private $solicitacao;
    private $indicador;
    private $vmeta1;
    private $vmeta2;
    private $vmeta3;
    private $vmeta4;
    private $metrica;
    
    
    
   public function __construct() {
        

    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getCodigo() {
        return $this->codigo;
    }
   

    
    public function setSolicitacao(SolicitacaoInsercaoObjetivo $solicitacao) {
    	$this->solicitacao = $solicitacao;
    }
    
    public function getSolicitacao() {
    	return $this->solicitacao;
    }
    
    public function setIndicador(Indicador $indicador) {
        $this->indicador = $indicador;
    }

    public function getIndicador() {
        return $this->indicador;
    }
    
  
    
    public function setVmeta1( $vmeta1 ) {
        $this->vmeta1 = $vmeta1;
    }

    public function getVmeta1() {
        return $this->vmeta1;
    }
    
    public function setVmeta2( $vmeta2 ) {
        $this->vmeta2 = $vmeta2;
    }

    public function getVmeta2() {
        return $this->vmeta2;
    }
    
    
    public function setVmeta3( $vmeta3 ) {
        $this->vmeta3 = $vmeta3;
    }

    public function getVmeta3() {
        return $this->vmeta3;
    }  
    
    public function setVmeta4( $vmeta4 ) {
        $this->vmeta4 = $vmeta4;
    }

    public function getVmeta4() {
        return $this->vmeta4;
    }  
    
    public function setMetrica( $metrica ) {
        $this->metrica = $metrica;
    }

    public function getMetrica() {
        return $this->metrica;
    }  
}


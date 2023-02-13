<?php

/*
 * Esta classe é criada por mapa/documento
 */
class SolicitacaoInsercaoObjetivo extends Solicitacao{
    private $documento;
    private $objetivo;
    private $solicitItens;
    private $arrayitensindicadores;
 
    
    public function __construct() {
        

    }

    
    public function setDocumento($documento) {
    	$this->documento = $documento;
    }
    
    public function getDocumento() {
    	return $this->documento;
    }
    
    public function setObjetivo(Objetivo $objetivo) {
        $this->objetivo = $objetivo;
    }

    public function getObjetivo() {
        return $this->objetivo;
    }
    
 public function setSolicitItens(SolicitItensIndicadoresDeObjetivo $solicitItens) {
        $this->solicitItens = solicitItens;
    }

    public function getSolicitItens() {
        return $this->solicitItens;
    }
    
 public function setArrayitensindicadores($arrayitensindicadores) {
        $this->arrayitensindicadores = arrayitensindicadores;
    }

    public function getArrayitensindicadores() {
        return $this->arrayitensindicadores;
    }
    
    
    
    
 public function  criaSolicitItensIndicadoresDeObjetivo( $codigo,  $indicador, $vmeta1, $vmeta2, $vmeta3, $vmeta4,$metrica){
    	$m=new SolicitItensIndicadoresDeObjetivo();
     
        $m->setCodigo($codigo);
        $m->setSolicitacao($this);
        $m->setIndicador($indicador);
     	$m->setVmeta1($vmeta1);  
     	$m->setVmeta2($vmeta2);    	
     	$m->setVmeta3($vmeta3);    	
     	$m->setVmeta4($vmeta4);    	
     	$m->setMetrica($metrica);
        $this->solicitItens=$m;
    
    }
    
public function adicionaItemSolicitaIndicadores($codigo,  $indicador, $vmeta1, $vmeta2, $vmeta3, $vmeta4,$metrica,$indexArray){
	 if ($indexArray==0){
	 	$this->arrayitensindicadores=array();
	 }
	//echo $vmeta1.','. $vmeta2.','. $vmeta3.','. $vmeta4;
     $this->criaSolicitItensIndicadoresDeObjetivo( $codigo,  $indicador, $vmeta1, $vmeta2, $vmeta3, $vmeta4,$metrica);
     $this->arrayitensindicadores[$indexArray] = $this->solicitItens;
     
    }
  
}


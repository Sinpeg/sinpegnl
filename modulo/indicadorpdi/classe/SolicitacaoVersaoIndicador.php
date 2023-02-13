<?php
/*
 * Esta classe é criada por mapaindicador
 */

class SolicitacaoVersaoIndicador extends Solicitacao{

   
    private $mapaindicador;
    private $indicador;
    private $nome;
    private $calculo;
    private $interpretacao;
    
    //private $tipoColeta;
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
    
   
    
    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getNome() {
        return $this->nome;
    }
    
    public function setInterpretacao($interpretacao) {
        $this->interpretacao = $interpretacao;
    }

    public function getInterpretacao() {
        return $this->interpretacao;
    }

    public function setCalculo($calculo) {
        $this->calculo = $calculo;
    }

    public function getCalculo() {
        return $this->calculo;
    }
 
    
    

}
?>
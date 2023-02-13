<?php

class Indicador {

    private $codigo;
    private $nome;
    private $objeto;
    private $calculo;
    private $validade;
    private $unidademedida;
    private $peso;
    private $interpretacao;
    private $metodo;
    private $observacoes;
    private $benchmarch;
    private $mapa;
    private $mapainidacador;
    private $meta;
    private $cesta;
    private $unidade;
    private $codversao;
  //  private $anoinicio;
 //   private $dataalteracao;
    

//    private $tipoColeta;

    public function __construct() {        

    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getCodigo() {
        return $this->codigo;
    }
   
    public function setCodversao($codversao) {
    	$this->codversao = $codversao;
    }
    
    public function getCodversao() {
    	return $this->codversao;
    }
	
   /* public function setAnoinicio($anoinicio) {
    	$this->anoinicio = $anoinicio;
    }
    
    public function getAnoinicio() {
    	return $this->anoinicio;
    }
    
    public function setDataalteracao($dataalteracao) {
    	$this->dataalteracao = $dataalteracao;
    }
    
    public function getDataalteracao() {
    	return $this->dataalteracao;
    }*/
    
    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getNome() {
        return $this->nome;
    }
    
    public function setObjeto($objeto) {
        $this->objeto = $objeto;
    }

    public function getObjeto() {
        return $this->objeto;
    }

    public function setCalculo($calculo) {
        $this->calculo = $calculo;
    }

    public function getCalculo() {
        return $this->calculo;
    }
    
    public function setCesta($cesta) {
        $this->cesta = $cesta;
    }

    public function getCesta() {
        return $this->cesta;
    }
    
    public function setValidade($validade) {
        $this->validade = $validade;
    }

    public function getValidade() {
        return $this->validade;
    }
    
    public function setUnidademedida($unidademedida) {
    	$this->unidademedida = $unidademedida;
    }
    
    public function getUnidademedida() {
    	return $this->unidademedida;
    }
    
       public function setUnidade($unidade) {
    	$this->unidade = $unidade;
    }
    
    public function getUnidade() {
    	return $this->unidade;
    }
    
    
    public function setPeso($peso) {
    	$this->peso = $peso;
    }
    
    public function getPeso() {
    	return $this->peso;
    }
    
    
    public function setInterpretacao($interpretacao) {
    	$this->interpretacao = $interpretacao;
    }
    
    public function getInterpretacao() {
    	return $this->interpretacao;
    }
    
    
    public function setMetodo($metodo) {
    	$this->metodo = $metodo;
    }
    
    public function getMetodo() {
    	return $this->metodo;
    }
    
    public function setObservacoes($observacoes) {
    	$this->observacoes = $observacoes;
    }
    
    public function getObservacoes() {
    	return $this->observacoes;
    }
    
    public function setBenchmarch($benchmarch) {
    	$this->benchmarch = $benchmarch;
    }
    
    public function getBenchmarch() {
    	return $this->benchmarch;
    }
 
    public function setMeta($meta) {
    	$this->meta = $meta;
    }
    
    public function getMeta() {
    	return $this->meta;
    }
    
    public function setMapa(Mapa $mapa) {
        $this->mapa = $mapa;
    }

    public function getMapa() {
        return $this->mapa;
    }
    
public function setMapaIndicador($mapaIndicador) {
    	$this->mapaindicador = $mapaIndicador;
    }
    
    public function getMapaIndicador() {
    	return $this->mapaindicador;
    }
    public function criaMapaIndicador($codigo,$unidade,$mapa){
        $m = new Mapainidacador();
        
        $m->setCodigo($codigo) ;
        $m->setIndicador($this);
        $m->setPropindicador($unidade);
        $m->setMetrica($mapa);
     
        $this->$mapainidacador=$m;

    }
  

}
?>
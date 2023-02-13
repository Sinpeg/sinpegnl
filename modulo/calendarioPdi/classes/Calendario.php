<?php

class Calendario {

    private $codigo;
    private $unidade;
    private $documento;
    private $anogestao;
    private $datainianalise;
    private $datafimanalise;
    private $datainianalisefinal;
    private $datafimanalisefinal;
    private $datainiRAA;
    private $datafimRAA;
    private $datainielabpdu;
    private $datafimelabpdu;
    private $datainielabpt;
    private $datafimelabpt;
    private $usuario;
    private $datalteracao;
    private $datainialterapdu;
    private $datafimalterapdu;


    public function __construct() {
       
    }
    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getCodigo() {
        return $this->codigo;
    }


   
    public function setUnidade( $unidade) {
        $this->unidade = $unidade;
    }

    public function getUnidade() {
        return $this->unidade;
    }

    public function setDocumento( $documento) {
        $this->documento = $documento;
    }

    public function getDocumento() {
        return $this->documento;
    }
	
    //Parcial
    public function setDatainianalise($datainianalise) {
        $this->datainianalise = $datainianalise;
    }

    public function getDatainianalise() {
        return $this->datainianalise;
    }

    public function setDatafimanalise($datafimanalise) {
        $this->datafimanalise = $datafimanalise;
    }

    public function getDatafimanalise() {
        return $this->datafimanalise;
    }
    
    //Final
    public function setDatainianalisefinal($datainianalisefinal) {
    	$this->datainianalisefinal = $datainianalisefinal;
    }
    
    public function getDatainianalisefinal() {
    	return $this->datainianalisefinal;
    }
    
    public function setDatafimanalisefinal($datafimanalisefinal) {
    	$this->datafimanalisefinal = $datafimanalisefinal;
    }
    
    public function getDatafimanalisefinal() {
    	return $this->datafimanalisefinal;
    }
    
    
    public function setAnogestao($anogestao) {
        $this->anogestao = $anogestao;
    }

    public function getAnogestao() {
        return $this->anogestao;
    }
      public function setMapa($mapa) {
        $this->mapa = $mapa;
    }

    public function getMapa() {
        return $this->mapa;
    }


   public function setDatainiRAA($datainiRAA) {
        $this->datainiRAA = $datainiRAA;
    }

    public function getDatainiRAA() {
        return $this->datainiRAA;
    }
    
      public function setDatafimRAA($datafimRAA) {
        $this->datafimRAA = $datafimRAA;
    }

    public function getDatafimRAA() {
        return $this->datafimRAA;
    }
    
    
    public function setDatainielabpdu($datainielabpdu) {
        $this->datainielabpdu = $datainielabpdu;
    }
    
    public function getDatainielabpdu() {
        return $this->datainielabpdu;
    }
    
    public function setDatafimelabpdu($datafimelabpdu) {
        $this->datafimelabpdu = $datafimelabpdu;
    }
    
    public function getDatafimelabpdu() {
        return $this->datafimelabpdu;
    }
    
    
    public function setDatainielabpt($datainielabpt) {
        $this->datainielabpt = $datainielabpt;
    }
    
    public function getDatainielabpt() {
        return $this->datainielabpt;
    }
    public function setDatafimelabpt($datafimelabpt) {
        $this->datafimelabpt = $datafimelabpt;
    }
    
    public function getDatafimelabpt() {
        return $this->datafimelabpt;
    }
    
    
 
    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }
    
    public function getUsuario() {
        return $this->usuario;
    }

    public function setDatalteracao($datalteracao) {
        $this->datalteracao = $datalteracao;
    }
    
    public function getDatalteracao() {
        return $this->datalteracao;
    }
    
    
    /**
     * @return mixed
     */
    public function getDatainialterapdu()
    {
        return $this->datainialterapdu;
    }
    
    /**
     * @return mixed
     */
    public function getDatafimalterapdu()
    {
        return $this->datafimalterapdu;
    }
    
    /**
     * @param mixed $datainialterapdu
     */
    public function setDatainialterapdu($datainialterapdu)
    {
        $this->datainialterapdu = $datainialterapdu;
    }
    
    /**
     * @param mixed $datafimalterapdu
     */
    public function setDatafimalterapdu($datafimalterapdu)
    {
        $this->datafimalterapdu = $datafimalterapdu;
    }
}

?>

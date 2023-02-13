<?php
class Tecnologias{
	private $codigo;
	private $unidade;
	private $bandaL;
	private $salaC;
	private $videoc;
	private $salasA;
	private $micro;
	private $ano;
	
	public function _construct($codigo, $unidade,$bandaL,$salaC,$videoc,$salasA,$micro,$ano) {
		$this->codigo = $codigo;
		$this->unidade = $unidade;
		$this->bandaL = $bandaL;
		$this->salaC = $salaC;
		$this->videoc = $videoc;
		$this->salasA = $salasA;
		$this->micro = $micro;
		$this->ano = $ano;
	}

	public function getCodigo(){
		return $this->codigo;
	}

	public function setCodigo($codigo){
		$this->codigo= $codigo;
	}

	public function getUnidade(){
		return $this->Unidade;
	}

	public function setUnidade($unidade){
		$this->Unidade =$unidade;
	}
	
	public function getBandaL(){
	    return $this->bandaL;
	}
	
	public function setBandaL($bandaL){
	    $this->bandaL = $bandaL;
	}
	
	public function getSalaC(){
	    return $this->salaC;
	}
	
	public function setSalaC($salaC){
	    $this->salaC = $salaC;
	}
	
	public function getVideoc(){
	    return $this->videoc;
	}
	
	public function setVideoc($videoc){
	    $this->videoc = $videoc;
	}
	
	public function getSalasA(){
	    return $this->salasA;
	}
	
	public function setSalasA($salasA){
	    $this->salaC = $salasA;
	}
	
	public function getMicro(){
	    return $this->micro;
	}
	
	public function setMicro($micro){
	    $this->micro = $micro;
	}
	
	public function getAno(){
		return $this->ano;
	}

	public function setAno($ano){
		$this->ano = $ano;
	}
}
?>
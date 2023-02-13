<?php
class Micros{
	private $codigo;
	private $unidade;
	private $acad;
	private $acadi;
	private $adm;
	private $admi;
	private $ano;
	public function _construct($codigo, $unidade,$acad,$acadi,$adm,$admi,$ano) {
		$this->codigo = $codigo;
		$this->unidade = $unidade;
		$this ->acad =$acad;
		$this ->acadi =$acadi;
		$this->adm = $adm;
		$this->admi = $admi;
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

	public function setUnidade( $unidade){
		$this->Unidade =$unidade;
	}

	public function getAcad(){
		return $this->acad;
	}

	public function setAcad($acad){
		$this->acad = $acad;
	}

	public function getAcadi(){
		return $this->acadi;
	}
	
	public function setAcadi($acadi){
		$this->acadi = $acadi;
	}

	public function getAdm(){
		return $this->adm;
	}

	public function setAdm($adm){
		$this->adm = $adm;
	}

	public function getAdmi(){
		return $this->admi;
	}
	
	public function setAdmi($admi){
		$this->admi = $admi;
	}
	public function getAno(){
		return $this->ano;
	}

	public function setAno($ano){
		$this->ano = $ano;
	}

}
?>
<?php
class Rhetemufpa{
	private $codigo;
	private $Unidade;
	private $doutores;
	private $mestres;
	private $especialistas;
	private $graduados;
	private $temporarios;
	private $tecnicos;
	private $ntecnicos;
	private $subunidade;
	private $ano;
	public function _construct($codigo, $unidade,$doutores,$mestres,$especialistas,$graduados,$temporarios,$tecnicos,$ano) {
		$this->codigo = $codigo;
		$this->Unidade = $unidade;
		$this ->doutores =$doutores;
		$this->mestres = $mestres;
		$this->especialistas = $especialistas;
		$this->graduados = $graduados;
		$this->temporarios = $temporarios;
		$this->tecnicos = $tecnicos;
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

	public function getDoutores(){
		return $this->doutores;
	}

	public function setDoutores($doutores){
		$this->doutores = $doutores;
	}


	public function getMestres(){
		return $this->mestres;
	}

	public function setMestres($mestres){
		$this->mestres = $mestres;
	}

	public function getEspecialistas(){
		return $this->especialistas;
	}

	public function setEspecialistas($especialistas){
		$this->especialistas= $especialistas;
	}

	public function getGraduados(){
		return $this->graduados;
	}

	public function setGraduados($graduados){
		$this->graduados= $graduados;
	}

	public function getTemporarios(){
		return $this->temporarios;
	}

	public function setTemporarios($temporarios){
		$this->temporarios= $temporarios;
	}

	public function getTecnicos(){
		return $this->tecnicos;
	}

	public function setTecnicos($tecnicos){
		$this->tecnicos= $tecnicos;
	}

	public function getNtecnicos(){
		return $this->ntecnicos;
	}

	public function setNtecnicos($ntecnicos){
		$this->ntecnicos= $ntecnicos;
	}

	public function getSubunidade(){
		return $this->subunidade;
	}
	 
	public function setSubunidade( $subunidade){
		$this->subunidade =$subunidade;
	}

	public function getAno(){
		return $this->ano;
	}

	public function setAno($ano){
		$this->ano = $ano;
	}

}
?>
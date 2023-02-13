<?php
class Freqfarmacia{
	private $codigo;
	private $mes;
	private $nalunos;
	private $nprofessores;
	private $nvisitantes;
	private $npesquisadores;
	private $ano;
	
	public function _construct($codigo, $mes,$nalunos,$nprofessores,$nvisitantes,$npesquisadores,$ano)
	 {
		$this->codigo = $codigo;
		$this->mes = $mes;
		$this->nalunos =$nalunos;
		$this->nprofessores = $nprofessores;
		$this->nvisitantes = $nvisitantes;
		$this->npesquisadores = $npesquisadores;
		$this->ano = $ano;
	}

	public function getCodigo(){
		return $this->codigo;
	}

	public function setCodigo($codigo){
		$this->codigo= $codigo;
	}

	public function getMes(){
		return $this->mes;
	}

	public function setMes( $mes){
		$this->mes =$mes;
	}

	public function getNalunos(){
		return $this->nalunos;
	}

	public function setNalunos($nalunos){
		$this->nalunos = $nalunos;
	}


	public function getNprofessores(){
		return $this->nprofessores;
	}

	public function setNprofessores($nprofessores){
		$this->nprofessores = $nprofessores;
	}

	public function getNvisitantes(){
		return $this->nvisitantes;
	}

	public function setNvisitantes($nvisitantes){
		$this->nvisitantes= $nvisitantes;
	}

	public function getNpesquisadores(){
		return $this->npesquisadores;
	}

	public function setNpesquisadores($npesquisadores){
		$this->npesquisadores= $npesquisadores;
	}

	public function getAno(){
		return $this->ano;
	}

	public function setAno($ano){
		$this->ano = $ano;
	}

}
?>



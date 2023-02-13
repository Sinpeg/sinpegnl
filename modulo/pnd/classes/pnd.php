<?php
class Pnd {
	private $codigo;
	private $nopnd;
	private $noatendidos;
	private $ano;
	private $Curso;
	
	public function _construct($codigo,$nopnd,$noatendidos,$ano) {
		$this->codigo = $codigo;
		$this->nopnd = $nopnd;
		$this->noatendidos = $noatendidos;
		$this->ano = $ano;
	}

	public function getCodigo(){
		return $this->codigo;
	}

	public function setCodigo($codigo){
		$this->codigo = $codigo;
	}

	public function getNopnd(){
		return $this->nopnd;
	}

	public function setNopnd($nopnd){
		$this->nopnd = $nopnd;
	}

	public function getNoatendidos(){
		return $this->noatendidos;
	}
	
	public function setNoatendidos($noatendidos){
		$this->noatendidos = $noatendidos;
	}

	public function getCurso(){
		return $this->Curso;
	}

	public function setCurso(Curso $curso){
		$this->Curso= $curso;
	}
	public function getAno(){
		return $this->ano;
	}
	
	public function setAno($ano){
		$this->ano = $ano;
	}
	
}
?>
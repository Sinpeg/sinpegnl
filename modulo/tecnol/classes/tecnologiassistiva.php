<?php
class Tecnologiassistiva {
	private $codtecnologiaassistiva;
	private $ano;
	private $Tipota;
	private $Curso;

	public function _construct($codtecnologiaassistiva, $codcurso,$ano,$npessoasnecessitadas,$npessoasatendidas) {
		$this->codtecnologiaassistiva = $codtecnologiaassistiva;
		$this->codcurso = $codcurso;
		$this->ano = $ano;

	}

	public function getCodtecnologiaassistiva(){
		return $this->codtecnologiaassistiva;
	}

	public function setCodtecnologiaassistiva($codigo){
		$this->codtecnologiaassistiva = $codigo;
	}


	public function getAno(){
		return $this->ano;
	}

	public function setAno($ano){
		$this->ano = $ano;
	}
	 

	public function getTipota(){
		return $this->Tipota;
	}

	public function setTipota(Tipotecnologiassistiva $tipota){
		$this->Tipota = $tipota;
	}

	public function getCurso(){
		return $this->Curso;
	}

	public function setCurso(Curso $curso){
		$this->Curso = $curso;
	}


}
?>
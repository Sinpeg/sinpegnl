<?php
class Libras {
	private $codigo;
	private $ano;
	private $Curso;
	 
	public function _construct($codigo,$ano,$curso) {
		$this->codigo = $codigo;
		$this->codcurso = $codcurso;
		$this->ano = $ano;
		$this->Curso = $curso;
	}

	public function getCodigo(){
		return $this->codigo;
	}

	public function setCodigo($codigo){
		$this->codigo = $codigo;
	}

	public function getAno(){
		return $this->ano;
	}

	public function setAno($ano){
		$this->ano = $ano;
	}

	 
	public function getCurso(){
		return $this->Curso;
	}

	public function setCurso(Curso $curso){
		$this->Curso[] = $curso;
	}

	public function criaCurso($CodCursoSis,$unidade,$CodCurso,$NomeCurso,$DataInicio,$CodEmec){
		$curso = new Curso();
		$curso->setUnidade($unidade);
	 $curso->setCampus($this);
	 $curso->setCodcursosis($CodCursoSis);
	 $curso->setCodcurso($CodCurso);
	 $curso->setNomecurso($NomeCurso);
	 $curso->setDatainicio($DataInicio);
	 $curso->setCodemec($CodEmec);
	 return $curso;
	}


}
?>
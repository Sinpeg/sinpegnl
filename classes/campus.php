<?php
class Campus {
	private $codigo;
	private $nome;
	private $Cursos  = array();

	public function _construct($codigo,$nome,$cursos) {
		$this->codigo = $codigo;
		$this->codcurso = $codcurso;
		$this->nome = $nome;
		$this->Cursos = $cursos;
	}

	public function getCodigo(){
		return $this->codigo;
	}

	public function setCodigo($codigo){
		$this->codigo = $codigo;
	}

	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}


	public function getCursos(){
		return $this->Cursos;
	}

	public function setCursos(Curso $curso){
		$this->Cursos[] = $curso;
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

	public function adicionaItemCursos($CodCursoSis,$unidade,$CodCurso,$NomeCurso,$DataInicio,$CodEmec){
		$curso = criaCurso($CodCursoSis,$unidade,$CodCurso,$NomeCurso,$DataInicio,$CodEmec);
		$this->Cursos[] = $curso;
	}
}
?>
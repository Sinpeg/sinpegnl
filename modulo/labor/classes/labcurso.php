<?php
class Labcurso {
	private $codlabcurso;
	private $Curso;
	private $Laboratorio;

	public function _construct($codlabcurso,$curso,$laboratorio ) {
		$this->codlabcurso = $codlabcurso;
		$this->Curso =  $curso;
		$this->Laboratorio = $laboratorio;
	}

	public function getCodlabcurso(){
		return $this->codlabcurso;
	}

	public function setCodlabcurso($codlabcurso){
		$this->codlabcurso = $codlabcurso;
	}

	public function getCurso(){
		return $this->Curso;
	}
	 
	public function setCurso(Curso $curso){
		$this->Curso = $curso;
	}

	public function getLaboratorio(){
		return $this->Laboratorio;
	}
	 
	public function setLaboratorio(Laboratorio $lab){
		$this->Laboratorio = $lab;
	}


	 
}
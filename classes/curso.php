<?php
class Curso {
	private $codcurso;
	private $Campus;
	private $codcursosis;
	private $nomecurso;
	private $Unidade;
	private $codemec;
	private $datainicio;
	private $anovalidade;
	private $Tacursos  = array();//agrega��o (1 curso 0.." tecnologia_assistiva)
	private $Labcursos  = array();//agrega��o (1 curso 0.." laborat�rio)
	private $Libra;
	private $Pnd;

	public function _construct($campus,$unidade,$codcurso, $codcursosis, $nomecurso,$datainicio,$codemec) {
		$this->Campus = $campus;
		$this->codcurso = $codcurso;
		$this->Unidade = $unidade;
		$this->codcursosis = $codcursosis;
		$this->nomecurso = $nomecurso;
		$this->datainicio = $datainicio;
		$this->codemec = $codemec;

	}

	public function getCampus(){
		return $this->campus;
	}

	public function setCampus($campus){
		$this->campus = $campus;
	}

	public function criaCampus($codigo,$nome){
		$campus = new Campus();
		$campos->setCodigo($codigo);
		$campus->setNome($nome);
		return $campus;
	}

	public function getCodcurso(){
		return $this->codcurso;
	}

	public function setCodcurso($codcurso){
		$this->codcurso = $codcurso;
	}


	public function getUnidade(){
		return $this->unidade;
	}

	public function setUnidade($unidade){
		$this->Unidade = $unidade;
	}

	public function getCodemec(){
		return $this->codemec;
	}

	public function setCodemec($codemec){
		$this->codemec = $codemec;
	}

	public function getCodcursosis(){
		return $this->codcursosis;
	}


	public function setCodcursosis($codcursosis){
		$this->codcursosis = $codcursosis;
	}
	

	public function getNomecurso(){
		return $this->nomecurso;
	}

	public function setNomecurso($nomecurso){
		$this->nomecurso = $nomecurso;
	}

	public function getDatainicio(){
		return $this->datainicio;
	}

	public function setDatainicio($datainicio){
		$this->datainicio = $datainicio;
	}

	public function getAnovalidade(){
	    return $this->anovalidade;
	}
	
	public function setAnovalidade($anovalidade){
	    $this->anovalidade = $anovalidade;
	}
	
	public function getTacursos(){
		return $this->Tacursos;
	}


	public function setTacursos($tacursos){
	    $this->Tacursos[] = $tacursos;
	}


	public function adicionaItemTacursos($curso,$anobase,$tipota,$codta){
		$tacurso = $this->criaTacurso($curso,$anobase,$tipota,$codta);
		$this->Tacursos[] = $tacurso;
	}

	public function criaTacurso($curso,$anobase,$tipota,$codta){
		$tacurso = new Tecnologiassistiva();
		$tacurso->setCurso($curso);
		$tacurso->setAno($anobase);
		$tacurso->setCodtecnologiaassistiva($codta);
		$tacurso->setTipota($tipota);
		return $tacurso;
	}

	public function getLabcursos(){
		return $this->labacursos;
	}

	public function adicionaItemLabcursos($codlabcurso,$curso,$lab){
		$labcurso = $this->criaLabcurso($codlabcurso,$curso,$lab);
		$this->Labcursos[] = $labcurso;
	}

	public function criaLabcurso($codlabcurso,$curso,$lab){
		$labcurso = new Labcurso();
		$labcurso->setCodlabcurso($codlabcurso);
		$labcurso->setCurso($curso);
		$labcurso->setLaboratorio($lab);
		return $labcurso;
	}
	public function getLibra(){
		return $this->Libra;
	}

	public function criaLibra($codigo,$ano){
		$libras = new Libras();
		$libras->setCodigo($codigo);
		$libras->setCurso($this);
		$libras->setAno($ano);
		$this->Libra = $libras;
	}

	public function getPnd(){
		return $this->Pnd;
	}
	
	public function criaPnd($codigo,$nopnd,$noatendidos,$ano){
		$p = new Pnd();
		$p->setCodigo($codigo);
		$p->setCurso($this);
		$p->setNopnd($nopnd);
		$p->setNoatendidos($noatendidos);
		$p->setAno($ano);
		$this->Pnd = $p;
	}
}
?>
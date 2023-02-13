<?php
 class Tipotecnologiassistiva {
 	private $codigo;
 	private $nome;
 	private $Tacursos = array(); //agregação

 	public function _construct($codigo, $nome) {
 		$this->codigo = $codigo;
 		$this->nome = $nome;

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
 	 
  public function setTacursos(Tacursos $tacursos){
  	$this->Tacursos[] = $tacursos;
  }

  public function getTacursos(){
  	return $this->Tacursos;
  }

  /* public function adicionaItemTacursos($curso,$anobase,$tipota,$codta){
   $tacurso = new Tecnologiassistiva();
  $tacurso->setCurso($curso);
  $tacurso->setAno($anobase);
  $tacurso->setCodtecnologiaassistiva($codta);
  $tacurso->setTipota($tipota);
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
  */

  function ExibeLista(){
  	foreach ($this->Tacursos as $tacurso){
  		print $tacurso->getAno() . "<br>\n";
  	}
  }



 }
 ?>
<?php
class Localoferta  {

	private $idLocal;
	private $nome;
	private $blofertas =array();
	private $Bloferta;
	
	public function _construct($idLocal, $nome) {
		$this->idLocal = $idLocal;
		$this->nome = $nome;
	}

/*	public function _construct($idLocal, $nome, $blofertas) {
		$this->idLocal = $idLocal;
		$this->nome = $nome;
		$this->blofertas = $blofertas;
	}
*/
	public function getIdLocal() {
		return $this->idLocal;
	}

	public function setIdLocal($idLocal) {
		$this->idLocal = $idLocal;
	}

	public function getNome() {
		return $this->nome;
	}

	public function setNome($nome) {
		$this->nome = $nome;
	}

	public function getBlofertas() {
		return $this->blofertas;
	}

	public function setBlofertas($blofertas) {
		$this->blofertas = $blofertas;
	}

	public function getBloferta() {
		return $this->Bloferta;
	}
	
	public function setBloferta($bloferta) {
		$this->Bloferta = $bloferta;
	}
	
	
	
	public function criaBloferta($Bibliemec){
		$b = new Bloferta();
		$b->setLocaloferta($this);
		$b->setBibliemec($Bibliemec);
		$this->Bloferta =$b;
		
	}
	
	
	
	
}
?>
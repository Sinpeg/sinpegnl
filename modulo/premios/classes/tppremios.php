<?php
class Tppremios{
	private $codpremio;
	private $nome;


	public function _construct($codPremio,$nome) {
		$this->codpremio = $codPremio;
		$this->nome= $nome;
	
	}

	public function getCodpremio(){
		return $this->codpremio;
	}

	public function setCodpremio($codigo){
		$this->codpremio= $codigo;
	}

	public function getNome(){
		return $this->nome;
	}
	
	public function setNome($nome){
		$this->nome= $nome;
	}
	

}
?>
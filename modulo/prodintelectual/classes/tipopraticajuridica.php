<?php
class Tipopraticajuridica {
 	private $codigo;
 	private $nome;
 	private $Praticajuridica;

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
 	public function getPraticajuridica(){
 		return $this->Praticajuridica;
 	}
 		
 	public function setPraticajuridica(Praticajuridica $praticajuridica){
 		$this->Praticajuridica = $praticajuridica;
 	}
 		
 	public function criaPraticajuridica($codigo,$unidade,$ano,$quantidade){
 		$praticajuridica = new Praticajuridica();
 		$praticajuridica->setCodigo($codigo);
 		$praticajuridica->setUnidade($unidade);
 		$praticajuridica->setTipo($this);
 		$praticajuridica->setAno($ano);
 		$praticajuridica->setQuantidade($quantidade);
 		$this->Praticajuridica = $praticajuridica;
 	} 	
}
?>
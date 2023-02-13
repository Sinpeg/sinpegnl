<?php
 class Tipoproducaoartistica {
 	private $codigo;
 	private $nome;
 	private $Prodartisticas=array();
 	private $Prodartistica;

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

 	public function getProdartistica(){
 		return $this->Prodartistica;
 	}
 		
 	public function setProdartistica(Producaoartistica $prodartistica){
 		$this->Prodartistica[] = $prodartistica;
 	}
 		
 	public function setProdartisticas(Producaoartistica $prodartistica){
 		$this->Prodartisticas[] = $prodartistica;
 	}
 		
 	public function getProdartisticas(){
 		return $this->Prodartisticas;
 	}
 	public function criaProdartistica($codigo,$unidade,$ano,$quantidade){
 		$prodartistica = new Producaoartistica();
 		$prodartistica->setCodigo($codigo);
 		$prodartistica->setUnidade($unidade);
 		$prodartistica->setTipo($this);
 		$prodartistica->setAno($ano);
 		$prodartistica->setQuantidade($quantidade);
 		$this->Prodartistica = $prodartistica;
 	}
 		
 	public function adicionaItemProdartistica($codigo,$unidade,$ano,$quantidade){
 		$this->criaProdartistica($codigo,$unidade,$ano,$quantidade);
 		$this->Prodartisticas[] = $prodartistica;
 			
 	}
 		
 }
 ?>
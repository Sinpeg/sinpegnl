<?php
class Produtos {
	private $codigo;
	private $nome;
	private $Prodfarmacia;
	private $Prodfarmacias;
	
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
	 
	public function getProdfarmacia(){
		return $this->Prodfarmacia;
	}
		
	public function setProdfarmacia(Prodfarmacia $prodfarmacia){
		$this->Prodfarmacia = $prodfarmacia;
	}

	
	public function getProdfarmacias(){
	return $this->Prodfarmacias;
	}
	
	public function setProdfarmacias(Prodfarmacia $prodfarmacia){
	$this->Prodfarmacias[] = $prodfarmacia;
	}
	public function criaProdfarmacia($codigo,$quantidade,$ano,$preco,$mes){
		$prodfarma = new Prodfarmacia();
		$prodfarma->setQuantidade($quantidade);
		$prodfarma->setAno($ano);
		$prodfarma->setMes($mes);
		$prodfarma->setPreco($preco);
		$prodfarma-> setTipoproduto($this);
		$prodfarma->setCodigo($codigo);
		$this->Prodfarmacia =  $prodfarma;
	}
	
	
	public function adicionaItemProdfarmacia($codigo,$quantidade,$ano,$preco,$mes){
		$this->criaProdfarmacia($codigo,$quantidade,$ano,$preco,$mes);
		$this->Prodfarmacias[] = $this->Prodfarmacia;
	}
}

?>
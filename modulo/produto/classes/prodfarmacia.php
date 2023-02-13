<?php
class Prodfarmacia {
	private $codigo;
	private $quantidade;
	private $ano;
	private $mes;
	private $preco;
	private $Tipoproduto;

	public function _construct($codigo, $quantidade,$ano,$preco, $mes) {
		$this->codigo = $codigo;
		$this->quantidade = $quantidade;
		$this->ano = $ano;
		$this->preco = $preco;
		$this->mes = $mes;
	}

	public function getCodigo(){
		return $this->codigo;
	}

	public function setCodigo($codigo){
		$this->codigo = $codigo;
	}

	 
	public function getQuantidade(){
		return $this->quantidade;
	}

	public function setQuantidade($quantidade){
		$this->quantidade = $quantidade;
	}
	public function getAno(){
		return $this->ano;
	}

	public function setAno($ano){
		$this->ano = $ano;
	}
	
	public function getMes(){
		return $this->mes;
	}
	
	public function setMes($mes){
		$this->mes = $mes;
	}
	public function getPreco(){
		return $this->preco;
	}

	public function setPreco($preco){
		$this->preco = $preco;
	}

	public function getTipoproduto(){
		return $this->Tipoproduto;
	}

	public function setTipoproduto(Produtos $tipoproduto){
		$this->Tipoproduto = $tipoproduto;
	}
	public function adicionaItemProdFarmacia($prodfarmacia){
		$this->Prodfarmacia[]  = $prodfarmacia;

	}
}

?>
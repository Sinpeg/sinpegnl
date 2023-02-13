<?php
class Praticajuridica{
	private $codigo;
	private $quantidade;
	private $ano;
	private $unidade;
	private $tipo;

	public function _construct($codigo, $unidade,$tipo,$ano,$quantidade) {
		$this->codigo = $codigo;
		$this->unidade = $unidade;
		$this ->tipo =$tipo;
		$this->quantidade = $quantidade;
		$this->ano = $ano;
	}

	public function getCodigo(){
		return $this->codigo;
	}

	public function setCodigo($codigo){
		$this->codigo= $codigo;
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

	public function getTipo(){
		return $this->tipo;
	}

	public function setTipo($tipopraticajuridica){
		$this->tipo= $tipopraticajuridica;
	}

	public function getUnidade(){
		return $this->unidade;
	}

	public function setUnidade( $unidade){
		$this->unidade =$unidade;
	}

}
?>
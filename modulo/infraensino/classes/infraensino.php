<?php
class Infraensino{
	private $codigo;
	private $quantidade;
	private $ano;
	private $Unidade;
	private $Tipo;

	public function _construct($codigo, $unidade,$tipo,$ano,$quantidade) {
		$this->codigo = $codigo;
		$this->Unidade = $unidade;
		$this ->Tipo =$tipo;
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
		return $this->Tipo;
	}

	public function setTipo($tipoinfraensino){
		$this->Tipo= $tipoinfraensino;
	}

	public function getUnidade(){
		return $this->Unidade;
	}

	public function setUnidade( $unidade){
		$this->Unidade =$unidade;
	}

}
?>
<?php
class Acessibilidade{
	private $codigoEstrutura;
	private $quantidade;
	private $ano;
	private $unidade;
	private $tipo;

	public function _construct($codigoEstrutura, $unidade,$tipo,$ano,$quantidade) {
		$this->codigoEstrutura = $codigoEstrutura;
		$this->unidade = $unidade;
		$this ->tipo =$tipo;
		$this->quantidade = $quantidade;
		$this->ano = $ano;
	}

	public function getCodigoEstrutura(){
		return $this->codigoEstrutura;
	}

	public function setCodigoEstrutura($codigo){
		$this->codigoEstrutura= $codigo;
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

	public function setTipo($tipoEstruturaAcessibilidade){
		$this->tipo= $tipoEstruturaAcessibilidade;
	}

	public function getUnidade(){
		return $this->unidade;
	}

	public function setUnidade( $unidade){
		$this->unidade =$unidade;
	}

}
?>
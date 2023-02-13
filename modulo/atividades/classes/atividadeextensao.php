<?php
class Atividadeextensao{
	private $codigo;
	private $Unidade;
	private $tipo;
	private $quantidade;
	private $participantes;
	private $atendidas;
	private $subunidade;
	private $ano;

	 
	public function _construct($codigo,$tipo, $unidade,$subunidade,$quantidade,$participantes,$atendidas,$ano) {
		$this->codigo = $codigo;
		$this->Unidade = $unidade;
		$this->subunidade = $subunidade;
		$this->tipo = $tipo;
		$this ->quantidade =$quantidade;
		$this->participantes = $participantes;
		$this->atendidas = $atendidas;
		$this->ano = $ano;
	}

	public function getCodigo(){
		return $this->codigo;
	}

	public function setCodigo($codigo){
		$this->codigo= $codigo;
	}

	public function getUnidade(){
		return $this->Unidade;
	}

	public function setUnidade( $unidade){
		$this->Unidade =$unidade;
	}

	public function getSubunidade(){
		return $this->subunidade;
	}
	 
	public function setSubunidade( $subunidade){
		$this->subunidade =$subunidade;
	}
	public function getTipo(){
		return $this->tipo;
	}

	public function setTipo( $tipo){
		$this->tipo =$tipo;
	}

	 
	public function getQuantidade(){
		return $this->quantidade;
	}

	public function setQuantidade($quantidade){
		$this->quantidade = $quantidade;
	}


	public function getParticipantes(){
		return $this->participantes;
	}

	public function setParticipantes($participantes){
		$this->participantes = $participantes;
	}

	public function getAtendidas(){
		return $this->atendidas;
	}

	public function setAtendidas($atendidas){
		$this->atendidas= $atendidas;
	}

	 
	public function getAno(){
		return $this->ano;
	}

	public function setAno($ano){
		$this->ano = $ano;
	}

}
?>
<?php
class EAprojpesquisa{
	private $codigo;
	private $execucao;
	private $tramitacao;
	private $cancelado;
	private $suspenso;
	private $concluido;
	private $docentes;
	private $tecnicos;
	private $discentes;
	private $outras;
	private $ano;

	public function _construct($codigo,$execucao,$tramitacao,$cancelado,$suspenso,$concluido,$docentes,$tecnicos,$discentes,$outras,$ano) {
		$this->codigo = $codigo;
		$this->execucao = $execucao;
		$this ->tramitacao =$tramitacao;
		$this->cancelado = $cancelado;
		$this->suspenso = $suspenso;
		$this->concluido = $concluido;
		$this->docentes = $docentes;
		$this->tecnicos = $tecnicos;
		$this->discentes = $discentes;
		$this->outras = $outras;
		$this->ano = $ano;
	}

	public function getCodigo(){
		return $this->codigo;
	}

	public function setCodigo($codigo){
		$this->codigo= $codigo;
	}


	public function getExecucao(){
		return $this->execucao;
	}

	public function setExecucao($execucao){
		$this->execucao = $execucao;
	}


	public function getTramitacao(){
		return $this->tramitacao;
	}

	public function setTramitacao($tramitacao){
		$this->tramitacao= $tramitacao;
	}

	public function getCancelado(){
		return $this->cancelado;
	}

	public function setCancelado( $cancelado){
		$this->cancelado =$cancelado;
	}

	public function getSuspenso(){
		return $this->suspenso;
	}

	public function setSuspenso( $suspenso){
		$this->suspenso =$suspenso;
	}

	public function getConcluido(){
		return $this->concluido;
	}

	public function setConcluido( $concluido){
		$this->concluido =$concluido;
	}


	public function getDocentes(){
		return $this->docentes;
	}

	public function setDocentes( $docentes){
		$this->docentes =$docentes;
	}

	public function getTecnicos(){
		return $this->tecnicos;
	}

	public function setTecnicos( $tecnicos){
		$this->tecnicos =$tecnicos;
	}

	public function getDiscentes(){
		return $this->discentes;
	}

	public function setDiscentes( $discentes){
		$this->discentes =$discentes;
	}


	public function getOutras(){
		return $this->outras;
	}

	public function setOutras($outras){
		$this->outras =$outras;
	}


	public function getAno(){
		return $this->ano;
	}

	public function setAno($ano){
		$this->ano = $ano;
	}

	 
}
?>
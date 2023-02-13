<?php
class Programa {
	private $codigo;
	private $nome;
	private $codprograma;
	private $Acoes  = array();
	private $Acao ;


	public function _construct($codigo,$nome) {
		$this->setcodigo = $codigo;
		$this->setnome = $nome;
	}

	public function getCodigo(){
		return $this->setcodigo;
	}

	public function setCodigo($codigo){
		$this->setcodigo = $codigo;
	}

	public function getCodprograma(){
		return $this->codprograma;
	}

	public function setCodprograma($codigo){
		$this->codprograma = $codigo;
	}
	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}

	public function getAcao(){
		return $this->Acao;
	}

	public function setAcao(Acao $acao){
		$this->Acao = $acao;
	}


	public function getAcoes(){
		return $this->Acoes;
	}

	public function setAcoes(Acao $acao){
		$this->Acoes[] = $acao;
	}

	public function criaAcao($codigo,$unidade,$codacao,$nome,$finalidade,$descricao,
	$analisecritica, $ano){
		$acao = new Acao();
		$acao->setCodigo( $codigo );
		$acao->setUnidade( $unidade);
		$acao->setCodcao( $codacao);
		$acao->setPrograma($this);
		$acao->setNome( $nome);
		$acao->setFinalidade( $finalidade);
		$acao->setAno( $ano);
		$acao->setDescricao( $descricao);
		$acao->setAnalisecritica( $analisecritica);
		$this->Acao=$acao;
		return $acao;
	}

	public function adicionaItemAcoes($codigo,$unidade,$codacao,$nome,$finalidade,$descricao,
	$analisecritica, $ano){
		$acao = $this->criaAcao($codigo,$unidade,$codacao,$nome,$finalidade,$descricao,
		$analisecritica, $ano);
		$this->Acoes[] = $acao;
	}
}
?>
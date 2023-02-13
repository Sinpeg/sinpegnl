<?php
class Acao {
	private $codigo;
	private $Unidade;
	private $codacao;
	private $Programa;
	private $nome;
	private $finalidade;
	private $descricao;
	private $analisecritica;
	private $ano;

	public function _construct($codigo,$unidade,$codacao,$programa,$nome,$finalidade,$descricao,
	$analisecritica,$ano) {
		$this->codigo = $codigo;
		$this->Unidade = $unidade;
		$this->codacao = $codacao;
		$this->Programa = $programa;
		$this->nome = $nome;
		$this->finalidade = $finalidade;
		$this->ano = $ano;
		$this->descricao = $descricao;
		$this->analisecritica = $analisecritica;
	}

	public function getCodigo(){
		return $this->codigo;
	}

	public function setCodigo($codigo){
		$this->codigo = $codigo;
	}

	public function getUnidade(){
		return $this->Unidade;
	}

	public function setUnidade(Unidade $unidade){
		$this->Unidade = $unidade;
	}

	public function getPrograma(){
		return $this->Programa;
	}

	public function setPrograma($programa){
		$this->Programa = $programa;
	}

	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}

	public function getFinalidade(){
		return $this->finalidade;
	}

	public function setFinalidade($finalidade){
		$this->finalidade = $finalidade;
	}

	public function getDescricao(){
		return $this->descricao;
	}

	public function setDescricao($descricao){
		$this->descricao = $descricao;
	}

	public function getCodacao(){
		return $this->codacao;
	}

	public function setCodcao( $codacao){
		$this->codacao = $codacao;
	}

	public function getAnalisecritica(){
		return $this->analisecritica;
	}


	public function setAnalisecritica($analisecritica){
		$this->analisecritica = $analisecritica;
	}

	public function getAno(){
		return $this->ano;
	}

	public function setAno( $ano){
		$this->ano = $ano;
	}


}
?>
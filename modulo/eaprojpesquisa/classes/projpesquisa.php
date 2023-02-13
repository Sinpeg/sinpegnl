<?php
class Projpesquisa{
	private $codigo;
	private $unidade;
	private $quantidade;
	private $pesquisadores;
	private $discentes;
	private $ano;
	public function _construct($codigo,$unidade,$quantidade,$pesquisadores,$discentes,$ano) {
		$this->codigo = $codigo;
		$this->unidade = $unidade;
		$this ->quantidade =$quantidade;
		$this->pesquisadores = $pesquisadores;
		$this->discentes = $discentes;
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

	public function getQuantidade(){
		return $this->quantidade;
	}

	public function setQuantidade($quantidade){
		$this->quantidade = $quantidade;
	}


	public function getPesquisadores(){
		return $this->pesquisadores;
	}

	public function setPesquisadores($pesquisadores){
		$this->pesquisadores = $pesquisadores;
	}

	public function getDiscentes(){
		return $this->discentes;
	}

	public function setDiscentes($discentes){
		$this->discentes= $discentes;
	}

	 
	public function getAno(){
		return $this->ano;
	}

	public function setAno($ano){
		$this->ano = $ano;
	}

}
?>
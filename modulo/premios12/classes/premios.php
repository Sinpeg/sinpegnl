<?php
class Premios{
	private $codigo;
	private $Unidade;
	private $orgao;
	private $categoria;
	private $nome;
	private $qtde;
	private $Tipo;
	private $data;
	private $ano;
	
	
	public function _construct($codigo, $Unidade,$orgao,$categoria,$nome,$qtde,$Reconhecimento,$data,$ano,$Tipo) {
		$this->codigo = $codigo;
		$this->Unidade = $Unidade;
		$this->orgao =$orgao;
		$this->categoria = $categoria;
		$this->nome = $nome;
		$this->qtde = $qtde;
		$this->data = $data;
		$this->ano = $ano;
		$this->Tipo=$Reconhecimento;
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

	public function setUnidade( $sub){
		$this->Unidade =$Unidade;
	}

	public function getOrgao(){
		return $this->orgao;
	}

	public function setOrgao($orgao){
		$this->orgao = $orgao;
	}

	public function getCategoria(){
		return $this->categoria;
	}
	
	public function setCategoria($categoria){
		$this->categoria = $categoria;
	}
	public function getNome(){
		return $this->nome;
	}
	
	public function setNome($nome){
		$this->nome = $nome;
	}
	

	public function getQtde(){
		return $this->qtde;
	}
	
	public function setQtde($qtde){
		$this->qtde = $qtde;
	}

	public function getData(){
		return $this->data;
	}
	
	public function setData($data){
		$this->data = $data;
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
		
	public function setTipo($tipo){
		$this->tipo=$tipo;
	}
		
}
?>
<?php
 class Qprodsaude {
 	private $codigo;
 	private $Unidade;
 	private $Tipo;
 	private $quantidade;
 	private $ano;

 	public function _construct($codigo, $unidade,$tipo,$quantidade,$ano) {
 		$this->codigo = $codigo;
 		$this->Unidade = $unidade;
 		$this->Tipo = $tipo;
 		$this->quantidade = $quantidade;
 		$this->ano = $ano;

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

 	public function setUnidade( $unidade){
 		$this->Unidade =$unidade;
 	}

 	public function getTipo(){
 		return $this->Tipo;
 	}

 	public function setTipo($tipo){
 		$this->Tipo= $tipo;
 	}

 	public function getAno(){
 		return $this->ano;
 	}

 	public function setAno($ano){
 		$this->ano = $ano;
 	}
 		
 	public function getQuantidade(){
 		return $this->quantidade;
 	}

 	public function setQuantidade($quantidade){
 		$this->quantidade = $quantidade;
 	}
 		
 }
 ?>
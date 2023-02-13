<?php
 class Local {
 	private $codigo;
 	private $nome;
 	private $tipo;

 	public function _construct($codigo, $nome,$tipo) {
 		$this->codigo = $codigo;
 		$this->nome = $nome;
 		$this->tipo = $tipo;

 	}

 	public function getCodigo(){
 		return $this->codigo;
 	}

 	public function setCodigo($codigo){
 		$this->codigo = $codigo;
 	}

 	public function getNome(){
 		return $this->nome;
 	}

 	public function setNome($nome){
 		$this->nome = $nome;
 	}
 		
 	public function getTipo(){
 		return $this->tipo;
 	}

 	public function setTipo($tipo){
 		$this->tipo = $tipo;
 	}
 }
 ?>
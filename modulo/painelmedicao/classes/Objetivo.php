<?php

class Objetivo{
	
	private $codigo;
	private $objetivo;
	private $descricao;
	
	
	public function _construct($codigo,$objetivo,$descricao){
		
		$this->codigo = $codigo;
		$this->objetivo = $objetivo;
		$this->descricao = $descricao;
		
	}
	
	public  function getCodigo(){
		return $this->codigo;
	}
	
	public function setCodigo($codigo){
		$this->codigo = $codigo;
	}
	
	public function getObjetivo(){
		return $this->objetivo;
	}
	
	public function setObjetivo($objetivo){
		$this->objetivo = $objetivo;
	}
	
	public function getDescricao(){
		return $this->descricao;
	}
	
	public function setDescricao($descricao){
		$this->descricao = $descricao;
	}
	
}
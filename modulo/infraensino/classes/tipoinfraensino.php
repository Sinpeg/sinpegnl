<?php
 class Tipoinfraensino {
 	private $codigo;
 	private $nome;
 	private $Infraensinos = array();
 	private $Infraensino;


 	public function _construct($codigo, $nome) {
 		$this->codigo = $codigo;
 		$this->nome = $nome;
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

  public function getInfraensino(){
  	return $this->Infraensino;
  }

  public function setInfraensino(Infraensino $infraensino){
  	$this->Infraensino = $infraensino;
  }

  public function getInfraensinos(){
  	return $this->Infraensinos;
  }

  public function setInfraensinos(Infraensino $infraensino){
  	$this->Infraensinos[] = $infraensino;
  }

  public function criaInfraensino($codigoinfraensino,$unidade,$ano,$quantidade){
  	$infraensino = new Infraensino();
  	$infraensino->setCodigo($codigoinfraensino);
  	$infraensino->setUnidade($unidade);
  	$infraensino->setTipo($this);
  	$infraensino->setAno($ano);
  	$infraensino->setQuantidade($quantidade);
  	$this->Infraensino = $infraensino;
  }

  public function adicionaItemInfraensino($codigoinfraensino,$unidade,$ano,$quantidade){
  	$this->criaInfraensino($codigoinfraensino,$unidade,$ano,$quantidade);
  	$this->Infraensinos[] = $this->Infraensino;;

  }

 }
 ?>
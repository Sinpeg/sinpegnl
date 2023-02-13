<?php

 class Tplaboratorio {
 	private $codigo;
 	private $nome;
 	private $codcategoria;
 	private $Labs = array(); //agregação

 	public function _construct($codigo,$codcategoria, $nome) {
 		$this->codigo = $codigo;
 		$this->nome = $nome;
 		$this->codcategoria = $codcategoria;

 	}

 	public function getCodigo(){
 		return $this->codigo;
 	}

 	public function setCodigo($codigo){
 		$this->codigo = $codigo;
 	}

 	public function getCodcategoria(){
 		return $this->codcategoria;
 	}

 	public function setCodcategoria($codcategoria){
 		$this->codcategoria = $codcategoria;
 	}

 	public function getNome(){
 		return $this->nome;
 	}

 	public function setNome($nome){
 		$this->nome = $nome;
 	}
 	 
 	public function getLabs(){
 		return $this->Labs;
 	}

  public function setLabs(Laboratorio $labs){
  	$this->Labs[] = $labs;
  }


  public function adicionaItemLabs($codlaboratorio,$Unidade,$nome,$capacidade,$sigla,$labensino,$area,$resposta,$cabo,$local,$so, $nestacoes, $situacao,$anoativacao, $anodesativacao){
  	$lab= $this->criaLab($codlaboratorio,$Unidade,$this,$nome,$capacidade,$sigla,$labensino,$area,$resposta,$cabo,$local,$so, $nestacoes, $situacao,$anoativacao, $anodesativacao);
  	$this->Labs[] = $lab;
  }

  public function criaLab($codlaboratorio,$unidade,$nome,$capacidade,$sigla,$labensino,$area,$resposta,$cabo,$local,$so, $nestacoes, $situacao,$anoativacao, $anodesativacao){
  	$lab = new Laboratorio();
  	$lab->setCodlaboratorio($codlaboratorio);
  	$lab->setUnidade($unidade);
  	$lab->setTipo($this);
  	$lab->setNome($nome);
  	$lab->setCapacidade($capacidade);
  	$lab->setSigla($sigla);
  	$lab->setLabensino($labensino);
  	$lab->setArea($area);
  	$lab->setResposta($resposta);
  	$lab->setCabo($cabo);
  	$lab->setLocal($local);
  	$lab->setSo($so);
  	$lab->setNestacoes($nestacoes);
  	$lab->setSituacao($situacao);
  	$lab->setAnoativacao($anoativacao);
  	if ($situacao=="D")
  	$lab->setAnodesativacao($anodesativacao);

  	return $lab;
  }
 }
 ?>
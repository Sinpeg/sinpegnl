<?php
 class Tipoinfraestrutura {
 	private $codigo;
 	private $nome;
 	private $Infraestrutura = array(); //agrega��o
 	private $Infra;
 	private $validade;
 		
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
 	
 	public function getValidade(){
 	    return $this->validade;
 	}
 	
 	public function setValidade($validade){
 	    $this->validade = $validade;
 	}
 		
 	public function getInfra(){
   return $this->Infra;
 	}
  public function setInfraestrutura(Infraestrutura $infraestrutura){
  	$this->Infraestrutura[] = $infraestrutura;
  }

  public function getInfraestrutura(){
  	return $this->Infraestrutura;
  }

  public function criaInfraestrutura($codinfraestrutura,$unidade,$ano,$nome,$sigla,$horainicio,
  $horafim,$adistancia,$pcd,$area,$capacidade,$anodesativacao,$situacao){
  	$infraunidade = new Infraestrutura();
  	$infraunidade->setCodinfraestrutura($codinfraestrutura);
  	$infraunidade->setUnidade($unidade);
  	$infraunidade->setNome($nome);
  	$infraunidade->setAnoativacao($ano);
  	$infraunidade->setTipo($this);
  	$infraunidade->setSigla($sigla);
  	$infraunidade->setHorainicio($horainicio);
  	$infraunidade->setHorafim($horafim);
  	$infraunidade->setAdistancia($adistancia);
  	$infraunidade->setPcd($pcd);
  	$infraunidade->setArea($area);
  	$infraunidade->setCapacidade($capacidade);
  	if ($situacao=="D"){
  		$infraunidade->setAnodesativacao($anodesativacao);
  	}
  	$infraunidade->setSituacao($situacao);
  	$this->Infra = $infraunidade;
  }

  public function adicionaItemInfraestrutura($codinfraestrutura,$unidade,$ano,$nome,$sigla,
  $horainicio,$horafim,$adistancia,$pcd,$area,$capacidade,$anodesativacao,$situacao){
  	$this->criaInfraestrutura($codinfraestrutura,$unidade,$ano,$nome,
  	$sigla,$horainicio,$horafim,$adistancia,$pcd,$area,$capacidade,$anodesativacao,$situacao);
  	$this->Infraestrutura[] = $this->getInfra();

  }

 }
 ?>
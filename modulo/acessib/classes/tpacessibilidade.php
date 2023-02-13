<?php
 class Tpacessibilidade {
 	private $codigo;
 	private $nome;
 	private $Acessib;//1 ou vários
 	 
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
 		
 	public function getAcessib(){
 		return $this->Acessib;
 	}
 		
 	public function setAcessib(Estruturaacessibilidade $acessib){
 		$this->Acessib = $acessib;
 	}
 		
 	public function criaAcessib($codigoestrutura,$unidade,$ano,$quantidade){
 		$acess = new Acessibilidade();
 		$acess->setCodigoestrutura($codigoestrutura);
 		$acess->setUnidade($unidade);
 		$acess->setTipo($this);
 		$acess->setAno($ano);
 		$acess->setQuantidade($quantidade);
 		$this->Acessib =  $acess;
 	}
 		
 		
 	/*  public function adicionaItemAcessib ($codigoestrutura,$unidade,$ano,$quantidade){
 	 $acess = $this->criaAcessib($codigoestrutura,$unidade,$ano,$quantidade);
 	$this->Acessibs[] = $acess;
 		
 	} */

 }
 ?>
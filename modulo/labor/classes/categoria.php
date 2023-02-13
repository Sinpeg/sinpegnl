<?php
 class Categoria {
 	private $codigo;
 	private $nome;
 	private $Tplabs = array(); //agregação

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
 		
  public function setTplabs(Tplaboratorio $tplabs){
  	$this->Tplabs[] = $tplabs;
  }


  public function getTplabs($tplabs){
  	$this->Tplabs[] = $tplabs;

  }

  public function adicionaItemTplabs($codcategoria,$codigo,$nome){
  	$tplabs = $this->criaTplab($codcategoria,$codigo,$nome);
  	$this->Tplabs[] = $tplabs;
  }

  public function criaTplab($codcategoria,$codigo,$nome){
  	$tplab = new Tplaboratorio();
  	$tplab->setCodigo($codigo);
  	$tplab->setCodcategoria($codcategoria);
  	$tplab->setNome($nome);
  	return $tplab;
  }
 }
 ?>
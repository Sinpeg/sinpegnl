<?php
class Servico {
	private $codigo;
	private $Subunidade;
	private $nome;
	private $Sp;
	private $Sps=array();
	
	

	

	public function getCodigo(){
		return $this->codigo;
	}

	public function setCodigo($codigo){
		$this->codigo = $codigo;
	}

	public function getSubunidade(){
		return $this->subunidade;
	}

	public function setSubunidade(Unidade $subunidade){
		$this->Subunidade = $subunidade;
	}

	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}
	
	public function getSps(){
		return $this->Sps;
	}
	
	public function setSps(Servproced $sp){
		$this->Sps[] = $sp;
	}
	
	public function getSp(){
		return $this->Sp;
	}
	
	public function setSp(Servproced $sp){
		$this->Sp = $sp;
	}
	public function criaSp($codigo,$Procedimento){
		$s = new Servproced();
		$s->setCodigo($codigo);
		$s->setServico($this);
		$s->setProcedimento($Procedimento);
		$this->Sp = $s;
	}
	
	public function adicionaItemProcedimento($codigo,$Procedimento){
		$this->criaSp($codigo,$Procedimento);
		$this->Sps[] = $this->Sp;
	}

}
?>
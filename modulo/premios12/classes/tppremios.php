<?php
class Tppremios{
	private $codPremio;
	private $nome;
	private $Reconhec;


	public function _construct($codPremio,$nome) {
		$this->codPremio = $codPremio;
		$this->nome= $nome;
	
	}

	public function getCodpremio(){
		return $this->codPremio;
	}

	
	public function setCodpremio($codigo){
		$this->codPremio= $codigo;
	}

	
	public function getNome(){
		return $this->nome;
	}
	public function setNome($nome){
		$this->nome= $nome;
	}
	
	public function getReconhecimento(){
		return $this->Reconhec;
	}
	
	public function setReconhecimento(Tppremios $reconhecimento){
		$this->Reconhec = $reconhecimento;
	}
	


	public function criaPremios($codigo, $Sub,$orgao,$categoria,$nome,$qtde,$data,$ano) {
			
		$r = new Premios();
		$r->setCodigo($codigo);
		$r->setOrgao($orgao) ;
		$r->setNome($nome);
		$r->setSub($Sub);
		$r->setCategoria($categoria);
		$r->setQtde($qtde) ;
		$r->setData($data);
		
		$r->setAno($ano);
		
		$r->setTipo($this) ;
	}
	
	public function criaPremios( $Unidade,$orgao,$categoria,$nome,$qtde,$data,$ano) {
			
		$r = new Premios();
		$r->setOrgao($orgao) ;
		$r->setNome($nome);
		$r->setSub($Unidade);
		$r->setCategoria($categoria);
		$r->setQtde($qtde) ;
		$r->setData($data);
	
		$r->setAno($ano);
	
		$r->setTipo($this) ;
	}
}
?>
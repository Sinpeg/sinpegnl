<?php
class Premios{
	private $codigo;
	private $orgao;
	private $nome;
	private $categoria;
	private $Unidade;
	private $qtde;
	private $qtdei;//discente
	private $qtdeo;//docente
	private $qtdet;	//tecnico
	private $Rec;
	private $pais;
	private $link;
	private $ano;
	
	public function _construct($codigo, $unidade,$orgao,$nome,$qtde,$ano,$rec,$cat,$pais,$link) {
		$this->codigo = $codigo;
		$this ->orgao =$orgao;
		$this->nome = $nome;
		$this->Unidade = $unidade;
		$this->Rec = $rec;
		$this->qtde = $qtde;
		$this->ano = $ano;
		$this->categoria=$cat;
		$this->pais=$pais;
		$this->link=$link;
	}

	public function getCodigo(){
		return $this->codigo;
	}

	public function setCodigo($codigo){
		$this->codigo= $codigo;
	}

	public function getUnidade(){
		return $this->Unidade;
	}

	public function setUnidade( $unidade){
		$this->Unidade =$unidade;
	}

	public function getOrgao(){
		return $this->orgao;
	}

	public function setOrgao($orgao){
		$this->orgao = $orgao;
	}

	public function getQtde(){
		return $this->qtde;
	}
	
	public function setQtde($qtde){
		$this->qtde = $qtde;
	}
	
	public function getQtdei(){
		return $this->qtdei;
	}
	
	public function setQtdei($qtdei){
		$this->qtdei = $qtdei;
	}
	
	public function getQtdeo(){
		return $this->qtdeo;
	}
	
	public function setQtdeo($qtdeo){
		$this->qtdeo = $qtdeo;
	}
	
    public function getQtdet(){
		return $this->qtdet;
	}
	
	public function setQtdet($qtdet){
		$this->qtdet = $qtdet;
	}
	
	
	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}

	 
	public function getAno(){
		return $this->ano;
	}

	public function setAno($ano){
		$this->ano = $ano;
	}
	
	public function getRec(){
		return $this->Rec;
	}
	
	public function setRec($r){
		$this->Rec = $r;
	}
	
	public function getCategoria(){
		return $this->categoria;
	}
	
	public function setCategoria($cat){
		$this->categoria = $cat;
	}
	
	public function getPais(){
	    return $this->pais;
	}
	
	public function setPais($pais){
	    $this->pais = $pais;
	}
	
	public function getLink(){
	    return $this->link;
	}
	
	public function setLink($link){
	    $this->link = $link;
	}

}

?>
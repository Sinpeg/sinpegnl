<?php
class Infraestrutura {
	private $codinfraestrutura;
	private $anoativacao;
	private $nome;
	private $anodesativacao;
	private $Tipo;
	private $sigla;
	private $Unidade;
	private $horainicio;
	private $horafim;
	private $adistancia;
	private $pcd;
	private $area;
	private $capacidade;
	private $situacao;
	 

	public function _construct($codinfraestrutura, $unidade,$anoativacao,$nome,$tipo,$sigla,$horainicio,
	$horafim,$adistancia,$pcd,$area,$capacidade,$anodesativacao,$situacao) {
		$this->codinfraestrutura = $codinfraestrutura;
		$this->ano = $anoativacao;
		$this->nome = $nome;
		$this->Tipo = $tipo;
		$this->sigla = $sigla;
		$this->Unidade = $unidade;
		$this->horainicio = $horainicio;
		$this->horafim = $horafim;
		$this->adistancia = $adistancia;
		$this->pcd = $pcd;
		$this->area = $area;
		$this->capacidade = $capacidade;
		$this->situacao = $situacao;
		$this->anodesativacao = $anodesativacao;

	}

	public function getCodinfraestrutura(){
		return $this->codinfraestrutura;
	}

	public function setCodinfraestrutura($codigo){
		$this->codinfraestrutura = $codigo;
	}


	public function getAnoativacao(){
		return $this->anoativacao;
	}

	public function setAnoativacao($anoativacao){
		$this->anoativacao = $anoativacao;
	}
	 
	public function getAnodesativacao(){
		return $this->anodesativacao;
	}
	 
	public function setAnodesativacao($anodesativacao){
		$this->anodesativacao = $anodesativacao;
	}
	 
	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}
	 
	public function getSituacao(){
		return $this->situacao;
	}
	 
	public function setSituacao($situacao){
		$this->situacao = $situacao;
	}
	 
	public function getSigla(){
		return $this->sigla;
	}

	public function setSigla($sigla){
		$this->sigla = $sigla;
	}
	 
	public function getTipo(){
		return $this->Tipo;
	}

	public function setTipo(Tipoinfraestrutura $tipo){
		$this->Tipo = $tipo;
	}

	public function getUnidade(){
		return $this->Unidade;
	}

	public function setUnidade($unidade){
		$this->Unidade = $unidade;
	}

	public function getHorainicio(){
		return $this->horainicio;
	}

	public function setHorainicio($horainicio){
		$this->horainicio = $horainicio;
	}

	public function getHorafim(){
		return $this->horafim;
	}

	public function setHorafim($horafim){
		$this->horafim = $horafim;
	}
	public function getAdistancia(){
		return $this->adistancia;
	}

	public function setAdistancia($adistancia){
		$this->adistancia = $adistancia;
	}

	public function getPcd(){
		return $this->pcd;
	}

	public function setPcd($pcd){
		$this->pcd = $pcd;
	}

	public function getArea(){
		return $this->area;
	}

	public function setArea($area){
		$this->area = $area;
	}


	public function getCapacidade(){
		return $this->capacidade;
	}

	public function setCapacidade($capacidade){
		$this->capacidade = $capacidade;
	}
}

?>
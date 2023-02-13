<?php

class Avaliacaofinal {
	
	private $codigo;
	private $documento;
	private $calendario;
	private $avaliacao;
	private $rat;
	private $periodo;
	private $situacao;
	
	public function setCodigo($codigo) {
		$this->codigo = $codigo;
	}
	
	public function getCodigo() {
		return $this->codigo;
	}
	
	public function setDocumento(Documento $documento) {
		$this->documento = $documento;
	}
	
	public function getDocumento() {
		return $this->documento;
	}
	
	public function setCalendario(Calendario $calendario){
		$this->calendario = $calendario;
	}
	
	public function getCalendario(){
		return $this->calendario;
	}
	
	public function setAvaliacao($avaliacao){
		$this->avaliacao = $avaliacao;
	}
	
	public function setSitucao($situacao){
		$this->situacao = $situacao;
	}
	
	public function getSituacao(){
		return $this->situacao;
	}
	
	public function getAvaliacao(){
		return $this->avaliacao;
	}
	
	public function setRat($rat){
		$this->rat = $rat;
	}
	
	public function getRat(){
		return $this->rat;
	}
	
	
		
	public function setPeriodo($periodo){
		$this->periodo = $periodo;
	}
	
	public function getPeriodo(){
		return $this->periodo;
	}
}
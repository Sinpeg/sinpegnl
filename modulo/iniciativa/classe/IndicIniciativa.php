<?php
class IndicIniciativa {
	
	private $codigo;
	private $mapaindicador;
	private $iniciativa;
	private $anoinicial;
	private $periodoinicial;
	private $anofinal;
	private $periodofinal;
	
	
		public function setCodigo($codigo) {
		$this->codigo = $codigo;
	}
	
	public function getCodigo() {
		return $this->codigo;
	}
	public function setMapaindicador(Mapaindicador $mapaindicador) {
		$this->mapaindicador = $mapaindicador;
	}
	
	public function getMapaindicador() {
		return $this->mapaindicador;
	}
    
    public function setIniciativa(Iniciativa $iniciativa) {
		$this->iniciativa = $iniciativa;
	}
	
	public function getIniciativa() {
		return $this->iniciativa;
	}
	
	public function setAnoinicial( $anoinicial) {
		$this->anoinicial = $anoinicial;
	}
	
	public function getAnoinicial() {
		return $this->anoinicial;
	}
	
	
	public function setAnofinal( $anofinal) {
		$this->anofinal = $anofinal;
	}
	
	public function getAnofinal() {
		return $this->anofinal;
	}
	
	public function setPeriodofinal( $periodofinal) {
		$this->periodofinal = $periodofinal;
	}
	
	public function getPeriodofinal() {
		return $this->periodofinal;
	}
	
  public function setPeriodoinicial( $periodoinicial) {
		$this->periodoinicial = $periodoinicial;
	}
	
	public function getPeriodoinicial() {
		return $this->periodoinicial;
	}
}
?>
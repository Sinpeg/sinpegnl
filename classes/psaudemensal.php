<?php
class Psaudemensal {
	private $codigo;
	private $Servproced;
	private $Local;
	private $ano;
	private $mes;
	private $ndocentes;
	private $ndiscentes;
	private $npesquisadores;
	private $npessoasatendidas;
	private $nexames;
	private $nprocedimentos;



	
	public function getCodigo(){
		return $this->codigo;
	}

	public function setCodigo($codigo){
		$this->codigo = $codigo;
	}

	public function getServproced(){
		return $this->Servproced;
	}

	public function setServproced(Servproced $sp){
		$this->Servproced = $sp;
	}


	public function getLocal(){
		return $this->Local;
	}

	public function setLocal($local){
		$this->Local = $local;
	}

	public function getAno(){
		return $this->ano;
	}

	public function setAno($ano){
		$this->ano = $ano;
	}

	public function getMes(){
		return $this->mes;
	}

	public function setMes($mes){
		$this->mes = $mes;
	}

	public function getNdocentes(){
		return $this->ndocentes;
	}

	public function setNdocentes($ndocentes){
		$this->ndocentes = $ndocentes;
	}

	public function getNdiscentes(){
		return $this->ndiscentes;
	}

	public function setNdiscentes($ndiscentes){
		$this->ndiscentes = $ndiscentes;
	}

	public function getNpesquisadores(){
		return $this->npesquisadores;
	}

	public function setNpesquisadores($npesquisadores){
		$this->npesquisadores = $npesquisadores;
	}
	public function setNpessoasatendidas($npessoasatendidas){
		$this->npessoasatendidas = $npessoasatendidas;

	}

	public function getNpessoasatendidas(){
		return $this->npessoasatendidas;

	}

	public function setNprocedimentos($nprocedimentos){
			$this->nprocedimentos = $nprocedimentos;

		}

	public function getNprocedimentos(){
			return $this->nprocedimentos;

	}
	public function setNexames($nexames){
		$this->nexames = $nexames;

	}

	public function getNexames(){
		return $this->nexames;

	}

}
?>

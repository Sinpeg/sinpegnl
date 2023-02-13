<?php
 class Servproced {
 	private $codigo;
 	private $Servico;
 	private $Procedimento;
 	private $Psaudeufpa=array();
 	private $Psaude;

 

 	public function getCodigo(){
 		return $this->codigo;
 	}

 	public function setCodigo($codigo){
 		$this->codigo = $codigo;
 	}



    public function getServico(){
 		return $this->Servico;
 	}

 	public function setServico(Servico $s){
 		$this->Servico = $s;
 	}

 	public function getProcedimento(){
 		return $this->Procedimento;
 	}

 	public function setProcedimento(Procedimento $p){
 		$this->Procedimento = $p;
 	}


 	public function getPsaudeufpa(){
 	 return $this->Psaudeufpa;
 	}

 	public function setPsaudeufpa(Psaudemensal $ps){
 	$this->Psaudeufpa[] = $ps;
 	}

 	public function getPsaude(){
 		return $this->Psaude;
 	}

 	public function setPsaude(Psaudemensal $ps){
 		$this->Psaude = $ps;
 	}

 	public function criaPsaude($codigo,$procedimento,$local, $ano,$mes,$ndocentes,$ndiscentes,
 	$npesquisadores,$npessoasatendidas,$nprocedimentos,$nexames){
 	$ps = new Psaudemensal();
 	
 	$ps->setCodigo($codigo);
 	
 	$ps->setServproced($this);
 	
 	$ps->setLocal($local);
 	
 	$ps->setAno($ano);
 	
 	$ps->setMes($mes);
 	$ps->setNdocentes($ndocentes);
 	$ps->setNdiscentes($ndiscentes);
 	$ps->setNpesquisadores($npesquisadores);
 	$ps->setNpessoasatendidas($npessoasatendidas);
 	$ps->setNprocedimentos($nprocedimentos);

 	$ps->setNexames($nexames);

 	$this->Psaude =  $ps;
 	}


 	public function adicionaItemPsaudemensal($codigo,$procedimento,$local, $ano,$mes,$ndocentes,$ndiscentes,
 	$npesquisadores,$npessoasatendidas,$nprocedimentos,$nexames){
 	$this->criaPsaude($codigo,$procedimento,$local, $ano,$mes,$ndocentes,$ndiscentes,
 	$npesquisadores,$npessoasatendidas,$nprocedimentos,$nexames);
 	$this->Psaudeufpa[]=$this->Psaude;

 	}
 }
 ?>

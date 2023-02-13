<?php
class Edprofissionallivre{
	private $codigo;
	private $Tipo;
	private $nomecurso;
	private $ingressantes1;
	private $ingressantes2;
	private $matriculados1;
	private $matriculados2;
	private $aprovados1;
	private $aprovados2;
	private $concluintes1;
	private $concluintes2;
	private $ano;

	public function _construct($codigo,$Tipo,$nomecurso, $ingressantes1,$ingressantes2,
	 $matriculados1, $matriculados2, $aprovados1, $aprovados2,$concluintes1,$concluintes2,$ano) {
		$this->codigo = $codigo;
		$this ->Tipo =$Tipo;
		$this->nomecurso = $nomecurso;
		$this->matriculados1 = $matriculados1;
		$this->matriculados2 = $matriculados2; //1 e 2 semestres
		$this->ingressantes1 = $ingressantes1;
		$this->ingressantes2 = $ingressantes2;
		$this->aprovados1 = $aprovados1;
		$this->aprovados2 = $aprovados2;
		$this->concluintes1 = $concluintes1;
		$this->concluintes2 = $concluintes2;
		$this->ano = $ano;
	}

	public function getCodigo(){
		return $this->codigo;
	}

	public function setCodigo($codigo){
		$this->codigo= $codigo;
	}


	public function getTipo(){
		return $this->Tipo;
	}

	public function setTipo( Tdmedprofissionallivre $t){
		$this->Tipo= $t;
	}

	public function getNomecurso(){
		return $this->nomecurso;
	}

	public function setNomecurso( $nomecurso){
		$this->nomecurso =$nomecurso;
	}

	public function setMatriculados1( $matriculados1){
		$this->matriculados1 =$matriculados1;
	}

	public function getMatriculados1(){
		return $this->matriculados1;
	}

	public function setMatriculados2( $matriculados2){
		$this->matriculados2 =$matriculados2;
	}


   public function getMatriculados2(){
		return $this->matriculados2;
	}

	public function setIngressantes1( $ingressantes1){
		$this->ingressantes1 =$ingressantes1;
	}
	
   public function getIngressantes1(){
		return $this->ingressantes1;
	}

	public function setIngressantes2( $ingressantes2){
		$this->ingressantes2 =$ingressantes2;
	}
	
	public function getIngressantes2(){
		return $this->ingressantes2;
	}
	public function setAprovados1( $aprovados1){
		$this->aprovados1 =$aprovados1;
	}
	
	public function getAprovados1(){
		return $this->aprovados1;
	}

	public function setAprovados2( $aprovados2){
		$this->aprovados2 =$aprovados2;
	}
	
	public function getAprovados2(){
		return $this->aprovados2;
	}
	
	public function setConcluintes1( $concluintes1){
		$this->concluintes1 =$concluintes1;
	}
	
	public function getConcluintes1(){
		return $this->concluintes1;
	}
	
	public function setConcluintes2( $concluintes2){
		$this->concluintes2 =$concluintes2;
	}
	
	public function getConcluintes2(){
		return $this->concluintes2;
	}
	
	
	public function getAno(){
		return $this->ano;
	}

	public function setAno($ano){
		$this->ano = $ano;
	}

	 
}
?>
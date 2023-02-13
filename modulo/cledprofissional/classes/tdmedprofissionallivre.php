<?php
class Tdmedprofissionallivre {
	private $codigo;
	private $categoria;
	private $Edproflivres = array(); //agregação
    private $Edproflivre;
    
	public function _construct($codigo, $categoria) {
		$this->codigo = $codigo;
		$this->categoria = $categoria;

	}

	public function getCodigo(){
		return $this->codigo;
	}

	public function setCodigo($codigo){
		$this->codigo = $codigo;
	}



	public function getCategoria(){
		return $this->categoria;
	}

	public function setCategoria($categoria){
		$this->categoria = $categoria;
	}
		
	public function setEdproflivres(Edprofissionallivre $tp){
		$this->Edproflivres[] = $tp;
	}


	public function getEdproflivres(){
		return $this->Edproflivres;

	}
	
	public function setEdproflivre(Edprofissionallivre $tp){
		$this->Edproflivre= $tp;
	}
	
	
	public function getEdproflivre(){
     return $this->Edproflivre;	
	}

	public function adicionaItemEdprofl($codigo,$nomecurso, $ingressantes1,$ingressantes2,
	 $matriculados1, $matriculados2, $aprovados1, $aprovados2,$concluintes1,$concluintes2,$ano){
		$this->criaEdproflivre($codigo,$nomecurso, $ingressantes1,$ingressantes2,
	 $matriculados1, $matriculados2, $aprovados1, $aprovados2,$concluintes1,$concluintes2,$ano);
		$this->Edproflivres[] = $this->Edproflivre;
	}

	public function criaEdproflivre($codigo,$nomecurso, $ingressantes1,$ingressantes2,
	 $matriculados1, $matriculados2, $aprovados1, $aprovados2,$concluintes1,$concluintes2,$ano){
		$tp = new Edprofissionallivre();
		$tp->setCodigo($codigo);
		$tp->setTipo($this);
		$tp->setNomecurso($nomecurso);
		$tp->setMatriculados1($matriculados1);
		$tp->setMatriculados2($matriculados2); //1 e 2 semestres
		$tp->setIngressantes1($ingressantes1);
		$tp->setIngressantes2($ingressantes2);
		$tp->setAprovados1($aprovados1);
		$tp->setAprovados2($aprovados2);
		$tp->setConcluintes1($concluintes1);
		$tp->setConcluintes2($concluintes2);
		$tp->setAno($ano);
		$this->Edproflivre = $tp;
	}
}
?>

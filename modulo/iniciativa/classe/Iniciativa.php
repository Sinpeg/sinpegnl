<?php
class Iniciativa {
	
	private $codIniciativa;
	private $nome;
	private $unidade;
	private $finalidade;
	private $coordenador;
	private $indicIniciativa;
	private $resultiniciativa;
	private $anoinicio;
    private $anofinal;
	// 	public function __construct($codigo, $documento, $perspectiva, $objetivoPDI, $calendario, $propMapa, $dataCdastro, $visao) {
	
	// 		$this->codigo = $codigo;
	// 		$this->documento = $documento;
	// 		$this->perspectiva = $perspectiva;
	// 		$this->objetivoPDI = $objetivoPDI;
	// 		$this->calendario = $calendario;
	// 		$this->propMapa = $propMapa;
	// 		$this->dataCadastro = $dataCdastro;
	// 		$this->visao = $visao;
	
	// 	}
	
	public function setCodIniciativa($codIniciativa) {
		$this->codIniciativa = $codIniciativa;
	}
	
	public function getCodIniciativa() {
		return $this->codIniciativa;
	}
	
	public function setNome($nome) {
		$this->nome = $nome;
	}
	
	public function getNome() {
		return $this->nome;
	}
	
    public function setAnoinicio($anoinicio) {
		$this->anoinicio = $anoinicio;
	}
	
	public function getAnoinicio() {
		return $this->anoinicio;
	}
	
   public function setAnofinal($anofinal) {
		$this->anofinal = $anofinal;
	}
	
	public function getAnofinal() {
		return $this->anofinal;
	}
	public function setUnidade($unidade) {
		$this->unidade = $unidade;
	}
	
	public function getUnidade() {
		return $this->unidade;
	}	
	public function setFinalidade($finalidade){
		$this->finalidade = $finalidade;
	}
	
	public function getFinalidade(){
		return $this->finalidade;
	}
	
	public function setCoordenador($coordenador) {
		$this->coordenador = $coordenador;
	}
	
	public function getCoordenador() {
		return $this->coordenador;
	}
	
	    public function setIndicIniciativa($indicIniciativa) {
			$this->indicIniciativa = $indicIniciativa;
		}
		
		public function getIndicIniciativa() {
			return $this->indicIniciativa;
		}
		
	    
	   public function criaIndicIniciativa($codigo,$mapaindicador){
	        $m = new IndicIniciativa();
	        
	        $m->setCodigo($codigo);
		    $m->setMapaindicador($mapaindicador);
		    $m->setIniciativa($this);
	
	        
	        $this->indicIniciativa=$m;
	
     }
     
    public function setResultiniciativa(ResultIniciativa $resultiniciativa) {
			$this->resultiniciativa = $resultiniciativa;
		}
		
		public function getResultiniciativa() {
			return $this->resultiniciativa;
		}
		
		

		
  public function criaResultIniciativa($codigo, $calendario,  $situacao, $pfcapacit, $pfrecti,$pfinfraf,$pfrecf,$pfplanj, $outros, $periodo){
	       $objiniresult=new ResultIniciativa();

	
            $objiniresult->setCodigo($codigo) ;
            $objiniresult->setCalendario($calendario);      
            $objiniresult->setIniciativa($this);           
            $objiniresult->setSituacao($situacao);
            $objiniresult->setPfcapacit($pfcapacit);
            $objiniresult->setPfrecti($pfrecti);
            $objiniresult->setPfinfraf($pfinfraf);
            $objiniresult->setPfrecf($pfrecf);
            $objiniresult->setPfplanj($pfplanj);
            $objiniresult->setOutros($outros);
            $objiniresult->setPeriodo($periodo);
	        $this->resultiniciativa=$objiniresult;
	                   // echo $codigo."-".$this->resultiniciativa->getCodigo().','. $situacao.','.$pfcapacit."criareult<br>";
	        
	
     }
		

	
   
}

?>
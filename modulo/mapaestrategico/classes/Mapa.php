<?php
class Mapa {
	
	private $codigo;
	private $documento;
	private $perspectiva;
	private $objetivoPDI;
	private $propMapa;
	private $anoinicio;
	private $periodoinicial;
	private $anofim;
	private $periodofinal;
	private $dataCadastro;
    private $dataAlteracao;
	private $mapaindicador;
    private $solicitacaoeditobjetivo;
    
	
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
	
	public function setPerspectiva(Perspectiva $Perspectiva) {
		$this->perspectiva = $Perspectiva;
	}
	
	public function getPerspectiva() {
		return $this->perspectiva;
	}
	
	public function setObjetivoPDI(Objetivo $objetivoPDI) {
		$this->objetivoPDI = $objetivoPDI;
	}
	
	public function getObjetivoPDI() {
		return $this->objetivoPDI;
	}	public function setCalendario(Calendario $calendario){
		$this->calendario = $calendario;
	}
	
	public function getCalendario(){
		return $this->calendario;
	}
	
	public function setPropMapa($propMapa) {
		$this->propMapa = $propMapa;
	}
	
	public function getPropMapa() {
		return $this->propMapa;
	}
	
	public function setDataCadastro($dataCadastro) {
		$this->dataCadastro = $dataCadastro;
	}
	
	public function getDataCadastro() {
		return $this->dataCadastro;
	}
    
    public function setMapaindicador($mapaindicador) {
		$this->mapaindicador = $mapaindicador;
	}
	
	public function getMapaindicador() {
		return $this->mapaindicador;
	}
	
	
	
	public function setAnoinicio( $anoinicio) {
		$this->anoinicio = $anoinicio;
	}
	
	public function getAnoinicio() {
		return $this->anoinicio;
	}
	
	
	public function setAnofim( $anofim) {
		$this->anofim = $anofim;
	}
	
	public function getAnofim() {
		return $this->anofim;
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
	
	public function setDataAlteracao($dataAlteracao) {
		$this->dataAlteracao = $dataAlteracao;
	}
	
	public function getDataAlteracao() {
		return $this->dataAlteracao;
	}
	
	public function setSolicitacaoeditobjetivo($solicitacaoeditobjetivo) {
		$this->solicitacaoeditobjetivo = $solicitacaoeditobjetivo;
	}
	
	public function getSolicitacaoeditobjetivo() {
		return $this->solicitacaoeditobjetivo;
	}
    
     public function  criaMapaIndicador($codigo,$indicador,$propindicador ) {
    	$m=new Mapaindicador();
     
        $m->setCodigo($codigo);
        $m->setMapa($this) ;
        $m->setIndicador($indicador);
    	$m->setPropindicador($propindicador);
      
        $this->mapaindicador=$m;
    
     
    }
    
 public function  criaMapaIndicadorSol($codigo,$indicador,$propindicador,$anoinicial,$periodoinicial,$anofinal,$periodofinal,$tipo ) {
    	$m=new Mapaindicador();
     
        $m->setCodigo($codigo);
        $m->setMapa($this) ;
        $m->setIndicador($indicador);
    	$m->setPropindicador($propindicador);
    	$m->setAnofim($anofinal);
        $m->setAnoinicio($anoinicial);
        $m->setPeriodofinal($periodofinal);
        $m->setPeriodoinicial($periodoinicial);
        $m->setTipoassociado($tipo);
        $this->mapaindicador=$m;
    
    }
	
    public function  criaSolicitacaoEditObjetivo( $codigo, $unidade, $objetivo,$justificativa,$anexo,
     $situacao, $usuarioanalista,$anogestao,$tipo){
    	$m=new SolicitacaoEditObjetivo();
     
        $m->setCodigo($codigo);
        $m->setMapa($this);
        $m->setObjetivo($objetivo);
        $m->setUnidade($unidade);
    	$m->setJustificativa($justificativa);
    	$m->setObjetivo($objetivo);
    	$m->setAnexo($anexo);
    	$m->setSituacao($situacao);
    
    	$m->setUsuarioanalista($usuarioanalista);
    	$m->setAnogestao($anogestao);
    	$m->setTipo($tipo);
    	    	
        $this->solicitacaoeditobjetivo=$m;
    
     
    }
}

?>
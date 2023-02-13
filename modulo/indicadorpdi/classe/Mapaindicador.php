<?php 


class Mapaindicador {

    private $codigo;
    private $indicador; 
    private $mapa; // objeto da classe mapa
    private $propindicador;
    private $tipoAssociado;/*preenchido com PDU, quando o objetivo estrategico vem do pdi e nao é incluido ao pdu e
     sao vinculados ao objetivo estratégico outros indicadores, alem dos que existem no pdu*/
    private $anoinicial;
    private $periodoinicial;
    private $anofim;
    private $periodofinal;
    
    private $meta;
    private $arraymeta;
    private $solicitacaoeditindicador;
	private $solicitacaoversaoindicador;
	private $arraysolicitacaoeditIndicador;
	private $arraysolicitacaoversaoindicador;
	
    public function __construct() {
        
    }

    
    public function setArraymeta($arraymeta){
    	$this->arraymeta = $arraymeta;
    }
    
    public function getArraymeta(){
    	return $this->arraymeta;
    }
    
    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getCodigo() {
        return $this->codigo;
    }
    
    public function setsolicitacaoeditindicador($s) {
    	$this->solicitacaoeditindicador = $s;
    }
    
    public function getsolicitacaoeditindicador() {
    	return $this->solicitacaoeditindicador;
    }
    
    public function setsolicitacaoversaoindicador($s) {
    	$this->solicitacaoversaoindicador = $s;
    }
    
    public function getsolicitacaoversaoindicador() {
    	return $this->solicitacaoversaoindicador;
    }
    
    public function setarraysolicitacaoversaoindicador($s) {
    	$this->arraysolicitacaoversaoindicador = $s;
    }
    
    public function getarraysolicitacaoversaoindicador() {
    	return $this->arraysolicitacaoversaoindicador;
    }
    
    
    public function setIndicador(Indicador $ind) {
    	$this->indicador = $ind;
    }
    
    public function getIndicador() {
    	return $this->indicador;    }
    
    
    public function setMapa(Mapa $mapa) {
    	$this->mapa = $mapa;
    }
    
    public function getMapa() {
    	return $this->mapa;
    }
    
    public function setPropindicador($propindicador) {
    	$this->propindicador = $propindicador;
    }
    
    public function getPropindicador() {
    	return $this->propindicador;
    }
    
    public function setMeta($meta) {
    	$this->meta = $meta;
    }
    
    public function getMeta() {
    	return $this->meta;
    }
    
   
   public function setTipoassociado($tipo) {
        $this->tipoAssociado = $tipo;
    }

    public function getTipoassociado() {
        return $this->tipoAssociado;
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
	
    
    public function criaMeta($codigo,$periodo,$calendario,$meta1,$ano,$metrica,$cumulativo,$anoinicial,$periodoinicial){
    	$m = new Meta();
    	
    
    	$m->setCodigo($codigo) ;
    	$m->setPeriodo($periodo);
    	$m->setMeta($meta1);
    	$m->setAno($ano);
    	$m->setMetrica($metrica);
    	$m->setCumulativo($cumulativo);
    	$m->setCalendario($calendario);
    	$m->setMapaindicador($this);
    	$m->setAnoinicial($anoinicial);
    	$m->setPeriodoinicial($periodoinicial);
    	    	 
    	 
    	$this->meta=$m;
    
    }
    
    
    public function criaMetaSol($codigo,$periodo,$calendario,$meta1,$ano,$metrica,$cumulativo,$anoinicial,$periodoinicial,$anofinal,$periodofinal){
    	$m = new Meta();
    	
    
    	$m->setCodigo($codigo) ;
    	$m->setPeriodo($periodo);
    	$m->setMeta($meta1);
    	$m->setAno($ano);
    	$m->setMetrica($metrica);
    	$m->setCumulativo($cumulativo);
    	$m->setCalendario($calendario);
    //	$m->setSituacao($situacao);
    	$m->setMapaindicador($this);
    	$m->setAnoinicial($anoinicial);
    	$m->setPeriodoinicial($periodoinicial);
    	$m->setAnofinal($anofinal);
    	$m->setPeriodofinal($periodofinal); 
    	$this->meta=$m;
    
    }
    public function adicionaItemMeta($codigo,$periodo,$calendario,$meta1,$ano,$metrica,$cumulativo,$anoinicial,$periodoinicial,$anofinal,$periodofinal,$indexArray){
     $this->criaMeta($codigo,$periodo,$calendario,$meta1,$ano,$metrica,$anoinicial,$periodoinicial,$anofinal,$periodofinal,$cumulativo);
     $this->arraymeta[$indexArray] = $this->meta;
     
    }
    
    


        
     public function criaIndicIniciativa($codigo,$iniciativa){
        $m = new IndicIniciativa();
        
        $m->setCodigo($codigo);
	    $m->setMapaindicador($this);
	    $m->setIniciativa($iniciativa);

        
        $this->indicIniciativa=$m;

     }
     
      public function criaSolicitacaoeditindicador( $codigo, $unidade, $indicador, $justificativa, $anexo, 
       $situacao,$dataemanalise, $usuarioanalista,$tipo,$anogestao){
        $m = new SolicitacaoEditIndicador();
        
        $m->setCodigo($codigo);
	    $m->setMapaindicador($this);
	    $m->setUnidade($unidade);
     	$m->setIndicador($indicador);
    	$m->setJustificativa($justificativa);
    	$m->setAnexo($anexo);
    	$m->setSituacao($situacao);    	
    	$m->setDataemanalise($dataemanalise);
    	$m->setUsuarioanalista($usuarioanalista);
    	$m->setTipo($tipo);
    	$m->setAnogestao($anogestao);
        $this->solicitacaoeditindicador=$m;
     }
     
     
     public function adicionaItemSoliciteditIndicador($codigo, $unidade, $indicador, $justificativa, $anexo,
       $situacao,$datasolicitacao, $dataemanalise, $usuarioanalista,$tipo,$indexArray){
     $this->criaSolicitacaoeditindicador($codigo, $unidade, $indicador, $justificativa, $anexo, $caminho, $situacao, $datasolicitacao,
      $dataemanalise, $usuarioanalista, $tipo);
     $this->arraysolicitacaoeditIndicador[$indexArray] = $this->solicitacaoeditindicador;
     
    }
     
     
 public function criaSolicitacaoversaoindicador($mapaInd, $tipo	,$codigo, $unidade, $indicador, $nome, $calculo, $interpretacao,
       $justificativa, $anexo,  $situacao, $datasolicitacao , $dataemanalise, $usuarioanalista,$anogestao){
        $m = new SolicitacaoVersaoIndicador();
          
        $m->setCodigo($codigo);
	    $m->setMapaIndicador($mapaInd);
	    $m->setUnidade($unidade);
     	$m->setIndicador($indicador);
     	$m->setNome($nome);
     	$m->setCalculo($calculo);
     	$m->setInterpretacao($interpretacao);
    	$m->setJustificativa($justificativa);
    	$m->setAnexo($anexo);
    	$m->setSituacao($situacao);    	
    	$m->setDataemanalise($dataemanalise);
    	$m->setUsuarioanalista($usuarioanalista);
    	$m->setDatasolicitacao($datasolicitacao);
    	$m->setAnogestao($anogestao);
    	$m->setTipo($tipo);
    	
        $this->solicitacaoversaoindicador=$m;
}
     
     
public function adicionaItemSolicitacaoversaoindicador( $mapaInd,$codigo, $unidade, $indicador, $nome, $calculo, $interpretacao,
       $justificativa, $anexo,  $situacao,$datasolicitacao, $dataemanalise, $usuarioanalista,$indexArray,$tipoSolicitacao,$anogestão){
     
	$this->criaSolicitacaoversaoindicador($mapaInd,$codigo, $unidade, $indicador, $nome, $calculo, $interpretacao, $justificativa,
     $anexo, $situacao, $datasolicitacao, $dataemanalise, $usuarioanalista,$anogestão);
       
     $this->arraysolicitacaoversaoindicador[$indexArray][$tipoSolicitacao] = $this->solicitacaoversaoindicador;
     
    } 
     
   }
?>
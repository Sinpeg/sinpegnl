<?php

class Meta {

    private $codigo;
    private $mapaindicador;
    private $calendario;
    private $periodo;
    private $meta;
    private $ano;
    private $metrica; /* P - Percentual; Q - Quantitativo; */
    private $cumulativo; // bandeira que indica se a meta é cumulativa ou não
    //private $situacao;
    private $resultado;
    private $solicitacaorepactuacao;
    private $anoinicial;
    private $periodoinicial;
    private $anofinal;
    private $periodofinal;
    
    public function __construct(){
       
    }
    
    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getCodigo() {
        return $this->codigo;
    }

     public function setMapaindicador($mapaindicador) {
        $this->mapaindicador = $mapaindicador;
    }

    public function getMapaindicador() {
        return $this->mapaindicador;
    }
    
     public function setCalendario($calendario) {
        $this->calendario = $calendario;
    }

    public function getCalendario() {
        return $this->calendario;
    }

    
    public function setPeriodo($periodo) {
        $this->periodo = $periodo;
    }

    public function getPeriodo() {
        return $this->periodo;
    }

    public function setMeta($meta) {
        $this->meta = $meta;
    }

    public function getMeta() {
        return $this->meta;
    }

    public function setAno($ano) {
    	$this->ano = $ano;
    }
    
    public function getAno() {
    	return $this->ano;
    }

    public function setMetrica($metrica) {
        $this->metrica = $metrica;
    }

    public function getMetrica() {
        return $this->metrica;
    }

   
    public function setCumulativo($cumulativo) {
        $this->cumulativo = $cumulativo;
    }
    
    public function getCumulativo() {
        return $this->cumulativo;
    }
    
       public function setResultado($resultado) {
        $this->resultado = $resultado;
    }
    
    public function getResultado() {
        return $this->resultado;
    }
    
   public function setAnoinicial($anoinicial) {
        $this->anoinicial = $anoinicial;
    }
    
    public function getAnoinicial() {
        return $this->anoinicial;
    }
      public function setPeriodoinicial($periodoinicial) {
        $this->periodoinicial = $periodoinicial;
    }
    
    public function getPeriodoinicial() {
        return $this->periodoinicial;
    }
    
    
 public function setAnofinal($anofinal) {
        $this->anofinal = $anofinal;
    }
    
    public function getAnofinal() {
        return $this->anofinal;
    }
    
     public function setPeriodofinal($periodofinal) {
        $this->periodofinal = $periodofinal;
    }
    
    public function getPeriodofinal() {
        return $this->periodofinal;
    }
    
    
    public function setSolicitacaorepactuacao($solicitacaorepactuacao) {
        $this->solicitacaorepactuacao = $solicitacaorepactuacao;
    }
    
    public function getSolicitacaorepactuacao() {
        return $this->solicitacaorepactuacao;
    }
    
   public function criaResultado($codigo,$meta_atingida,$periodo,$analiseCritica,$acaopdi){
       $this->resultado=new Resultado();
       $this->resultado->setCodigo($codigo);
       $this->resultado->setMetaAtingida($meta_atingida);
       $this->resultado->setPeriodo($periodo) ;
       $this->resultado->setAnaliseCritica($analiseCritica);
       $this->resultado->setMeta($this);
       $this->resultado->setAcao($acaopdi);

   
   }
   

public function  criaSolicitacaoRepactuacao( $codigo, $unidade, $justificativa,$novameta,$anexo,
     $situacao, $datasolicitacao,$dataemanalise,$usuarioanalista,$anobase){
     
     	
     	
    	$m=new SolicitacaoRepactuacao();
     
        $m->setCodigo($codigo);
        $m->setMeta($this) ;
        $m->setUnidade($unidade);
    	$m->setNovameta($novameta);
    	$m->setJustificativa($justificativa);
    	$m->setAnexo($anexo);
    	$m->setSituacao($situacao);
    	$m->setDatasolicitacao($datasolicitacao);
    	$m->setDataemanalise($dataemanalise);
    	$m->setUsuarioanalista($usuarioanalista);
        $m->setAnogestao($anobase);
        $this->solicitacaorepactuacao=$m;
    
     
    }

}

?>

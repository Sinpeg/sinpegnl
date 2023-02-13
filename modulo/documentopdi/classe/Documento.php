<?php

class Documento {

    private $codigo;
  // private $codigoPDI; // código do documento institucional
    private $nome;
    private $anoinicial;
    private $anofinal;
    private $situacao;
    private $missao;
    private $visao;
    private $unidade;
    private $anexo;
    private $tamarq;
    private $tipoarq;
    private $nomearq;
    private $tipo;
    private $mapa; 
    private $solicitacaoinsertobjetivo;
    private $objetivo;


    public function __construct() {
       
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setAnoinicial($anoinicial) {
        $this->anoinicial = $anoinicial;
    }

    public function getAnoinicial() {
        return $this->anoinicial;
    }

    public function setAnofinal($anofinal) {
        $this->anofinal = $anofinal;
    }

    public function getAnofinal() {
        return $this->anofinal;
    }

    public function setSituacao($situacao) {
        $this->situacao = $situacao;
    }

    public function getSituacao() {
        return $this->situacao;
    }

    public function setMissao($missao) {
        $this->missao = $missao;
    }

    public function getMissao() {
        return $this->missao;
    }

    public function setVisao($visao) {
        $this->visao = $visao;
    }

    public function getVisao() {
        return $this->visao;
    }

    public function setUnidade($unidade) {
        $this->unidade = $unidade;
    }

    public function getUnidade() {
        return $this->unidade;
    }

    public function setAnexo($anexo) {
        $this->anexo = $anexo;
    }

    public function getAnexo() {
        return $this->anexo;
    }

     public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function getTipo() {
        return $this->tipo;
    }
    
    public function setNomearq($nomearq) {
        $this->nomearq = $nomearq;
    }

    public function getNomearq() {
        return $this->nomearq;
    }
     public function setTamarq($tamarq) {
        $this->tamarq = $tamarq;
    }

    public function getTamarq() {
        return $this->tamarq;
    }

     public function setTipoarq($tipoarq) {
        $this->tipoarq = $tipoarq;
    }

    public function getTipoarq() {
        return $this->tipoarq;
    }

    
    public function setMapa($mapa) {
        $this->mapa = $mapa;
    }

    public function getMapa() {
        return $this->mapa;
    }
    
    //Calendário
    public function setCalendario($calendario) {
    	$this->calendario = $calendario;
    }
    
    public function getCalendario() {
    	return $this->calendario;
    
    }
     //Calendário
    public function setSolicitacaoinsertobjetivo($solicitacaoinsertobjetivo) {
    	$this->solicitacaoinsertobjetivo = $solicitacaoinsertobjetivo;
    }
    
    public function getSolicitacaoinsertobjetivo() {
    	return $this->solicitacaoinsertobjetivo;
    }
    
     public function  criaMapa($codigo,  $perspectiva, $objetivoPDI,  $propMapa,  $anoinicio, $periodoinicial) {
    	$m=new Mapa();     
        $m->setCodigo($codigo);
        $m->setDocumento($this) ;
        $m->setPerspectiva($perspectiva);
    	$m->setObjetivoPDI($objetivoPDI);
    	$m->setPropMapa($propMapa);
        $m->setAnoinicio($anoinicio);
    	$m->setPeriodoinicial($periodoinicial);
              
      
        $this->mapa=$m;      
    }
    public function  criaMapaSol($codigo,  $perspectiva, $objetivoPDI,  $propMapa,  $anoinicio, $periodoinicial,
    	 $anofim, $periodofinal) {
    	$m=new Mapa();
    	$m->setCodigo($codigo);
    	$m->setDocumento($this) ;
    	$m->setPerspectiva($perspectiva);
    	$m->setObjetivoPDI($objetivoPDI);
    	$m->setPropMapa($propMapa);
    	$m->setAnofim($anofim);
    	$m->setAnoinicio($anoinicio);
    	$m->setPeriodofinal($periodofinal);
    	$m->setPeriodoinicial($periodoinicial);
    	$this->mapa=$m;
    }
    
    
    
    public function criaCalendario($codigo,$unidade,
        $datainianalise,$datafimanalise,
        $datainianalisefinal,$datafimanalisefinal
        ,$datainiRAA        ,$datafimRAA
        ,$datainielabpdu,$datafimelabpdu
        ,$datainielabpt,$datafimelabpt
        ,$datainialtpdu,$datafimaltpdu
        ,$anogestao,$usuario) {
            $cal= new Calendario();
            $cal->setCodigo($codigo);
            $cal->setUnidade( $unidade );
            $cal->setDocumento( $this );
            $cal->setDatainianalise($datainianalise);
            $cal->setDatafimanalise($datafimanalise);
            $cal->setDatainianalisefinal($datainianalisefinal);
            $cal->setDatafimanalisefinal($datafimanalisefinal);
            $cal->setDatainiRAA($datainiRAA);
            $cal->setDatafimRAA($datafimRAA);
            
            $cal->setDatainielabpdu($datainielabpdu);
            $cal->setDatafimelabpdu($datafimelabpdu);
            $cal->setDatainielabpt($datainielabpt);
            $cal->setDatafimelabpt($datafimelabpt);
            $cal->getDatainialterapdu($datainialtpdu);
            $cal->getDatafimalterapdu($datafimaltpdu);
            $cal->setAnogestao($anogestao);
            $cal->setUsuario($usuario);
            $this->calendario = $cal;
    }

public function  criaSolicitacaoInsercaoObjetivo( $codigo, $unidade, $objetivo,$justificativa,$anexo,
     $situacao, $datasolicitacao,$dataemanalise,$usuarioanalista,$anogestao){
    	$m=new SolicitacaoInsercaoObjetivo();
     
        $m->setCodigo($codigo);
        $m->setDocumento($this);
        $m->setObjetivo($objetivo);
        $m->setUnidade($unidade);
    	$m->setJustificativa($justificativa);
    	$m->setAnexo($anexo);
    	$m->setSituacao($situacao);
    	$m->setDatasolicitacao($datasolicitacao);
    	$m->setDataemanalise($dataemanalise);
    	$m->setUsuarioanalista($usuarioanalista);
    	$m->setAnogestao($anogestao);
    	    	
        $this->solicitacaoinsertobjetivo=$m;
    
     
    }

}

?>

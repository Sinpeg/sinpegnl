<?php
class Solicitacao {

    private $codigo;
    private $unidade;
    private $justificativa;
    private $anexo;
    private $situacao;
    private $datasolicitacao;
    private $dataemanalise;
    private $usuarioanalista;
    private $comentario;   
    private $anogestao;
    private $arraycomentario;
    private $tipo;
    
    public function __construct() {
        

    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getCodigo() {
        return $this->codigo;
    }
   
    
    
    public function setUnidade($unidade) {
        $this->unidade = $unidade;
    }

    public function getUnidade() {
        return $this->unidade;
    }
    
    public function setJustificativa($justificativa) {
        $this->justificativa = $justificativa;
    }

    public function getJustificativa() {
        return $this->justificativa;
    }
    
    public function setAnexo($anexo) {
        $this->anexo = $anexo;
    }

    public function getAnexo() {
        return $this->anexo;
    }

  
    public function setSituacao($situacao) {
        $this->situacao = $situacao;
    }

    public function getSituacao() {
        return $this->situacao;
    }
    
    public function setDatasolicitacao($datasolicitacao) {
        $this->datasolicitacao = $datasolicitacao;
    }

    public function getDatasolicitacao() {
        return $this->datasolicitacao;
    }
    
    public function setDataemanalise($dataemanalise) {
    	$this->dataemanalise = $dataemanalise;
    }
    
    public function getDataemanalise() {
    	return $this->dataemanalise;
    }
    
    public function setUsuarioanalista(Usuario $usuarioanalista) {
    	$this->usuarioanalista = $usuarioanalista;
    }
    
    public function getUsuarioanalista() {
    	return $this->usuarioanalista;
    }
    
    
    public function setDataresposta($dataresposta) {
    	$this->dataresposta = $dataresposta;
    }
    
    
    
    public function getComentario() {
    	return $this->comentario;
    }
    
    
      public function setAnogestao($anogestao) {
    	$this->anogestao = $anogestao;
    }
    
    public function getAnogestao() {
    	return $this->anogestao;
    }
    
     public function setTipo($tipo) {
    	$this->tipo = $tipo;
    }
    
    public function getTipo() {
    	return $this->tipo;
    }
    
   
    
 public function  criaComentarioSolicitPDU(   $codigo,  $texto, $datacomentario){
    	$m=new ComentarioSolicitPDU();
     
        $m->setCodigo($codigo);
        $m->setSolicitacao($this);
        $m->setTexto($texto);     
    	$m->setDatacomentario($datacomentario);
    	
    	
    	    	
        $this->comentario=$m;
    
     
    }
    
    
  public function adicionaItemComentarioSolicitPDU($codigo,  $texto, $datacomentario,$indexArray){
     $this->criaComentarioSolicitPDU($codigo,  $texto, $datacomentario);
     $this->arraycomentario[$indexArray] = $this->comentario;
     
    }
    
}
    
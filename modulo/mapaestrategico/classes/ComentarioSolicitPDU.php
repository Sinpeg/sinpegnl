<?php
class ComentarioSolicitPDU {

    private $codigo;
    private $solicitacao;
    private $texto;
    private $datacomentario;
    private $autor;
    
    
    public function __construct() {
        

    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getCodigo() {
        return $this->codigo;
    }
   
    public function setSolcitacao($solicitacao) {
        $this->solicitacao = $solicitacao;
    }

    public function getSolicitacao() {
        return $this->solicitacao;
    }
    
    public function setTexto($texto) {
        $this->texto = $texto;
    }

    public function getTexto() {
        return $this->texto;
    }
    
    public function setAutor($autor) {
    	$this->autor = $autor;
    }
    
    public function getAutor() {
    	return $this->autor;
    }
    
    public function setDatacomentario($Datacomentario) {
        $this->Datacomentario = $Datacomentario;
    }

    public function getDatacomentario() {
        return $this->Datacomentario;
    }

  
   
    
    
}
    
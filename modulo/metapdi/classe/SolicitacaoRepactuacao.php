<?php
/*
 * Esta classe é criada por meta
 */
class SolicitacaoRepactuacao extends Solicitacao {

    
    private $meta;
    private $novameta;
   
    
   
    
    public function setMeta($meta) {
        $this->meta = $meta;
    }

    public function getMeta() {
        return $this->meta;
    }
    
   
     public function setNovameta($novameta) {
        $this->novameta = $novameta;
    }

    public function getNovameta() {
        return $this->novameta;
    }
    
    
    
    
}

 	

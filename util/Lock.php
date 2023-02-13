<?php

class Lock {
    
    private $data; // dados para bloqueio
    private $locked; // está bloqueado?
    private $disabled; // string "disabled"
    private $ano; // ano do lock
    
    public function getAno() {
        return $this->ano;
    }

    public function setAno($ano) {
        $this->ano = $ano;
        return $this;
    }

        public function __construct() {
        $this->locked = false;
        $this->ano = NULL;
    }

    public function getData() {
        return $this->data;
    }

    public function getLocked() {
        return $this->locked;
    }

    public function getDisabled() {
        return $this->disabled;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function setLocked($locked) {
        $this->locked = $locked;
        if ($this->locked) {
            $this->disabled = "disabled";
        }
    }

}

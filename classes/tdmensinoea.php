<?php

class Tdmensinoea {

    private $codigo;
    private $modalidade;
    private $ensino;
    private $serie;
    private $Ensinoea;

    public function _construct($codigo) {
        $this->codigo = $codigo;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getModalidade() {
        return $this->modalidade;
    }

    public function setModalidade($modalidade) {
        $this->modalidade = $modalidade;
    }

    public function getSerie() {
        return $this->serie;
    }

    public function setSerie($serie) {
        $this->serie = $serie;
    }

    public function getEnsino() {
        return $this->ensino;
    }

    public function setEnsino($ensino) {
        $this->ensino = $ensino;
    }

    public function getEnsinoea() {
        return $this->Ensinoea;
    }

    public function setEnsinoea(Ensinoea $ensinoea) {
        $this->Ensinoea = $ensinoea;
    }

    public function criaEnsinoea($codigo, $matriculados, $aprovados, $reprovados, $ano) {
        $ea = new Ensinoea();
        $ea->setCodigo($codigo);
        $ea->setCodtdmensinoea($this);
        $ea->setMatriculados($matriculados);
        $ea->setAprovados($aprovados);
        $ea->setReprovados($reprovados);
        $ea->setAno($ano);
        $this->Ensinoea = $ea;
    }

}

?>
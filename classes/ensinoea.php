<?php

class Ensinoea {

    private $codigo;
    private $codtdmensinoea;
    private $matriculados;
    private $aprovados;
    private $reprovados;
    private $ano;

    public function _construct($codigo, $codtdmensinoea, $matriculados, $aprovados, $reprovados, $evasao, $ano) {
        $this->codigo = $codigo;
        $this->codtdmensinoea = $codtdmensinoea;
        $this->matriculados = $matriculados;
        $this->aprovados = $aprovados;
        $this->reprovados = $reprovados;
        $this->ano = $ano;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getCodtdmensinoea() {
        return $this->codtdmensinoea;
    }

    public function setCodtdmensinoea($codtdmensinoea) {
        $this->codtdmensinoea = $codtdmensinoea;
    }

    public function getAno() {
        return $this->ano;
    }

    public function setAno($ano) {
        $this->ano = $ano;
    }

    public function getMatriculados() {
        return $this->matriculados;
    }

    public function setMatriculados($matriculados) {
        $this->matriculados = (!isset($matriculados)) ? 0 : ($matriculados);
    }

    public function getAprovados() {
        return $this->aprovados;
    }

    public function setAprovados($aprovados) {
        $this->aprovados = $aprovados;
    }

    public function getReprovados() {
        return $this->reprovados;
    }

    public function setReprovados($reprovados) {
        $this->reprovados = $reprovados;
    }

}

?>
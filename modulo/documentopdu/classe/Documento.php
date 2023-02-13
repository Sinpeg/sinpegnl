<?php

class Documento {

    private $codigo;
    private $codigoPDI; // código do documento institucional
    private $nome;
    private $anoinicial;
    private $anofinal;
    private $situacao;
    private $missao;
    private $visao;
    private $unidade;

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

    public function setAnoInicial($anoinicial) {
        $this->anoinicial = $anoinicial;
    }

    public function getAnoInicial() {
        return $this->anoinicial;
    }

    public function setAnoFinal($anofinal) {
        $this->anofinal = $anofinal;
    }

    public function getAnoFinal() {
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

    public function setUnidade(Unidade $unidade) {
        $this->unidade = $unidade;
    }

    public function getUnidade() {
        return $this->unidade;
    }

    public function setCodigoPDI($pdi) {
        $this->codigoPDI = $pdi;
    }

    public function getCodigoPDI() {
        return $this->codigoPDI;
    }

}

?>

<?php

class Tipoprodintelectual {

    private $codigo;
    private $nome;
    private $anuario;
    private $Prodintelectual;
    private $Prodintelectuais = array();

    public function _construct($codigo, $nome) {
        $this->codigo = $codigo;
        $this->nome = $nome;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getAnuario() {
        return $this->anuario;
    }

    public function setAnuario($anuario) {
        $this->anuario = $anuario;
    }

    public function getProdintelectual() {
        return $this->Prodintelectual;
    }

    public function setProdintelectual(Prodintelectual $prodintelectual) {
        $this->Prodintelectual[] = $prodintelectual;
    }

    public function criaProdintelectual($codigo, $unidade, $ano, $quantidade) {
        $prodintelectual = new Prodintelectual();
        $prodintelectual->setCodigo($codigo);
        $prodintelectual->setUnidade($unidade);
        $prodintelectual->setTipo($this);
        $prodintelectual->setAno($ano);
        $prodintelectual->setQuantidade($quantidade);
        $this->Prodintelectual = $prodintelectual;
    }

    public function adicionaItemprodintelectual($codigo, $unidade, $ano, $quantidade) {
        $this->criaProdintelectual($codigo, $unidade, $ano, $quantidade);
        $this->Prodintelectual[] = $this->prodintelectual;
    }

}

?>
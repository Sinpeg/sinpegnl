<?php

class Incubadora {

    private $codigo;
    private $empresasgrad;
    private $empgerados;
    private $projaprovados;
    private $eventos;
    private $capacitrh;
    private $nempreendedores;
    private $consultorias;
    private $partempfeiras;
    private $ano;
    private $unidade;
    private $tipo;

    public function _construct($codigo, $empresasgrad, $empgerados, $projaprovados, $eventos, $capacitrh, $nempreendedores, $consultorias, $partempfeiras, $ano) {
        $this->codigo = $codigo;
        $this->empresasgrad = $empresasgrad;
        $this->empgerados = $empgerados;
        $this->projaprovados = $projaprovados;
        $this->eventos = $eventos;
        $this->capacitrh = $capacitrh;
        $this->nempreendedores = $nempreendedores;
        $this->consultorias = $consultorias;
        $this->partempfeiras = $partempfeiras;
        $this->ano = $ano;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getEmpresasgrad() {
        return $this->Empresasgrad;
    }

    public function setEmpresasgrad($value) {
        $this->Empresasgrad = $value;
    }

    public function getEmpgerados() {
        return $this->empgerados;
    }

    public function setempgerados($value) {
        $this->empgerados = $value;
    }

    public function getProjaprovados() {
        return $this->projaprovados;
    }

    public function setProjaprovados($value) {
        $this->projaprovados = $value;
    }

    public function getEventos() {
        return $this->eventos;
    }

    public function setEventos($value) {
        $this->eventos = $value;
    }

    public function getCapacitrh() {
        return $this->capacitrh;
    }

    public function setCapacitrh($value) {
        $this->capacitrh = $value;
    }

    public function getNempreendedores() {
        return $this->nempreendedores;
    }

    public function setNempreendedores($value) {
        $this->nempreendedores = $value;
    }

    public function getConsultorias() {
        return $this->consultorias;
    }

    public function setConsultorias($value) {
        $this->consultorias = $value;
    }

    public function getPartempfeiras() {
        return $this->partempfeiras;
    }

    public function setPartempfeiras($value) {
        $this->partempfeiras = $value;
    }

    public function getAno() {
        return $this->ano;
    }

    public function setAno($ano) {
        $this->ano = $ano;
    }

    public function setUnidade($unidade) {
        $this->unidade = $unidade;
    }

    public function getUnidade() {
        return $this->unidade;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function getTipo() {
        return $this->tipo;
    }

}

?>
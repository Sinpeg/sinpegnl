<?php

class Sessao {
    
    private $codUnidade;
    private $codusuario;
    private $nomeUnidade;
    private $responsavel;
    private $anobase;
    private $data;
    private $login;
    private $aplicacoes;
    private $codestruturado;
    private $grupo = array();
    private $unidadeResponsavel; //código do SIGAA para a unidade responsável
    private $codunidadesup; // código da unidade superior
    private $idunidade; // id da unidade no SIGAA
//para usuários internos
    private $codunidsel;
    private $nomeunidsel;
    private $modulo;
    private $acao;
    private $codigo;
    private $categoria;



    public function getIdunidade() {
        return $this->idunidade;
    }

    public function setIdunidade($idunidade) {
        $this->idunidade = $idunidade;
    }
    
    public function getCodUnidadeSup() {
        return $this->codunidadesup;
    }

    public function setCodUnidadeSup($codunidadesup) {
        $this->codunidadesup = $codunidadesup;
    }

        
    public function getUnidadeResponsavel() {
        return $this->unidadeResponsavel;
    }

    public function setUnidadeResponsavel($unidadeResponsavel) {
        $this->unidadeResponsavel = $unidadeResponsavel;
    }

   public function _construct($codunidade, $nomeunidade, $codusuario, $responsavel, $anobase, $data) {
        $this->codUnidade = $codunidade;
        $this->codusuario = $codusuario;
        $this->nomeUnidade = $nomeunidade;
        $this->responsavel = $responsavel;
        $this->anobase = $anobase;
        $this->data = $data;
    }
    
    public function isUnidade() {
    	if ($this->unidadeResponsavel==1) {
	  return true;
        }
	else { 
          return false; 
        }
    } 
  
    public function getCodUnidade() {
        return $this->codUnidade;
    }

    public function setCodUnidade($codUnidade) {
        $this->codUnidade = $codUnidade;
    }

    public function getNomeUnidade() {
        return $this->nomeUnidade;
    }

    public function setNomeUnidade($nomeunidade) {
        $this->nomeUnidade = $nomeunidade;
    }

    public function getLogin() {
        return $this->login;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function getCodestruturado() {
        return $this->codestruturado;
    }

    public function setCodestruturado($codestruturado) {
        $this->codestruturado = $codestruturado;
    }

    public function getCodusuario() {
        return $this->codusuario;
    }

    public function setCodusuario($codusuario) {
        $this->codusuario = $codusuario;
    }

    public function getResponsavel() {
        return $this->responsavel;
    }

    public function setResponsavel($responsavel) {
        $this->responsavel = $responsavel;
    }

    public function getAnobase() {
        return $this->anobase;
    }

    public function setAnobase($anobase) {
        $this->anobase = $anobase;
    }

    public function getAplicacoes() {
        return $this->aplicacoes;
    }

    public function setAplicacoes($aplicacoes) {
        $this->aplicacoes = $aplicacoes;
    }

    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

    public function getGrupo() {
        return $this->grupo;
    }


    public function getCodunidsel() {
        return $this->codunidsel;
    }

    public function setCodunidsel($codunidsel) {
        $this->codunidsel = $codunidsel;
    }

    public function getNomeunidsel() {
        return $this->nomeunidsel;
    }

    public function setNomeunidsel($nomeunidsel) {
        $this->nomeunidsel = $nomeunidsel;
    }

    public function getModulo() {
        return $this->modulo;
    }

    public function setModulo($modulo) {
        $this->modulo = $modulo;
    }

    public function getAcao() {
        return $this->acao;
    }

    public function setAcao($acao) {
        $this->acao = $acao;
    }
    
    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }
    
    public function getCategoria() {
        return $this->categoria;
    }
    
    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }
}

?>

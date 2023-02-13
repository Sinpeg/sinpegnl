<?php

class Usuario {

    private $codusuario;
    private $responsavel;
    private $login;
    private $senha;
    private $Unidade;
    private $email;
    private $Arquivo;
    private $Arquivos = array();

    public function _construct($codusuario, $codunidade, $responsavel, $login, $senha, $email) {
        $this->codusuario = $codusuario;
        $this->codunidade = $codunidade;
        $this->responsavel = $responsavel;
        $this->login = $login;
        $this->senha = $senha;
        $this->email = $email;
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

    public function getLogin() {
        return $this->login;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getUnidade() {
        return $this->Unidade;
    }

    public function setUnidade(Unidade $unidade) {
        $this->Unidade = $unidade;
    }

    public function criaUnidade($codunidade, $nomeunidade, $codestruturado) {
        $this->Unidade = new Unidade();
        $this->Unidade->setCodunidade($codunidade);
        $this->Unidade->setNomeunidade($nomeunidade);
        $this->Unidade->setCodestruturado($codestruturado);
    }

    public function getArquivo() {
        return $this->Arquivo;
    }

    public function setArquivo(Arquivo $arquivo) {
        $this->Arquivo = $arquivo;
    }

    public function criaArquivo($codigo, $assunto, $tipo, $nome, $tamanho, $conteudo, $comentario, $dataalteracao, $datainclusao, $ano) {
        $arq = new Arquivo();
        $arq->setCodigo($codigo);
        $arq->setAssunto($assunto);
        $arq->setTipo($tipo);
        $arq->setNome($nome);
        $arq->setConteudo($conteudo);
        $arq->setComentario($comentario);
        $arq->setTamanho($tamanho);
        $arq->setUsuario($this);
        $arq->setDataalteracao($dataalteracao);
        $arq->setDatainclusao($datainclusao);
        $arq->setAno($ano);
        $this->Arquivo = $arq;
    }

    public function getArquivos() {
        return $this->Arquivos;
    }

    public function setArquivos(Arquivo $arquivo) {
        $this->Arquivos[] = $arquivo;
    }

    public function adicionaItemArquivo($codigo, $assunto, $tipo, $nome, $tamanho, $conteudo, $comentario, $dataalteracao, $datainclusao, $ano) {
        $this->criaArquivo($codigo, $assunto, $tipo, $nome, $tamanho, $conteudo, $comentario, $dataalteracao, $datainclusao, $ano);
        $this->Arquivos[] = $this->Arquivo;
    }

}

?>
<?php

class Arquivo {
	private $codigo;
	private $assunto;
	private $tipo;
	private $nome;
	private $conteudo;
	private $comentario;
	private $tamanho;
	private $Usuario;
	private $dataalteracao;
	private $datainclusao;
	private $ano;


	public function _construct($codigo,$assunto, $tipo, $nome,$tamanho,
	$conteudo,$comentario,$Usuario,$dataalteracao,$datainclusao,$ano) {
		$this->codigo = $codigo;
		$this->assunto = $assunto;
		$this ->tipo =$tipo;
		$this->nome=$nome;
		$this->conteudo=$conteudo;
		$this->comentario=$comentario;
		$this->Usuario=$Usuario;
		$this->dataalteracao=$dataalteracao;
		$this->datainclusao=$datainclusao;
		$this->ano = $ano;
	}

	public function getCodigo(){
		return $this->codigo;
	}

	public function setCodigo($codigo){
		$this->codigo = $codigo;
	}

	public function getAssunto(){
		return $this->assunto;
	}

	public function setAssunto($assunto){
		$this->assunto = $assunto;
	}

	public function getTamanho(){
		return $this->tamanho;
	}

	public function setTamanho($tamanho){
		$this->tamanho = $tamanho;
	}
	public function getTipo(){
		return $this->tipo;
	}

	public function setTipo($tipo){
		$this->tipo= $tipo;
	}

	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome= $nome;
	}

	public function setConteudo($conteudo){
		$this->conteudo = $conteudo;
	}
	public function getConteudo(){
		return $this->conteudo;
	}

	public function setComentario($comentario){
		$this->comentario = $comentario;
	}
	public function getComentario(){
		return $this->comentario;
	}
	public function setUsuario(Usuario $usuario){
		$this->Usuario = $usuario;
	}
	public function getUsuario(){
		return $this->Usuario;
	}

	public function setDatainclusao($datainclusao){
		$this->datainclusao = $datainclusao;
	}
	public function getDatainclusao(){
		return  $this->datainclusao ;
	}
	public function setDataalteracao($dataalteracao){
		$this->dataalteracao = $dataalteracao;
	}
	public function getDataalteracao(){
		return $this->dataalteracao;
	}

	public function getAno(){
		return $this->ano;
	}

	public function setAno($ano){
		$this->ano = $ano;
	}
}
?>
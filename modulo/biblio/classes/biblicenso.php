<?php

class Biblicenso{
	

	private $idBiblicenso;
	private $Bibliemec;
	private $nassentos;//Assentos
	private $nempDomicilio;//Empr&eacute;imos domiciliares
	private $nempBiblio;//Empr&eacute;imos entre Bibliotecas
	private $frequencia;
	private $nconsPresencial;//Consulta presencial
	private $nconsOnline;//Consulta online
	private $fbuscaIntegrada;//Usa ferramenta de busca integrada
	private $comutBibliog;//Realiza comuta&ccedil;&oacute;e bibliogr&aacute;ficas
	private $servInternet;//Oferece servi&ccedil;os pela Internet
	private $nusuariosTpc;//Usu&aacute;rios treinados em programas de capacita&ccedil;&atilde;o
	private $redeSemFio;//Possui rede sem fio
	private $partRedeSociais;//Participa de redes sociais
	private $nitensAcervoElet;//Itens do acervo eletr&ocirc;nico
	private $nitensAcervoImp;//Itens do acervo impresso
	private $atendTreiLibras;//Possui Atendente ou Membro da Equipe de Atendimento Treinado na Língua Brasileira de Sinais- LIBRAS
	private $acervoFormEspecial;//Possui acervo em formato especial (Braile/sonoro)
	private $appFormEspecial;//Sítios e aplicações desenvolvidos para que pessoas percebam, compreendam, naveguem e utilizaem serviços oferecidos
	private $planoFormEspecial;//Plano de aquisi&ccedil;&atilde;o gradual de acervo bibliogr&aacute;fico dos conte&uacute;dos b&aacute;sicos em formato especial
	private $sofLeitura;//Disponibiliza software de leitura para pessoas com baixa vis&atilde;o
	private $tecVirtual;//Teclado virtual
	private $impBraile;//Disponibiliza impressoras em Braile
	private $portalCapes;//Possui acesso ao Portal Capes de Peri&oacute;dicos
	private $outrasBases;//Assina outras bases de dados
	private $bdOnlineSerPub;//Possui biblioteca digital de Servi&ccedil;o P&uacute;blico
	private $catOnlineSerPub;//Possui cat&aacute;logo online de Servi&ccedil;o P&uacute;blico
	private $ano;

	



	public function _construct($Bibliemec, $nassentos, $nempDomicilio,
			$nempBiblio, $frequencia, $nconsPresencial,
			$nconsOnline, $fbuscaIntegrada, $comutBibliog,
			$servInternet, $nusuariosTpc, $redeSemFio,
			$partRedeSociais, $nitensAcervoElet, $nitensAcervoImp,
			$atendTreiLibras, $acervoFormEspecial,
			$appFormEspecial, $planoFormEspecial, $sofLeitura,
			$tecVirtual, $impBraile, $portalCapes,
			$outrasBases, $bdOnlineSerPub, $catOnlineSerPub,
			$ano) {
		$this->Bibliemec = $Bibliemec;
		$this->nassentos = $nassentos;
		$this->nempDomicilio = $nempDomicilio;
		$this->nempBiblio = $nempBiblio;
		$this->frequencia = $frequencia;
		$this->nconsPresencial = $nconsPresencial;
		$this->nconsOnline = $nconsOnline;
		$this->fbuscaIntegrada = $fbuscaIntegrada;
		$this->comutBibliog = $comutBibliog;
		$this->servInternet = $servInternet;
		$this->nusuariosTpc = $nusuariosTpc;
		$this->redeSemFio = $redeSemFio;
		$this->partRedeSociais = $partRedeSociais;
		$this->nitensAcervoElet = $nitensAcervoElet;
		$this->nitensAcervoImp = $nitensAcervoImp;
		$this->atendTreiLibras = $atendTreiLibras;
		$this->acervoFormEspecial = $acervoFormEspecial;
		$this->appFormEspecial = $appFormEspecial;
		$this->planoFormEspecial = $planoFormEspecial;
		$this->sofLeitura = $sofLeitura;
		$this->tecVirtual = $tecVirtual;
		$this->impBraile = $impBraile;
		$this->portalCapes = $portalCapes;
		$this->outrasBases = $outrasBases;
		$this->bdOnlineSerPub = $bdOnlineSerPub;
		$this->catOnlineSerPub = $catOnlineSerPub;
		$this->ano = $ano;
	}

	public function  getIdBiblicenso() {
		return $this->idBiblicenso;
	}

	public function setIdBiblicenso($idBiblicenso) {
		$this->idBiblicenso = $idBiblicenso;
	}

	public function getBibliemec() {
		return $this->Bibliemec;
	}

	public function setBibliemec($bibliemec) {
		$this->Bibliemec = $bibliemec;
	}

	public function getNassentos() {
		return $this->nassentos;
	}

	public function setNassentos($nassentos) {
		$this->nassentos = $nassentos;
	}

	public function getNempDomicilio() {
		return $this->nempDomicilio;
	}

	public function setNempDomicilio($nempDomicilio) {
		$this->nempDomicilio = $nempDomicilio;
	}

	public function getNempBiblio() {
		return $this->nempBiblio;
	}

	public function setNempBiblio($nempBiblio) {
		$this->nempBiblio = $nempBiblio;
	}

	public function getFrequencia() {
		return $this->frequencia;
	}

	public function setFrequencia($frequencia) {
		$this->frequencia = $frequencia;
	}

	public function getNconsPresencial() {
		return $this->nconsPresencial;
	}

	public function setNconsPresencial($nconsPresencial) {
		$this->nconsPresencial = $nconsPresencial;
	}

	public function getNconsOnline() {
		return $this->nconsOnline;
	}

	public function setNconsOnline($nconsOnline) {
		$this->nconsOnline = $nconsOnline;
	}

	public function getFbuscaIntegrada() {
		return $this->fbuscaIntegrada;
	}

	public function setFbuscaIntegrada($fbuscaIntegrada) {
		$this->fbuscaIntegrada = $fbuscaIntegrada;
	}

	public function getComutBibliog() {
		return $this->comutBibliog;
	}

	public function setComutBibliog($comutBibliog) {
		$this->comutBibliog = $comutBibliog;
	}

	public function getServInternet() {
		return $this->servInternet;
	}

	public function setServInternet($servInternet) {
		$this->servInternet = $servInternet;
	}

	public function getNusuariosTpc() {
		return $this->nusuariosTpc;
	}

	public function setNusuariosTpc($nusuariosTpc) {
		$this->nusuariosTpc = $nusuariosTpc;
	}

	public function getRedeSemFio() {
		return $this->redeSemFio;
	}

	public function setRedeSemFio($redeSemFio) {
		$this->redeSemFio = $redeSemFio;
	}

	public function getPartRedeSociais() {
		return $this->partRedeSociais;
	}

	public function setPartRedeSociais($partRedeSociais) {
		$this->partRedeSociais = $partRedeSociais;
	}

	public function getNitensAcervoElet() {
		return $this->nitensAcervoElet;
	}

	public function setNitensAcervoElet($nitensAcervoElet) {
		$this->nitensAcervoElet = $nitensAcervoElet;
	}

	public function getNitensAcervoImp() {
		return $this->nitensAcervoImp;
	}

	public function setNitensAcervoImp($nitensAcervoImp) {
		$this->nitensAcervoImp = $nitensAcervoImp;
	}

	public function getAtendTreiLibras() {
		return $this->atendTreiLibras;
	}

	public function setAtendTreiLibras($atendTreiLibras) {
		$this->atendTreiLibras = $atendTreiLibras;
	}

	public function getAcervoFormEspecial() {
		return $this->acervoFormEspecial;
	}

	public function setAcervoFormEspecial($acervoFormEspecial) {
		$this->acervoFormEspecial = $acervoFormEspecial;
	}

	public function getAppFormEspecial() {
		return $this->appFormEspecial;
	}

	public function setAppFormEspecial($appFormEspecial) {
		$this->appFormEspecial = $appFormEspecial;
	}

	public function getPlanoFormEspecial() {
		return $this->planoFormEspecial;
	}

	public function setPlanoFormEspecial($planoFormEspecial) {
		$this->planoFormEspecial = $planoFormEspecial;
	}

	public function getSofLeitura() {
		return $this->sofLeitura;
	}

	public function setSofLeitura($sofLeitura) {
		$this->sofLeitura = $sofLeitura;
	}

	public function getTecVirtual() {
		return $this->tecVirtual;
	}

	public function setTecVirtual($tecVirtual) {
		$this->tecVirtual = $tecVirtual;
	}

	public function getImpBraile() {
		return $this->impBraile;
	}

	public function setImpBraile($impBraile) {
		$this->impBraile = $impBraile;
	}

	public function getPortalCapes() {
		return $this->portalCapes;
	}

	public function setPortalCapes($portalCapes) {
		$this->portalCapes = $portalCapes;
	}

	public function getOutrasBases() {
		return $this->outrasBases;
	}

	public function setOutrasBases($outrasBases) {
		$this->outrasBases = $outrasBases;
	}

	public function getBdOnlineSerPub() {
		return $this->bdOnlineSerPub;
	}

	public function setBdOnlineSerPub($bdOnlineSerPub) {
		$this->bdOnlineSerPub = $bdOnlineSerPub;
	}

	public function getCatOnlineSerPub() {
		return $this->catOnlineSerPub;
	}

	public function setCatOnlineSerPub($catOnlineSerPub) {
		$this->catOnlineSerPub = $catOnlineSerPub;
	}

	public function getAno() {
		return $this->ano;
	}

	public function setAno($ano) {
		$this->ano = $ano;
	}



}



?>

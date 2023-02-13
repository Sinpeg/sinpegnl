<?php
 
class Bibliemec  {
 
 	private  $idBibliemec;
 	private  $Unidade;
 	private  $codEmec;
 	private  $sigla;
 	private  $nome;
 	private  $tipo;
 	private  $Biblicenso;
 	private $blofertas = array();
 	private $Bloferta;
 	
 
 	public function _construct($codEmec, $nome, $tipo) {
 		$this->codEmec = codEmec;
 		$this->nome = nome;
 		if ($tipo==1)
 			$this->tipo ="Central";
 		else if ($tipo==2) 
 			$this->tipo ="Setorial";

 	}
 
 	public function getIdBibliemec() {
 		return $this->idBibliemec;
 	}
 
 	public function setIdBibliemec($idBibliemec) {
 		$this->idBibliemec = $idBibliemec;
 	}
 
 	public function getUnidade() {
 		return $this->Unidade;
 	}
 
 	public function setUnidade($unidade) {
 		$this->Unidade = $unidade;
 	}
 
 	public function getCodEmec() {
 		return $this->codEmec;
 	}
 
 	public function setCodEmec($codEmec) {
 		$this->codEmec = $codEmec;
 	}
 
 	public function getSigla() {
 		return $this->sigla;
 	}
 
 	public function setSigla($sigla) {
 		$this->sigla = $sigla;
 	}
 
 	public function getNome() {
 		return $this->nome;
 	}
 
 	public function setNome($nome) {
 		$this->nome = $nome;
 	}
 
 	public  function getTipo() {

 		return $this->tipo;
 	}
 
 	public function setTipo($tipo) {
 		if ($tipo==1)
 			$this->tipo ="Central";
 		else if ($tipo==2)
 			$this->tipo ="Setorial";
 	}
 
 	public function getBiblicenso() {
 		return $this->Biblicenso;
 	}
 
 	public function setBiblicenso($Biblicenso) {
 		$this->Biblicenso = $Biblicenso;
 	}
 
 	public function getBloferta() {
 		return $this->Bloferta;
 	}
 	
 	public function setBloferta($bloferta) {
 		$this->Bloferta = $bloferta;
 	}
 	public function getBlofertas() {
 		return $this->blofertas;
 	}
 	
 	public function setBlofertas($blofertas) {
 		$this->blofertas = $blofertas;
 	}
 	
 	public function criaBloferta($Local){
 		$b = new Bloferta();
 		$b->setLocaloferta($Local);
 		$b->setBibliemec($this);
 		$this->Bloferta =$b;
 	
 	}
 	
 	
 	public function criaBiblicenso(  $idBiblicenso,	   $nassentos,	  $nempDomicilio,	 
 	  $nempBiblio,	  $frequencia,
	  $nconsPresencial,	  $nconsOnline,	  $fbuscaIntegrada,	  $comutBibliog,
	  $servInternet,	  $nusuariosTpc,	  $redeSemFio,	  $partRedeSociais,
	  $nitensAcervoElet,	  $nitensAcervoImp,	  $atendTreiLibras,	  $acervoFormEspecial,
	  $appFormEspecial,	  $planoFormEspecial,	  $sofLeitura,	  $tecVirtual,	  $impBraile,
	  $portalCapes,	  $outrasBases,	  $bdOnlineSerPub,	  $catOnlineSerPub,	  $ano){
 		$b = new Biblicenso();
 		$b->setAno($ano);
 		$b->setBibliemec($this);
 		$b->setIdBiblicenso($idBiblicenso);
 		$b->setNassentos($nassentos);
 		$b->setNempDomicilio($nempDomicilio);
 		$b->setNempBiblio($nempBiblio);
 		$b->setFrequencia($frequencia);
 		$b->setNconsPresencial($nconsPresencial);
 		$b->setNconsOnline($nconsOnline);
 		$b->setFbuscaIntegrada($fbuscaIntegrada);
 		$b->setComutBibliog($comutBibliog);
 		$b->setServInternet($servInternet);
 		$b->setNusuariosTpc($nusuariosTpc);
 		$b->setRedeSemFio($redeSemFio);
 		$b->setPartRedeSociais($partRedeSociais);
 		$b->setNitensAcervoElet($nitensAcervoElet);
 		$b->setNitensAcervoImp($nitensAcervoImp);
 		$b->setAtendTreiLibras($atendTreiLibras);
 		$b->setAcervoFormEspecial($acervoFormEspecial);
 		$b->setAppFormEspecial($appFormEspecial);
 		$b->setPlanoFormEspecial($planoFormEspecial);
 		$b->setSofLeitura($sofLeitura);
 		$b->setTecVirtual($tecVirtual);
 		$b->setImpBraile($impBraile);
 		$b->setPortalCapes($portalCapes);
 		$b->setOutrasBases($outrasBases);
 		$b->setBdOnlineSerPub($bdOnlineSerPub);
 		$b->setCatOnlineSerPub($catOnlineSerPub);
 		$this->Biblicenso=$b;
 	}
 	
 	public function criaBiblicenso1(  	   $nassentos,	  $nempDomicilio,
 			$nempBiblio,	  $frequencia,
 			$nconsPresencial,	  $nconsOnline,	  $fbuscaIntegrada,	  $comutBibliog,
 			$servInternet,	  $nusuariosTpc,	  $redeSemFio,	  $partRedeSociais,
 			$nitensAcervoElet,	  $nitensAcervoImp,	  $atendTreiLibras,	  $acervoFormEspecial,
 			$appFormEspecial,	  $planoFormEspecial,	  $sofLeitura,	  $tecVirtual,	  $impBraile,
 			$portalCapes,	  $outrasBases,	  $bdOnlineSerPub,	  $catOnlineSerPub,	  $ano){
 		$b = new Biblicenso();
 		$b->setAno($ano);
 		$b->setBibliemec($this);
 		$b->setNassentos($nassentos);
 		$b->setNempDomicilio($nempDomicilio);
 		$b->setNempBiblio($nempBiblio);
 		$b->setFrequencia($frequencia);
 		$b->setNconsPresencial($nconsPresencial);
 		$b->setNconsOnline($nconsOnline);
 		$b->setFbuscaIntegrada($fbuscaIntegrada);
 		$b->setComutBibliog($comutBibliog);
 		$b->setServInternet($servInternet);
 		$b->setNusuariosTpc($nusuariosTpc);
 		$b->setRedeSemFio($redeSemFio);
 		$b->setPartRedeSociais($partRedeSociais);
 		$b->setNitensAcervoElet($nitensAcervoElet);
 		$b->setNitensAcervoImp($nitensAcervoImp);
 		$b->setAtendTreiLibras($atendTreiLibras);
 		$b->setAcervoFormEspecial($acervoFormEspecial);
 		$b->setAppFormEspecial($appFormEspecial);
 		$b->setPlanoFormEspecial($planoFormEspecial);
 		$b->setSofLeitura($sofLeitura);
 		$b->setTecVirtual($tecVirtual);
 		$b->setImpBraile($impBraile);
 		$b->setPortalCapes($portalCapes);
 		$b->setOutrasBases($outrasBases);
 		$b->setBdOnlineSerPub($bdOnlineSerPub);
 		$b->setCatOnlineSerPub($catOnlineSerPub);
 		$this->Biblicenso=$b;

 	}
 	
 	/*public function criaBiblicenso(  $idBiblicenso,	   $nassentos,	  $nempDomicilio,
 			$nempBiblio,	  $frequencia,
 			$nconsPresencial,	  $nconsOnline,	  $fbuscaIntegrada,	  $comutBibliog,
 			$servInternet,	  $nusuariosTpc,	  $redeSemFio,	  $partRedeSociais,
 			$nitensAcervoElet,	  $nitensAcervoImp,	  $atendTreiLibras,	  $acervoFormEspecial,
 			$appFormEspecial,	  $planoFormEspecial,	  $sofLeitura,	  $tecVirtual,	  $impBraile,
 			$portalCapes,	  $outrasBases,	  $bdOnlineSerPub,	  $catOnlineSerPub,	  $ano){
 		$b = new Biblicenso();
 		$b->setAno($ano);
 		$b->setBibliemec($this);
 		$b->setIdBiblicenso($idBiblicenso);
 		$b->setNassentos($nassentos);
 		$b->setNempDomicilio($nempDomicilio);
 		$b->setNempBiblio($nempBiblio);
 		$b->setFrequencia($frequencia);
 		$b->setNconsPresencial($nconsPresencial);
 		$b->setNconsOnline($nconsOnline);
 		$b->setFbuscaIntegrada($fbuscaIntegrada);
 		$b->setComutBibliog($comutBibliog);
 		$b->setServInternet($servInternet);
 		$b->setNusuariosTpc($nusuariosTpc);
 		$b->setRedeSemFio($redeSemFio);
 		$b->setPartRedeSociais($partRedeSociais);
 		$b->setNitensAcervoElet($nitensAcervoElet);
 		$b->setNitensAcervoImp($nitensAcervoImp);
 		$b->setAtendTreiLibras($atendTreiLibras);
 		$b->setAcervoFormEspecial($acervoFormEspecial);
 		$b->setAppFormEspecial($appFormEspecial);
 		$b->setPlanoFormEspecial($planoFormEspecial);
 		$b->setSofLeitura($sofLeitura);
 		$b->setTecVirtual($tecVirtual);
 		$b->setImpBraile($impBraile);
 		$b->setPortalCapes($portalCapes);
 		$b->setOutrasBases($outrasBases);
 		$b->setBdOnlineSerPub($bdOnlineSerPub);
 		$b->setCatOnlineSerPub($catOnlineSerPub);
 		$this->Biblicenso=$b;
 	}
 	*/
 	public function criaBiblicenso2($idBiblicenso,  	   $nassentos,	  $nempDomicilio,
 			$nempBiblio,	  $frequencia,
 			$nconsPresencial,	  $nconsOnline,	  $fbuscaIntegrada,	  $comutBibliog,
 			$servInternet,	  $nusuariosTpc,	  $redeSemFio,	  $partRedeSociais,
 			$nitensAcervoElet,	  $nitensAcervoImp,	  $atendTreiLibras,	  $acervoFormEspecial,
 			$appFormEspecial,	  $planoFormEspecial,	  $sofLeitura,	  $tecVirtual,	  $impBraile,
 			$portalCapes,	  $outrasBases,	  $bdOnlineSerPub,	  $catOnlineSerPub){
 		$b = new Biblicenso();
 		$b->setBibliemec($this);
 		$b->setNassentos($nassentos);
 		$b->setNempDomicilio($nempDomicilio);
 		$b->setNempBiblio($nempBiblio);
 		$b->setFrequencia($frequencia);
 		$b->setNconsPresencial($nconsPresencial);
 		$b->setNconsOnline($nconsOnline);
 		$b->setFbuscaIntegrada($fbuscaIntegrada);
 		$b->setComutBibliog($comutBibliog);
 		$b->setServInternet($servInternet);
 		$b->setNusuariosTpc($nusuariosTpc);
 		$b->setRedeSemFio($redeSemFio);
 		$b->setPartRedeSociais($partRedeSociais);
 		$b->setNitensAcervoElet($nitensAcervoElet);
 		$b->setNitensAcervoImp($nitensAcervoImp);
 		$b->setAtendTreiLibras($atendTreiLibras);
 		$b->setAcervoFormEspecial($acervoFormEspecial);
 		$b->setAppFormEspecial($appFormEspecial);
 		$b->setPlanoFormEspecial($planoFormEspecial);
 		$b->setSofLeitura($sofLeitura);
 		$b->setTecVirtual($tecVirtual);
 		$b->setImpBraile($impBraile);
 		$b->setPortalCapes($portalCapes);
 		$b->setOutrasBases($outrasBases);
 		$b->setBdOnlineSerPub($bdOnlineSerPub);
 		$b->setCatOnlineSerPub($catOnlineSerPub);
 		$b->setIdBiblicenso($idBiblicenso);
 		$this->Biblicenso=$b;
 	
 	}
 	
 	
 }
 
 
 
 
 
 
 ?>

<?php
class Unidade {
	private $codunidade;
	private $nomeunidade;
	private $codestruturado;
	private $tipounidade;
	private $Cursos = array();
	private $Labs = array();
	private $PremiosUfpa = array();
	private $Premios;
	private $Atividadeextensaoufpa = array();
	private $Atividadeextensao;
	private $Subunidade;
	private $Subunidades=array();
	private $MicrosUfpa = array();
	private $Micros;
	private $Rhetemufpa = array();
	private $Rhufpa;
	private $Servico;
	private $Servicos;
	private $Procedimento;
	private $Procedimentos;

	public function _construct($codunidade, $nomeunidade,$codestruturado) {
		$this->codunidade = $codunidade;
		$this->nomeunidade = $nomeunidade;
		$this->codestruturado = $codestruturado;

	}


	public function getCodunidade(){
		return $this->codunidade;
	}

	public function setCodunidade($codunidade){
		$this->codunidade = $codunidade;
	}

	public function getNomeunidade(){
		return $this->nomeunidade;
	}

	public function setNomeunidade($nomeunidade){
		$this->nomeunidade = $nomeunidade;
	}

	public function getTipounidade(){
		return $this->tipounidade;
	}

	public function setTipounidade($tipounidade){
		$this->tipounidade = $tipounidade;
	}


	public function getCodestruturado(){
		return $this->codestruturado;
	}

	public function setCodestruturado($codestruturado){
		$this->codestruturado = $codestruturado;
	}

	public function getCursos(){
		return $this->Cursos;
	}

	public function setCursos(Curso $curso){
		$this->Cursos[] = $curso;
	}

	public function criaCurso($campus,$CodCursoSis,$CodCurso,$NomeCurso,$DataInicio,$CodEmec){
	 $curso = new Curso();
	 $curso->setUnidade($this);
	 $curso->setCampus($campus);
	 $curso->setCodcursosis($CodCursoSis);
	 $curso->setCodcurso($CodCurso);
	 $curso->setNomecurso($NomeCurso);
	 $curso->setDatainicio($DataInicio);
	 $curso->setCodemec($CodEmec);
	 return $curso;
	}

	public function adicionaItemCursos($campus,$CodCursoSis,$CodCurso,$NomeCurso,$DataInicio,$CodEmec){
		$curso = $this->criaCurso($campus,$CodCursoSis,$CodCurso,$NomeCurso,$DataInicio,$CodEmec);
		$this->Cursos[] = $curso;

	}

	public function adicionaItemCursosLibras($campus,$CodCursoSis,$CodCurso,$NomeCurso,$DataInicio,$CodEmec,$codigo,$Ano){
		$curso = $this->criaCurso($campus,$CodCursoSis,$CodCurso,$NomeCurso,$DataInicio,$CodEmec);
	 $curso->criaLibra($codigo,$Ano);
	 $this->Cursos[] = $curso;
	  
	}


	public function getLabs(){
		return $this->Labs;
	}

	public function setLabs(Laboratorio $labs){
		$this->Labs[] = $labs;
	}


	public function adicionaItemLabs($codlaboratorio,$Tipo,$nome,$capacidade,$sigla,$labensino,$area,$resposta,$cabo,$local,$so, $nestacoes, $situacao,$anoativacao, $anodesativacao){
		$lab= $this->criaLab($codlaboratorio,$Tipo,$nome,$capacidade,$sigla,$labensino,$area,$resposta,$cabo,$local,$so, $nestacoes, $situacao,$anoativacao, $anodesativacao);
		$this->Labs[] = $lab;
	}

	public function criaLab($codlaboratorio,$Tipo,$nome,$capacidade,$sigla,$labensino,$area,$resposta,$cabo,$local,$so, $nestacoes, $situacao,$anoativacao, $anodesativacao){
		$lab = new Laboratorio();
		$lab->setCodlaboratorio($codlaboratorio);
		$lab->setUnidade($this);
		$lab->setTipo($Tipo);
		$lab->setNome($nome);
		$lab->setCapacidade($capacidade);
		$lab->setSigla($sigla);
		$lab->setLabensino($labensino);
		$lab->setArea($area);
		$lab->setResposta($resposta);
		$lab->setCabo($cabo);
		$lab->setLocal($local);
		$lab->setSo($so);
		$lab->setNestacoes($nestacoes);
		$lab->setSituacao($situacao);
		$lab->setAnoativacao($anoativacao);
		if ($situacao=="D")
		$lab->setAnodesativacao($anodesativacao);

		return $lab;
	}
        
	public function getPremios(){
		return $this->Premios;
	}

	public function setPremios(Premios $premios){
            $this->Premios = $premios;
	}

	public function getPremiosUfpa(){
		return $this->PremiosUfpa;
	}

	public function setPremiosUfpa($premiosufpa){
		$this->PremiosUfpa= $premiosufpa;
	}

	public function adicionaItemPremiosUfpa($codigo,$orgao,$nome,$qtde,$ano){
		$this->criaPremios($codigo,$orgao,$nome,$qtde,$ano);
		$this->PremiosUfpa[] = $this->Premios;;
	}

	public function criaPremios($codigo,$orgao,$nome,$qtde,$ano){
		$premiosufpa = new Premios();
		$premiosufpa->setCodigo($codigo);
		$premiosufpa->setUnidade($this);
		$premiosufpa->setQtde($qtde);
		$premiosufpa->setOrgao($orgao);
		$premiosufpa->setNome($nome);
		$premiosufpa->setAno($ano);
		$this->Premios=$premiosufpa;
	}

	public function getAtividadeextensao(){
		return $this->Atividadeextensao;
	}

	public function setAtividadeextensao(Atividadeextensao $atividade){
		$this->Ativisadeextensao = $atividade;
	}

	public function getAtividadeextensaoufpa(){
		return $this->Atividadeextensaoufpas;
	}

	public function setAtividadeextensaoufpa(Atividadeextensao $atividade){
		$this->Atividadeextensaoufpa[] = $Atividade;
	}

	public function criaAtividadeextensao($codigo,$codsubunidade,$tipo,$quantidade,$participantes,$atendidas,$ano){
		$atividadeextensaoufpa = new Atividadeextensao();
		$atividadeextensaoufpa->setCodigo($codigo);
		$atividadeextensaoufpa->setSubunidade($codsubunidade);
		$atividadeextensaoufpa->setTipo($tipo);
		$atividadeextensaoufpa->setUnidade($this);
		$atividadeextensaoufpa->setQuantidade($quantidade);
		$atividadeextensaoufpa->setParticipantes($participantes);
		$atividadeextensaoufpa->setAtendidas($atendidas);
		$atividadeextensaoufpa->setAno($ano);
		$this->Atividadeextensao=$atividadeextensaoufpa;
	}

	public function getMicros(){
		return $this->Micros;
	}

	public function setMicros(Micros $micros){
		$this->Micros = $micros;
	}

	public function getMicrosUfpa(){
		return $this->MicrosUfpa;
	}

	public function setMicrosUfpa(Micros $microsufpa){
		$this->MicrosUfpa[] = $microsufpa;
	}

	public function criaMicros($codigo,$acad,$acadi,$adm,$admi, $ano){
		$microsufpa = new Micros();
		$microsufpa->setCodigo($codigo);
		$microsufpa->setUnidade($this);
		$microsufpa->setAcad($acad);
		$microsufpa->setAcadi($acadi);
		$microsufpa->setAdm($adm);
		$microsufpa->setAdmi($admi);
		$microsufpa->setAno($ano);
		$this->Micros=$microsufpa;
	}

	public function adicionaItemMicros($codigo,$acad,$acadi,$adm,$admi, $ano){
		$this->criaMicros($codigo,$acad,$acadi,$adm,$admi, $ano);
		$this->MicrosUfpa[] = $this->Micros;
	}
	public function getRhufpa(){
		return $this->Rhufpa;
	}

	public function setRhufpa(Rhetemufpa $rhufpa){
		$this->Rheufpa = $rhufpa;
	}

	public function getRhetemufpa(){
		return $this->Rhetemufpa;
	}

	public function setRhetemufpa(Rhetemufpa $rhetemufpa){
		$this->Rhetemufpa[] = $rhetemufpa;
	}

	public function adicionaItemRhetemufpa($codigo,$subunidade,$doutores,$mestres,$especialistas,$graduados,$ntecnicos,$temporarios,$tecnicos,$ano){
		$this->criaRhetemufpa($codigo,$subunidade,$doutores,$mestres,$especialistas,$graduados,$ntecnicos,$temporarios,$tecnicos,$ano);
		$this->Rhetemufpa[] = $this->Rhufpa;

	}
	public function criaRhetemufpa($codigo,$subunidade,$doutores,$mestres,$especialistas,$graduados,$ntecnicos,$temporarios,$tecnicos,$ano){
		$rhetemufpa = new Rhetemufpa();
		$rhetemufpa->setCodigo($codigo);
		$rhetemufpa->setUnidade($this);
		$rhetemufpa->setSubunidade($subunidade);
		$rhetemufpa->setDoutores($doutores);
		$rhetemufpa->setMestres($mestres);
		$rhetemufpa->setNtecnicos($ntecnicos);
		$rhetemufpa->setEspecialistas($especialistas);
		$rhetemufpa->setGraduados($graduados);
		$rhetemufpa->setTemporarios($temporarios);
		$rhetemufpa->setTecnicos($tecnicos);
		$rhetemufpa->setAno($ano);
		$this->Rhufpa=$rhetemufpa;
	}
	public function getSubunidade(){
		return $this->Subunidade;
	}

	public function setSubunidade(Unidade $subunidade){
		$this->Subunidade[] = $subunidade;
	}

	public function getSubunidades(){
	 return $this->Subunidades;
	}

	public function setSubunidades(Unidade $subunidade){
		$this->Subunidades[] = $subunidade;
	}

	public function criaSubunidade($codunidade, $nomeunidade,$codestruturado){
		$sub = new Unidade();
		$sub->setCodunidade($codunidade);
		$sub->setNomeunidade($nomeunidade);
		$sub->setCodestruturado($codestruturado);
		$sub->setSubunidade($this);
		$this->Subunidade = $sub;
	}

	public function adicionaItemSubunidade($codunidade, $nomeunidade,$codestruturado){
		$this->criaSubunidade($codunidade, $nomeunidade,$codestruturado);
		$this->Subunidades[] = $this->Subunidade;
	}

	public function getServicos(){
		return $this->Servicos;
	}

	public function setServicos(Servico $servico){
		$this->Servicos[] = $servico;
	}

	public function criaServico($codigo,$unidade,$subunidade,$nome){
		$sub = new Servico();
		$sub->setCodigo($codigo);
		$sub->setUnidade($this);
		$sub->setSubunidade($subunidade);
		$sub->setNome($nome);
		$this->Servico = $sub;
	}

	public function adicionaItemServico($codigo,$unidade,$subunidade,$nome){
		$this->criaServico($codigo,$unidade,$subunidade,$nome);
		$this->Servicos[] = $this->Servico;
	}

	public function getProcedimentos(){
		return $this->Procedimentos;
	}

	public function setProcedimentos(Procedimento $procedimento){
		$this->Procedimentos[] = $procedimento;
	}

	public function criaProcedimento($codigo,$unidade,$subunidade,$nome){
		$sub = new Procedimento();
		$sub->setCodigo($codigo);
		$sub->setUnidade($this);
		$sub->setSubunidade($subunidade);
		$sub->setNome($nome);
		$this->Procedimento = $sub;
	}

	public function adicionaItemProcedimento($codigo,$unidade,$subunidade,$nome){
		$this->criaProcedimento($codigo,$unidade,$subunidade,$nome);
		$this->Procedimentos[] = $this->Procedimento;
	}
}
?>
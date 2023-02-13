<?php

class Unidade {

    private $codunidade;
    private $nomeunidade;
    private $codestruturado;
    private $tipounidade;
    
    private $sigla;
    private $siafi;
    private $unidaderesponsavel;   
    private $idunidaderesponsavel;
    
    
    private $Cursos = array();
    private $Labs = array(); // 
    private $PremiosUfpa = array();
    private $Premios;
    private $Atividadeextensaoufpa = array();
    private $Atividadeextensao;
    private $Subunidade;
    private $Subunidades = array();
    private $MicrosUfpa = array();
    private $Micros;
    private $Rhetemufpa = array();
    private $Rhufpa;
    private $Servico;
    private $Servicos;
    private $Documento;
    private $solicitacao;
    

    public function __construct() {
        
    }

    public function getCodunidade() {
        return $this->codunidade;
    }

    public function setCodunidade($codunidade) {
        $this->codunidade = $codunidade;
    }

    public function getNomeunidade() {
        return $this->nomeunidade;
    }

    public function setNomeunidade($nomeunidade) {
        $this->nomeunidade = $nomeunidade;
    }

    public function getTipounidade() {
        return $this->tipounidade;
    }

    public function setTipounidade($tipounidade) {
        $this->tipounidade = $tipounidade;
    }
    
    public function getSigla() {
        return $this->sigla;
    }
    
    public function setSigla($sigla) {
        $this->sigla = $sigla;
    }
    
    
    public function getSiafi() {
        return $this->siafi;
    }
    
    public function setSiafi($siafi) {
        $this->siafi= $siafi;
    }
    
    public function getUnidaderesponsavel() {
        return $this->unidaderesponsavel;
    }
    
    public function setUnidaderesponsavel($unidaderesponsavel) {
        $this->unidaderesponsavel= $unidaderesponsavel;
    }
    
    public function getIdunidaderesponsavel() {
        return $this->idunidaderesponsavel;
    }
    
    public function setIdunidaderesponsavel($idunidaderesponsavel) {
        $this->idunidaderesponsavel= $idunidaderesponsavel;
    }

    public function getCodestruturado() {
        return $this->codestruturado;
    }

    public function setCodestruturado($codestruturado) {
        $this->codestruturado = $codestruturado;
    }

    
     public function getSolicitacao() {
        return $this->solicitacao;
    }

    public function setSolicitacao($solicitacao) {
        $this->Solicitacao = $solicitacao;
    }
    
    public function getCursos() {
        return $this->Cursos;
    }

    public function setCursos(Curso $curso) {
        $this->Cursos[] = $curso;
    }

    public function criaCurso($campus, $CodCursoSis, $CodCurso, $NomeCurso, $DataInicio, $CodEmec) {
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

    public function adicionaItemCursos($campus, $CodCursoSis, $CodCurso, $NomeCurso, $DataInicio, $CodEmec) {
        $curso = $this->criaCurso($campus, $CodCursoSis, $CodCurso, $NomeCurso, $DataInicio, $CodEmec);
        $this->Cursos[] = $curso;
    }

    public function adicionaItemCursosLibras($campus, $CodCursoSis, $CodCurso, $NomeCurso, $DataInicio, $CodEmec, $codigo, $Ano) {
        $curso = $this->criaCurso($campus, $CodCursoSis, $CodCurso, $NomeCurso, $DataInicio, $CodEmec);
        $curso->criaLibra($codigo, $Ano);
        $this->Cursos[] = $curso;
    }

    public function getLabs() {
        return $this->Labs;
    }

    public function setLabs(Laboratorio $labs) {
        $this->Labs[] = $labs;
    }

    public function adicionaItemLabs($codlaboratorio, $Tipo, $nome, $capacidade, $sigla, $labensino, $area, $resposta, $cabo, $local, $so, $nestacoes, $situacao, $anoativacao, $anodesativacao) {
        $lab = $this->criaLab($codlaboratorio, $Tipo, $nome, $capacidade, $sigla, $labensino, $area, $resposta, $cabo, $local, $so, $nestacoes, $situacao, $anoativacao, $anodesativacao);
        $this->Labs[] = $lab;
    }

    public function criaLab($codlaboratorio, $Tipo, $nome, $capacidade, $sigla, $labensino, $area, $resposta, $cabo, $local, $so, $nestacoes, $situacao, $anoativacao, $anodesativacao) 
    {
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
        
        if ($situacao == "D")
        $lab->setAnodesativacao($anodesativacao);        
        

        return $lab;
    }
    
    public function criaLabv2($codlaboratorio,  $nome, $capacidade, $sigla,  $area,  $cabo, $local, $so, $nestacoes, $situacao, $anoativacao, $anodesativacao)
    {
        $lab = new Laboratorio();
        $lab->setCodlaboratorio($codlaboratorio);
        $lab->setUnidade($this);
        $lab->setNome($nome);
        $lab->setCapacidade($capacidade);
        $lab->setSigla($sigla);
        $lab->setArea($area);
        $lab->setCabo($cabo);
        $lab->setLocal($local);
        $lab->setSo($so);
        $lab->setNestacoes($nestacoes);
        $lab->setSituacao($situacao);
        $lab->setAnoativacao($anoativacao);
        
        if ($situacao == "D")
            $lab->setAnodesativacao($anodesativacao);
            
            
            return $lab;
    }
    public function criaLabv21($codlaboratorio,  $nome)
    {
        $lab = new Laboratorio();
        $lab->setCodlaboratorio($codlaboratorio);
        $lab->setUnidade($this);
        $lab->setNome($nome);
        
        return $lab;
    }
    
    
    public function getPremios() {
        return $this->Premios;
    }

    public function setPremios(Premios $premios) {
        $this->Premios = $premios;
    }

    public function getPremiosUfpa() {
        return $this->PremiosUfpa;
    }

    public function setPremiosUfpa(Premios $premiosufpa) {
        $this->PremiosUfpa[] = $premiosufpa;
    }

    public function adicionaItemPremiosUfpa($codigo, $orgao, $nome, $qtde,$qtdei,$qtdeo,$qtdet, $ano,$tp,$cat) {
        $this->criaPremios($codigo, $orgao, $nome, $qtde,$qtdei,$qtdeo,$qtdet, $ano,$tp,$ca);
        $this->PremiosUfpa[] = $this->Premios;
    }

    public function criaPremios($codigo, $orgao, $nome, $qtde,$qtdei,$qtdeo,$qtdet, $ano,$tp,$cat,$pais,$link) {
        $premiosufpa = new Premios();
        $premiosufpa->setCodigo($codigo);
        $premiosufpa->setUnidade($this);
         $premiosufpa->setOrgao($orgao);
        $premiosufpa->setNome($nome);
        $premiosufpa->setQtde($qtde);
        $premiosufpa->setQtdei($qtdei);
        $premiosufpa->setQtdeo($qtdeo);
        $premiosufpa->setQtdet($qtdet);        
        $premiosufpa->setAno($ano);
        $premiosufpa->setRec($tp);
        $premiosufpa->setCategoria($cat);
        $premiosufpa->setPais($pais);
        $premiosufpa->setLink($link);
        
        
        $this->Premios = $premiosufpa;
    }

    public function getAtividadeextensao() {
        return $this->Atividadeextensao;
    }

    public function setAtividadeextensao(Atividadeextensao $atividade) {
        $this->Ativisadeextensao = $atividade;
    }

    public function getAtividadeextensaoufpa() {
        return $this->Atividadeextensaoufpas;
    }

    public function setAtividadeextensaoufpa(Atividadeextensao $atividade) {
        $this->Atividadeextensaoufpa[] = $Atividade;
    }

    public function criaAtividadeextensao($codigo, $codsubunidade, $tipo, $quantidade, $participantes, $atendidas, $ano) {
        $atividadeextensaoufpa = new Atividadeextensao();
        $atividadeextensaoufpa->setCodigo($codigo);
        $atividadeextensaoufpa->setSubunidade($codsubunidade);
        $atividadeextensaoufpa->setTipo($tipo);
        $atividadeextensaoufpa->setUnidade($this);
        $atividadeextensaoufpa->setQuantidade($quantidade);
        $atividadeextensaoufpa->setParticipantes($participantes);
        $atividadeextensaoufpa->setAtendidas($atendidas);
        $atividadeextensaoufpa->setAno($ano);
        $this->Atividadeextensao = $atividadeextensaoufpa;
    }

    public function getMicros() {
        return $this->Micros;
    }

    public function setMicros(Micros $micros) {
        $this->Micros = $micros;
    }

    public function getMicrosUfpa() {
        return $this->MicrosUfpa;
    }

    public function setMicrosUfpa(Micros $microsufpa) {
        $this->MicrosUfpa[] = $microsufpa;
    }

    public function criaMicros($codigo, $acad, $acadi, $adm, $admi, $ano) {
        $microsufpa = new Micros();
        $microsufpa->setCodigo($codigo);
        $microsufpa->setUnidade($this);
        $microsufpa->setAcad($acad);
        $microsufpa->setAcadi($acadi);
        $microsufpa->setAdm($adm);
        $microsufpa->setAdmi($admi);
        $microsufpa->setAno($ano);
        $this->Micros = $microsufpa;
    }

    public function adicionaItemMicros($codigo, $acad, $acadi, $adm, $admi, $ano) {
        $this->criaMicros($codigo, $acad, $acadi, $adm, $admi, $ano);
        $this->MicrosUfpa[] = $this->Micros;
    }

    public function getRhufpa() {
        return $this->Rhufpa;
    }

    public function setRhufpa(Rhetemufpa $rhufpa) {
        $this->Rheufpa = $rhufpa;
    }

    public function getRhetemufpa() {
        return $this->Rhetemufpa;
    }

    public function setRhetemufpa(Rhetemufpa $rhetemufpa) {
        $this->Rhetemufpa[] = $rhetemufpa;
    }

    public function adicionaItemRhetemufpa($codigo, $subunidade, $doutores, $mestres, $especialistas, $graduados, $ntecnicos, $temporarios, $tecnicos, $ano) {
        $this->criaRhetemufpa($codigo, $subunidade, $doutores, $mestres, $especialistas, $graduados, $ntecnicos, $temporarios, $tecnicos, $ano);
        $this->Rhetemufpa[] = $this->Rhufpa;
    }

    public function criaRhetemufpa($codigo, $subunidade, $doutores, $mestres, $especialistas, $graduados, $ntecnicos, $temporarios, $tecnicos, $ano) {
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
        $this->Rhufpa = $rhetemufpa;
    }

    public function getSubunidade() {
        return $this->Subunidade;
    }

    public function setSubunidade(Unidade $subunidade) {
        $this->Subunidade = $subunidade;
    }

    public function getSubunidades() {
        return $this->Subunidades;
    }

    public function setSubunidades(Unidade $subunidade) {
        $this->Subunidades[] = $subunidade;
    }

    public function criaSubunidade($codunidade, $nomeunidade, $codestruturado) {
        $sub = new Unidade();
        $sub->setCodunidade($codunidade);
        $sub->setNomeunidade($nomeunidade);
        $sub->setCodestruturado($codestruturado);
        $sub->setSubunidade($this);
        $this->Subunidade = $sub;
    }

    public function adicionaItemSubunidade($codunidade, $nomeunidade, $codestruturado) {
        $this->criaSubunidade($codunidade, $nomeunidade, $codestruturado);
        $this->Subunidades[] = $this->Subunidade;
    }

    public function getServicos() {
        return $this->Servicos;
    }

    public function setServicos(Servico $servico) {
        $this->Servicos[] = $servico;
    }

    public function getServico() {
        return $this->Servico;
    }

    public function setServico(Servico $servico) {
        $this->Servico = $servico;
    }

    public function criaServico($codigo, $nome) {
        $s = new Servico();
        $s->setCodigo($codigo);
        $s->setSubunidade($this);
        $s->setNome($nome);
        $this->Servico = $s;
    }

    public function adicionaItemServico($codigo, $nome) {
        $this->criaServico($codigo, $nome);
        $this->Servicos[] = $this->Servico;
    }

    /* 	public function getProcedimentos(){
      return $this->Procedimentos;
      }

      public function setProcedimentos(Procedimento $procedimento){
      $this->Procedimentos[] = $procedimento;
      }premiosufpa

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
      } */


   public function getDocumento() {
        return $this->Documento;
    }

    public function setDocumento( $documento) {
        $this->Documento = $documento;
    }

 public function criaDocumento($codigo,$nome,$anoinicial,$anofinal,$situacao,$missao,$visao,$anexo,$tipo) {
        $doc= new Documento();
     
        $doc->setCodigo($codigo);
        $doc->setNome($nome);
        $doc->setAnoinicial($anoinicial);
        $doc->setAnofinal($anofinal) ;
        $doc->setSituacao($situacao);
        $doc->setMissao($missao);
        $doc->setVisao($visao);
        $doc->setUnidade($this);
        $doc->setAnexo($anexo);
        $doc->setTipo($tipo);
       

        $this->Documento = $doc;
    }
    
  public function criaTopico( $codigo,$nome,$tipo,$situacao, $anoinicial,$anofinal,$unidade,$ordem) {
        $t = new Topico();
        $t->setCodigo($codigo);
        $t->setSubtopico(NULL);
        $t->setUnidade($this);
        $t->setNome($nome);
        $t->setTipo($tipo);
        $t->setSituacao($situacao);
        $t->setAnoinicial($anoinicial);
        $t->setAnoFinal($anofinal);
        $t->setOrdem($ordem);

        $this->topico = $t;
    }
    
 public function  criaSolicitacao( $codigo,  $justificativa,$anexo,
     $situacao, $datasolicitacao,$dataemanalise,$usuarioanalista,$anogestao,$tipo,$mapaind,
     $indicador,$objetivo,$documento,$meta,$novameta,$mapa){
     
       switch ($tipo){
    	
       	case 1:
				 		$sol=new SolicitacaoEditIndicador();
				 		$sol->setMapaIndicador($mapaind);
				        $sol->setIndicador($indicador);
				        						
				        
			            break;
       	case 2:
    				    $sol=new SolicitacaoVersaoIndicador();
    					$sol->setMapaIndicador($mapaind);
				        $sol->setIndicador($indicador);
				        
				        break;
       	case 3:
	    				$sol=new SolicitacaoInsercaoObjetivo();    				
				        $sol->setObjetivo($objetivo);
				        $sol->setDocumento($documento);
				        break;
     	case 4:
	    				$sol=new SolicitacaoRepactuacao();
				        $sol->setMeta($meta);
				        $sol->setNovameta($novameta);
				        break;
       	case 5:
    					$sol=new SolicitacaoEditObjetivo();
			        	$sol->setMapa($mapa);
			       		$sol->setObjetivo($objetivo);
				    	break;
				    	
       	case 6:			
			       		$sol=new SolicitacaoVersaoIndicador();
			       		$sol->setMapaIndicador($mapaind);
			       		$sol->setIndicador($indicador);
			       		break;
		
       	case 7: 		
		       			$sol=new SolicitacaoVersaoIndicador();
		       			$sol->setMapaIndicador($mapaind);
		       			$sol->setIndicador($indicador);
		       			break;
		} 
    					$sol->setCodigo($codigo);      
				        $sol->setUnidade($this);
				    	$sol->setJustificativa($justificativa);
				    				       	
				    	
				    	$sol->setAnexo($anexo);
				    	$sol->setSituacao($situacao);
				    	$sol->setDatasolicitacao($datasolicitacao);
				    	$sol->setDataemanalise($dataemanalise);
				    	
				    	$sol->setUsuarioanalista($usuarioanalista);
			    	
				    	$sol->setAnogestao($anogestao);
				    	$sol->setTipo($tipo); 
        return $sol;
     
     }

    public function  criaSolicitacaoEditada( $codigo,  $justificativa,$anexo,
     $situacao, $datasolicitacao,$dataemanalise,$usuarioanalista,$anogestao,$tipo,$mapaind,
     $indicador,$objetivo,$documento,$meta,$novameta,$mapa,$codsoledicao){
        
        switch ($tipo){
    	
       	case 1:
				 		$sol=new SolicitacaoEditIndicador();
				 		$sol->setMapaIndicador($mapaind);
				        $sol->setIndicador($indicador);
				        						
				        
			            break;
       	case 2:
                       
    				    $sol=new SolicitacaoVersaoIndicador();
    					$sol->setMapaIndicador($mapaind);
				        $sol->setIndicador($indicador);             
				        
				        break;
       	case 3:
	    				$sol=new SolicitacaoInsercaoObjetivo();    				
				        $sol->setObjetivo($objetivo);
				        $sol->setDocumento($documento);
				        break;
     	case 4:
	    				$sol=new SolicitacaoRepactuacao();
				        $sol->setMeta($meta);
				        $sol->setNovameta($novameta);
				        break;
       	case 5:
    					$sol=new SolicitacaoEditObjetivo();
			        	$sol->setMapa($mapa);
			       		$sol->setObjetivo($objetivo);
				    	break;
				    	
       	case 6:			
			       		$sol=new SolicitacaoVersaoIndicador();
			       		$sol->setMapaIndicador($mapaind);
			       		$sol->setIndicador($indicador);
			       		break;
		
       	case 7: 		
		       			$sol=new SolicitacaoVersaoIndicador();
		       			$sol->setMapaIndicador($mapaind);
		       			$sol->setIndicador($indicador);
		       			break;
		}
    					
      
       $sol->setCodigo($codigo);      
        $sol->setUnidade($this);
        $sol->setJustificativa($justificativa);
        $sol->setAnexo($anexo);
        $sol->setSituacao($situacao);
        $sol->setDatasolicitacao($datasolicitacao);
        $sol->setDataemanalise($dataemanalise);
        $sol->setUsuarioanalista($usuarioanalista);
        $sol->setAnogestao($anogestao);
        $sol->setTipo($tipo); 
        $sol->setCodsoledicao($codsoledicao);  
       
       return $sol;
     
     }
}


?>
<?php
class Laboratorio {
	private $codlaboratorio;
	private $Unidade;
	private $Tipo;
	private $nome;
	private $capacidade;
	private $sigla;
	private $labensino;//roteiro
	private $area;//roteiro
	private $resposta;//roteiro
	private $cabo;
	private $local;
	private $so;
	private $nestacoes;
	private $situacao;
	private $anoativacao;
	private $anodesativacao;
	private $Labcursos;

	private $vetorsiglas;
	private $vetorconteudo;
	public function _construct($codlaboratorio,$Unidade,$Tipo,$nome,$capacidade,$sigla,$labensino,$area,$resposta,$cabo,$local,$so, $nestacoes, $anoativacao, $anodesativacao, $situacao) 
	{
		$this->codlaboratorio = $codlaboratorio;
		$this->Unidade = $unidade;
		$this->Tipo =  $tipo;
		$this->nome = $nome;
		$this->capacidade = $capacidade;
		$this->sigla = $sigla;
		$this->labensino = $labensino;
		$this->area = $area;
		$this->cabo = $cabo;
		$this->local = $local;
		$this->so = $so;
		$this->resposta = $resposta;
		$this->nestacoes = $nestacoes;
		$this->anoativacao = $anoativacao;
		$this->anodesativacao = $anodesativacao;
		$this->situacao = $situacao;
		
	}

	public function getCodlaboratorio(){
		return $this->codlaboratorio;
	}

	public function setCodlaboratorio($codlaboratorio){
		$this->codlaboratorio = $codlaboratorio;
	}


	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}
	 
	public function getCapacidade(){
		return $this->capacidade;
	}

	public function setCapacidade($capacidade){
		$this->capacidade = $capacidade;
	}

	public function getCabo(){
		return $this->cabo;
	}

	public function setCabo($cabo){
		$this->cabo = $cabo;
	}
	 
	public function getSo(){
		return $this->so;
	}

	public function setSo($so){
		$this->so = $so;
	}
	 
	public function getNestacoes(){
		return $this->nestacoes;
	}

	public function setNestacoes($nestacoes){
		$this->nestacoes = $nestacoes;
	}
	 
	 
	public function getLocal(){
		return $this->local;
	}

	public function setLocal($local){
		$this->local = $local;
	}
	 
	 
	public function getSigla(){
		return $this->sigla;
	}

	public function setSigla($sigla){
		$this->sigla = $sigla;
	}

	public function getLabensino(){
		return $this->labensino;
	}

	public function setLabensino($labensino){
		$this->labensino = $labensino;
	}
	 
	public function getArea(){
		return $this->area;
	}

	public function setArea($area){
		$this->area = $area;
	}

	public function getResposta(){
		return $this->resposta;
	}

	public function setResposta($resposta){
		$this->resposta = $resposta;
	}

	public function getUnidade(){
		return $this->Unidade;
	}

	public function setUnidade(Unidade $unidade){
		$this->Unidade = $unidade;
	}


	public function getTipo(){
		return $this->Tipo;
	}

	public function setTipo(Tplaboratorio $tipo){
		$this->Tipo = $tipo;
	}

	public function getAnoativacao(){
		return $this->anoativacao;
	}

	public function setAnoativacao($anoativacao){
		$this->anoativacao = $anoativacao;
	}
	public function getAnodesativacao(){
		return $this->anodesativacao;
	}

	public function setAnodesativacao($anodesativacao){
		$this->anodesativacao = $anodesativacao;
	}
	 
	public function getSituacao(){
		return $this->situacao;
	}

	public function setSituacao($situacao){
		$this->situacao = $situacao;
	}

	
	public function getVetorsiglas(){
	    return $this->vetorsiglas;
	}
	
	public function setVetorsiglas($vetorsiglas){
	    $this->vetorsiglas = $vetorsiglas;
	}
	
	public function getLabcursos(){
		return $this->Labcursos;
	}

	public function setLabcursos(Labcurso $labcursos){
		$this->Labcursos[] = $labcursos;
	}


	public function adicionaItemLabcursos($codigo,$Curso){
		$labcur= $this->criaLabcurso($codigo,$Curso);
		$this->Labcursos[] = $labcur;
	}

	public function adicionaItemLabcursoscenso($codigo,$Curso){
	    $labcur= $this->criaLabcurso($codigo,$Curso);
	    $inclui=True;
	    if ($this->Labcursos==NULL){
	        $tam=0;
	        $tam++;
	        $this->Labcursos[$tam] = $labcur;
	    }else {
	        $tam=count($this->Labcursos);
	        for ($i=1;$i<=$tam;$i++){
	            if ( $this->Labcursos[$i]->getCodlabcurso()==$codigo) {//pra pegar o codigo emec e nao o codlabcurso
	                $inclui=False;
	            }
	        }
	        if ($inclui){
	            $tam++;
	            $this->Labcursos[$tam] = $labcur;
	        }
	    }
	    return  $inclui;
	}
	public function criaLabcurso($codigo,$curso){
		$labcur = new Labcurso();
		$labcur->setCodlabcurso($codigo);
		$labcur->setLaboratorio($this);
		$labcur->setCurso($curso);

		return $labcur;
	}	

	
	//censo
	
	
	public function separaUnidade($labs){
	    $this->vetorsiglas=array();
	    $novoindice=0;
	    
	    for ($ind=1;$ind<=count($labs);$ind++){
	        
	        if ($novoindice==0 ){
	            $novoindice++;
	            $this->vetorsiglas[$novoindice]=$labs[$ind]->getSigla();
	            
	        }else {
	            
	            $indicesigla=$this->posicaovetor($labs[$ind]->getSigla(), $this->vetorsiglas);
	            if  ($indicesigla==0) {
	                $novoindice=count($this->vetorsiglas)+1;
	                $this->vetorsiglas[$novoindice]=$labs[$ind]->getSigla();
	            }
	    }
	   
	}
	}
	
	public function inicializaVetor($vetor,$tamanho){
	    for ($j=1;$j<=$tamanho;$j++){
	        $vetor[$j]="";
	    }
	    return $vetor;
	}
	
	
	public function separaConteudo($labs){
	 //$labs = obj linhasinconsistentes
	    $this->vetorconteudo=array();
	    $this->vetorconteudo=$this->inicializaVetor($this->vetorconteudo,count($this->vetorsiglas));
	    for ($j=1;$j<=count($labs);$j++){
	        $indice= $this->posicaovetor($labs[$j]->getSigla(), $this->vetorsiglas);
	        $this->vetorconteudo[$indice].=$labs[$j]->getSigla().",".$labs[$j]->getNomelab().",".$labs[$j]->getCodlab().",\n";
          
	    }//for i
	}
	
	public function posicaovetor($valor, $vetor){
	    $jaexiste=0;
	    for ($i=1;$i<=count($vetor);$i++){
	    
	        if ($valor==$vetor[$i]){
	            $jaexiste=$i;
	        }
	    }
	    
	    return $jaexiste;
	    
	}
	
	public function posicaovetorstring($valor, $vetor){
	 //   echo $valor;
	    $jaexiste=0;
	    for ($i=1;$i<=count($vetor);$i++){
	//echo $vetor[$i]."----".$valor."<br>";
	        $vvalor=strstr($vetor[$i], ',', true);
	        if ($vvalor== $valor){
	           // echo "existe";
	            $jaexiste=$i;
	        }
	    }
	    return $jaexiste;
	    
	}
	
	public function imprimeConteudoPorUnidade($anobase){
	    
	    for ($i=1;$i<=count($this->vetorsiglas);$i++){
	    
	        $nomearquivo="../public/raa_anexos/labinc".$this->vetorsiglas[$i].$anobase.".txt";
	        $arquivo = fopen($nomearquivo,'w');
	        fwrite($arquivo, $this->vetorconteudo[$i]);
	        fclose($arquivo);
	}
	}
	
	public function imprimeConteudo($anobase){
	    $nomearquivo="../public/raa_anexos/labincCompleto".$anobase.".txt";
	    $tudojunto="";
	    for ($i=1;$i<=count($this->vetorconteudo);$i++){
	        $tudojunto.=$this->vetorconteudo[$i];
	    }
	    $arquivo = fopen($nomearquivo,'w');
	    fwrite($arquivo, $tudojunto);
	    fclose($arquivo);
	}
}
?>

 
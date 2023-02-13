<?php
class Labclasse {
    private $codigo;
    private $codtipo;
    private $codlaboratorio;
    private $ano;
    private $sugestaoDeTipo;
    private $inforAdicional;
    
    public function getCodigo()
    {
        return $this->codigo;
    }

   
    public function getCodtipo()
    {
        return $this->codtipo;
    }

   
    public function getCodlaboratorio()
    {
        return $this->codlaboratorio;
    }

    
    public function getAno()
    {
        return $this->ano;
    }

   
    public function getSugestaoDeTipo()
    {
        return $this->sugestaoDeTipo;
    }

    
    public function getInforAdicional()
    {
        return $this->inforAdicional;
    }

   
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    
    public function setCodtipo($codtipo)
    {
        $this->codtipo = $codtipo;
    }

  
    public function setCodlaboratorio($codlaboratorio)
    {
        $this->codlaboratorio = $codlaboratorio;
    }

 
    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    
    public function setSugestaoDeTipo($sugestaoDeTipo)
    {
        $this->sugestaoDeTipo = $sugestaoDeTipo;
    }

    
    public function setInforAdicional($inforAdicional)
    {
        $this->inforAdicional = $inforAdicional;
    }

    
    public function adicionaItemLabs($codlaboratorio,$Unidade,$nome,$capacidade,$sigla,$labensino,$area,$resposta,$cabo,$local,$so, $nestacoes, $situacao,$anoativacao, $anodesativacao){
        $lab= $this->criaLab($codlaboratorio,$Unidade,$this,$nome,$capacidade,$sigla,$labensino,$area,$resposta,$cabo,$local,$so, $nestacoes, $situacao,$anoativacao, $anodesativacao);
        $this->Labs[] = $lab;
    }
    
    public function criaLab($codlaboratorio,$unidade,$nome,$capacidade,$sigla,$labensino,$area,$resposta,$cabo,$local,$so, $nestacoes, $situacao,$anoativacao, $anodesativacao){
        $lab = new Laboratorio();
        $lab->setCodlaboratorio($codlaboratorio);
        $lab->setUnidade($unidade);
        $lab->setTipo($this);
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


	 
}
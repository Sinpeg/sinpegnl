<?php

 class Tplaboratorio {
 	private $codigo;
 	private $codcategoria;
 	private $codcenso;
 	private $nome;
 	private $validade;
 	
 	
    

   
    public function getCodigo()
    {
        return $this->codigo;
    }

   
    public function getCodcategoria()
    {
        return $this->codcategoria;
    }

    
    public function getCodcenso()
    {
        return $this->codcenso;
    }

   
    public function getNome()
    {
        return $this->nome;
    }

    
    public function getValidade()
    {
        return $this->validade;
    }

    
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

   
    public function setCodcategoria($codcategoria)
    {
        $this->codcategoria = $codcategoria;
    }

   
    public function setCodcenso($codcenso)
    {
        $this->codcenso = $codcenso;
    }

    
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

   
    public function setValidade($validade)
    {
        $this->validade = $validade;
    }

   
    public function criaLabv3($codlaboratorio)
    {
        $lab = new Laboratorio();
        $lab->setCodlaboratorio($codlaboratorio);
      $lab->setTipo($this);
        
        
            
            return $lab;
    }
  
 }
 ?>
<?php

Class Pessoa {
    private $idPessoa;
    private $idpessoainep;
    private $idalunoies;
    private $nome;
    private $cpf;
    private $passaporte; 
    private $dtnascimento; 
    private $sexo; 
    private $cor; 
    private $nomemae; 
    private $nacionalidade; 
    private $ufnascimento; 
    private $munnascimento; 
    private $paisorigem; 
    private $baixa; 
    private $pnd; 
    private $cegueira; 
    private $baixavisao; 
    private $surdez; 
    private $auditiva; 
    private $fisica; 
    private $surdocegueira; 
    private $multipla; 
    private $mental; 
    private $autismoinfantil;
    private $sindromeasperger;
    private $sindromerett; 
    private $tdinfancia; 
    private $altashabilidades; 
    private $tipoescola;
    /**
     * @return mixed
     */
    public function getIdPessoa()
    {
        return $this->idPessoa;
    }

    /**
     * @return mixed
     */
    public function getIdpessoainep()
    {
        return $this->idpessoainep;
    }

    /**
     * @return mixed
     */
    public function getIdalunoies()
    {
        return $this->idalunoies;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @return mixed
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * @return mixed
     */
    public function getPassaporte()
    {
        return $this->passaporte;
    }

    /**
     * @return mixed
     */
    public function getDtnascimento()
    {
        return $this->dtnascimento;
    }

    /**
     * @return mixed
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * @return mixed
     */
    public function getCor()
    {
        return $this->cor;
    }

    /**
     * @return mixed
     */
    public function getNomemae()
    {
        return $this->nomemae;
    }

    /**
     * @return mixed
     */
    public function getNacionalidade()
    {
        return $this->nacionalidade;
    }

    /**
     * @return mixed
     */
    public function getUfnascimento()
    {
        return $this->ufnascimento;
    }

    /**
     * @return mixed
     */
    public function getMunnascimento()
    {
        return $this->munnascimento;
    }

    /**
     * @return mixed
     */
    public function getPaisorigem()
    {
        return $this->paisorigem;
    }

    /**
     * @return mixed
     */
    public function getBaixa()
    {
        return $this->baixa;
    }

    /**
     * @return mixed
     */
    public function getPnd()
    {
        return $this->pnd;
    }

    /**
     * @return mixed
     */
    public function getCegueira()
    {
        return $this->cegueira;
    }

    /**
     * @return mixed
     */
    public function getBaixavisao()
    {
        return $this->baixavisao;
    }

    /**
     * @return mixed
     */
    public function getSurdez()
    {
        return $this->surdez;
    }

    /**
     * @return mixed
     */
    public function getAuditiva()
    {
        return $this->auditiva;
    }

    /**
     * @return mixed
     */
    public function getFisica()
    {
        return $this->fisica;
    }

    /**
     * @return mixed
     */
    public function getSurdocegueira()
    {
        return $this->surdocegueira;
    }

    /**
     * @return mixed
     */
    public function getMultipla()
    {
        return $this->multipla;
    }

    /**
     * @return mixed
     */
    public function getMental()
    {
        return $this->mental;
    }

    /**
     * @return mixed
     */
    public function getAutismoinfantil()
    {
        return $this->autismoinfantil;
    }

    /**
     * @return mixed
     */
    public function getSindromeasperger()
    {
        return $this->sindromeasperger;
    }

    /**
     * @return mixed
     */
    public function getSindromerett()
    {
        return $this->sindromerett;
    }

    /**
     * @return mixed
     */
    public function getTdinfancia()
    {
        return $this->tdinfancia;
    }

    /**
     * @return mixed
     */
    public function getAltashabilidades()
    {
        return $this->altashabilidades;
    }

    /**
     * @return mixed
     */
    public function getTipoescola()
    {
        return $this->tipoescola;
    }

    /**
     * @param mixed $idPessoa
     */
    public function setIdPessoa($idPessoa)
    {
        $this->idPessoa = $idPessoa;
    }

    /**
     * @param mixed $idpessoainep
     */
    public function setIdpessoainep($idpessoainep)
    {
        $this->idpessoainep = $idpessoainep;
    }

    /**
     * @param mixed $idalunoies
     */
    public function setIdalunoies($idalunoies)
    {
        $this->idalunoies = $idalunoies;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @param mixed $cpf
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    /**
     * @param mixed $passaporte
     */
    public function setPassaporte($passaporte)
    {
        $this->passaporte = $passaporte;
    }

    /**
     * @param mixed $dtnascimento
     */
    public function setDtnascimento($dtnascimento)
    {
        $this->dtnascimento = $dtnascimento;
    }

    /**
     * @param mixed $sexo
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;
    }

    /**
     * @param mixed $cor
     */
    public function setCor($cor)
    {
        $this->cor = $cor;
    }

    /**
     * @param mixed $nomemae
     */
    public function setNomemae($nomemae)
    {
        $this->nomemae = $nomemae;
    }

    /**
     * @param mixed $nacionalidade
     */
    public function setNacionalidade($nacionalidade)
    {
        $this->nacionalidade = $nacionalidade;
    }

    /**
     * @param mixed $ufnascimento
     */
    public function setUfnascimento($ufnascimento)
    {
        $this->ufnascimento = $ufnascimento;
    }

    /**
     * @param mixed $munnascimento
     */
    public function setMunnascimento($munnascimento)
    {
        $this->munnascimento = $munnascimento;
    }

    /**
     * @param mixed $paisorigem
     */
    public function setPaisorigem($paisorigem)
    {
        $this->paisorigem = $paisorigem;
    }

    /**
     * @param mixed $baixa
     */
    public function setBaixa($baixa)
    {
        $this->baixa = $baixa;
    }

    /**
     * @param mixed $pnd
     */
    public function setPnd($pnd)
    {
        $this->pnd = $pnd;
    }

    /**
     * @param mixed $cegueira
     */
    public function setCegueira($cegueira)
    {
        $this->cegueira = $cegueira;
    }

    /**
     * @param mixed $baixavisao
     */
    public function setBaixavisao($baixavisao)
    {
        $this->baixavisao = $baixavisao;
    }

    /**
     * @param mixed $surdez
     */
    public function setSurdez($surdez)
    {
        $this->surdez = $surdez;
    }

    /**
     * @param mixed $auditiva
     */
    public function setAuditiva($auditiva)
    {
        $this->auditiva = $auditiva;
    }

    /**
     * @param mixed $fisica
     */
    public function setFisica($fisica)
    {
        $this->fisica = $fisica;
    }

    /**
     * @param mixed $surdocegueira
     */
    public function setSurdocegueira($surdocegueira)
    {
        $this->surdocegueira = $surdocegueira;
    }

    /**
     * @param mixed $multipla
     */
    public function setMultipla($multipla)
    {
        $this->multipla = $multipla;
    }

    /**
     * @param mixed $mental
     */
    public function setMental($mental)
    {
        $this->mental = $mental;
    }

    /**
     * @param mixed $autismoinfantil
     */
    public function setAutismoinfantil($autismoinfantil)
    {
        $this->autismoinfantil = $autismoinfantil;
    }

    /**
     * @param mixed $sindromeasperger
     */
    public function setSindromeasperger($sindromeasperger)
    {
        $this->sindromeasperger = $sindromeasperger;
    }

    /**
     * @param mixed $sindromerett
     */
    public function setSindromerett($sindromerett)
    {
        $this->sindromerett = $sindromerett;
    }

    /**
     * @param mixed $tdinfancia
     */
    public function setTdinfancia($tdinfancia)
    {
        $this->tdinfancia = $tdinfancia;
    }

    /**
     * @param mixed $altashabilidades
     */
    public function setAltashabilidades($altashabilidades)
    {
        $this->altashabilidades = $altashabilidades;
    }

    /**
     * @param mixed $tipoescola
     */
    public function setTipoescola($tipoescola)
    {
        $this->tipoescola = $tipoescola;
    }

    
    
    
    
    
}
?>
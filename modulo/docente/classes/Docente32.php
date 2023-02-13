<?php

Class Docente32 {
    private $idRegistro32; 
    private $idDocente; 
    private $idCursoinep;
    private $CodTempo; 
    private $Situacao;
    /**
     * @return mixed
     */
    public function getIdRegistro32()
    {
        return $this->idRegistro32;
    }

    /**
     * @return mixed
     */
    public function getIdDocente()
    {
        return $this->idDocente;
    }

    /**
     * @return mixed
     */
    public function getIdCursoinep()
    {
        return $this->idCursoinep;
    }

    /**
     * @return mixed
     */
    public function getCodTempo()
    {
        return $this->CodTempo;
    }

    /**
     * @return mixed
     */
    public function getSituacao()
    {
        return $this->Situacao;
    }

    /**
     * @param mixed $idRegistro32
     */
    public function setIdRegistro32($idRegistro32)
    {
        $this->idRegistro32 = $idRegistro32;
    }

    /**
     * @param mixed $idDocente
     */
    public function setIdDocente($idDocente)
    {
        $this->idDocente = $idDocente;
    }

    /**
     * @param mixed $idCursoinep
     */
    public function setIdCursoinep($idCursoinep)
    {
        $this->idCursoinep = $idCursoinep;
    }

    /**
     * @param mixed $CodTempo
     */
    public function setCodTempo($CodTempo)
    {
        $this->CodTempo = $CodTempo;
    }

    /**
     * @param mixed $Situacao
     */
    public function setSituacao($Situacao)
    {
        $this->Situacao = $Situacao;
    }

    
    
    
    
    
}
<?php
class Labinconsistente {
    private $registro;
    private $nomelab;
    private $codlab;
    private $classelab; 
    private $sigla;
    private $vetorsiglas;
    /**
     * @return mixed
     */
    public function getRegistro()
    {
        return $this->registro;
    }

    /**
     * @return mixed
     */
    public function getNomelab()
    {
        return $this->nomelab;
    }

    /**
     * @return mixed
     */
    public function getCodlab()
    {
        return $this->codlab;
    }

    /**
     * @return mixed
     */
    public function getClasselab()
    {
        return $this->classelab;
    }

    /**
     * @return mixed
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * @param mixed $registro
     */
    public function setRegistro($registro)
    {
        $this->registro = $registro;
    }

    /**
     * @param mixed $nomelab
     */
    public function setNomelab($nomelab)
    {
        $this->nomelab = $nomelab;
    }

    /**
     * @param mixed $codlab
     */
    public function setCodlab($codlab)
    {
        $this->codlab = $codlab;
    }

    /**
     * @param mixed $classelab
     */
    public function setClasselab($classelab)
    {
        $this->classelab = $classelab;
    }

    /**
     * @param mixed $sigla
     */
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;
    }

    
 
    
}
?>
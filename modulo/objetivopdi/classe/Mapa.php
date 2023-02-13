<?php
class Mapa {
	
    private $codigo;
    private $codDocumento;
    private $codPerspectiva;
    private $codObjetivoPDI;
    private $codCalendario;
    private $ordem;
    private $propMapa;
    private $ordemPersp;
    private $dataCadastro;
    private $visao;

    public function __construct($codigo, $codDocumento, $codPerspectiva, $codObjetivoPDI, $codCalendario, $ordem, $propMapa, $ordemPerspec, $dataCdastro, $visao) {
    	
    	$this->codigo = $codigo;
    	$this->codDocumento = $codDocumento;
    	$this->codPerspectiva = $codPerspectiva;
    	$this->codObjetivoPDI = $codObjetivoPDI;
    	$this->codCalendario = $codCalendario;
    	$this->ordem = $ordem;
    	$this->propMapa = $propMapa;
    	$this->ordemPersp = $ordemPerspec;
    	$this->dataCadastro = $dataCdastro;
    	$this->visao = $visao;
        
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodDocumento($codDocumento) {
        $this->codDocumento = $codDocumento;
    }

    public function getCodDocumento() {
        return $this->codDocumento;
    }
    
    public function setCodPerspectiva($codPerspectiva) {
    	$this->codPerspectiva = $codPerspectiva;
    }
    
    public function getCodPerspectiva() {
    	return $this->codPerspectiva;
    }
    
    public function setCodObjetivoPDI($codobjetivoPDI) {
    	$this->codObjetivoPDI = $codobjetivoPDI;
    }
    
    public function getCodObjetivoPDI() {
    	return $this->codObjetivoPDI;
    }
    
    public function setCodCalendario($codCalendario){
    	$this->codCalendario = $codCalendario;
    }
    
    public function getCodCalendario(){
    	return $this->codCalendario;
    }
    
    public function setOrdem($ordem) {
        $this->ordem = $ordem;
    }

    public function getOrdem() {
        return $this->ordem;
    }

    public function setPropMapa($propMapa) {
        $this->propMapa = $propMapa;
    }

    public function getPropMapa() {
        return $this->propMapa;
    }

    public function setOrdemPerspectiva($ordemPerspe) {
        $this->ordemPersp = $ordemPerspe;
    }

    public function getOrdemPerspectiva() {
        return $this->ordemPersp;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }
    
    public function setVisao($visao){
    	$this->visao = $visao;
    }
    
    public function getVisao(){
    	return $this->visao;
    }
 
}

?>

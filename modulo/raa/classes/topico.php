<?php class Topico{
    private $codigo;
    private $nome;
    private $tipo;
    private $situacao;
    private $anoinicial;
    private $anofinal;
    private $unidade;
    private $texto;
    private $ordem;
    private $subtopicos=array();
    private $subtopico;
    private $nivel;
    private $modelo;
    private $modelos;
    
    /*
     public function _construct() {
     
     }*/
    
    
    public function getCodigo(){
        return $this->codigo;
    }
    
    public function setCodigo($codigo){
        $this->codigo = $codigo;
    }
    
    public function getNome(){
        return $this->nome;
    }
    
    public function setNome($nome){
        $this->nome = $nome;
    }
    
    public function getTexto(){
        return $this->texto;
    }
    
    public function setTexto($texto){
        $this->texto = $texto;
    }
    public function getTipo(){
        return $this->tipo;
    }
    
    public function setTipo($tipo){
        $this->tipo = $tipo;
    }
    
    public function getAnoinicial(){
        return $this->anoinicial;
    }
    
    public function setAnoinicial($anoinicial){
        $this->anoinicial = $anoinicial;
    }
    
    public function getAnofinal(){
        return $this->anofinal;
    }
    
    public function setAnofinal($anofinal){
        $this->anofinal = $anofinal;
    }
    
    public function getUnidade(){
        return $this->unidade;
    }
    
    public function setUnidade($unidade){
        $this->unidade = $unidade;
    }
    
    public function getSituacao(){
        return $this->situacao;
    }
    
    public function setSituacao($situacao){
        $this->situacao = $situacao;
    }
    
    public function getOrdem(){
        return $this->ordem;
    }
    
    public function setOrdem($ordem){
        $this->ordem = $ordem;
    }
    
    public function getNivel(){
        return $this->nivel;
    }
    
    public function setNivel($nivel){
        $this->nivel = $nivel;
    }
    public function getSubtopicos(){
        return $this->subtopicos;
    }
    
    public function setSubtopicos($subtopicos){
        $this->subtopicos = $subtopicos;
    }
    public function getSubtopico(){
        return $this->subtopico;
    }
    
    public function setSubtopico($subtopico){
        $this->subtopico = $subtopico;
    }
    
    public function getModelo(){
        return $this->modelo;
    }
    
    public function setModelo($modelo){
        $this->modelo = $modelo;
    }
    
    
    public function getModelos(){
        return $this->modelos;
    }
    
    public function setModelos($modelos){
        $this->modelos = $modelos;
    }
    public function adicionaItemSubtopico($codigo,$nome,$tipo,$situacao, $anoinicial,$anofinal,$ordem,$unidade,$nivel) {
        
        $t = new Topico();
        
        $t->setCodigo($codigo);
        $t->setSubtopico($this);
        $t->setUnidade($unidade);
        $t->setNome($nome);
        $t->setTipo($tipo);
        $t->setSituacao($situacao);
        $t->setAnoinicial($anoinicial);
        $t->setAnoFinal($anofinal);
        $t->setOrdem($ordem);
        $t->setNivel($nivel);
        
        $this->subtopicos[] = $t;
        
    }
    
    public function criaTopico( $codigo,$nome,$tipo,$situacao, $anoinicial,$anofinal,$ordem,$unidade,$nivel) {
        $t = new Topico();
        $t->setCodigo($codigo);
        $t->setSubtopico($this);
        $t->setUnidade($unidade);
        $t->setNome($nome);
        $t->setTipo($tipo);
        $t->setSituacao($situacao);
        $t->setAnoinicial($anoinicial);
        $t->setAnoFinal($anofinal);
        $t->setOrdem($ordem);
        $t->setNivel($nivel);
        
        $this->subtopico = $t;
    }
    
    public function criaTexto($codigo,$desctexto,$unidade,$ano) {
        $t = new Texto();
        $t->setCodigo($codigo);
        $t->setTopico($this);
        $t->setUnidade($unidade);
        $t->setDesctexto($desctexto);
        $t->setAno($ano);
        $this->texto = $t;
        
        
    }
    public function criaModelo($codigo, $legenda,$modelo,$anoinicial,$anofinal,$unidade,$situacao,$ordemintopico) {
        $m= new Modelo();
        $m->setCodigo($codigo);
        $m->setlegenda($legenda);
        $m->setmodelo(trim($modelo));
        $m->setSituacao($situacao);
        $m->setanoinicial($anoinicial);
        $m->setanofinal($anofinal);
        $m->setunidade($unidade);
        $m->settopico($this);
        $m->setOrdemintopico($ordemintopico);
        
        $this->modelo=$m;
    }
    
    public function adicionaItemModelo($codigo, $legenda,$modelo,$anoinicial,$anofinal,$unidade,$situacao,$ordemintopico) {
        
        $m = new Modelo();
        
        $m->setCodigo($codigo);
        $m->setlegenda($legenda);
        $m->setmodelo(trim($modelo));
        $m->setSituacao($situacao);
        $m->setanoinicial($anoinicial);
        $m->setanofinal($anofinal);
        $m->setunidade($unidade);
        $m->settopico($this);
        $m->setOrdemintopico($ordemintopico);
        
        $this->modelos[] = $m;
        
        
        
        
    }
}?>
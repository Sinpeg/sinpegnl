<?php
class Prodsaude {
	private $codigo;
	private $subunidade;
	private $secao;
	private $procedimento;
	private $formulario;
	private $Qprodsaude;
	private $Qprodsaudevetor;

	public function _construct($codigo,$codunidade,$subunidade,$secao,$procedimento,$formulario) {
		$this->codigo = $codigo;
		$this->codunidade = $codunidade;
		$this->subunidade = $subunidade;
		$this->secao = $secao;
		$this->procedimento = $procedimento;
		$this->formulario = $formulario;

	}

	public function getCodigo(){
		return $this->codigo;
	}

	public function setCodigo($codigo){
		$this->codigo = $codigo;
	}

	public function getSubunidade(){
		return $this->subunidade;
	}

	public function setSubunidade($subunidade){
		$this->subunidade = $subunidade;
	}

	public function getSecao(){
		return $this->secao;
	}

	public function setSecao( $secao){
		$this->secao = $secao;
	}

	public function getProcedimento(){
		return $this->procedimento;
	}

	public function setProcedimento( $procedimento){
		$this->procedimento = $procedimento;
	}

	public function getFormulario(){
		return $this->formulario;
	}

	public function setFormulario( $formulario){
		$this->formulario = $formulario;
	}

	public function getQprodsaude(){
		return $this->Qprodsaude;
	}

	public function set(Qprodsaude $qprodsaude){
		$this->Qprodsaude = $qprodsaude;
	}

	public function criaQprodsaude($codigo, $unidade,$quantidade,$ano){
		$qp = new Qprodsaude();
		$qp->setCodigo($codigo);
	 $qp->setUnidade($unidade);
	 $qp->setTipo($this);
	 $qp->setQuantidade($quantidade);
	 $qp->setAno($ano);
	 $this->Qprodsaude = $qp;
	}

	public function adicionaItemQprodsaude($codigo, $unidade,$quantidade,$ano){
		$qp = $this->criaQprodsaude($codigo, $unidade,$quantidade,$ano);
		$this->Qprodsaudevetor[] = $qp;
	}
}
?>
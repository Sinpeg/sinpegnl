<?php
class QprodsaudeDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	private $conex = null;


	// constructor
/*	public function QprodsaudeDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}
*/

	public function buscaQpsAno($ano){
		try{
			$stmt = parent::prepare("SELECT `Tipo`,`Quantidade` FROM   `qprodsaude` where `Ano` =  :ano order by  `Tipo` ");
			//print_r($stmt);
			$stmt->execute(array(':ano'=>$ano));
			// retorna o resultado da query
			return $stmt;

		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	public function buscaQPorAnoSecao($ano, $secao){
		$stmt = parent::prepare("SELECT * FROM qprodsaude qp JOIN prodsaude p ON(p.Codigo = qp.Tipo) WHERE qp.Ano = :ano AND p.Secao = :secao ");
		$stmt->execute(array(':ano'=>$ano, ':secao' => $secao));
		return $stmt;
	}


	public function buscaQps($ano, $tps){
		try{
			$stmt = parent::prepare("SELECT * FROM `qprodsaude` where `Ano` =:ano and `Tipo` = :tipo ");
			//print_r($stmt);
			$stmt->execute(array(':ano'=>$ano,':tipo'=>$tps));
			// retorna o resultado da query
			return $stmt;

		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}


	public function inseretodos( $qps ){
		try{
			parent::beginTransaction();
			$sql="INSERT INTO `qprodsaude` (`CodUnidade`,`Tipo`,`Quantidade`,`Ano`)  VALUES (?,?,?,?)";
			for ($cont=1; $cont<=count($qps);$cont++){
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$qps[$cont]->getQprodsaude()->getUnidade()->getCodunidade());
				$stmt->bindValue(2,$qps[$cont]->getQprodsaude()->getTipo()->getCodigo());
				$stmt->bindValue(3,$qps[$cont]->getQprodsaude()->getQuantidade());
				$stmt->bindValue(4,$qps[$cont]->getQprodsaude()->getAno());
				$stmt->execute();
			}
			parent::commit();
		}catch ( PDOException $ex ){
			$db->rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function alteratodos( $qps ){
		try{
			parent::beginTransaction();
			$sql="UPDATE `qprodsaude` SET `Quantidade`=? WHERE `Codigo`=? ";
			for ($cont=1; $cont <=count($qps); $cont++){
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$qps[$cont]->getQprodsaude()->getQuantidade());
				$stmt->bindValue(2,$qps[$cont]->getQprodsaude()->getCodigo());
				$stmt->execute();
			}
			parent::commit();
		}catch ( PDOException $ex ){
			$db->rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}


	public function fechar(){
		PDOConnectionFactory::Close();
	}



}
?>
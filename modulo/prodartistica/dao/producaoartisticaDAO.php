<?php
class ProducaoartisticaDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	private $conex = null;

	// constructor
	/* public function ProducaoartisticaDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}
    */
	public function deleta($codunidade, $ano){
		try{
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `prodartistica` where `CodUnidade` = :codunidade and `Ano` = :ano ");
			$stmt->execute(array(':codunidade'=>$codunidade,':ano'=>$ano));
			parent::commit();
		}catch ( PDOException $ex ){
			//echo "Erro: ".$ex->getMessage();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function buscapaunidade($codunidade, $ano){
		try{
			$stmt = parent::prepare("SELECT * FROM   `prodartistica` where `CodUnidade` = :codunidade and `Ano` = :ano ");
			$stmt->execute(array(':codunidade'=>$codunidade,':ano'=>$ano));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			//echo "Erro: ".$ex->getMessage();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function buscapa($codunidade, $ano, $tipopa){
		try{
			$stmt = parent::prepare("SELECT * FROM   `prodartistica` where `CodUnidade` =  :codunidade and `Ano` =  :ano and `Tipo` =  :tipopa  ");
			//print_r($stmt);
			$stmt->execute(array(':codunidade'=>$codunidade,':ano'=>$ano,':tipopa'=>$tipopa));
			// retorna o resultado da query
			return $stmt;

		}catch ( PDOException $ex ){
			//echo "Erro: ".$ex->getMessage();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}


	public function inseretodos( $pas ){
		try{
			parent::beginTransaction();
			foreach ($pas as $pa){
				$sql="INSERT INTO `prodartistica` (`CodUnidade`,`Tipo`,`Quantidade`,`Ano`)  VALUES (?,?,?,?)";
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$pa->getProdartistica()->getUnidade()->getCodunidade());
				$stmt->bindValue(2,$pa->getCodigo());
				$stmt->bindValue(3,$pa->getProdartistica()->getQuantidade());
				$stmt->bindValue(4,$pa->getProdartistica()->getAno());
				$stmt->execute();
			}
			parent::commit();

		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function alteratodos( $pas ){
		parent::beginTransaction();
		try{
	  foreach ($pas as $pa){
	  	$sql="UPDATE `prodartistica` SET `Quantidade`=? WHERE `Codigo`=?";
	  	$stmt = parent::prepare($sql);
	  	$stmt->bindValue(1, $pa->getProdartistica()->getQuantidade() );
	  	$stmt->bindValue(2, $pa->getProdartistica()->getCodigo());
	  	$stmt->execute();
	  }
	  parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;

			//print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
			header($cadeia);
		}
	}

	public function fechar(){
		PDOConnectionFactory::Close();
	}

}
?>
<?php
class infraensinoDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	private $conex = null;

	// constructor
 /*
	public function infraensinoDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}
 */
	public function deleta( $ie ){
		try{
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `infraensino` WHERE `Codigo`=?");
			$stmt->bindValue(1, $ie->getCodigo() );
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function buscaieunidade($codunidade, $ano){
		try{
			$stmt = parent::prepare("SELECT * FROM   `infraensino` where `CodUnidade` = :codunidade and `Ano` = :ano ");
			$stmt->execute(array(':codunidade'=>$codunidade,':ano'=>$ano));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function buscaie($codunidade, $ano, $tipoie){
		try{
			$sql="SELECT * FROM  `infraensino` where `CodUnidade`=:codunidade and `Ano` =:ano and `Tipo`=:tipoie";
			$stmt = parent::prepare($sql);
			//print_r($stmt);
			$stmt->execute(array(':codunidade'=>$codunidade,':ano'=>$ano,':tipoie'=>$tipoie));
			// retorna o resultado da query
			return $stmt;

		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}


	public function inseretodos( $ies ){
		try{
			parent::beginTransaction();
			foreach ($ies as $ie){
				$sql="INSERT INTO `infraensino` (`CodUnidade`,`Tipo`,`Quantidade`,`Ano`)  VALUES (?,?,?,?)";
				$stmt = parent::prepare($sql);
				//print($ea->getUnidade()->getCodunidade());
				$stmt->bindValue(1,$ie->getInfraensino()->getUnidade()->getCodunidade());
				//print($ea->getUnidade()->getCodunidade());
				$stmt->bindValue(2,$ie->getCodigo());
				//print($ea->getTipo()->getCodigo());
				$stmt->bindValue(3,$ie->getInfraensino()->getQuantidade());
				//print($ea->getQuantidade());
				$stmt->bindValue(4,$ie->getInfraensino()->getAno());
				//print $ea->getAno();
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

	public function alteratodos( $ies ){
		try{
			parent::beginTransaction();
			foreach ($ies as $ie){
				$sql="UPDATE `infraensino` SET `Quantidade`=? WHERE `Codigo`=?";
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1, $ie->getInfraensino()->getQuantidade() );
				$stmt->bindValue(2, $ie->getInfraensino()->getCodigo());
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

	public function fechar(){
		PDOConnectionFactory::Close();
	}
}
?>
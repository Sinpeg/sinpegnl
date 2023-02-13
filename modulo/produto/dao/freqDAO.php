<?php
class FreqDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	private $conex = null;


	// constructor
	/* public function FreqDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}
   */
	public function inseretodos( $eas ){
		$tamanho = count($eas);
		try{
			parent::beginTransaction();
			$sql="INSERT INTO `frequentadores` (`CodUnidade`,`Tipo`,`Quantidade`,`Ano`)  VALUES (?,?,?,?)";
			for ($cont=1; $cont <= $tamanho; $cont++){
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$eas[$cont]->getFrequentadores()->getUnidade()->getCodunidade());
				$stmt->bindValue(2,$eas[$cont]->getCodigo());
				$stmt->bindValue(3,$eas[$cont]->getFrequentadores()->getQuantidade());
				$stmt->bindValue(4,$eas[$cont]->getFrequentadores()->getAno());
				$stmt->execute();
			}
			parent::commit();
		}catch ( PDOException $ex ){
			$db->rollback();
			print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}

	}

	public function alteratodos( $eas ){
		$tamanho = count($eas);
		try{
			parent::beginTransaction();
			$sql = "UPDATE `frequentadores` SET `Quantidade`=? WHERE `Codigo`=?";
			for ($cont=1; $cont <= $tamanho; $cont++){
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$eas[$cont]->getFrequentadores()->getQuantidade());
				$stmt->bindValue(2,$eas[$cont]->getFrequentadores()->getCodigo());
				$stmt->execute();
			}
			parent::commit();
		}catch ( PDOException $ex ){
			$db->rollback();
			print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}

	public function altera( $ac ){
		try{
			$sql="UPDATE `frequentadores` SET `Quantidade`=? WHERE `Codigo`=?";
			parent::beginTransaction();
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1, $ac->getQuantidade());
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}

	public function buscafrequentadores($anobase){
		try{
			$stmt = parent::prepare("SELECT * FROM   `frequentadores` where `Ano` = :ano ");
			$stmt->execute(array(':ano'=>$anobase));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}

	public function buscatipofrequentadores($codunidade, $ano, $tipofreq){
		try{
			$stmt = parent::prepare("SELECT * FROM   `frequentadores` where `CodUnidade` = :codunidade and `Ano` = :ano and `Tipo` = :tipofreq  ");
			$stmt->execute(array(':codunidade'=>$codunidade,':ano'=>$ano,':tipofreq'=>$tipofreq));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}

	}


	public function buscafreq($codigo){
		try{
			$stmt = parent::prepare("SELECT * FROM   `frequentadores` where `Codigo` = :codigo ");
			$stmt->execute(array(':codigo'=>$codigo));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}

	}
	public function buscatipofreq($codunidade, $ano){
		try{
			$stmt = parent::prepare("SELECT * FROM   `frequentadores` where `CodUnidade` = :codunidade and `Ano` = :ano ");
			$stmt->execute(array(':codunidade'=>$codunidade,':ano'=>$ano));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}

	}

	public function insere( $freq ){
		try{
			$sql= "INSERT INTO `frequentadores` (`CodUnidade`,`Tipo`,`ano`,`Quantidade`)  VALUES (?,?,?,?,)";
			parent::beginTransaction();
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1,$freq->getUnidade()->getCodunidade());
			$stmt->bindValue(2,$freq->getTipo()->getCodigo());
			$stmt->bindValue(3,$freq->getAno());
			$stmt->bindValue(4,$freq->getQuantidade());
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}


	public function fechar(){
		PDOConnectionFactory::Close();
	}
}
?>
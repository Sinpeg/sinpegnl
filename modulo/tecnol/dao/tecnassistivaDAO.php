<?php
class TecnassistivaDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	private $conex = null;


	// constructor
/*	public function TecnassistivaDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}
*/
	public function deleta( $ta ){
		try{
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `tecnologia_assistiva` WHERE `CodTecnologiaAssistiva`=?");
			$stmt->bindValue(1, $ta->getCodtecnologiaassistiva() );
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}


	public function altera( $ta ){
		try{
			$sql="UPDATE `tecnologia_assistiva` SET `numpnd`=?,`numpessoasatendidas`=? WHERE `CodTecnologiaAssistiva`=?";
			parent::beginTransaction();
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1, $ta->getNpessoasnecessitadas() );
			$stmt->bindValue(2, $ta->getNpessoasatendidas() );
			$stmt->bindValue(3, $ta->getCodtecnologiaassistiva() );
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}

	public function buscatacurso($codcurso, $ano){
		try{
			$stmt = parent::prepare("SELECT * FROM   `tecnologia_assistiva` where `CodCurso` = :codcurso and `Ano` = :ano ");
			$stmt->execute(array(':codcurso'=>$codcurso,':ano'=>$ano));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}

	public function buscata($codcurso, $ano, $tipota){
		try{
			$stmt = parent::prepare("SELECT * FROM   `tecnologia_assistiva` where `CodCurso` = :codcurso and `Ano` = :ano and `Tipo` = :tipota  ");
			$stmt->execute(array(':codcurso'=>$codcurso,':ano'=>$ano,':tipota'=>$tipota));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}

	public function insere( $ta ){
		try{
			$sql="INSERT INTO `tecnologia_assistiva` (`CodCurso`,`Tipo`,`Ano`)  VALUES (?,?,?)";
			parent::beginTransaction();
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1,$ta->getCurso()->getCodcurso());
			$stmt->bindValue(2,$ta->getTipota()->getCodigo());
			$stmt->bindValue(3,$ta->getAno());
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			$db->rollback(); print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}

	public function inseretodos( $tas ){
		try{
			parent::beginTransaction();
			foreach ($tas as $ta){
				$sql="INSERT INTO `tecnologia_assistiva` (`CodCurso`,`Tipo`,`Ano`)  VALUES (?,?,?)";
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$ta->getCurso()->getCodcurso());
				$stmt->bindValue(2,$ta->getTipota()->getCodigo());
				$stmt->bindValue(3,$ta->getAno());
				$stmt->execute();
			}
			parent::commit();
		}catch ( PDOException $ex ){
			//$db->rollback(); 
			print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}

	}


	public function fechar(){
		PDOConnectionFactory::Close();
	}
}
?>
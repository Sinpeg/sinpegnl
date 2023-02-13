<?php
class atividadeextensaoDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	private $conex = null;

	// constructor
	/* public function atividadeextensaoDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	} */ 


	public function buscaaeunidade($codunidade, $ano){
		try{
			$stmt = parent::prepare("SELECT * FROM   `atividadeextensao` where `CodUnidade` = :codunidade and `Ano` = :ano ");
			$stmt->execute(array(':codunidade'=>$codunidade,':ano'=>$ano));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function buscasubunidade($codsubunidade,$tipo, $ano){
		try{
			$stmt = parent::prepare("SELECT * FROM   `atividadeextensao` where  `Tipo`= :tipo and `CodSubunidade` = :codsubunidade and `Ano` = :ano ");
			$stmt->execute(array(':codsubunidade'=>$codsubunidade,':ano'=>$ano,':tipo'=>$tipo));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function Insere( $ae){
		try{

			$stmt = parent::prepare("INSERT INTO `atividadeextensao` (`CodUnidade`, `Tipo`, `Quantidade`, `Participantes`, `PesAtendidas`, `Ano`,`CodSubunidade`) VALUES (?,?,?,?,?,?,?)");
			parent::beginTransaction();
			$stmt->bindValue(1, $ae->getUnidade()->getCodunidade());
			$stmt->bindValue(2, $ae->getTipo() );
			$stmt->bindValue(3, $ae->getQuantidade() );
			$stmt->bindValue(4, $ae->getParticipantes() );
			$stmt->bindValue(5, $ae->getAtendidas() );
			$stmt->bindValue(6, $ae->getAno());
			$stmt->bindValue(7, $ae->getSubunidade());
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function buscaae($codigo){
		try{

			$stmt = parent::prepare("SELECT * FROM `atividadeextensao` WHERE `Codigo` =:codigo ");
			$stmt->execute(array(':codigo'=>$codigo));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}


	public function altera( $ae ){
		try{
			$sql="UPDATE `atividadeextensao` SET `Tipo`=?, `Quantidade`=?, `Participantes`=?,`PesAtendidas`=? WHERE `Codigo`=?";
			parent::beginTransaction();
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1, $ae->getTipo());
			$stmt->bindValue(2, $ae->getQuantidade());
			$stmt->bindValue(3, $ae->getParticipantes());
			$stmt->bindValue(4, $ae->getAtendidas());
			$stmt->bindValue(5, $ae->getCodigo());
			$stmt->execute();
			parent::commit();
		}
		catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	public function deleta($codigo){
		try{
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `atividadeextensao` WHERE `Codigo` =:codigo ");
			$stmt->execute(array(':codigo'=>$codigo));
			parent::commit();
		}catch ( PDOException $ex ){
			//echo "Erro: ".$ex->getMessage();
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
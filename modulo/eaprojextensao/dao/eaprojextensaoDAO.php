<?php
class EAprojextensaoDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	private $conex = null;

	// constructor
	/*
	public function EAprojextensaoDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}
	*/

	public function Insere( $eaprojextensao){
		try{
			$stmt = parent::prepare("INSERT INTO ea_projextensao (Codigo, EmExecucao, Emtramitacao, Cancelado, Suspenso, Concluido, Qdocentes, Qtecnicos, Qgradbolsistas, Qgradnbolsistas, Qposgrad,QoutrasInstituicoes, Ano ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			parent::beginTransaction();
			
			$stmt->bindValue(1, $eaprojextensao->getCodigo());
			$stmt->bindValue(2, $eaprojextensao->getExecucao() );
			$stmt->bindValue(3, $eaprojextensao->getTramitacao() );
			$stmt->bindValue(4, $eaprojextensao->getCancelado() );
			$stmt->bindValue(5, $eaprojextensao->getSuspenso() );
			$stmt->bindValue(6, $eaprojextensao->getConcluido() );
			$stmt->bindValue(7, $eaprojextensao->getDocentes() );
			$stmt->bindValue(8, $eaprojextensao->getTecnicos() );
			$stmt->bindValue(9, $eaprojextensao->getBolsistas() );
			$stmt->bindValue(10, $eaprojextensao->getNBolsistas() );
			$stmt->bindValue(11, $eaprojextensao->getPosgraduacao() );
			$stmt->bindValue(12, $eaprojextensao->getOutras());
			$stmt->bindValue(13, $eaprojextensao->getAno() );

			// executo a query preparada
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			//print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function Lista($query=null){
		try{
			if( $query == null ){
				// executo a query
				$stmt = parent::query("SELECT * FROM `ea_projextensao`");
			}else{
				$stmt = parent::query($query);
			}
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			//print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function buscape($ano){
		try{
			$stmt = parent::prepare("SELECT * FROM `ea_projextensao` WHERE `Ano` =:ano ");
			$stmt->execute(array(':ano'=>$ano));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			//print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function altera( $pe){
		try{
			$sql="UPDATE `ea_projextensao` SET `EmExecucao`=?, `Emtramitacao`=?,`Cancelado`=?,".
		"`Suspenso`=?,`Concluido`=?,`Qdocentes`=?,`Qtecnicos`=?,`Qgradbolsistas`=?,`Qgradnbolsistas`=?,".
		"`Qposgrad`=?,`QoutrasInstituicoes`=? WHERE `Codigo`=?";
			parent::beginTransaction();
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1, $pe->getExecucao());
			$stmt->bindValue(2, $pe->getTramitacao());
			$stmt->bindValue(3, $pe->getCancelado());
			$stmt->bindValue(4, $pe->getSuspenso());
			$stmt->bindValue(5, $pe->getConcluido());
			$stmt->bindValue(6, $pe->getDocentes());
			$stmt->bindValue(7, $pe->getTecnicos());
			$stmt->bindValue(8, $pe->getBolsistas());
			$stmt->bindValue(9, $pe->getNBolsistas());
			$stmt->bindValue(10, $pe->getPosgraduacao());
			$stmt->bindValue(11, $pe->getOutras());
			$stmt->bindValue(12, $pe->getCodigo());
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			//print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
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

<?php
class EApimetodologicasDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	private $conex = null;

	// constructor
	/*
	public function EApimetodologicasDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}
	*/

	public function Insere( $eapimetodologicas){
		try{
			$stmt = parent::prepare("INSERT INTO pi_metodologicas (Codigo, EmExecucao, EmTramitacao, Cancelado, Suspenso, Concluido, Qdocentes, Qtecnicos, Qgradbolsistas, Qgradnbolsistas, Qposgrad,QoutrasInstituicoes, Ano ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			parent::beginTransaction();
			
			$stmt->bindValue(1, $eapimetodologicas->getCodigo());
			$stmt->bindValue(2, $eapimetodologicas->getExecucao() );
			$stmt->bindValue(3, $eapimetodologicas->getTramitacao() );
			$stmt->bindValue(4, $eapimetodologicas->getCancelado() );
			$stmt->bindValue(5, $eapimetodologicas->getSuspenso() );
			$stmt->bindValue(6, $eapimetodologicas->getConcluido() );
			$stmt->bindValue(7, $eapimetodologicas->getDocentes() );
			$stmt->bindValue(8, $eapimetodologicas->getTecnicos() );
			$stmt->bindValue(9, $eapimetodologicas->getBolsistas() );
			$stmt->bindValue(10, $eapimetodologicas->getNBolsistas() );
			$stmt->bindValue(11, $eapimetodologicas->getPosgraduacao() );
			$stmt->bindValue(12, $eapimetodologicas->getOutras());
			$stmt->bindValue(13, $eapimetodologicas->getAno() );

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
				$stmt = parent::query("SELECT * FROM `pi_metodologicas`");
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

	public function buscapim($ano){
		try{
			$stmt = parent::prepare("SELECT * FROM `pi_metodologicas` WHERE `Ano` =:ano ");
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

	public function altera( $pim){
		try{
			$sql="UPDATE `pi_metodologicas` SET `EmExecucao`=?, `EmTramitacao`=?,`Cancelado`=?,".
		"`Suspenso`=?,`Concluido`=?,`Qdocentes`=?,`Qtecnicos`=?,`Qgradbolsistas`=?,`Qgradnbolsistas`=?,".
		"`Qposgrad`=?,`QoutrasInstituicoes`=? WHERE `Codigo`=?";
			parent::beginTransaction();
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1, $pim->getExecucao());
			$stmt->bindValue(2, $pim->getTramitacao());
			$stmt->bindValue(3, $pim->getCancelado());
			$stmt->bindValue(4, $pim->getSuspenso());
			$stmt->bindValue(5, $pim->getConcluido());
			$stmt->bindValue(6, $pim->getDocentes());
			$stmt->bindValue(7, $pim->getTecnicos());
			$stmt->bindValue(8, $pim->getBolsistas());
			$stmt->bindValue(9, $pim->getNBolsistas());
			$stmt->bindValue(10, $pim->getPosgraduacao());
			$stmt->bindValue(11, $pim->getOutras());
			$stmt->bindValue(12, $pim->getCodigo());
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

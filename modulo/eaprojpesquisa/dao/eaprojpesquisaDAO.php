<?php
class EAprojpesquisaDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	private $conex = null;

	// constructor
	/*
	public function EAprojpesquisaDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}
	*/

	// remove um registro
	public function Deleta( $id ){
		try{
			// executo a query
			$num = parent::exec("DELETE FROM `ea_projpesquisa` WHERE `Codigo`=$id");
			// caso seja execuado ele retorna o n�mero de rows que foram afetadas.
			if( $num >= 1 ){
				return $num;
			} else { return 0;
			}
			// caso ocorra um erro, retorna o erro;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}

	public function Insere( $eaprojpesquisa){
		try{
			$stmt = parent::prepare("INSERT INTO ea_projpesquisa (Codigo, EmExecucao, Emtramitacao, Cancelado, Suspenso, Concluido, Qdocentes, Qtecnicos, Qdiscentes, QoutrasInstituicoes, Ano ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			parent::beginTransaction();
					
			$stmt->bindValue(1, $eaprojpesquisa->getCodigo());
			$stmt->bindValue(2, $eaprojpesquisa->getExecucao() );
			$stmt->bindValue(3, $eaprojpesquisa->getTramitacao() );
			$stmt->bindValue(4, $eaprojpesquisa->getCancelado() );
			$stmt->bindValue(5, $eaprojpesquisa->getSuspenso() );
			$stmt->bindValue(6, $eaprojpesquisa->getConcluido() );
			$stmt->bindValue(7, $eaprojpesquisa->getDocentes() );
			$stmt->bindValue(8, $eaprojpesquisa->getTecnicos() );
			$stmt->bindValue(9, $eaprojpesquisa->getDiscentes() );
			$stmt->bindValue(10, $eaprojpesquisa->getOutras());
			$stmt->bindValue(11, $eaprojpesquisa->getAno() );

			// executo a query preparada
			$stmt->execute();
			parent::commit();
				
			// caso ocorra um erro, retorna o erro;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}

	public function Lista($query=null){
		try{
			if( $query == null ){
				// executo a query
				$stmt = parent::query("SELECT * FROM `ea_projpesquisa`");
			}else{
				$stmt = parent::query($query);
			}
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}

	public function buscapp($ano){
		try{

			$stmt = parent::prepare("SELECT * FROM `ea_projpesquisa` WHERE `Ano` =:ano ");
			$stmt->execute(array(':ano'=>$ano));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}


	public function altera( $pp){
		try{
			$sql="UPDATE `ea_projpesquisa` SET `EmExecucao`=?, `Emtramitacao`=?,`Cancelado`=?,".
		"`Suspenso`=?,`Concluido`=?,`Qdocentes`=?,`Qtecnicos`=?,`Qdiscentes`=?,`QoutrasInstituicoes`=? WHERE `Codigo`=?";
			parent::beginTransaction();
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1, $pp->getExecucao());
			$stmt->bindValue(2, $pp->getTramitacao());
			$stmt->bindValue(3, $pp->getCancelado());
			$stmt->bindValue(4, $pp->getSuspenso());
			$stmt->bindValue(5, $pp->getConcluido());
			$stmt->bindValue(6, $pp->getDocentes());
			$stmt->bindValue(7, $pp->getTecnicos());
			$stmt->bindValue(8, $pp->getDiscentes());
			$stmt->bindValue(9, $pp->getOutras());
			$stmt->bindValue(10, $pp->getCodigo());
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

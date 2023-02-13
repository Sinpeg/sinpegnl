<?php
class LaboratorioDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	public	 $conex = null;

	// constructor
	public function LaboratorioDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}

	// realiza uma inser��o
	public function insere( $laboratorio ){
		try{
			$sql="INSERT INTO `laboratorio` (`CodUnidade`, `Tipo`, `Area`,`Capacidade`,`Nome`,`Sigla`,".
"`Resposta`,`LabEnsino`,`Nestacoes`,`Local`,`SisOperacional`,`CabEstruturado`,`Situacao`,`AnoAtivacao`) ".
" VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$stmt = $this->conex->prepare($sql);
			$this->conex->beginTransaction();
			$stmt->bindValue(1, $laboratorio->getUnidade()->getCodunidade());
			$stmt->bindValue(2, $laboratorio->getTipo()->getCodigo());
			$stmt->bindValue(3, $laboratorio->getArea());
			$stmt->bindValue(4, $laboratorio->getCapacidade());
			$stmt->bindValue(5, $laboratorio->getNome());
			$stmt->bindValue(6, $laboratorio->getSigla());
			$stmt->bindValue(7, $laboratorio->getResposta());
			$stmt->bindValue(8, $laboratorio->getLabensino());
			$stmt->bindValue(9, $laboratorio->getNestacoes());
			$stmt->bindValue(10, $laboratorio->getLocal());
			$stmt->bindValue(11, $laboratorio->getSo());
			$stmt->bindValue(12, $laboratorio->getCabo());
			$stmt->bindValue(13, $laboratorio->getSituacao());
			$stmt->bindValue(14, $laboratorio->getAnoativacao());
			$stmt->execute();
			$this->conex->commit();
		}catch ( PDOException $ex ){
			$db->rollback(); print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}

	}
	// realiza um Update
	public function altera( $laboratorio ){
		try{

			$stmt = $this->conex->prepare("UPDATE `laboratorio` SET  `Tipo`=?,`Area`=?,`Capacidade` = ?,`Nome` =?,`Sigla` = ?,`Resposta`=?, `Labensino`=?, `Nestacoes`=?, `Local`=?, `SisOperacional`=?,`CabEstruturado`=?,`Situacao`=?,`AnoDesativacao`=? WHERE CodLaboratorio=?");
			$this->conex->beginTransaction();
			$stmt->bindValue(1, $laboratorio->getTipo()->getCodigo());
			$stmt->bindValue(2, $laboratorio->getArea());
			$stmt->bindValue(3, $laboratorio->getCapacidade());
			$stmt->bindValue(4, $laboratorio->getNome());
			$stmt->bindValue(5, $laboratorio->getSigla());
			print "upda labresposta".$laboratorio->getResposta()."<br/>";
			$stmt->bindValue(6, $laboratorio->getResposta());
			print "upda labensino".$laboratorio->getLabensino()."<br/>";
			$stmt->bindValue(7, $laboratorio->getLabensino());
			$stmt->bindValue(8, $laboratorio->getNestacoes());
			$stmt->bindValue(9, $laboratorio->getLocal());
			$stmt->bindValue(10, $laboratorio->getSo());
			$stmt->bindValue(11, $laboratorio->getCabo());
			$stmt->bindValue(12, $laboratorio->getSituacao());
			$stmt->bindValue(13, $laboratorio->getAnodesativacao());
			$stmt->bindValue(14, $laboratorio->getCodlaboratorio());

			$stmt->execute();
			$this->conex->commit();
		}catch ( PDOException $ex ){
			$db->rollback(); print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}
	// remove um registro
	/*public function deleta( $Codlaboratorio ){
	 try{
	// executo a query
	$num = $this->conex->exec("DELETE FROM laboratorio WHERE CodLaboratorio=:Codlaboratorio");
	// caso seja execuado ele retorna o n�mero de rows que foram afetadas.
	if( $num >= 1 ){ return $num; } else { return 0; }
	// caso ocorra um erro, retorna o erro;
	}catch ( PDOException $ex ){ echo "Erro: ".$ex->getMessage(); }
	}*/
	public function deleta( $codlab ){
		try{
			$this->conex->beginTransaction();
			$stmt = $this->conex->prepare("DELETE FROM `laboratorio` WHERE `CodLaboratorio`=?");
			$stmt->bindValue(1, $codlab );
			$stmt->execute();
			$this->conex->commit();
		}catch ( PDOException $ex ){
			$db->rollback(); print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}
	public function Lista(){
		try{

			$stmt = $this->conex->query("SELECT * FROM laboratorio");

			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}
	public function buscaLaboratorio($codlaboratorio) {
		try{
			$stmt = $this->conex->prepare("SELECT * FROM laboratorio where Codlaboratorio=:codlaboratorio");
			$stmt->execute(array(':codlaboratorio'=>$codlaboratorio));
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}
	public function buscaLaboratoriosUnidade($CodUnidade){
		try{
			$stmt = $this->conex->prepare("SELECT * FROM tdm_tipo_laboratorio t,laboratorio l where CodUnidade=:codunidade and t.Codigo = l.Tipo");

			$stmt->execute(array(':codunidade'=>$CodUnidade));
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}
	public function fechar(){
		PDOConnectionFactory::Close();
	}
}
?>
<?php

class ObjetivoDAO extends PDOConnectionFactory{
	
	public $conex = null;
	
	public function insere(Objetivo $objetivo) {
		try {
			$query = "INSERT INTO `objetivo` (`Codigo`, `objetivo`, `descricaoObjetivo`) VALUES (?,?,?)";
			$stmt = parent::prepare($query);
			parent::beginTransaction();
			$stmt->bindValue(1, $objetivo->getCodigo());
			$stmt->bindValue(2, $objetivo->getObjetivo());
			$stmt->bindValue(2, $objetivo->getDescricao());
			
			$stmt->execute();
			parent::commit();
		} catch (PDOException $ex) {
			parent::rollback();
			print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}
	
	
	
	public function altera(Objetivo $objetivo) {
		try {
			$stmt = parent::prepare("UPDATE `objetivo` SET `Objetivo`=? `DescricaoObjetivo`=? WHERE `codigo`=?");
			parent::beginTransaction();
			$stmt->bindValue(1, $objetivo->getObjetivo());
			$stmt->bindValue(2, $objetivo->getDescricao());
			$stmt->bindValue(2, $objetivo->getCodigo());
			$stmt->execute();
			parent::commit();
			
		} catch (PDOException $ex) {
			parent::rollback();
			print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}
	
	public function deleta($codigo) {
		try {
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `objetivo` WHERE `Codigo`=?");
			$stmt->bindValue(1, $codigo);
			$stmt->execute();
			parent::commit();
		} catch (PDOException $ex) {
			print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
		}
	}
	
	public function lista() {
		try {
			
			$sql = "SELECT * FROM `objetivo`";
			$stmt = parent::prepare($sql);
			$stmt->execute();
			return $stmt;
			
		} catch (PDOException $ex) {
			print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
		}
	}
	
	public function buscaobjetivo($codigo) {
		try {
			$sql = "SELECT * FROM `objetivo` WHERE `codigo`=:Codigo";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':codigo' => $codigo));
			return $stmt;
		} catch (PDOException $ex) {
			
		}
	}
	
	public function fechar() {
		PDOConnectionFactory::Close();
	}
	
} 
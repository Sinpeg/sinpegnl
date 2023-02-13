<?php

class PerspectivaDAO extends PDOConnectionFactory{
	private $conex = null;
	
	public function insere(Perspectiva $perspectiva) {
		try {
			$query = "INSERT INTO `perspectiva` (`CodPerspectiva`, `nome`) VALUES (?,?)";
			$stmt = parent::prepare($query);
			parent::beginTransaction();
			$stmt->bindValue(1, $perspectiva->getCodigo());
			$stmt->bindValue(2, $perspectiva->getNome());
			
			$stmt->execute();
			parent::commit();
		} catch (PDOException $ex) {
			parent::rollback();
			print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}
	
	
	
	public function altera(Perspectiva $perspectiva) {
		try {
			$stmt = parent::prepare("UPDATE `perspectiva` SET `nome`=? WHERE `codPerspectiva`=?");
			parent::beginTransaction();
			$stmt->bindValue(1, $perspectiva->getNome());
			$stmt->bindValue(2, $perspectiva->getCodigo());
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
			$stmt = parent::prepare("DELETE FROM `perspectiva` WHERE `codPerspectiva`=?");
			$stmt->bindValue(1, $codigo);
			$stmt->execute();
			parent::commit();
		} catch (PDOException $ex) {
			print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
		}
	}
	
	public function lista() {
		try {
			$stmt = parent::query("SELECT * FROM `perspectiva`");
			return $stmt;
		} catch (PDOException $ex) {
			print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
		}
	}
	
	public function buscaperspectiva($codigo) {
		try {
			$sql = "SELECT * FROM `perspectiva` WHERE `codperspectiva`=:codigo";
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
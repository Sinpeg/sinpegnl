<?php

class CalendarioDAO extends PDOConnectionFactory{
	
	public $conex = null;
	
	public function insere(Calendario $calendario) {
		try {
			$query = "INSERT INTO `calendario` ( `CodUnidade`, `codDocumento`, `anoGestao`, `dataIniAnalise`, 'dataFimAnalise') VALUES (?,?,?,?,?)";
			$stmt = parent::prepare($query);
			parent::beginTransaction();
			
			$stmt->bindValue(1, $calendario->getCodUnidade());
			$stmt->bindValue(2, $calendario->getCodDocumento());
			$stmt->bindValue(2, $calendario->getAnoGestao());
			$stmt->bindValue(2, $calendario->getDataIniAnalise());
			$stmt->bindValue(2, $calendario->getDataFimAnalise());
			
			$stmt->execute();
			parent::commit();
		} catch (PDOException $ex) {
			parent::rollback();
			print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}
	
	
	
	public function altera(Calendario $calendario) {
		try {
			$stmt = parent::prepare("UPDATE `calendario` SET `CodUnidade`=? `codDocumento`=? `anoGestao`=? `dataIniAnalise`=? `dataFimAnalise`=? WHERE `codCalendario`=?");
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
			$stmt = parent::prepare("DELETE FROM `calendario` WHERE `codCalendario`=?");
			$stmt->bindValue(1, $codigo);
			$stmt->execute();
			parent::commit();
		} catch (PDOException $ex) {
			print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
		}
	}
	
	public function lista() {
		try {
			
			$sql = "SELECT * FROM `calendario`";
			$stmt = parent::prepare($sql);
			$stmt->execute();
			return $stmt;
			
		} catch (PDOException $ex) {
			print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
		}
	}
	
	public function fechar() {
		PDOConnectionFactory::Close();
	}
	
} 
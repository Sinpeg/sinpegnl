<?php

class ComentarioSolicitpduDAO extends PDOConnectionFactory{
	
	public $conex = null;
	
	public function insere(ComentarioSolicitPDU $c) {
		try {
			$query = "INSERT INTO `comentarioSolicitPDU` (`codigo`, `autor`, `codSolicitacao`, `texto`) VALUES (?,?,?,?)";
			$stmt = parent::prepare($query);
			parent::beginTransaction();
			
			$stmt->bindValue(1, $c->getCodigo());
			$stmt->bindValue(2, $c->getAutor());
			$stmt->bindValue(3, $c->getSolicitacao()->getCodigo());
			$stmt->bindValue(4, $c->getTexto());			
			
			$stmt->execute();
			parent::commit();
		} catch (PDOException $ex) {
			parent::rollback();
			print "Erro: insere: ComentarioSolicitpduDAO " . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}
	
	
	
	public function altera(Calendario $calendario) {
		try {
			$stmt = parent::prepare("UPDATE `comentarioSolicitPDU` SET `codSolicitacao`=?, `texto`=? ,`dataComentario`=?  WHERE `codigo`=?");
			parent::beginTransaction();
			$stmt->bindValue(1, $c->getSolicitacao()->getCodigo());
			$stmt->bindValue(2, $c->getTexto());
			$stmt->bindValue(3, $c->getDatacomentario());
			$stmt->bindValue(4, $c->getCodigo());
			
			$stmt->execute();
			parent::commit();
			
		} catch (PDOException $ex) {
			parent::rollback();
			print "Erro: altera: ComentarioSolicitpduDAO" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}
	
	public function deleta($codigo) {
		try {
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `comentarioSolicitPDU` WHERE `codCalendario`=?");
			$stmt->bindValue(1, $codigo);
			$stmt->execute();
			parent::commit();
		} catch (PDOException $ex) {
			print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
		}
	}
	
	public function lista() {
		try {
			
			$sql = "SELECT * FROM `comentarioSolicitPDU` ";
			$stmt = parent::prepare($sql);
			$stmt->execute();
			return $stmt;
			
		} catch (PDOException $ex) {
			print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
		}
	}
	
	
 public function buscaComentarios($codsolicitacao) {
        try {
            $sql = "SELECT * FROM `comentarioSolicitPDU` WHERE `CodSolicitacao` = :codigo";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codsolicitacao));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }
	
	public function fechar() {
		PDOConnectionFactory::Close();
	}
	
} 
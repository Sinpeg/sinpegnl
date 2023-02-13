<?php
class PerspectivaDAO extends PDOConnectionFactory {
	private $conex = null;
	
	public function deleta( $codigo ){
		try{
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `perspectiva` WHERE `codPerspectiva`=?");
			$stmt->bindValue(1, $codigo);
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}
	
	public function altera(Perspectiva $p) {
		try {
			$stmt = parent::prepare("UPDATE `perspectiva` SET `nome`=? WHERE `codPerspectiva`=?");
			parent::beginTransaction();
			$stmt->bindValue(1, $p->getNome());
			$stmt->bindValue(2, $p->getCodigo());
			$stmt->execute();
			parent::commit();
		} catch (PDOException $ex) {
			print "Erro: altera:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}
	
	public function insere(Perspectiva $p) {
		try {
			$query = "INSERT INTO `perspectiva` (`nome`) VALUES (?)";
			$stmt = parent::prepare($query);
			parent::beginTransaction();
			$stmt->bindValue(1, $p->getNome());
			$stmt->execute();
			//parent::commit();
			return parent::lastInsertId();
		} catch (PDOException $ex) {
			print "Erro: insere:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}
	

	public function lista() {
		try {
			$stmt = parent::query("SELECT * FROM `perspectiva`");
			return $stmt;
		} catch (PDOException $ex) {
			print "Erro: lista" . $ex->getCode() . " " . $ex->getMessage();
		}
	}
	public function buscaperspectiva($codigo) {
		try {
			$sql = "SELECT * FROM `perspectiva` WHERE `codPerspectiva`=:codigo";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':codigo' => $codigo));
			return $stmt;
		} catch (PDOException $ex) {
			
		}

	}
	public function buscaperspectivanome($nome) {
		try {
			echo "buscaperspectivanome   ".$nome;
			$sql = "SELECT * FROM `perspectiva` WHERE `nome`like :nome";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':nome' => $nome));
			return $stmt;
		} catch (PDOException $ex) {
			
		}
	}
	
	public function fechar() {
		PDOConnectionFactory::Close();
	}
	
}
?>



	


<?php

class AvaliacaofinalDAO extends PDOConnectionFactory {
	
	public $conex = null;
	/*
	
	 */
	
	public function buscaAval( $ano, $periodo) {
		
		try {
            $sql = "SELECT * FROM avaliacaofinal a INNER JOIN calendario c ON(c.codCalendario = a.codCalendario) WHERE c.anoGestao=:ano AND periodo = :per ";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':ano' => $ano, ':per' => $periodo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
		
		
	}
	
	
	public function buscaAvalDP($codDocumento, $codcal, $periodo) {
		
		try {
            $sql = "SELECT * FROM avaliacaofinal a INNER JOIN calendario c ON c.codCalendario = a.codCalendario WHERE a.codDocumento= :coddoc AND c.codCalendario = :codcal and a.periodo=:periodo";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':coddoc' => $codDocumento, ':codcal' => $codcal,':periodo'=>$periodo));
                        return $stmt;

        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
		
		
	}
	
	public function buscaArquivoAval($codigo) {
		
		try {
            $sql = "SELECT * FROM `avaliacaofinal` WHERE `codigo`=:codigo";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
                        return $stmt;

        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
	}
	
	public function insere(Avaliacaofinal $avaliacaofinal) {
		
			try{
			$stmt = parent::prepare("INSERT INTO `avaliacaofinal` (`codDocumento`, `codCalendario`, `avaliacao`,`RAT`, `periodo`) 
					VALUES (?,?,?,?,?)");
			parent::beginTransaction();
			$stmt->bindValue(1, $avaliacaofinal->getDocumento()->getCodigo());
			$stmt->bindValue(2,  $avaliacaofinal->getCalendario()->getCodigo());
			$stmt->bindValue(3, $avaliacaofinal->getAvaliacao() );
			$stmt->bindValue(4, $avaliacaofinal->getRat() );
			$stmt->bindValue(5, $avaliacaofinal->getPeriodo() );
			$stmt->execute();
			parent::commit();

		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
		}
	
		
	}
	

    public function altera( Avaliacaofinal $avaliacaofinal ){
		try{
			$sql="UPDATE `avaliacaofinal` SET `CodDocumento` =?, `codCalendario` =?, `avaliacao` =?,
					  `rat` =?,  `periodo` =? WHERE `Codigo`=?";
			parent::beginTransaction();
			$stmt = parent::prepare($sql);
			
			$stmt->bindValue(1, $avaliacaofinal->getDocumento()->getCodigo());
			$stmt->bindValue(2, $avaliacaofinal->getCalendario()->getCodigo());
			$stmt->bindValue(3, $avaliacaofinal->getAvaliacao());
			$stmt->bindValue(4, $avaliacaofinal->getRat());
			$stmt->bindValue(5, $avaliacaofinal->getPeriodo());
			$stmt->bindValue(6, $avaliacaofinal->getCodigo());
			$stmt->execute();
            parent::commit();
		}
		catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
		}
	}
	
	public function deleta($codigo) {
		try {
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `avaliacaofinal` WHERE `codigo`=?");
			$stmt->bindValue(1, $codigo);
			$stmt->execute();
			parent::commit();
		} catch (PDOException $ex) {
			parent::rollback();
			print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}
	
	public function lista() {
	
        
         try {
            $sql = "SELECT * FROM `avaliacaofinal` ORDER BY `codigo`";
            $stmt = parent::prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
        }
	}
	
	public function listadisctinct() {
		try {
			$stmt = parent::query("SELECT DISTINCT * FROM `avaliacaofinal`");
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	
	public function buscaAvaliacafinalPorCodigo($codaval){
			
			try {
				parent::beginTransaction();
				$stmt = parent::query("SELECT * FROM avaliacaofinal WHERE codigo = ?");
				$stmt->bindValue(1, $codaval);
				$stmt->execute();
				parent::commit();
			} catch (PDOException $ex) {
				parent::rollback();
				print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
			}
			
	}
	
	public function buscaAvaliacafinalPorCodDocumento(){
		
		try {
			parent::beginTransaction();
			$stmt = parent::query("SELECT * FROM avaliacaofinal WHERE codDocumento= {$codDocumento}");
			$stmt->execute();
			parent::commit();
		} catch (PDOException $ex) {
			parent::rollback();
			print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
		
	}
	
	public function fechar() {
		PDOConnectionFactory::Close();
	}
}
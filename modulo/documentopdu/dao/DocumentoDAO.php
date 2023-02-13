<?php
class DocumentoDAO extends PDOConnectionFactory {
    public $conex = null;
    /*
    public function DocumentoDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
    }
    */

    public function insere(Documento $documento) {
        try {
            $query = "INSERT INTO `documento` (`CodDocumento`, `CodUnidade`, `nome`, `anoinicial`,".
                "`anofinal`,`situacao`,`missao`,`visao`)".
                " VALUES (?,?,?,?,?,?,?,?)";
            $stmt = parent::prepare($query);
            parent::beginTransaction();
            $stmt->bindValue(1, $documento->getCodigoPDI());
            $stmt->bindValue(2, $documento->getUnidade()->getCodunidade());
            $stmt->bindValue(3, $documento->getNome());
            $stmt->bindValue(4, $documento->getAnoInicial());
            $stmt->bindValue(5, $documento->getAnoFinal());
            $stmt->bindValue(6, $documento->getSituacao());
            $stmt->bindValue(7, $documento->getMissao());
            $stmt->bindValue(8, $documento->getVisao());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }

    public function altera(Documento $documento) {
        try {
            $stmt = parent::prepare("UPDATE `documento` SET `nome`=?, `anoinicial`=?, `anofinal`=?, `situacao`=?, `missao`=?, `visao`=? WHERE `codigo`=?");
            parent::beginTransaction();
            $stmt->bindValue(1, $documento->getNome());
            $stmt->bindValue(2, $documento->getAnoInicial());
            $stmt->bindValue(3, $documento->getAnoFinal());
            $stmt->bindValue(4, $documento->getSituacao());
            $stmt->bindValue(5, $documento->getMissao());
            $stmt->bindValue(6, $documento->getVisao());
            $stmt->bindValue(7, $documento->getCodigo());
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
            $stmt = parent::prepare("DELETE FROM `documento` WHERE `codigo`=?");
            $stmt->bindValue(1, $codigo);
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }

    public function lista() {
        try {
            $stmt = parent::query("SELECT * FROM `documento`");
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }
    
        public function listanull() {
        try {
            $stmt = parent::query("SELECT * FROM `documento` WHERE `CodDocumento` IS NULL");
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }

    public function buscadocumento($codigo) {
        try {
            $sql = "SELECT * FROM `documento` WHERE `codigo`=:codigo";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            
        }
    }
    
     public function buscaPDI() {
        try {
            $sql = "SELECT * FROM `documento` WHERE `CodDocumento` IS NULL ORDER BY `nome` ";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            
        }
    }
    
    public function buscadocumentoporunidade($codUnidade) {
        try {
            $sql = "SELECT * FROM `documento` WHERE `CodUnidade`=:codigo";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codUnidade));
            return $stmt;
        } catch (PDOException $ex) {
            
        }
    }
    

    public function fechar() {
        PDOConnectionFactory::Close();
    }

}

?>

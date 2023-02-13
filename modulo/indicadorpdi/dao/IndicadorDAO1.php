<?php

class IndicadorDAO extends PDOConnectionFactory {

    public $conex = null;
/*
    public function IndicadorDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
    }
*/
    
    public function insere(Indicador $indicador) {
        try {
            $sql = "INSERT INTO `indicador` (`PropIndicador`, `CodMapa` ," .
                    " `indicador`, `calculo`, `validade`) VALUES (?,?,?,?,?)";
            $stmt = parent::prepare($sql);
            parent::beginTransaction();
            $stmt->bindValue(1, $indicador->getUnidade()->getCodunidade()); // unidade
            $stmt->bindValue(2, $indicador->getMapa()->getCodigo()); // mapa
            $stmt->bindValue(3, $indicador->getIndicador());
            $stmt->bindValue(4, $indicador->getCalculo());
            $stmt->bindValue(5, $indicador->getValidade());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }

    public function altera(Indicador $indicador) {
        try {
            $sql = "UPDATE `indicador` SET `PropIndicador`=?,`CodMapa`=?,`indicador`=?,`calculo`=?,`validade`=? WHERE `Codigo`=?";
            $stmt = parent::prepare($sql);
            parent::beginTransaction();   
            $stmt->bindValue(1, $indicador->getUnidade()->getCodunidade());
            $stmt->bindValue(2, $indicador->getMapa()->getCodigo());
            $stmt->bindValue(3, $indicador->getIndicador());
            $stmt->bindValue(4, $indicador->getCalculo());
            $stmt->bindValue(5, $indicador->getValidade());
            $stmt->bindValue(6, $indicador->getCodigo());
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
            $stmt = parent::prepare("DELETE FROM `indicador` WHERE `Codigo`=?");
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
            $stmt = parent::query("SELECT * FROM `indicador`");
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaindicador($codigo) {
        try {
            $sql = "SELECT * FROM `indicador` WHERE `Codigo`=:codigo";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaindicadorunidade($codUnidade) {
        try {
            $sql = "SELECT * FROM `indicador` WHERE `PropIndicador`=:codigo ORDER BY `Codigo`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codUnidade));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaindicadorpormapa($codigo, $codunidade) {
        try {
            $sql = "SELECT * FROM `indicador` WHERE `CodMapa`= :codigo
                    AND `PropIndicador`= :codunidade";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo, ':codunidade' => $codunidade));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaindicadorpormapa1($codigo, $codunidade) {
        try {
            $sql = "SELECT DISTINCT(`CodMapa`) FROM `indicador` WHERE `CodMapa` = :codigo
                    AND `PropIndicador` = :codunidade ORDER BY `CodMapa`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo, ':codunidade' => $codunidade));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaindicadorpormapa2($codigo) {
        try {
            $sql = "SELECT * FROM `indicador` WHERE `CodMapa` = :codigo ORDER BY `CodMapa`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function fechar() {
        PDOConnectionFactory::Close();
    }

}

?>

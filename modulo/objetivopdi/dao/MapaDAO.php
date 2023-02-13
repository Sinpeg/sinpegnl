<?php

class MapaDAO extends PDOConnectionFactory {

    public $conex = null;
 /*
    public function MapaDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
    }
 */
    public function insere(Mapa $mapa) {
        try {
            $sql = "INSERT INTO `mapa` (`CodigoDocumento`, `codPerspectiva`, `codObjetivoPDI`, `CodCalendario`,
										 `Ordem`, `PropMapa`, `ordemPersp`, `dataCadastro`, `visao`)
										 VALUES (?,?,?,?,?,?,?,?,?)";
            $stmt = parent::prepare($sql);
            parent::beginTransaction();
            $stmt->bindValue(1, $mapa->getCodDocumento());
            $stmt->bindValue(2, $mapa->getCodPerspectiva());
            $stmt->bindValue(3, $mapa->getCodObjetivoPDI());
            $stmt->bindValue(4, $mapa->getCodCalendario());
            $stmt->bindValue(5, $mapa->getOrdem());
            $stmt->bindValue(6, $mapa->getPropMapa());
            $stmt->bindValue(7, $mapa->getOrdemPerspectiva());
            $stmt->bindValue(8, $mapa->getDataCadastro());
            $stmt->bindValue(9, $mapa->getVisao());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }

    public function altera(Mapa $mapa) {
        try {
            $sql = "UPDATE `mapa` SET `CodigoDocumento` =?, `codPerspectiva` =?, `codObjetivoPDI` =?, `CodCalendario` =?,
					 `Ordem` =?, `PropMapa` =?, `ordemPersp` =?, `dataCadastro` =?, `visao` =? WHERE `Codigo`=?";
            $stmt = parent::prepare($sql);
            parent::beginTransaction();
//            print $sql;
            $stmt->bindValue(1, $mapa->getCodDocumento());
            $stmt->bindValue(2, $mapa->getCodPerspectiva());
            $stmt->bindValue(3, $mapa->getCodObjetivoPDI());
            $stmt->bindValue(4, $mapa->getCodCalendario());
            $stmt->bindValue(5, $mapa->getOrdem());
            $stmt->bindValue(6, $mapa->getPropMapa());
            $stmt->bindValue(7, $mapa->getOrdemPerspectiva());
            $stmt->bindValue(8, $mapa->getDataCadastro());
            $stmt->bindValue(9, $mapa->getVisao());
            $stmt->bindValue(10, $mapa->getCodigo());
            
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }

    public function deleta($codigo) {
        try {
            parent::beginTransaction();
            $stmt = parent::prepare("DELETE FROM `mapa` WHERE `Codigo`=?");
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
            $stmt = parent::query("SELECT * FROM `mapa` ORDER BY `Codigo`");
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function listadisctinct() {
        try {
            $stmt = parent::query("SELECT DISTINCT * FROM `mapa`");
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscamapadocumento($codigo) {
        try {
            $sql = "SELECT * FROM `mapa` WHERE `CodigoDocumento` = :codigo ORDER BY `Codigo`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscamapa($codigo) {
        try {
            $sql = "SELECT * FROM `mapa` WHERE `Codigo` = :codigo ORDER BY `Objetivo`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscamapaordem($ordem) {
        try {
            $sql = "SELECT * FROM `mapa` WHERE `Ordem` =:codigo ORDER BY `Objetivo`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $ordem));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscamapaordemdoc($ordem, $coddoc) {
        try {
            $sql = "SELECT * FROM `mapa` m, `documento` d WHERE `Ordem` =:codigo" .
                    " AND d.`codigo` = :coddoc" .
                    " AND m.`CodigoDocumento` = d.`codigo`" .
                    " ORDER BY `codObjetivoPDI`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $ordem, ':coddoc' => $coddoc));
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
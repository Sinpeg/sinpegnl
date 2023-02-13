<?php

class ProdfarmaciaDAO extends PDOConnectionFactory {

    // ir� receber uma conex�o
    private $conex = null;

    // constructor
   /* public function ProdfarmaciaDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
    }
   */
    public function deleta($prod) {
        try {
            parent::beginTransaction();
            $stmt = parent::prepare("DELETE FROM `prodfarmacia` WHERE `Codigo`=?");
            $stmt->bindValue(1, $prod);
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            $db->rollback();
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function inseretodos($prod) {
        $tamanho = count($prod);
        try {
            parent::beginTransaction();
            $sql = "INSERT INTO `prodfarmacia` (`Tipoproduto`,`Quantidade`,`Ano`,`Preco`,`Mes`)  VALUES (?,?,?,?,?)";
            for ($cont = 1; $cont <= $tamanho; $cont++) {
                $stmt = parent::prepare($sql);
                $stmt->bindValue(1, $prod[$cont]->getProdfarmacia()->getTipoproduto);
                $stmt->bindValue(2, $prod[$cont]->getProdfarmacia()->getQuantidade());
                $stmt->bindValue(3, $prod[$cont]->getProdfamracia()->getAno());
                $stmt->bindValue(4, $prod[$cont]->getProdfamracia()->getPreco());
                $stmt->bindValue(5, $prod[$cont]->getProdfamracia()->getMes());

                $stmt->execute();
            }
            parent::commit();
        } catch (PDOException $ex) {
            $db->rollback();
            print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }

    public function alteratodos($prod) {
        $tamanho = count($prod);
        try {
            parent::beginTransaction();
            $sql = "UPDATE `prodfarmacia` SET `Tipoproduto`=?,`Quantidade`=? ,`Ano`=?,`Preco`=?  WHERE `Codigo`=?";
            for ($cont = 1; $cont <= $tamanho; $cont++) {
                $stmt = parent::prepare($sql);
                $stmt->bindValue(1, $prod->getTipoproduto());
                $stmt->bindValue(2, $prod->getQuantidade());
                $stmt->bindValue(3, $prod->getAno());
                $stmt->bindValue(4, $prod->getPreco());
                $stmt->bindValue(5, $prod->getCodigo());
                $stmt->execute();
            }
            parent::commit();
        } catch (PDOException $ex) {
            $db->rollback();
            print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }

    public function altera($prod) {
        try {
            $sql = "UPDATE `prodfarmacia` SET `Tipoproduto`=?,`Quantidade`=? ,`Ano`=?,`Preco`=?,`Mes`=?  WHERE `Codigo`=?";
            parent::beginTransaction();
            $stmt = parent::prepare($sql);
            $stmt->bindValue(1, $prod->getCodigo());
            $stmt->bindValue(2, $prod->getProdfarmacia()->getQuantidade());
            $stmt->bindValue(3, $prod->getProdfarmacia()->getAno());
            $stmt->bindValue(4, $prod->getProdfarmacia()->getPreco());
            $stmt->bindValue(5, $prod->getProdfarmacia()->getMes());
            $stmt->bindValue(6, $prod->getProdfarmacia()->getCodigo());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaprodfarmacia($anobase) {
        try {
            $stmt = parent::prepare("SELECT * FROM   `prodfarmacia` where `Ano` = :ano ");
            $stmt->execute(array(':ano' => $anobase));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function busca($codigo) {
        try {
            $stmt = parent::prepare("SELECT * FROM   `prodfarmacia` where `Codigo` = :codigo");
            $stmt->execute(array(':codigo' => $codigo));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscatipoproduto1($ano, $mes, $tipoproduto) {
        try {
            $sql = "SELECT p.`Nome`,pf.`Codigo`,pf.`Quantidade`,pf.`Preco`,pf.`Mes`" .
                    " FROM  `produto` p, `prodfarmacia` pf " .
                    " where  `Ano` = :ano and `Tipoproduto` = :tipoproduto and `Mes` = :mes" .
                    " and p.`Codigo`= pf.`Codigo` order by pf.`Mes`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':ano' => $ano, ':tipoproduto' => $tipoproduto, ':mes' => $mes));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscatipoproduto($ano, $tipoproduto) {
        try {
            $sql = "SELECT p.`Nome`,pf.`Codigo`,pf.`Tipoproduto`,pf.`Quantidade`,pf.`Preco`,pf.`Mes`" .
                    " FROM  `produto` p, `prodfarmacia` pf " .
                    " where  `Ano` = :ano and `Tipoproduto` = :tipoproduto " .
                    " and p.`Codigo`= pf.`Tipoproduto` order by pf.`Mes`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':ano' => $ano, ':tipoproduto' => $tipoproduto));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaprod($codigo) {
        try {
            $stmt = parent::prepare("SELECT * FROM   `prodfarmacia` where `Codigo` = :codigo ");
            $stmt->execute(array(':codigo' => $codigo));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function insere($prod) {
        try {
            $sql = "INSERT INTO `prodfarmacia` (`Tipoproduto`,`Quantidade`,`Ano`,`Preco`,`Mes`)  VALUES (?,?,?,?,?)";
            parent::beginTransaction();
            $stmt = parent::prepare($sql);
            $stmt->bindValue(1, $prod->getCodigo());
            $stmt->bindValue(2, $prod->getProdfarmacia()->getQuantidade());
            $stmt->bindValue(3, $prod->getProdfarmacia()->getAno());
            $stmt->bindValue(4, $prod->getProdfarmacia()->getPreco());
            $stmt->bindValue(5, $prod->getProdfarmacia()->getMes());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function fechar() {
        PDOConnectionFactory::Close();
    }

}

?>
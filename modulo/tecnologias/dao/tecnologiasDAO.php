<?php
class tecnologiasDAO extends PDOConnectionFactory {

    // irá receber uma conexão
    private $conex = null;

    public function deleta($tecnologias) {
        try {
            parent::beginTransaction();
            $stmt = parent::prepare("DELETE FROM `tecnologias` WHERE `Codigo`=?");
            $stmt->bindValue(1, $tecnologias->getCodigo());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscatecnologiasunidade($codunidade, $ano) {
        try {
            $stmt = parent::prepare("SELECT * FROM   `tecnologias` WHERE `codunidade` = :codunidade and `ano` = :ano ");
            $stmt->execute(array(':codunidade' => $codunidade, ':ano' => $ano));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function Insere($tecnologias) {
        try {
            parent::beginTransaction();

            $stmt = parent::prepare("INSERT INTO `tecnologias` (`codunidade`,`bandaL`,`salaC`,`videoc`,`salasA`,`micro`,`Ano`) VALUES (?,?,?,?,?,?,?)");
            $stmt->bindValue(1, $tecnologias->getUnidade()->getCodunidade());
            $stmt->bindValue(2, $tecnologias->getBandaL());
            $stmt->bindValue(3, $tecnologias->getSalaC());
            $stmt->bindValue(4, $tecnologias->getVideoc());
            $stmt->bindValue(5, $tecnologias->getSalasA());
            $stmt->bindValue(6, $tecnologias->getMicro());
            $stmt->bindValue(7, $tecnologias->getAno());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function buscatecnologias($codigo) {
        try {
            $stmt = parent::prepare("SELECT * FROM `tecnologias` WHERE `codigo` =:codigo ");
            $stmt->execute(array(':codigo' => $codigo));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscatecnologiasadmin($sqlp, $anoin, $anofim) {
        try {
            $sql = $sql = "SELECT u.`NomeUnidade` as Unidade, s.`NomeUnidade` as Subunidade,\n"
            		. " `bandaL`,\n"
            	    . " `salaC`,\n"
            	    . " `videoc`,\n"
            	    . " `micro`,\n"
            	    . " `salasA`,\n"
            	    . "  ano \n"
            	    . " FROM  \n"
            	    . " `unidade` u,\n"
            	    . " `unidade` s,\n"
            	    . " tecnologias t\n"
            	    . " WHERE \n"
            	    . " u.`unidade_responsavel`=1 \n"
            	    . " AND s.`unidade_responsavel`<>1 \n"
            	    . " AND s.`hierarquia_organizacional` like concat(u.`hierarquia_organizacional`,'%') \n"
            	    . " AND s.`CodUnidade`=t.`codunidade` AND t.`ano` >= :anoin AND t.`ano` <= :anofim\n"
            	    . " AND u.`NomeUnidade`<>'Reitoria'\n";
            
            
            $sql .= $sqlp;
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':anoin' => $anoin, ':anofim' => $anofim));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function altera($tecnologias) {
        try {
            $sql = "UPDATE `tecnologias` SET `bandaL`=?, `salaC`=?,`videoc`=?,`salasA`=?,`micro`=? WHERE `codigo`=?";
            parent::beginTransaction();
            $stmt = parent::prepare($sql);
            $stmt->bindValue(1, $tecnologias->getBandaL());
            $stmt->bindValue(2, $tecnologias->getSalaC());
            $stmt->bindValue(3, $tecnologias->getVideoc());
            $stmt->bindValue(4, $tecnologias->getSalasA());
            $stmt->bindValue(5, $tecnologias->getMicro());
            $stmt->bindValue(6, $tecnologias->getCodigo());
            //print $tecnologias->getCodigo();
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function fechar() {
        PDOConnectionFactory::Close();
    }

}

?>
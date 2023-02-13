<?php

class microsDAO extends PDOConnectionFactory {

    // irá receber uma conexão
    private $conex = null;

    // constructor
 /*     public function microsDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
    }
 */
    public function deleta($micros) {
        try {
            parent::beginTransaction();
            $stmt = parent::prepare("DELETE FROM `micros` WHERE `Codigo`=?");
            $stmt->bindValue(1, $micros->getCodigo());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscamicrosunidade($codunidade, $ano) {
        try {
            $stmt = parent::prepare("SELECT * FROM   `micros` where `CodUnidade` = :codunidade and `Ano` = :ano ");
            $stmt->execute(array(':codunidade' => $codunidade, ':ano' => $ano));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function Insere($micros) {
        try {
            parent::beginTransaction();

            $stmt = parent::prepare("INSERT INTO `micros` (`CodUnidade`,`QtdeAcadInt`,`QtdeAcad`,`QtdeAdm`,`QtdeAdmInt`,`Ano`) VALUES (?,?,?,?,?,?)");
            $stmt->bindValue(1, $micros->getUnidade()->getCodunidade());
            $stmt->bindValue(2, $micros->getAcadi());
            $stmt->bindValue(3, $micros->getAcad());
            $stmt->bindValue(4, $micros->getAdm());
            $stmt->bindValue(5, $micros->getAdmi());
            $stmt->bindValue(6, $micros->getAno());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function buscamicros($codigo) {
        try {
            $stmt = parent::prepare("SELECT * FROM `micros` WHERE `Codigo` =:codigo ");
            $stmt->execute(array(':codigo' => $codigo));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscamicrosadmin($sqlp, $anoin, $anofim) {
        try {
            $sql = "SELECT u.`NomeUnidade` as Unidade, s.`NomeUnidade` as Subunidade,\n"
            		. " `QtdeAcadInt` as `AcademicoInternet`,\n"
            	    . " `QtdeAcad` as `Academico`,\n"
            	    . " `QtdeAdmInt` as `AdministrativoInternet`,\n"
            	    . " `QtdeAdm` `Administrativo`,\n"
            	    . "Ano \n"
            	    . " FROM  \n"
            	    . " `unidade` u,\n"
            	    . " `unidade` s,\n"
            	    . " micros m\n"
            	    . " WHERE \n"
            	    . " u.`unidade_responsavel`=1 \n"
            	    . " AND s.`unidade_responsavel`<>1 \n"
            	    . " AND s.`hierarquia_organizacional` like concat(u.`hierarquia_organizacional`,'%') \n"
            	    . " AND s.`CodUnidade`=m.`CodUnidade` AND m.`Ano` >= :anoin AND m.`Ano` <= :anofim\n"
            	    . " AND u.`NomeUnidade`<>'Reitoria'\n";
            
            
            $sql .= $sqlp;
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':anoin' => $anoin, ':anofim' => $anofim));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function altera($micros) {
        try {
            $sql = "UPDATE `micros` SET `QtdeAcad`=?, `QtdeAcadInt`=?,`QtdeAdm`=?,`QtdeAdmInt`=? WHERE `Codigo`=?";
            parent::beginTransaction();
            $stmt = parent::prepare($sql);
            $stmt->bindValue(1, $micros->getAcad());
            $stmt->bindValue(2, $micros->getAcadi());
            $stmt->bindValue(3, $micros->getAdm());
            $stmt->bindValue(4, $micros->getAdmi());
            $stmt->bindValue(5, $micros->getCodigo());
            print $micros->getCodigo();
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
<?php

class TpacessibilidadeDAO extends PDOConnectionFactory {

    // ir� receber uma conex�o
    public $conex = null;

    // constructor
    public function Tpacessibilidade_DAO() {
        $this->conex = PDOConnectionFactory::getConnection();
    }

    public function tiponaoinserido($codunidade, $anobase) {
        try {
            $sql = "SELECT t1.`Codigo` , t1.`Nome` FROM `tdm_tipo_acessibilidade` t1 WHERE t1.`Codigo` NOT IN (" .
                    " SELECT t2.`Tipo` FROM `estrutura_acessibilidade` t2 WHERE t1.`Codigo` = t2.`Tipo` AND `CodUnidade` =:codunidade AND `Ano` =:anobase)";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codunidade' => $codunidade, ':anobase' => $anobase));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function Lista($parametro) {
        try {
            $sql = "SELECT * FROM tdm_tipo_acessibilidade where `categoria`=:ano order by `Codigo`";

            $stmt = parent::prepare($sql);
            $stmt->execute(array(':ano' => $parametro));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function Lista1() {
        try {
            $sql = "SELECT * FROM tdm_tipo_acessibilidade";
            $stmt = parent::query($sql);
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscatipoacessib($codatcess) {
        try {

            $stmt = parent::prepare("SELECT * FROM tdm_tipo_acessibilidade WHERE Codigo=:codigo");
            $stmt->execute(array(':codigo' => $codtacess));
            print($stmt);
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaestruturaacess($ano, $ano1, $codigo, $sqlparam) {
        try {
            $sql = "SELECT ea.`Quantidade`, tta.`Nome`, u.`NomeUnidade`, ea.`Tipo`, ea.`Ano`
			FROM  `estrutura_acessibilidade` ea,  `unidade` u, `tdm_tipo_acessibilidade` tta
			WHERE
			u.`CodUnidade` = ea.`CodUnidade`
			AND tta.`Codigo` = ea.`Tipo`";
            $sql .= " AND ((:ano <= ea.`Ano`) AND (ea.`Ano`<= :ano1))";
            if ($codigo != "todos")
                $sql .= " AND tta.`Codigo` = '$codigo'";
            $sql .= " " . $sqlparam;
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':ano' => $ano, ':ano1' => $ano1));
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

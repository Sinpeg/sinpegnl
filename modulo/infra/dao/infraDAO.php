<?php

class InfraDAO extends PDOConnectionFactory {

    // irá receber uma conexão

    // constructor
    /*
    public function InfraDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
    }
    */
    
    public function deleta($ti) {
        try {
            parent::beginTransaction();
            $stmt = parent::prepare("DELETE FROM `infraestrutura` WHERE `CodInfraestrutura`=?");
            $stmt->bindValue(1, $ti->getInfra()->getCodinfraestrutura());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function altera($ti) {
        try {
            $sql = "UPDATE `infraestrutura` SET `Nome`=?,`Sigla`=? ,`Horainicio`=?," .
                    " `Horafim`=?,`Adistancia`=?,`PCD`=?," .
                    " `Area`=?,`Capacidade`=?,`AnoDesativacao`=?,`Situacao`=?, " .
                    " `Tipo`=?  WHERE `CodInfraestrutura`=?";
            parent::beginTransaction();
            $stmt = parent::prepare($sql);
            $stmt->bindValue(1, $ti->getInfra()->getNome());
            $stmt->bindValue(2, $ti->getInfra()->getSigla());
            $stmt->bindValue(3, $ti->getInfra()->getHorainicio());
            $stmt->bindValue(4, $ti->getInfra()->getHorafim());
            $stmt->bindValue(5, $ti->getInfra()->getAdistancia());
            $stmt->bindValue(6, $ti->getInfra()->getPcd());
            $stmt->bindValue(7, $ti->getInfra()->getArea());
            $stmt->bindValue(8, $ti->getInfra()->getCapacidade());
            $stmt->bindValue(9, $ti->getInfra()->getAnodesativacao());
            $stmt->bindValue(10, $ti->getInfra()->getSituacao());
            $stmt->bindValue(11, $ti->getCodigo());
            $stmt->bindValue(12, $ti->getInfra()->getCodinfraestrutura());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function insere($ti) {
        try {
            $sql = "INSERT INTO `infraestrutura` (`CodUnidade`,`Tipo`,`AnoAtivacao`,`Nome`,`Sigla`,`HoraInicio`," .
                    "`HoraFim`,`Adistancia`,`PCD`,`Area`,`Capacidade`,`Situacao`)  VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
            parent::beginTransaction();
            $stmt = parent::prepare($sql);
            $stmt->bindValue(1, $ti->getInfra()->getUnidade()->getCodunidade());
            $stmt->bindValue(2, $ti->getCodigo());
            $stmt->bindValue(3, $ti->getInfra()->getAnoativacao());
            $stmt->bindValue(4, $ti->getInfra()->getNome());
            $stmt->bindValue(5, $ti->getInfra()->getSigla());
            $stmt->bindValue(6, $ti->getInfra()->getHorainicio());
            $stmt->bindValue(7, $ti->getInfra()->getHorafim());
            $stmt->bindValue(8, $ti->getInfra()->getAdistancia());
            $stmt->bindValue(9, $ti->getInfra()->getPcd());
            $stmt->bindValue(10, $ti->getInfra()->getArea());
            $stmt->bindValue(11, $ti->getInfra()->getCapacidade());
            $stmt->bindValue(12, $ti->getInfra()->getSituacao());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    /**
     * 
     * @param type $ano
     * @param type $agrupamento
     * @param type $campovalor
     * @param type $pcd
     * @param type $adistancia
     * @param type $situacao
     * @param type $tipo
     */
    public function buscaInfraGrafico($ano, $agrupamento, $campovalor, $txtUnidade, $pcd, $modalidade, $situacao, $tipo) {
        try {
            $sql = ""; // sql
            $where = ""; // where
            $groupby = ""; // agrupamento
            $orderby = ""; // ordenar
            // palavras-chave
            $keywords = array("INSTITUTO", "CAMPUS", "ESCOLA", "HOSPITAL");

            /* configuração inicial da clausula from */
            $from = "`tdm_tipo_infraestrutura` tti," .
                    "`infraestrutura` i, " .
                    "`unidade` u";

            /* Configurações padrões para o where */
            $where .= "\nu.`CodUnidade` = i.`CodUnidade`";
            $where .= "\nAND tti.`Codigo` = i.`Tipo`";

            // se está no array Instituto, Campus, Escola ou Hospital
            // inserir na consulta
            if (in_array($agrupamento, $keywords)) {
                $sql .= "u.`NomeUnidade` as `por " . strtolower($agrupamento) . "`";
                $where .= "\nAND u.`NomeUnidade` LIKE '%$agrupamento%'";
                $groupby .= " u.`NomeUnidade`";
            } else { // outros tipos de agrupamentos
                switch ($agrupamento) {
                    case 'PCD':
                        $sql .= ", CASE i.`PCD`"
                                . " WHEN 'S' THEN 'Sim' "
                                . " WHEN 'N' THEN 'Não' "
                                . " END as `por PCD`";
                        $groupby .= " i.`PCD`";
                        break;
                    case 'MODALIDADE':
                        $sql .= ", CASE i.`Adistancia`"
                                . " WHEN '1' THEN 'Presencial'"
                                . " WHEN '2' THEN 'À distância'"
                                . " WHEN '3' THEN 'Presencial e à distância'"
                                . " END as `por modalidade de ensino`";
                        $groupby .= " i.`Adistancia`";
                        break;
                    case 'SITUACAO':
                        $sql .= ", CASE i.`Situacao`"
                                . " WHEN 'A' TH EN 'Ativo' "
                                . " WHEN 'D' THEN 'Desativado' "
                                . " END as `Situacao` ";
                        $groupby .= " i.`Situacao`";
                        break;
                    case 'TIPO':
                        $sql .= ",tti.`Nome` as `por tipo de infraestrutura`"; // título
                        $groupby .= " i.`Nome`";
                        break;
                }
            }
            // campo para valor
            switch ($campovalor) {
                case "1": // capacidade
                    $sql .= ", sum(`Capacidade`) as `Soma da capacidade`"; // título
                    $orderby .= " sum(`Capacidade`)";
                    break;
                case "2": // área
                    $sql .= ", sum(`Area`) as `Soma da área`";
                    $orderby .= " sum(`Area`)";
                    break;
                case "3": // número de infraestruturas
                    $sql .= ", count(*) as `Total de infraestruturas`"; // título
                    $orderby .= " count(*)";
                    break;
            }
            // condições
            if (!empty($txtUnidade)) { // nome da unidade
                $where.= " AND u.`NomeUnidade` = '$txtUnidade'";
            }
            if (!empty($pcd) && $pcd != "0") { // seleciona o PCD
                $where.= " AND i.`PCD` = '$pcd'";
            }
            if (!empty($modalidade) && $modalidade != "0") { // seleciona a modalidade
                $where.= " AND i.`Adistancia` = $modalidade";
            }
            if (!empty($situacao) && $situacao != "0") { // seleciona a situação
                $where.= " AND i.`Situacao` = '$situacao'";
                if ($situacao == "A") {
                    $where .= " AND (i.`AnoAtivacao` <=" . addslashes($ano);
                    $where .= " and (i.`AnoDesativacao` >= " . addslashes($ano);
                    $where .= " or i.`AnoDesativacao` is null))";
                } else {
                    $where .= " AND (i.`AnoAtivacao` <= " . addslashes($ano);
                    $where .= " and i.`AnoDesativacao` <= " . addslashes($ano) . ")";
                }
            }
            if (!empty($tipo) && $tipo != "0") { // seleciona o tipo da infraestrutura
                $where.= " AND i.`Tipo` = '$tipo'";
            }

            /* realiza a verificação dos campos selecionados */
            if ($sql[0] == ",") {
                $sql = substr($sql, 1);
            }
//            echo "SELECT $sql FROM $from WHERE $where GROUP BY $groupby ORDER BY $orderby";
            $stmt = parent::query("SELECT $sql FROM $from WHERE $where GROUP BY $groupby ORDER BY $orderby");
            return $stmt;
        } catch (Exception $ex) {
            
        }
    }

    public function buscainfraunidade($codunidade) {
        try {
            $stmt = parent::prepare("SELECT * FROM `infraestrutura` where `CodUnidade` = :codunidade");
            $stmt->execute(array(':codunidade' => $codunidade));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function buscainfra($codinfraestrutura) {
        try {
            $stmt = parent::prepare("SELECT * FROM   `infraestrutura` where `CodInfraestrutura` = :codinfraestrutura ");
            $stmt->execute(array(':codinfraestrutura' => $codinfraestrutura));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function contaInfraTipo($codunidade) {
        try {
            $sql = "SELECT COUNT(*) as total
			FROM  `infraestrutura` i, `unidade` u,  `tdm_tipo_infraestrutura` tdm
			WHERE i.`CodUnidade` = u.`CodUnidade`
			AND i.`CodUnidade` = :codigo
			AND i.`Tipo` = tdm.`Codigo`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codunidade));
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function buscaInfraTipo($sqlparam, $codunidade) {
        try {
            $sql = "SELECT tdm.`Nome` as `NomeTipo`, i.`Nome` as `NomeInfra`,
			`CodInfraestrutura`
			FROM  `infraestrutura` i, `unidade` u,  `tdm_tipo_infraestrutura` tdm
			WHERE i.`CodUnidade` = u.`CodUnidade`
			AND i.`CodUnidade` = :codigo
			AND i.`Tipo` = tdm.`Codigo`";
            $sql .= " " . $sqlparam;
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codunidade));
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    /**
     *
     * @param unknown_type $sql_param
     * @param unknown_type $ano
     * @param unknown_type $situacao
     * @return unknown
     */
    public function buscaInfraAdmin($ano, $situacao, $sql_param) {
        try {
            $sql = "SELECT u.`NomeUnidade`, u.`CodUnidade`, t.`Nome` as `NomeTipo`, `CodInfraestrutura`, i.`Nome` as `NomeInfra`,
			i.`HoraInicio`, i.`HoraFim`,
			CASE  `Adistancia`
			WHEN 1 THEN  'presencial'
			WHEN 2 THEN  'à distância'
			WHEN 3 THEN  'ambos' END AS FORMA,
			CASE  `PCD`
			WHEN  'S' THEN  'sim'
			WHEN  'N' THEN  'não'
			END AS  'PCD',  `Area` , `Capacidade`
			FROM  `tdm_tipo_infraestrutura` t,  `infraestrutura` i,  `unidade` u
			WHERE i.`CodUnidade` = u.`CodUnidade`
			AND t.`Codigo` = i.`Tipo`";
            $sql .= " " . $sql_param;
            switch ($situacao) {
                case 'A':
                    $sql .= " AND (i.`Situacao` =  'A' AND ( i.`AnoAtivacao` <= :ano) AND (i.`AnoDesativacao` IS NULL))";
                    break;
                case 'D':
                    $sql .= " AND (i.`AnoDesativacao`>= :ano)";
                    break;
            }
            $sql .= " ORDER BY u.`NomeUnidade`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':ano' => $ano));
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    /**
     *
     * @param unknown_type $codunidade
     */
    public function buscaInfraPDF($codunidade) {
        try {
            $sql = "SELECT t.`Nome` AS  `NomeTipo`,  i.`Nome` AS `NomeInfra`, `CodInfraestrutura` , i.`HoraInicio` , i.`HoraFim` ,
			CASE  `Adistancia`
			WHEN 1 THEN  'presencial'
			WHEN 2 THEN  'à distância'
			WHEN 3 THEN  'ambos' END AS FORMA,
			CASE  `PCD`
			WHEN  'S' THEN  'sim'
			WHEN  'N' THEN  'não'
			END AS  'PCD',  `Area` ,  `Capacidade`
			FROM  `tdm_tipo_infraestrutura` t,  `infraestrutura` i,  `unidade` u
			WHERE i.`CodUnidade` = u.`CodUnidade`
			AND t.`Codigo` = i.`Tipo`
			AND u.`CodUnidade` = :codigo
			ORDER BY t.`Nome`, i.`Nome`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codunidade));

            return $stmt;
        } catch (PDOException $ex) {
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

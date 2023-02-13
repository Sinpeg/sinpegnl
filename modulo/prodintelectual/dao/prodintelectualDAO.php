<?php

class prodintelectualDAO extends PDOConnectionFactory {
    // receber uma conexão
    private $conex = null;
    // constructor
  /*  public function prodintelectualDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
    }
  */
    public function deleta($pi) {
        try {
            parent::beginTransaction();
            $stmt = parent::prepare("DELETE FROM `prodintelectual` WHERE `Codigo`=?");
            $stmt->bindValue(1, $pi->getCodigo());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            //echo "Erro: ".$ex->getMessage();
            parent::rollback();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function tiponaoinserido($codunidade, $anobase,$validade) {
        try {
            $sql = "SELECT t1.`Codigo`, t1.`Nome` "
                    . " FROM `tdm_prodintelectual` t1"
                    . " WHERE t1.`Codigo` NOT IN (SELECT t2.`Tipo`"
                    . " FROM `prodintelectual` t2 WHERE t1.`Codigo` = t2.`Tipo` "
                    . " AND t2.`codunidade` =:coduni AND t2.`Ano` =:anobase ) AND t1.`Validade`=:val12 "
                    . " ORDER BY t1.`Codigo`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':coduni' => $codunidade, ':anobase' => $anobase,':val12' => $validade));
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function buscapiunidade($codunidade, $ano) {
        try {
            $stmt = parent::prepare("SELECT `Codigo`,`CodCurso`,`Tipo`,`Quantidade` FROM `prodintelectual` where `codunidade` = :codunidade and `Ano` = :ano order by `Tipo`");
            $stmt->execute(array(':codunidade' => $codunidade, ':ano' => $ano));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            //echo "Erro: ".$ex->getMessage();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function buscapi($codunidade, $ano, $tipopi) {
        try {
            $sql = "SELECT * FROM  `prodintelectual` where `codunidade`=:coduni and `Ano` =:ano and `Tipo`=:tipopi";
            $stmt = parent::prepare($sql);
            //print_r($stmt);
            $stmt->execute(array(':coduni' => $codunidade, ':ano' => $ano, ':tipopi' => $tipopi));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            //echo "Erro: ".$ex->getMessage();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function lista($codcurso, $ano, $tipopi) {
    	try {
    		$sql = "SELECT * FROM  `prodintelectual` where `CodCurso`=:codcurso and `Ano` =:ano and `Tipo`=:tipopi";
    		$stmt = parent::prepare($sql);
    		//print_r($stmt);
    		$stmt->execute(array(':codcurso' => $codcurso, ':ano' => $ano, ':tipopi' => $tipopi));
    		// retorna o resultado da query
    		return $stmt;
    	} catch (PDOException $ex) {
    		//echo "Erro: ".$ex->getMessage();
    		$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
    		$cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
    		header($cadeia);
    	}
    }
    
    public function inseretodos($pis) {
        try {
            parent::beginTransaction();
            foreach ($pis as $pi) {
                $sql = "INSERT INTO `prodintelectual` (`codunidade`,`Tipo`,`Quantidade`,`Ano`)  VALUES (?,?,?,?)";
                $stmt = parent::prepare($sql);
                $stmt->bindValue(1, $pi->getProdintelectual()->getunidade()->getCodunidade());
                $stmt->bindValue(2, $pi->getCodigo());
                $stmt->bindValue(3, $pi->getProdintelectual()->getQuantidade());
                $stmt->bindValue(4, $pi->getProdintelectual()->getAno());
                $stmt->execute();
            }
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function alteratodos($pis) {
        try {
            parent::beginTransaction();
            foreach ($pis as $pi) {
                $sql = "UPDATE `prodintelectual` SET `Quantidade`=? WHERE `Codigo`=?";
                $stmt = parent::prepare($sql);
                $stmt->bindValue(1, $pi->getProdintelectual()->getQuantidade());
                $stmt->bindValue(2, $pi->getProdintelectual()->getCodigo());
                $stmt->execute();
            }
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }
    
    //Exporta o relatório de produção intelectual para o user ADMIN
    public  function  producaoAdm($ano_base){
        try {
            $sql = "SELECT CASE WHEN tp.Anuario = 'A' THEN 'Artística'
					WHEN tp.Anuario = 'B' THEN 'Bibliográfica'
					WHEN tp.Anuario = 'O' THEN 'Bibliográfica'
					WHEN tp.Anuario = 'T' THEN 'Técnica'
					END AS tipo, tp.Nome, SUM( p.Quantidade ) AS quant FROM prodintelectual AS p JOIN tdm_prodintelectual AS tp ON ( p.Tipo = tp.Codigo )
					WHERE p.Ano =:ano_base
					AND tp.Validade =2014
					GROUP BY tp.Anuario, tp.Nome";
            
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':ano_base'=>$ano_base));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }
    
    //Exporta o relatório de produção intelectual para unidade
    public  function  producaoUni($ano_base,$codUnidade){
        try {
            $sql = "SELECT CASE WHEN tp.Anuario = 'A' THEN 'Artística'
					WHEN tp.Anuario = 'B' THEN 'Bibliográfica'
					WHEN tp.Anuario = 'O' THEN 'Bibliográfica'
					WHEN tp.Anuario = 'T' THEN 'Técnica'
					END AS tipo, tp.Nome, SUM( p.Quantidade ) AS quant FROM prodintelectual AS p JOIN tdm_prodintelectual AS tp ON ( p.Tipo = tp.Codigo ) INNER JOIN curso AS c ON p.CodCurso=c.CodCurso
					WHERE p.Ano =:ano_base
					AND c.CodUnidade=:unidade
					AND tp.Validade =2014
					GROUP BY tp.Anuario, tp.Nome";
            
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':ano_base'=>$ano_base,':unidade'=>$codUnidade));
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
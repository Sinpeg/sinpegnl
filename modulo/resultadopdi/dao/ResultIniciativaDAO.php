<?php
class ResultIniciativaDAO extends PDOConnectionFactory {
	
	public $conex = null;
    
    
    public function resultadoporIni($codigo,$codcalendario) {
        $sql=" SELECT * FROM `resultIniciativa` WHERE `codIniciativa`=:codigo and codcalendario=:codcal";
		try {
            $stmt = parent::prepare($sql);
    		$stmt->execute(array(':codigo' => $codigo,':codcal' => $codcalendario));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro resultadoporIni: " . $ex->getMessage();
		}
	}
	
	
  public function iniciativaPorResultado($codigo, $anogestao,$periodo) {
        $sql=" SELECT * FROM `resultIniciativa` r inner join calendario c on c.codCalendario=r.codCalendario WHERE `codIniciativa`=:micodigo and  anogestao=:ano and periodo=:p";
		try {
            $stmt = parent::prepare($sql);
    		$stmt->execute(array(':micodigo' => $codigo,':ano' => $anogestao,':p'=>$periodo));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro iniciativaPorResultado: " . $ex->getMessage();
		}
	}
 public function iniciativaPorResultado1($codigoinic, $anogestao,$periodo) {
        $sql=" SELECT distinct r.`codResultIniciativa`, r.`codCalendario`, r.`codIniciativa`, r.`situacao`, r.`pfcapacit`,".
       " r. `pfrecti`, r.`pfinfraf`, r.`pfrecf`, r.`pfplanj`, r.`outros`, r.`periodo` FROM iniciativa i ".
       " inner join `indic_iniciativa` ii on i.codIniciativa=ii.codIniciativa".
       " inner join `resultIniciativa` r on r.codIniciativa=i.codIniciativa".
       " inner join calendario c on c.codCalendario=r.codCalendario WHERE i.codiniciativa=:inic  ".
       " and  anogestao=:ano and periodo=:p limit 1";
		try {
            $stmt = parent::prepare($sql);
    		$stmt->execute(array(':inic' => $codigoinic,':ano' => $anogestao,':p'=>$periodo));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro iniciativaPorResultado1: " . $ex->getMessage();
		}
	}
    
      public function insere($objiniresult) {
        try {
            $query = "INSERT INTO `resultIniciativa` (`codCalendario`, `codIniciativa`, `situacao`, `pfcapacit`, `pfrecti`, `pfinfraf`, `pfrecf`, `pfplanj`,`outros`, `periodo`) VALUES (?,?,?,?,?,?,?,?,?,?)";
            $stmt = parent::prepare($query);
            parent::beginTransaction();
            $stmt->bindValue(1,$objiniresult->getCalendario()->getCodigo());
            $stmt->bindValue(2, $objiniresult->getIniciativa()->getCodIniciativa());
            $stmt->bindValue(3, $objiniresult->getSituacao());
            $stmt->bindValue(4, $objiniresult->getPfcapacit());
            $stmt->bindValue(5, $objiniresult->getPfrecti());
            $stmt->bindValue(6, $objiniresult->getPfinfraf());
            $stmt->bindValue(7, $objiniresult->getPfrecf());
            $stmt->bindValue(8, $objiniresult->getPfplanj());
            $stmt->bindValue(9, $objiniresult->getOutros());
            $stmt->bindValue(10, $objiniresult->getPeriodo());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            print "Erro: insere resultiniciativa" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }

    /**
     * 
     * @param Resultado $resultado
     */
    public function altera($objiniresult) {
        try {
            $stmt = parent::prepare("UPDATE `resultIniciativa` SET `codCalendario`=?,`codIniciativa`=?,`situacao`=?,`pfcapacit`=?,`pfrecti`=?,`pfinfraf`=?,`pfrecf`=?,`pfplanj`=?,`periodo`=?,`outros`=? WHERE  `codResultIniciativa`=?");
            parent::beginTransaction();
          
            $stmt->bindValue(1,$objiniresult->getCalendario()->getCodigo());
            $stmt->bindValue(2, $objiniresult->getIniciativa()->getCodIniciativa());
            $stmt->bindValue(3, $objiniresult->getSituacao());
            $stmt->bindValue(4, $objiniresult->getPfcapacit());
            $stmt->bindValue(5, $objiniresult->getPfrecti());
            $stmt->bindValue(6, $objiniresult->getPfinfraf());
            $stmt->bindValue(7, $objiniresult->getPfrecf());
            $stmt->bindValue(8, $objiniresult->getPfplanj());
            $stmt->bindValue(9, $objiniresult->getPeriodo());
            $stmt->bindValue(10, $objiniresult->getOutros());
            $stmt->bindValue(11, $objiniresult->getCodigo());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            print "Erro: altera resultiniciativa " . $ex->getCode() . " " . $ex->getMessage();
        }
    }

    /**
     * 
     * @param type $codigo
     */
    public function deleta($codigo) {
        try {
            parent::beginTransaction();
            $stmt = parent::prepare("DELETE FROM `resultIniciativa` WHERE `codResultIniciativa`=?");
            $stmt->bindValue(1, $codigo);
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            print "Erro: delete:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }

}

?>
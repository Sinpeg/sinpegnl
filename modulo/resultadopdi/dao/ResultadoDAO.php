<?php
class ResultadoDAO extends PDOConnectionFactory {

    public $conex = null;
/*
    public function ResultadoDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
    }
*/
    /**
     * 
     * @param Resultado $resultado
     */
    public function insere(Resultado $resultado) {
        try {
            $query = "INSERT INTO `resultados_pdi` (`CodMeta`, `meta_atingida`, `periodo`, `analiseCritica`,`acaoPdi`) VALUES (?,?,?,?,?)";
            $stmt = parent::prepare($query);
            parent::beginTransaction();
           //echo "passou1".$resultado->getMeta()->getCodigo().",".$resultado->getPeriodo().",".$resultado->getMetaAtingida();die;  
            
            $stmt->bindValue(1, $resultado->getMeta()->getCodigo());
            $stmt->bindValue(2, $resultado->getMetaAtingida());
            $stmt->bindValue(3, $resultado->getPeriodo());
            $stmt->bindValue(4, $resultado->getAnaliseCritica());
            $stmt->bindValue(5, $resultado->getAcao());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            print "Erro: insere resuklt:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
            die;
        }
    }

    /**
     * 
     * @param Resultado $resultado
     */
    public function altera(Resultado $resultado) {
        try {
            $stmt = parent::prepare("UPDATE `resultados_pdi` SET `CodMeta`=?, `meta_atingida`=?, `periodo`=?, `analiseCritica`=? ,
             `acaoPdi`=?  WHERE `Codigo`=?");
            parent::beginTransaction();
            
            $stmt->bindValue(1, $resultado->getMeta()->getCodigo());
            $stmt->bindValue(2, $resultado->getMetaAtingida());
            $stmt->bindValue(3, $resultado->getPeriodo());
            $stmt->bindValue(4, $resultado->getAnaliseCritica());
            $stmt->bindValue(5, $resultado->getAcao());
            $stmt->bindValue(6, $resultado->getCodigo());
            $stmt->execute();
            parent::commit();
            
        } catch (PDOException $ex) {
            print "Erro: altera " . $ex->getCode() . " " . $ex->getMessage();die;
        }
    }


    public function deleta($codigo) {
        try {
            parent::beginTransaction();
            $stmt = parent::prepare("DELETE FROM `resultados_pdi` WHERE `Codigo`=?");
            $stmt->bindValue(1, $codigo);
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            print "Erro: delete:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }

    /**
     * 
     * @return type
     */
    public function lista() {
        try {
            $stmt = parent::query('select * FROM `resultados_pdi`');
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro lista: " . $ex->getMessage();
        }
    }

    //Busca resultado de meta independente do ano e periodo
    public function buscaresultadometa($codigo) {
        try {
            $sql = "SELECT * FROM `resultados_pdi` WHERE `CodMeta`= :codigo";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaresultadometa: " . $ex->getMessage();
        }
    }

    public function buscaresultado($codigo) {
        try {
            $sql = "SELECT * FROM `resultados_pdi` WHERE `Codigo`= :codigo";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaresultado: " . $ex->getMessage();
        }
    }

    public function buscaresultaperiodometa($codmeta, $periodo) {
        try {
            $sql = "SELECT * FROM `resultados_pdi` WHERE `periodo`= :periodo
                AND `CodMeta` = :codmeta";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':periodo' => $periodo, ':codmeta' => $codmeta));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaresultaperiodometa: " . $ex->getMessage();
        }
    }
    
    public function buscaresultadometa1($codmeta, $periodo) {
        try {
            $sql = "SELECT * FROM `resultados_pdi` WHERE `CodMeta`= :codigo
                AND `periodo`= :periodo";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':periodo' => $periodo, ':codigo' => $codmeta));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaresultadometa1: " . $ex->getMessage();
        }
    }
    
    public function buscaResultMapaindAno($codMapaind, $ano) {
    	try {
    		$sql = "SELECT * FROM `meta` AS m INNER JOIN resultados_pdi AS r ON r.CodMeta =m.Codigo WHERE `CodMapaInd`= :mapaind  and `ano` = :ano";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':mapaind' => $codMapaind, ':ano' => $ano));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscar: " . $ex->getMessage();
    	}
    }
    
    //Buscar resultados por mapa indicador independente do ano
    public function buscaResultMapaind($codMapaind) {
    	try {
    		$sql = "SELECT * FROM `meta` AS m INNER JOIN resultados_pdi AS r ON r.CodMeta =m.Codigo WHERE `CodMapaInd`= :mapaind ";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':mapaind' => $codMapaind));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscar: " . $ex->getMessage();
    	}
    } 

    
    public function buscaResultMapaAno($codmapa, $ano) {
    	try {
    		$sql = "SELECT * FROM mapaindicador mi inner join `meta` AS m on mi.codigo= m.`CodMapaInd`
INNER JOIN resultados_pdi AS r ON r.CodMeta =m.Codigo WHERE codmapa= :mapa  and `ano` = :ano";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':mapa' => $codmapa, ':ano' => $ano));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscar: " . $ex->getMessage();
    	}
    }
    
    /**
     * 
     */
    
    
    public function verResultados($coddoc,$codunidade) {
	    try {
	    	if ($codunidade==938){
	    	$sql="select  d1.codigo
from documento d1 
join `mapa` m1 on m1.`CodDocumento`=d1.`codigo` 
join `mapaindicador` mi1 on m1.`Codigo`=mi1.`codMapa` 
join indicador i1 on mi1.codIndicador=i1.codigo and mi1.codindicador=i1.codigo 
join `meta` a1 on a1.`CodMapaInd`=mi1.`codigo` 
join `resultados_pdi` r1 on r1.`CodMeta`=a1. `Codigo` 
where  d1.codigo =:doc   and mi1.tipoassociado=7 
";
	    	$stmt = parent::prepare($sql);
	    	$stmt->execute(array(':doc' => $coddoc));
	    	
	    	}else{
    		$sql = "select d1.codigo
from documento d1 
join `mapa` m1 on m1.`CodDocumento`=d1.`codigo` 
join `mapaindicador` mi1 on m1.`Codigo`=mi1.`codMapa` 
join indicador i1 on mi1.codIndicador=i1.codigo and mi1.codindicador=i1.codigo 
join `meta` a1 on a1.`CodMapaInd`=mi1.`codigo` 
join `resultados_pdi` r1 on r1.`CodMeta`=a1. `Codigo` 
where d1.codunidade=:unid or (d1.codigo =:doc and (mi1.propindicador=:unid and 
(mi1.tipoassociado=7 or mi1.tipoAssociado is null or mi1.tipoAssociado='PDU')) )
 ";
    		
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':doc' => $coddoc,':unid'=>$codunidade));
    		
	    	}
    		
    		return $stmt;
    	} catch (PDOException $ex) {
    		print "Erro na consulta verResultados: " . $ex->getCode() . " " . $ex->getMessage();
    	}
    }
    
    
    public function verResultadosAno($coddoc,$codunidade,$anogestao) {
	    try {
	    	if ($codunidade==938){
	    	$sql="select  d1.codigo
from documento d1 
join `mapa` m1 on m1.`CodDocumento`=d1.`codigo` 
join `mapaindicador` mi1 on m1.`Codigo`=mi1.`codMapa` 
join indicador i1 on mi1.codIndicador=i1.codigo and mi1.codindicador=i1.codigo 
join `meta` a1 on a1.`CodMapaInd`=mi1.`codigo` and ano=:ano
join `resultados_pdi` r1 on r1.`CodMeta`=a1.`Codigo` 
where  d1.codigo =:doc  and d1.anoinicial<=:ano and  d1.anofinal>=:ano   and mi1.tipoassociado=7 
";
	    	$stmt = parent::prepare($sql);
	    	$stmt->execute(array(':doc' => $coddoc));
	    	
	    	}else{
    		$sql = "select d1.codigo
from documento d1 
join `mapa` m1 on m1.`CodDocumento`=d1.`codigo` 
join `mapaindicador` mi1 on m1.`Codigo`=mi1.`codMapa` 
join indicador i1 on mi1.codIndicador=i1.codigo and mi1.codindicador=i1.codigo 
join `meta` a1 on a1.`CodMapaInd`=mi1.`codigo` and ano=:ano
join `resultados_pdi` r1 on r1.`CodMeta`=a1. `Codigo` 
where d1.codunidade=938 and d1.anoinicial<=:ano and  d1.anofinal>=:ano 
or (d1.codunidade =:unid and d1.anoinicial<=:ano 
and  d1.anofinal>=:ano and (mi1.propindicador=:unid and 
(mi1.tipoassociado=7 )) )
 ";
    		
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':doc' => $coddoc,':unid'=>$codunidade,':ano'=>$anogestao));
    		
	    	}
    		
    		return $stmt;
    	} catch (PDOException $ex) {
    		print "Erro na consulta verResultados: " . $ex->getCode() . " " . $ex->getMessage();
    	}
    }
    
    public function verResultadosAnosAnteriores($codunidade,$anoinicial,$anogestao,$coddoc) {
	    try {
	    	if ($codunidade==938){
	    	$sql="select  d1.codigo
from documento d1 
join `mapa` m1 on m1.`CodDocumento`=d1.`codigo` 
join `mapaindicador` mi1 on m1.`Codigo`=mi1.`codMapa` 
join indicador i1 on mi1.codIndicador=i1.codigo and mi1.codindicador=i1.codigo 
join `meta` a1 on a1.`CodMapaInd`=mi1.`codigo` and (ano>=:ano and ano<:ano)
join `resultados_pdi` r1 on r1.`CodMeta`=a1.`Codigo` 
where  d1.codunidade =938   and mi1.tipoassociado=7 and d1.anoinicial<=:ano and  d1.anofinal>=:ano
";
	    	$stmt = parent::prepare($sql);
	    	$stmt->execute(array(':anoini'=>$anoinicial,':ano'=>$anogestao));
	    	
	    	}else{
    		$sql = "select d1.codigo
from documento d1 
join `mapa` m1 on m1.`CodDocumento`=d1.`codigo` 
join `mapaindicador` mi1 on m1.`Codigo`=mi1.`codMapa` 
join indicador i1 on mi1.codIndicador=i1.codigo and mi1.codindicador=i1.codigo 
join `meta` a1 on a1.`CodMapaInd`=mi1.`codigo` and (ano>=:ano and ano<:ano)
join `resultados_pdi` r1 on r1.`CodMeta`=a1. `Codigo` 
where (d1.codunidade=:unid and d1.anoinicial<=:ano and  d1.anofinal>=:ano  and d1.codigo=:coddoc) or 
(d1.codunidade =938 and d1.anoinicial<=:ano and  d1.anofinal>=:ano 
and (mi1.propindicador=:unid and 
(mi1.tipoassociado=7 or mi1.tipoAssociado is null or mi1.tipoAssociado='PDU')) )
 ";
    		
    		$stmt = parent::prepare($sql);
    		 
    		$stmt->execute(array(':unid'=>$codunidade,':anoini'=>$anoinicial,':ano'=>$anogestao,':coddoc'=>$coddoc));
    		
	    	}
    		
    		return $stmt;
    	} catch (PDOException $ex) {
    		print "Erro na consulta verResultadosAnosAnteriores: " . $ex->getCode() . " " . $ex->getMessage();
    	}
    }
    public function verResultadosAnoPeriodoFinal($codunidade,$anogestao,$coddoc) {
	    try {//especificamente o periodo deve ser o final
	    	if ($codunidade==938){
	    	$sql="select  d1.codigo
from documento d1 
join `mapa` m1 on m1.`CodDocumento`=d1.`codigo` 
join `mapaindicador` mi1 on m1.`Codigo`=mi1.`codMapa` 
join indicador i1 on mi1.codIndicador=i1.codigo and mi1.codindicador=i1.codigo 
join `meta` a1 on a1.`CodMapaInd`=mi1.`codigo` and ano=:ano
join `resultados_pdi` r1 on r1.`CodMeta`=a1.`Codigo` and r1.periodo=2
where  d1.codigo =938   and mi1.tipoassociado=7 d1.anoinicial<=:ano and  d1.anofinal>=:ano
";
	    	$stmt = parent::prepare($sql);
	    	$stmt->execute(array(':ano'=>$anogestao));
	    	
	    	}else{
    		$sql = "select d1.codigo
from documento d1 
join `mapa` m1 on m1.`CodDocumento`=d1.`codigo` 
join `mapaindicador` mi1 on m1.`Codigo`=mi1.`codMapa` 
join indicador i1 on mi1.codIndicador=i1.codigo and mi1.codindicador=i1.codigo 
join `meta` a1 on a1.`CodMapaInd`=mi1.`codigo` and  ano=:ano
join `resultados_pdi` r1 on r1.`CodMeta`=a1. `Codigo` and r1.periodo=2
where d1.codunidade=:unid AND d1.anoinicial<=:ano and  d1.anofinal>=:ano
or (d1.codunidade =938 and (mi1.propindicador=:unid and 
(mi1.tipoassociado=7 or mi1.tipoAssociado is null or mi1.tipoAssociado='PDU')
 and d1.anoinicial<=:ano and  d1.anofinal>=:ano))
 ";
    		
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':unid'=>$codunidade,':ano'=>$anogestao));
    		
	    	}
    		
    		return $stmt;
    	} catch (PDOException $ex) {
    		print "Erro na consulta verResultadosAnoPeriodoFinal: " . $ex->getCode() . " " . $ex->getMessage();
    	}
    }
    public function fechar() {
        PDOConnectionFactory::Close();
    }

}

?>

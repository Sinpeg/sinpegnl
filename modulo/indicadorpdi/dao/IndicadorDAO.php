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
            $sql = "INSERT INTO `indicador` (`nome`, `objeto` ," .
                    "`calculo`, `validade`,`unidadeMedida`, `peso`, `interpretacao` ," .
                    " `metodo`, `observacoes`, `benchmarch`,`cesta`,`codunidade`,`codversao`,`codEixo`,`anoinicio`) 
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = parent::prepare($sql);
            parent::beginTransaction();        
            $stmt->bindValue(1, $indicador->getNome());
            $stmt->bindValue(2, $indicador->getObjeto());
            $stmt->bindValue(3, $indicador->getCalculo());
            $stmt->bindValue(4, $indicador->getValidade());
            $stmt->bindValue(5, $indicador->getUnidademedida());
            $stmt->bindValue(6, $indicador->getPeso());
            $stmt->bindValue(7, $indicador->getInterpretacao());
            $stmt->bindValue(8, $indicador->getMetodo());
            $stmt->bindValue(9, $indicador->getObservacoes());
            $stmt->bindValue(10,$indicador->getBenchmarch()); 
            $stmt->bindValue(11,$indicador->getCesta());  
            $stmt->bindValue(12,$indicador->getUnidade()->getCodunidade());
            $stmt->bindValue(13,$indicador->getCodversao());
            $stmt->bindValue(14,$indicador->getEixo());
            $stmt->bindValue(15,$indicador->getAnoinicio());
            $stmt->execute();
            $id= parent::lastInsertId();
            parent::commit();
            return  $id;
        } catch (PDOException $ex) {
            parent::rollback();
            print "Erro insere novo indicador: " . $ex->getCode() . "Mensagem" . $ex->getMessage();
            return 0;
        }
    }

 public function altera(Indicador $indicador) {
        try {
            $sql = "UPDATE `indicador` SET `nome`=?,`objeto`=?,`calculo`=?,`validade`=?," . 
            	   "`unidadeMedida`=?, `peso`=?, `interpretacao`=?, `metodo`=?, `observacoes`=?,`cesta`=?, `benchmarch`=?, codunidade=? " .
                   "WHERE `Codigo`=?";
            $stmt = parent::prepare($sql);
            parent::beginTransaction();   
            
            $stmt->bindValue(1, $indicador->getNome());
            $stmt->bindValue(2, $indicador->getObjeto());
            $stmt->bindValue(3, $indicador->getCalculo());
            $stmt->bindValue(4, $indicador->getValidade());
            $stmt->bindValue(5, $indicador->getUnidademedida());
            $stmt->bindValue(6, $indicador->getPeso());
            $stmt->bindValue(7, $indicador->getInterpretacao());
            $stmt->bindValue(8, $indicador->getMetodo());
            $stmt->bindValue(9,$indicador->getObservacoes());
            $stmt->bindValue(10,$indicador->getCesta());               
            $stmt->bindValue(11,$indicador->getBenchmarch()); 
            $stmt->bindValue(12,$indicador->getUnidade()->getCodunidade());  
            $stmt->bindValue(13, $indicador->getCodigo());          
          
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            print "Erro em altera Indicador:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
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


    
    
    
    public function listaNCesta() {
        try {
            $stmt = parent::query("SELECT * FROM `indicador`where cesta=0");
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro listaNCesta: " . $ex->getMessage();
        }
    }
    
    public function lista() {
        try {
            $stmt = parent::query("SELECT * FROM `indicador`");
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro lista: " . $ex->getMessage();
        }
    }
    
    public function lista2() {
    	try {
    		$stmt = parent::query("SELECT * FROM `indicador` WHERE `Codigo` in (32,33,34,43) ");
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro lista: " . $ex->getMessage();
    	}
    }
    
    public function listaAtivo() {
    	try {
    		$stmt = parent::query("SELECT * FROM `indicador` where `validade` > '2015-12-31' ");
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro lista: " . $ex->getMessage();
    	}
    }

    public function buscaindicador($codigo) {
        try {
            $sql = "SELECT * FROM `indicador` WHERE `Codigo`=:codigo";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaindicador: " . $ex->getMessage();
        }
    }
    
    public function buscaindicadorinsere(Indicador $indicador) {
    	try {
    		$sql = "SELECT `Codigo` FROM `indicador` WHERE `nome`=:nome AND `objeto`=:objeto AND `calculo`=:calculo AND `validade`=:validade";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':nome' => $indicador->getNome(), ':objeto' => $indicador->getObjeto(), ':calculo' => $indicador-> getCalculo(),':validade'=> $indicador->getValidade()));   				
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscaindicador: " . $ex->getMessage();
    	}
    }

    public function buscaindicadorunidade($codUnidade) {
        try {
            $sql = "SELECT * FROM `indicador` WHERE `PropIndicador`=:codigo ORDER BY `Codigo`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codUnidade));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaindicadorunidade: " . $ex->getMessage();
        }
    }

    public function listaIndicadorNaoVinculado($anobase, $coddoc) {
        try {
 $sql = "SELECT * FROM indicador i  WHERE  i.codigo not in (SELECT mi.codindicador FROM  documento d  inner join `mapa` m on m.`CodDocumento`=d.codigo  inner join `mapaindicador` mi on mi.`codMapa`=m.`Codigo` WHERE d.codigo=:coddoc and  (`anoinicial`<=:ano and `anofinal`>=:ano) and mi.codindicador=i.codigo) ";
            $stmt = parent::prepare($sql);
            $stmt->execute(array( ':coddoc' => $coddoc,':ano' => $anobase));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro listaIndicadorNaoVinculado: " . $ex->getMessage();
        }
    }
    
    
    public function indicadoresPDI($anogestao,$periodo){
         //pega pds onde a unidade é proprietária de indicador
         try {
            $sql ="   
   SELECT distinct  o1.`Objetivo` , mi1.`Codigo` AS codmi, i1.`nome`,i1.codigo
  FROM `documento` d1 
  INNER JOIN `mapa` m1 ON m1.`CodDocumento` = d1.`codigo` and (m1.anoinicio<=:ano) and (m1.anofim is NULL or m1.anofim>:ano)
  INNER JOIN objetivo o1 ON m1.`codObjetivoPDI` = o1.`Codigo` 
  LEFT JOIN mapaindicador mi1 ON mi1.codmapa = m1.codigo  and ( mi1.anoinicial<=:ano) and (mi1.anofinal is NULL or mi1.anofinal>:ano)
  LEFT JOIN indicador i1 ON mi1.codindicador = i1.codigo 
  LEFT JOIN meta a1 ON mi1.codigo = a1.codmapaind  and ( a1.anoinicial<=:ano) and (a1.anofinal is NULL or a1.anofinal>:ano)
  left JOIN calendario c1 ON c1.codcalendario = a1.codcalendario 
  WHERE d1.codunidade =938 and mi1.tipoassociado=7
AND d1.anoinicial<=:ano AND d1.anofinal >=:ano";
            
     $stmt=parent::prepare($sql);// d.`situacao`='A' and
             $stmt->execute(array(':ano'=>$anogestao));
             return $stmt;
        } catch (PDOException $ex) {
            print "Erro indicadoresPDI: " . $ex->getCode() . " " . $ex->getMessage()." indicadoresPDI";
        } 
             
    
    }
    
public function indicadoresPDI1($anogestao,$periodo){
         //pega pds onde a unidade é proprietária de indicador
         try {
            $sql =" SELECT distinct  o1.`Objetivo` , mi1.`Codigo` AS codmi, i1.`nome`,i1.codigo, a1.meta,rp.meta_atingida as resultado
  FROM `documento` d1 
  INNER JOIN `mapa` m1 ON m1.`CodDocumento` = d1.`codigo` and (m1.anoinicio<=:ano) and (m1.anofim is NULL or m1.anofim>:ano)
  JOIN objetivo o1 ON m1.`codObjetivoPDI` = o1.`Codigo` 
  JOIN mapaindicador mi1 ON mi1.codmapa = m1.codigo  and (mi1.anoinicial<=:ano) and (mi1.anofinal is NULL or mi1.anofinal>:ano)
  JOIN indicador i1 ON mi1.codindicador = i1.codigo 
  JOIN meta a1 ON mi1.codigo = a1.codmapaind  and  (a1.anoinicial<=:ano) and (a1.anofinal is NULL or a1.anofinal>:ano)
  JOIN calendario c1 ON c1.codcalendario = a1.codcalendario
  JOIN resultados_pdi rp on rp.codmeta=a1.codigo and rp.periodo=:periodo  
  WHERE d1.codunidade =938 and mi1.tipoassociado=7
AND d1.anoinicial<=:ano AND d1.anofinal >=:ano";
            
     $stmt=parent::prepare($sql);// d.`situacao`='A' and
            $stmt->execute(array(':ano'=>$anogestao,':periodo'=>$periodo));
            return $stmt;

        } catch (PDOException $ex) {
            print "Erro indicadoresPDI1: " . $ex->getCode() . " " . $ex->getMessage()." indicadoresPDI";
        } 
             
    
    }
    
    public function indicadoresPDU($anogestao,$codunidade,$periodo){
         //pega pds onde a unidade é proprietária de indicador
         try {
            $sql ="   
select sd.nome,si.codigo,si.nome as indicador ,a1.meta,rp.meta_atingida as resultado
from documento sd join
mapa sm on sm.CodDocumento=sd.codigo  and (sm.anoinicio<=:ano) and (sm.anofim is NULL or sm.anofim>:ano)
join mapaindicador smi on smi.codmapa=sm.Codigo and (smi.anoinicial<=:ano) and (smi.anofinal is NULL or smi.anofinal>:ano)
join indicador si on smi.codindicador=si.codigo
  JOIN meta a1 ON smi.codigo = a1.codmapaind and  (a1.anoinicial<=:ano) and (a1.anofinal is NULL or a1.anofinal>:ano)
  JOIN calendario c1 ON c1.codcalendario = a1.codcalendario
  JOIN resultados_pdi rp on rp.codmeta=a1.codigo and rp.periodo=:periodo 
where sd.anoinicial<=:ano and sd.anofinal>=:ano
and sd.codunidade=:unid
union select sd.nome,si.Codigo,si.nome ,a1.meta,rp.meta_atingida as resultado
from documento sd join
mapa sm on sm.CodDocumento=sd.codigo  and (sm.anoinicio<=:ano) and (sm.anofim is NULL or sm.anofim>:ano)
join mapaindicador smi on smi.codmapa=sm.Codigo and smi.PropIndicador=:unid and smi.tipoAssociado=7 and (smi.anoinicial<=:ano) and (smi.anofinal is NULL or smi.anofinal>:ano)
join indicador si on smi.codindicador=si.codigo
  JOIN meta a1 ON smi.codigo = a1.codmapaind and  (a1.anoinicial<=:ano) and (a1.anofinal is NULL or a1.anofinal>:ano)
  JOIN calendario c1 ON c1.codcalendario = a1.codcalendario
  JOIN resultados_pdi rp on rp.codmeta=a1.codigo and rp.periodo=:periodo  
where sd.anoinicial<=:ano and sd.anofinal>=:ano
and sd.codunidade=938";
            
     $stmt=parent::prepare($sql);// d.`situacao`='A' and
             $stmt->execute(array(':ano'=>$anogestao,':unid'=>$codunidade,':periodo'=>$periodo));
             return $stmt;
        } catch (PDOException $ex) {
            print "Erro indicadoresPDU: " . $ex->getCode() . " " . $ex->getMessage()." indicadoresPDI";
        } 
             
    
    }
    
   public function indicadoresPDU1($anogestao,$periodo, $codindicador){
         //pega pds onde a unidade é proprietária de indicador
         try {
            $sql ="   
select u.nomeunidade,sd.nome,si.codigo,si.nome as indicador ,a1.meta,rp.meta_atingida as resultado,smi.tipoassociado
from documento sd 
join unidade u on u.codunidade=sd.codunidade
 join mapa sm on sm.CodDocumento=sd.codigo
join mapaindicador smi on smi.codmapa=sm.Codigo
join indicador si on smi.codindicador=si.codigo and si.codigo=:indicador
  JOIN meta a1 ON smi.codigo = a1.codmapaind  and  (a1.anoinicial<=:ano) and (a1.anofinal is NULL or a1.anofinal>:ano)
  JOIN calendario c1 ON c1.codcalendario = a1.codcalendario
  JOIN resultados_pdi rp on rp.codmeta=a1.codigo and rp.periodo=:periodo 
where sd.anoinicial<=:ano and sd.anofinal>=:ano and sd.codunidade<>938
union select u.nomeunidade, sd.nome,si.Codigo,si.nome ,a1.meta,rp.meta_atingida as resultado,smi.tipoassociado
from documento sd 
join mapa sm on sm.CodDocumento=sd.codigo
join mapaindicador smi on smi.codmapa=sm.Codigo  and smi.tipoAssociado=7
join indicador si on smi.codindicador=si.codigo and si.codigo=:indicador
join unidade u on u.codunidade=smi.propindicador
  JOIN meta a1 ON smi.codigo = a1.codmapaind  and  (a1.anoinicial<=:ano) and (a1.anofinal is NULL or a1.anofinal>:ano)
  JOIN calendario c1 ON c1.codcalendario = a1.codcalendario
  JOIN resultados_pdi rp on rp.codmeta=a1.codigo and rp.periodo=:periodo  
where sd.anoinicial<=:ano and sd.anofinal>=:ano
and sd.codunidade=938";
            
     $stmt=parent::prepare($sql);// d.`situacao`='A' and
             $stmt->execute(array(':ano'=>$anogestao,':periodo'=>$periodo,':indicador'=>$codindicador));
             return $stmt;
        } catch (PDOException $ex) {
            print "Erro indicadoresPDU1: " . $ex->getCode() . " " . $ex->getMessage()."";
        } 
             
    
    }
    
   
    
  public function listaIndicadorNaoVinculado1($anobase, $coddoc,$codunidade) {
        try {
 $sql = "SELECT * FROM indicador i  WHERE (i.codunidade=938 or i.codunidade=:unid) and i.cesta<>0 and  i.codigo not in 
 (SELECT mi.codindicador FROM  documento d  inner join `mapa` m on m.`CodDocumento`=d.codigo  inner 
 join `mapaindicador` mi on mi.`codMapa`=m.`Codigo` WHERE d.codigo=:coddoc and  
 (d.`anoinicial`<=:ano and d.`anofinal`>=:ano) and mi.codindicador=i.codigo ) and 
i.codigo not in 
 (SELECT mi1.codindicador FROM  documento d1  inner join `mapa` m1 on m1.`CodDocumento`=d1.codigo  inner 
 join `mapaindicador` mi1 on mi1.`codMapa`=m1.`Codigo` WHERE d1.codunidade=938 and  
 (d1.`anoinicial`<=:ano and d1.`anofinal`>=:ano) and mi1.codindicador=i.codigo and mi1.propindicador=:unid ) order by i.nome ";
            $stmt = parent::prepare($sql);
            $stmt->execute(array( ':coddoc' => $coddoc,':ano' => $anobase,':unid'=>$codunidade));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro listaIndicadorNaoVinculado1: " . $ex->getMessage();
        }
    }
    
    public function listaIndicadorNaoVinculado1_2022($anobase, $coddoc,$codunidade,$codObj) {
        try {
            $sql = "SELECT i.`Codigo`, i.`nome`, `objeto`, `calculo`, i.`validade`, `unidadeMedida`, `peso`, `interpretacao`, `metodo`, `observacoes`, `benchmarch`, `cesta`, `codunidade`, `codversao`, `anoinicio`, `anofim`, `dataAlteracao`, `codEixo`,ex.Nome AS nomeEixo FROM indicador i  
LEFT JOIN eixo ex ON ex.Codigo=i.codEixo
WHERE (i.codObjetivo=:obj or codunidade=:unid) and  (i.codunidade=938 or i.codunidade=:unid) and i.codEixo IS NOT null and  i.codigo not in
 (SELECT mi.codindicador FROM  documento d  inner join `mapa` m on m.`CodDocumento`=d.codigo  inner
 join `mapaindicador` mi on mi.`codMapa`=m.`Codigo`  
  WHERE d.codigo=:coddoc and
 (d.`anoinicial`<=:ano and d.`anofinal`>=:ano) and mi.codindicador=i.codigo ) and
i.codigo not in
 (SELECT mi1.codindicador FROM  documento d1  inner join `mapa` m1 on m1.`CodDocumento`=d1.codigo  inner
 join `mapaindicador` mi1 on mi1.`codMapa`=m1.`Codigo` WHERE d1.codunidade=938 and
 (d1.`anoinicial`<=:ano and d1.`anofinal`>=:ano) and mi1.codindicador=i.codigo and mi1.propindicador=:unid )  
 order by i.nome";
            $stmt = parent::prepare($sql);
            $stmt->execute(array( ':coddoc' => $coddoc,':ano' => $anobase,':unid'=>$codunidade, ':obj'=>$codObj));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro listaIndicadorNaoVinculado1: " . $ex->getMessage();
        }
    }
    
	public function buscaindicadorpormapa($codigo) {
        try {
            $sql = "SELECT rep.Codigo, ini.nome as nomeini, i.nome, rei.situacao, me.meta, rep.meta_atingida 
                    FROM indicador i 
					JOIN mapaindicador mi ON(i.Codigo = mi.codIndicador) 
					LEFT JOIN indic_iniciativa idn ON(idn.codMapaInd = mi.codigo) 
					LEFT JOIN iniciativa ini ON(ini.codIniciativa = idn.codIniciativa) 
					LEFT JOIN resultIniciativa rei ON(rei.codInidIniciativa = idn.codigo) 
					LEFT JOIN meta me on(me.CodMapaInd = mi.codigo) 
					LEFT JOIN resultados_pdi rep ON(rep.CodMeta = me.Codigo)  
					WHERE mi.codMapa= :codigo ORDER BY i.nome, me.ano";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaindicadorpormapa: " . $ex->getMessage();
        }
    }
    
public function buscaindicadorporano2($ano) {
        try {
            $sql = "SELECT rep.Codigo, ini.nome as nomeini, i.nome, rei.situacao, me.meta, rep.meta_atingida 
                    FROM indicador i 
					JOIN mapaindicador mi ON(i.Codigo = mi.codIndicador) 
					LEFT JOIN indic_iniciativa idn ON(idn.codMapaInd = mi.codigo) 
					LEFT JOIN iniciativa ini ON(ini.codIniciativa = idn.codIniciativa) 
					LEFT JOIN resultIniciativa rei ON(rei.codInidIniciativa = idn.codigo) 
					LEFT JOIN meta me on(me.CodMapaInd = mi.codigo) 
					LEFT JOIN resultados_pdi rep ON(rep.CodMeta = me.Codigo)  
					WHERE mi.codMapa= :codigo ORDER BY i.nome, me.ano";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaindicadorpormapa: " . $ex->getMessage();
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
            echo "Erro buscaindicadorpormapa1: " . $ex->getMessage();
        }
    }

    public function buscaindicadorpormapa2($codigo) {
        try {
            $sql = "SELECT * FROM `indicador` WHERE `CodMapa` = :codigo ORDER BY `CodMapa`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaindicadorpormapa2: " . $ex->getMessage();
        }
    }

    
    public function selectIndicadorByCodMapa($codmapa){
    	try {
    		
    		$sql = "SELECT * FROM `indicador` i JOIN `mapaindicador` mi ON(i.Codigo = mi.codIndicador) WHERE mi.codmapa = :codmap"  ;
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codmap' => $codmapa));
    		return $stmt;
    		
    	}catch (PDOException $ex) {
    		echo "Erro: " . $ex->getMessage();
    	}
    }
    
    public function selectIndicadorByCodMapa22($codmapa,$codInd){
    	try {
    
    		$sql = "SELECT * FROM `indicador` i JOIN `mapaindicador` mi ON(i.Codigo = mi.codIndicador) WHERE mi.codmapa = :codmap AND mi.codIndicador= :codInd"  ;
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codmap' => $codmapa,':codInd' => $codInd));
    		return $stmt;
    
    	}catch (PDOException $ex) {
    		echo "Erro: " . $ex->getMessage();
    	}
    }
    
    public function buscaIndicadorPorObjetivoDocumento($codObjetivo, $codDocumento){
    
    	try {
    
    		$sql = "SELECT i.Codigo, i.nome, mi.codigo as codMapaInd FROM `indicador` i JOIN `mapaindicador` mi ON(i.Codigo = mi.codIndicador)
					JOIN mapa ma ON(ma.Codigo = mi.codMapa) WHERE ma.codObjetivoPDI = :codobj AND ma.CodDocumento = :coddoc"    ;
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codobj' => $codObjetivo, ':coddoc' => $codDocumento));
    		return $stmt;
    
    	}catch (PDOException $ex) {
    		echo "Erro: buscaIndicadorPorObjetivoDocumento" . $ex->getMessage();
    	}
    }
    
 public function buscaIndicadorPorObjetivoDocumento1($codObjetivo, $codDocumento,$anobase,$codunidade){
    
    	try {
    
    		$sql = "SELECT i.Codigo, i.nome, mi.codigo as codMapaInd 
    		FROM `indicador` i 
    		JOIN `mapaindicador` mi ON(i.Codigo = mi.codIndicador) and mi.anoinicial<=:ano and (mi.anofinal>=:ano  or mi.anofinal is null)
    		JOIN mapa ma ON(ma.Codigo = mi.codMapa) and ma.anoinicio<=:ano and (ma.anofim>=:ano or ma.anofim is null)
    		 WHERE ma.codObjetivoPDI = :codobj AND ma.CodDocumento =:coddoc 
    		 union 
    		select i1.Codigo, i1.nome, mi1.codigo as codMapaInd 
    		FROM mapa m1 JOIN documento doc1 ON(m1.CodDocumento = doc1.codigo) and m1.anoinicio<=:ano and (m1.anofim>=:ano or m1.anofim is null) 
    		JOIN objetivo ob1 ON(m1.codObjetivoPDI = ob1.Codigo) JOIN perspectiva pers1 ON (m1.codPerspectiva = pers1.codPerspectiva) 
    		inner join mapaindicador mi1 on mi1.codMapa=m1.`Codigo` and mi1.anoinicial<=:ano and (mi1.anofinal>=:ano  or mi1.anofinal is null)
    		inner join `indicador` i1 ON (i1.Codigo = mi1.codIndicador)
    		 WHERE mi1.`PropIndicador` =:unid and doc1.anoinicial<=:ano AND doc1.anofinal >= :ano and doc1.CodUnidade=938 and m1.codObjetivoPDI = :codobj ";
    	
    		
    		
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codobj' => $codObjetivo, ':coddoc' => $codDocumento,':ano'=>$anobase,':unid'=>$codunidade));
    		return $stmt;
    
    	}catch (PDOException $ex) {
    		echo "Erro: buscaIndicadorPorObjetivoDocumento" . $ex->getMessage();
    	}
    }
    
    
    public function buscaIndicadoresForaCesta($codmapa){
    	
    try {
    
    		$sql = "SELECT i.`Codigo`,i.`nome` FROM `indicador` i WHERE i.`Codigo` not in (select mi.Codindicador from mapaindicador mi where mi.`codIndicador`=i.codigo and `codMapa`=:codm) and cesta=0";
    		 
            $stmt = parent::prepare($sql);
    		$stmt->execute(array(':codm' => $codmapa));
    		return $stmt;
    }catch (PDOException $ex) {
    		echo "Erro: buscaIndicadoresForaCesta" . $ex->getMessage();
    	}
    }

public function selectIndicadorByCodMapa2($codindicador, $codmapa){
    	try {
    		
    		$sql = "SELECT * FROM `indicador` i JOIN `mapaindicador` mi ON(i.Codigo = mi.codIndicador) WHERE  i.codigo=:indicador and `codMapa`=:codm"  ;
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':indicador'=>$codindicador,':codm' => $codmapa));
    		return $stmt;
    		
    	}catch (PDOException $ex) {
    		echo "Erro: " . $ex->getMessage();
    	}
    }
    
    
    
    
public function selectIndicadorByCodMapa1($codindicador){
    	try {
    		
    		$sql = "SELECT * FROM `indicador` i JOIN `mapaindicador` mi ON(i.Codigo = mi.codIndicador) WHERE  i.codigo=:indicador "  ;
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':indicador'=>$codindicador));
    		return $stmt;
    		
    	}catch (PDOException $ex) {
    		echo "Erro: " . $ex->getMessage();
    	}
    }
    public function fechar() {
        PDOConnectionFactory::Close();
    }

}

?>

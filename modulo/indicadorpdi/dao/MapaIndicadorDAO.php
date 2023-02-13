<?php

class MapaIndicadorDAO extends PDOConnectionFactory {

    public $conex = null;
  

    public function insere($codind,$codmapa,$propindicador,$tipo) {
    	try {
    		$sql = "INSERT INTO `mapaindicador` (`codIndicador`, `codMapa`,`PropIndicador`,`tipoAssociado`)" .
    				"VALUES (?,?,?,?)";
    		$stmt = parent::prepare($sql);
    		parent::beginTransaction();
    		$stmt->bindValue(1, $codind); // indicador
    		$stmt->bindValue(2, $codmapa); // mapa
    		$stmt->bindValue(3, $propindicador); // unidade
    		$stmt->bindValue(4, $tipo); // unidade
    		
    		$stmt->execute();
    		parent::commit();
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
    		
    	}
    }
    
    

    public function insereMiSol(Mapaindicador $mi) {
    	try {
    		$sql = "INSERT INTO `mapaindicador` (`codIndicador`, `codMapa`,`PropIndicador`,`tipoAssociado`,
    		 `anoinicial`, `periodoinicial`, `anofinal`, `periodofinal`) VALUES (?,?,?,?,?,?,?,?)";

    		$stmt = parent::prepare($sql);
    		parent::beginTransaction();

    		$stmt->bindValue(1, $mi->getIndicador()->getCodigo()); // indicador
    		$stmt->bindValue(2, $mi->getMapa()->getCodigo()); // mapa
    		$stmt->bindValue(3, $mi->getPropindicador()->getCodunidade()); 
    		$stmt->bindValue(4, $mi->getTipoassociado()); // unidade
    		$stmt->bindValue(5, $mi->getAnoinicio()); 
    		$stmt->bindValue(6, $mi->getPeriodoinicial()); 
    		$stmt->bindValue(7, $mi->getAnofim());  
    		$stmt->bindValue(8, $mi->getPeriodofinal());  

    		$stmt->execute();
            $id= parent::lastInsertId();
    		parent::commit();
    		return $id;    	
    	
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: Código:insereMiSol" . $ex->getCode() . "Mensagem" . $ex->getMessage();
    		die;
    	}
    }
    
     public function insereTodos($pis) {
      	
      try {
            parent::beginTransaction();
            for ($i=1;$i<=count($pis);$i++) {
                $sql = "INSERT INTO `mapaindicador` (`codIndicador`, `codMapa`,`PropIndicador`) VALUES (?,?,?)";
                $stmt = parent::prepare($sql);
                $stmt->bindValue(1, $pis[$i]->getIndicador()->getCodigo()); // indicador
    			$stmt->bindValue(2, $pis[$i]->getMapa()->getCodigo()); // mapa
    			$stmt->bindValue(3, $pis[$i]->getPropindicador()); // unidade
                $stmt->execute();
            }
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            print "Erro: insereTodos Mapaindicador:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }
    
    
    public function spvincularInd($ind,$map,$prop) 
{
            try {
            	//echo "passou".$ind.",".$map.",".$prop;
                $stmt = parent::prepare("CALL  vincularIndicadorPDI_PDU(:g,:h,:u)");
                $stmt->bindParam(':g', $ind);//,parent::PARAM_INT );
                $stmt->bindParam(':h', $map);//,parent::PARAM_STR,200);
                $stmt->bindParam(':u', $prop);//,parent::PARAM_INT);
        
                    
                $success = $stmt->execute();
                if($success){
                	
                    //$result = $stmt->fetchAll(parent::FETCH_ASSOC);
                    //echo 'teste';
                    return 1;
                }else{
                	
                	return 0;   
                             }
        
            } catch (PDOException $ex) {
                parent::rollback();
                echo "Erro: sp indicador " . $ex->getMessage();
        
        
            }
        }
	
      
    
    public function altera(Mapaindicador $mapaindicador) {
    	try {
    		$sql = "UPDATE `mapaindicador` SET `codIndicador`=?,`CodMapa`=?,`PropIndicador`=? " .
    				"WHERE `Codigo`=?";
    		$stmt = parent::prepare($sql);
    		parent::beginTransaction();
    		$stmt->bindValue(1, $mapaindicador->getIndicador()->getCodigo());
    		$stmt->bindValue(2, $mapaindicador->getMapa()->getCodigo());
    		$stmt->bindValue(3, $mapaindicador->getPropindicador()->getCodunidade());
    		$stmt->bindValue(4, $mapaindicador->getCodigo());
    		$stmt->execute();
    		parent::commit();
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
    	}
    }
    
public function alteraSol($codigo,$anofinal) {
    	try {
    		$sql = "UPDATE `mapaindicador` SET `anofinal`=?,`periodofinal`=? WHERE `Codigo`=?";
    		$stmt = parent::prepare($sql);
    		parent::beginTransaction();
    		$stmt->bindValue(1, $anofinal);
    		$stmt->bindValue(2, "1");
    		$stmt->bindValue(3, $codigo);
    		$stmt->execute();
    		parent::commit();
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
    	}
    }
    
    public function deleta($codigo) {
    	try {
    		parent::beginTransaction();
    		$stmt = parent::prepare("DELETE FROM `mapaindicador` WHERE `codigo`=?");
    		$stmt->bindValue(1, $codigo);
    		$stmt->execute();
    		parent::commit();
    		return 1;
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
    		return 0;
    	}
    }
    
     public function buscaIndicadorVinculadoAoObjetivoIncluidoNoPDUporIndicador($anodocumento,$codobjetivo,$propIndicador,$codind) {
        try {
 
$sql="SELECT a.codCalendario,a.meta,a.periodo,a.metrica,a.ano 
FROM `documento` d inner 
join mapa m on d.`codigo`=m.`CodDocumento` 
inner join `mapaindicador` mi on mi.`codMapa`=m.Codigo 
inner join indicador i on 
mi.`codIndicador`=i.Codigo inner join meta a on a.codMapaInd=mi.codigo  
WHERE d.`CodUnidade`=938 and d.`anoinicial`<=:anobase and d.`anofinal`>=:anobase 
and (m.anoinicio is NULL or m.anoinicio<=:anobase) and (m.anofim is NULL or m.anofim>:anobase)
and mi.propindicador=:cod_unidade and (mi.anoinicial is NULL or mi.anoinicial<=:anobase) and (mi.anofinal is NULL or mi.anofinal>:anobase)
 and m.`codObjetivoPDI`=:codobj and `PropIndicador`=:propind and i.Codigo=:codind";
           $stmt = parent::prepare($sql);
    	   $stmt->execute(array(':anobase' => $anodocumento, ':codobj' => $codobjetivo, ':propind' => $propIndicador,':codind'=>$codind));
    	   return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaIndicadorVinculadoAoObjetivoIncluidoNoPDUporIndicador: " . $ex->getMessage();
        }
    }    
    
    
    public function buscaIndicadorVinculadoAoObjetivoIncluidoNoPDU($anodocumento,$codobjetivo,$propIndicador) {
        try {
 
$sql="SELECT mi.`codIndicador`, d.`codigo`,m.codObjetivoPDI,m.codPerspectiva,mi.codigo as codmapind1 
FROM `documento` d 
inner join mapa m on d.`codigo`=m.`CodDocumento` 
inner join `mapaindicador` mi on mi.`codMapa`=m.Codigo 
inner join indicador i on  mi.`codIndicador`=i.Codigo 
WHERE d.`CodUnidade`=938 and d.`anoinicial`<=:anobase and d.`anofinal`>=:anobase 
and (m.anoinicio is NULL or m.anoinicio<=:anobase) and (m.anofim is NULL or m.anofim>:anobase)
 and (mi.anoinicial is NULL or mi.anoinicial<=:anobase) and (mi.anofinal is NULL or mi.anofinal>:anobase)
 and m.`codObjetivoPDI`=:codobj and `PropIndicador`=:propind";
           $stmt = parent::prepare($sql);
    	   $stmt->execute(array(':anobase' => $anodocumento, ':codobj' => $codobjetivo, ':propind' => $propIndicador));
    	   return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaIndicadorVinculadoAoObjetivoIncluidoNoPDU: " . $ex->getMessage();
        }
    }    
    
    
    public function lista() {
        try {
            $stmt = parent::query("SELECT * FROM `mapaindicador`");
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro lista: " . $ex->getMessage();
        }
    }
    
    public function buscamapaporindicador($codigo) {
    	try {
    		$sql = "SELECT * FROM `mapaindicador` WHERE `codIndicador`=:codigo";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codigo' => $codigo));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscaindicador: " . $ex->getMessage();
    	}
    }
    
    //Buscar dados mapaindicador por código identificador
    public function buscarDadosMapaindicador($codigo) {
    	try {
    		$sql = "SELECT * FROM `mapaindicador` WHERE `codigo`=:codigo";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codigo' => $codigo));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscaindicador: " . $ex->getMessage();
    	}
    } 
    
    public function buscapormapa($codigo) {
    	try {
    		$sql = "SELECT * FROM `mapaindicador` WHERE `codMapa`= :codigo";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codigo' => $codigo));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscaindicadorpormapa: " . $ex->getMessage();
    	}
    }

    public function buscaMapaIndicador($micodigo) {
        try {
            $sql = "SELECT mi.`codigo`,mi.`codIndicador`,mi.`codMapa`,mi.`PropIndicador`,i.nome, interpretacao,calculo,unidadeMedida ".
                " FROM indicador i inner join `mapaindicador` mi on mi.`codIndicador`=i.codigo".
                " WHERE mi.`codigo`=:codigo";

            //echo "mapaind".$micodigo;
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $micodigo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaMapaIndicador: " . $ex->getMessage();
        }
    }

 /* public function verSeMapaTemIndicador($codmapa){//17 de nov 2022
    	try {
    		$sql = "SELECT * FROM `mapaindicador` WHERE codMapa = :codmap ";
    		
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codmap' => $codmapa));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro verSeMapaTemIndicador: " . $ex->getMessage();
    	}
    }*/
    
    public function verSeMapaTemIndicador($codmapa,$anobase){//17 de nov 2022
        try {
            $sql = "SELECT * FROM `mapaindicador` WHERE codMapa = :codmap and anoinicial<=:anobase and (anofinal>=:anobase or anofinal is null)";
            
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codmap' => $codmapa,':anobase' => $anobase));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro verSeMapaTemIndicador: " . $ex->getMessage();
        }
    }

    public function buscaMapaIndicadorporMI($codmapa, $codindicador){
    	try {
    		$sql = "SELECT * FROM `mapaindicador` WHERE codMapa = :codmap AND codIndicador = :codind";
    		
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codmap' => $codmapa, ':codind' => $codindicador));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscaMapaIndicador: " . $ex->getMessage();
    	}
    } 
    
 public function origemDoIndicadorDaUnidadeEPDI($anobase,$codunid,$codind) {
        try {
            $sql = "SELECT  distinct d.nome,d.tipo
FROM  documento d
 JOIN mapa m ON m.coddocumento = d.codigo
JOIN  `mapaindicador` mi ON  mi.`codMapa` = m.codigo  
WHERE d.anoinicial<=:ano and d.anofinal >=:ano and `PropIndicador`=:codunid and d.codunidade=938 
and mi.codindicador=:mind and mi.tipoAssociado=7
and  (m.anoinicio is NULL or m.anoinicio<=:ano) and (m.anofim is NULL or m.anofim>:ano)
 and (mi.anoinicial is NULL or mi.anoinicial<=:ano) and (mi.anofinal is NULL or mi.anofinal>:ano) ";

            //echo "mapaind".$micodigo;
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':ano'=>$anobase,':codunid'=>$codunid,':mind'=>$codind));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro origemDoIndicadorDaUnidadeEPDI: " . $ex->getMessage();
        }
    } 
    
      public function buscaIndicadorporObjetivo($doccodigo) {
        try {
            $sql = "SELECT  `codObjetivoPDI` , o.Objetivo, i.`nome` 
FROM  `indicador` i
JOIN  `mapaindicador` mi ON ( i.Codigo = mi.codIndicador ) 
INNER JOIN mapa m ON mi.`codMapa` = m.codigo
INNER JOIN objetivo o ON o.`Codigo` =  `codObjetivoPDI` 
WHERE coddocumento =:codigo";

            //echo "mapaind".$micodigo;
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $doccodigo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaIndicadorporDoc: " . $ex->getMessage();
        }
    } 
    
    public function buscaporMapaUnidade($codigo,$unidade) {
    	try {
    		$sql = "SELECT mi.*,i.nome as nomeindicador,ob.Codigo as codigoobjetivo,ob.Objetivo as nomeobjetivo 
    		FROM `mapaindicador` mi
    		 JOIN indicador i ON(mi.codIndicador = i.Codigo) 
    		 JOIN mapa m ON(mi.codMapa = m.Codigo) JOIN objetivo ob
    		  ON(m.codObjetivoPDI = ob.Codigo)  WHERE `codMapa`= :codigo AND PropIndicador= :codprop  ";
    		
    		
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codigo' => $codigo,':codprop' => $unidade));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscaporMapaUnidade: " . $ex->getMessage();
    	}
    }
    
  
    
    
    public function buscaporMapaUnidadeAno($codigo,$unidade,$ano,$coddocumento) {//aqui
        try {
            $sql = "SELECT mi.*,i.nome as nomeindicador,ob.Codigo as codigoobjetivo,ob.Objetivo as nomeobjetivo
    		FROM `mapaindicador` mi
    		 JOIN indicador i ON(mi.codIndicador = i.Codigo)
    		 JOIN mapa m ON(mi.codMapa = m.Codigo) JOIN objetivo ob
    		  ON(m.codObjetivoPDI = ob.Codigo)
    		  WHERE `codMapa`= :codigo AND PropIndicador= :codprop and m.coddocumento=:doc
    		  AND (mi.anoinicial<=:ano AND (mi.anofinal>=:ano OR  mi.anofinal IS NULL) ) ";
            
            
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo,':codprop' => $unidade,':ano' => $ano,':doc' => $coddocumento));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaporMapaUnidadeAno: " . $ex->getMessage();
        }
    }
    
  public function buscaporMapaSemUnidade($codigo) {
    	try {
    		$sql = "SELECT mi.*,i.nome as nomeindicador,ob.Codigo as codigoobjetivo,ob.Objetivo as nomeobjetivo
    		 FROM `mapaindicador` mi 
    		 JOIN indicador i ON(mi.codIndicador = i.Codigo) 
    		 JOIN mapa m ON(mi.codMapa = m.Codigo) 
    		 JOIN objetivo ob ON(m.codObjetivoPDI = ob.Codigo)  WHERE `codMapa`= :codigo  and tipoAssociado =7";
    		
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codigo' => $codigo));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscaporMapaUnidade: " . $ex->getMessage();
    	}
    }
    
    public function buscaporMapaSemUnidadeComAno($codigo,$ano,$coddoc) {
    	try {
    		$sql = "SELECT mi.*,i.nome as nomeindicador,ob.Codigo as codigoobjetivo,ob.Objetivo as nomeobjetivo
    		 FROM `mapaindicador` mi
    		 JOIN indicador i ON(mi.codIndicador = i.Codigo)
    		 JOIN mapa m ON(mi.codMapa = m.Codigo)
    		 JOIN objetivo ob ON(m.codObjetivoPDI = ob.Codigo)  
WHERE `codMapa`= :codigo  and tipoAssociado =7 
AND ((mi.anoinicial<=:ano AND mi.anofinal>=:ano) OR (mi.anoinicial<=:ano AND mi.anofinal IS NULL) )
and m.coddocumento=:coddoc";
    
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codigo' => $codigo,':ano'=>$ano,':coddoc'=>$coddoc));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscaporMapaUnidade: " . $ex->getMessage();
    	}
    }
    
     public function buscaporIndicadorPorMapa($codigo) {
    	try {
    		$sql = "SELECT mi.*,i.nome as nomeindicador,ob.Codigo as codigoobjetivo,ob.Objetivo as nomeobjetivo
    		 FROM `mapaindicador` mi 
    		 JOIN indicador i ON(mi.codIndicador = i.Codigo) 
    		 JOIN mapa m ON(mi.codMapa = m.Codigo) 
    		 JOIN objetivo ob ON(m.codObjetivoPDI = ob.Codigo)  WHERE `codMapa`= :codigo ";
    		
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codigo' => $codigo));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscaporMapaUnidade: " . $ex->getMessage();
    	}
    }
    
    public function buscamapa($codind,$codmapa,$codunidade) {
    	try {
    		$sql = "SELECT * FROM `mapaindicador` WHERE `codIndicador`=:codind and `codMapa`=:codmapa and `PropIndicador`=:coduni";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codind' => $codind,':codmapa' => $codmapa, ':coduni' => $codunidade ));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscaindicador: " . $ex->getMessage();
    	}
    }
    
    //Atualizar data-final do mapaindicador
    public function inserirDatafinalMapaind($codMapaind,$ano) {
    	try {
    		$sql = "UPDATE `mapaindicador` SET `anofinal`=:ano	WHERE `codigo`=:codigo";
    		$stmt = parent::prepare($sql);
    		parent::beginTransaction();    		
    		$stmt->execute(array(':codigo' => $codMapaind,':ano'=>$ano));
    		parent::commit();
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
    	}
    }
    
    public function fechar() {
        PDOConnectionFactory::Close();
    }

}

?>

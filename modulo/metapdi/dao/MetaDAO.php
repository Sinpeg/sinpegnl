<?php

class MetaDAO extends PDOConnectionFactory {

    public $conex = null;
/*
    public function MetaDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
    }
*/
public function insere(Meta $meta) {
        try {
            $sql = "INSERT INTO `meta` (`CodMapaInd`, `codCalendario` ,`periodo`, `meta`, `ano`,`metrica`,`cumulativo`,`anoinicial`,`periodoinicial`) VALUES (?,?,?,?,?,?,?,?,?)";
            $stmt = parent::prepare($sql);
            parent::beginTransaction();
           
              //    echo $meta->getAnofinal()."-". $meta->getPeriodofinal()."-".$meta->getCodigo(); die;
            
            $stmt->bindValue(1, $meta->getMapaindicador()->getCodigo());
            $stmt->bindValue(2, $meta->getCalendario()->getCodigo());
            $stmt->bindValue(3, $meta->getPeriodo());
            $stmt->bindValue(4, $meta->getMeta());
            
            $stmt->bindValue(5, $meta->getAno());
            $stmt->bindValue(6, $meta->getMetrica());
            $stmt->bindValue(7, $meta->getCumulativo()); 
            $stmt->bindValue(8, $meta->getAnoinicial());  
                      
            $stmt->bindValue(9, $meta->getPeriodoinicial());            
            
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            print "Erro em insere meta: Código:" . $ex->getCode() . " Mensagem" . $ex->getMessage();die;
        }
    }
    
     public function alteraSol(Meta $meta) {
        try {
            $sql = "UPDATE `meta` SET  `anofinal`=?,`periodofinal`=? WHERE `Codigo`=?";
            $stmt = parent::prepare($sql);
            parent::beginTransaction();
   //    echo $meta->getAnofinal()."-". $meta->getPeriodofinal()."-".$meta->getCodigo(); die;
            $stmt->bindValue(1, $meta->getAnofinal());
            $stmt->bindValue(2, $meta->getPeriodofinal());
            $stmt->bindValue(3, $meta->getCodigo());
            
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            print "Erro: alteraSol meta:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
            die;
        }
    }
    
    public function alteraMeta2021($codmeta,$valor,$metrica) {
        try {
            $sql = "UPDATE `meta` SET  `meta`=?,`metrica`=? WHERE `Codigo`=?";
            $stmt = parent::prepare($sql);
            parent::beginTransaction();
            //    echo $meta->getAnofinal()."-". $meta->getPeriodofinal()."-".$meta->getCodigo(); die;
            $stmt->bindValue(1, $valor);
            $stmt->bindValue(2, $metrica);
            $stmt->bindValue(3, $codmeta);
            
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            print "Erro: alteraMeta2021 meta:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
            die;
        }
    }

       public function alteraMeta($codmeta,$valor) {
        try {
            $sql = "UPDATE `meta` SET  `meta`=? WHERE `Codigo`=?";
            $stmt = parent::prepare($sql);
            parent::beginTransaction();
            
            $stmt->bindValue(1, $valor);
            $stmt->bindValue(2, $codmeta);
            
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            print "Erro: alteraMeta meta:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
            die;
        }
    }


    public function altera(Meta $meta) {
        try {
            $sql = "UPDATE `meta` SET `CodMapaInd`=?,  
                `periodo`=?,`meta`=?, `ano`=?, `metrica`=?, `cumulativo`=?, `codCalendario`=?,anoinicial=?,periodoinicial=?  WHERE `Codigo`=?";
            $stmt = parent::prepare($sql);
            parent::beginTransaction();
            $stmt->bindValue(1, $meta->getMapaindicador()->getCodigo());      
            $stmt->bindValue(2, $meta->getPeriodo());
            $stmt->bindValue(3, $meta->getMeta());
            $stmt->bindValue(4, $meta->getAno());
            $stmt->bindValue(5, $meta->getMetrica());
            $stmt->bindValue(6, $meta->getCumulativo());
            $stmt->bindValue(7, $meta->getCalendario()->getCodigo());
            $stmt->bindValue(8, $meta->getAnoinicial());
            $stmt->bindValue(9, $meta->getPeriodoinicial());
            
            $stmt->bindValue(10, $meta->getCodigo());
            
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            print "Erro: altera meta:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
            die;
        }
    }

   

    
 public function insereAlll($arraymeta) {
    	try {
    		
    		parent::beginTransaction();
    		foreach ($arraymeta as $m){
    			$sql = "INSERT INTO `meta` (`CodMapaInd`, `codCalendario` ,`periodo`, `meta`, `ano`,`metrica`,cumulativo,`anoinicial`,`periodoinicial`) VALUES (?,?,?,?,?,?,?,?,?)";
    			
	    		$stmt = parent::prepare($sql);
	    		//echo $m->getMapaindicador()->getCodigo().",".$m->getCalendario()->getCodigo().",".$m->getPeriodo().",".$m->getMeta().",".
	    		//$m->getMetrica().",".$m->getAno().",".$m->getAnoinicial().",".$m->getPeriodoinicial().",". $m->getCumulativo();die;
	    		
	    		$stmt->bindValue(1, $m->getMapaindicador()->getCodigo());

	    	    $stmt->bindValue(2, $m->getCalendario()->getCodigo());
	    		$stmt->bindValue(3, $m->getPeriodo());
	    		$stmt->bindValue(4, $m->getMeta());
	    		$stmt->bindValue(5, $m->getAno());
	    		$stmt->bindValue(6, $m->getMetrica());
	    	    $stmt->bindValue(7, $m->getCumulativo());
				$stmt->bindValue(8, $m->getAnoinicial());            
            	$stmt->bindValue(9, $m->getPeriodoinicial()); 	    		
            	$stmt->execute();
    		
    		}
    		parent::commit();
    	} catch (PDOException $ex) {
    		//            parent::rollback();
    		print "Erro insereAlll:" . $ex->getCode() . " Mensagem" . $ex->getMessage();
    	}
    }
    
    public function updateAlll($arraymeta) {
    	try {
    		parent::beginTransaction();
    
    		foreach ($arraymeta as $meta){
    			 
    			$sql = "UPDATE `meta` SET `CodMapaInd`=?, codCalendario = ?, `periodo`=?,`meta`=?, `ano`=?,
    			 `metrica`=?,cumulativo=?,`anoinicial`=?,`periodoinicial`=? WHERE `Codigo`=?";
    			
    			
    			$stmt = parent::prepare($sql);
    			$stmt->bindValue(1, $meta->getMapaindicador()->getCodigo());
    			$stmt->bindValue(2, $meta->getCalendario()->getCodigo());
    			$stmt->bindValue(3, $meta->getPeriodo());
    			$stmt->bindValue(4, $meta->getMeta());
    			$stmt->bindValue(5, $meta->getAno());
    			$stmt->bindValue(6, $meta->getMetrica());
                $stmt->bindValue(7, $meta->getCumulativo());
				$stmt->bindValue(8, $meta->getAnoinicial());            
            	$stmt->bindValue(9, $meta->getPeriodoinicial()); 	      			
    			$stmt->bindValue(10, $meta->getCodigo());
    			/*echo $meta->getMapaindicador()->getCodigo()." ".
    			$meta->getCalendario()->getCodigo()." ".
    			$meta->getPeriodo()." ".
    			$meta->getMeta()." ".
    			$meta->getAno()." ".
    			$meta->getMetrica()." ".
    			$meta->getCumulativo()." ".
    			$meta->getAnoinicial()." ".
    			$meta->getPeriodoinicial()."  ----- xxx ".
    			 $meta->getCodigo()."<br>";*/
    			//echo 'até aqui xx';die;
    			$stmt->execute();
    
    		}
    		parent::commit();
    	} catch (PDOException $ex) {
    		//            parent::rollback();
    		print "Erro: Código:" . $ex->getCode() . " Mensagem" . $ex->getMessage();die;
    	}
    }
    
    
    public function deleta($codigo) {
        try {
            parent::beginTransaction();
            $stmt = parent::prepare("DELETE FROM `meta` WHERE `Codigo`=?");
            $stmt->bindValue(1, $codigo);
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }

    
        public function deletaAll($arraymeta) {
        	try {
    		parent::beginTransaction();
    
    		foreach ($arraymeta as $meta){
    			 
    			$sql = "DELETE FROM `meta` WHERE `Codigo`=?";
    			$stmt = parent::prepare($sql);
    				$stmt->bindValue(1, $meta->getCodigo());
    			$stmt->execute();
    
    		}
    		parent::commit();
    	} catch (PDOException $ex) {
    		//            parent::rollback();
    		print "Erro em deletaAll(meta):" . $ex->getCode() . " Mensagem" . $ex->getMessage();
    	}
       }
    
    
  public function buscaMetaResultadoporCodMapaIndiOnly($codMapaIndicador,$anobase){
    	try {
    		$sql = "SELECT * FROM `meta` WHERE `codMapaInd`=:codmi
 and ano<>2016 and anoinicial<=:ano and (anofinal>=:ano or anofinal is NULL) order by ano ";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codmi' => $codMapaIndicador,':ano' => $anobase));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro: buscaMetaResultadoporCodMapaIndiOnly " . $ex->getMessage();
    	}
    }

public function buscaMetaResultadoporCodMapaIndiResult($codMapaIndicador,$anoinicial,$anofinal){
    	try {
    		$sql = "SELECT * FROM `meta` me JOIN `resultados_pdi` re ON(me.Codigo = re.CodMeta) WHERE `codMapaInd`= :codmi
    		and me.ano>=:ai and me.ano<=:af ";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codmi' => $codMapaIndicador, ':ai'=>$anoinicial,':af'=>$anofinal));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscaMetaResultadoporCodMapaIndi: " . $ex->getMessage();
    	}
    }

    public function lista() {
        try {
            $stmt = parent::query("SELECT * FROM `meta` ORDER BY `CodigoIndicador`, `ano`");
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }

    public function buscarmeta($codigo) {
        try {
            $sql = "SELECT * FROM `meta` WHERE `Codigo`= :codigo ORDER BY  `ano`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscarmeta: " . $ex->getMessage();
        }
    }

    public function buscarmetaindicador($codmapind,$codcal) {
        try {
        	$sessao=$_SESSION['sessao'];
        	$anobase=$sessao->getAnobase();
        	//echo 'sessao'.$anobase;
            $sql = "SELECT `Codigo`, `CodMapaInd`, `codCalendario`, `periodo`, `meta`,
                 `ano`, `metrica`, `cumulativo`,anoinicial, anofinal, periodoinicial, periodofinal 
                  FROM `meta`
                 WHERE `CodMapaInd`=:codindmap and `codCalendario`=:codcal 
                 and anoinicial<=:ano and (anofinal>=:ano or anofinal is NULL)";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codindmap' => $codmapind,':codcal'=>$codcal,':ano'=>$anobase));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscarmetaindicador: " . $ex->getMessage();
        }
    }
    public function buscarmetaParaCalendario($codcal) {
        try {
        	$sessao=$_SESSION['sessao'];
        	$anobase=$sessao->getAnobase();
            $sql = "SELECT * FROM `meta` WHERE `codCalendario`= :codigo  and anoinicial<=:ano and (anofinal>:ano or anofinal is NULL)  ";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codcal,':ano'=>$anobase));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

	public function buscaMetaResultadoporCodMapaIndi($codMapaIndicador){
    	try {
    		$sql = "SELECT * FROM `meta` me JOIN `resultados_pdi` re ON (me.Codigo = re.CodMeta) WHERE `codMapaInd`= :codmi ";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codmi' => $codMapaIndicador));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscaMetaResultadoporCodMapaIndi: " . $ex->getMessage();
    	}
    }
    
    public function buscarmetamapaindicador($codMapaIndicador,$ano) {
    	try {
    		$sql = "SELECT * FROM `meta` WHERE `CodMapaInd`= :codigo  
and `ano` = :ano and anoinicial<=:ano and (anofinal>=:ano or anofinal is null) ";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codigo' => $codMapaIndicador,':ano'=>$ano));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscarmetamapaindicador: " . $ex->getMessage();
    	}
    }
    
       public function buscarmetamapaindicador1($codMapaIndicador) {
    	try {
    		$sql = "SELECT * FROM `meta` WHERE `CodMapaInd`= :codigo  and  ( anofinal is null)  ";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codigo' => $codMapaIndicador));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscarmetamapaindicador1: " . $ex->getMessage();
    	}
    }
    

   public function buscarmetamapaindicadorperiodo($codMapaIndicador) {
    	try {
    		$sql = "SELECT * FROM `meta` WHERE `CodMapaInd`= :codigo ";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codigo' => $codMapaIndicador));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscarmeta: " . $ex->getMessage();
    	}
    }
    
    public function buscarindicadorpormeta($codmeta) {
    	try {
    		$sql = "SELECT i.nome FROM `meta` a inner join mapaindicador mi on `CodMapaInd`=mi.codigo inner join indicador i on i.codigo=mi.`codIndicador` WHERE a.`Codigo`= :codigo ";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codigo' => $codmeta));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscarindicadorpormeta metaDAO: " . $ex->getMessage();
    	}
    }
    
    

 
    public function fechar() {
        PDOConnectionFactory::Close();
    }

}

?>

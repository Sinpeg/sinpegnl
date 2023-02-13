<?php
class MapaDAO extends PDOConnectionFactory{

    public $conex = null;
 /*
    public function MapaDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
    }
 */
    public function insere(Mapa $mapa) {
        try {
            $sql = "INSERT INTO `mapa` (`CodDocumento`, `codPerspectiva`, `codObjetivoPDI`,
										 `PropMapa`,`anoinicio`,`periodoinicial`)
										 VALUES (?,?,?,?,?,?)";
            $stmt = parent::prepare($sql);
            parent::beginTransaction();
            $stmt->bindValue(1, $mapa->getDocumento()->getCodigo());
            $stmt->bindValue(2, $mapa->getPerspectiva()->getCodigo());
            $stmt->bindValue(3, $mapa->getObjetivoPDI()->getCodigo());
            $stmt->bindValue(4, $mapa->getPropMapa());
            $stmt->bindValue(5, $mapa->getAnoinicio());
            $stmt->bindValue(6, $mapa->getPeriodoinicial());
            
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            print "Erro: insere mapa:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }
    
 public function insereMapaSol(Mapa $mapa) {
        try {
            $sql = "INSERT INTO `mapa` (`CodDocumento`, `codPerspectiva`, `codObjetivoPDI`,
										 `PropMapa`,`anofim`, `periodofinal`,`anoinicio`, `periodoinicial`)
										 VALUES (?,?,?,?,?,?,?,?)";
            $stmt = parent::prepare($sql);
            parent::beginTransaction();
            $stmt->bindValue(1, $mapa->getDocumento()->getCodigo());
            $stmt->bindValue(2, $mapa->getPerspectiva()->getCodigo());
            $stmt->bindValue(3, $mapa->getObjetivoPDI()->getCodigo());
            $stmt->bindValue(4, $mapa->getPropMapa()->getCodunidade());
            $stmt->bindValue(5, $mapa->getAnofim());
            $stmt->bindValue(6, $mapa->getPeriodofinal());
            $stmt->bindValue(7, $mapa->getAnoinicio());
            $stmt->bindValue(8, $mapa->getPeriodoinicial());           
            $stmt->execute();
            $id= parent::lastInsertId();
    		parent::commit();
    		return $id;
        } catch (PDOException $ex) {
            parent::rollback();
            print "Erro: insere mapa sol:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
            die;
        }
    }
    
  public function insereVinculados(Mapa $mapa) {
       
        
    try {
            parent::beginTransaction();
            $sql = "INSERT INTO `mapa` (`CodDocumento`, `codPerspectiva`, `codObjetivoPDI`,`PropMapa`) VALUES (?,?,?,?)";
            parent::commit();
        }catch (PDOException $ex){
            parent::rollback();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            header($cadeia);
        }
    }

     public function buscaMpaByCodigoIndicador($codindicador){
        try {
            $sql = "SELECT * FROM `mapa` m JOIN `mapaindicador` maI ON (m.Codigo = maI.codMapa) WHERE maI.codIndicador = :codind"  ;
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codind' => $codindicador));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }
    
    public function altera(Mapa $mapa) {
        try {
            $sql = "UPDATE `mapa` SET `CodDocumento` =?, `codPerspectiva` =?, `codObjetivoPDI` =?, 
					  `PropMapa` =?, `anofim` =?, `periodofinal` =? WHERE `Codigo`=?";
            $stmt = parent::prepare($sql);
            parent::beginTransaction();
//            print $sql;
            $stmt->bindValue(1, $mapa->getDocumento()->getCodigo());
            $stmt->bindValue(2, $mapa->getPerspectiva()->getCodigo());
            $stmt->bindValue(3, $mapa->getObjetivoPDI()->getCodigo());
            $stmt->bindValue(4, $mapa->getPropMapa());
            $stmt->bindValue(5, $mapa->getVisao());
            $stmt->bindValue(6, $mapa->getAnofim());
            $stmt->bindValue(7, $mapa->getPeriodofinal());
            $stmt->bindValue(8, $mapa->getCodigo());
            
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }

    
 public function alteraMapaporCodigo($codmapa,$anofim) {
        try {
            $sql = "UPDATE `mapa` SET `anofim` =?, `periodofinal` =? WHERE `Codigo`=?";
            $stmt = parent::prepare($sql);
            parent::beginTransaction();
//            print $sql;
            
            $stmt->bindValue(1, $anofim);
            $stmt->bindValue(2, "1");
            $stmt->bindValue(3, $codmapa);
            
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            print "Erro: alteraMapaporCodigo " . $ex->getCode() . " " . $ex->getMessage();
        }
    }
    
    
    public function deleta($codigo) {
        try {
        	
            parent::beginTransaction();
            $stmt = parent::prepare("DELETE FROM `mapa` WHERE `Codigo`=?");
            $stmt->bindValue(1, $codigo);
            $stmt->execute();
            parent::commit();
            
        } catch (PDOException $ex) {
            parent::rollback();
            return $ex->getMessage();
            print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }

    public function lista($anobase) {
        try {
        	$sql="SELECT * FROM mapa m WHERE m.anoinicio<=".$anobase."   and (m.anofim>".$anobase." or m.anofim is null) ORDER BY m.Codigo";
            $stmt = parent::query($sql);
                        
           
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro lista mapadao: " . $ex->getMessage();
        }
    }

    public function listadisctinct() {
        try {
            $stmt = parent::query("SELECT DISTINCT * FROM `mapa`");
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscamapadocumento($codigo,$anobase) {
        try {
            $sql = "SELECT * FROM `mapa` WHERE `CodDocumento` = :codigo and (anofim is null or anofim>:ano) 
            and ( anoinicio<=:ano) 
             ORDER BY codPerspectiva";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo,':ano' => $anobase));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscamapa($codigo) {
        try {
            $sql = "SELECT * FROM `mapa` WHERE `Codigo` = :codigo ";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }
    
    
    
    public function buscamapaporObjetivoDoc($codobjetivo,$codocumento) {
        try {
            $sql = "SELECT * FROM `mapa` WHERE `CodObjetivoPDI` = :codigo and codDocumento=:coddoc";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codobjetivo,':coddoc' => $codocumento));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }
    

    public function buscamapaordem($ordem) {
        try {
            $sql = "SELECT * FROM `mapa` WHERE `Ordem` =:codigo ORDER BY `Objetivo`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $ordem));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

//     public function buscamapaordemdoc($ordem, $coddoc) {
//         try {
//             $sql = "SELECT * FROM `mapa` m, `documento` d WHERE `Ordem` =:codigo" .
//                     " AND d.`codigo` = :coddoc" .
//                     " AND m.`CodDocumento` = d.`codigo`" .
//                     " ORDER BY `codObjetivoPDI`";
//             $stmt = parent::prepare($sql);
//             $stmt->execute(array(':codigo' => $ordem, ':coddoc' => $coddoc));
//             return $stmt;
//         } catch (PDOException $ex) {
//             echo "Erro: " . $ex->getMessage();
//         }
//     }

    
  public function isMapaDocumentoPDI($codmapa,$anobase) {
    	try {
    		$sql = "SELECT d.tipo,d.nome,d.codiog FROM `mapa` m, `documento` d WHERE m.codigo=:codmapa" .
      		" AND m.`CodDocumento` = d.`codigo` and m.anoinicio<=:ano and (m.anofim>:ano or m.anofim is null)" ;
    		
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array( ':codmapa' => $codmapa,':ano'=>$anobase));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro: " . $ex->getMessage();
    	}
    }
    
    public function buscaMapaDocumentoPerspectivaOrdemObjetivo($codDocumento, $codPerspectiva, $ordemObjetivo) {
    	try {
    		$sql = "SELECT * FROM `mapa` m, `documento` d WHERE m.`codPerspectiva` =:codper AND d.`codigo` = :coddoc" .
      		" AND m.`Ordem` =:ordemo" .
      		" AND m.`CodDocumento` = d.`codigo`" .
      		" ORDER BY `codObjetivoPDI`";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':ordemo' => $ordemObjetivo, ':coddoc' => $codDocumento, ':codper' => $codPerspectiva));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro: " . $ex->getMessage();
    	}
    }
    
     public function buscaMapaDocumentoAno($ano) {
    	try {
    		$sql = "SELECT * FROM `mapa` m, `documento` d WHERE d.anoinicial<=:ano AND d.anofinal >= :ano" .
      		" AND m.`CodDocumento` = d.`codigo` and m.anoinicio<=:ano and (m.anofim>:ano or m.anofim is null)" .
      		" ORDER BY `codObjetivoPDI`";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':ano' => $ano));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro: " . $ex->getMessage();
    	}
    } 
    
    
    
//     public function buscamapaordemperspectivadoc($ordemPerspectiva, $coddoc) {
//     	try {
//     		$sql = "SELECT * FROM `mapa` m, `documento` d WHERE m.`ordemPersp` =:codigo" .
//       		" AND d.`codigo` = :coddoc" .
//       		" AND m.`CodDocumento` = d.`codigo`" .
//       		" ORDER BY `codPerspectiva`";
//     		$stmt = parent::prepare($sql);
//     		$stmt->execute(array(':codigo' => $ordemPerspectiva, ':coddoc' => $coddoc));
//     		return $stmt;
//     	} catch (PDOException $ex) {
//     		echo "Erro: " . $ex->getMessage();
//     	}
//     }
    
    public function buscaMapaObjetivoPerspectivaPorDocumento($codObjetivo, $codPerspectiva, $codDocumento,$anobase) {
    	try {
    		$sql = "SELECT * FROM `mapa` m WHERE m.CodDocumento = :coddoc AND m.CodObjetivoPDI = :codob AND m.codPerspectiva = :codper AND (m.anoinicio<=:ano) and (m.anofim is NULL or m.anofim>:ano)";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':coddoc' => $codDocumento,':codob' => $codObjetivo, ':codper' => $codPerspectiva,':ano'=>$anobase));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro: " . $ex->getMessage();
    	}
    }
    
    public function buscaMapaObjetivoPerspectiva($codObjetivo, $codPerspectiva) {
    	try {
    		$sql = "SELECT * FROM `mapa` m WHERE m.CodDocumento = :coddoc AND m.CodObjetivoPDI = :codob";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codob' => $codObjetivo, ':codper' => $codPerspectiva));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro: " . $ex->getMessage();
    	}
    }
    
    public function buscaMapaByUnidadeDocumento($codDocumento, $propmapa) {
    	try {
    		$sql = "SELECT m.*, doc.nome as nomedocumento, ob.Objetivo as nomeobjetivo, pers.nome as nomeperspectiva FROM mapa m JOIN documento doc ON(m.CodDocumento = doc.codigo) JOIN objetivo ob ON(m.codObjetivoPDI = ob.Codigo) JOIN perspectiva pers ON(m.codPerspectiva = pers.codPerspectiva) WHERE m.CodDocumento = :codigo AND m.PropMapa = :codigo2 ORDER BY m.codPerspectiva, m.codObjetivoPDI";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codigo' => $codDocumento, ':codigo2' => $propmapa));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro: " . $ex->getMessage();
    	}
    }
    
 public function buscaMapaByUnidadeDocumentoOuCalendario( $codDocumento, $propmapa) {
   	try {
    		
    		$sql = "SELECT m.*, doc.nome as nomedocumento, ob.Objetivo as nomeobjetivo, pers.nome as nomeperspectiva FROM mapa m JOIN documento doc ON(m.CodDocumento = doc.codigo) JOIN objetivo ob ON(m.codObjetivoPDI = ob.Codigo) JOIN perspectiva pers ON(m.codPerspectiva = pers.codPerspectiva) WHERE m.PropMapa = :codigo2 AND m.CodDocumento = :coddoc ORDER BY m.codPerspectiva, m.codObjetivoPDI";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codigo2' => $propmapa, ':coddoc' => $codDocumento));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro  buscaMapaByUnidadeDocumentoOuCalendario: " . $ex->getMessage();
    	}
    }
    public function buscaMapaByUnidadeDocumentoOuCalendario1( $codDocumento,$propmapa,$anobase ) {
   
    	
    try {
    		$sql = "SELECT m.`Codigo`, m.`CodDocumento`, m.`codPerspectiva`, m.`codObjetivoPDI`, m.`PropMapa`, 
    		doc.nome as nomedocumento, ob.Objetivo as nomeobjetivo, pers.nome as nomeperspectiva 
    		 FROM mapa m    		  		    				
    		 JOIN documento doc ON (m.CodDocumento = doc.codigo ) 
    		 JOIN objetivo ob ON(m.codObjetivoPDI = ob.Codigo) 
    		 JOIN perspectiva pers ON(m.codPerspectiva = pers.codPerspectiva)
    		 WHERE m.PropMapa = :codigo2 
    		 aND m.CodDocumento = :coddoc  AND (m.anofim is NULL or m.anofim>:ano) and ( m.anoinicio <=:ano) 
         	
    		 union select m1.`Codigo`, m1.`CodDocumento`, m1.`codPerspectiva`, m1.`codObjetivoPDI`, m1.`PropMapa`,doc1.nome as nomedocumento, 
    		 ob1.Objetivo as nomeobjetivo, pers1.nome as nomeperspectiva 
    		 FROM mapa m1 JOIN documento doc1 ON(m1.CodDocumento = doc1.codigo) 
    		 JOIN objetivo ob1 ON(m1.codObjetivoPDI = ob1.Codigo) JOIN perspectiva pers1 ON(m1.codPerspectiva = pers1.codPerspectiva) 
    		 inner join mapaindicador mi on mi.codMapa=m1.`Codigo` 
    		 WHERE mi.`PropIndicador` = :codigo2 and doc1.anoinicial<=:ano AND doc1.anofinal >= :ano 
    		 and doc1.CodUnidade=938 AND (m1.anofim is NULL or m1.anofim>:ano) and (m1.anoinicio<=:ano) ";
    		
    		
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codigo2' => $propmapa, ':coddoc' => $codDocumento,':ano'=>$anobase));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro  buscaMapaByUnidadeDocumentoOuCalendario1: " . $ex->getMessage();
    	}
    }
    
    public function buscaMapaByUnidadeDocumentoOuCalendario11( $codDocumento,$propmapa,$anobase ) {
    	 
    	 
    	try {
    		$sql = "SELECT m.`Codigo`, mi.codIndicador, m.`CodDocumento`, m.`codPerspectiva`, m.`codObjetivoPDI`, m.`PropMapa`,
    		doc.nome as nomedocumento, ob.Objetivo as nomeobjetivo, pers.nome as nomeperspectiva
    		 FROM mapaindicador mi,mapa m
    		 JOIN documento doc ON (m.CodDocumento = doc.codigo )
    		 JOIN objetivo ob ON(m.codObjetivoPDI = ob.Codigo)
    		 JOIN perspectiva pers ON(m.codPerspectiva = pers.codPerspectiva)
    		 WHERE m.PropMapa = :codigo2 AND mi.codMapa=m.codigo
    		 aND m.CodDocumento = :coddoc  AND (m.anofim is NULL or m.anofim>:ano) and ( m.anoinicio <=:ano)
    
    		 union select m1.`Codigo`,mi.codIndicador, m1.`CodDocumento`, m1.`codPerspectiva`, m1.`codObjetivoPDI`, m1.`PropMapa`,doc1.nome as nomedocumento,
    		 ob1.Objetivo as nomeobjetivo, pers1.nome as nomeperspectiva
    		 FROM mapa m1 JOIN documento doc1 ON(m1.CodDocumento = doc1.codigo)
    		 JOIN objetivo ob1 ON(m1.codObjetivoPDI = ob1.Codigo) JOIN perspectiva pers1 ON(m1.codPerspectiva = pers1.codPerspectiva)
    		 inner join mapaindicador mi on mi.codMapa=m1.`Codigo`
    		 WHERE mi.`PropIndicador` = :codigo2 and doc1.anoinicial<=:ano AND doc1.anofinal >= :ano
    		 and doc1.CodUnidade=938 AND (m1.anofim is NULL or m1.anofim>:ano) and (m1.anoinicio<=:ano) ";
    
    
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codigo2' => $propmapa, ':coddoc' => $codDocumento,':ano'=>$anobase));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro  buscaMapaByUnidadeDocumentoOuCalendario1: " . $ex->getMessage();
    	}
    }
    
    
    public function buscaMapaByUnidadeDocumentoOuCalendario111( $codDocumento,$propmapa,$anobase ) {//Buscar indicadores painel de medições
        
        
        try {
            $sql = "SELECT m.`Codigo`, mi.codIndicador, m.`CodDocumento`, m.`codPerspectiva`, m.`codObjetivoPDI`, m.`PropMapa`,
    		doc.nome as nomedocumento, ob.Objetivo as nomeobjetivo, pers.nome as nomeperspectiva
    		 FROM mapaindicador mi,mapa m
    		 JOIN documento doc ON (m.CodDocumento = doc.codigo )
    		 JOIN objetivo ob ON(m.codObjetivoPDI = ob.Codigo)
    		 JOIN perspectiva pers ON(m.codPerspectiva = pers.codPerspectiva)
    		 WHERE m.PropMapa = :codigo2 AND mi.codMapa=m.codigo
    		 aND m.CodDocumento = :coddoc  AND (mi.anoinicial<=:ano AND (mi.anofinal>:ano OR  mi.anofinal IS NULL) ) 
                
    		 union select m1.`Codigo`,mi.codIndicador, m1.`CodDocumento`, m1.`codPerspectiva`, m1.`codObjetivoPDI`, m1.`PropMapa`,doc1.nome as nomedocumento,
    		 ob1.Objetivo as nomeobjetivo, pers1.nome as nomeperspectiva
    		 FROM mapa m1 JOIN documento doc1 ON(m1.CodDocumento = doc1.codigo)
    		 JOIN objetivo ob1 ON(m1.codObjetivoPDI = ob1.Codigo) JOIN perspectiva pers1 ON(m1.codPerspectiva = pers1.codPerspectiva)
    		 inner join mapaindicador mi on mi.codMapa=m1.`Codigo`
    		 WHERE mi.`PropIndicador` = :codigo2 and doc1.anoinicial<=:ano AND doc1.anofinal >= :ano
    		 and doc1.CodUnidade=938 AND (mi.anoinicial<=:ano AND (mi.anofinal>:ano OR  mi.anofinal IS NULL) ) ";
            
            
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo2' => $propmapa, ':coddoc' => $codDocumento,':ano'=>$anobase));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro  buscaMapaByUnidadeDocumentoOuCalendario1: " . $ex->getMessage();
        }
    }
    
  

    public function buscaMapaDocumentoPerspectiva($codDocumento, $codPerspectiva) {
    	try {
    		$sql = "SELECT * FROM `mapa` m WHERE m.`codPerspectiva` =:codper AND m.`CodDocumento` = :coddoc "  ;
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':coddoc' => $codDocumento, ':codper' => $codPerspectiva));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro: " . $ex->getMessage();
    	}
    }
    
      public function buscaMapaByDocumento($codDocumento) {
    	try {
    		
    		$sql = "SELECT m.*, doc.nome as nomedocumento,ob.codigo, ob.Objetivo as nomeobjetivo, pers.nome as nomeperspectiva FROM mapa m JOIN documento doc ON(m.CodDocumento = doc.codigo) JOIN objetivo ob ON(m.codObjetivoPDI = ob.Codigo) JOIN perspectiva pers ON(m.codPerspectiva = pers.codPerspectiva) WHERE m.CodDocumento = :coddoc ORDER BY m.codPerspectiva, m.codObjetivoPDI";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array( ':coddoc' => $codDocumento));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro  buscaMapaByDocumento: " . $ex->getMessage();
    	}
    }
    
    
 public function buscaDadosMapaPorCodigo($codmapa) {
        try {
            $sql = "SELECT p.codPerspectiva,codObjetivoPDI,d.`codigo`,d.`nome`,`Objetivo`, p.`nome` as perspect ".
            " FROM `documento` d inner join mapa m on d.codigo=`CodDocumento` ".
            " inner join objetivo o on `codObjetivoPDI`=o.`Codigo` ".
            " inner join perspectiva p on m.`codPerspectiva` = p.`codPerspectiva` ".
            " WHERE  m.codigo=:codmapa ".
            " order by p.codPerspectiva,o.Codigo ";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codmapa' => $codmapa));
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro buscaDocumentoUnidadePrincipal: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }
    
    public function fechar() {
        PDOConnectionFactory::Close();
    }
    

    public function inserePlanoDeAcao($codArquivo, $comentario, $arquivo, $situacao,$codunidade,$anobase) {
    	try {
    		$sql = "INSERT INTO `planoacaoPDU` (`comentario`, `arquivo`, `unidade`, `anobase`) VALUES (?,?,?,?)";
    		$stmt = parent::prepare($sql);
    		parent::beginTransaction();
    		$stmt->bindValue(1, $comentario);
    		$stmt->bindValue(2, $arquivo);
    		$stmt->bindValue(3, $codunidade);
    		$stmt->bindValue(4, $anobase);    
    		$stmt->execute();
    		parent::commit();
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: insere mapa:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
    	}
    }
    
    public function verificarPlanoAcao($codunidade,$ano) {
        try {
            $sql = "SELECT `unidade`,`cadastro`,`anobase`,`arquivo`,`comentario` FROM planoacaoPDU AS p1 WHERE cadastro = (SELECT MAX(cadastro) FROM planoacaoPDU AS p2 WHERE p1.unidade = p2.unidade AND p2.anobase=:ano) AND p1.anobase=:ano AND p1.unidade=:unidade
ORDER BY cadastro "  ;
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':unidade' => $codunidade,':ano' => $ano));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }
    
    public function buscaPlanoAcao($ano) {
        try {
            $sql = "SELECT `unidade`,u.NomeUnidade,`cadastro`,`anobase`,`arquivo`,`comentario` FROM planoacaoPDU AS p1 
INNER JOIN unidade u ON u.CodUnidade=p1.unidade 
WHERE cadastro = (SELECT MAX(cadastro) FROM planoacaoPDU AS p2 WHERE p1.unidade = p2.unidade AND p2.anobase=:ano) AND p1.anobase=:ano ORDER BY cadastro";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':ano' => $ano));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaArquivoPduAtivo($codDocumento) {
    	try {
    		$sql = "SELECT * FROM `arquivoPDU` a WHERE a.`coddocumento` =:coddoc AND a.`situacao` = 'A' "  ;
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':coddoc' => $codDocumento));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro: " . $ex->getMessage();
    	}
    }    
}

?>
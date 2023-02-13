<?php
class DocumentoDAO extends PDOConnectionFactory {
    public $conex = null;
    
    private $mysqli;
    // constructor
    public function __construct() {
    	 
    	$this->mysqli = new mysqli(parent::getHost(), parent::getUser(), parent::getSenha(), parent::getDb());
    	        $this->mysqli->set_charset('utf8');
    	
    	// Caso algo tenha dado errado, exibe uma mensagem de erro
    	if (mysqli_connect_errno()) {
    		printf("Conexão mysqli falhou: %s\n", mysqli_connect_error());die;
    		exit();
    	}else{
    		//echo "conectou";
    	}
    	
    }
    
       public function insere(Documento $documento) {
    $unidade=$documento->getUnidade()->getCodunidade();
    $nome=$documento->getNome();
    $anoinicial=$documento->getAnoInicial();
    $anofinal=$documento->getAnoFinal();
    $situacao="A";
    $missao=$documento->getMissao();
    $visao=$documento->getVisao();
   // $anexo=$documento->getAnexo();
   	$tipo=$documento->getTipo();
    $nomearq=$documento->getNomearq();
    $tipoarq="application/octet-stream";//$documento->getTipoarq();
    $tamarq=$documento->getTamarq();
         echo "insere<br>";
    
    
   $query = "INSERT INTO `documento` ( `CodUnidade`, `nome`, `anoinicial`,`anofinal`,`situacao`,`missao`,`visao`,`anexo`,`tipo`,`nomearq`,`tipoarq`,`tamarq`)
      VALUES ('$unidade','$nome','$anoinicial','$anofinal','$situacao','$missao','$visao',NULL,'$tipo','$nomearq','$tipoarq','$tamarq')";
                        
                        $query = $this->mysqli->query($query);
         	printf("Affected rows (INSERT): %d\n", $this->mysqli->affected_rows);

                        mysqli_close( $this->mysqli);
        
        

        
             
        
    }
    
    
/*
    public function insere(Documento $documento) {
    $unidade=$documento->getUnidade()->getCodunidade();
    $nome=$documento->getNome();
    $anoinicial=$documento->getAnoInicial();
    $anofinal=$documento->getAnoFinal();
    $situacao="A";
    $missao=$documento->getMissao();
    $visao=$documento->getVisao();
    $anexo=$documento->getAnexo();
   	$tipo=$documento->getTipo();
    $nomearq=$documento->getNomearq();
    $tipoarq="application/octet-stream";//$documento->getTipoarq();
    $tamarq=$documento->getTamarq();
   
                        $query = "INSERT INTO `documento` ( `CodUnidade`, `nome`, `anoinicial`,".
                            "`anofinal`,`situacao`,`missao`,`visao`,`anexo`,`tipo`,`nomearq`,`tipoarq`,`tamarq`)".
                            " VALUES ('$unidade','$nome','$anoinicial','$anofinal','$situacao','$missao','$visao','$anexo','$tipo','$nomearq','$tipoarq','$tamarq')";

                        $query = $this->mysqli->query($query);
         //  	printf("Affected rows (INSERT): %d\n", $this->mysqli->affected_rows);
         //  $linhas=$mysqli->affected_rows;
                        
                        mysqli_close( $this->mysqli);
        
        
        return $linhas;
                        
        
             
        
    }*/

    public function altera(Documento $documento) {
      $nome=$documento->getNome();
      $anoinicial=$documento->getAnoInicial();
      $anofinal=$documento->getAnoFinal();
      $missao=$documento->getMissao();
      $visao=$documento->getVisao();
     // $anexo=$documento->getAnexo();
      $nomearq=$documento->getNomearq();
      $tamarq=$documento->getTamarq();

      $query="UPDATE `documento` SET `nome`='$nome', `anoinicial`='$anoinicial', `anofinal`=
      '$anofinal', `situacao`='A', `missao`='$missao', `visao`='$visao',anexo=NULL,`nomearq`='$nomearq',
      `tipoarq`='application/octet-stream',`tamarq`='$tamarq' WHERE `codigo`=".$documento->getCodigo();
      // But, this will affect $mysqli->real_escape_string();
           $query = $this->mysqli->query($query);
         // $linhas=$this->$mysqli->affected_rows;
                       mysqli_close($this->mysqli);
                       
    }

    public function deleta($codigo) {
        try {
            parent::beginTransaction();
            $stmt = parent::prepare("DELETE FROM `documento` WHERE `codigo`=?");
            $stmt->bindValue(1, $codigo);
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            print "Erro deleta: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }

    public function lista($anobase) {
        try {
            $stmt = parent::prepare("SELECT `codigo`, `CodUnidade`, `nome`, `anoinicial`, `anofinal`, `situacao`, `missao`, 
            `visao`, `nomearq`, `tipoarq`, `tamarq`,  `tipo` 
            FROM `documento` where  `anoinicial`<=? and `anofinal`>=? order by nome");
            $stmt->bindValue(1, $anobase);
            $stmt->bindValue(2, $anobase);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro lista docdao: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }


	public function buscadocumentoporunidadePeriodoSemPDI($codUnidade,$anogestao) {
	    try {
    		$sql = "SELECT `codigo`, `CodUnidade`, `nome`, `anoinicial`, `anofinal`, `situacao`, `missao`, `visao`, `nomearq`,
    		 `tipoarq`, `tamarq`,  `tipo` FROM `documento` 
    		 WHERE `CodUnidade`=:codigo AND `situacao`='A'
      		 and anoinicial<=:ano and anofinal>=:ano";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codigo' => $codUnidade,':ano'=>$anogestao));
    		return $stmt;
    	} catch (PDOException $ex) {
    		print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
    	}
    }
    

      
    
    
    public function painelSituacaoIniciativas($coddoc,$codunidade,$codperiodo,$codcal,$anobase) {
	    try {
	    	if ($codunidade==938){
    		$sql = "select  ri.situacao as sit,count(distinct c.codiniciativa) as qtsit
from unidade u left join documento d on d.CodUnidade=u.CodUnidade
 join `mapa` m on m.`CodDocumento`=d.`codigo` and m.anoinicio<=:ano and (m.anofim>:ano or m.anofim is null)
 join `mapaindicador` mi on  m.`Codigo`=mi.`codMapa`  and mi.anoinicial<=:ano and (mi.anofinal>:ano or mi.anofinal is null)
 join indicador i on mi.codIndicador=i.codigo
 join indic_iniciativa ii on mi.codigo=ii.CodMapaInd and ii.anoinicial<=:ano and (ii.anofinal>:ano or ii.anofinal is null)
 join  iniciativa c on c.codiniciativa=ii.codiniciativa
 join resultIniciativa ri on 	ri.codiniciativa=c.codiniciativa and ri.codcalendario=:codcal and ri.periodo=:periodo
where  d.codunidade=:unid  and mi.tipoassociado=7   group by 1 order by 1";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':unid'=>$codunidade,':codcal'=>$codcal,':periodo'=>$codperiodo,':ano'=>$anobase));
	    	}else{
	    	$sql = "select  ri.situacao as sit,count(distinct c.codiniciativa) as qtsit
from unidade u left join documento d on d.CodUnidade=u.CodUnidade
 join `mapa` m on m.`CodDocumento`=d.`codigo` and m.anoinicio<=:ano and (m.anofim>:ano or m.anofim is null)
 join `mapaindicador` mi on  m.`Codigo`=mi.`codMapa`  and mi.anoinicial<=:ano and (mi.anofinal>:ano or mi.anofinal is null)
 join indicador i on mi.codIndicador=i.codigo
 join indic_iniciativa ii on mi.codigo=ii.CodMapaInd and ii.anoinicial<=:ano and (ii.anofinal>:ano or ii.anofinal is null)
 join  iniciativa c on c.codiniciativa=ii.codiniciativa
 join resultIniciativa ri on 	ri.codiniciativa=c.codiniciativa and ri.codcalendario=:codcal and ri.periodo=:periodo
 where d.codunidade=:unid or (d.codigo =:doc and (mi.propindicador=:unid
  and (mi.tipoassociado=7 or mi.tipoAssociado is null or mi.tipoAssociado='PDU')) )
group by 1 order by 1";	
	    	$stmt = parent::prepare($sql);
    		$stmt->execute(array(':doc'=>$coddoc,':unid'=>$codunidade,':codcal'=>$codcal,':periodo'=>$codperiodo,':ano'=>$anobase));
	    	}
    		
    		return $stmt;
    	} catch (PDOException $ex) {
    		print "Erro na consulta painelSituacaoIniciativas: " . $ex->getCode() . " " . $ex->getMessage();
    	}
    }
    
 public function painelFatoresIniciativas($coddoc,$codunidade,$codcal,$periodo,$anobase) {
 
 	
	    try {
	    	if ($codunidade==938){
	    $sql = "  select distinct ri.situacao,
 sum(pfcapacit) as capacit, sum(pfrecti) as recti,sum(pfinfraf) as infra,sum(pfrecf) as recfinanc,sum(pfplanj) as planeja 	
	from unidade u left join documento d on d.CodUnidade=u.CodUnidade
 join `mapa` m on m.`CodDocumento`=d.`codigo` and m.anoinicio<=:ano and (m.anofim>:ano or m.anofim is null)
 join `mapaindicador` mi on  m.`Codigo`=mi.`codMapa` and mi.anoinicial<=:ano and (mi.anofinal>:ano or mi.anofinal is null)
 join indicador i on mi.codIndicador=i.codigo
 join indic_iniciativa ii on mi.codigo=ii.CodMapaInd and ii.anoinicial<=:ano and (ii.anofinal>:ano or ii.anofinal is null)
 join  iniciativa c on c.codiniciativa=ii.codiniciativa 
 join resultIniciativa ri on 	ri.codiniciativa=c.codiniciativa and ri.codcalendario=:codcal and ri.periodo=:periodo
where  d.codunidade=:unid  and mi.tipoassociado=7   group by 1 order by 1";
	    	 $stmt = parent::prepare($sql);
    		$stmt->execute(array(':unid'=>$codunidade,':periodo'=>$periodo,':codcal'=>$codcal,':ano'=>$anobase));
	    	}    	
	    else{	
    		$sql = "  select distinct ri.situacao,
 sum(pfcapacit) as capacit, sum(pfrecti) as recti,sum(pfinfraf) as infra,sum(pfrecf) as recfinanc,sum(pfplanj) as planeja 
from unidade u left join documento d on d.CodUnidade=u.CodUnidade
 join `mapa` m on m.`CodDocumento`=d.`codigo` and m.anoinicio<=:ano and (m.anofim>:ano or m.anofim is null)
 join `mapaindicador` mi on  m.`Codigo`=mi.`codMapa` and mi.anoinicial<=:ano and (mi.anofinal>:ano or mi.anofinal is null)
 join indicador i on mi.codIndicador=i.codigo
 join indic_iniciativa ii on mi.codigo=ii.CodMapaInd  and ii.anoinicial<=:ano and (ii.anofinal>:ano or ii.anofinal is null)
 join  iniciativa c on c.codiniciativa=ii.codiniciativa 
 join resultIniciativa ri on ri.codiniciativa=c.codiniciativa and ri.codcalendario=:codcal and ri.periodo=:periodo
where d.codunidade=:unid or (d.codigo =:doc and (mi.propindicador=:unid
  and (mi.tipoassociado=7 or mi.tipoAssociado is null or mi.tipoAssociado='PDU')) )                 
group by 1
order by 1";
    		 $stmt = parent::prepare($sql);
    		$stmt->execute(array(':doc'=>$coddoc,':unid'=>$codunidade,':periodo'=>$periodo,':codcal'=>$codcal,':ano'=>$anobase));
	    }
    		
	   
    		return $stmt;
    	} catch (PDOException $ex) {
    		print "Erro na consulta painelFatoresIniciativas: " . $ex->getCode() . " " . $ex->getMessage();die;
    	}
    }		
    		
    
	public function painelSituacaoIndicadores($coddoc,$codunidade,$codcal,$periodo,$anobase) {
	    try {
	    	if ($codunidade==938){
	    	$sql="select definirSemaforo(i1.interpretacao,r1.meta_atingida,a1.meta) as sit, count(*) as qtsit
from documento d1 
join `mapa` m1 on m1.`CodDocumento`=d1.`codigo` and m1.anoinicio<=:ano and (m1.anofim>:ano or m1.anofim is null)
join `mapaindicador` mi1 on m1.`Codigo`=mi1.`codMapa` and mi1.anoinicial<=:ano and (mi1.anofinal>:ano or mi1.anofinal is null)
join indicador i1 on mi1.codIndicador=i1.codigo and mi1.codindicador=i1.codigo 
join `meta` a1 on a1.`CodMapaInd`=mi1.`codigo` and a1.codcalendario=:calend and a1.anoinicial<=:ano AND (a1.anofinal>:ano OR  a1.anofinal IS NULL)
left join `resultados_pdi` r1 on r1.`CodMeta`=a1.`Codigo` and r1.periodo=:periodo
where  d1.codunidade =:codunidade   and mi1.tipoassociado=7 
group by 1 order by 1";
    		 $stmt = parent::prepare($sql);
	    	$stmt->execute(array(':codunidade' => $codunidade,':calend'=>$codcal,':periodo'=>$periodo,':ano'=>$anobase));
	    	
	    	}else{
    		$sql = "select definirSemaforo(i1.interpretacao,r1.meta_atingida,a1.meta) as sit, count(*) as qtsit
from documento d1 
join `mapa` m1 on m1.`CodDocumento`=d1.`codigo` and m1.anoinicio<=:ano and (m1.anofim>:ano or m1.anofim is null)
join `mapaindicador` mi1 on m1.`Codigo`=mi1.`codMapa` and mi1.anoinicial<=:ano and (mi1.anofinal>:ano  or mi1.anofinal is null)
join indicador i1 on mi1.codIndicador=i1.codigo and mi1.codindicador=i1.codigo 
join `meta` a1 on a1.`CodMapaInd`=mi1.`codigo` and a1.codcalendario=:calend and   a1.anoinicial<=:ano AND (a1.anofinal>:ano OR  a1.anofinal IS NULL)
left join `resultados_pdi` r1 on r1.`CodMeta`=a1. `Codigo` and r1.periodo=:periodo
where d1.codunidade=:unid or (d1.codigo =:doc and (mi1.propindicador=:unid and 
(mi1.tipoassociado=7 or mi1.tipoAssociado is null or mi1.tipoAssociado='PDU')) )
group by 1 order by 1";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':doc' => $coddoc,':unid'=>$codunidade,':calend'=>$codcal,':periodo'=>$periodo,':ano'=>$anobase));
    		
	    	}
    		
    		return $stmt;
    	} catch (PDOException $ex) {
    		print "Erro na consulta painelSituacaoIndicadores: " . $ex->getCode() . " " . $ex->getMessage();die;
    	}
    }

public function raa_tabelaindicador($coddoc,$codunidade,$periodo,$anobase){
	
 try {
	    	
    		$sql = "select i1.nome,a1.meta, r1.meta_atingida as resultado,
    		percentualAlcancado(i1.interpretacao,r1.meta_atingida,a1.meta) as palcance,ic.nome as nomeiniciativa, i1.codigo ,ic.codIniciativa
from documento d1 
join `mapa` m1 on m1.`CodDocumento`=d1.`codigo` and m1.anoinicio<=:ano and (m1.anofim>:ano or m1.anofim is null)
join `mapaindicador` mi1 on m1.`Codigo`=mi1.`codMapa` and mi1.anoinicial<=:ano and (mi1.anofinal>:ano  or mi1.anofinal is null)
join indicador i1 on mi1.codIndicador=i1.codigo and mi1.codindicador=i1.codigo 
join `meta` a1 on a1.`CodMapaInd`=mi1.`codigo` and a1.ano=:ano and   a1.anoinicial<=:ano AND (a1.anofinal>:ano OR  a1.anofinal IS NULL)
left join `resultados_pdi` r1 on r1.`CodMeta`=a1. `Codigo` and r1.periodo=:periodo

JOIN indic_iniciativa ii on ii.codmapaind=mi1.codigo and ii.anoinicial<=:ano and (ii.anofinal>=:ano or ii.anofinal is null )
join iniciativa ic on ic.codiniciativa=ii.codiniciativa


where 
 d1.codunidade=:unid or (d1.codigo =:doc and (mi1.propindicador=:unid and 
 (mi1.tipoassociado=7 or mi1.tipoAssociado is null or mi1.tipoAssociado='PDU')) )
-- (d1.codigo =:doc and mi1.propindicador=:unid and 
-- mi1.tipoassociado=7) or (d1.codunidade=:unid  and  mi1.tipoAssociado='PDU' and mi1.tipoAssociado is null) 
-- group by percentualAlcancado(i1.interpretacao,r1.meta_atingida,a1.meta),i1.codigo,ic.codIniciativa
group by 4,6,7,1,5,2,3
order by 4 DESC";

//order by 1";
    		//-- group by 1,2,3 ,4,5 
    		
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':doc' => $coddoc,':unid'=>$codunidade,':periodo'=>$periodo,':ano'=>$anobase));
    		
	    	
    		return $stmt;
    	} catch (PDOException $ex) {
    		print "Erro na consulta raa_tabelaindicador: " . $ex->getCode() . " " . $ex->getMessage();
    	}
}
    
public function painelIndicadoresPorSituacao($coddoc,$codunidade,$codcal,$periodo) {
	    try {
	    	if ($codunidade==938){
	    	$sql="select definirSemaforo(i1.interpretacao,r1.meta_atingida,a1.meta) as sit, i1.nome,a1.meta,r1.meta_atingida as resultado
from documento d1 
join `mapa` m1 on m1.`CodDocumento`=d1.`codigo` and m1.anoinicio<=:ano and (m1.anofim>:ano or m1.anofim is null)
join `mapaindicador` mi1 on m1.`Codigo`=mi1.`codMapa` and mi1.anoinicial<=:ano and (mi1.anofinal>:ano  or mi1.anofinal is null)
join indicador i1 on mi1.codIndicador=i1.codigo and mi1.codindicador=i1.codigo 
join `meta` a1 on a1.`CodMapaInd`=mi1.`codigo` and a1.codcalendario=:calend and   a1.anoinicial<=:ano AND (a1.anofinal>:ano OR  a1.anofinal IS NULL)
left join `resultados_pdi` r1 on r1.`CodMeta`=a1. `Codigo` and r1.periodo=:periodo
where  d1.codigo =:doc   and mi1.tipoassociado=7 
 order by 2,4";
	    	$stmt = parent::prepare($sql);
	    	$stmt->execute(array(':doc' => $coddoc,':calend'=>$codcal,':periodo'=>$periodo));
	    	
	    	}else{
    		$sql = "select definirSemaforo(i1.interpretacao,r1.meta_atingida,a1.meta) as sit,  i1.nome,a1.meta,r1.meta_atingida as resultado
from documento d1 
join `mapa` m1 on m1.`CodDocumento`=d1.`codigo` and m1.anoinicio<=:ano and (m1.anofim>:ano or m1.anofim is null)
join `mapaindicador` mi1 on m1.`Codigo`=mi1.`codMapa` and mi1.anoinicial<=:ano and (mi1.anofim>:ano  or mi1.anofim is null)
join indicador i1 on mi1.codIndicador=i1.codigo and mi1.codindicador=i1.codigo 
join `meta` a1 on a1.`CodMapaInd`=mi1.`codigo` and a1.codcalendario=:calend and   a1.anoinicial<=:ano AND (a1.anofinal>:ano OR  a1.anofinal IS NULL)
left join `resultados_pdi` r1 on r1.`CodMeta`=a1. `Codigo` and r1.periodo=:periodo
where d1.codunidade=:unid or (d1.codigo =:doc and (mi1.propindicador=:unid and 
(mi1.tipoassociado=7 or mi1.tipoAssociado is null or mi1.tipoAssociado='PDU')) )
 order by 2,4";
    		
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':doc' => $coddoc,':unid'=>$codunidade,':calend'=>$codcal,':periodo'=>$periodo));
    		
	    	}
    		
    		return $stmt;
    	} catch (PDOException $ex) {
    		print "Erro na consulta painelIndicadoresPorSituacao: " . $ex->getCode() . " " . $ex->getMessage();
    	}
    }
    
    
    
        public function listanull() {
        try {
            $stmt = parent::query("SELECT `codigo`, `CodUnidade`, `nome`, `anoinicial`, `anofinal`, `situacao`, `missao`, `visao`, `nomearq`, `tipoarq`, `tamarq`,  `tipo` FROM `documento` WHERE `CodDocumento` IS NULL");
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro listanull: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }

    
     public function buscaArquivoDoc($codigo) {
    
            $sql = "SELECT * FROM `documento` WHERE `codigo`=".$codigo;
            return $this->mysqli->query($sql);
            print "Erro buscadocumento: " . $ex->getCode() . " " . $ex->getMessage();
            
          
            
        
    }
    
  public function buscaDocumentoUnidadePrincipal($anobase,$codunidade) {
        try {
            $sql = "SELECT p.codPerspectiva,codObjetivoPDI,d.`codigo`,d.`nome`,`Objetivo`, p.`nome`,d.codunidade, anoinicial, anofinal ".
            " FROM `documento` d inner join mapa m on d.codigo=`CodDocumento` ".
            " inner join objetivo o on `codObjetivoPDI`=o.`Codigo` ".
            " inner join perspectiva p on m.`codPerspectiva` = p.`codPerspectiva` ".
            " WHERE  `anoinicial`<=:ano and  `anofinal`>=:ano and d.codunidade=:codunidade ".
            " order by p.codPerspectiva,o.Codigo ";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':ano' => $anobase,'codunidade'=>$codunidade));
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro buscaDocumentoUnidadePrincipal: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }
    
    
    public function buscadocumento($codigo) {
        try {
            $sql = "SELECT `codigo`, u.`CodUnidade`, NomeUnidade,`nome`, `anoinicial`, `anofinal`, `situacao`, 
            `missao`, `visao`, `nomearq`, `tipoarq`, `tamarq`,  d.`tipo` 
            FROM `documento` d inner join unidade u on d.codunidade=u.codunidade WHERE `codigo`=:codigo";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro buscadocumento: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }
    
     public function buscadocumentoPrazo($anobase) {
        try {
            $sql = "SELECT `codigo`, `CodUnidade`, `nome`, `anoinicial`, `anofinal`, `situacao`, `missao`, `visao`, `nomearq`, 
            `tipoarq`, `tamarq`,  `tipo` FROM `documento` WHERE `anoinicial`<=:ano and  `anofinal`>=:ano ORDER BY nome";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':ano' => $anobase));
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro buscadocumento: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }
    
 public function buscadocumentoPrazoEUnidade($anobase,$codunidade) {
        try {
            $sql = "SELECT `codigo`, `CodUnidade`, `nome`, `anoinicial`, `anofinal`, `situacao`, `missao`, `visao`, `nomearq`, 
            `tipoarq`, `tamarq`,  `tipo` FROM `documento` WHERE `anoinicial`<=:ano and  `anofinal`>=:ano and codunidade=:unidade";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':ano' => $anobase,':unidade'=>$codunidade));
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro buscadocumentoPrazoEUnidade: " . $ex->getCode() . " " . $ex->getMessage();die;
        }
    }
     public function buscaPeriodoGestao($anobase,$tipo) {
        try {
            $sql = "SELECT *  FROM periodogestao WHERE `anoinicial`<=:ano and  `anofinal`>=:ano and tipo=:tipo";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':ano' => $anobase,':tipo'=>$tipo));
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro buscaPeriodoGestao: " . $ex->getCode() . " " . $ex->getMessage();die;
        }
    }
    

  public function buscaunidadedocumento($codigo){
        try {
            $sql = "SELECT u.`CodUnidade`,`NomeUnidade` FROM `documento` d inner join unidade u ".
            " WHERE d.`codigo`=:codigo".
            " and u.`CodUnidade`=d.`CodUnidade`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
                        print "Erro buscaunidadedocumento: " . $ex->getCode() . " " . $ex->getMessage();

        }
    }

     public function buscaPDI() {
        try {
            $sql = "SELECT `codigo`, `CodUnidade`, `nome`, `anoinicial`, `anofinal`, `situacao`, `missao`,
             `visao`, `nomearq`, `tipoarq`, `tamarq`,  `tipo` FROM `documento` WHERE `CodDocumento` IS NULL ORDER BY `nome` ";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
                        print "Erro buscaPDI: " . $ex->getCode() . " " . $ex->getMessage();
 
        }
    }
    
    
    
    
    public function buscaUnidadeAnogestao($codUnidade,$anogestao){
         //pega pds onde a unidade é proprietária de indicador
         try {
            $sql = " SELECT distinct d.`codigo`, d.`nome`,d.anoinicial,d.anofinal 
                 FROM `documento` d inner join `calendario` c on c.codDocumento=d.codigo 
                 inner join `mapa` m on m.`CodDocumento`=d.`codigo` and m.anoinicio<=:ano and (m.anofim>:ano or m.anofim is null)
                 inner join `mapaindicador` mi on m.`Codigo`=mi.`CodMapa`  and mi.anoinicial<=:ano and (mi.anofim>:ano  or mi.anofim is null) 
                WHERE mi.`PropIndicador`=:codigo and `anoGestao`=:ano";
                $stmt=parent::prepare($sql);// d.`situacao`='A' and
             $stmt->execute(array(':codigo' => $codUnidade,':ano'=>$anogestao));
             return $stmt;
        } catch (PDOException $ex) {
             print "Erro buscaUnidadeAnogestao: " . $ex->getCode() . " " . $ex->getMessage();

        } 
    }
     public function listaIndporDocCalObj($anogestao,$coddocumento,$codunidade,$codobj){
         //pega pds onde a unidade é proprietária de indicador
         try {
            $sql =  "  SELECT DISTINCT mi.`Codigo` , i.`nome`  FROM  `documento` d
 INNER JOIN  `mapa` m ON m.`CodDocumento` = d.`codigo`  and m.anoinicio<=:ano and (m.anofim>:ano or m.anofim is null)
 INNER JOIN mapaindicador mi ON mi.codmapa = m.codigo  and mi.anoinicial<=:ano and (mi.anofim>:ano  or mi.anofim is null) 
 INNER JOIN indicador i ON mi.codindicador = i.codigo
 INNER JOIN meta a ON mi.codigo = a.codmapaind and a.anoinicial<=:ano and (a.anofim>:ano  or a.anofim is null)
 INNER JOIN calendario c ON c.codcalendario = a.codcalendario
 WHERE d.`codigo` =:codigo
 AND c.`anoGestao` =:ano
 AND mi.`PropIndicador` =:prop 
 AND m.codObjetivoPDI=:obj";
             //echo $codunidade."unidade"."-".$coddocumento."---".$anogestao;
             $stmt=parent::prepare($sql);// d.`situacao`='A' and
             $stmt->execute(array(':codigo' => $coddocumento,':ano'=>$anogestao,':prop'=>$codunidade,':obj'=>$codobj));
             return $stmt;
        } catch (PDOException $ex) {
            print "Erro listaIndporDocCalObj: " . $ex->getCode() . " " . $ex->getMessage();
        } 
             
              
    }
    
     public function listaIndporDocCal($anogestao,$coddocumento,$codunidade){
         //pega pds onde a unidade é proprietária de indicador
         try {
            $sql =  "  SELECT DISTINCT mi.`Codigo` , i.`nome`  FROM  `documento` d
 INNER JOIN  `mapa` m ON m.`CodDocumento` = d.`codigo` and m.anoinicio<=:ano and (m.anofim>:ano or m.anofim is null)
 INNER JOIN mapaindicador mi ON mi.codmapa = m.codigo and mi.anoinicial<=:ano AND (mi.anofinal>:ano OR  mi.anofinal IS NULL) 
 INNER JOIN indicador i ON mi.codindicador = i.codigo
 LEFT OUTER JOIN meta a ON mi.codigo = a.codmapaind and  a.anoinicial<=:ano  AND (a.anofinal>:ano OR  a.anofinal IS NULL)
 INNER JOIN calendario c ON c.codcalendario = a.codcalendario
 WHERE d.`codigo` =:codigo
 AND c.`anoGestao` =:ano
 AND mi.`PropIndicador` =:prop ";
             //echo $codunidade."unidade"."-".$coddocumento."---".$anogestao;
             $stmt=parent::prepare($sql);// d.`situacao`='A' and
             $stmt->execute(array(':codigo' => $coddocumento,':ano'=>$anogestao,':prop'=>$codunidade));
             return $stmt;
        } catch (PDOException $ex) {
            print "Erro listaIndporDocCal: " . $ex->getCode() . " " . $ex->getMessage()." listaIndporDocCal";
        } 
             
              
    }
    
    
   public function pedenciasDocumento($coddocumento,$anogestao,$codunidade){
         //pega pds onde a unidade é proprietária de indicador
         try {
            $sql =  "SELECT distinct d.codigo as coddoc,a.periodo,m.Codigo as codmapa, o.`Codigo` as codobj , 
`Objetivo` , mi.`Codigo` AS codmi, i.`nome` ";
 
            if ($anogestao<2022){
                $sql.=" , CASE WHEN a.metrica = 'P' THEN 'Percentual' WHEN a.metrica = 'Q' THEN 'Absoluto' END AS metrica ";
            }  else {
 
                  $sql.=" ,a.Codigo as codmeta";
                }


$sql.=",ii.codmapaind
  FROM `documento` d 
  INNER JOIN `mapa` m ON m.`CodDocumento` = d.`codigo`  and m.anoinicio<=:ano and (m.anofim>=:ano or m.anofim is null)
  INNER JOIN objetivo o ON m.`codObjetivoPDI` = o.`Codigo` 
  LEFT JOIN mapaindicador mi ON mi.codmapa = m.codigo and mi.anoinicial<=:ano AND (mi.anofinal>=:ano OR  mi.anofinal IS NULL)  
  LEFT JOIN indicador i ON mi.codindicador = i.codigo 
  LEFT JOIN meta a ON mi.codigo = a.codmapaind and  a.anoinicial<=:ano  AND (a.anofinal>=:ano OR  a.anofinal IS NULL) and a.ano=:ano
  left JOIN calendario c ON c.codcalendario = a.codcalendario 
  LEFT JOIN indic_iniciativa ii on ii.codmapaind=mi.codigo and ii.anoinicial<=:ano and (ii.anofinal>=:ano or ii.anofinal is null )
  left join iniciativa ic on ic.codiniciativa=ii.codiniciativa
  WHERE d.`codigo` =:codigo 

union
SELECT distinct d1.codigo as coddoc,a1.periodo,m1.Codigo as codmapa, o1.`Codigo` as codobj , o1.`Objetivo` , mi1.`Codigo` AS codmi, i1.`nome` ";
  
if ($anogestao<2022){
    $sql.=" , CASE WHEN a1.metrica = 'P' THEN 'Percentual' WHEN a1.metrica = 'Q' THEN 'Absoluto' END AS metrica , ";
}  else {
    
    $sql.=" ,a1.Codigo as codmeta ,";
}




$sql.="ii1.codmapaind
  FROM `documento` d1 
  INNER JOIN `mapa` m1 ON m1.`CodDocumento` = d1.`codigo`  and m1.anoinicio<=:ano and (m1.anofim>=:ano or m1.anofim is null) 
  INNER JOIN objetivo o1 ON m1.`codObjetivoPDI` = o1.`Codigo` 
  LEFT JOIN mapaindicador mi1 ON mi1.codmapa = m1.codigo and mi1.anoinicial<=:ano AND (mi1.anofinal>=:ano OR  mi1.anofinal IS NULL) 
  LEFT JOIN indicador i1 ON mi1.codindicador = i1.codigo 
  LEFT JOIN meta a1 ON mi1.codigo = a1.codmapaind and a1.anoinicial<=:ano AND (a1.anofinal>=:ano OR  a1.anofinal IS NULL) and a1.ano=:ano
  left JOIN calendario c1 ON c1.codcalendario = a1.codcalendario 
    LEFT JOIN indic_iniciativa ii1 on ii1.codmapaind=mi1.codigo and ii1.anoinicial<=:ano and (ii1.anofinal>=:ano or ii1.anofinal is null )
      left join iniciativa ic on ic.codiniciativa=ii1.codiniciativa 
    WHERE d1.codunidade =938 
AND d1.anoinicial<=:ano AND d1.anofinal >= :ano  AND mi1.`PropIndicador` =:prop";
             $stmt=parent::prepare($sql);// d.`situacao`='A' and
             $stmt->execute(array(':codigo' => $coddocumento,':ano'=>$anogestao,':prop'=>$codunidade));
             return $stmt;
            
        } catch (PDOException $ex) {
            print "Erro pedenciasDocumento: " . $ex->getCode() . " " . $ex->getMessage()." ";
        } 
             
              
    }
    
    
 
    
    
    
    
    
    
  /* Antes das alterações de 2022  
public function pedenciasDocumento($coddocumento,$anogestao,$codunidade){
         //pega pds onde a unidade é proprietária de indicador
         try {
            $sql =  "SELECT distinct d.codigo as coddoc,a.periodo,m.Codigo as codmapa, o.`Codigo` as codobj , `Objetivo` , mi.`Codigo` AS codmi, i.`nome` ,
   a.Codigo AS codMeta ,ii.codmapaind,
  CASE WHEN i.unidadeMedida = 'P' THEN 'Percentual' WHEN i.unidadeMedida = 'Q' THEN 'Absoluto' WHEN i.unidadeMedida = 'R' THEN 'Real' WHEN i.unidadeMedida = 'M' THEN 'Metro quadrado' END AS metrica
FROM `documento` d 
  INNER JOIN `mapa` m ON m.`CodDocumento` = d.`codigo`  and m.anoinicio<=:ano and (m.anofim>:ano or m.anofim is null)
  INNER JOIN objetivo o ON m.`codObjetivoPDI` = o.`Codigo` 
  LEFT JOIN mapaindicador mi ON mi.codmapa = m.codigo and mi.anoinicial<=:ano AND (mi.anofinal>:ano OR  mi.anofinal IS NULL)  
  LEFT JOIN indicador i ON mi.codindicador = i.codigo 
  LEFT JOIN meta a ON mi.codigo = a.codmapaind and  a.anoinicial<=:ano  AND (a.anofinal>:ano OR  a.anofinal IS NULL)
  left JOIN calendario c ON c.codcalendario = a.codcalendario 
  LEFT JOIN indic_iniciativa ii on ii.codmapaind=mi.codigo and ii.anoinicial<=:ano and (ii.anofinal>:ano or ii.anofinal is null )
  left join iniciativa ic on ic.codiniciativa=ii.codiniciativa
  WHERE d.`codigo` =:codigo 
union
SELECT distinct d1.codigo as coddoc,a1.periodo,m1.Codigo as codmapa, o1.`Codigo` as codobj , o1.`Objetivo` , mi1.`Codigo` AS codmi, i1.`nome` ,
   a1.Codigo AS codMeta ,ii.codmapaind,
  CASE WHEN i1.unidadeMedida = 'P' THEN 'Percentual' WHEN i1.unidadeMedida = 'Q' THEN 'Absoluto' WHEN i1.unidadeMedida = 'R' THEN 'Real' WHEN i1.unidadeMedida = 'M' THEN 'Metro quadrado' END AS metrica
  FROM `documento` d1 
  INNER JOIN `mapa` m1 ON m1.`CodDocumento` = d1.`codigo`  and m1.anoinicio<=:ano and (m1.anofim>:ano or m1.anofim is null) 
  INNER JOIN objetivo o1 ON m1.`codObjetivoPDI` = o1.`Codigo` 
  LEFT JOIN mapaindicador mi1 ON mi1.codmapa = m1.codigo and mi1.anoinicial<=:ano AND (mi1.anofinal>:ano OR  mi1.anofinal IS NULL) 
  LEFT JOIN indicador i1 ON mi1.codindicador = i1.codigo 
  LEFT JOIN meta a1 ON mi1.codigo = a1.codmapaind and a1.anoinicial<=:ano AND (a1.anofinal>:ano OR  a1.anofinal IS NULL)
  left JOIN calendario c1 ON c1.codcalendario = a1.codcalendario 
    LEFT JOIN indic_iniciativa ii on ii.codmapaind=mi1.codigo and ii.anoinicial<=:ano and (ii.anofinal>:ano or ii.anofinal is null )
      left join iniciativa ic on ic.codiniciativa=ii.codiniciativa 
    WHERE d1.codunidade =938 
AND d1.anoinicial<=:ano AND d1.anofinal >= :ano  AND mi1.`PropIndicador` =:prop";
             $stmt=parent::prepare($sql);// d.`situacao`='A' and
             $stmt->execute(array(':codigo' => $coddocumento,':ano'=>$anogestao,':prop'=>$codunidade));
             return $stmt;
        } catch (PDOException $ex) {
            print "Erro pedenciasDocumento: " . $ex->getCode() . " " . $ex->getMessage()." ";
        } 
             
              
    }
    
    */
    public function listaIndporDocCal1($anogestao,$coddocumento,$codunidade){
         //pega pds onde a unidade é proprietária de indicador
         try {//(d.`codigo` =:codigo )and
            $sql =  "  SELECT d.codigo as coddoc,a.periodo,m.Codigo as codmapa, o.`Codigo` as codobj  ,  
            `Objetivo` , mi.`Codigo` AS codmi, i.`nome` , a.Codigo as codmeta,";

            if ($anogestao>2022){
                $sql.="CASE WHEN i.unidadeMedida = 'P' THEN 'Percentual' WHEN i.unidadeMedida = 'Q' THEN 'Absoluto' 
                WHEN i.unidadeMedida = 'R' THEN 'Real' WHEN i.unidadeMedida = 'M' THEN 'Metro quadrado' END AS metrica,";
            }else {
               $sql.="CASE WHEN a.metrica = 'P' THEN 'Percentual' WHEN a.metrica = 'Q' THEN 'Absoluto'
                 END AS metrica,";
                }

$sql.="a.meta,mi.`PropIndicador`
FROM  `documento` d
INNER JOIN  `mapa` m ON m.`CodDocumento` = d.`codigo`  and  m.anoinicio<=:ano and  
(m.anofim>=:ano or m.anofim is null) 
INNER JOIN objetivo o ON  m.`codObjetivoPDI` = o.`Codigo`   
INNER JOIN mapaindicador mi ON mi.codmapa = m.codigo and mi.`PropIndicador` =:prop and mi.anoinicial<=:ano AND 
(mi.anofinal>=:ano OR  mi.anofinal IS NULL) and ( mi.tipoassociado is NULL or  mi.tipoassociado='PDU') 
INNER JOIN indicador i ON mi.codindicador = i.codigo
LEFT OUTER JOIN meta a ON mi.codigo = a.codmapaind and a.anoinicial<=:ano  AND (a.anofinal>=:ano OR  a.anofinal IS NULL)
INNER JOIN calendario c ON c.codcalendario = a.codcalendario
WHERE  c.`anoGestao` =:ano


  union  SELECT d1.codigo as coddoc , a1.periodo,m1.Codigo as codmapa, o1.`Codigo` as codobj  , o1.`Objetivo` ,
 mi1.`Codigo` AS codmi, i1.`nome` , a1.Codigo as codmeta,
CASE WHEN a1.metrica =  'P'
THEN  'Percentual'
WHEN a1.metrica =  'Q'
THEN  'Absoluto'
END AS metrica,a1.meta,mi1.`PropIndicador`
FROM  `documento` d1
INNER JOIN  `mapa` m1 ON m1.`CodDocumento` = d1.`codigo` and (d1.codunidade =938 )
 and m1.anoinicio<=:ano and (m1.anofim>=:ano or m1.anofim is null) 
AND d1.anoinicial<=:ano AND d1.anofinal >= :ano 
INNER JOIN objetivo o1 ON  m1.`codObjetivoPDI` = o1.`Codigo` 
INNER JOIN mapaindicador mi1 ON mi1.codmapa = m1.codigo AND mi1.`PropIndicador` =:prop  and mi1.tipoassociado=7 and mi1.anoinicial<=:ano AND (mi1.anofinal>=:ano OR  mi1.anofinal IS NULL) 
INNER JOIN indicador i1 ON mi1.codindicador = i1.codigo 
LEFT OUTER JOIN meta a1 ON mi1.codigo = a1.codmapaind AND a1.anoinicial<=:ano  AND (a1.anofinal>=:ano OR  a1.anofinal IS NULL)
INNER JOIN calendario c1 ON c1.codcalendario = a1.codcalendario
WHERE  c1.anogestao=:ano ";
   

         
             //echo $codunidade."unidade"."-".$coddocumento."---".$anogestao;
             $stmt=parent::prepare($sql);// d.`situacao`='A' and
             $stmt->execute(array(':codigo' => $coddocumento,':ano'=>$anogestao,':prop'=>$codunidade));
             return $stmt;
        } catch (PDOException $ex) {
            print "Erro listaIndporDocCal1: " . $ex->getCode() . " " . $ex->getMessage()." listaIndporDocCal";
        } 
             
              
    }
    
 public function listaIndporDocCal2($anogestao,$coddocumento,$codunidade){
         //pega pds onde a unidade é proprietária de indicador
         try {
            $sql =  "  SELECT d1.codigo as coddoc , a1.periodo,m1.Codigo as codmapa, 
o1.`Codigo` as codobj  , o1.`Objetivo` ,
 mi1.`Codigo` AS codmi, i1.`nome` , a1.Codigo as codmeta,
CASE WHEN i1.unidadeMedida = 'P' THEN 'Percentual' 
WHEN i1.unidadeMedida = 'Q' THEN 'Absoluto' 
WHEN i1.unidadeMedida = 'R' THEN 'Real' 
WHEN i1.unidadeMedida = 'M' THEN 'Metro quadrado' 
END AS metrica,a1.meta,
mi1.`PropIndicador`
FROM  `documento` d1
INNER JOIN  `mapa` m1 ON m1.`CodDocumento` = d1.`codigo` AND d1.anoinicial<=:ano AND d1.anofinal >= :ano  and m1.anoinicio<=:ano and (m1.anofim>=:ano or m1.anofim is null) 
INNER JOIN objetivo o1 ON  m1.`codObjetivoPDI` = o1.`Codigo` 
INNER JOIN mapaindicador mi1 ON mi1.codmapa = m1.codigo  and mi1.tipoassociado=7 and mi1.anoinicial<=:ano AND (mi1.anofinal>=:ano OR  mi1.anofinal IS NULL) 
INNER JOIN indicador i1 ON mi1.codindicador = i1.codigo 
LEFT OUTER JOIN meta a1 ON mi1.codigo = a1.codmapaind and a1.anoinicial<=:ano AND (a1.anofinal>=:ano OR  a1.anofinal IS NULL)
INNER JOIN calendario c1 ON c1.codcalendario = a1.codcalendario
WHERE  c1.anogestao=:ano and (d1.codunidade =938 )";
     

         
             //echo $codunidade."unidade"."-".$coddocumento."---".$anogestao;
             $stmt=parent::prepare($sql);// d.`situacao`='A' and
             $stmt->execute(array(':codigo' => $coddocumento,':ano'=>$anogestao,':prop'=>$codunidade));
             return $stmt;
        } catch (PDOException $ex) {
            print "Erro listaIndporDocCal1: " . $ex->getCode() . " " . $ex->getMessage()." listaIndporDocCal";
        } 
             
              
    }

    
               
               public function listaDocporIndCal1($anogestao,$codunidade){
         //pega pds onde a unidade é proprietária de indicador
         try {
            $sql =  "   SELECT DISTINCT d.`codigo` ,d.`nome`,d.anoinicial,d.anofinal 
            FROM  `documento` d 
INNER JOIN  `mapa` m ON m.`CodDocumento` = d.codigo AND m.anoinicio<=:ano and (m.anofim>:ano or m.anofim is null) 
INNER JOIN mapaindicador mi ON mi.codmapa = m.codigo and mi.anoinicial<=:ano AND (mi.anofinal>:ano OR  mi.anofinal IS NULL) 
INNER JOIN indicador i ON mi.codindicador = i.codigo 
INNER JOIN meta a ON mi.codigo = a.codmapaind and a.anoinicial<=:ano AND (a.anofinal>:ano OR  a.anofinal IS NULL)
INNER JOIN calendario c ON c.codcalendario = a.codcalendario WHERE c.anoGestao =:ano
AND d.codunidade =:prop or  (mi.tipoassociado=7 and d.codunidade=938 and mi.PropIndicador=:prop) ";

             
             $stmt=parent::prepare($sql);// d.`situacao`='A' and
             $stmt->execute(array(':ano'=>$anogestao,':prop'=>$codunidade));
             return $stmt;
        } catch (PDOException $ex) {
            print "Erro listaDocporIndCal: " . $ex->getCode() . " " . $ex->getMessage()." listaDocporIndCal";
        } 
             
              
    } 
    
   public function listaDocporIndCal($anogestao,$codunidade){
         //pega pds onde a unidade é proprietária de indicador
         try {
            $sql =  "   SELECT DISTINCT d.`codigo` ,d.`nome`,d.anoinicial,d.anofinal 
            FROM  `documento` d 
 INNER JOIN  `mapa` m ON m.`CodDocumento` = d.codigo and m.anoinicio<=:ano and (m.anofim>:ano or m.anofim is null)
 INNER JOIN mapaindicador mi ON mi.codmapa = m.codigo  and mi.anoinicial<=:ano AND (mi.anofinal>:ano OR  mi.anofinal IS NULL)
 INNER JOIN indicador i ON mi.codindicador = i.codigo 
 INNER JOIN meta a ON mi.codigo = a.codmapaind  and a.anoinicial<=:ano AND (a.anofinal>:ano OR  a.anofinal IS NULL)
 INNER JOIN calendario c ON c.codcalendario = a.codcalendario WHERE c.anoGestao =:ano
 AND mi.PropIndicador =:prop";

             
             $stmt=parent::prepare($sql);// d.`situacao`='A' and
             $stmt->execute(array(':ano'=>$anogestao,':prop'=>$codunidade));
             return $stmt;
        } catch (PDOException $ex) {
            print "Erro listaDocporIndCal: " . $ex->getCode() . " " . $ex->getMessage()." listaDocporIndCal";
        } 
             
              
    } 
               
    
    public function buscadocumentoporunidade($codUnidade,$anogestao) {
        try {
            $sql = "SELECT `codigo`, `CodUnidade`, `nome`, `anoinicial`, `anofinal`, `situacao`, `missao`, `visao`, `nomearq`, 
            `tipoarq`, `tamarq`,  `tipo` FROM `documento` WHERE (`CodUnidade`=:codigo or CodUnidade=938)
             AND `situacao`='A' and anoinicial<=:ano and (anofinal>=:ano or anofinal is null)";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codUnidade,':ano' => $anogestao));
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }
    
    public function buscadocumentoporunidadePeriodo($codUnidade,$anogestao, $coddocumento) {//aqui
        try {
            $sql = "SELECT `codigo`, `CodUnidade`, `nome`, `anoinicial`, `anofinal`, `situacao`, `missao`, `visao`, `nomearq`,
             `tipoarq`, `tamarq`,  `tipo` FROM `documento` WHERE (`CodUnidade`=:codigo or CodUnidade=938) AND `situacao`='A'".
             " and anoinicial<=:ano and anofinal>=:ano and codigo=:doc";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codUnidade,':ano'=>$anogestao,':doc'=>$coddocumento));
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }
    
          public function buscaporRedundancia($codUnidade,$anogestao) {
        try {
            $sql = "SELECT `codigo`, `CodUnidade`, `nome`, `anoinicial`, `anofinal`, `situacao`, `missao`, `visao`, `nomearq`, 
            `tipoarq`, `tamarq`,  `tipo` FROM `documento` WHERE `CodUnidade`=:codigo and anoinicial<=:ano and anofinal>=:ano ";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codUnidade,':ano'=>$anogestao));
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro buscaporRedundancia: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }

      public function painelbuscaporRedundancia($codUnidade,$anogestao) {
        try {
            $sql = "SELECT `codigo`, `CodUnidade`, `nome`, `anoinicial`, `anofinal`, `situacao`, `missao`, `visao`, 
            `nomearq`, `tipoarq`, `tamarq`,  `tipo` FROM ". "`documento` WHERE (`CodUnidade`=:codigo or CodUnidade=938) ".
                " and anoinicial<=:ano and anofinal>=:ano order by 1 desc";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codUnidade,':ano'=>$anogestao));
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro buscaporRedundancia: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }
    
public function painelbuscaporRedundancia1($anogestao) {
        try {
            $sql = "SELECT `codigo`, `CodUnidade`, `nome`, `anoinicial`, `anofinal`, `situacao`, `missao`, `visao`, `nomearq`,
            `tipoarq`, `tamarq`,  `tipo` FROM ". "`documento` WHERE  
              anoinicial<=:ano and anofinal>=:ano order by nome asc";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':ano'=>$anogestao));
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro buscaporRedundancia: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }
    
    
  public function buscadocumentonomeunidade($nome) {
        try {
            $sql = "SELECT d.`codigo`,d.`nome`,d.anoinicial,d.anofinal, u.CodUnidade FROM `documento` d inner join unidade u ".
            " WHERE (u.`NomeUnidade`=:nome or u.CodUnidade=938) ".
            " and u.`CodUnidade`=d.`CodUnidade`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':nome' => $nome));
            return $stmt;
        } catch (PDOException $ex) {
                        print "Erro buscadocumentonomeunidade: " . $ex->getCode() . " " . $ex->getMessage();

        }
    }

//Função para prorrogar o prazo do PDU
public function prorrogarPDU($ano,$codDoc){
        try {
            $r=1;
            $stmt = parent::prepare("CALL ProrrogarPDU(:novoanofinal,:codigodocumento)");
            
            $stmt->bindParam(':novoanofinal', $ano);
            $stmt->bindParam(':codigodocumento',$codDoc);            
            
            $success = $stmt->execute();            
            
            if($success){
                //$result = $stmt->fetchAll(parent::FETCH_ASSOC);
                echo 1;
                
            }else{
                echo 'Erro na função prorrogarPDU';
            }           
           
        } catch (PDOException $ex) {
        
                // parent::rollback();
                $string= "Erro procedure ProrrogarPDU: " . $ex->getMessage();?>
                <div id="error">
                        <img src="webroot/img/error.png" width="30" height="30"/>
                        <?php print $string;?>
                    </div>
                <?php
        }
 }
    
     
    
    public function fechar() {
        PDOConnectionFactory::Close();
    }

    
}

?>

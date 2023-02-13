<?php
class DocumentoDAO extends PDOConnectionFactory {
    public $conex = null;
    
    private $mysqli;
    // constructor
    public function __construct() {
    	 
    	$this->mysqli = new mysqli(PDOConnectionFactory::getHost(), PDOConnectionFactory::getUser(), PDOConnectionFactory::getSenha(), PDOConnectionFactory::getDb());
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
   
    
   $query = "INSERT INTO `documento` ( `CodUnidade`, `nome`, `anoinicial`,`anofinal`,`situacao`,`missao`,`visao`,`anexo`,`tipo`,`nomearq`,`tipoarq`,`tamarq`)
      VALUES ('$unidade','$nome','$anoinicial','$anofinal','$situacao','$missao','$visao',NULL,'$tipo','$nomearq','$tipoarq','$tamarq')";
                        
                        $query = $this->mysqli->query($query);
         //  	printf("Affected rows (INSERT): %d\n", $this->mysqli->affected_rows);

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
            $stmt = parent::prepare("SELECT `codigo`, `CodUnidade`, `nome`, `anoinicial`, `anofinal`, `situacao`, `missao`, `visao`, `nomearq`, `tipoarq`, `tamarq`,  `tipo` FROM `documento` where  `anoinicial`<=? and `anofinal`>=? order by nome");
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
    		$sql = "SELECT `codigo`, `CodUnidade`, `nome`, `anoinicial`, `anofinal`, `situacao`, `missao`, `visao`, `nomearq`, `tipoarq`, `tamarq`,  `tipo` FROM `documento` WHERE `CodUnidade`=:codigo AND `situacao`='A'".
      		" and anoinicial<=:ano and anofinal>=:ano";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codigo' => $codUnidade,':ano'=>$anogestao));
    		return $stmt;
    	} catch (PDOException $ex) {
    		print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
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
            $sql = "SELECT p.codPerspectiva,codObjetivoPDI,d.`codigo`,d.`nome`,`Objetivo`, p.`nome` ".
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
            $sql = "SELECT `codigo`, `CodUnidade`, `nome`, `anoinicial`, `anofinal`, `situacao`, `missao`, `visao`, `nomearq`, `tipoarq`, `tamarq`,  `tipo` FROM `documento` WHERE `codigo`=:codigo";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro buscadocumento: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }
    
     public function buscadocumentoPrazo($anobase) {
        try {
            $sql = "SELECT `codigo`, `CodUnidade`, `nome`, `anoinicial`, `anofinal`, `situacao`, `missao`, `visao`, `nomearq`, `tipoarq`, `tamarq`,  `tipo` FROM `documento` WHERE `anoinicial`<=:ano and  `anofinal`>=:ano";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':ano' => $anobase));
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro buscadocumento: " . $ex->getCode() . " " . $ex->getMessage();
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
            $sql = "SELECT `codigo`, `CodUnidade`, `nome`, `anoinicial`, `anofinal`, `situacao`, `missao`, `visao`, `nomearq`, `tipoarq`, `tamarq`,  `tipo` FROM `documento` WHERE `CodDocumento` IS NULL ORDER BY `nome` ";
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
            $sql = " SELECT distinct d.`codigo`, d.`nome`,d.anoinicial,d.anofinal ".
                " FROM `documento` d inner join `calendario` c on c.codDocumento=d.codigo ".
                " inner join `mapa` m on m.`CodDocumento`=d.`codigo`".
                " inner join `mapaindicador` i on m.`Codigo`=i.`CodMapa` WHERE i.`PropIndicador`=:codigo and `anoGestao`=:ano";
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
            $sql =  "  SELECT DISTINCT mi.`Codigo` , i.`nome`  FROM  `documento` d".
" INNER JOIN  `mapa` m ON m.`CodDocumento` = d.`codigo` ".
" INNER JOIN mapaindicador mi ON mi.codmapa = m.codigo " .
" INNER JOIN indicador i ON mi.codindicador = i.codigo".
" INNER JOIN meta a ON mi.codigo = a.codmapaind".
" INNER JOIN calendario c ON c.codcalendario = a.codcalendario".
" WHERE d.`codigo` =:codigo".
" AND c.`anoGestao` =:ano".
" AND mi.`PropIndicador` =:prop ".
" AND m.codObjetivoPDI=:obj";
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
            $sql =  "  SELECT DISTINCT mi.`Codigo` , i.`nome`  FROM  `documento` d".
" INNER JOIN  `mapa` m ON m.`CodDocumento` = d.`codigo` ".
" INNER JOIN mapaindicador mi ON mi.codmapa = m.codigo " .
" INNER JOIN indicador i ON mi.codindicador = i.codigo".
" LEFT OUTER JOIN meta a ON mi.codigo = a.codmapaind".
" INNER JOIN calendario c ON c.codcalendario = a.codcalendario".
" WHERE d.`codigo` =:codigo".
" AND c.`anoGestao` =:ano".
" AND mi.`PropIndicador` =:prop ";
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
            $sql =  "SELECT distinct d.codigo as coddoc,a.periodo,m.Codigo as codmapa, o.`Codigo` as codobj , `Objetivo` , mi.`Codigo` AS codmi, i.`nome` ,
   CASE WHEN a.metrica = 'P' THEN 'Percentual' WHEN a.metrica = 'Q' THEN 'Absoluto' END AS metrica ,ii.codmapaind
  FROM `documento` d INNER JOIN `mapa` m ON m.`CodDocumento` = d.`codigo` INNER JOIN objetivo o ON m.`codObjetivoPDI` = o.`Codigo` 
  LEFT JOIN mapaindicador mi ON mi.codmapa = m.codigo 
  LEFT JOIN indicador i ON mi.codindicador = i.codigo 
  LEFT JOIN meta a ON mi.codigo = a.codmapaind left JOIN calendario c ON c.codcalendario = a.codcalendario 
  LEFT JOIN indic_iniciativa ii on ii.codmapaind=mi.codigo
  left join iniciativa ic on ic.codiniciativa=ii.codiniciativa and ic.anoinicio<=:ano and (ic.anofinal>:ano or ic.anofinal is null )
  WHERE d.`codigo` =:codigo 
union
SELECT distinct d1.codigo as coddoc,a1.periodo,m1.Codigo as codmapa, o1.`Codigo` as codobj , o1.`Objetivo` , mi1.`Codigo` AS codmi, i1.`nome` ,
   CASE WHEN a1.metrica = 'P' THEN 'Percentual' WHEN a1.metrica = 'Q' THEN 'Absoluto' END AS metrica ,ii.codmapaind
  FROM `documento` d1 INNER JOIN `mapa` m1 ON m1.`CodDocumento` = d1.`codigo` INNER JOIN objetivo o1 ON m1.`codObjetivoPDI` = o1.`Codigo` 
  LEFT JOIN mapaindicador mi1 ON mi1.codmapa = m1.codigo LEFT JOIN indicador i1 ON mi1.codindicador = i1.codigo 
  LEFT JOIN meta a1 ON mi1.codigo = a1.codmapaind left JOIN calendario c1 ON c1.codcalendario = a1.codcalendario 
    LEFT JOIN indic_iniciativa ii on ii.codmapaind=mi1.codigo
      left join iniciativa ic on ic.codiniciativa=ii.codiniciativa and ic.anoinicio<=:ano and (ic.anofinal>:ano or ic.anofinal is null )
    WHERE d1.codunidade =938 
AND d1.anoinicial<=:ano AND d1.anofinal >= :ano  AND mi1.`PropIndicador` =:prop";
             $stmt=parent::prepare($sql);// d.`situacao`='A' and
             $stmt->execute(array(':codigo' => $coddocumento,':ano'=>$anogestao,':prop'=>$codunidade));
             return $stmt;
        } catch (PDOException $ex) {
            print "Erro pedenciasDocumento: " . $ex->getCode() . " " . $ex->getMessage()." ";
        } 
             
              
    }
    
    
    public function listaIndporDocCal1($anogestao,$coddocumento,$codunidade){
         //pega pds onde a unidade é proprietária de indicador
         try {
            $sql =  "  SELECT d.codigo as coddoc,a.periodo,m.Codigo as codmapa, o.`Codigo` as codobj  ,  
            `Objetivo` , mi.`Codigo` AS codmi, i.`nome` , a.Codigo as codmeta,
CASE WHEN a.metrica =  'P'
THEN  'Percentual'
WHEN a.metrica =  'Q'
THEN  'Absoluto'
END AS metrica, a.meta
FROM  `documento` d
INNER JOIN  `mapa` m ON m.`CodDocumento` = d.`codigo` 
INNER JOIN objetivo o ON  m.`codObjetivoPDI` = o.`Codigo` 
INNER JOIN mapaindicador mi ON mi.codmapa = m.codigo
INNER JOIN indicador i ON mi.codindicador = i.codigo
LEFT OUTER JOIN meta a ON mi.codigo = a.codmapaind
INNER JOIN calendario c ON c.codcalendario = a.codcalendario
WHERE (d.`codigo` =:codigo )
AND c.`anoGestao` =:ano
AND mi.`PropIndicador` =:prop
union SELECT d1.codigo as coddoc , a1.periodo,m1.Codigo as codmapa, o1.`Codigo` as codobj  , o1.`Objetivo` ,
 mi1.`Codigo` AS codmi, i1.`nome` , a1.Codigo as codmeta,
CASE WHEN a1.metrica =  'P'
THEN  'Percentual'
WHEN a1.metrica =  'Q'
THEN  'Absoluto'
END AS metrica,a1.meta
FROM  `documento` d1
INNER JOIN  `mapa` m1 ON m1.`CodDocumento` = d1.`codigo` 
INNER JOIN objetivo o1 ON  m1.`codObjetivoPDI` = o1.`Codigo` 
INNER JOIN mapaindicador mi1 ON mi1.codmapa = m1.codigo
INNER JOIN indicador i1 ON mi1.codindicador = i1.codigo
LEFT OUTER JOIN meta a1 ON mi1.codigo = a1.codmapaind
INNER JOIN calendario c1 ON c1.codcalendario = a1.codcalendario
WHERE (d1.codunidade =938 )
AND d1.anoinicial<=:ano AND d1.anofinal >= :ano and c1.anogestao=:ano ";

if ($codunidade!=938){
$sql.=" AND mi1.`PropIndicador` =:prop";
         }
             //echo $codunidade."unidade"."-".$coddocumento."---".$anogestao;
             $stmt=parent::prepare($sql);// d.`situacao`='A' and
             $stmt->execute(array(':codigo' => $coddocumento,':ano'=>$anogestao,':prop'=>$codunidade));
             return $stmt;
        } catch (PDOException $ex) {
            print "Erro listaIndporDocCal1: " . $ex->getCode() . " " . $ex->getMessage()." listaIndporDocCal";
        } 
             
              
    }
    
     
               
               public function listaDocporIndCal($anogestao,$codunidade){
         //pega pds onde a unidade é proprietária de indicador
         try {
            $sql =  "   SELECT DISTINCT d.`codigo` ,d.`nome`,anoinicial,anofinal FROM  `documento` d ".
" INNER JOIN  `mapa` m ON m.`CodDocumento` = d.codigo INNER JOIN mapaindicador mi ON mi.codmapa = m.codigo ".
" INNER JOIN indicador i ON mi.codindicador = i.codigo INNER JOIN meta a ON mi.codigo = a.codmapaind ".
" INNER JOIN calendario c ON c.codcalendario = a.codcalendario WHERE c.anoGestao =:ano".
" AND mi.PropIndicador =:prop";

             
             $stmt=parent::prepare($sql);// d.`situacao`='A' and
             $stmt->execute(array(':ano'=>$anogestao,':prop'=>$codunidade));
             return $stmt;
        } catch (PDOException $ex) {
            print "Erro listaDocporIndCal: " . $ex->getCode() . " " . $ex->getMessage()." listaDocporIndCal";
        } 
             
              
    } 
    
   
               
    
    public function buscadocumentoporunidade($codUnidade) {
        try {
            $sql = "SELECT `codigo`, `CodUnidade`, `nome`, `anoinicial`, `anofinal`, `situacao`, `missao`, `visao`, `nomearq`, `tipoarq`, `tamarq`,  `tipo` FROM `documento` WHERE (`CodUnidade`=:codigo or CodUnidade=938) AND `situacao`='A'";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codUnidade));
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }
    
      public function buscadocumentoporunidadePeriodo($codUnidade,$anogestao) {
        try {
            $sql = "SELECT `codigo`, `CodUnidade`, `nome`, `anoinicial`, `anofinal`, `situacao`, `missao`, `visao`, `nomearq`, `tipoarq`, `tamarq`,  `tipo` FROM `documento` WHERE (`CodUnidade`=:codigo or CodUnidade=938) AND `situacao`='A'".
                " and anoinicial<=:ano and anofinal>=:ano";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codUnidade,':ano'=>$anogestao));
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }
    
          public function buscaporRedundancia($codUnidade,$anogestao) {
        try {
            $sql = "SELECT `codigo`, `CodUnidade`, `nome`, `anoinicial`, `anofinal`, `situacao`, `missao`, `visao`, `nomearq`, `tipoarq`, `tamarq`,  `tipo` FROM ". "`documento` WHERE `CodUnidade`=:codigo ".
                " and anoinicial<=:ano and anofinal>=:ano";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codUnidade,':ano'=>$anogestao));
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
    
     
    
    public function fechar() {
        PDOConnectionFactory::Close();
    }

    
}

?>

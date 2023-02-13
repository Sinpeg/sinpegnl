<?php

class SolicitItensIndicadoresDeObjetivoDAO extends PDOConnectionFactory {
    public $conex = null;
  

    public function insere(SolicitItensIndicadoresDeObjetivo $seo) {
    	try {
    		$sql = "INSERT INTO `solicitItensIndicadoresDeObjetivos`(`codigo`, `codSolicitacao`, `codIndicador`,`meta1`,`meta2`,`meta3`,`meta4`,metrica) VALUES (?,?,?,?,?,?,?,?)";
    		$stmt = parent::prepare($sql);
    		parent::beginTransaction();
    		$stmt->bindValue(1, $seo->getCodigo()); 
    		$stmt->bindValue(2, $seo->getSolicitacao()->getCodigo()); // solicitacao de inser objetivo
    		$stmt->bindValue(3, $seo->getIndicador()->getCodigo()); // indic
    	    $stmt->bindValue(4, $seo->getVmeta1()); // indic
    		$stmt->bindValue(5, $seo->getVmeta2()); // indic
    		$stmt->bindValue(6, $seo->getVmeta3()); // indic
    	    $stmt->bindValue(7, $seo->getVmeta4()); // indic
    		$stmt->bindValue(8, $pi->getMetrica()); // indic
    	    
    		
    		$stmt->execute();
    		parent::commit();
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: insere SolicitacaoItensObj:" . $ex->getCode() . "Mensagem" . $ex->getMessage();die;
    	}
    }
    
public function inseretodos($arrayindobj) {
        try {
            parent::beginTransaction();
            foreach ($arrayindobj as $pi) {
    		$sql = "INSERT INTO `solicitItensIndicadoresDeObjetivos`(`codigo`, `codSolicitacao`, `codIndicador`,`meta1`,`meta2`,`meta3`,`meta4`,metrica) VALUES (?,?,?,?,?,?,?,?)";
            	$stmt = parent::prepare($sql);
                $stmt->bindValue(1, $pi->getCodigo()); 
    		    $stmt->bindValue(2, $pi->getSolicitacao()->getCodigo()); // solicitacao de inser objetivo
    		    $stmt->bindValue(3, $pi->getIndicador()->getCodigo()); // indic
    	        $stmt->bindValue(4, $pi->getVmeta1()); // indic
    		    $stmt->bindValue(5, $pi->getVmeta2()); // indic
    		    $stmt->bindValue(6, $pi->getVmeta3()); // indic
    	        $stmt->bindValue(7, $pi->getVmeta4()); // indic
    	        $stmt->bindValue(8, $pi->getMetrica()); // indic
    	        
                $stmt->execute();
            }
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
    		print "Erro: insere SolicitacaoItensObj:" . $ex->getCode() . "Mensagem" . $ex->getMessage();die;
        }
    }
    
    public function altera(SolicitItensIndicadoresDeObjetivo $seo) {
    	try {
    		$sql = "UPDATE  `solicitItensIndicadoresDeObjetivos` SET `codSolicitacao`=?,`codIndicador`=?,`meta1`=?,`meta2`=?,`meta3`=?,`meta4`=?,metrica=?
    		 WHERE  `codigo`=?";
    		$stmt = parent::prepare($sql);
    		parent::beginTransaction();
    		
    		$stmt->bindValue(1, $seo->getSolicitacao()->getCodigo()); // solicitacao de inser objetivo
    		$stmt->bindValue(2, $seo->getIndicador()->getCodigo()); // indic
    		$stmt->bindValue(3, $seo->getVmeta1()); // indic
    		$stmt->bindValue(4, $seo->getVmeta2()); // indic
    		$stmt->bindValue(5, $seo->getVmeta3()); // indic
    	    $stmt->bindValue(6, $seo->getVmeta4()); // indic
    	    $stmt->bindValue(7, $seo->getMetrica()); // indic
    	    
    		$stmt->bindValue(8, $seo->getCodigo()); 
    		
    		$stmt->execute();
    		parent::commit();
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: altera SolicitItensIndicadoresDeObjetivo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
    	}
    }
    
   
    
    public function deleta($codigo) {
    	try {
    		parent::beginTransaction();
    		$stmt = parent::prepare("DELETE FROM `solicitItensIndicadoresDeObjetivos` WHERE `Codigo`=?");
    		$stmt->bindValue(1, $codigo);
    		$stmt->execute();
    		parent::commit();
    		return 1;
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print  $ex->getCode() . "Mensagem" . $ex->getMessage();
    		return 0;
    	}
    }
    
     public function buscaIndicadoresObjetivo($anobase,$codobjetivo,$tipo) {
        try {
 
$sql="SELECT s.codigo,tipoSolicitacao, s.codunidade, s.codmapa,s.codobjetivo,o.objetivo as nomeobjetivo,
`justificativa` ,`anexo`,s.`situacao`,s.codIndicador,
datasolicitacao, `dataEmanalise` , `codusuarioanalise`, meta1,meta2,meta3,meta4,metrica 
from solicitacaoPDU s inner join objetivo o on o.codigo=s.codobjetivo 
inner join solicitItensIndicadoresDeObjetivos si on si.codSolicitacao=s.codigo
where s.codobjetivo=:codobjetivo and `anogestao`=:ano and tipoSolicitacao=:tipo and s.`situacao`not in ('F','C')
order by `tipoSolicitacao`,datasolicitacao";
           $stmt = parent::prepare($sql);
    	   $stmt->execute(array(':ano' => $anobase, ':codobjetivo' => $codobjetivo, ':tipo' => $tipo));
    	   return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaIndicadoresObjetivo SolicitItensIndicadoresDeObjetivo: " . $ex->getMessage();
        }
    }    
    
     public function buscaIndicadoresObjetivoIgSituacao($codsolicitacao) {
        try {
 
$sql="SELECT si.codIndicador,i.nome, meta1,meta2,meta3,meta4,CASE metrica  WHEN 'P' THEN 'Percentual'
WHEN 'Q' THEN 'Quantitativo' END AS metrica 
from indicador i inner join solicitItensIndicadoresDeObjetivos si on si.codIndicador=i.codigo
where si.codSolicitacao=:codigo ";
           $stmt = parent::prepare($sql);
    	   $stmt->execute(array(':codigo' => $codsolicitacao));
    	   return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaIndicadoresObjetivoIgSituacao SolicitItensIndicadoresDeObjetivo: " . $ex->getMessage();
        }
    }    
    
     
    
      public function listaTodas($anobase) {
        try {
 
$sql="SELECT`codigo`,`tipoSolicitacao`, `codunidade`, `codmapa` , `codobjetivo`,
  `justificativa` ,`anexo`,`situacao`,`datasolicitacao`,  `dataEmanalise` ,
  `codusuarioanalise`,  `dataComentario`,`comentario`,`anogestao` from solicitacao
   where  `anogestao`=:ano ";
           $stmt = parent::prepare($sql);
    	   $stmt->execute(array(':anobase' => $anobase));
    	   return $stmt;
        } catch (PDOException $ex) {
            echo "Erro listaTodas SolicitItensIndicadoresDeObjetivo por ano: " . $ex->getMessage();
        }
    }    
    
  
    
   
    
    public function fechar() {
        PDOConnectionFactory::Close();
    }

}

?>

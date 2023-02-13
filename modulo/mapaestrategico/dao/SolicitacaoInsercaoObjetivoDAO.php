<?php

class SolicitacaoInsercaoObjetivoDAO extends PDOConnectionFactory {

    public $conex = null;
  

    public function insere(SolicitacaoInsercaoObjetivo $seo) {
    	try {
    		$sql = "INSERT INTO `solicitacaoPDU` (`codigo`,`tipoSolicitacao`, `codunidade`, `coddocumento` , `codobjetivo`,
  `justificativa` ,`anexo`, `situacao`,  `anogestao`,codusuarioanalise) VALUES (?,?,?,?,?,?,?,?,?,?)";
    		$stmt = parent::prepare($sql);
    		parent::beginTransaction();
    		$stmt->bindValue(1, $seo->getCodigo()); 
    		$stmt->bindValue(2, 3); // tipo da solicitacao
    		$stmt->bindValue(3, $seo->getUnidade()->getCodUnidade()); // unidade
    		$stmt->bindValue(4, $seo->getDocumento()->getCodigo()); // documento
    		$stmt->bindValue(5, $seo->getObjetivo()->getCodigo()); // indicador
    		$stmt->bindValue(6, $seo->getJustificativa()); 
    		$stmt->bindValue(7, $seo->getAnexo()); 
    		$stmt->bindValue(8, $seo->getSituacao()); 
    	    $stmt->bindValue(9, $seo->getAnogestao()); // ano
    	    $stmt->bindValue(10, $seo->getUsuarioanalista()->getCodusuario()); // indicador
    	    
    		
    		$stmt->execute();
    		$id= parent::lastInsertId();
    		parent::commit();
    		return $id;
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: insere SolicitacaoInsercaoObjetivo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();die;
    	}
    }
    
    
    public function altera(SolicitacaoInsercaoObjetivo $seo) {
    	try {
    		$sql = "UPDATE `solicitacaoPDU` SET  `codmapa`=? , `codobjetivo`=?,
  `justificativa`=? ,`anexo`=?, `situacao`=?,`datasolicitacao`=?,  `dataEmanalise`=? ,
  `codusuarioanalise`=? WHERE `codigo`=?";
    		$stmt = parent::prepare($sql);
    		parent::beginTransaction();
    		
    		$stmt->bindValue(1, $seo->getMapa()->getCodigo()); // mapaindicador
    		$stmt->bindValue(2, $seo->getObjetivo()->getCodigo()); // indicador
    		$stmt->bindValue(3, $seo->getJustificativa()); 
    		$stmt->bindValue(4, $seo->getAnexo()); 
    		$stmt->bindValue(5, $seo->getSituacao()); 
    		$stmt->bindValue(6, $seo->getDatasolicitacao()); 
    		$stmt->bindValue(7, $seo->getDataemnalise()); 
    		$stmt->bindValue(8, $seo->getUsuario()->getCodusuario()); 
    		$stmt->bindValue(9, $seo->getCodigo()); 
    		$stmt->execute();
    		parent::commit();
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: altera SolicitacaoInsercaoObjetivo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
    	}
    }
    
    public function alteraSituacao(SolicitacaoEditObjetivo $seo) {
    	try {
    		$sql = "UPDATE `solicitacaoPDU` SET  `situacao`=?   WHERE `codigo`=?";
    		$stmt = parent::prepare($sql);
    		parent::beginTransaction();
    		
    		
    		$stmt->bindValue(1, $seo->getSituacao()); 
    		$stmt->bindValue(9, $seo->getCodigo()); 
    		$stmt->execute();
    		parent::commit();
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: altera SolicitacaoEditObjetivo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
    	}
    }
    
    public function deleta($codigo) {
    	try {
    		parent::beginTransaction();
    		$stmt = parent::prepare("DELETE FROM `solicitacaoPDU` WHERE `Codigo`=?");
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
    
     public function buscaSolicitacaoInsObjetivoUnidAno($anobase,$codobjetivo,$tipo) {
        try {
 
$sql="SELECT s.codigo,tipoSolicitacao, s.codunidade, s.codmapa,s.codobjetivo,o.objetivo as nomeobjetivo,
`justificativa` ,`anexo`,s.`situacao`,
datasolicitacao, `dataEmanalise` , `codusuarioanalise` 
from solicitacaoPDU s inner join objetivo o on o.codigo=s.codobjetivo
where s.codobjetivo=:codobjetivo and `anogestao`=:ano and tipoSolicitacao=:tipo and s.`situacao`not in ('F','C')
order by `tipoSolicitacao`,datasolicitacao";
           $stmt = parent::prepare($sql);
    	   $stmt->execute(array(':ano' => $anobase, ':codobjetivo' => $codobjetivo, ':tipo' => $tipo));
    	   return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaSolicitacaoInsObjetivoUnidAno: " . $ex->getMessage();
        }
    }    
    
     public function buscaSolicitacaoInsObjetivo($unidade,$anobase,$situacao,$tipo) {
        try {
 
$sql="SELECT s.codigo,tipoSolicitacao, s.codunidade, s.codmapa,s.codobjetivo,o.objetivo as nomeobjetivo,
`justificativa` ,`anexo`,s.`situacao`,
datasolicitacao, `dataEmanalise` , `codusuarioanalise` 
from solicitacaoPDU s inner join objetivo o on o.codigo=s.codobjetivo
where s.codunidade=:unidade and `anogestao`=:ano and s.`situacao`=:situacao and tipoSolicitacao=:tipo
order by `tipoSolicitacao`,datasolicitacao";
           $stmt = parent::prepare($sql);
    	   $stmt->execute(array(':anobase' => $anobase, ':unidade' => $unidade, 'situacao'=>$situacao, ':tipo' => $tipo));
    	   return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaSolicitacaoEditObjetivo: " . $ex->getMessage();
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
            echo "Erro listaTodas SolicitacaoEditObjetivo por ano: " . $ex->getMessage();
        }
    }    
    
    public function buscaSolicitacao($codigo) {
        try {
 
$sql="SELECT * from solicitacao
   where  codigo=:codigo";
           $stmt = parent::prepare($sql);
    	   $stmt->execute(array(':codigo' => $codigo));
    	   return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaSolicitacao SolicitacaoEditObjetivo por ano: " . $ex->getMessage();
        }
    }    
  
    
   
    
    public function fechar() {
        PDOConnectionFactory::Close();
    }

}

?>

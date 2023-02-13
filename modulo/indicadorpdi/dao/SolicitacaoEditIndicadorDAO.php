<?php

class SolicitacaoEditIndicadorDAO extends PDOConnectionFactory {

    public $conex = null;
  

    public function insere(SolicitacaoEditIndicador $sei) {
    	try {
    		$sql = "INSERT INTO `solicitacaoPDU` (`codigo`,`tipoSolicitacao`, `codunidade`, `codmapaind` , `codindicador`,
  `justificativa` ,`anexo`, `situacao`,  `dataEmanalise` , `codusuarioanalise`, `anogestao`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    		$stmt = parent::prepare($sql);
    		parent::beginTransaction();
    		$stmt->bindValue(1, $sei->getCodigo()); 
    		$stmt->bindValue(2, 1); // tipo da solicitacao
    		$stmt->bindValue(3, $sei->getUnidade()->getCodUnidade()); // unidade
    		$stmt->bindValue(4, $sei->getMapaindicador()->getCodigo()); // mapaindicador
    		$stmt->bindValue(5, $sei->getIndicador()->getCodigo()); // indicador
    		$stmt->bindValue(6, $sei->getJustificativa()); 
    		$stmt->bindValue(7, $sei->getAnexo()); 
    		$stmt->bindValue(8, $sei->getSituacao()); 
    		$stmt->bindValue(9, $sei->getDataemanalise()); 
    		$stmt->bindValue(10,$sei->getUsuarioanalista()->getCodusuario());    		
    		$stmt->bindValue(11,$sei->getAnogestao()); // ano
    		
    		$stmt->execute();
    		parent::commit();
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: insere SolicitacaoEditIndicador:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
    	}
    }
    
    
    public function altera(SolicitacaoEditIndicador $sei) {
    	try {
    		$sql = "UPDATE `solicitacaoPDU` SET  `codmapaind`=? , `codindicador`=?,
  `justificativa`=? ,`anexo`=?, `situacao`=?,`datasolicitacao`=?,  `dataEmanalise`=? ,
  `codusuarioanalise`=?,    `tipo`=? WHERE `codigo`=?";
    		$stmt = parent::prepare($sql);
    		parent::beginTransaction();
    		
    		$stmt->bindValue(1, $sei->getMapaindicador()->getCodigo()); // mapaindicador
    		$stmt->bindValue(2, $sei->getIndicador()->getCodigo()); // indicador
    		$stmt->bindValue(3, $sei->getJustificativa()); 
    		$stmt->bindValue(4, $sei->getAnexo()); 
    		$stmt->bindValue(5, $sei->getSituacao()); 
    		$stmt->bindValue(6, $sei->getDatasolicitacao()); 
    		$stmt->bindValue(7, $sei->getDataemnalise()); 
    		$stmt->bindValue(8, $sei->getUsuario()->getCodusuario()); 
    		$stmt->bindValue(9, $sei->getTipo()); // unidade
    		$stmt->bindValue(10, $sei->getCodigo()); 
    		$stmt->execute();
    		parent::commit();
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: altera SolicitacaoEditIndicador:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
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
    
    public function buscaSolicitacaoEditIndicador($unidade,$anobase)
     {
    	try {
    
    		$sql="SELECT s.`codigo`,`tipoSolicitacao`, s.`codunidade`, `codmapaind` , s.`codindicador` as codindicadornovo,
(select ia.nome from indicador ia where mi.codIndicador=ia.codigo) as nomeindicadornovo, mi.codIndicador as codindicadorantigo, i.nome as nomeindicadorantigo,
`justificativa` ,`anexo`,`situacao`,`datasolicitacao`, `dataEmanalise` , `codusuarioanalise`
from solicitacaoPDU s inner join mapaindicador mi on mi.codigo=s.codmapaind
inner join indicador i on i.codigo=mi.codIndicador
where s.codunidade=:unidade and `anogestao`=:ano order by `tipoSolicitacao`,`datasolicitacao`";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':ano' => $anobase, ':unidade' => $unidade));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscaSolicitacaoEditIndicador: " . $ex->getMessage();
    	}
    }
    
    
     public function buscaSolicitacaoEditIndicadorPorSituacao($unidade,$anobase,$situacao) {
        try {
 
$sql="SELECT s.`codigo`,`tipoSolicitacao`, s.`codunidade`, `codmapaind` , s.`codindicador` as codindicadornovo, 
(select ia.nome from indicador ia where mi.codIndicador=ia.codigo) as nomeindicadornovo, mi.codIndicador as codindicadorantigo, i.nome as nomeindicadorantigo,
`justificativa` ,`anexo`,`situacao`,`datasolicitacao`, `dataEmanalise` , `codusuarioanalise` 
from solicitacaoPDU s inner join mapaindicador mi on mi.codigo=s.codmapaind 
inner join indicador i on i.codigo=mi.codIndicador
where s.codunidade=:unidade and `anogestao`=:ano and `situacao`=:situacao
order by `tipoSolicitacao`,`datasolicitacao`";
           $stmt = parent::prepare($sql);
    	   $stmt->execute(array(':anobase' => $anobase, ':unidade' => $unidade, 'situacao'=>$situacao));
    	   return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaSolicitacaoEditIndicador: " . $ex->getMessage();
        }
    }    
    
    
      public function listaTodas($anobase) {
        try {
 
$sql="SELECT `codigo`,`tipoSolicitacao`, `codunidade`, `codmapaind` , `codindicador`,
  `justificativa` ,`anexo`,`situacao`,`datasolicitacao`,  `dataEmanalise` ,
  `codusuarioanalise` from solicitacaoPDU where  `anogestao`=:ano ";
           $stmt = parent::prepare($sql);
    	   $stmt->execute(array(':ano' => $anobase));
    	   return $stmt;
        } catch (PDOException $ex) {
            echo "Erro listaTodas por ano: " . $ex->getMessage();
        }
    }    
    
    
  
    
   
    
    public function fechar() {
        PDOConnectionFactory::Close();
    }

}

?>

<?php

class SolicitacaoRepactuacaoDAO extends PDOConnectionFactory {

    public $conex = null;
  

    public function insere(SolicitacaoRepactuacao $sv) {
    	try {
    		$sql = "INSERT INTO `solicitacaoPDU` (`codigo`,`tipoSolicitacao`, `codunidade`, `codmeta` , `novameta`,  `justificativa` ,`anexo`, `situacao`,`dataEmanalise` ,
  `codusuarioanalise`, `anogestao`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    		
    
    		$stmt = parent::prepare($sql);
    		parent::beginTransaction();
    		$stmt->bindValue(1, $sv->getCodigo()); 
    		$stmt->bindValue(2, 4); // tipo da solicitacao
    		$stmt->bindValue(3, $sv->getUnidade()->getCodUnidade()); // unidade
    		$stmt->bindValue(4, $sv->getMeta()->getCodigo()); // mapaindicador
    		$stmt->bindValue(5, $sv->getNovameta()); // indicador
    		$stmt->bindValue(6, $sv->getJustificativa()); 
    		$stmt->bindValue(7, $sv->getAnexo()); 
    		$stmt->bindValue(8, $sv->getSituacao()); 
    		$stmt->bindValue(9, $sv->getDataemanalise());
    		$stmt->bindValue(10, $sv->getUsuarioanalista()->getCodusuario()); 
      		
    		$stmt->bindValue(11, $sv->getAnogestao()); // unidade
    		
    		$stmt->execute();
    		parent::commit();
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: insere SolicitacaoRepactuacao:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
    	}
    }
    
    
    public function altera(SolicitacaoRepactuacao $sr) {
    	try {
    		$sql = "UPDATE `solicitacaoPDU` SET  `codmeta`=? , `novameta`=?,`justificativa`=? ,`anexo`=?, `situacao`=?,`datasolicitacao`=?,  `dataEmanalise`=? ,
  `codusuarioanalise`=? WHERE `codigo`=?";
    		$stmt = parent::prepare($sql);
    		parent::beginTransaction();
    		
    		$stmt->bindValue(1, $sr->getMeta()->getCodigo()); // mapaindicador
    		$stmt->bindValue(2, $sr->getNovameta()); // indicador
    
    		$stmt->bindValue(3, $sr->getJustificativa()); 
    		$stmt->bindValue(4, $sr->getAnexo()); 
    		$stmt->bindValue(5, $sr->getSituacao()); 
    		$stmt->bindValue(6, $sr->getDatasolicitacao()); 
    		$stmt->bindValue(7, $sr->getDataemnalise()); 
    		$stmt->bindValue(8, $sr->getUsuario()->getCodusuario()); 
    		$stmt->bindValue(9, $sr->getCodigo()); 
    		$stmt->execute();
    		parent::commit();
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: altera SolicitacaoRepactuacao:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
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
    
     public function buscaSolicitacaoRepactuacao($anobase,$codmeta) {
        try {
 //SITUACAO: f - FECHADA C- 	CANCELADA, I - INDEFERIDA
$sql="SELECT s.`codigo`,`tipoSolicitacao`, s.`codunidade`,ma.codmapaind,ma.codcalendario,ma.meta as metaantiga,s.novameta, 
`justificativa` ,`anexo`,s.`situacao`,`datasolicitacao`, `dataEmanalise` , `codusuarioanalise` 
from solicitacaoPDU s inner join meta ma on ma.codigo=s.codmeta
where s.codmeta=:meta and `anogestao`=:ano and s.`situacao` not in ('F','I','C') and tipoSolicitacao=4
order by `tipoSolicitacao`,`datasolicitacao`";
           $stmt = parent::prepare($sql);
    	   $stmt->execute(array( ':meta' => $codmeta,':ano' => $anobase));
    	   return $stmt;
        } catch (PDOException $ex) {
            echo "Erro SolicitacaoRepactuacao1: " . $ex->getMessage();
        }
    }    
    
    
      public function listaTodas($anobase) {
        try {
 
$sql="SELECT `codigo`,`tipoSolicitacao`, `codunidade`, `codmeta` , `novameta`,
  `justificativa` ,`anexo`, `caminho`,`situacao`,`datasolicitacao`,  `dataEmanalise` ,
  `codusuarioanalise`,  `dataComentario`,`comentario`,  `tipo`, `anogestao` 
  from solicitacao where  `anogestao`=:ano ";
           $stmt = parent::prepare($sql);
    	   $stmt->execute(array(':anobase' => $anobase));
    	   return $stmt;
        } catch (PDOException $ex) {
            echo "Erro listaTodas SolicitacaoRepactuacao por ano: " . $ex->getMessage();
        }
    }    
    
    
  
    
   
    
    public function fechar() {
        PDOConnectionFactory::Close();
    }

}

?>

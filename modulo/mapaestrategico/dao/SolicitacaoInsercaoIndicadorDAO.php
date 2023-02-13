<?php

class SolicitacaoInsercaoIndicadorDAO extends PDOConnectionFactory {

    public $conex = null;  

    public function insere(SolicitacaoInsereIndicador $seo) {
    	try {
    		$sql = "INSERT INTO `solicitacaoPDU` (`tipoSolicitacao`, `codunidade` , `codobjetivo`,`codmapa`,
  `justificativa` ,`anexo`, `situacao`,  `anogestao`,codusuarioanalise,codindicador) VALUES (?,?,?,?,?,?,?,?,?,?)";
    		$stmt = parent::prepare($sql);
    		parent::beginTransaction();    		
    		$stmt->bindValue(1, 6); // tipo da solicitacao
    		$stmt->bindValue(2, $seo->getUnidade()->getCodUnidade()); // unidade
    		$stmt->bindValue(3, $seo->getObjetivo()->getCodigo()); // indicador
    		$stmt->bindValue(4, $seo->getMapa());
    		$stmt->bindValue(5, $seo->getJustificativa()); 
    		$stmt->bindValue(6, $seo->getAnexo()); 
    		$stmt->bindValue(7, $seo->getSituacao()); 
    	    $stmt->bindValue(8, $seo->getAnogestao()); // ano
    	    $stmt->bindValue(9, $seo->getUsuarioanalista()->getCodusuario()); //    	    
    	    $stmt->bindValue(10, $seo->getIndicador()->getCodigo()); //
    	    
    		$stmt->execute();
    		$id= parent::lastInsertId();
    		parent::commit();
    		return $id;
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: insere SolicitacaoInsercaoIndicador:" . $ex->getCode() . "Mensagem" . $ex->getMessage();die;
    	}
    }  
    
    public function fechar() {
        PDOConnectionFactory::Close();
    }

}

?>

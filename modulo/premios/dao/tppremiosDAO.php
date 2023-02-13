<?php
class TppremiosDAO extends PDOConnectionFactory {


    
    public function lista(){
    	try{
    
    		$stmt = parent::query("SELECT * FROM `tdm_tipo_premio`");
    		// retorna o resultado da query
    		return $stmt;
    	}catch ( PDOException $ex ){
    		$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
    		$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
    		header($cadeia);
    	}
    }
    
    
    public function buscaPorCodigo($parametro) {
        try {
            $sql = "SELECT * FROM `tdm_tipo_premio` where `CodPremio`=:cod ";

            $stmt = parent::prepare($sql);
            $stmt->execute(array(':cod' => $parametro));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }
    
    public function insere($tpi) {
    	try {
    		parent::beginTransaction();
    		$sql = "INSERT INTO `tdm_tipo_premios` (`Nome`)  VALUES (?)";
    		$stmt = parent::prepare($sql);
    		$stmt->bindValue(1, $tpi->getNome());
    		$stmt->execute();
    		parent::commit();
    	} catch (PDOException $ex) {
    		parent::rollback();
    		$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
    		$cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
    		header($cadeia);
    	}
    }
    
    public function altera($tpi) {
    	try {
    		parent::beginTransaction();
    		$sql = "UPDATE `tdm_tipo_premios` SET `Nome`=? WHERE `CodPremio`=?";
    		$stmt = parent::prepare($sql);
    		$stmt->bindValue(1, $tpi->getNome());
    		$stmt->bindValue(2, $tpi->getCodigo());
    		$stmt->execute();
    		parent::commit();
    	} catch (PDOException $ex) {
    		parent::rollback();
    		$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
    		$cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
    		header($cadeia);
    	}
    }
    
    public function deleta($tpi) {
    	try {
    		parent::beginTransaction();
    		$stmt = parent::prepare("DELETE FROM `tdm_tipo_premios` WHERE `Codigo`=?");
    		$stmt->bindValue(1, $tpi->getCodigo());
    		$stmt->execute();
    		parent::commit();
    	} catch (PDOException $ex) {
    		parent::rollback();
    		$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
    		$cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
    		header($cadeia);
    	}
    }
    
    public function fechar() {
    	PDOConnectionFactory::Close();
    }
    
}
?>
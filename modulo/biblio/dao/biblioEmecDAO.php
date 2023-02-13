<?php

class BiblioEmecDAO extends PDOConnectionFactory {

    // ir� receber uma conex�o
    public $conex = null;

     // constructor
    // public function BiblioEmecDAO() {
   //     $this->conex = PDOConnectionFactory::getConnection();
  // }



    public function altera( $b ){
    	try{

    		$sql="UPDATE `bibliemec` SET `idUnidade`=? WHERE `idBibliemec`=?";
    		parent::beginTransaction();
    		$stmt = parent::prepare($sql);
    		if (!is_null($b->getUnidade()))
    			$stmt->bindValue(1, $b->getUnidade()->getCodunidade());
    		else $stmt->bindValue(1, NULL);
    		$stmt->bindValue(2, $b->getIdBibliemec());
    		$stmt->execute();
    		parent::commit();
    	}
    	catch ( PDOException $ex ){
    		parent::rollback();
    		$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
    		$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
    		header($cadeia);
    	}
    }


    public function buscaCodEmecBiblioUnidade($codunidade) {
        try {
        	
        	$sql = " SELECT `idBibliemec`,`tipo`,`nome`,`codEmec`,idunidade,sigla FROM `bibliemec` WHERE "
            ."  `idUnidade`=:unidade";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':unidade' => $codunidade));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            parent::rollback();
    		$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
    		$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem." buscaCodEmecBiblioUnidade ";
    		header($cadeia);
                }
    }

    public function Lista() {
        try {
            $sql = "SELECT * FROM bibliemec order by `idBibliemec`";
            $stmt = parent::query($sql);
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
             $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function buscaBiblioemec($cod) {
        try {

            $stmt = parent::prepare("SELECT * FROM bibliemec WHERE idBibliemec=:codigo");
            $stmt->execute(array(':codigo' => $cod));

            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }
    
    public function buscaUnidade($parametro) {
    	 
    	try {
    		$nome = strtoupper(addslashes($parametro));
    		if(is_string($nome)){
    			$nome1 = "%" . $nome .="%";
    			$sql = "SELECT `idBibliemec`, `nome` FROM bibliemec WHERE `nome` LIKE :nome";
    			$stmt = parent::prepare($sql);
    			$stmt->execute(array('nome' => $nome1));
    			// retorna o resultado da query
    			return $stmt;
    		}
    	} catch (PDOException $ex) {
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

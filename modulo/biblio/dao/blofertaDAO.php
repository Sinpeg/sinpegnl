<?php

class BlofertaDAO extends PDOConnectionFactory {

    // ir� receber uma conex�o
    public $conex = null;

     // constructor
    // public function BlofertaDAO() {
   //     $this->conex=PDOConnectionFactory::getConnection();
  // }



    public function buscaporIdbibli($parametro) {
        try {
            $sql = "SELECT * FROM bloferta  where `idBibliemec`=:idcenso ";

            $stmt = parent::prepare($sql);
            $stmt->execute(array(':idcenso' => $parametro));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }
    
    public function buscaporIdbibliemec($parametro) {
    	try {
    		$sql = "SELECT * FROM bloferta b, localoferta l  where l.idLocal = b.idloferta and `idBibliemec`=:idcenso ";
    
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':idcenso' => $parametro));
    		// retorna o resultado da query
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro: " . $ex->getMessage();
    	}
    }

    public function Lista1() {
        try {
            $sql = "SELECT * FROM bloferta";
            $stmt = parent::query($sql);
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }



    public function deleta( $idbibliemec ){
    	try{
    		$sql="delete FROM `bloferta` WHERE  `idBibliemec`=?";
    		parent::beginTransaction();
    		$stmt = parent::prepare($sql);
    		$stmt->bindValue(1, $idbibliemec);
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


    public function insere( $lo ){
    	try{
    	  foreach ($lo->getBlofertas() as $blo){
    		parent::beginTransaction();
    		$sql="INSERT INTO `bloferta` ( `idloferta`, `idBibliemec`)"
    			." 	VALUES (?,?)";
    		$stmt = parent::prepare($sql);
    		$stmt->bindValue(1,$blo->getLocaloferta()->getIdLocal());
    		$stmt->bindValue(2,$blo->getBibliemec()->getIdBibliemec());
    		$stmt->execute();

    		parent::commit();
    	  }
    	}catch ( PDOException $ex ){
    		parent::rollback();
    		$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
    		$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
    		header($cadeia);
    	}



    }
    public function fechar() {
        PDOConnectionFactory::Close();
    }

}

?>

<?php
class TppremiosDAO extends PDOConnectionFactory {
    // ir� receber uma conex�o
	private $conex = null;
    
    // constructor
      public function TppremiosDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}

 public function lista() {
        try {
            $sql = "SELECT * FROM tdm_tipo_premios";
            $stmt = $this->conex->query($sql);
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }
    
    public function buscaPorCodigo($parametro) {
        try {
            $sql = "SELECT * FROM tdm_tipo_premios where `CodPremio`=:cod ";

            $stmt = $this->conex->prepare($sql);
            $stmt->execute(array(':cod' => $parametro));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }
}
?>
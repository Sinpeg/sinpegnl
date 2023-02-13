<?php

class CampusDAO extends PDOConnectionFactory {

    // irá receber uma conexão

   
 /*   public function CampusDAO() {
        $this->conex = parent::getConnection();
    }*/

    public function Lista() {
        try {

            $stmt = parent::query("SELECT * FROM campus");
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscacat($codcat) {
        try {

            $stmt = parent::prepare("SELECT * FROM campus WHERE Codigo=:codigo");
            $stmt->execute(array(':codigo' => $codcat));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

   

}

?>
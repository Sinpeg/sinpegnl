<?php

class TplaboratorioDAO extends PDOConnectionFactory {

    // ir� receber uma conex�o
    public $conex = null;

    // constructor
   /*   public function TplaboratorioDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
       } 
   */

    public function Lista() {
        try {

            $stmt = parent::query("SELECT * FROM tdm_tipo_laboratorio order by Nome");
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscatipo($codtipo) {
        try {

            $stmt = parent::prepare("SELECT * FROM tdm_tipo_laboratorio WHERE Codigo=:codigo");
            $stmt->execute(array(':codigo' => $codtipo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscacattipo($cat) {
        try {

            $stmt = parent::prepare("SELECT * FROM tdm_tipo_laboratorio WHERE CodCategoria=:codigo");
            $stmt->execute(array(':codigo' => $cat));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscatipocat($tipo) {
        try {

            $stmt = parent::prepare("SELECT * FROM tdm_tipo_laboratorio WHERE Codigo=:codigo");
            $stmt->execute(array(':codigo' => $tipo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function fechar() {
        PDOConnectionFactory::Close();
    }

}

?>
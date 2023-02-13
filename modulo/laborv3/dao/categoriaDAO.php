<?php

class CategoriaDAO extends PDOConnectionFactory {

    // irá receber uma conexão
    public $conex = null;

    // constructor
   /*
    public function CategoriaDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
    }
  */
    public function Lista() {
        try {

            $stmt = parent::query("SELECT * FROM categoria");
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscacat($codcat) {
        try {

            $stmt = parent::prepare("SELECT * FROM categoria WHERE Codigo=:codigo");
            $stmt->execute(array(':codigo' => $codcat));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function listacattplab() {
        try {
            $sql = "SELECT cat.`Nome` as categoria, ttl.`Nome` as subcategoria" .
                    " FROM `tdm_tipo_laboratorio` ttl, `categoria` cat" .
                    " WHERE cat.`Codigo` = ttl.`CodCategoria`";
            $stmt = parent::query($sql);
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
<?php

class TplaboratorioDAO extends PDOConnectionFactory {

    // ir� receber uma conex�o
    public $conex = null;

    // constructor
   /*   public function TplaboratorioDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
       } 
   */

    public function Lista($ano) {
        try {
            if ($ano<=2019)
                $rsql="  validade=2019 ";
             else $rsql="  validade=2021 ";
             $sql="SELECT Codigo, Nome FROM tdm_tipo_laboratorio where ".$rsql." order by Nome";
            $stmt = parent::query($sql);
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscatipo($codigo) {
        try {

            $stmt = parent::prepare("SELECT * FROM tdm_tipo_laboratorio WHERE Codigo=:codigo");
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaCodigoNoCenso($codcenso) {
        try {

            $stmt = parent::prepare("SELECT * FROM tdm_tipo_laboratorio WHERE CodCenso=:codigo");
            $stmt->execute(array(':codigo' => $cat));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

   

   

}

?>
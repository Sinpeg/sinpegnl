<?php
class Lab_classecensoDAO extends PDOConnectionFactory {
    
    // ir� receber uma conex�o
    public $conex = null;
    
    // constructor
    /*   public function TplaboratorioDAO() {
     $this->conex = PDOConnectionFactory::getConnection();
     }
     */
    
    public function Lista() {
        try {
            
            $stmt = parent::query("SELECT * FROM lab_classecenso order by Nome");
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }
    
    public function buscatipo($codigo) {
        try {
            
            $stmt = parent::prepare("SELECT * FROM lab_classecenso WHERE Codigo=:codigo");
            $stmt->execute(array(':codigo' => $codtipo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }
    
    public function buscaCodigoNoCenso($codcenso) {
        try {
            
            $stmt = parent::prepare("SELECT * FROM lab_classecenso WHERE CodCenso=:codigo");
            $stmt->execute(array(':codigo' => $cat));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }
    
    
    
    
    
}

?>
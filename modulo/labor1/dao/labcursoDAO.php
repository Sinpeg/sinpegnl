<?php

class LabcursoDAO extends PDOConnectionFactory {

    // ir� receber uma conex�o
    public $conex = null;

    // constructor
  /*
    public function LabcursoDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
    }
 */
    // realiza uma inser��o
    public function insere($labcurso) {
        try {
            $stmt = parent::prepare("INSERT INTO `laboratorio_curso` (`CodCurso`, `CodLaboratorio`) VALUES (?, ?)");
            parent::beginTransaction();
            $stmt->bindValue(1, $labcurso->getCurso()->getCodcurso());
            $stmt->bindValue(2, $labcurso->getLaboratorio()->getCodlaboratorio());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            $db->rollback();
            print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }

    // remove um registro
    public function deleta($CodLabCurso) {
        try {
            parent::beginTransaction();
            $stmt = parent::prepare("DELETE FROM `laboratorio_curso` WHERE `CodLabCurso`=?");
            $stmt->bindValue(1, $CodLabCurso);
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaCursoLaboratorio($codcurso, $codlab) {
        try {
            $sql = "SELECT * FROM `laboratorio_curso`  " .
                    "where `CodLaboratorio`=:codigo and `CodCurso`=:codcurso ";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codlab, ':codcurso' => $codcurso));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function Lista() {
        try {

            $stmt = parent::query("SELECT * FROM laboratorio_curso");

            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaCursosLaboratorio($lab) {
        try {
            $sql = "SELECT t1.`CodCampus`,t1.`Campus`,t2.`CodCurso`,t2.`CodCursoSis`,t2.`DataInicio`,t2.`NomeCurso`,t2.`CodEmec`," .
                    " t3.`CodLabCurso`" .
                    " FROM `campus` t1,`curso` t2,`laboratorio_curso`  t3  " .
                    " where t3.`CodLaboratorio`=:codigo and t1.`CodCampus`=t2.`CodCampus` and t2.`CodCurso`=t3.`CodCurso`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $lab->getCodlaboratorio()));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    
    
    public function buscaCursosLaboratorio2($lab,$anobase) {
        try {
            $sql = "SELECT distinct t1.`CodCampus`,t1.`Campus`,t2.`CodCurso`,t2.`CodCursoSis`,t2.`DataInicio`,t2.`NomeCurso`,t2.`CodEmec`," .
                    " t3.`CodLabCurso`" .
                    " FROM `campus` t1,`curso` t2,`laboratorio_curso`  t3  " .
                    " where t3.`CodLaboratorio`=:codigo and t1.`CodCampus`=t2.`CodCampus` and t2.`CodCurso`=t3.`CodCurso`";
            if ($anobase<2018)
               $sql.=" and t3.ano=2014";
              else $sql.=" and t3.ano=2018";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $lab->getCodlaboratorio()));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }
    public function buscaCursosLaboratorio1($codlab) {
        try {
            $sql = "SELECT t1.`CodCampus`,t1.`Campus`,t2.`CodCurso`,t2.`CodCursoSis`,t2.`DataInicio`,t2.`NomeCurso`,t2.`CodEmec`," .
                    " t3.`CodLabCurso`" .
                    " FROM `campus` t1,`curso` t2,`laboratorio_curso`  t3  " .
                    " where t3.`CodLaboratorio`=:codigo and t1.`CodCampus`=t2.`CodCampus` and t2.`CodCurso`=t3.`CodCurso`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codlab));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaLaboratoriosUnidade($codunidade) {
        try {
            $stmt = parent::prepare("SELECT * FROM laboratorio_curso where CodUnidade=:codunidade ");

            $stmt->execute(array(':codunidade' => $codunidade));
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
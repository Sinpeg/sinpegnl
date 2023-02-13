<?php

class LibrasDAO extends PDOConnectionFactory {

    // ir� receber uma conex�o
    public $conex = null;

    // constructor
    /*
    public function LibrasDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
    }
    */

    public function buscaLibras($codlibras) {
        try {
            $stmt = parent::prepare("SELECT * FROM `librascurriculo` where `Codigo`=:codigo");

            $stmt->execute(array(':codigo' => $codlibras));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function deleta($l) {
        try {
            parent::beginTransaction();
            $stmt = parent::prepare("DELETE FROM `librascurriculo` WHERE `Codigo`=?");
            $stmt->bindValue(1, $l->getLibra()->getCodigo());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            $db->rollback();
            print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }

    public function Lista($query = null) {
        try {
            if ($query == null) {
                // executo a query
                $stmt = parent::query("SELECT * FROM `librascurriculo`");
            } else {
                $stmt = parent::query($query);
            }
            // desconecta
            $this->conex = null;
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaCNLibras($CodUnidade, $anobase) {
        try {
            $sql = "SELECT cur.`CodCurso` , cam.`CodCampus` codcampus, cam.`Campus` nomecampus, cur.`CodUnidade` ," .
                    " cur.`CodNivel` , cur.`CodCursoSis` , cur.`NomeCurso` , cur.`CodEmec` , cur.`DataInicio` " .
                    " FROM `campus` cam, `curso` cur " .
                    " WHERE cur.CodUnidade =:codunidade " .
                    " AND Year( cur.`DataInicio` ) <=:anobase " .
                    " AND cam.`CodCampus` = cur.`CodCampus`" .
                    " AND cur.`CodCurso` NOT IN " .
                    " (SELECT l.`CodCurso` FROM `librascurriculo` l WHERE cur.`CodCurso`=l.`CodCurso` AND l.`Ano`=:ano)";
            $stmt = parent::prepare($sql);

            $stmt->execute(array(':codunidade' => $CodUnidade, ':anobase' => $anobase, ':ano' => $anobase));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaCursosLibras($CodUnidade, $anobase) {
        try {
            $sql = "SELECT cur.`CodCurso` , cam.`CodCampus` codcampus, cam.`Campus` nomecampus, cur.`CodUnidade` ," .
                    " cur.`CodNivel` , cur.`CodCursoSis` , cur.`NomeCurso` , cur.`CodEmec` , cur.`DataInicio`,l.`Codigo` " .
                    " FROM `campus` cam, `curso` cur, `librascurriculo` l " .
                    " WHERE cur.CodUnidade =:codunidade " .
                    " AND Year( cur.`DataInicio` ) <=:anobase " .
                    " AND l.`Ano`=:ano " .
                    " AND cam.`CodCampus` = cur.`CodCampus`" .
                    " AND cur.`CodCurso` AND cur.`CodCurso`=l.`CodCurso`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codunidade' => $CodUnidade, ':anobase' => $anobase, ':ano' => $anobase));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function inseretodos($lcursos) {
        try {
            parent::beginTransaction();
            foreach ($lcursos as $l) {
                $sql = "INSERT INTO `librascurriculo` (`CodCurso`,`Ano`)  VALUES (?,?)";
                $stmt = parent::prepare($sql);
                $stmt->bindValue(1, $l->getCodcurso());
                $stmt->bindValue(2, $l->getLibra()->getAno());
                $stmt->execute();
            }
            parent::commit();
        } catch (PDOException $ex) {
            $db->rollback();
            print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }

    public function fechar() {
        PDOConnectionFactory::Close();
    }

}

?>

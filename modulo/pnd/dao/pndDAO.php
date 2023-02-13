<?php

class PndDAO extends PDOConnectionFactory {

    // ir� receber uma conex�o
    public $conex = null;

    // constructor
      public function PndDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
    }
  
    public function Lista() {
        try {

            $stmt = $this->conex->query("SELECT * FROM pnd");
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscacodigo($cod) {
        try {

            $stmt = $this->conex->prepare("SELECT * FROM pnd WHERE Codigo=:codigo");
            $stmt->execute(array(':codigo' => $cod));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscacursoano($codcurso, $ano) {
        try {

            $stmt = $this->conex->prepare("SELECT * FROM pnd WHERE CodCurso=:codigo and Ano=:ano");
            $stmt->execute(array(':codigo' => $codcurso, ':ano' => $ano));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function insere($p) {
        try {
            $sql = "INSERT INTO `pnd` (`CodCurso`,`Nopnd`,`Noatendidos`,`Ano`)  VALUES (?,?,?,?)";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindValue(1, $p->getCodcurso());
            $stmt->bindValue(2, $p->getPnd()->getNopnd());
            $stmt->bindValue(3, $p->getPnd()->getNoatendidos());
            $stmt->bindValue(4, $p->getPnd()->getAno());

            $stmt->execute();
            $this->conex->commit();
        } catch (PDOException $ex) {
            $this->conex->rollback();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function altera($f) {
        try {
            $this->conex->beginTransaction();
            $sql = "update `pnd` set `CodCurso`=?,`Nopnd`=?,`Noatendidos`=?," .
                    " where `Codigo`=?";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindValue(1, $p->getCurso()->getCodcurso);
            $stmt->bindValue(2, $p->getNopnd());
            $stmt->bindValue(3, $p->getNoatendidos());
            $stmt->bindValue(4, $p->getAno());
            $stmt->bindValue(5, $p->getCodigo());
            $stmt->execute();
            $this->conex->commit();
        } catch (PDOException $ex) {
            $this->conex->rollback();
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

<?php

class TipoprodintelectualDAO extends PDOConnectionFactory {

    // irá receber uma conexão
    public $conex = null;

    // constructor
  /*  public function TipoprodintelectualDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
    }
  */
    public function Lista($validade) {
        try {

            $stmt = parent::prepare("SELECT Codigo,Nome FROM tdm_prodintelectual where Validade=:validacao  order by Codigo");
            // retorna o resultado da query
            $stmt->execute(array(':validacao' => $validade));
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function buscatipoprodintelectual($codtpprodintelectual) {
        try {

            $stmt = parent::prepare("SELECT * FROM tdm_prodintelectual WHERE Codigo=:codigo");
            $stmt->execute(array(':codigo' => $codtpprodintelectual));
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function listaitem() {
        try {

            $stmt = parent::query("SELECT `Codigo`,`Nome` FROM `tdm_prodintelectual` WHERE `Codigo`>33 order by `Codigo` ");
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function insere($tpi) {
        try {
            parent::beginTransaction();
            $sql = "INSERT INTO `tdm_prodintelectual` (`Nome`,`Anuario`)  VALUES (?,?)";
            $stmt = parent::prepare($sql);
            $stmt->bindValue(1, $tpi->getNome());
            $stmt->bindValue(2, "N");
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function altera($tpi) {
        try {
            parent::beginTransaction();
            $sql = "UPDATE `tdm_prodintelectual` SET `Nome`=? WHERE `Codigo`=?";
            $stmt = parent::prepare($sql);
            $stmt->bindValue(1, $tpi->getNome());
            $stmt->bindValue(2, $tpi->getCodigo());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function deleta($tpi) {
        try {
            parent::beginTransaction();
            $stmt = parent::prepare("DELETE FROM `tdm_prodintelectual` WHERE `Codigo`=?");
            $stmt->bindValue(1, $tpi->getCodigo());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
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
<?php

class IncubadoraDAO extends PDOConnectionFactory {

    // irá receber uma conexão
    private $conex = null;

    // constructor
    /*
    public function IncubadoraDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
    }
    */

    public function Insere($incubadora) {
        try {
            $stmt = parent::prepare("INSERT INTO `prodincubadora` (`CodUnidade`, `Tipo`, `empresasgrad`, `empgerados`, `projaprovados`," .
                    "`eventos`, `capacitrh`, `nempreendedores`, `consultorias`, `partempfeiras`," .
                    "`Ano` ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            parent::beginTransaction();
            $stmt->bindValue(1, $incubadora->getUnidade());
            $stmt->bindValue(2, $incubadora->getTipo());
            $stmt->bindValue(3, $incubadora->getEmpresasgrad());
            $stmt->bindValue(4, $incubadora->getEmpgerados());
            $stmt->bindValue(5, $incubadora->getProjaprovados());
            $stmt->bindValue(6, $incubadora->getEventos());
            $stmt->bindValue(7, $incubadora->getCapacitrh());
            $stmt->bindValue(8, $incubadora->getNempreendedores());
            $stmt->bindValue(9, $incubadora->getConsultorias());
            $stmt->bindValue(10, $incubadora->getPartempfeiras());
            $stmt->bindValue(11, $incubadora->getAno());
            // executo a query preparada
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            print "Erro: Código: " . $ex->getCode() . " Mensagem " . $ex->getMessage();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function Lista($query = null) {
        try {
            if ($query == null) {
                // executo a query
                $stmt = parent::query("SELECT * FROM `prodIncubadora`");
            } else {
                $stmt = parent::query($query);
            }
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            //print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function buscainc($ano) {
        try {
            $stmt = parent::prepare("SELECT * FROM `prodincubadora` WHERE `Ano` =:ano");
            $stmt->execute(array(':ano' => $ano));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            //print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function altera($incubadora) {
        try {
            $sql = "UPDATE `prodincubadora` SET `empresasgrad`=?, `empgerados`=?,`projaprovados`=?," .
                    "`eventos`=?,`capacitrh`=?,`nempreendedores`=?,`consultorias`=?,`partempfeiras`=? WHERE `Codigo`=?";
            parent::beginTransaction();
            $stmt = parent::prepare($sql);
            $stmt->bindValue(1, $incubadora->getEmpresasgrad());
            $stmt->bindValue(2, $incubadora->getEmpgerados());
            $stmt->bindValue(3, $incubadora->getProjaprovados());
            $stmt->bindValue(4, $incubadora->getEventos());
            $stmt->bindValue(5, $incubadora->getCapacitrh());
            $stmt->bindValue(6, $incubadora->getNempreendedores());
            $stmt->bindValue(7, $incubadora->getConsultorias());
            $stmt->bindValue(8, $incubadora->getPartempfeiras());
            $stmt->bindValue(9, $incubadora->getCodigo());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            //print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
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

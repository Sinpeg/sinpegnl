<?php

class UnidadeDAO extends PDOConnectionFactory {

    public $conex = null;

    /*
     * Tipo de unidade:
     * P: subunidade que tem produ��o na �rea de sa�de
     * N: n�o � levada em conta
     * I: local interno
     * E: local externo
     */

    public function UnidadeDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
    }

    public function Lista($query = null) {
        try {
            if ($query == null) {
                // executo a query
                $stmt = $this->conex->query("SELECT * FROM unidade");
            } else {
                $stmt = $this->conex->query($query);
            }
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function unidadeporcodigo($codigo) {
        try {
            // executo a query
            $stmt = $this->conex->prepare("SELECT * FROM `unidade` WHERE `CodUnidade`=:codigo");
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function buscaUnidade($codunidade) {
        try {
            // executo a query
            $sql = "SELECT `NomeUnidade`,a.`Codaplicacao`,`CodEstruturado` " .
                    " FROM `unidade` u,`grupounidade` gu,`grupo` g,`aplicacoesdogrupo` a" .
                    " WHERE  u.`CodUnidade`=gu.`Codunidade` and gu.`Codgrupo`=g.`Codigo` and " .
                    " g.`Codigo`=a.`Codgrupo` and gu.`Codunidade`=:codigo";
            $stmt = $this->conex->prepare($sql);
            // desconecta
            $stmt->execute(array(':codigo' => $codunidade));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscalunidade($parametro) {
        try {
            $nome = strtoupper(addslashes($parametro));
            if (is_string($nome)) {
                // executo a query
                $nome1 = "%" . $nome .="%";
                $sql = "SELECT `CodUnidade`, `NomeUnidade` FROM `unidade` WHERE  `NomeUnidade` like :nome";
                $stmt = $this->conex->prepare($sql);
                $stmt->execute(array(':nome' => $nome1));
                return $stmt;
            }
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function buscacodestruturado($parametro) {
        try {
            $p = strtoupper(addslashes($parametro));

            if (is_string($p)) {
                $posicao = strpos($p, ".00");

                // executo a query
                $sql = "SELECT `CodUnidade`, `NomeUnidade`,`CodEstruturado` FROM `unidade` WHERE  `CodUnidade` like :codigo";
                $stmt = $this->conex->prepare($sql);
                $stmt->execute(array(':codigo' => $p));
                return $stmt;
            }
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function buscalocais() {
        try {
            // executo a query
            $sql = "SELECT `CodUnidade`, `NomeUnidade`,`TipoUnidade` FROM `unidade` WHERE  `TipoUnidade` in ('E','I')";
            $stmt = $this->conex->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    function buscasubunidades($tipo, $codestruturado) {

        try {
            $subs = substr($codestruturado, 0, 8) . "%";
            $sql = "SELECT `CodUnidade`, `NomeUnidade`,`CodEstruturado` FROM `unidade` WHERE  `TipoUnidade`=:tipo and `CodEstruturado` like :codestr";
            $stmt = $this->conex->prepare($sql);
            $stmt->execute(array(':tipo' => $tipo, ':codestr' => $subs));
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    function buscasubunidades00($codestruturado) {

        try {
            $fim = strpos($codestruturado, ".00.");
            $subs = substr($codestruturado, 0, $fim) . "%";
            $sql = "SELECT `CodUnidade`, `NomeUnidade`,`hierarquia_organizacional` FROM `unidade` WHERE  `hierarquia_organizacional` like :codestr";
            $stmt = $this->conex->prepare($sql);
            $stmt->execute(array(':codestr' => $subs));
            return $stmt;
        } catch (PDOException $ex) {
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
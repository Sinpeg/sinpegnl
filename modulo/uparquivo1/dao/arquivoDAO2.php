<?php

class ArquivoDAO extends PDOConnectionFactory {

    // ir� receber uma conex�o
    public $conex = null;

    // constructor
    public function ArquivoDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
    }

    public function buscaUnidadeAdmin($ano, $nomeunidade, $assunto) {
        try {
            addslashes($nomeunidade);
            $pnomeunidade = "%" . $nomeunidade . "%";
            $sql = "SELECT a.`Codigo`,`NomeUnidade` , `Nome` , `Conteudo` , `DataInclusao` , `DataAlteracao`," .
                    " s.`CodUsuario`,`Responsavel`,`Assunto`,`Comentario`, u.`CodUnidade`" .
                    " FROM `usuario` s, `unidade` u, `arquivo` a" .
                    " WHERE `ano` =:ano" .
                    " AND `Assunto`=:assunto" .
                    " AND `NomeUnidade` LIKE :nome" .
                    " AND s.`CodUnidade` = u.`CodUnidade`" .
                    " AND a.`CodUsuario` = s.`CodUsuario`";
            $stmt = $this->conex->prepare($sql);
            $stmt->execute(array(':nome' => $pnomeunidade, ':ano' => $ano, ':assunto' => $assunto));
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function Lista() {
        try {

            $stmt = $this->conex->query("SELECT * FROM arquivo   ");
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function buscaarquivo($ano) {
        try {

            $stmt = $this->conex->prepare("SELECT * FROM arquivo WHERE Ano=:ano");
            $stmt->execute(array(':ano' => $ano));
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function buscaAssunto($codusuario, $ano, $assunto) {
        try {

            $stmt = $this->conex->prepare("SELECT * FROM `arquivo` WHERE `Assunto`=:assunto and `Codusuario`=:codigo and `Ano`=:ano");
            $stmt->execute(array(':ano' => $ano, ':codigo' => $codusuario, ':assunto' => $assunto));
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function buscaUnidade($ano, $codigo) {
        try {
            $sql = "SELECT a.`Codigo`,`NomeUnidade`,`Nome`,s.`CodUsuario`,`Responsavel`," .
                    " `Comentario`,`Tamanho`,`DataInclusao`,`Assunto`" .
                    " FROM `arquivo` a, `usuario` s,`unidade` u " .
                    " WHERE  s.`CodUnidade`=:codigo and `Ano`=:ano and s.`CodUnidade`=u.`CodUnidade`" .
                    " and s.`CodUsuario`=a.`CodUsuario`";
            $stmt = $this->conex->prepare($sql);
            $stmt->execute(array(':codigo' => $codigo, ':ano' => $ano));
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function buscaUniUsuario($anobase, $codunidade, $codusuario) {
        try {
            $sql = "SELECT a.`Codigo`,`NomeUnidade`,`Nome`,s.`CodUsuario`,`Responsavel`, `Comentario`," .
                    " `Tamanho`,`DataInclusao`" .
                    " FROM `arquivo` a, `usuario` s,`unidade` u " .
                    " WHERE  s.`CodUnidade`=:codigo and `Ano`=:ano and s.`CodUsuario`=:codusuario and s.`CodUnidade`=u.`CodUnidade`" .
                    " and s.`CodUsuario`=a.`CodUsuario`";
            $stmt = $this->conex->prepare($sql);
            $stmt->execute(array(':codigo' => $codigo, ':ano' => $ano, ':codusuario' => $codusuario));
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function buscaCodigo($codigo) {
        try {

            $stmt = $this->conex->prepare("SELECT `Nome`,`Tipo`,`Conteudo` , `Tamanho` FROM `arquivo` WHERE  `Codigo`=:codigo");
            // INTO OUTFILE 'c://wamp//tmp//retorno.xls'
            $stmt->execute(array(':codigo' => $codigo));
            $stmt->bindColumn(1, $nome);
            $stmt->bindColumn(2, $tipo);
            $stmt->bindColumn(3, $conteudo, PDO::PARAM_LOB);
            $stmt->bindColumn(4, $tamanho);

            while ($stmt->fetch()) {
                //$nome=$codigo.".xls";
                //file_put_contents($codigo.".xls",$conteudo);
                file_put_contents($nome, $conteudo);
                header("Content-Type: " . $tipo);
                header('Pragma: no-cache');
                header('Content-Disposition: attachment; filename="' . $nome . '"');

                echo $conteudo;
            }
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function buscaporCodigo($codigo) {
        try {
            $stmt = $this->conex->prepare("SELECT `Nome`,`Tipo`,`Assunto`,`Comentario` ,`Tamanho`,`DataInclusao`,`DataAlteracao`, `Codusuario`  FROM `arquivo` WHERE  `Codigo`=:codigo");
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function buscaPorAno($ano) {
        try {
            $sql = "SELECT DISTINCT (un.`NomeUnidade`) FROM `usuario` us, `arquivo` arq, `unidade` un
                    WHERE
                      us.`CodUsuario` = arq.`Codusuario`
                     AND un.`CodUnidade`= us.`CodUnidade`
                     AND arq.`Ano` = :ano
                     ORDER BY un.`NomeUnidade`";
            $stmt = $this->conex->prepare($sql);
            $stmt->execute(array(':ano' => $ano));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function insere($u) {
        try {
            $this->conex->beginTransaction();
            $sql = "INSERT INTO `arquivo` (`Assunto`,`Tipo`,`Nome`,`Conteudo`,`Comentario`,`Codusuario`,`DataInclusao`,`Tamanho`,`Ano`) " .
                    " VALUES (?,?,?,?,?,?,STR_TO_DATE(?,'%d-%m-%Y'),?,?)";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindValue(1, $u->getArquivo()->getAssunto());
            $stmt->bindValue(2, $u->getArquivo()->getTipo());
            $stmt->bindValue(3, $u->getArquivo()->getNome());
            $stmt->bindValue(4, $u->getArquivo()->getConteudo(), PDO::PARAM_LOB);
            $stmt->bindValue(5, $u->getArquivo()->getComentario());
            $stmt->bindValue(6, $u->getCodusuario());
            $stmt->bindValue(7, $u->getArquivo()->getDatainclusao());
            $stmt->bindValue(8, $u->getArquivo()->getTamanho());
            $stmt->bindValue(9, $u->getArquivo()->getAno());
            $stmt->execute();

            $this->conex->commit();
        } catch (PDOException $ex) {
            $db->rollback();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function altera($u) {
        try {
            $this->conex->beginTransaction();
            $sql = "UPDATE `arquivo` SET `Tipo`=?,`Nome`=?,`Comentario`=?," .
                    " `Conteudo`=?,`DataAlteracao`=STR_TO_DATE(?,'%d-%m-%Y'),`Tamanho`=?  " .
                    " WHERE `Codigo`=?"; //,
            $stmt = $this->conex->prepare($sql);
            $stmt->bindValue(1, $u->getArquivo()->getTipo());
            $stmt->bindValue(2, $u->getArquivo()->getNome());
            $stmt->bindValue(3, $u->getArquivo()->getComentario());
            $stmt->bindValue(4, $u->getArquivo()->getConteudo(), PDO::PARAM_LOB);
            $stmt->bindValue(5, $u->getArquivo()->getDataalteracao());
            $stmt->bindValue(6, $u->getArquivo()->getTamanho());
            $stmt->bindValue(7, $u->getArquivo()->getCodigo());
            $stmt->execute();

            $this->conex->commit();
        } catch (PDOException $ex) {
            $db->rollback();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function deleta($codigo) {
        try {
            $this->conex->beginTransaction();
            $stmt = $this->conex->prepare("DELETE FROM `arquivo` WHERE `Codigo`=?");
            $stmt->bindValue(1, $codigo);
            $stmt->execute();
            $this->conex->commit();
        } catch (PDOException $ex) {
            $db->rollback();
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
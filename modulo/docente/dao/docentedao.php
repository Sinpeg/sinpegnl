<?php

class docentedao extends PDOConnectionFactory {
    
    public function altera( $p) {
        try {
            $stmt = parent::prepare("UPDATE `pessoa` SET `texto`=? WHERE `codigo`=?");
            parent::beginTransaction();
            $stmt->bindValue(1, $p->getTexto());
            $stmt->bindValue(2, $p->getCodigo());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            print "Erro: altera comentariopdu:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }
    
    
    public function altera31( $p) {
        try {
            $stmt = parent::prepare("UPDATE `docente31` SET `texto`=? WHERE `codigo`=?");
            parent::beginTransaction();
            $stmt->bindValue(1, $p->getTexto());
            $stmt->bindValue(2, $p->getCodigo());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            print "Erro: altera comentariopdu:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }
    
    public function insere($p) {
        try {
            $query = "INSERT INTO `docente32` ( `codarquivo`, `codtipo`,
`autor`, `texto`) VALUES (?,?,?,?)";
            $stmt = parent::prepare($query);
            parent::beginTransaction();
            $stmt->bindValue(1, $p->getCodigo());
            $stmt->bindValue(2, $p->getComentarioPDU()->getTipo());
            $stmt->bindValue(3, $p->getComentarioPDU()->getAutor()->getCodusuario());
            $stmt->bindValue(4, $p->getComentarioPDU()->getTexto());
            $stmt->execute();
            // echo parent::lastInsertId();die;
            parent::commit();
            // return parent::lastInsertId();
        } catch (PDOException $ex) {
            print "Erro: insere comentario:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }
    
    
    
    
    public function buscacPessoa($cpf) {
        try {
            $sql = "SELECT * FROM `pessoa` WHERE `cpf`=:cod ";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':cod' => $cpf));
            return $stmt;
        } catch (PDOException $ex) {
            
        }
        
    }
    
    
    public function buscaDocente31($idpessoa, $codtempo) {
        try {
            $sql = "SELECT * FROM `docente31` WHERE iddocente=:cod  and codtempo=:codtempo";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':cod' => $idpessoa,':codtempo' => $codtempo));
            return $stmt;
        } catch (PDOException $ex) {
            
        }
        
    }
    
    public function buscaDocente32($iddocente) {
        try {
            $sql = "SELECT c.idcursocenso, c.nome,c.idcursoinep,iddocente, modalidade FROM `docente32` d join cursocenso c on c.idcursocenso=d.idCursoinep
              WHERE iddocente=:cod";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':cod' => $iddocente));
            return $stmt;
        } catch (PDOException $ex) {
            
        }
        
    }
    
    
    public function buscaCursoCenso($codigoemec) {
        try {
            $sql = "SELECT * FROM cursocenso 
              WHERE idcursoinep=:codigo";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigoemec));
            return $stmt;
        } catch (PDOException $ex) {
            
        }
        
    }
    
    public function buscaTempo($ano) {
        try {
            $sql = "SELECT * FROM tempo WHERE ano=:ano and mes=1";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':ano' => $ano));
            return $stmt;
        } catch (PDOException $ex) {
            
        }
        
    }
    
    public function buscaCursos($codtempo) {
        try {
            $sql = "SELECT * FROM cursosatuacao a join cursocenso o on o.idcursocenso=a.idcursocenso WHERE  codtempo=:codtempo";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codtempo' => $codtempo));
            return $stmt;
        } catch (PDOException $ex) {
            
        }
        
    }
    public function buscaPais() {
        try {
            $sql = "SELECT `idPais`, `nome` FROM `pais` order by 2";
            $stmt = parent::prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $ex) {
            
        }
        
    }
    
    public function buscaPaisCodigo($codigo) {
        try {
            $sql = "SELECT `idPais`, `nome` FROM `pais` WHERE idPais=:codigo order by 2";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            
        }
        
    }
    
    
    public function buscaUf() {
        try {
            $sql = "SELECT `idUf`, `nome` FROM `uf` order by 2";
            $stmt = parent::prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $ex) {
            
        }
        
    }
    
    public function buscaUfCodigo($uf) {
        try {
            $sql = "SELECT `idUf`, `nome` FROM `uf` where idUf=:uf";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':uf' => $uf));
            return $stmt;
        } catch (PDOException $ex) {
            
        }
        
    }
    
    
    public function buscaMunicipio() {
        try {
            $sql = "SELECT `idMunicipio`, `idUf`, `nome` FROM `municipio` order by 3";
            $stmt = parent::prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $ex) {
            
        }
        
    }
    
    public function buscaMunicipioCodigos($uf) {
        try {
            $sql = "SELECT `idMunicipio`, `idUf`, `nome` FROM `municipio` WHERE idUf=:uf ";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':uf' => $uf));
            return $stmt;
        } catch (PDOException $ex) {
            
        }
        
    }
}

?>



	


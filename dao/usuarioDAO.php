<?php

class UsuarioDAO extends PDOConnectionFactory {

    // irá receber a conexão 
    private $conex = null;

    // constructor
    //   public function UsuarioDAO() {
    //    $this->conex = PDOConnectionFactory::getConnection();
    //}

    // realiza uma inserção
    public function Insere($usuario) {
        try {
            parent::beginTransaction();
            $stmt = parent::prepare("INSERT INTO `usuario` (`CodUnidade`,`Responsavel`,`Login`,`Senha`,`Email`) VALUES (?, ?, ?, ?,?)");
            $stmt->bindValue(1, $usuario->getUnidade()->getCodunidade());
            $stmt->bindValue(2, $usuario->getResponsavel());
            $stmt->bindValue(3, $usuario->getLogin());
            $stmt->bindValue(4, $usuario->getSenha());
            $stmt->bindValue(5, $usuario->getEmail());
            $stmt->execute();
            parent::commit();
            exit();
        } catch (PDOException $ex) {
            parent::rollback();
        }
    }

    public function insere1($usuario) {
        try {
            parent::beginTransaction();
            $stmt = parent::prepare("INSERT INTO `usuario` (`CodUnidade`,`Responsavel`,`Login`,`Senha`,`Email`) VALUES (?, ?, ?, ?,?)");
            $stmt->bindValue(1, $usuario->getUnidade()->getCodunidade());
            $stmt->bindValue(2, $usuario->getResponsavel());
            $stmt->bindValue(3, $usuario->getLogin());
            $stmt->bindValue(4, $usuario->getSenha());
            $stmt->bindValue(5, $usuario->getEmail());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
        }
    }

    // realiza um Update
    // Esta versão altera somente o nome do usuário, senha e e-mail
    public function altera($usuario) {
        try {
            $stmt = parent::prepare("UPDATE `usuario` SET  `Responsavel`=?, `Senha`=?,`Email`=?  WHERE `CodUsuario`=?");
            parent::beginTransaction();
            $stmt->bindValue(1, $usuario->getResponsavel());
            $stmt->bindValue(2, $usuario->getSenha());
            $stmt->bindValue(3, $usuario->getEmail());
            $stmt->bindValue(4, $usuario->getCodUsuario());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            exit();
        }
    }
    
    public function defUnidadeUser($usuario,$unidade) {
        try {
            $stmt = parent::prepare("UPDATE `usuario` SET  `CodUnidade`=? WHERE `CodUsuario`=?");
            parent::beginTransaction();
            $stmt->bindValue(1, $unidade);
            $stmt->bindValue(2, $usuario);
            
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            exit();
        }
    }

    // realiza um update
    // Esta versão altera somente o nome do usuário, senha, e-mail e login
    public function altera1($usuario) {
        try {
            $stmt =parent::prepare("UPDATE `usuario` SET  `Responsavel`=?, `Senha`=?,`Email`=?,`Login`=?  WHERE `CodUsuario`=?");
            parent::beginTransaction();
            $stmt->bindValue(1, $usuario->getResponsavel());
            $stmt->bindValue(2, $usuario->getSenha());
            $stmt->bindValue(3, $usuario->getEmail());
            $stmt->bindValue(4, $usuario->getLogin());
            $stmt->bindValue(5, $usuario->getCodUsuario());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
        }
    }
    
    // realiza um update
    // Esta versão altera nome do usuário, senha, e-mail, login e código da unidade
    public function altera2($usuario) {
                try {
            $stmt = parent::prepare("UPDATE `usuario` SET `CodUnidade`=?, `Responsavel`=?, `Senha`=?,`Email`=?,`Login`=?  WHERE `CodUsuario`=?");
            parent::beginTransaction();
            $stmt->bindValue(1, $usuario->getUnidade()->getCodunidade());
            $stmt->bindValue(2, $usuario->getResponsavel());
            $stmt->bindValue(3, $usuario->getSenha());
            $stmt->bindValue(4, $usuario->getEmail());
            $stmt->bindValue(5, $usuario->getLogin());
            $stmt->bindValue(6, $usuario->getCodUsuario());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
        }
    }

    public function buscaLogin($login) {
        try {
            $sql = "SELECT un.`CodUnidade`,un.`NomeUnidade` as nome,`Responsavel`,`CodUsuario`,`Email`,`Login` " .
                    " FROM `unidade` un, `usuario` us WHERE `Login` =:login and un.`CodUnidade`=us.`CodUnidade`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':login' => $login));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
//            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
//            header($cadeia);
//            exit();;
        }
    }
    
    public function buscaCodUnidade($unidade) {
    	try {
    		$sql = "SELECT Login FROM `usuario` WHERE `CodUnidade` = :codunidade";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codunidade' => $unidade));
    		// retorna o resultado da query
    		return $stmt;
    	} catch (PDOException $ex) {
    		$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
    		//            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
    		//            header($cadeia);
    		//            exit();;
    	}
    }
    
    public function buscaNome($unidade) {
    	try {
    		$sql = "SELECT * FROM `unidade` WHERE `CodUnidade`= :codunidade";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codunidade' => $unidade));
    		// retorna o resultado da query
    		return $stmt;
    	} catch (PDOException $ex) {
    		$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
    		//            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
    		//            header($cadeia);
    		//            exit();;
    	}
    }

    public function buscaLoginUnidade($login, $codunidade) {
        try {
            $sql = "SELECT un.`CodUnidade` , un.`NomeUnidade` , `Responsavel` , `CodUsuario` , `Email` , `Login` \n"
                    . "FROM `unidade` un, `usuario` us\n"
                    . "WHERE `Login` = :login\n"
                    . "AND un.`CodUnidade` = us.`CodUnidade` \n"
                    . "AND un.`CodUnidade` = :codunidade";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':login' => $login, ':codunidade'=>$codunidade));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
//            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
//            header($cadeia);
//            exit();;
        }
    }

    public function buscaUnidadeLogin($unidade, $hierarquia) {
        try {
        	$temporario = strtoupper(addslashes($hierarquia));
        	$subunidades = $temporario."%";
        	
            $sql = "SELECT u.`CodUnidade` , `NomeUnidade` , `Login`, s.`CodUsuario`" .
                    " FROM `unidade` u, `usuario` s" .
                    " WHERE u.`hierarquia_organizacional` LIKE :hierarquia AND" .
                    " u.`CodUnidade` = s.`CodUnidade`" .
                    "AND `NomeUnidade` LIKE :unidade";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':unidade' => $unidade, ':hierarquia' => $subunidades));
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage());
        }
    }

    public function buscaCodigoLogin($login, $codigo) {
        try {
            $sql = "SELECT un.`CodUnidade`,un.`NomeUnidade`,`Responsavel`,`CodUsuario`,`Email`,`Login` " .
                    " FROM `unidade` un, `usuario` us WHERE `Login` =:login " .
                    " and `CodUsuario`=:codigo and un.`CodUnidade`=us.`CodUnidade`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':login' => $login, ':codigo' => $codigo));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
//            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
//            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
//            header($cadeia);
//            exit();
        }
    }

    public function buscaUsuario($login) {
        try {//date_format( current_date() , '%d-%m-%Y' ) as data
            $stmt = parent::prepare("SELECT date_format( current_date() , '%d-%m-%Y' ) as data,`CodUnidade`,`Responsavel`,`CodUsuario`,`Email`,`Login`, `Senha`, `categoria` FROM `usuario` WHERE `Login` =:login");
            $stmt->bindParam(':login', $login);
            $stmt->execute();
            // retorna o resultado da query
            return $stmt->fetch();
            //return $stmt;
        } catch (PDOException $ex) {
//            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
//            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
//            header($cadeia);
        }
    }

    public function buscapunidade($codigo) {
        try {

            $stmt = parent::prepare("SELECT `CodUnidade`,`Responsavel`,`CodUsuario`,`Email`,`Login` FROM `usuario` WHERE `CodUnidade` =:codigo ");
            $stmt->execute(array(':codigo' => $codigo));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
//            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
//            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
//            header($cadeia);
        }
    }

    public function buscaemail($email) {
        try {
            $sql = "SELECT * FROM `usuario` WHERE `Email` = :email";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':email' => $email));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
//            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
//            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
//            header($cadeia);
//            exit();
        }
    }

    public function buscaunidade() {
        try {
            $sql = "SELECT n.`CodUnidade`,`Responsavel`,`CodUsuario`,`Email`,`Login`,`NomeUnidade`" .
                    " FROM `unidade` n, `usuario` u" .
                    " WHERE u.`CodUnidade`=n.`CodUnidade` order by  `NomeUnidade`";
            $stmt = parent::prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function fechar() {
        PDOConnectionFactory::Close();
    }
    
    //Gera chave de recuperação de senha
    function geraChaveAcesso($nomeUnidade){
    	$stmt = parent::prepare("SELECT n.`CodUnidade`,`CodUsuario`,`Email`,`Senha`,`Login`,`NomeUnidade`
    			 				 FROM `unidade` n, `usuario` u  WHERE `NomeUnidade`=:nomeUnidade AND  u.`CodUnidade`=n.`CodUnidade`");
    	$stmt->bindValue(":nomeUnidade",$nomeUnidade);
    	$run = $stmt->execute();
    
    	$rs = $stmt->fetch(PDO::FETCH_ASSOC);
    	 
    	if($rs){
    		$chave = sha1($rs["CodUnidade"].$rs["Senha"]);
    		return $chave;
    	}    
    }
    
    //Validar chave de recuperar senha
    function checkChave($usuario,$chave){
		   $stmt = parent::prepare("SELECT * FROM usuario WHERE Login = :usuario");
		   $stmt->bindValue(":usuario",$usuario);
		   $run = $stmt->execute();
		 
		   $rs = $stmt->fetch(PDO::FETCH_ASSOC);
		 
		   if($rs){
		     $chaveCorreta = sha1($rs["CodUnidade"].$rs["Senha"]);
		     if($chave == $chaveCorreta){
		        return $rs["CodUsuario"];
		     }
		   }
    }
    
    //Atualizar a senha de recuperar senha
    function setNovaSenha($nova_senha,$CodUsuario){
    	$stmt = parent::prepare("UPDATE usuario SET Senha = :novasenha WHERE CodUsuario = :id");
    	$stmt->bindValue(":novasenha",md5($nova_senha));
    	$stmt->bindValue(":id",$CodUsuario);
    	$run = $stmt->execute();
    }
    
    
    //buscar e-mail por nome da unidade
    function buscarEmailUnidade($nomeUnidade){    	
    	$stmt = parent::prepare("SELECT n.`CodUnidade`,`CodUsuario`,`Email`,`Login`,`NomeUnidade`
    			 				 FROM `unidade` n, `usuario` u  WHERE `NomeUnidade`=:nomeUnidade AND  u.`CodUnidade`=n.`CodUnidade` AND u.categoria=1");
    	$stmt->bindValue(":nomeUnidade",$nomeUnidade);
    	$run = $stmt->execute();
    
    	$rs = $stmt->fetch(PDO::FETCH_ASSOC);
    	
    	if($rs){
    		$email = $rs['Email'];
    		return $email;
    	}
    }
    
    //buscar login por nome da unidade
    function buscarLoginUnidade($nomeUnidade){
    	$stmt = parent::prepare("SELECT n.`CodUnidade`,`CodUsuario`,`Email`,`Login`,`NomeUnidade`
    			 				 FROM `unidade` n, `usuario` u  WHERE `NomeUnidade`=:nomeUnidade AND  u.`CodUnidade`=n.`CodUnidade` AND u.categoria=1");
    	$stmt->bindValue(":nomeUnidade",$nomeUnidade);
    	$run = $stmt->execute();
    
    	$rs = $stmt->fetch(PDO::FETCH_ASSOC);
    
    	if($rs){
    		$login = $rs['Login'];
    		return $login;
    	}
    }
    
    public function buscarUsuario($codUsuario) {
    	try {
    		$sql = "SELECT * FROM `usuario` WHERE `CodUsuario` = :user";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':user' => $codUsuario));
    		// retorna o resultado da query
    		return $stmt;
    	} catch (PDOException $ex) {
    		$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais    		
    	}
    }

}

?>
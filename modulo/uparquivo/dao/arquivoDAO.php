<?php
class ArquivoDAO extends PDOConnectionFactory {

    // irï¿½ receber uma conexï¿½o
    private $mysqli;
    // constructor
    public function __construct() {
    	 
    	$this->mysqli = new mysqli(PDOConnectionFactory::getHost(), PDOConnectionFactory::getUser(), PDOConnectionFactory::getSenha(), PDOConnectionFactory::getDb());
    	    	        $this->mysqli->set_charset('utf8');
    	
    	// Caso algo tenha dado errado, exibe uma mensagem de erro
    	if (mysqli_connect_errno()) {
    		printf("Connect failed: %s\n", mysqli_connect_error());
    		exit();
    	}else{
    		//echo "conectou";
    	}
    	
    	 
    }

    
public function buscaUnidadeAdmin1($ano,  $assunto) {
        try {
            addslashes($nomeunidade);
            $pnomeunidade = "%" . $nomeunidade . "%";
            $sql = "SELECT a.`Codigo`,`NomeUnidade` , `Nome` , `Conteudo` , `DataInclusao` , `DataAlteracao`," .
                    " s.`CodUsuario`,`Responsavel`,`Assunto`,`Comentario`, u.`CodUnidade`" .
                    " FROM `usuario` s, `unidade` u, `arquivo` a" .
                    " WHERE `ano` =:ano" .
                    " AND `Assunto`=:assunto" .
                //    " AND `NomeUnidade` LIKE :nome" .
                    " AND s.`CodUnidade` = u.`CodUnidade`" .
                    " AND a.`CodUsuario` = s.`CodUsuario`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array( ':ano' => $ano, ':assunto' => $assunto));//':nome' => $pnomeunidade,
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
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
            $stmt = parent::prepare($sql);
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

            $stmt = parent::query("SELECT * FROM arquivo   ");
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

            $stmt = parent::prepare("SELECT * FROM arquivo WHERE Ano=:ano");
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

            $stmt = parent::prepare("SELECT * FROM `arquivo` WHERE `Assunto`=:assunto and `Codusuario`=:codigo and `Ano`=:ano");
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
            $stmt = parent::prepare($sql);
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
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo, ':ano' => $ano, ':codusuario' => $codusuario));
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

   	public function buscaCodigo($codigo){
   		
   		$query=$this->mysqli->query("SELECT `Nome`,`Tipo`,`Conteudo` ,`Tamanho` FROM `arquivo` 	WHERE `Codigo`='$codigo'");
   		
   		return $query;
   		 
   	}


   	public function insere( $u ){
   	$assunto=$u->getArquivo()->getAssunto();
   	$tipo=$u->getArquivo()->getTipo();
    $nome=$u->getArquivo()->getNome();
  //  $conteudo=$u->getArquivo()->getConteudo();
    $comentario=$u->getArquivo()->getComentario();
    $codusuario=$u->getCodusuario();
    $data=$u->getArquivo()->getDatainclusao();
    $tamanho=$u->getArquivo()->getTamanho();
    $ano=$u->getArquivo()->getAno();
   	
   	$query = $this->mysqli->query("INSERT INTO `arquivo` (`Assunto`,`Tipo`,`Nome`,`Comentario`,".
   			"`Codusuario`,`DataInclusao`,`Tamanho`,`Ano`) ".
   				" VALUES ('$assunto','$tipo','$nome','$comentario',".
   			"'$codusuario',STR_TO_DATE('$data','%d-%m-%Y'),'$tamanho','$ano')");
   	  	
 //  	printf("Affected rows (INSERT): %d\n", $this->mysqli->affected_rows);
   		
/*   	try{
   			$this->conex->beginTransaction();
   			$sql="INSERT INTO `arquivo` (`Assunto`,`Tipo`,`Nome`,`Conteudo`,`Comentario`,`Codusuario`,`DataInclusao`,`Tamanho`,`Ano`) ".
   		  " VALUES (?,?,?,?,?,?,STR_TO_DATE(?,'%d-%m-%Y'),?,?)";
   			$stmt = $this->conex->prepare($sql);
   			$stmt->bindValue(1,$u->getArquivo()->getAssunto());
   			$stmt->bindValue(2,$u->getArquivo()->getTipo());
   			$stmt->bindValue(3,$u->getArquivo()->getNome());
   			$stmt->bindValue(4,$u->getArquivo()->getConteudo(),PDO::PARAM_LOB);
   			$stmt->bindValue(5,$u->getArquivo()->getComentario());
   			$stmt->bindValue(6,$u->getCodusuario());
   			$stmt->bindValue(7,$u->getArquivo()->getDatainclusao());
   			$stmt->bindValue(8,$u->getArquivo()->getTamanho());
   			$stmt->bindValue(9,$u->getArquivo()->getAno());
   			$stmt->execute();

   			$this->conex->commit();
   		}catch ( PDOException $ex ){
   			$db->rollback();
   			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
   			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
   			header($cadeia);
   		}*/
   	}


    public function buscaporCodigo($codigo) {
        try {
            $stmt = parent::prepare("SELECT `Nome`,`Tipo`,`Assunto`,`Comentario` ,`Tamanho`,`DataInclusao`,`DataAlteracao`, `Codusuario`  FROM `arquivo` WHERE  `Codigo`=:codigo");
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
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':ano' => $ano));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }




    
    
    public function altera($u) {
     $tipo=$u->getArquivo()->getTipo();
     $nome= $u->getArquivo()->getNome();
     $comentario= $u->getArquivo()->getComentario();
  //   $conteudo= $u->getArquivo()->getConteudo();
     $tamanho= $u->getArquivo()->getTamanho();
     $codigo= $u->getArquivo()->getCodigo();
     $data=$u->getArquivo()->getDataalteracao();
      $query="UPDATE `arquivo` SET `Tipo`='$tipo',`Nome`='$nome',`Comentario`='$comentario', 
      `DataAlteracao`=STR_TO_DATE('$data','%d-%m-%Y'),`Tamanho`='$tamanho' WHERE `Codigo`='$codigo'";
             $query = $this->mysqli->query($query);
                //   echo "dd".$codigo;die;
             
         // $linhas=$this->$mysqli->affected_rows;
                       mysqli_close($this->mysqli);
//       echo $linhas;die;
    
    }
    
    
    
    

    public function deleta($codigo) {
        try {
            parent::beginTransaction();
            $stmt = parent::prepare("DELETE FROM `arquivo` WHERE `Codigo`=?");
            $stmt->bindValue(1, $codigo);
            $stmt->execute();
            parent::commit();
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
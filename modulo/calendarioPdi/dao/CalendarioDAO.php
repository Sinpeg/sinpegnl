<?php
class CalendarioDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	private $conex = null;

	  // constructor
	 //  public function BibliocensoDAO(){
	//	 $this->conex = PDOConnectionFactory::getConnection();
   //	}

	public function deleta( $c ){
		try{
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `calendario` WHERE `codCalendario`=?");
			$stmt->bindValue(1, $c->getCodigo());
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			echo $ex->getCode()."&mensagem=".$mensagem;
		}
	}
	
    public function listaCalendarioPorDoc($codigodoc) {
		try {
			$sql = "SELECT * ".
           " FROM `calendario`  ".
           " WHERE `CodDocumento`=:doc order by `anoGestao` desc";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':doc' =>$codigodoc));	 

			// retorna o resultado da query
			return $stmt;
		} catch (PDOException $ex) {
			$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
			echo $ex->getCode() . "&mensagem=" . $mensagem." XXXXX ";
			header($cadeia);
		}
	}
	public function buscaCalendarioporAnoBaseEFormulario($anobase,$atividade) {
	    try {
	       switch ($atividade) {
	           case 1: //raa
	               $sql = "SELECT  c.codCalendario,c.`anoGestao` FROM  `calendario` c
                           WHERE  c.`anoGestao`=:ano and
                           dataIniAnaliseRAA<=now() and dataFimAnaliseRAA>=now()";            
	               break;
	           case 2://Solicitacao de alteracao pdu
	               $sql = "SELECT  c.codCalendario,c.`anoGestao` FROM  `calendario` c
                           WHERE  c.`anoGestao`=:ano and
                           dataIniAlteraPDU<=now() and dataFimAlteraPDU>=now()";
	               break;
	           case 3://Elaboração do pdu
	               $sql = "SELECT  c.codCalendario,c.`anoGestao` FROM  `calendario` c
                           WHERE  c.`anoGestao`=:ano and
                           dataInicioElabPDU<=now() and dataFimElabPDU>=now()";
	               break;
	           case 4://Elaboracao do painel tatico
	               $sql = "SELECT  c.codCalendario,c.`anoGestao` FROM  `calendario` c
                           WHERE  c.`anoGestao`=:ano and
                           dataInicioElabPT<=now() and dataFimElabPT>=now()";
	               break;
	           case 5://Lancamento resultado final
	               $sql = "SELECT  c.codCalendario,c.`anoGestao` FROM  `calendario` c
                           WHERE  c.`anoGestao`=:ano and
                           datainiAnaliseFinal<=now() and datafimAnaliseFinal>=now()";
	               break;
	           case 6://Lancamento resultado parcial
	               $sql = "SELECT  c.codCalendario,c.`anoGestao` FROM  `calendario` c
                           WHERE  c.`anoGestao`=:ano and
                           dataIniAnaliseParcial<=now() and dataFimAnaliseParcial>=now()";
	               break;
	       }
	        $stmt = parent::prepare($sql);
	        $stmt->execute(array(':ano' =>$anobase));
	        // retorna o resultado da query
	        return $stmt;
	    } catch (PDOException $ex) {
	        $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
	        echo $ex->getCode() . "&mensagem=" . $mensagem." buscaCalendarioporAnoBaseOnly ";
	        header($cadeia);
	    }
	}
	
	
	
	
public function buscaCalendarioporAnoBaseOnly($anobase) {
		try {
			$sql = "SELECT  c.codCalendario,c.`anoGestao` FROM  `calendario` c WHERE  c.`anoGestao`=:ano";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano' =>$anobase));
			// retorna o resultado da query
			return $stmt;
		} catch (PDOException $ex) {
			$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
			echo $ex->getCode() . "&mensagem=" . $mensagem." buscaCalendarioporAnoBaseOnly ";
			header($cadeia);
		}
	}
    public function listaCalendarioUnidTipo1($anobase) {
		try {
			$sql = "SELECT d.`codigo` as dcodigo, d.`nome` as ndocumento,`anoGestao`, c.codCalendario ,".
               " `anoinicial`, `anofinal` ".
           " FROM `documento` d inner join `calendario` c on c.`codDocumento`= d.`codigo` ".
           " WHERE d.`situacao`='A' and d.tipo=1 and `anoGestao`=:ano ".
                " and (d.anoinicial<=:ano and d.anofinal>=:ano) order by d.`codigo`, `anoGestao` desc";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano'=>$anobase));	 

			// retorna o resultado da query
			return $stmt;
		} catch (PDOException $ex) {
			$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
			echo $ex->getCode() . "&mensagem=" . $mensagem." XXXXX ";
			header($cadeia);
		}
	}
	
    
	public function listaCalendario($uni) {
		try {
			$sql = "SELECT d.`codigo` as dcodigo, d.`nome` as ndocumento,`anoGestao`, c.codCalendario ,"." `anoinicial`, `anofinal` ".
           " FROM `documento` d inner join `calendario` c on c.`codDocumento`= d.`codigo` ".
           " WHERE d.`situacao`='A' and  d.`CodUnidade`=:uni order by d.`codigo`, `anoGestao` desc";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':uni' =>$uni->getCodunidade()));	 

			// retorna o resultado da query
			return $stmt;
		} catch (PDOException $ex) {
			$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
			echo $ex->getCode() . "&mensagem=" . $mensagem." XXXXX ";
			header($cadeia);
		}
	}
	
	//Busca calendário por ano base e unidade
	public function listaCalendario1($uni,$anoBase) {
		try {
			$sql = "SELECT d.`codigo` as dcodigo, d.`nome` as ndocumento,`anoGestao`, c.codCalendario ,`anoinicial`, `anofinal` 
					FROM `documento` d inner join `calendario` c on c.`codDocumento`= d.`codigo` 
					WHERE d.`situacao`='A' and d.`CodUnidade`=:uni and :ano BETWEEN `anoinicial` and `anofinal` order by d.`codigo`, `anoGestao` desc ";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':uni' =>$uni->getCodunidade(),':ano'=>$anoBase));
	
			// retorna o resultado da query
			return $stmt;
		} catch (PDOException $ex) {
			$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
			echo $ex->getCode() . "&mensagem=" . $mensagem."  ";
		}
	}
	
  public function buscaCalendarioporCod($codigo) {
		try {
			$sql = "SELECT * ".
           " FROM  `calendario`  ".
           " WHERE  `CodCalendario`=:cod ";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':cod' =>$codigo));	 

			// retorna o resultado da query
			return $stmt;
		} catch (PDOException $ex) {
			$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
			echo $ex->getCode() . "&mensagem=" . $mensagem."  ";
			header($cadeia);
		}
	}
	
       public function buscaCalendarioporUniDoc($uni) {
		try {
			$sql = "SELECT * ".
           " FROM  `calendario`  ".
           " WHERE  `CodUnidade`=:uni and `CodDocumento`=:doc ";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':uni' =>$uni->getCodunidade(),':doc' =>$uni->getDocumento()->getCodigo()));	 

			// retorna o resultado da query
			return $stmt;
		} catch (PDOException $ex) {
			$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
			echo $ex->getCode() . "&mensagem=" . $mensagem." XXXXX ";
			die;
 		}
	}
	
 public function buscaCalendarioporUniDocAno($codunidade,$anogestao,$coddoc) {
		try {
			$sql = "SELECT * 
            FROM  `calendario`  
            WHERE  `CodUnidade`=:uni and `CodDocumento`=:doc and `anoGestao`=:ano";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':uni' =>$codunidade,':doc' =>$coddoc,':ano' =>$anogestao));	 
			// retorna o resultado da query
			return $stmt;
		} catch (PDOException $ex) {
			$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
			echo $ex->getCode() . "&mensagem=" . $mensagem." XXXXX ";
			die;
		}
	}
      public function buscaCalendarioporAnoBase($anobase,$coddoc) {
		try {
			$sql = "SELECT  c.codCalendario,c.`anoGestao`, c.datainiAnaliseFinal, c.datafimAnaliseFinal".
" FROM  `calendario` c inner join documento d  on d.codigo=c.codDocumento".
" WHERE  c.`anoGestao`=:ano and ".
" c.`codDocumento`=:doc ";;
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano' =>$anobase,':doc' =>$coddoc));	 
			// retorna o resultado da query
			return $stmt;
		} catch (PDOException $ex) {
			$mensagem = $ex->getMessage(); //para aceitar caracteres especiais
			echo $ex->getCode() . "&mensagem=" . $mensagem." buscaCalendarioporAnoBase ";
			header($cadeia);
		}
	}
	
	
    public function verificaPrazoCalendarioDoDocumento($anobase) {
      //  if ($periodo==1){
            $sql = "SELECT codigo,nome,anoinicial,anofinal,situacao,missao,visao, codCalendario,anoGestao,".
                " case when  (now()>=`dataIniAnaliseParcial` and now()<=`dataFimAnaliseParcial`) then 'Parcial' ".
                "   when  (now()>=`dataIniAnaliseFinal` and now()<=`dataFimAnaliseFinal`) then 'Final' ".
                " ELSE 'Nenhuma' END as habilita".
                " FROM  `calendario` c inner join documento d  on d.codigo=c.codDocumento".
                " WHERE  c.`anoGestao`=:ano and  d.`tipo`=1  ";
 
		try {
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano' =>$anobase));	 
			// retorna o resultado da query
			return $stmt;
		} catch (PDOException $ex) {
			$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
			echo $ex->getCode() . "&mensagem=" . $mensagem." verificaPrazoCalendarioDoDocumento ";
		}
	}
	
 public function verificaPrazoCalendarioDoRAA($anobase) {
      //  if ($periodo==1){
            $sql = "SELECT  codCalendario,anoGestao,".
                " case when  (curdate()>=`dataIniAnaliseRAA` and curdate()<=`dataFimAnaliseRAA`) then 'H' ".
                " ELSE 'D' END as habilita".
                " FROM  `calendario` c ".
                " WHERE  c.`anoGestao`=:ano ";
 
		try {
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano' =>$anobase));	 
			// retorna o resultado da query
			return $stmt;
		} catch (PDOException $ex) {
			$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
			echo $ex->getCode() . "&mensagem=" . $mensagem." verificaPrazoCalendarioDoRAA ";
		}
	}
	
	public function verificaPrazoCalendarioDoResultado($anobase) {
	    //  if ($periodo==1){
	    $sql = "SELECT  codCalendario,anoGestao,".
	   	    " case when  (curdate()>=datainiAnaliseFinal and curdate()<=`datafimAnaliseFinal`) then 'H' ".
	   	    " ELSE 'D' END as habilita".
	   	    " FROM  `calendario` c ".
	   	    " WHERE  c.`anoGestao`=:ano ";
	    
	    try {
	        $stmt = parent::prepare($sql);
	        $stmt->execute(array(':ano' =>$anobase));
	        // retorna o resultado da query
	        return $stmt;
	    } catch (PDOException $ex) {
	        $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
	        echo $ex->getCode() . "&mensagem=" . $mensagem." verificaPrazoCalendarioDoRAA ";
	    }
	}
	
	
	public function verificaPrazoCalendarioElabPDU($anobase) {
	    $sql = "SELECT  codCalendario,anoGestao,
	   	    case when  (curdate()>=`dataInicioElabPDU` and curdate()<=`dataFimElabPDU`) then 'H' 
	   	    ELSE 'D' END as habilita
	   	    FROM  `calendario` c 
	   	    WHERE  c.`anoGestao`=:ano ";
	    
	    try {
	        $stmt = parent::prepare($sql);
	        $stmt->execute(array(':ano' =>$anobase));
	        // retorna o resultado da query
	        return $stmt;
	    } catch (PDOException $ex) {
	        $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
	        echo "Erro verificaPrazoCalendarioElabPDU ".$ex->getCode() . "&mensagem=" . $mensagem."  ";
	    }
	}
	
	public function verificaPrazoCalendarioElabPT($anobase) {
	    //  if ($periodo==1){
	    $sql = "SELECT  codCalendario,anoGestao,
	   	     case when  (curdate()>=`dataInicioElabPT` and curdate()<=`dataFimElabPT`) then 'H' 
	   	     ELSE 'D' END as habilita
	   	     FROM  `calendario` c 
	   	     WHERE  c.`anoGestao`=:ano ";
	    
	    try {
	        $stmt = parent::prepare($sql);
	        $stmt->execute(array(':ano' =>$anobase));
	        // retorna o resultado da query
	        return $stmt;
	    } catch (PDOException $ex) {
	        $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
	        echo "Erro verificaPrazoCalendarioElabPT ".$ex->getCode() . "&mensagem=" . $mensagem."  ";
	    }
	}
	
	
    public function buscaCalendarSomenteioPorAnoBase($anobase) {
		try {
			$sql = "SELECT * ".
					" FROM  `calendario`  ".
					" WHERE  `anoGestao`=:anbas ";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':anbas' =>$anobase));
			
			// retorna o resultado da query
			return $stmt;
		} catch (PDOException $ex) {
			$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
			echo $ex->getCode() . "&mensagem=" . $mensagem." buscaCalendarSomenteioPorAnoBase ";
		}
	}

	public function insere( $be ){
		try{
			parent::beginTransaction();
			$sql="INSERT INTO `calendario` (`CodUnidade`,`CodDocumento`, `anoGestao`, `dataIniAnaliseParcial`, `dataFimAnaliseParcial`
			
			,`datainiAnaliseFinal`, `datafimAnaliseFinal`, `dataIniAnaliseRAA`, `dataFimAnaliseRAA`

,`dataInicioElabPDU`,`dataFimElabPDU`,`dataInicioElabPT`,`dataFimElabPT`,dataIniAlteraPDU,dataFimAlteraPDU
,`codusuario`)  VALUES 
(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			
			
                $stmt = parent::prepare($sql);
				$stmt->bindValue(1,$be->getCodUnidade());
				$stmt->bindValue(2,$be->getDocumento()->getCodigo());
				$stmt->bindValue(3,$be->getDocumento()->getCalendario()->getAnogestao());
				$stmt->bindValue(4,$be->getDocumento()->getCalendario()->getDatainianalise());
				$stmt->bindValue(5,$be->getDocumento()->getCalendario()->getDatafimanalise());
				
				$stmt->bindValue(6,$be->getDocumento()->getCalendario()->getDatainianalisefinal());
				$stmt->bindValue(7,$be->getDocumento()->getCalendario()->getDatafimanalisefinal());
				
				$stmt->bindValue(8,$be->getDocumento()->getCalendario()->getDatainiRAA());
				$stmt->bindValue(9,$be->getDocumento()->getCalendario()->getDatafimRAA());
				
				$stmt->bindValue(10,$be->getDocumento()->getCalendario()->getDatainielabpdu());
				$stmt->bindValue(11,$be->getDocumento()->getCalendario()->getDatafimelabpdu());
				$stmt->bindValue(12,$be->getDocumento()->getCalendario()->getDatainielabpt());
				$stmt->bindValue(13,$be->getDocumento()->getCalendario()->getDatafimelabpt());
				
				$stmt->bindValue(12,$be->getDocumento()->getCalendario()->getDatainialterapdu());
				$stmt->bindValue(13,$be->getDocumento()->getCalendario()->getDatafimalterapdu());
				
				$stmt->bindValue(14,$be->getDocumento()->getCalendario()->getUsuario());
				$stmt->execute();

			parent::commit();
			Flash::addFlash("Operação realizada com sucesso!");
			//Utils::redirect('calendarioPdi', 'finsCalend');
		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());
			echo $ex->getCode() . "&mensagem=" . $mensagem." insere calendario ";
			Flash::addFlash("Erro!");
			
			Error::addErro('Erro encontrado durante a inserção do calendário');
		}

    }
        public function lista() {
		try {
			
			$sql = "SELECT * FROM `calendario`";
			$stmt = parent::prepare($sql);
			$stmt->execute();
			return $stmt;
			
		} catch (PDOException $ex) {
			print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
		}
	}

	

	public function altera(Calendario $be ){
		try{
			parent::beginTransaction();
			$sql = " UPDATE `calendario` SET 
`anoGestao`=?,`dataIniAnaliseParcial`=?,`dataFimAnaliseParcial`=?,
			`datainiAnaliseFinal`=?,`datafimAnaliseFinal`=?,
			dataIniAnaliseRAA=?,dataFimAnaliseRAA=?,
            `dataInicioElabPDU`=?,`dataFimElabPDU`=?,
             `dataInicioElabPT`=?,`dataFimElabPT`=?,
dataIniAlteraPDU=?,dataFimAlteraPDU=?,
            `codusuario`=?,
			`dataAlteracao`=now()  WHERE `codCalendario`=? ";
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$be->getAnogestao());
				$stmt->bindValue(2,$be->getDatainianalise());
				$stmt->bindValue(3,$be->getDatafimanalise());
				$stmt->bindValue(4,$be->getDatainianalisefinal());
				$stmt->bindValue(5,$be->getDatafimanalisefinal());
							
				$stmt->bindValue(6,$be->getDatainiRAA());
				$stmt->bindValue(7,$be->getDatafimRAA());
				$stmt->bindValue(8,$be->getDatainielabpdu());
				$stmt->bindValue(9,$be->getDatafimelabpdu());
				$stmt->bindValue(10,$be->getDatainielabpt());
				$stmt->bindValue(11,$be->getDatafimelabpt());
				
				$stmt->bindValue(12,$be->getDatainialterapdu());
				$stmt->bindValue(13,$be->getDatafimalterapdu());
				
			
				
				
				$stmt->bindValue(14,$be->getUsuario());
				
				$stmt->bindValue(15,$be->getCodigo());
				$stmt->execute();

			parent::commit();
			print  "Operação realizada com sucesso!";
			//Utils::redirect('calendarioPdi', 'finsCalend');
		}catch ( PDOException $ex ){
			parent::rollback();
			print "Erro altera calendário: " . $ex->getCode() . " " . $ex->getMessage()." altera calendario ";
			
			
			print('Erro encontrado durante alteração do calendário');
			//Utils::redirect('calendarioPdi', 'listaCalendario');
			die;
		}
	}


	public function fechar(){
		PDOConnectionFactory::Close();
	}
}
?>

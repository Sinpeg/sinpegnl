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
			echo $ex->getCode() . "&mensagem=" . $mensagem." XXXXX ";
			header($cadeia);
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
			echo $ex->getCode() . "&mensagem=" . $mensagem." XXXXX ";
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
			header($cadeia);
		}
	}
	
 public function buscaCalendarioporUniDocAno($codunidade,$anogestao,$coddoc) {
		try {
			$sql = "SELECT * ".
           " FROM  `calendario`  ".
           " WHERE  `CodUnidade`=:uni and `CodDocumento`=:doc and `anoGestao`=:ano";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':uni' =>$codunidade,':doc' =>$coddoc,':ano' =>$anogestao));	 
			// retorna o resultado da query
			return $stmt;
		} catch (PDOException $ex) {
			$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
			echo $ex->getCode() . "&mensagem=" . $mensagem." XXXXX ";
			header($cadeia);
		}
	}
      public function buscaCalendarioporAnoBase($anobase,$coddoc) {
		try {
			$sql = "SELECT  c.codCalendario,c.`anoGestao`, c.dataIniAnalise, c.dataFimAnalise, c.datainiAnaliseFinal, c.datafimAnaliseFinal".
" FROM  `calendario` c inner join documento d  on d.codigo=c.codDocumento".
" WHERE  c.`anoGestao`=:ano and ".
" c.`codDocumento`=:doc ";;
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano' =>$anobase,':doc' =>$coddoc));	 
			// retorna o resultado da query
			return $stmt;
		} catch (PDOException $ex) {
			$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
			echo $ex->getCode() . "&mensagem=" . $mensagem." buscaCalendarioporAnoBase ";
			header($cadeia);
		}
	}
	
	
    public function verificaPrazoCalendarioDoDocumento($anobase) {
      //  if ($periodo==1){
            $sql = "SELECT codigo,nome,anoinicial,anofinal,situacao,missao,visao, codCalendario,anoGestao,".
                " case when  (now()>=`dataIniAnalise` and now()<=`dataFimAnalise`) then 'Parcial' ".
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
			header($cadeia);
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
			echo $ex->getCode() . "&mensagem=" . $mensagem." XXXXX ";
			header($cadeia);
		}
	}

	public function insere( $be ){
		try{
			parent::beginTransaction();
			$sql="INSERT INTO `calendario` (`CodCalendario`,`CodUnidade`,`CodDocumento`, `anoGestao`, `dataIniAnalise`, `dataFimAnalise`) ".
                " VALUES (?,?,?,?,?,?)";
                $stmt = parent::prepare($sql);
				$stmt->bindValue(1,$be->getDocumento()->getCalendario()->getCodigo());
				$stmt->bindValue(2,$be->getCodUnidade());
				$stmt->bindValue(3,$be->getDocumento()->getCodigo());
				$stmt->bindValue(4,$be->getDocumento()->getCalendario()->getAnogestao());
				$stmt->bindValue(5,$be->getDocumento()->getCalendario()->getDatainianalise());
				$stmt->bindValue(6,$be->getDocumento()->getCalendario()->getDatafimanalise());
				
				$stmt->execute();

			parent::commit();
		}catch ( PDOException $ex ){
			/*parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);*/
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

	

	public function altera( $be ){
		try{
			parent::beginTransaction();
			$sql = " UPDATE `calendario` SET `anoGestao`=?,`dataIniAnalise`=?,`dataFimAnalise`=?,`datainiAnaliseFinal`=?,`datafimAnaliseFinal`=?,`dataAlteracao`=now()  WHERE `codCalendario`=? ";
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$be->getAnogestao());
				$stmt->bindValue(2,$be->getDatainianalise());
				$stmt->bindValue(3,$be->getDatafimanalise());
				$stmt->bindValue(4,$be->getDatainianalisefinal());
				$stmt->bindValue(5,$be->getDatafimanalisefinal());
				$stmt->bindValue(6,$be->getCodigo());
				$stmt->execute();

			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}


	public function fechar(){
		PDOConnectionFactory::Close();
	}
}
?>

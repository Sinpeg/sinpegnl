<?php
class PremiosDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	private $conex = null;

	// constructor
 /*     public function premiosDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}
 */
	public function deleta( $premios ){
		try{
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `premios` WHERE `Codigo`=?");
			$stmt->bindValue(1, $premios->getCodigo() );
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function buscapremiosunidade($codunidade, $ano){
		try{
			$stmt = parent::prepare("SELECT * FROM `premios` where `CodUnidade` = :codunidade and `Ano` = :ano ");
			$stmt->execute(array(':codunidade'=>$codunidade,':ano'=>$ano));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	public function buscapremiosSubunidade($codunidade, $ano){
		try{
			$stmt = parent::prepare("SELECT * FROM `premios` where `CodSubunidade` = :codunidade and `Ano` = :ano ");
			$stmt->execute(array(':codunidade'=>$codunidade,':ano'=>$ano));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function Insere($unidade){
		if ( $unidade->getSubunidade()->getPremios()->getAno()<2018){
				try{
		            parent::beginTransaction();
					$stmt = parent::prepare("INSERT INTO `premios` (`CodUnidade`,`CodSubunidade`,`OrgaoConcessor`,`Nome`,`Ano`,`Quantidade`,`Categoria`,`Reconhecimento`) VALUES (?,?,?,?,?,?,?,?)");
				    //,STR_TO_DATE(?,'%d-%m-%Y'))");
		           
					$stmt->bindValue(1, $unidade->getCodunidade());
					$stmt->bindValue(2, $unidade->getSubunidade()->getCodUnidade());
					$stmt->bindValue(3, strtoupper($unidade->getSubunidade()->getPremios()->getOrgao() ));
					$stmt->bindValue(4, strtoupper($unidade->getSubunidade()->getPremios()->getNome()));
					$stmt->bindValue(5, $unidade->getSubunidade()->getPremios()->getAno());
					$stmt->bindValue(6, $unidade->getSubunidade()->getPremios()->getQtde());
					$stmt->bindValue(7, $unidade->getSubunidade()->getPremios()->getCategoria());
					$stmt->bindValue(8, $unidade->getSubunidade()->getPremios()->getRec()->getCodpremio());
					//$stmt->bindValue(9, $unidade->getSubunidade()->getPremios()->getData());
						
					$stmt->execute();
					parent::commit();
				}catch ( PDOException $ex ){
					parent::rollback();
					//echo $ex->getMessage();
					//header($cadeia);			
					$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
					$cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
					header($cadeia);
				}
		}else{
			try{
				
            parent::beginTransaction();
            
			$stmt = parent::prepare("INSERT INTO `premios` (`CodUnidade`,`CodSubunidade`,`OrgaoConcessor`,`Nome`,`Ano`,`Quantidade`,`Qtde_discente`,`Qtde_docente`,`Qtde_tecnico`,`Categoria`,`Reconhecimento`,`pais`,`link`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
		    //,STR_TO_DATE(?,'%d-%m-%Y'))");
			
			$stmt->bindValue(1, $unidade->getCodunidade());
			$stmt->bindValue(2, $unidade->getSubunidade()->getCodUnidade());
			$stmt->bindValue(3, strtoupper($unidade->getSubunidade()->getPremios()->getOrgao() ));
			$stmt->bindValue(4, strtoupper($unidade->getSubunidade()->getPremios()->getNome()));
			$stmt->bindValue(5, $unidade->getSubunidade()->getPremios()->getAno());
			
			$stmt->bindValue(6, 0);
			$stmt->bindValue(7, $unidade->getSubunidade()->getPremios()->getQtdei());
			$stmt->bindValue(8, $unidade->getSubunidade()->getPremios()->getQtdeo());
			
			$stmt->bindValue(9, $unidade->getSubunidade()->getPremios()->getQtdet());			
			$stmt->bindValue(10, $unidade->getSubunidade()->getPremios()->getCategoria());
			$stmt->bindValue(11, $unidade->getSubunidade()->getPremios()->getRec()->getCodpremio());
			$stmt->bindValue(12, $unidade->getSubunidade()->getPremios()->getPais());
			$stmt->bindValue(13, $unidade->getSubunidade()->getPremios()->getLink());

			//$stmt->bindValue(9, $unidade->getSubunidade()->getPremios()->getData());
				
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
            print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();			
			
		}
	}
	}

	public function buscapremios($codigo){
		try{

			$stmt = parent::prepare("SELECT * FROM `premios` WHERE `Codigo` =:codigo ");
			$stmt->execute(array(':codigo'=>$codigo));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	public function listarPaisesPremios(){
	    try{
	        
	        $stmt = parent::prepare("SELECT * FROM `pais`");
	        $stmt->execute();
	        // retorna o resultado da query
	        return $stmt;
	    
	    }catch ( PDOException $ex ){
	        $mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
	        $cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
	        header($cadeia);
	    }
	}

	public function buscarPaisPremio($codPais){
	    try{
	        
	        $stmt = parent::prepare("SELECT * FROM `pais` WHERE codPais=:pais");
			$stmt->execute(array(':pais'=>$codPais));
	      
	        // retorna o resultado da query
	        return $stmt;
	    
	    }catch ( PDOException $ex ){
	        $mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
	        $cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
	        header($cadeia);
	    }
	}


	public function altera( $unidade ){
	if ( $unidade->getSubunidade()->getPremios()->getAno()<2018){
		
		try{
			$sql="UPDATE `premios` SET `CodSubunidade`=?,`OrgaoConcessor`=?, `Nome`=?,`Quantidade`=?,`Categoria`=?,`Reconhecimento`=? WHERE `Codigo`=?";
			parent::beginTransaction();
			$stmt = parent::prepare($sql);
			
				
			$stmt->bindValue(1, $unidade->getSubunidade()->getCodunidade());
			$stmt->bindValue(2, strtoupper( $unidade->getSubunidade()->getPremios()->getOrgao() ));
				
			$stmt->bindValue(3, strtoupper($unidade->getSubunidade()->getPremios()->getNome() ));
			$stmt->bindValue(4, $unidade->getSubunidade()->getPremios()->getQtde());
			$stmt->bindValue(5, $unidade->getSubunidade()->getPremios()->getCategoria());
				
			$stmt->bindValue(6, $unidade->getSubunidade()->getPremios()->getRec()->getCodpremio());
				
		//	$stmt->bindValue(7, $unidade->getSubunidade()->getPremios()->getData())
			$stmt->bindValue(7, $unidade->getSubunidade()->getPremios()->getCodigo());
			
			$stmt->execute();
			parent::commit();
		}
		catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
		
	}else{
		
			try{
			$sql="UPDATE `premios` SET `CodSubunidade`=?,`OrgaoConcessor`=?, `Nome`=?,`Qtde_discente`=?,`Qtde_docente`=?,`Qtde_tecnico`=?,`Categoria`=?,`Reconhecimento`=?,`pais`=?,`link`=? WHERE `Codigo`=?";
			parent::beginTransaction();
			$stmt = parent::prepare($sql);
			
				
			$stmt->bindValue(1, $unidade->getSubunidade()->getCodunidade());
			$stmt->bindValue(2, strtoupper( $unidade->getSubunidade()->getPremios()->getOrgao()));
			$stmt->bindValue(3, strtoupper($unidade->getSubunidade()->getPremios()->getNome() ));
			$stmt->bindValue(4, $unidade->getSubunidade()->getPremios()->getQtdei());
			$stmt->bindValue(5, $unidade->getSubunidade()->getPremios()->getQtdeo());
			$stmt->bindValue(6, $unidade->getSubunidade()->getPremios()->getQtdet());			
			$stmt->bindValue(7, $unidade->getSubunidade()->getPremios()->getCategoria());
			$stmt->bindValue(8, $unidade->getSubunidade()->getPremios()->getRec()->getCodpremio());
		//	$stmt->bindValue(7, $unidade->getSubunidade()->getPremios()->getData())
			$stmt->bindValue(9, $unidade->getSubunidade()->getPremios()->getPais());
			$stmt->bindValue(10, $unidade->getSubunidade()->getPremios()->getLink());
			$stmt->bindValue(11, $unidade->getSubunidade()->getPremios()->getCodigo());
			
			$stmt->execute();
			parent::commit();
		}
		catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	}

	public function fechar(){
		PDOConnectionFactory::Close();
	}

}
?>
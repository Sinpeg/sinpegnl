<?php
class premiosDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	private $conex = null;

	// constructor
      public function premiosDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}
 
	public function deleta( $premios ){
		try{
			$this->conex->beginTransaction();
			$stmt = $this->conex->prepare("DELETE FROM `premios` WHERE `Codigo`=?");
			$stmt->bindValue(1, $premios->getCodigo() );
			$stmt->execute();
			$this->conex->commit();
		}catch ( PDOException $ex ){
			$this->conex->rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function buscapremiosunidade($codunidade, $ano){
		try{
			$stmt = $this->conex->prepare("SELECT * FROM `premios` where `CodUnidade` = :codunidade and `Ano` = :ano ");
			$stmt->execute(array(':codunidade'=>$codunidade,':ano'=>$ano));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function Insere( $unidade){
		try{
                        $this->conex->beginTransaction();
			$stmt = $this->conex->prepare("INSERT INTO `premios` (`CodUnidade`,`CodSubunidade`,`OrgaoConcessor`,`Nome`,`Ano`,`Quantidade`,`Categoria`,`Reconhecimento`,`Data`) VALUES (?,?,?,?,?,?,?,?,STR_TO_DATE(?,'%d-%m-%Y'))");
                        $stmt->bindValue(1, $unidade->getCodunidade());
			$stmt->bindValue(2, $unidade->getSubunidade()->getCodUnidade());
			$stmt->bindValue(3, strtoupper($unidade->getSubunidade()->getPremios()->getOrgao() ));
			$stmt->bindValue(4, strtoupper($unidade->getSubunidade()->getPremios()->getNome()));
			$stmt->bindValue(5, $unidade->getSubunidade()->getPremios()->getAno());
			$stmt->bindValue(6, $unidade->getSubunidade()->getPremios()->getQtde());
			$stmt->bindValue(7, $unidade->getSubunidade()->getPremios()->getCategoria());
			$stmt->bindValue(8, $unidade->getSubunidade()->getPremios()->getReconhecimento());
			$stmt->bindValue(9, $unidade->getSubunidade()->getPremios()->getData());
				
			$stmt->execute();
			$this->conex->commit();
		}catch ( PDOException $ex ){
			$this->conex->rollback();
			echo $ex->getMessage();
			header($cadeia);
		}
	}

	public function buscapremios($codigo){
		try{

			$stmt = $this->conex->prepare("SELECT * FROM `premios` WHERE `Codigo` =:codigo ");
			$stmt->execute(array(':codigo'=>$codigo));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}


	public function altera( $unidade ){
		try{
			$sql="UPDATE `premios` SET `CodSubunidade`=?,`OrgaoConcessor`=?, `Nome`=?,`Quantidade`=?,`Categoria`=?,`Reconhecimento`=?,`Data`=? WHERE `Codigo`=?";
			$this->conex->beginTransaction();
			$stmt = $this->conex->prepare($sql);
			$stmt->bindValue(1, $unidade->getSubunidade()->getCodunidade());
			$stmt->bindValue(2, strtoupper( $unidade->getSubunidade()->getPremios()->getOrgao() ));
			$stmt->bindValue(3, strtoupper($unidade->getSubunidade()->getPremios()->getNome() ));
			$stmt->bindValue(4, $unidade->getSubunidade()->getPremios()->getQtde());
			
			$stmt->bindValue(5, $unidade->getSubunidade()->getPremios()->geCategoria());
			$stmt->bindValue(6, $unidade->getSubunidade()->getPremios()->getReconhecimento());
			$stmt->bindValue(7, $unidade->getSubunidade()->getPremios()->getData());
				
			
			$stmt->bindValue(8, $unidade->getSubunidade()->getPremios()->getCodigo());

			
			$stmt->execute();
			$this->conex->commit();
		}
		catch ( PDOException $ex ){
			$this->conex->rollback();
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
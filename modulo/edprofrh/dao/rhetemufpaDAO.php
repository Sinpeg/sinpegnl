<?php
class rhetemufpaDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	private $conex = null;

	// constructor
	/*
	public function rhetemufpaDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}
    */
	
	public function buscarhunidade($codunidade, $ano){
		try{
			$stmt = parent::prepare("SELECT * FROM   `rhetemufpa` where `CodUnidade` = :codunidade and `Ano` = :ano ");
			$stmt->execute(array(':codunidade'=>$codunidade,':ano'=>$ano));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function buscarhsubunidade($ano,$subunidade){
		try{
			$stmt = parent::prepare("SELECT * FROM   `rhetemufpa` where `CodSubunidade` = :subunidade and `Ano` = :ano ");
			$stmt->execute(array(':subunidade'=>$subunidade,':ano'=>$ano));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	public function Insere( $rhetemufpa){
		try{
			$sql="INSERT INTO `rhetemufpa`  (`CodUnidade`, `CodSubunidade`, `DocDoutores`, `DocMestres`,".
" `DocEspecialistas`, `DocGraduados`, `DocNTecnicos`, `DocTemporarios`, `Tecnicos`,`Ano`) ".
" VALUES (?,?,?,?,?,?,?,?,?,?)";
			parent::beginTransaction();
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1, $rhetemufpa->getUnidade()->getCodunidade());
			$stmt->bindValue(2, $rhetemufpa->getSubunidade());
			$stmt->bindValue(3, $rhetemufpa->getDoutores() );
			$stmt->bindValue(4, $rhetemufpa->getMestres() );
			$stmt->bindValue(5, $rhetemufpa->getEspecialistas() );
			$stmt->bindValue(6, $rhetemufpa->getGraduados());
			$stmt->bindValue(7, $rhetemufpa->getNtecnicos());
			$stmt->bindValue(8, $rhetemufpa->getTemporarios() );
			$stmt->bindValue(9, $rhetemufpa->getTecnicos() );
			$stmt->bindValue(10, $rhetemufpa->getAno());
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function buscarh($codigo){
		try{

			$stmt = parent::prepare("SELECT * FROM `rhetemufpa` WHERE `Codigo` =:codigo ");
			$stmt->execute(array(':codigo'=>$codigo));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}


	public function altera( $rh ){
		try{
			$sql="UPDATE `rhetemufpa` SET `DocDoutores`=?, `DocMestres`=?,`DocEspecialistas`=?,".
		"`DocGraduados`=?,`DocTemporarios`=?,`Tecnicos`=?,`DocNTecnicos`=? WHERE `Codigo`=?";
			parent::beginTransaction();
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1, $rh->getDoutores());
			//print ($rh->getDoutores());
			$stmt->bindValue(2, $rh->getMestres());
			$stmt->bindValue(3, $rh->getEspecialistas());
			$stmt->bindValue(4, $rh->getGraduados());
			$stmt->bindValue(5, $rh->getTemporarios());
			$stmt->bindValue(6, $rh->getTecnicos());
			$stmt->bindValue(7, $rh->getNtecnicos());
			$stmt->bindValue(8, $rh->getCodigo());
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
	
	public function deleta($codigo){
		try{
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `rhetemufpa` WHERE `Codigo` =:codigo ");
			$stmt->execute(array(':codigo'=>$codigo));
			parent::commit();
		}catch ( PDOException $ex ){
			//echo "Erro: ".$ex->getMessage();
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
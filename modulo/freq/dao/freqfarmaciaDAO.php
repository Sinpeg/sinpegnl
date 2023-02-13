<?php
class FreqfarmaciaDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	public $conex = null;

	// constructor
	/*
	public function FreqfarmaciaDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	} 
	*/
	
	public function Lista(){
		try{

			$stmt = parent::query("SELECT * FROM `freqfarmacia` order by `Codigo`");
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}


	public function buscaporcodigo($codigo){
		try{

			$stmt = parent::prepare("SELECT * FROM `freqfarmacia` WHERE `Codigo`=:codigo order by `Codigo` ");
			$stmt->execute(array(':codigo'=>$codigo));
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function buscaporano($ano){
		try{
	
			$stmt = parent::prepare("SELECT * FROM `freqfarmacia` WHERE Ano=:ano order by `Mes`");
			$stmt->execute(array(':ano'=>$ano));
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	public function buscaporanomes($ano,$mes){
		try{
	  
			$stmt = parent::prepare("SELECT * FROM `freqfarmacia` WHERE Ano=:ano and Mes=:mes order by `Codigo`");
			$stmt->execute(array(':ano'=>$ano,':mes'=>$mes));
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	public function insere( $f ){
		try{
			parent::beginTransaction();
			$sql="INSERT INTO `freqfarmacia` (`Mes`,`NAlunos`,`NProfessores`,`NVisitantes`,".
			"`NPesquisadores`,`Ano`)  VALUES (?,?,?,?,?,?)";	
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1,$f->getMes());
			$stmt->bindValue(2,$f->getNAlunos());
			$stmt->bindValue(3,$f->getNProfessores());
			$stmt->bindValue(4,$f->getNVisitantes());
			$stmt->bindValue(5,$f->getNPesquisadores());
			$stmt->bindValue(6,$f->getAno());
				
			$stmt->execute();
			parent::commit();

		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function altera( $f ){
		try{
			parent::beginTransaction();
			$sql="update `freqfarmacia` set `Mes`=?,`NAlunos`=?,`NProfessores`=?,".
			" `NVisitantes`=?, `NPesquisadores`=?  where `Codigo`=?";
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1,$f->getMes());
			$stmt->bindValue(2,$f->getNAlunos());
			$stmt->bindValue(3,$f->getNProfessores());
			$stmt->bindValue(4,$f->getNVisitantes());
			$stmt->bindValue(5,$f->getNPesquisadores());
			$stmt->bindValue(6,$f->getCodigo());
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	public function deleta( $codigo ){
		try{
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `freqfarmacia` WHERE `Codigo`=?");
			$stmt->bindValue(1, $codigo);
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
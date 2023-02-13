<?php
class EdprofissionallivreDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	private $conex = null;

	// constructor
	/* public function EdprofissionallivreDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	} */
	
	public function deleta( $codigo ){
		try{
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `edprofissionallivre` WHERE `Codigo`=?");
			$stmt->bindValue(1, $codigo );
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function Insere( $edp){
		try{
			
			$sql="INSERT INTO `edprofissionallivre` (`Categoria` ,".
			 "`NomeCurso` ,	`Ano` ,	`Matriculados1` ,	`Matriculados2` ,`Ingressantes1` ,".
			 "	`Ingressantes2`, `Aprovados1` ,	`Aprovados2`,`Concluintes1`,`Concluintes2`) ".
			" VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
			parent::beginTransaction();
			$stmt = parent::prepare($sql);
			
			$stmt->bindValue(1, $edp->getCodigo());
			$stmt->bindValue(2, $edp->getEdproflivre()->getNomecurso() );
			$stmt->bindValue(3, $edp->getEdproflivre()->getAno() );
			$stmt->bindValue(4, $edp->getEdproflivre()->getMatriculados1() );
			$stmt->bindValue(5, $edp->getEdproflivre()->getMatriculados2() );
			$stmt->bindValue(6, $edp->getEdproflivre()->getIngressantes1());
			$stmt->bindValue(7, $edp->getEdproflivre()->getIngressantes2() );
			$stmt->bindValue(8, $edp->getEdproflivre()->getAprovados1());
			$stmt->bindValue(9, $edp->getEdproflivre()->getAprovados2());
			$stmt->bindValue(10, $edp->getEdproflivre()->getConcluintes1());
			$stmt->bindValue(11, $edp->getEdproflivre()->getConcluintes2());
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			//print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	

	public function buscaeduc($ano){
		try{
			$stmt = parent::prepare("SELECT * FROM `edprofissionallivre` WHERE `Ano` =:ano ORDER BY Categoria ASC");
			$stmt->execute(array(':ano'=>$ano));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			//print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function busca($codigo){
		try{
			$stmt = parent::prepare("SELECT * FROM `edprofissionallivre` WHERE `Codigo` =:codigo");
			$stmt->execute(array(':codigo'=>$codigo));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			//print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	public function Altera( $edp){
		try{
			$sql="UPDATE `edprofissionallivre` SET `Categoria`=? ,".
			"`NomeCurso`=? ,`Ano`=? ,`Matriculados1`=? ,`Matriculados2`=? ,	`Ingressantes1`=? ,".
			"`Ingressantes2`=? ,`Aprovados1`=? ,`Aprovados2`=?, `Concluintes1`=?,`Concluintes2`=?".
			" WHERE `Codigo`=?";
			
			parent::beginTransaction();
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1, $edp->getCodigo());
			$stmt->bindValue(2, $edp->getEdproflivre()->getNomecurso() );
			print $edp->getEdproflivre()->getNomecurso()."<br/>";
			$stmt->bindValue(3, $edp->getEdproflivre()->getAno() );
			$stmt->bindValue(4, $edp->getEdproflivre()->getMatriculados1() );
			$stmt->bindValue(5, $edp->getEdproflivre()->getMatriculados2() );
			$stmt->bindValue(6, $edp->getEdproflivre()->getIngressantes1());
			$stmt->bindValue(7, $edp->getEdproflivre()->getIngressantes2() );
			$stmt->bindValue(8, $edp->getEdproflivre()->getAprovados1());
			$stmt->bindValue(9, $edp->getEdproflivre()->getAprovados2());
			$stmt->bindValue(10, $edp->getEdproflivre()->getConcluintes1());
			$stmt->bindValue(11, $edp->getEdproflivre()->getConcluintes2());
			$stmt->bindValue(12, $edp->getEdproflivre()->getCodigo());
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			//print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
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

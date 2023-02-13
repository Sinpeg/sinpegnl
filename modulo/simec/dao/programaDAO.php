<?php
class ProgramaDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	public $conex = null;

	// constructor
	/* public function ProgramaDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}
*/


	public function Lista(){
		try{
			$stmt = parent::query("SELECT `Codigo`,`CodigoPrograma`,`Nome` FROM `programa` ");
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}

	public function buscacodigo($codigo){
		try{
			$sql = " SELECT `Codigo`,`Subunidade` , `Secao` , `Procedimento`,`Quantidade`,`Formulario` ".
       " FROM `prodsaude4` WHERE `Codigo` =:codigo";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':codigo'=>$codigo));
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}
	public function altera( $p ){
		try{
			parent::beginTransaction();
			$sql="UPDATE `prodsaude4` SET `Quantidade`=?,`Subunidade`=? , `Secao`=? , `Procedimento`=?  WHERE `Codigo`=?";
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1,$p->getQuantidade());
			$stmt->bindValue(2,$p->getSubunidade());
			$stmt->bindValue(3,$p->getSecao());
			$stmt->bindValue(4,$p->getProcedimento());
			$stmt->bindValue(5,$p->getCodigo());
				
			$stmt->execute();

			parent::commit();
		}catch ( PDOException $ex ){
			$db->rollback();
			print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}

	public function insere4( $ps ){
		try{
			parent::beginTransaction();
			$sql="INSERT INTO `prodsaude4` (`Subunidade`,`Secao`,`Procedimento`,`Formulario`,`Quantidade`,`Ano`)".
		  " VALUES (?,?,?,?,?,?)";
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1,$ps->getSubunidade());
			$stmt->bindValue(2,$ps->getSecao());
			$stmt->bindValue(3,$ps->getProcedimento());
			$stmt->bindValue(4,$ps->getFormulario());
			$stmt->bindValue(5,$ps->getQuantidade());
			$stmt->bindValue(6,$ps->getAno());
			$stmt->execute();

			parent::commit();
		}catch ( PDOException $ex ){
			$db->rollback();
			print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}


	public function fechar(){
		PDOConnectionFactory::Close();
	}
}
?>



<?php
class ProdutosDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	private $conex = null;


	// constructor
/*	public function ProdutosDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}
*/
	public function Lista(){
		try{

			$stmt = parent::query("SELECT `Codigo`,`Nome` FROM produto order by `Nome`");
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}

	public function deleta( $prod ){
		try{
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `produtos` WHERE `Codigo`=?");
			$stmt->bindValue(1, $prod->getCodigo() );
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}

	public function inseretodos( $prod ){
		$tamanho = count($prod);
		try{
			parent::beginTransaction();
			$sql="INSERT INTO `produtos` (`Nome`)  VALUES (?)";
			for ($cont=1; $cont <= $tamanho; $cont++){
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$prod[$cont]->getProdutos()-> getNome());
				$stmt->execute();
			}
			parent::commit();
		}catch ( PDOException $ex ){
			$db->rollback();
			print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}

	}

	public function alteratodos( $prod ){
		$tamanho = count($prod);
		try{
			parent::beginTransaction();
			$sql = "UPDATE `produots` SET `Nome`=? WHERE `Codigo`=?";
			for ($cont=1; $cont <= $tamanho; $cont++){
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$prod[$cont]->getProdutos()->getNome());
				$stmt->bindValue(2,$prod[$cont]->getProdutos()->getCodigo());
				$stmt->execute();
			}
			parent::commit();
		}catch ( PDOException $ex ){
			$db->rollback();
			print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}

	public function altera( $pd ){
		try{
			$sql="UPDATE `produtos` SET `Nome`=? WHERE `Codigo`=?";
			parent::beginTransaction();
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1, $pd->getNome());
			$stmt->bindValue(2, $pd->getCodigo ());
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}

	public function busca($anobase){
		try{
			$stmt = parent::prepare("SELECT * FROM   `produtos` where `Ano` = :ano ");
			$stmt->execute(array(':ano'=>$anobase));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}



	public function buscaprod($codigo){
		try{
			$stmt = parent::prepare("SELECT * FROM   `produtos` where `Codigo` = :codigo ");
			$stmt->execute(array(':codigo'=>$codigo));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}

	}
	public function insere( $prod ){
		try{
			$sql= "INSERT INTO `produtos` (`Nome`)  VALUES (?)";
			parent::beginTransaction();
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1,$prod->getNome());
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}


	public function fechar(){
		PDOConnectionFactory::Close();
	}
}
?>
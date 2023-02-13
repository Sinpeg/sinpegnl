<?php
class AcessibilidadeDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	private $conex = null;

	// constructor
	public function Acessibilidade_DAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}

	public function deleta( $ea ){
		try{
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `estrutura_acessibilidade` WHERE `CodigoEstrutura`=?");
			$stmt->bindValue(1, $ea->getCodigoEstrutura() );
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function buscaeaunidade($codunidade, $ano){
		try{
			$stmt = parent::prepare("SELECT * FROM   `estrutura_acessibilidade` where `CodUnidade` = :codunidade and `Ano` = :ano  order by `Tipo`");
			$stmt->execute(array(':codunidade'=>$codunidade,':ano'=>$ano));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function buscaea($codunidade, $ano, $tipoea){
		try{
			$stmt = parent::prepare("SELECT * FROM   `estrutura_acessibilidade` where `CodUnidade` =  :codunidade and `Ano` =  :ano and `Tipo` =  :tipoea  ");
			//print_r($stmt);
			$stmt->execute(array(':codunidade'=>$codunidade,':ano'=>$ano,':tipoea'=>$tipoea));
			// retorna o resultado da query
			return $stmt;

		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function inseretodos( $eas ){
		$tamanho = count($eas);
		try{
			parent::beginTransaction();
			$sql="INSERT INTO `estrutura_acessibilidade` (`CodUnidade`,`Tipo`,`Quantidade`,`Ano`)  VALUES (?,?,?,?)";
			for ($cont=1; $cont <= $tamanho; $cont++){
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$eas[$cont]->getAcessib()->getUnidade()->getCodunidade());
				$stmt->bindValue(2,$eas[$cont]->getCodigo());
				$stmt->bindValue(3,$eas[$cont]->getAcessib()->getQuantidade());
				$stmt->bindValue(4,$eas[$cont]->getAcessib()->getAno());
				$stmt->execute();
			}
			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}

	}

	public function alteratodos( $eas ){
		$tamanho = count($eas);
		try{
			parent::beginTransaction();
			$sql = "UPDATE `estrutura_acessibilidade` SET `Quantidade`=? WHERE `CodigoEstrutura`=?";
			for ($cont=1; $cont <= $tamanho; $cont++){
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$eas[$cont]->getAcessib()->getQuantidade());
			//	echo "qtde ".$eas[$cont]->getAcessib()->getQuantidade();
				$stmt->bindValue(2,$eas[$cont]->getAcessib()->getCodigoEstrutura());
			//	echo "codigo ".$eas[$cont]->getAcessib()->getCodigoEstrutura();
				$stmt->execute();
			}
			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	/*	public function altera( $ea ){
		try{
	$sql="UPDATE `estrutura_acessibilidade` SET `Quantidade`=? WHERE `CodigoEstrutura`=?";
	parent::beginTransaction();
	$stmt = parent::prepare($sql);
	$stmt->bindValue(1, $ea->getQuantidade() );
	$stmt->bindValue(2, $ea->getCodigoEstrutura());
	$stmt->execute();
	parent::commit();
	}catch ( PDOException $ex ){
	$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
	$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
	header($cadeia);
	}
	}*/

	public function fechar(){
		PDOConnectionFactory::Close();
	}
}
?>
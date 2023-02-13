<?php
class PraticajuridicaDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	private $conex = null;

	// constructor
	/* public function PraticajuridicaDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}
   */
	public function deleta( $pj ){
		try{
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `praticajuridica` WHERE `Codigo`=?");
			$stmt->bindValue(1, $pj->getCodigo() );
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			//echo "Erro: ".$ex->getMessage();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function buscapjAnuario($ano,$ano2){
	    try{
	        $stmt = parent::prepare("SELECT * FROM `praticajuridica` where `Ano` >= :ano2 AND `Ano` <= :ano ORDER BY CodUnidade,Tipo,Ano ASC");
	        $stmt->execute(array(':ano'=>$ano,':ano2'=>$ano2));
	        // retorna o resultado da query
	        return $stmt;
	    }catch ( PDOException $ex ){
	        $mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
	        $cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
	        header($cadeia);
	    }
	}
	
	public function buscapjunidade($codunidade, $ano){
		try{
			$stmt = parent::prepare("SELECT * FROM   `praticajuridica` where `CodUnidade` = :codunidade and `Ano` = :ano ");
			$stmt->execute(array(':codunidade'=>$codunidade,':ano'=>$ano));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			//echo "Erro: ".$ex->getMessage();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function buscapj($codunidade, $ano, $tipopj){
		try{
			$stmt = parent::prepare("SELECT * FROM   `praticajuridica` where `CodUnidade` =  :codunidade and `Ano` =  :ano and `Tipo` =  :tipopj ");
			//print_r($stmt);
			$stmt->execute(array(':codunidade'=>$codunidade,':ano'=>$ano,':tipopj'=>$tipopj));
			// retorna o resultado da query
			return $stmt;

		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}


	public function inseretodos( $pjs ){
		$tamanho = count($pjs);
		try{
			parent::beginTransaction();
			$sql="INSERT INTO `praticajuridica` (`CodUnidade`,`Tipo`,`Quantidade`,`Ano`)  VALUES (?,?,?,?)";
			for ($cont=1; $cont <= $tamanho; $cont++){
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$pjs[$cont]->getPraticajuridica()->getUnidade()->getCodunidade());
				$stmt->bindValue(2,$pjs[$cont]->getCodigo());
				$stmt->bindValue(3,$pjs[$cont]->getPraticajuridica()->getQuantidade());
				$stmt->bindValue(4,$pjs[$cont]->getPraticajuridica()->getAno());
				$stmt->execute();
			}
			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			//print "Erro: C�digo:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}

	}

	public function alteratodos( $pjs ){
		$tamanho = count($pjs);
		try{
			parent::beginTransaction();
			$sql = "UPDATE `praticajuridica` SET `Quantidade`=? WHERE `Codigo`=?";
			for ($cont=1; $cont <= $tamanho; $cont++){
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$pjs[$cont]->getPraticajuridica()->getQuantidade());
				print "--".$pjs[$cont]->getPraticajuridica()->getQuantidade();
				$stmt->bindValue(2,$pjs[$cont]->getPraticajuridica()->getCodigo());
				print $pjs[$cont]->getPraticajuridica()->getCodigo();
				$stmt->execute();
			}
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
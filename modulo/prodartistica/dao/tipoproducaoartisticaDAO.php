<?php
class TipoproducaoartisticaDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	public $conex = null;

	// constructor
   /*	public function TipoproducaoartisticaDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}
  */
	public function tiponaoinserido($codunidade,$anobase){
		try{
			$sql ="SELECT t1.`Codigo` , t1.`Nome` FROM `tdm_prodartistica` t1 WHERE t1.`Codigo` NOT IN (".
" SELECT t2.`Tipo` FROM `prodartistica` t2 WHERE t1.`Codigo` = t2.`Tipo` AND `CodUnidade` =:codunidade AND `Ano` =:anobase)";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':codunidade'=>$codunidade, ':anobase'=>$anobase));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}


	public function Lista(){
		try{

			$stmt = parent::query("SELECT * FROM tdm_prodartistica");
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}


	public function buscatipoprodartistica($codtpprodartistica){
		try{

			$stmt = parent::prepare("SELECT * FROM tdm_prodartistica WHERE Codigo=:codigo");
			$stmt->execute(array(':codigo'=>$codtpprodartistica));
			print($stmt);
			return $stmt;
		}catch ( PDOException $ex ){
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
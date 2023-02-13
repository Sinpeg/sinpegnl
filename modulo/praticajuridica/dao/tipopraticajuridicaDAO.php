<?php
class TipopraticajuridicaDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	public $conex = null;

	// constructor
  /*    public function TipopraticajuridicaDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	   }
  */
	public function tiponaoinserido($codunidade,$anobase){
		try{
			$sql ="SELECT t1.`Codigo` , t1.`Nome` FROM `tdm_prat_juridica` t1 WHERE t1.`Codigo` NOT IN (".
" SELECT t2.`Tipo` FROM `praticajuridica` t2 WHERE t1.`Codigo` = t2.`Tipo` AND `CodUnidade` =:codunidade AND `Ano` =:anobase)";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':codunidade'=>$codunidade, ':anobase'=>$anobase));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}


	public function Lista(){
		try{

			$stmt = parent::query("SELECT * FROM tdm_prat_juridica");
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}


	public function buscatipopraticajuridica($codtppraticajuridica){
		try{

			$stmt = parent::prepare("SELECT * FROM tdm_prat_juridica WHERE Codigo=:codigo");
			$stmt->execute(array(':codigo'=>$codtppraticajuridica));
			print($stmt);
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}

	public function fechar(){
		PDOConnectionFactory::Close();
	}
}
?>
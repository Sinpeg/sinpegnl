<?php
class TipoinfraDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	public $conex = null;

	// constructor
 /*
	public function TipoinfraDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}
 */
	
	public function tiponaoinserido($codunidade,$anobase){
		try{
			$sql ="SELECT t1.`Codigo` , t1.`Nome` FROM `tdm_tipo_infraestrutura` t1 WHERE t1.`Codigo` NOT IN (".
" SELECT t2.`Tipo` FROM `infraestrutura` t2 WHERE t1.`Codigo` = t2.`Tipo` AND `CodUnidade` =:codunidade AND `Ano` =:anobase)";
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

			$stmt = parent::query("SELECT * FROM tdm_tipo_infraestrutura ORDER BY Nome");
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}
	
	public function ListaIncluir(){
	    try{
	        
	        $stmt = parent::query("SELECT * FROM tdm_tipo_infraestrutura WHERE validade=2021 ORDER BY Nome");
	        // retorna o resultado da query
	        return $stmt;
	    }catch ( PDOException $ex ){
	        echo "Erro: ".$ex->getMessage();
	    }
	}

	public function buscatipota($codtta){
		try{

			$stmt = parent::prepare("SELECT * FROM tdm_tipo_infraestrutura WHERE Codigo=:codigo");
			$stmt->execute(array(':codigo'=>$codtta));
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
<?php
class TptecnassistDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	public $conex = null;

	// constructor
/*	public function TptecnassistDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}
*/
	public function tiponaoinserido($codcurso,$anobase){
		try{
			$sql ="SELECT t1.`Codigo` , t1.`Nome` FROM `tdm_tipo_tecnologia_assistiva` t1 WHERE t1.`Codigo` NOT IN (".
" SELECT t2.`Tipo` FROM `tecnologia_assistiva` t2 WHERE t1.`Codigo` = t2.`Tipo` AND `CodCurso` =:codcurso AND `Ano` =:anobase)";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':codcurso'=>$codcurso, ':anobase'=>$anobase));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}
	
	public function Lista(){
		try{

			$stmt = parent::query("SELECT * FROM tdm_tipo_tecnologia_assistiva");
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}

	public function buscatipota($codtta){
		try{

			$stmt = parent::prepare("SELECT * FROM tdm_tipo_tecnologia_assistiva WHERE Codigo=:codigo");
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
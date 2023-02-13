<?php
class TdmedprofissionallivreDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	public $conex = null;

	// constructor
/*	public function TdmedprofissionallivreDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	} */

public function Lista(){
		try{

			$stmt = parent::query("SELECT * FROM `tdm_edprofissionallivre` ORDER BY Categoria DESC");
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}


	public function buscaporunidade($codigo){
		try{

			$stmt = parent::prepare("SELECT * FROM tdm_edprofissionallivre WHERE CodUnidade=:codigo");
			$stmt->execute(array(':codigo'=>$codigo));
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
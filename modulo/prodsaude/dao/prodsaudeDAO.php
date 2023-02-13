<?php
class ProdsaudeDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	public $conex = null;

	// constructor
	/* public function ProdsaudeDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}
*/
	
	public function ListaSecao(){
		try {
			$stmt = parent::query("SELECT Subunidade, Secao FROM `prodsaude` GROUP BY Subunidade,Secao  ");
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	
	
	public function ListaPorSecao($secao){
		try {
			$stmt = parent::query("SELECT * FROM `prodsaude` WHERE Secao ='{$secao}' ");
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	

	public function Lista($formulario,$ano){
		try{
			$sql = "SELECT p.`Codigo` as Pcodigo,`Subunidade` , `Secao` , `Procedimento` ,  `Quantidade`, q.`Codigo` as Qcodigo".
" FROM `prodsaude` p, `qprodsaude` q".
" WHERE `Ano` =:ano".
" AND `Formulario`=:formulario".
" AND p.`Codigo` = q.`Tipo` order by  `Subunidade`";
			$stmt->execute(array(':ano'=>$ano,':formulario'=>$formulario));
			$stmt = parent::query();
			// retorna o resultado da query
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

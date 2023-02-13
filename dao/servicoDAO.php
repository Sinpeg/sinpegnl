<?php
class ServicoDAO extends PDOConnectionFactory {
	// irá receber uma conexão
	public $conex = null;

	public function buscaservicos($codunidade,$codsubunidade){
		try{
			$stmt = parent::prepare("SELECT * FROM servico WHERE CodUnidade = :codigo and CodSubunidade=:subunidade");
			
			$stmt->execute(array(':codigo'=>$codunidade,'subunidade'=>$codsubunidade));
			return $stmt;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}
	
	public function buscaservicosSub($codsubunidade){
	    try{
	        $stmt = parent::prepare("SELECT * FROM servico WHERE  CodSubunidade=:subunidade");
	        
	        $stmt->execute(array('subunidade'=>$codsubunidade));
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
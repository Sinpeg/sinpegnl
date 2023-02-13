<?php
class ModeloDAO extends PDOConnectionFactory {
	
//Buscar modelos
	public function buscarmodelosUniTopAno($top,$ano,$codunidade){
		try{
			
			$stmt = parent::prepare("SELECT * FROM `raa_modelo` WHERE  (codUnidade=:uni or codUnidade is NULL)  
			and codtopico=:codtopico and anoinicio>=:ano and  (anofinal<:ano || anofinal is NULL) 
            order by ordemintopico");
			  	 //echo $codunidade."-".$top->getCodigo()."-".$ano."buscarmodelosUniTopAno";    	
			$stmt->execute(array(':uni'=> $codunidade,':codtopico'=>$top->getCodigo(), ':ano'=>$ano, ':ano'=>$ano));
			// retorna o resultado da query
			
			return $stmt;
		}catch ( PDOException $ex ){
		echo "Erro: buscarmodelosUniTopAno - modeloDAO" . $ex->getMessage();
			
		}
	}
	
	public function buscarmodelosUniTopAno1($top,$ano,$codunidade){
		try{
			
			$stmt = parent::prepare("SELECT * FROM `raa_modelo` WHERE  (codUnidade=:uni or codUnidade is NULL)  
			and codtopico=:codtopico and anoinicio>=:ano and  (anofinal<:ano || anofinal is NULL) 
            order by ordemintopico");
			  	 //echo $codunidade."-".$top->getCodigo()."-".$ano."buscarmodelosUniTopAno";    	
			$stmt->execute(array(':uni'=> $codunidade,':codtopico'=>$top, ':ano'=>$ano, ':ano'=>$ano));
			// retorna o resultado da query
			
			return $stmt;
		}catch ( PDOException $ex ){
		echo "Erro: buscarmodelosUniTopAno - modeloDAO" . $ex->getMessage();
			
		}
	}
	
	//Buscar modelos
	public function buscarmodelos($unidade,$anobase){
		try{
			if($unidade == 100000){
				$stmt = parent::prepare("SELECT * FROM `raa_modelo` WHERE codTopico IN (SELECT codigo FROM raa_topico WHERE codUnidade IS NULL) AND ((  :anobase >= anoInicio AND :anobase < anofinal) OR ( anoInicio<= :anobase AND anofinal IS NULL))");
				$stmt->execute(array(':codUnidade'=>$unidade,':anobase'=>$anobase));
			}else{
				$stmt = parent::prepare("SELECT * FROM `raa_modelo` WHERE codTopico IN (SELECT codigo FROM raa_topico WHERE codUnidade=:codUnidade) AND ((  :anobase >= anoInicio AND :anobase < anofinal) OR ( anoInicio<= :anobase AND anofinal IS NULL))");
				$stmt->execute(array(':codUnidade'=>$unidade,':anobase'=>$anobase));
			}
			
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
					echo "Erro: buscarmodelos - modeloDAO" . $ex->getMessage();
			
		}
	}
	
	//Insere modelo
	public function inseremodelo( $dados ){
		try{
			parent::beginTransaction();
	
			if($dados['codUnidade'] == 100000){
				$sql="INSERT INTO `raa_modelo` (`codUnidade`,`legenda`,`ordemInTopico`,`descModelo`,`anoInicio`,`situacao`,`codTopico`)  VALUES (?,?,?,?,?,?,?)";
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$dados['codUnidade']);
				$stmt->bindValue(2,$dados['legenda']);
				$stmt->bindValue(3,$dados['ordem']);
				$stmt->bindValue(4,$dados['modelo']);
				$stmt->bindValue(5,$dados['anoInicio']);
				$stmt->bindValue(6,$dados['situacao']);
				$stmt->bindValue(7,$dados['topico']);
			}else{
				$sql="INSERT INTO `raa_modelo` (`legenda`,`ordemInTopico`,`descModelo`,`anoInicio`,`situacao`,`codTopico`)  VALUES (?,?,?,?,?,?)";
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$dados['legenda']);
				$stmt->bindValue(2,$dados['ordem']);
				$stmt->bindValue(3,$dados['modelo']);
				$stmt->bindValue(4,$dados['anoInicio']);
				$stmt->bindValue(5,$dados['situacao']);
				$stmt->bindValue(6,$dados['topico']);
			}
			$stmt->execute();
	
			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
														echo "Erro: inseremodelo - modeloDAO" . $ex->getMessage();
			
		}
	}
	
	//Buscar quantidade de modelos existentes para o tópico
	public function countmodelos($topico,$anobase){
		try{
			$stmt = parent::prepare("SELECT count(*) AS qtd FROM `raa_modelo` WHERE codTopico=:topico AND ((  :anobase >= anoInicio AND :anobase < anofinal) OR ( anoInicio<= :anobase AND anofinal IS NULL))");
			$stmt->execute(array(':topico' => $topico,':anobase'=>$anobase));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
											echo "Erro: countmodelos - modeloDAO" . $ex->getMessage();
			
		}
	}
	
	
	//Buscar Modelo por código
	public function buscarModeloCod($codModelo){
		try{
			$stmt = parent::prepare("SELECT * FROM `raa_modelo` WHERE codigo=:codModelo");
			$stmt->execute(array(':codModelo'=>$codModelo));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
								echo "Erro: buscarModeloCod - modeloDAO" . $ex->getMessage();
			
		}
	}
	
	//Buscar Modelo por código do tópico
	public function buscarModeloTopico($codTopico){
		try{
			$stmt = parent::prepare("SELECT * FROM `raa_modelo` WHERE codTopico=:codTopico ORDER BY ordemInTopico");
			$stmt->execute(array(':codTopico'=>$codTopico));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	//Função que edita os dados do modelo
	public function alteramodelo( $dados ){
	
		try{
			parent::beginTransaction();
			if($dados['situacao']==0){
				$sql = "UPDATE `raa_modelo` SET `descModelo`=?, `situacao`=?, `legenda`=?, `codTopico`=?, `codUnidade`=?, `anofinal`=? WHERE `codigo`=?";
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$dados['modelo']);
				$stmt->bindValue(2,$dados['situacao']);
				$stmt->bindValue(3,$dados['legenda']);
				$stmt->bindValue(4,$dados['topico']);
				$stmt->bindValue(5,$dados['codUnidade']);
				$stmt->bindValue(6,$dados['anofinal']);
				$stmt->bindValue(7,$dados['codModelo']);
			}else{
				$sql = "UPDATE `raa_modelo` SET `descModelo`=?, `situacao`=?, `legenda`=?, `codTopico`=?, `codUnidade`=? WHERE `codigo`=?";
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$dados['modelo']);
				$stmt->bindValue(2,$dados['situacao']);
				$stmt->bindValue(3,$dados['legenda']);
				$stmt->bindValue(4,$dados['topico']);
				$stmt->bindValue(5,$dados['codUnidade']);
				$stmt->bindValue(6,$dados['codModelo']);
			}			
			
			$stmt->execute();
				
			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	//Excluir modelo
	public function excluirmodelo($codModelo){
		try{
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `raa_modelo` WHERE `codigo`=?");
			$stmt->bindValue(1, $codModelo );
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	//Função para atualizar ordem do modelo
	public function atualizarordemmodelo($ordem,$modelo){
	
		try{
			parent::beginTransaction();
			$sql = "UPDATE `raa_modelo` SET `ordemInTopico`=? WHERE `codigo`=?";
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1,$ordem);
			$stmt->bindValue(2,$modelo);
			$stmt->execute();
	
			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
}
?>
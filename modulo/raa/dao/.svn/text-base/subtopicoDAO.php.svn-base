<?php
class SubtopicoDAO extends PDOConnectionFactory {
	
	//Insere subtopico
	public function inseresubtopico( $dados ){
		try{
			parent::beginTransaction();
				
			if($dados['codUnidade'] == 100000){
				$sql="INSERT INTO `raa_topico` (`titulo`,`situacao`,`tipo`,`anoinicial`, `codTopico`, `ordem`)  VALUES (?,?,?,?,?,?)";
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$dados['titulo']);
				$stmt->bindValue(2,$dados['situacao']);
				$stmt->bindValue(3,$dados['tipo']);
				$stmt->bindValue(4,$dados['anoinicial']);
				$stmt->bindValue(5,$dados['topico']);
				$stmt->bindValue(6,$dados['ordem']);
			}else{
				$sql="INSERT INTO `raa_topico` (`titulo`,`situacao`,`tipo`,`anoinicial`,`codUnidade`, `codTopico`, `ordem`)  VALUES (?,?,?,?,?,?,?)";
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$dados['titulo']);
				$stmt->bindValue(2,$dados['situacao']);
				$stmt->bindValue(3,$dados['tipo']);
				$stmt->bindValue(4,$dados['anoinicial']);
				$stmt->bindValue(5,$dados['codUnidade']);
				$stmt->bindValue(6,$dados['topico']);
				$stmt->bindValue(7,$dados['ordem']);
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
	
	//Buscar subtopicos com o mesmo titulo ao inserir
	public function countmesmotitulo($unidade,$titulo,$topico,$anobase){
		try{
			$stmt = parent::prepare("SELECT count(*) AS qtd FROM `raa_topico` WHERE codUnidade=:codUnidade AND titulo=:titulo AND codTopico=:topico AND (( :anobase >= anoinicial AND :anobase < anofinal) OR ( anoinicial<= :anobase AND anofinal IS NULL))");
			$stmt->execute(array(':codUnidade'=>$unidade,':titulo' => $titulo, ':topico' => $topico,':anobase'=>$anobase));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	//Buscar quantidade de subtopicos existentes para o tópico 
	public function countsubtopicos($unidade,$topico,$anobase){
		try{
			$stmt = parent::prepare("SELECT count(*) AS qtd FROM `raa_topico` WHERE codUnidade=:codUnidade AND codTopico=:topico AND ((  :anobase >= anoinicial AND :anobase < anofinal) OR ( anoinicial<= :anobase AND anofinal IS NULL))");
			$stmt->execute(array(':codUnidade'=>$unidade,':topico' => $topico,':anobase'=>$anobase));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	//Buscar subtopicos
	public function buscarsubtopicos($unidade,$topico,$anobase){
		try{
			if($unidade == 100000){
				$stmt = parent::prepare("SELECT * FROM `raa_topico` WHERE codUnidade IS NULL AND codTopico=:topico AND ((  :anobase >= anoinicial AND :anobase < anofinal) OR ( anoinicial<= :anobase AND anofinal IS NULL)) ORDER BY ordem");
				$stmt->execute(array(':topico'=>$topico,':anobase'=>$anobase));
			}else{
				$stmt = parent::prepare("SELECT * FROM `raa_topico` WHERE codUnidade=:codUnidade AND codTopico=:topico AND ((  :anobase >= anoinicial AND :anobase < anofinal) OR ( anoinicial<= :anobase AND anofinal IS NULL)) ORDER BY ordem");
				$stmt->execute(array(':codUnidade'=>$unidade, 'topico' => $topico,':anobase'=>$anobase));
			}			
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	//Buscar subtopicos
	public function buscarsubtopicosparao($topico,$anobase){
		try{			
			$stmt = parent::prepare("SELECT * FROM `raa_topico` WHERE codTopico=:codTopico AND ((  :anobase >= anoinicial AND :anobase < anofinal) OR ( anoinicial<= :anobase AND anofinal IS NULL)) ORDER BY ordem");
			$stmt->execute(array(':codTopico' => $topico,':anobase'=>$anobase));
			
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	
	//Função para atualizar ordem de subtopico
	public function atualizarordemsubtopico($ordem,$topico){
	
		try{
			parent::beginTransaction();
			$sql = "UPDATE `raa_topico` SET `ordem`=? WHERE `codigo`=?";
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1,$ordem);
			$stmt->bindValue(2,$topico);
			$stmt->execute();
				
			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	//Buscar subtopicos por unidade
	public function buscarsubtopicosunidade($unidade,$anobase){
		try{
			if($unidade == 100000){
				$stmt = parent::prepare("SELECT * FROM `raa_topico` WHERE codUnidade IS NULL AND codTopico IS NOT NULL AND ((  :anobase >= anoinicial AND :anobase < anofinal) OR ( anoinicial<= :anobase AND anofinal IS NULL)) ORDER BY ordem");
				$stmt->execute(array(':codUnidade'=>$unidade,':anobase'=>$anobase));
			}else{
				$stmt = parent::prepare("SELECT * FROM `raa_topico` WHERE codUnidade=:codUnidade AND codTopico IS NOT NULL AND ((  :anobase >= anoinicial AND :anobase < anofinal) OR ( anoinicial<= :anobase AND anofinal IS NULL)) ORDER BY ordem");
				$stmt->execute(array(':codUnidade'=>$unidade,':anobase'=>$anobase));
			}
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	//Buscar subtopicos por unidade - Para cadastrar modelo
	public function buscarsubtopicosunidadeM($unidade,$anobase){
		try{
			if($unidade == 100000){
				$stmt = parent::prepare("SELECT * FROM `raa_topico` WHERE codUnidade IS NULL AND codTopico IS NOT NULL AND ((  :anobase >= anoinicial AND :anobase < anofinal) OR ( anoinicial<= :anobase AND anofinal IS NULL)) ORDER BY ordem");
				$stmt->execute(array(':codUnidade'=>$unidade,':anobase'=>$anobase));
			}else{
				$stmt = parent::prepare("SELECT * FROM `raa_topico` WHERE codUnidade=:codUnidade AND codTopico IS NOT NULL AND ((  :anobase >= anoinicial AND :anobase < anofinal) OR ( anoinicial<= :anobase AND anofinal IS NULL)) ORDER BY ordem");
				$stmt->execute(array(':codUnidade'=>$unidade,':anobase'=>$anobase));
			}
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
}
?>
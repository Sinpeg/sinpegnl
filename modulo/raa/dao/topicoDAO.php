<?php


class RaaDAO extends PDOConnectionFactory {
	
	//Buscar tópicos
	public function buscartopicos($unidade,$anoBase){
		try{
			if($unidade == 100000){
				$stmt = parent::prepare("SELECT * FROM `raa_topico` WHERE codTopico IS NULL AND codUnidade IS NULL AND ordem IS NOT NULL AND ((  :anobase >= anoinicial AND :anobase < anofinal) OR ( anoinicial<= :anobase AND anofinal IS NULL)) ORDER BY ordem");
			}else{
				$stmt = parent::prepare("SELECT * FROM `raa_topico` WHERE codUnidade=:codUnidade AND codTopico IS NULL AND ((  :anobase >= anoinicial AND :anobase < anofinal) OR ( anoinicial<= :anobase AND anofinal IS NULL)) ORDER BY ordem");
			}
			$stmt->execute(array(':codUnidade'=>$unidade,':anobase'=>$anoBase));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	//Verificar se o tópico é vinculado a unidades específicas
	public function buscarUnidadestopico($codTopico){
		try{
			$stmt = parent::prepare("SELECT * FROM `raa_unidadetopico` WHERE codTopico=:codTopico");
			$stmt->execute(array(':codTopico'=>$codTopico));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	//Selecionar unidades vinculadas aos tópicos
	public function buscarUnidadesdoTopico($codTopico){
		try{
			$stmt = parent::prepare("SELECT codTopico,ut.codUnidade,NomeUnidade FROM `raa_unidadetopico` ut
inner JOIN unidade u ON u.CodUnidade=ut.codUnidade
WHERE codTopico=:codTopico");
			$stmt->execute(array(':codTopico'=>$codTopico));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	//Buscar tópicos padrões
	public function buscartopicospadroes($anobase){
		try{
			$stmt = parent::prepare("SELECT * FROM `raa_topico` WHERE tipo='P' AND codTopico IS NULL AND ordem IS NOT NULL AND ((  :anobase >= anoinicial AND :anobase < anofinal) OR ( anoinicial<= :anobase AND anofinal IS NULL)) ORDER BY ordem");
			$stmt->execute(array(':anobase'=>$anobase));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	//Buscar subtopicos padrões
	public function buscarsubtopicospadroes($topico,$anobase){
		try{
			$stmt = parent::prepare("SELECT * FROM `raa_topico` WHERE tipo='P' AND codTopico=:codTopico AND ((  :anobase >= anoinicial AND :anobase < anofinal) OR ( anoinicial<= :anobase AND anofinal IS NULL)) ORDER BY ordem");
			$stmt->execute(array(':codTopico'=>$topico,':anobase'=>$anobase));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	//Buscar Considerações Finais
	public function buscarconsideracoes($anobase){
		try{
			$stmt = parent::prepare("SELECT * FROM `raa_topico` WHERE tipo='P' AND codTopico IS NULL AND ordem IS NULL AND ((  :anobase >= anoinicial AND :anobase < anofinal) OR ( anoinicial<= :anobase AND anofinal IS NULL))");
			$stmt->execute(array(':anobase'=>$anobase));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	
	//Insere topico
	public function inseretopico( $dados ){
		try{
			parent::beginTransaction();			
			if($dados['codUnidade'] == 100000){
				if($dados['titulo']=="CONSIDERAÇÕES FINAIS"){
					$sql="INSERT INTO `raa_topico` (`titulo`,`situacao`,`tipo`,`anoinicial`)  VALUES (?,?,?,?)";
					$stmt = parent::prepare($sql);
					$stmt->bindValue(1,$dados['titulo']);
					$stmt->bindValue(2,$dados['situacao']);
					$stmt->bindValue(3,$dados['tipo']);
					$stmt->bindValue(4,$dados['anoinicial']);					
				}else{
					$sql="INSERT INTO `raa_topico` (`titulo`,`situacao`,`tipo`,`anoinicial`, `ordem`)  VALUES (?,?,?,?,?)";
					$stmt = parent::prepare($sql);
					$stmt->bindValue(1,$dados['titulo']);
					$stmt->bindValue(2,$dados['situacao']);
					$stmt->bindValue(3,$dados['tipo']);
					$stmt->bindValue(4,$dados['anoinicial']);
					$stmt->bindValue(5,$dados['ordem']);
				}				
				 
			}else{			
				$sql="INSERT INTO `raa_topico` (`titulo`,`situacao`,`tipo`,`anoinicial`,`codUnidade`, `ordem`)  VALUES (?,?,?,?,?,?)";
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$dados['titulo']);
				$stmt->bindValue(2,$dados['situacao']);
				$stmt->bindValue(3,$dados['tipo']);
				$stmt->bindValue(4,$dados['anoinicial']);
				$stmt->bindValue(5,$dados['codUnidade']);
				$stmt->bindValue(6,$dados['ordem']);
			}									
			$stmt->execute();
			
			$id= parent::lastInsertId();
    		parent::commit();
    		return $id;
		}catch ( PDOException $ex ){
			parent::rollback();
						echo "Erro: inseretopico - TOPICODAO" . $ex->getMessage();
			
		}	
	}
	
	//Buscar quantidade de topicos existentes 
	public function counttopicos($unidade,$anobase){
		try{
			$stmt = parent::prepare("SELECT count(*) AS qtd FROM `raa_topico` WHERE codUnidade=:codUnidade AND codTopico IS NULL AND ((  :anobase >= anoinicial AND :anobase < anofinal) OR ( anoinicial<= :anobase AND anofinal IS NULL))");
			$stmt->execute(array(':codUnidade'=>$unidade,':anobase'=>$anobase));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	//Buscar tópicos com o mesmo titulo ao inserir
	public function countmesmotitulo($unidade,$titulo,$anobase){
		try{
			$stmt = parent::prepare("SELECT count(*) AS qtd FROM `raa_topico` WHERE codUnidade=:codUnidade AND titulo=:titulo AND ((  :anobase >= anoinicial AND :anobase < anofinal) OR ( anoinicial<= :anobase AND anofinal IS NULL))");
			$stmt->execute(array(':codUnidade'=>$unidade,':titulo' => $titulo,':anobase'=>$anobase));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	//Buscar tópicos com o mesmo titulo ao editar
	public function countmesmotituloeditar($unidade,$titulo,$codTitulo,$anobase){
		try{
			$stmt = parent::prepare("SELECT count(*) AS qtd FROM `raa_topico` WHERE codUnidade=:codUnidade AND titulo=:titulo AND codigo NOT IN (:codTitulo) AND ((  :anobase >= anoinicial AND :anobase < anofinal) OR ( anoinicial<= :anobase AND anofinal IS NULL))");
			$stmt->execute(array(':codUnidade'=>$unidade,':titulo' => $titulo, 'codTitulo' => $codTitulo,':anobase'=>$anobase));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	//Buscar tópicos por codigo
	public function buscarTopicoCod($codTopico){
		try{
			$stmt = parent::prepare("SELECT * FROM `raa_topico` WHERE codigo=:codTitulo");
			$stmt->execute(array(':codTitulo'=>$codTopico));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	//Função que edita os dados do tópico
	public function alteratopico( $dados ){
		
		try{
			parent::beginTransaction();
			if($dados['situacao']==1){
				$sql = "UPDATE `raa_topico` SET `titulo`=?, `situacao`=? WHERE `codigo`=?";
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$dados['titulo']);
				$stmt->bindValue(2,$dados['situacao']);
				$stmt->bindValue(3,$dados['codTopico']);
			}else{
				$sql = "UPDATE `raa_topico` SET `titulo`=?, `situacao`=?,`anofinal`=?  WHERE `codigo`=?";
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$dados['titulo']);
				$stmt->bindValue(2,$dados['situacao']);
				$stmt->bindValue(3,$dados['anofinal']);
				$stmt->bindValue(4,$dados['codTopico']);
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
	
	
	public function excluirtopico( $codTopico ){
		try{
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `raa_topico` WHERE `codigo`=?");
			$stmt->bindValue(1, $codTopico );
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	//Função para atualizar ordem de topico
	public function atualizarordemtopico($ordem,$topico){
	
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
	

	public function fechar(){
		PDOConnectionFactory::Close();
	}
	
	//Insere topico
	public function insereTopicoUnidade($dados){
		try{
			parent::beginTransaction();
			$sql="INSERT INTO `raa_unidadetopico` (`codTopico`,`codUnidade`)  VALUES (?,?)";
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1,$dados['codTopico']);
			$stmt->bindValue(2,$dados['codUnidade']);
										
			$stmt->execute();				
			//$id= parent::lastInsertId();
			parent::commit();
			//return $id;
		}catch ( PDOException $ex ){
			parent::rollback();
			echo "Erro: inseretopicoUnidade - TOPICODAO" . $ex->getMessage();
				
		}
	}
	
	public function excluirUnidadeTopico( $codTopico ){
		try{
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `raa_unidadetopico` WHERE `codTopico`=?");
			$stmt->bindValue(1, $codTopico );
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
}
?>

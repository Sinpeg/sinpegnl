<?php
class AcaoDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	public $conex = null;

	// constructor
	/* public function AcaoDAO(){
		$this->conex = PDOConnectionFactory::getConnection();
	}
*/


	public function buscaAcao($codunidade,$ano){
		try{
			$sql = "SELECT p.`Codigo` as pcodigo,p.`CodigoPrograma`,p.`Nome` as pnome, a.`Codigo` as acodigo,`CodigoAcao`,".
"a.`Nome`, `Finalidade`,`Descricao`,`AnaliseCritica` ".
" FROM `programa` p,`acao` a ".
" WHERE `Ano` =:ano".
" AND `CodUnidade`=:unidade".
" AND p.`Codigo`=a.`CodigoPrograma`";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano'=>$ano,':unidade'=>$codunidade));
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function buscacodigo($codigo){
		try{
			$sql = "SELECT p.`Codigo` as pcodigo,p.`CodigoPrograma`,p.`Nome` as pnome,".
" a.`Codigo` as acodigo,`CodigoAcao`,".
"a.`Nome`, `Finalidade`,`Descricao`,`AnaliseCritica` ".
" FROM `programa` p,`acao` a ".
" WHERE  p.`Codigo`=a.`CodigoPrograma` and a.`Codigo` =:codigo";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':codigo'=>$codigo));
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	public function altera( $p ){
		try{
			parent::beginTransaction();
			$sql="UPDATE `acao` SET `AnaliseCritica`=? WHERE `Codigo`=?";
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1,$p->getAnalisecritica()) ;
			$stmt->bindValue(2,$p->getCodigo());
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
}
?>



<?php
class PsaudemensalDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	public $conex = null;

	//public function PsaudemensalDAO(){
	//	$this->conex = PDOConnectionFactory::getConnection();
	//}

	public function deleta( $p ){
		try{
			parent::beginTransaction();
			//$stmt = $this->conex->prepare("DELETE FROM `psaudemensal` WHERE `Codigo`=?");
			$stmt = parent::prepare("DELETE FROM `psaudemensal` WHERE `Codigo`=?");
			$stmt->bindValue(1, $p->getCodigo() );
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}


	public function insere( $sp ){
	try{
			parent::beginTransaction();
			$sql="INSERT INTO `psaudemensal` ( `CodServProc`, `CodLocal`,".
		" `Mes`,`Ano`,`ndocentes`,`ndiscentes`,`npesquisadores`,`npessoasatendidas`,nprocedimentos,`nexames`)".
		  " VALUES (?,?,?,?,?,?,?,?,?,?)";
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1,$sp->getCodigo());
			$stmt->bindValue(2,$sp->getPsaude()->getLocal()->getCodigo());
			$stmt->bindValue(3,$sp->getPsaude()->getMes());
			$stmt->bindValue(4,$sp->getPsaude()->getAno());
			$stmt->bindValue(5,$sp->getPsaude()->getNdocentes());
			$stmt->bindValue(6,$sp->getPsaude()->getNdiscentes());
			$stmt->bindValue(7,$sp->getPsaude()->getNpesquisadores());
			$stmt->bindValue(8,$sp->getPsaude()->getNpessoasatendidas());
			$stmt->bindValue(9,$sp->getPsaude()->getNprocedimentos());

			$stmt->bindValue(10,$sp->getPsaude()->getNexames());
			$stmt->execute();

			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function altera( $sp ){
		try{
			parent::beginTransaction();
			$sql="UPDATE `psaudemensal` SET  `CodServProc`=? , `CodLocal`=?,".
			" `Mes`=?, `Ano`=?,`ndocentes`=?,`ndiscentes`=?,`npesquisadores`=?,".
			" `npessoasatendidas`=?,nprocedimentos=?,`nexames`=? WHERE `Codigo`=?";
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1,$sp->getCodigo());
			$stmt->bindValue(2,$sp->getPsaude()->getLocal()->getCodigo());
			$stmt->bindValue(3,$sp->getPsaude()->getMes());
			$stmt->bindValue(4,$sp->getPsaude()->getAno());
			$stmt->bindValue(5,$sp->getPsaude()->getNdocentes());
			$stmt->bindValue(6,$sp->getPsaude()->getNdiscentes());
			$stmt->bindValue(7,$sp->getPsaude()->getNpesquisadores());
			$stmt->bindValue(8,$sp->getPsaude()->getNpessoasatendidas());
			$stmt->bindValue(9,$sp->getPsaude()->getNprocedimentos());

			$stmt->bindValue(10,$sp->getPsaude()->getNexames());
			$stmt->bindValue(11,$sp->getPsaude()->getCodigo());
			$stmt->execute();

			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function altera1( $sp ){
		try{
			parent::beginTransaction();
			$sql="UPDATE `psaudemensal` SET  `CodServProc`=?  ,".
				" `Mes`=?, `Ano`=?,`ndocentes`=?,`ndiscentes`=?,`npesquisadores`=?,".
				" `npessoasatendidas`=?,`nexames`=? WHERE `Codigo`=?";
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1,$sp->getCodigo());
			//	if ($sp->getPsaude()->getLocal()->getCodigo()!=0)
			//	   $stmt->bindValue(2,$sp->getPsaude()->getLocal()->getCodigo());
			//		else $stmt->bindValue(2,null);
			$stmt->bindValue(2,$sp->getPsaude()->getMes());
			$stmt->bindValue(3,$sp->getPsaude()->getAno());
			$stmt->bindValue(4,$sp->getPsaude()->getNdocentes());
			$stmt->bindValue(5,$sp->getPsaude()->getNdiscentes());
			$stmt->bindValue(6,$sp->getPsaude()->getNpesquisadores());
			$stmt->bindValue(7,$sp->getPsaude()->getNpessoasatendidas());

			$stmt->bindValue(8,$sp->getPsaude()->getNexames());
			$stmt->bindValue(9,$sp->getPsaude()->getCodigo());
			$stmt->execute();
			
			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			echo "Erro em ".$ex->getMessage();//para aceitar caracteres especiais
		}
	}
	public function insere1( $sp ){
		try{
			parent::beginTransaction();
			$sql="INSERT INTO `psaudemensal` ( `CodServProc`,".
			" `Mes`,`Ano`,`ndocentes`,`ndiscentes`,`npesquisadores`,`npessoasatendidas`,`nprocedimentos`,`nexames`)".
			  " VALUES (?,?,?,?,?,?,?,?,?)";
			$stmt = parent::prepare($sql);
			
			$stmt->bindValue(1,$sp->getCodigo());
			$stmt->bindValue(2,$sp->getPsaude()->getMes());
			$stmt->bindValue(3,$sp->getPsaude()->getAno());
			$stmt->bindValue(4,$sp->getPsaude()->getNdocentes());
			
			
			$stmt->bindValue(5,$sp->getPsaude()->getNdiscentes());
			$stmt->bindValue(6,$sp->getPsaude()->getNpesquisadores());
			$stmt->bindValue(7,$sp->getPsaude()->getNpessoasatendidas());
			
			$stmt->bindValue(8,$sp->getPsaude()->getNprocedimentos());

			$stmt->bindValue(9,$sp->getPsaude()->getNexames());
			$stmt->execute();

			parent::commit();
		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	public function ListaMensal($ano,$codunidade,$codsubunidade){
		try{
			$sql="SELECT `Mes` ,u.`NomeUnidade`, s.`Nome` , o.`Nome` , `ndocentes` , `ndiscentes` , ".
		" `npesquisadores` , `npessoasatendidas`,nprocedimentos,`nexames`".
		" FROM `servico` s, `procedimentos` o, `psaudemensal` p".
		" WHERE s.`CodUnidade` = o.`CodUnidade`".
		" AND s.`CodSubunidade` = o.`CodSubunidade`".
		" AND s.`Codigo` = p.`CodServico`".
	    " AND p.`CodProcedimento` = o.`CodProcedimento`".
		" AND p.`CodLocal` = u.`CodUnidade`".
		" AND `Ano` = :ano".
		" AND s.`CodUnidade`=:unidade ".
		" AND s.`CodSubunidade`=:subunidade ".
		" ORDER BY u.`NomeUnidade`,`Mes`";
		    $stmt = parent::prepare($sql);
			$stmt->execute(array(':ano'=>$ano,':unidade'=>$codunidade,':subunidade'=>$codsubunidade));
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function ListaMensal1($ano,$codlocal,$codservico,$codprocedimento){
		try{
			$sql="SELECT `Mes` , u.`NomeUnidade` as local, s.`Nome` as servico, o.`Nome` as procedimento,".
" `ndocentes` , `ndiscentes` , `npesquisadores` , `npessoasatendidas`,nprocedimentos,`nexames`, p.`Codigo`".
"  FROM `servico` s, `procedimento` o, `servproced` sp, `psaudemensal` p, `unidade` u".
"  WHERE  p.`Ano` = :ano".
"  AND u.`CodUnidade`=:local".
" AND s.`Codigo`=:servico".
" AND o.`CodProcedimento`=:procedimento".
" and (p.`CodLocal` = u.`CodUnidade`)".
" AND (s.`Codigo`=sp.`CodServico`".
" AND o.`CodProcedimento`=sp.`CodProced`)".
" AND (p.`CodServProc`= sp.`CodServProc`)".
" ORDER BY u.`NomeUnidade`,`Mes`";
$stmt = parent::prepare($sql);
$stmt->execute(array(':ano'=>$ano,':local'=>$codlocal,':servico'=>$codservico,':procedimento'=>$codprocedimento));
return $stmt;
}catch ( PDOException $ex ){
$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
header($cadeia);
}
}

public function ListaMensal12($ano,$codservico,$codprocedimento){
	try{
		$sql="SELECT `Mes` , s.`Nome` as servico, o.`Nome` as procedimento,".
" `ndocentes` , `ndiscentes` , `npesquisadores` , `npessoasatendidas`,nprocedimentos,`nexames`, p.`Codigo`".
"  FROM `servico` s, `procedimento` o, `servproced` sp, `psaudemensal` p".
"  WHERE  p.`Ano` = :ano".
" AND s.`Codigo`=:servico".
" AND o.`CodProcedimento`=:procedimento".
" AND (s.`Codigo`=sp.`CodServico`".
" AND o.`CodProcedimento`=sp.`CodProced`)".
" AND (p.`CodServProc`= sp.`CodServProc`)".
" ORDER BY `Mes`";
        $stmt = parent::prepare($sql);
		$stmt->execute(array(':ano'=>$ano,':servico'=>$codservico,':procedimento'=>$codprocedimento));
		return $stmt;
	}catch ( PDOException $ex ){
		$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
		$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
		header($cadeia);
	}
}
	public function psaudecodigo($codigo){
		try{
			$sql="SELECT `Mes` , u.`CodUnidade` as codlocal,u.`NomeUnidade` as local,
  sub.`CodUnidade` as codsubunidade, sub.`NomeUnidade` as subunidade,
  s.`Codigo` as codservico,s.`Nome` as servico, o.`CodProcedimento` as codproc,
  o.`Nome` as procedimento, `ndocentes` , `ndiscentes`,nprocedimentos ,`nexames`,
  `npesquisadores` , `npessoasatendidas`, p.`Codigo`
  FROM `unidade` sub,`servico` s, `procedimento` o,`servproced` sp ,
 `psaudemensal` p, `unidade` u
  WHERE (s.`CodSubunidade` = sub.`CodUnidade`)
   and (sp.`CodServico` = s.`Codigo`)
   AND (o.`CodProcedimento` = sp.`CodProced`)
   and (sp.`CodServProc`=p.`CodServProc`)
   AND (p.`CodLocal` = u.`CodUnidade`)
   AND p.`Codigo` = :codigo";

		    $stmt = parent::prepare($sql);
			$stmt->execute(array(':codigo'=>$codigo));
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			echo "Erro psaudecodigo ".$ex->getCode().$mensagem;
		}
	}


	public function psaudecodigo1($codigo){
		try{
			$sql="SELECT `Mes` , ".
	 " sub.`CodUnidade` as codsubunidade, sub.`NomeUnidade` as subunidade,".
	 " s.`Codigo` as codservico,s.`Nome` as servico, o.`CodProcedimento` as codproc,".
	 " o.`Nome` as procedimento, `ndocentes` , `ndiscentes` ,nprocedimentos,`nexames`,".
	 " `npesquisadores` , `npessoasatendidas`, p.`Codigo`".
	 " FROM `unidade` sub,`servico` s, `procedimento` o,`servproced` sp ,".
	 "`psaudemensal` p ".
	 " WHERE (s.`CodSubunidade` = sub.`CodUnidade`)".
	 " and (sp.`CodServico` = s.`Codigo`)".
	 " AND (o.`CodProcedimento` = sp.`CodProced`)".
	 " and (sp.`CodServProc`=p.`CodServProc`)".
	  " AND p.`Codigo` = :codigo";

		    $stmt = parent::prepare($sql);
			$stmt->execute(array(':codigo'=>$codigo));
			return $stmt;
		}catch ( PDOException $ex ){
		    $mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
		    echo "Erro psaudecodigo1 ".$ex->getCode().$mensagem;
		}
	}
	public function BuscaPsaudemensal($ano,$mes,$codlocal,$codservico,$codprocedimento){
		try{
			$sql="SELECT *  FROM `servproced` sp, `psaudemensal` p ".
			" WHERE p.`Mes` = :mes ".
			" AND p.`Ano` = :ano ".
			" AND p.`CodLocal`=:local ".
			" AND sp.`CodServico`=:servico ".
			" AND sp.`CodProced`=:procedimento ".
			" AND sp.`CodServProc`=p.`CodServProc`";
			//$stmt = $this->conex->prepare($sql);
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano'=>$ano,':mes'=>$mes,':local'=>$codlocal,':servico'=>$codservico,':procedimento'=>$codprocedimento));
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}

	public function BuscaPsaudemensal1($ano,$mes,$codservico,$codprocedimento){
		try{
				//		echo $ano.','.$mes.','.$codservico.','.$codprocedimento;
			
			$sql="SELECT *  FROM `servproced` sp, `psaudemensal` p ".
				" WHERE p.`Mes` = :mes ".
				" AND p.`Ano` = :ano ".
				" AND sp.`CodServico`=:servico ".
				" AND sp.`CodProced`=:procedimento ".
				" AND sp.`CodServProc`=p.`CodServProc`";
			
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano'=>$ano,':mes'=>$mes,':servico'=>$codservico,':procedimento'=>$codprocedimento));
			return $stmt;
		}catch ( PDOException $ex ){
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
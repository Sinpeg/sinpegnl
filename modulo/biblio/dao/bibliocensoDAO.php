<?php
class BibliocensoDAO extends PDOConnectionFactory {
	// ir� receber uma conex�o
	private $conex = null;

	  // constructor
	 //  public function BibliocensoDAO(){
	//	 $this->conex = PDOConnectionFactory::getConnection();
   //	}

	public function deleta( $ea ){
		try{
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `biblicenso` WHERE `idBiblicenso`=?");
			$stmt->bindValue(1, $ea->getIdbiblicenso());
			$stmt->execute();
			parent::commit();
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);
		}
	}
	
	public function consultabiblio($parametro ,$anoin, $anofim) {
		try {
			$sql = "SELECT emec.idBibliemec,
					 emec.idUnidade,
					 emec.nome as Biblioteca,
					 cen.idBiblicenso,
					 cen.nAssentos,
					 cen.nEmpDomicilio,
					 cen.nEmpBiblio,
					 cen.frequencia,
					 cen.ano
					 FROM bibliemec emec,
					 biblicenso cen
					 WHERE emec.idBibliemec = cen.idBibliemec
					 AND cen.ano >= :anoin AND cen.ano <= :anofim " .$parametro;
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':anoin' => $anoin, ':anofim' => $anofim));
			// retorna o resultado da query
			return $stmt;
		} catch (PDOException $ex) {
			$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
			$cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
			header($cadeia);
		}
	}
	
	//Busca as bibliotecas da Unidade(Instituto)
	public function buscaBiblioUni($hierarquia ,$ano) {
		try {
			$sql = 'SELECT emec.idBibliemec,
					 emec.idUnidade,
					 emec.nome as Biblioteca,
					 cen.idBiblicenso,
					 cen.nAssentos,
					 cen.nEmpDomicilio,
					 cen.nEmpBiblio,
					 cen.frequencia,
					 cen.ano,
					 uni.hierarquia_organizacional
					 FROM bibliemec emec,
					 biblicenso cen,
					 unidade uni
					 WHERE emec.idBibliemec = cen.idBibliemec
					 AND cen.ano = :ano 
					 AND emec.idUnidade = uni.CodUnidade
					 AND uni.hierarquia_organizacional LIKE "%'.$hierarquia.'%"';
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano' => $ano));
			// retorna o resultado da query
			return $stmt;
		} catch (PDOException $ex) {
			$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
			$cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
			header($cadeia);
		}
	}
	
	public function buscaBibli($idbibliemec, $ano){
		try{
			$stmt = parent::prepare("SELECT * FROM   `biblicenso` where `idBibliemec` = :idbibli and `Ano` = :ano ");
			$stmt->execute(array(':idbibli'=>$idbibliemec,':ano'=>$ano));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem+" buscaBibli";
			header($cadeia);
		}
	}
	
	public function buscaBiblitodos($ano){
		try{
			$stmt = parent::prepare("SELECT * FROM  `bibliemec` emec LEFT OUTER JOIN `biblicenso` censo ON censo.`idBibliemec` = emec.`idBibliemec` WHERE `Ano` = :ano");
			$stmt->execute(array(':ano'=>$ano));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem+" buscaBibli";
			header($cadeia);
		}
	}

	public function buscaBibliId($id){
		try{
			$stmt = parent::prepare("SELECT * FROM   `biblicenso` where `idBiblicenso` = :id ");
			$stmt->execute(array(':id'=>$id));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem+" buscaBibliId " ;
			header($cadeia);
		}
	}
	
	public function buscaBibliIdeAno($id, $ano){
		try{
			$stmt = parent::prepare("SELECT * FROM   `biblicenso` where `idBiblicenso` = :id and `Ano` = :ano ");
			$stmt->execute(array(':id'=>$id, ':ano'=>$ano));
			// retorna o resultado da query
			return $stmt;
		}catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem+" buscaBibliId " ;
			header($cadeia);
		}
	}


	public function insere( $be ){
		try{
			parent::beginTransaction();
			$sql="INSERT INTO `biblicenso` ( `idBibliemec`, `nAssentos`, `nEmpDomicilio`, `nEmpBiblio`, `frequencia`,"
		 ." `nConsPresencial`, `nConsOnline`, `fBuscaIntegrada`, `comutBibliog`, `servInternet`, `nUsuariosTpc`, `redeSemFio`,"
		 ."`partRedeSociais`, `nItensAcervoElet`, `nItensAcervoImp`, `atendTreiLibras`, `acervoFormEspecial`, `appFormEspecial`,"
		 ."`planoFormEspecial`, `sofLeitura`, `tecVirtual`, `impBraile`, `portalCapes`, `outrasBases`, `bdOnlineSerPub`,"
		 ." `catOnlineSerPub`, `ano`)"
		 ." 	VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
				$stmt = parent::prepare($sql);

				$stmt->bindValue(1,$be->getIdBibliemec());
				$stmt->bindValue(2,$be->getBiblicenso()->getNassentos());
				$stmt->bindValue(3,$be->getBiblicenso()->getNempDomicilio());
				$stmt->bindValue(4,$be->getBiblicenso()->getNempBiblio());
				$stmt->bindValue(5,$be->getBiblicenso()->getFrequencia());
				$stmt->bindValue(6,$be->getBiblicenso()->getNconsPresencial());
				$stmt->bindValue(7,$be->getBiblicenso()->getNconsOnline());
				$stmt->bindValue(8,$be->getBiblicenso()->getFbuscaIntegrada());
				$stmt->bindValue(9,$be->getBiblicenso()->getComutBibliog());
				$stmt->bindValue(10,$be->getBiblicenso()->getServInternet());
				$stmt->bindValue(11,$be->getBiblicenso()->getNusuariosTpc());
				$stmt->bindValue(12,$be->getBiblicenso()->getRedeSemFio());
			    $stmt->bindValue(13,$be->getBiblicenso()->getPartRedeSociais());
				$stmt->bindValue(14,$be->getBiblicenso()->getNitensAcervoElet());
				$stmt->bindValue(15,$be->getBiblicenso()->getNitensAcervoImp());
				$stmt->bindValue(16,$be->getBiblicenso()->getAtendTreiLibras());
				$stmt->bindValue(17,$be->getBiblicenso()->getAcervoFormEspecial());
				$stmt->bindValue(18,$be->getBiblicenso()->getAppFormEspecial());
				$stmt->bindValue(19,$be->getBiblicenso()->getPlanoFormEspecial());
				$stmt->bindValue(20,$be->getBiblicenso()->getSofLeitura());
				$stmt->bindValue(21,$be->getBiblicenso()->getTecVirtual());
				$stmt->bindValue(22,$be->getBiblicenso()->getImpBraile());
				$stmt->bindValue(23,$be->getBiblicenso()->getPortalCapes());
				$stmt->bindValue(24,$be->getBiblicenso()->getOutrasBases());
				$stmt->bindValue(25,$be->getBiblicenso()->getBdOnlineSerPub());
				$stmt->bindValue(26,$be->getBiblicenso()->getCatOnlineSerPub());

				$stmt->bindValue(27,$be->getBiblicenso()->getAno());
				$stmt->execute();

			parent::commit();
		}catch ( PDOException $ex ){
			/*parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			$cadeia="location:../saida/erro.php?codigo=".$ex->getCode()."&mensagem=".$mensagem;
			header($cadeia);*/
		}



	}

	public function altera( $be ){
		try{
			parent::beginTransaction();
			$sql = "UPDATE `biblicenso` SET `nAssentos`=?,`nEmpDomicilio`=?,`nEmpBiblio`=?,`frequencia`=?,`nConsPresencial`=?,"
			."`nConsOnline`=?,`fBuscaIntegrada`=?,`comutBibliog`=?,`servInternet`=?,`nUsuariosTpc`=?,`redeSemFio`=?,`partRedeSociais`=?,"
		    ."`nItensAcervoElet`=?,`nItensAcervoImp`=?,`atendTreiLibras`=?,`acervoFormEspecial`=?,`appFormEspecial`=?,"
		    ."`planoFormEspecial`=?,`sofLeitura`=?,`tecVirtual`=?,`impBraile`=?,`portalCapes`=?,`outrasBases`=?,`bdOnlineSerPub`=?,"
		    ."`catOnlineSerPub`=?  WHERE `idBiblicenso`=?";
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1,$be->getBiblicenso()->getNassentos());
				echo $be->getBiblicenso()->getNassentos();
				$stmt->bindValue(2,$be->getBiblicenso()->getNempDomicilio());
				$stmt->bindValue(3,$be->getBiblicenso()->getNempBiblio());
				$stmt->bindValue(4,$be->getBiblicenso()->getFrequencia());
				$stmt->bindValue(5,$be->getBiblicenso()->getNconsPresencial());
				$stmt->bindValue(6,$be->getBiblicenso()->getNconsOnline());
				$stmt->bindValue(7,$be->getBiblicenso()->getFbuscaIntegrada());
				$stmt->bindValue(8,$be->getBiblicenso()->getComutBibliog());
				$stmt->bindValue(9,$be->getBiblicenso()->getServInternet());
				$stmt->bindValue(10,$be->getBiblicenso()->getNusuariosTpc());
				$stmt->bindValue(11,$be->getBiblicenso()->getRedeSemFio());
			    $stmt->bindValue(12,$be->getBiblicenso()->getPartRedeSociais());
				$stmt->bindValue(13,$be->getBiblicenso()->getNitensAcervoElet());
				$stmt->bindValue(14,$be->getBiblicenso()->getNitensAcervoImp());
				$stmt->bindValue(15,$be->getBiblicenso()->getAtendTreiLibras());
				$stmt->bindValue(16,$be->getBiblicenso()->getAcervoFormEspecial());
				$stmt->bindValue(17,$be->getBiblicenso()->getAppFormEspecial());
				$stmt->bindValue(18,$be->getBiblicenso()->getPlanoFormEspecial());
				$stmt->bindValue(19,$be->getBiblicenso()->getSofLeitura());
				$stmt->bindValue(20,$be->getBiblicenso()->getTecVirtual());
				$stmt->bindValue(21,$be->getBiblicenso()->getImpBraile());
				$stmt->bindValue(22,$be->getBiblicenso()->getPortalCapes());
				$stmt->bindValue(23,$be->getBiblicenso()->getOutrasBases());
				$stmt->bindValue(24,$be->getBiblicenso()->getBdOnlineSerPub());
				$stmt->bindValue(25,$be->getBiblicenso()->getCatOnlineSerPub());
				$stmt->bindValue(26,$be->getBiblicenso()->getIdBiblicenso());

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

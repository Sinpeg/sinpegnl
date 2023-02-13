<?php

class ObjetivoDAO extends PDOConnectionFactory{
	
	public $conex = null;
	
	public function insere(Objetivo $objetivo) {
		try {
			$query = "INSERT INTO `objetivo` (`Codigo`, `objetivo`, `descricaoObjetivo`) VALUES (?,?,?)";
			$stmt = parent::prepare($query);
			parent::beginTransaction();
			$stmt->bindValue(1, $objetivo->getCodigo());
			$stmt->bindValue(2, $objetivo->getObjetivo());
			$stmt->bindValue(2, $objetivo->getDescricao());
			
			$stmt->execute();
			parent::commit();
		} catch (PDOException $ex) {
			parent::rollback();
			print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}
	
	
	
	public function altera(Objetivo $objetivo) {
		try {
			$stmt = parent::prepare("UPDATE `objetivo` SET `Objetivo`=? `DescricaoObjetivo`=? WHERE `codigo`=?");
			parent::beginTransaction();
			$stmt->bindValue(1, $objetivo->getObjetivo());
			$stmt->bindValue(2, $objetivo->getDescricao());
			$stmt->bindValue(2, $objetivo->getCodigo());
			$stmt->execute();
			parent::commit();
			
		} catch (PDOException $ex) {
			parent::rollback();
			print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}
	
	public function deleta($codigo) {
		try {
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `objetivo` WHERE `Codigo`=?");
			$stmt->bindValue(1, $codigo);
			$stmt->execute();
			parent::commit();
		} catch (PDOException $ex) {
			print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
		}
	}
	
	public function lista() {
		try {
			
			$sql = "SELECT * FROM `objetivo` order by objetivo";
			$stmt = parent::prepare($sql);
			$stmt->execute();
			return $stmt;
			
		} catch (PDOException $ex) {
			print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
		}
	}

public function buscaObjetivoPorDocumento($codDocumento){
		try {
			$sql = "SELECT ob.Codigo, ob.Objetivo, ob.DescricaoObjetivo,ma.Codigo codMapa, per.codPerspectiva, per.nome as perspectiva FROM `objetivo` ob JOIN mapa ma ON(ma.codObjetivoPDI = ob.Codigo) JOIN perspectiva per ON(per.codPerspectiva = ma.codPerspectiva) WHERE ma.CodDocumento=:codDocumento order by per.codPerspectiva";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':codDocumento' => $codDocumento));
			return $stmt;
		} catch (PDOException $ex) {
									
            print "Erro buscaObjetivoPorDocumento : " . $ex->getCode() . " " . $ex->getMessage();

		}
	}

public function buscaObjetivoPorDocumento1($codDocumento,$codunidade,$anobase){
		try {
			$sql = "SELECT ob.Codigo, ob.Objetivo, ob.DescricaoObjetivo,ma.Codigo codMapa, per.codPerspectiva, per.nome as perspectiva 
			FROM `objetivo` ob 
			JOIN mapa ma ON(ma.codObjetivoPDI = ob.Codigo)
			 JOIN perspectiva per ON(per.codPerspectiva = ma.codPerspectiva) 
			WHERE ma.CodDocumento=:codDocumento and ma.anoinicio<=:ano and (ma.anofim>:ano or ma.anofim is NULL)
			union select * from (select ob1.Codigo, ob1.Objetivo, ob1.DescricaoObjetivo,m1.Codigo codMapa, per1.codPerspectiva, per1.nome as perspectiva 
    		 FROM mapa m1 JOIN documento doc1 ON(m1.CodDocumento = doc1.codigo) and m1.anoinicio<=:ano and (m1.anofim>:ano or m1.anofim is NULL)
    		 JOIN objetivo ob1 ON(m1.codObjetivoPDI = ob1.Codigo) JOIN perspectiva per1 ON(m1.codPerspectiva = per1.codPerspectiva)  
    		 inner join mapaindicador mi on mi.codMapa=m1.`Codigo` and mi.anoinicial<=:ano and (mi.anofinal>:ano or mi.anofinal is NULL)
    		 WHERE mi.`PropIndicador` =:unid and doc1.anoinicial<=:ano AND doc1.anofinal >= :ano and doc1.CodUnidade=938 ) as b
   		
			";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':codDocumento' => $codDocumento,':ano'=>$anobase,':unid'=>$codunidade));
			return $stmt;
		} catch (PDOException $ex) {
									
            print "Erro buscaObjetivoPorDocumento1 : " . $ex->getCode() . " " . $ex->getMessage();

		}
	}
	
public function buscaObjetivoPorMapa($codmapa){
		try {
			$sql = "SELECT ob.Codigo as codobj,Objetivo as des FROM `objetivo` ob inner JOIN mapa ma ON ma.codObjetivoPDI = ob.Codigo WHERE ma.codigo =:mapa ";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':mapa' => $codmapa));
			return $stmt;
		} catch (PDOException $ex) {
									
            print "Erro buscaObjetivoPorMapa : " . $ex->getCode() . " " . $ex->getMessage();

		}
	}
	
	public function buscaObjetivoPorSolicitacao($codSol){
		try {
			$sql = "SELECT ob.Codigo as codobj,ob.Objetivo AS nomeObjetivo FROM `solicitacaoPDU` AS s inner JOIN mapaindicador mi ON mi.codigo = s.codmapaind INNER JOIN mapa AS ma ON ma.Codigo = mi.codMapa INNER JOIN objetivo AS ob ON ob.Codigo = ma.codObjetivoPDI WHERE s.codigo =:codSol ";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':codSol' => $codSol));
			return $stmt;
		} catch (PDOException $ex) {
				
			print "Erro buscaObjetivoPorMapa : " . $ex->getCode() . " " . $ex->getMessage();
	
		}
	}
	
	
    public function buscaobjsPDI($anogestao){
        try {
              $sql="  SELECT distinct o.Codigo,Objetivo FROM `documento` d, mapa m, objetivo o WHERE    d.`anoinicial`<=? and d.`anofinal`>=? and `codObjetivoPDI`=o.codigo and m.`CodDocumento`=d.codigo and d.tipo=1 order by Objetivo";
              $stmt = parent::prepare($sql);
              $stmt->bindValue(1, $anogestao);
              $stmt->bindValue(2, $anogestao);
			  $stmt->execute();
              return $stmt;
		} catch (PDOException $ex) {
				print "Erro buscaobjsPDI: " . $ex->getCode() . " " . $ex->getMessage();

		}
    }
    
    

    
public function buscaobjsPDI1($anogestao,$codunidade){//NAO ESTA CORRETA
        try {
              $sql="  SELECT distinct o.Codigo,Objetivo FROM `documento` d, mapa m, objetivo o WHERE    d.`anoinicial`<=? and d.`anofinal`>=? ". 
              " and `codObjetivoPDI`=o.codigo and m.`CodDocumento`=d.codigo and d.tipo=1  and o.codigo not in (".
              
              " select  ob1.codigo ".
    		" FROM mapa m1 JOIN documento doc1 ON(m1.CodDocumento = doc1.codigo) ".
    		" JOIN objetivo ob1 ON(m1.codObjetivoPDI = ob1.Codigo) JOIN perspectiva pers1 ON(m1.codPerspectiva = pers1.codPerspectiva)  ".
    		" inner join mapaindicador mi on mi.codMapa=m1.`Codigo` ".
    		" WHERE mi.`PropIndicador` = ? and doc1.anoinicial<=? AND doc1.anofinal >= ? and doc1.CodUnidade=938 and ".
              "o.Codigo=ob1.codigo )".
            " order by Objetivo";
              $stmt = parent::prepare($sql);
              $stmt->bindValue(1, $anogestao);
              $stmt->bindValue(2, $anogestao);              
              $stmt->bindValue(3, $codunidade);              
              $stmt->bindValue(4, $anogestao);              
              $stmt->bindValue(5, $anogestao);              
                            
			  $stmt->execute();
              return $stmt;
		} catch (PDOException $ex) {
				print "Erro buscaobjsPDI1: " . $ex->getCode() . " " . $ex->getMessage();

		}
    }
    
    
    
   
	
	public function buscaobjetivo($codigo) {
		try {
			$sql = "SELECT * FROM `objetivo` WHERE `codigo`=:codigo";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':codigo' => $codigo));
			return $stmt;
		} catch (PDOException $ex) {
						print "Erro buscaobjetivo : " . $ex->getCode() . " " . $ex->getMessage();

		}
	}
	
	public function fechar() {
		PDOConnectionFactory::Close();
	}
	
} 

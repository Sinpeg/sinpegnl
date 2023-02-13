<?php
class IniciativaDAO extends PDOConnectionFactory {

	
	public $conex = null;
	/*
	 public function MapaDAO() {
	 $this->conex = PDOConnectionFactory::getConnection();
	 }
	 */
	public function insere(Iniciativa $iniciativa) {
	try {
			$sql = "INSERT INTO `iniciativa` (`nome`, `anoInicio`,`codUnidade`,`anoFinal`)
					VALUES (?,?,?,?)";
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1, $iniciativa->getNome());
			$stmt->bindValue(2, $iniciativa->getAnoinicio());
            $stmt->bindValue(3, $iniciativa->getUnidade()->getCodUnidade());
            $stmt->bindValue(4, $iniciativa->getAnofinal());
            
			$stmt->execute();
			//return parent::lastInsertId();
			
		} catch (PDOException $ex) {
			print "Erro insere: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}
	
// 	public function buscaMpaByCodigoIndicador($codindicador){
// 		try {
// 			$sql = "SELECT * FROM `mapa` m JOIN `mapaindicador` maI ON(m.Codigo = maI.codMapa) WHERE maI.codIndicador = :codind"  ;
// 			$stmt = parent::prepare($sql);
// 			$stmt->execute(array(':codind' => $codindicador));
// 			return $stmt;
// 		} catch (PDOException $ex) {
// 			echo "Erro: " . $ex->getMessage();
// 		}
// 	}
	
	public function altera(Iniciativa $iniciativa) {
		try {
			$sql = "UPDATE `iniciativa` SET `nome` =?, `codUnidade` =?, `anoInicio` =?,`anoFinal`=? WHERE `codIniciativa`=?";
			$stmt = parent::prepare($sql);
			parent::beginTransaction();
			//            print $sql;
			$stmt->bindValue(1, $iniciativa->getNome());
			$stmt->bindValue(2, $iniciativa->getUnidade()->getCodunidade());			
			$stmt->bindValue(3, $iniciativa->getAnoinicio());
			$stmt->bindValue(4, $iniciativa->getAnofinal());
			$stmt->bindValue(5, $iniciativa->getCodIniciativa());
			$stmt->execute();
			
			parent::commit();
		} catch (PDOException $ex) {
			print "Erro altera: " . $ex->getCode() . " " . $ex->getMessage();
		}
	}
	
	public function deleta($codIniciativa) {
		try {
			
			parent::beginTransaction();
			$sql="DELETE FROM `iniciativa` WHERE `codIniciativa`=:codini";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':codini' => $codIniciativa));
			parent::commit();
		} catch (PDOException $ex) {
			parent::rollback();
			print "Erro deleta: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}
	
	
	public function lista($codunidade,$ano) {
		try {		
			$sql = 	" SELECT i.codIniciativa,nome, i.anoInicio,anogestao,i.codunidade , 
			CASE WHEN periodo=1 THEN 'Parcial'
			else 'Final' end as periodo,
			case
			WHEN i.anofinal<=:ano   THEN  'Cancelada' 
			WHEN situacao='2' THEN 'Em atraso'
			WHEN situacao='3' THEN 'Com atrasos críticos'
			WHEN situacao='4' THEN 'Em andamento normal'
			WHEN situacao='5' THEN 'Concluído'
			WHEN i.anoinicio<=:ano and (i.anofinal>=:ano or i.anofinal is null )  THEN 'Normal' END as Situacao 
			FROM
			 (select  i1.`codIniciativa`, i1.`nome`, i1.`codUnidade`, i1.`finalidade`, 
			 i1.`coordenador`, i1.`anoInicio`, i1.`anoFinal` FROM `iniciativa` i1
			  where i1.anoinicio<=:ano and (i1.anofinal>=:ano or i1.anofinal is null ) ) as i 
			 left join 	 
			 (select r1.`codResultIniciativa`, r1.`codCalendario`, r1.`codIniciativa`, r1.`situacao`, r1.`pfcapacit`,r1.`pfrecti`, r1.`pfinfraf`, 
			 r1.`pfrecf`, r1.`pfplanj`, r1.`outros`, r1.`periodo`,c.anogestao
			 from resultIniciativa r1 
			 inner join calendario c on c.codcalendario=r1.codcalendario and ( c.anogestao=:ano)
			 where 1 ) as r on i.codiniciativa=r.codIniciativa 
			
			where i.codunidade=:unid   
			ORDER BY i.`codIniciativa`";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':unid' => $codunidade,':ano'=>$ano,':ano'=>$ano,':ano'=>$ano));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro lista: " . $ex->getMessage();
		}
	}
	
	public function BuscaIniciativa($codigo,$ano){
		try {
			$sql = "SELECT codIniciativa,nome,codunidade,anoInicio,codUnidade,
			case WHEN anoinicio<=:ano and (anofinal>:ano or anofinal is null )  THEN 'Normal' ELSE  'Cancelada' END as Situacao 
			 FROM `iniciativa` WHERE `codIniciativa`=:codini";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':codini' => $codigo,':ano' => $ano));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro buscainiciativa: " . $ex->getMessage();
		}
	}
    
    public function buscaPorNome($nome){
		try {
			$sql = "SELECT * FROM `iniciativa` WHERE `nome`=:nome";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':nome' => $nome));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro buscaPorNome: " . $ex->getMessage();
		}
	}
	
	public function buscaIniciativaUnidade($codUnidade) {
		try {
			$sql = "SELECT * FROM `iniciativa` WHERE `codUnidade`=:coduni";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':coduni' => $codUnidade));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro buscaIniciativaUnidade: " . $ex->getMessage();
		}
	}
	
	public function buscaIndicadoresVinculadosPorIniciativa($codiniciativa,$anobase){
		try {

			$sql = "SELECT ind.Codigo, ind.nome 
			        FROM iniciativa ini 
					JOIN indic_iniciativa iin ON(ini.codIniciativa = iin.codIniciativa ) 
                    and iin.anoinicial<=:ano and (iin.anofinal>=:ano OR iin.anofinal is NULL )
					JOIN mapaindicador mi ON (mi.codigo = iin.codMapaInd  ) 
                    and  mi.anoinicial<=:ano and (mi.anofinal>=:ano or mi.anofinal is NULL)
					JOIN indicador ind ON(ind.Codigo = mi.codIndicador)

					WHERE ini.codIniciativa=:codini";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':codini' => $codiniciativa,':ano' => $anobase));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro buscaIndicadoresVinculadosPorIniciativa: " . $ex->getMessage();
		}
	}
	
	public function fechar() {
		PDOConnectionFactory::Close();
	}
	
	
}

?>

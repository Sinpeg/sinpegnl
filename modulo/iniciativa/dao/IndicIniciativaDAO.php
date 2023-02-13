<?php
class IndicIniciativaDAO extends PDOConnectionFactory {
	
	public $conex = null;
	
   public function iniciativaPorMapIndicador($micodigo,$ano,$periodo) {
        $sql=" SELECT  distinct i.`codIniciativa` , i.`nome` , i.`finalidade`,ii.codigo as codindinic,i.anoInicio, periodo
        FROM `iniciativa` i INNER JOIN `indic_iniciativa` ii ON i.`codIniciativa` = ii.`codIniciativa` and i.anoinicio<=:ano 
        and (i.anofinal>=:ano or i.anofinal is null )     
        INNER JOIN `mapaindicador` mi ON mi.`codigo` = ii.`codMapaInd` and ( mi.anoinicial<=:ano) and (mi.anofinal is NULL or mi.anofinal>=:ano)
        left join resultIniciativa ri on ri.`codIniciativa`=i.`codIniciativa` and periodo=:periodo 
        and  ((situacao=5 and ii.anofinal>=:ano)  or (situacao<5))
        left join calendario c on c.`codCalendario`=ri.codCalendario and anogestao=:ano
        WHERE mi.`codigo`=:micodigo ";
        
        
        
		try {
            $stmt = parent::prepare($sql);
    		$stmt->execute(array(':micodigo' => $micodigo,':ano' => $ano,":periodo" => $periodo));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro iniciativaPorMapIndicador: " . $ex->getMessage();
		}
	}
    
  /*  public function iniciativaPorMapIndicador($micodigo) {
        $sql=" SELECT i.`codIniciativa` , i.`nome` , i.`finalidade`
				FROM  `iniciativa` i
				INNER JOIN  `indic_iniciativa` ii ON i.`codIniciativa` = ii.`codIniciativa` 
				INNER JOIN  `mapaindicador` mi ON mi.`codigo` = ii.`codMapaInd` 
				WHERE mi.`codigo` =:micodigo";
		try {
            $stmt = parent::prepare($sql);
    		$stmt->execute(array(':micodigo' => $micodigo));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro iniciativaPorMapIndicador: " . $ex->getMessage();
		}
	}*/
	
	
	public function iniciativaPorIniciativaEMapIndicador($codIniciativa, $cod) {
		
		$sql=" SELECT * FROM  `indic_iniciativa` WHERE `codMapaIndi` =:codmi AND `codIniciativa` = :codini";
		try {
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':codmi' => $indicinciativa->getMapaindicador()->getCodigo()));
			$stmt->execute(array(':codini' => $indicinciativa->getIniciativa()->getCodIniciativa()));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro iniciativaPorMapIndicador: " . $ex->getMessage();
		}
	}
	
	public function Ind_iniciativaPorMapIndicador($codMi) {
	
		$sql=" SELECT * FROM  `indic_iniciativa` WHERE `codMapaInd` =:codmi";
		try {
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':codmi' => $codMi));			
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro iniciativaPorMapIndicador: " . $ex->getMessage();
		}
	}
	
public function Ind_iniciativaPorMapIndicador1($codMi,$ano) {
	
		$sql=" SELECT * FROM  `indic_iniciativa` WHERE `codMapaInd` =:codmi and (anofinal is null or anofinal>:ano) and anoinicial<=:ano ";
		try {
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':codmi' => $codMi,':ano'=>$ano));			
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro iniciativaPorMapIndicador: " . $ex->getMessage();
		}
	}
	
	public function listaPorIniciativa($codIniciativa,$anobase){
		$sql=" SELECT * FROM `indic_iniciativa` 
WHERE `codIniciativa` = :codini and (anofinal is null or anofinal>=:ano) and anoinicial<=:ano ";
		try {
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':codini' => $codIniciativa,':ano'=>$anobase));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro indicIniciativa: " . $ex->getMessage();
		}
	}
	
	public function deletaIndicIniciativas($deletados, $codIniciativa){
		
		try {
			parent::beginTransaction();
			
			foreach ($deletados as $mapaind){
				
				$sql="DELETE FROM indic_iniciativa WHERE codIniciativa = :codini AND codMapaInd = :mapind ";
				$stmt = parent::prepare($sql);
				$stmt->execute(array(':codini' => $codIniciativa, ':mapind' => $mapaind));
			}
			
			parent::commit();
		} catch (PDOException $ex) {
			echo "Erro iniciativaPorMapIndicador: " . $ex->getMessage();
		}
		
	}
	
	public function deletaIndicIniciativaIniciativaNapaIndi($codIniciativa, $codMapaInd){
		
			$sql="DELETE FROM indic_iniciativa WHERE codMapaInd = :codmi AND codIniciativa = :codini  ";
		try {
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':codmi' => $codMapaInd, ':codini' => $codIniciativa));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro iniciativaPorMapIndicador: " . $ex->getMessage();
		}
		
	}
	
	public function regidtraIndicIniciativas($mapaIndicadores, $codIniciativa,$ano){
		
		try {
			parent::beginTransaction();
			foreach ($mapaIndicadores as $mapaIndicador) {
				$sql="INSERT INTO indic_iniciativa (codMapaInd, codIniciativa,anoinicial,periodoinicial) VALUES(?,?,?,?) ";
				$stmt = parent::prepare($sql);
				$stmt->bindValue(1, $mapaIndicador);
				$stmt->bindValue(2, $codIniciativa);
				$stmt->bindValue(3, $ano);
				$stmt->bindValue(4, 1);
								
				$stmt->execute();
			}
			parent::commit();
		} catch (PDOException $ex) {
			echo "Erro iniciativaPorMapIndicador: " . $ex->getMessage();
		}
		
	}
	
	//Vincular indicador-iniciativa ao novo indicador
	public function insertEditeIndi(IndicIniciativa $IndIni){
	
		try {
			parent::beginTransaction();
			$sql="INSERT INTO indic_iniciativa (codMapaInd, codIniciativa,anoinicial,periodoinicial,anofinal,periodofinal) VALUES(?,?,?,?,?,?) ";
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1, $IndIni->getMapaindicador()->getCodigo());
			$stmt->bindValue(2, $IndIni->getIniciativa()->getCodIniciativa());
			$stmt->bindValue(3, $IndIni->getAnoinicial());
			$stmt->bindValue(4, $IndIni->getPeriodoinicial());
			$stmt->bindValue(5, $IndIni->getAnofinal());
			$stmt->bindValue(6, $IndIni->getPeriodofinal());
				
			$stmt->execute();			
			parent::commit();
		} catch (PDOException $ex) {
			echo "Erro iniciativaPorMapIndicador: " . $ex->getMessage();
		}
	
	}
	
	
	public function vincularIndicIniciativas($indiciniciativas, $codIniciativa,$anobase){		
		try {
			parent::beginTransaction();
			foreach ($indiciniciativas as $indiciniciativa) {	
				//deleta se o anoinicial=anobase			
		$sql="delete FROM indic_iniciativa WHERE codMapaInd=? AND codIniciativa=? AND anofinal is NULL AND anoinicial=?";//COLOCAR ANO FINAL NO WHERE
		        $stmt = parent::prepare($sql);
		        
				
				$stmt->bindValue(1, $indiciniciativa);//mapaindicador
				$stmt->bindValue(2, $codIniciativa);
			    $stmt->bindValue(3, $anobase);
				
				$stmt->execute();
				
				$alterou1=$stmt->rowCount();
			 if ($alterou1==0){
				
				$sql="UPDATE  indic_iniciativa SET anofinal=?,periodofinal=? WHERE codMapaInd=? AND codIniciativa=? AND anofinal is NULL";//COLOCAR ANO FINAL NO WHERE
		        $stmt = parent::prepare($sql);
		        
				$stmt->bindValue(1, $anobase);
				$stmt->bindValue(2, 2);
				$stmt->bindValue(3, $indiciniciativa);//mapaindicador
				$stmt->bindValue(4, $codIniciativa);
				
				$stmt->execute();
				
				$alterou1=$stmt->rowCount();
				
				// print("upd $alterou1 rows.\n");
			}			
				/* if ($alterou1==0){
					$sql="UPDATE  indic_iniciativa SET anofinal=?,periodofinal=? WHERE codMapaInd=? AND codIniciativa=? 
					AND (anofinal=?)";//COLOCAR ANO FINAL NO WHERE
			        $stmt = parent::prepare($sql);
					
					$stmt->bindValue(3, $indiciniciativa);
					$stmt->bindValue(4, $codIniciativa);
					$stmt->bindValue(5, $anobase);
					$stmt->execute();
				}
				*/
								// print("upd stmt->rowCount() rows.\n");
				
				
				if ($alterou1==0 && $stmt->rowCount()==0){
					$sql="INSERT INTO indic_iniciativa (codMapaInd, codIniciativa,anoinicial,periodoinicial) VALUES (?,?,?,?) ";
					$stmt = parent::prepare($sql);
					$stmt->bindValue(1, $indiciniciativa);
					$stmt->bindValue(2, $codIniciativa);
					$stmt->bindValue(3, $anobase);
				    $stmt->bindValue(4, 1);
					
					$stmt->execute();
													// print("upd stmt->rowCount() rows.\n");
					
				}
			}
			parent::commit();
		} catch (PDOException $ex) {
			echo "Erro vincularIndicIniciativas: " . $ex->getMessage();
		}
	}
		
	public function delvincIndicIniciativas($codmapaind,$anobase){		
		try {
			parent::beginTransaction();
				$sql="UPDATE  indic_iniciativa SET anofinal=?,periodofinal=? WHERE codMapaInd=? 
				AND (anofinal is NULL)";//COLOCAR ANO FINAL NO WHERE
		        $stmt = parent::prepare($sql);
		        
				$stmt->bindValue(1, $anobase);
				
				$stmt->bindValue(2, 1);
				$stmt->bindValue(3, $codmapaind);//mapaindicador
				
				$stmt->execute();
				
				
													// print("upd stmt->rowCount() rows.\n");
					
			
			
			parent::commit();
		} catch (PDOException $ex) {
			echo "Erro delvincIndicIniciativas: " . $ex->getMessage();
		}
	}
	
         public function iniciativaPorMapIndicadorIni($micodigo,$codini,$anobase) {
        $sql=" SELECT ii.codigo as codindinic,i.`codIniciativa` , i.`nome` , i.`finalidade`
FROM  `iniciativa` i
INNER JOIN  `indic_iniciativa` ii ON i.`codIniciativa` = ii.`codIniciativa` 
INNER JOIN  `mapaindicador` mi ON mi.`codigo` = ii.`codMapaInd` 
WHERE mi.`codigo` =:micodigo and i.codiniciativa=:icodigo  and 
(mi.anofinal is NULL or mi.anofinal>:ano) and ( mi.anoinicial<=:ano) 
and (ii.anofinal is NULL or ii.anofinal>:ano) and (ii.anoinicial is NULL or ii.anoinicial<=:ano) 
";
		try {
            $stmt = parent::prepare($sql);
    		$stmt->execute(array(':micodigo' => $micodigo,':icodigo' => $codini,':ano'=>$anobase));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro iniciativaPorMapIndicadorIni: " . $ex->getMessage();
		}
	}
	
}

?>

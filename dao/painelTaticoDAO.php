<?php


class PainelTaticoDAO extends PDOConnectionFactory{
	
	public $conex = null;
	
	public  function  exportarPainel($ano_base,$cod_unidade){
		try {
			$sql = "(SELECT d.`nome` as nomedocumento,d.`anoinicial`,d.`anofinal`, d.CodUnidade, `Objetivo`,i.nome as nomeindicador,i.interpretacao ,i.calculo,
			c.nome as nomeiniciativa,`meta`,ma.ano as ano,case when `metrica`='Q' then 'Absoluto' else 'Percentual' end as metrica,
			case when r.`periodo`=1 then 'Parcial' else 'Final' end periodo1 
			FROM `documento` d inner 
			join `mapa` m on d.`codigo`=m.`CodDocumento`  and (m.anoinicio is NULL or m.anoinicio<=:ano_base) and (m.anofim is NULL or m.anofim>:ano_base)  
			inner join `objetivo` o on `codObjetivoPDI`=o.`Codigo` 
			left join mapaindicador mi on mi.`codMapa`=m.`Codigo`  and (mi.anoinicial<=:ano_base) and (mi.anofinal is NULL or mi.anofinal>:ano_base)  
			left join indicador i on i.`Codigo`=`codIndicador` 
			left join meta ma on ma.`CodMapaInd`=mi.codigo and (ma.anoinicial<=:ano_base) and (ma.anofinal is NULL or ma.anofinal>:ano_base)  
			left join resultados_pdi r	on r.`CodMeta`=ma.Codigo 
			left join indic_iniciativa ii on ii.`codMapaInd`=mi.`codigo` and ii.anoinicial<=:ano_base  and (ii.anofinal is NULL or ii.anofinal>:ano_base)  
			left join 

			(select  i1.`codIniciativa`, i1.`nome`, i1.`codUnidade`, i1.`finalidade`, 
			  i1.`anoInicio`, i1.`anoFinal` FROM `iniciativa` i1
			  where i1.anoinicio<=:ano_base and (i1.anofinal>=:ano_base or i1.anofinal is null ) )
			c on c.`codIniciativa`=ii.`codIniciativa` 
			left join resultIniciativa ri on ri.`codIniciativa`=c.`codIniciativa`
			where (d.CodUnidade=:cod_unidade AND (d.anoinicial <= :ano_base AND d.anofinal >= :ano_base))
            order by d.`nome`,ma.ano )
			union
			(SELECT d.`nome` as nomedocumento,d.`anoinicial`,d.`anofinal`, d.CodUnidade, `Objetivo`,i.nome as nomeindicador,i.interpretacao ,i.calculo,
			c.nome as nomeiniciativa,`meta`,ma.ano as ano,case when `metrica`='Q' then 'Absoluto' else 'Percentual' end as metrica,
			case when r.`periodo`=1 then 'Parcial' else 'Final' end periodo1 
			FROM `documento` d 
			 join `mapa` m on d.`codigo`=m.`CodDocumento`  and (m.anoinicio<=:ano_base) and (m.anofim is NULL or m.anofim>:ano_base) 
			 join `objetivo` o on `codObjetivoPDI`=o.`Codigo` 
			 join mapaindicador mi on mi.`codMapa`=m.`Codigo` and mi.propindicador=:cod_unidade and  
			 (mi.anoinicial is NULL or mi.anoinicial<=:ano_base) and (mi.anofinal is NULL or mi.anofinal>:ano_base) 
			 and  (mi.tipoAssociado is null or mi.tipoAssociado='PDU')  

			 join indicador i on i.`Codigo`=`codIndicador` 
			 join meta ma on ma.`CodMapaInd`=mi.codigo and ma.ano>2016 and (ma.anoinicial<=:ano_base) and (ma.anofinal is NULL or ma.anofinal>:ano_base)
			left join resultados_pdi r	on r.`CodMeta`=ma.Codigo 
			left join indic_iniciativa ii on ii.`codMapaInd`=mi.`codigo` and ii.anoinicial<=:ano_base  and (ii.anofinal is NULL or ii.anofinal>:ano_base)   
			left join 

			(select  i1.`codIniciativa`, i1.`nome`, i1.`codUnidade`, i1.`finalidade`, 
			  i1.`anoInicio`, i1.`anoFinal` FROM `iniciativa` i1
			  where i1.anoinicio<=:ano_base and (i1.anofinal>=:ano_base or i1.anofinal is null ) )
			
			
			c on c.`codIniciativa`=ii.`codIniciativa` 
			left join resultIniciativa ri on ri.`codIniciativa`=c.`codIniciativa`
			where		
			d.CodUnidade=938 AND (d.anoinicial <= :ano_base 
AND d.anofinal>= :ano_base))
            order by nomedocumento,Objetivo,nomeindicador,ano ";
			
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano_base'=>$ano_base,':cod_unidade'=>$cod_unidade));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro:exportarPainel " . $ex->getMessage();
		}
	}
	
	public  function  exportarPainelPDI($ano_base,$cod_unidade){
		try {
			$sql = "select DISTINCT doc1.nome as nomedocumento,doc1.`anoinicial`,doc1.`anofinal`, doc1.CodUnidade, ob1.`Objetivo`,
			i.nome as nomeindicador, i.interpretacao, i.calculo,c.nome as nomeiniciativa,case when ma.`meta` is NULL THEN 0.0 ELSE ma.meta end as meta,ma.ano as ano,
			case when `metrica`='Q' then 'Absoluto' else 'Percentual' end as metrica,
			case when r.`periodo`=1 then 'Parcial' else 'Final' end periodo1, ob1.Objetivo as nomeobjetivo, pers1.nome as nomeperspectiva 
			FROM mapa m1 
			JOIN documento doc1 ON(m1.CodDocumento = doc1.codigo) and ( m1.anoinicio<=:ano_base) and (m1.anofim is NULL or m1.anofim>:ano_base) 
			JOIN objetivo ob1 ON(m1.codObjetivoPDI = ob1.Codigo) 
			JOIN perspectiva pers1 ON(m1.codPerspectiva = pers1.codPerspectiva) 
			inner join mapaindicador mi on mi.codMapa=m1.`Codigo`  and ( mi.anoinicial<=:ano_base) and (mi.anofinal is NULL or mi.anofinal>:ano_base)  
			left join indicador i on i.`Codigo`=`codIndicador` 
			left join meta ma on ma.`CodMapaInd`=mi.codigo and  ( ma.anoinicial<=:ano_base) and (ma.anofinal is NULL or ma.anofinal>:ano_base)
			left join resultados_pdi r on r.`CodMeta`=ma.Codigo 
			left join indic_iniciativa ii on ii.`codMapaInd`=mi.`codigo` and ii.anoinicial<=:ano_base  and (ii.anofinal is NULL or ii.anofinal>:ano_base)  
			left join 

			(select  i1.`codIniciativa`, i1.`nome`, i1.`codUnidade`, i1.`finalidade`, 
			  i1.`anoInicio`, i1.`anoFinal` FROM `iniciativa` i1
			  where i1.anoinicio<=:ano_base and (i1.anofinal>=:ano_base or i1.anofinal is null ) ) as
			
			c on c.`codIniciativa`=ii.`codIniciativa` 
			left join resultIniciativa ri on ri.`codIniciativa`=c.`codIniciativa`
			WHERE mi.`PropIndicador` = :cod_unidade and doc1.anoinicial<=:ano_base AND doc1.anofinal >= :ano_base and doc1.CodUnidade=938
			and mi.tipoAssociado=7 order by 1,6,10";
			//and (tipoAssociado is NULL or (tipoAssociado like 'PDU' and mi.`PropIndicador` = :cod_unidade) )";
				
			
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano_base'=>$ano_base,':cod_unidade'=>$cod_unidade));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro:exportarPainelPDI " . $ex->getMessage();
		}
	}
	
public  function  exportarPainelPDI938($ano_base){
		try {
			$sql = "select DISTINCT doc1.nome as nomedocumento,doc1.`anoinicial`,doc1.`anofinal`, doc1.CodUnidade, ob1.`Objetivo`,
			i.nome as nomeindicador, i.interpretacao, i.calculo,c.nome as nomeiniciativa,`meta`,ma.ano as ano,
			case when `metrica`='Q' then 'Absoluto' else 'Percentual' end as metrica,
			case when r.`periodo`=1 then 'Parcial' else 'Final' end periodo1, ob1.Objetivo as nomeobjetivo, pers1.nome as nomeperspectiva 
			FROM mapa m1 JOIN documento doc1 ON(m1.CodDocumento = doc1.codigo) and (m1.anoinicio is NULL or m1.anoinicio<=:ano_base) and (m1.anofim is NULL or m1.anofim>:ano_base) 
			JOIN objetivo ob1 ON(m1.codObjetivoPDI = ob1.Codigo) 
			JOIN perspectiva pers1 ON(m1.codPerspectiva = pers1.codPerspectiva) 
			inner join mapaindicador mi on mi.codMapa=m1.`Codigo`  and ( mi.anoinicial<=:ano_base) and (mi.anofinal is NULL or mi.anofinal>:ano_base)   
			left join indicador i on i.`Codigo`=`codIndicador` 
			left join meta ma on ma.`CodMapaInd`=mi.codigo and ( ma.anoinicial<=:ano_base) and (ma.anofinal is NULL or ma.anofinal>:ano_base)  
			left join resultados_pdi r on r.`CodMeta`=ma.Codigo 
			left join indic_iniciativa ii on ii.`codMapaInd`=mi.`codigo` and ii.anoinicial<=:ano_base  and (ii.anofinal is NULL or ii.anofinal>:ano_base)  
			left join 

			(select  i1.`codIniciativa`, i1.`nome`, i1.`codUnidade`, i1.`finalidade`, 
			  i1.`anoInicio`, i1.`anoFinal` FROM `iniciativa` i1
			  where i1.anoinicio<=:ano_base and (i1.anofinal>=:ano_base or i1.anofinal is null ) ) as
			
			c on c.`codIniciativa`=ii.`codIniciativa` 
			left join resultIniciativa ri on ri.`codIniciativa`=c.`codIniciativa`
			WHERE  doc1.anoinicial<=:ano_base AND doc1.anofinal >= :ano_base and doc1.CodUnidade=938
				and mi.tipoAssociado=7 order by 1,11";
						//and (tipoAssociado is NULL or (tipoAssociado like 'PDU' and mi.`PropIndicador` = :cod_unidade) )";
			
			
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano_base'=>$ano_base));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	
	
	
	//Exporta os resultados dos indicadores não PDI
	public  function  exportarResultadoPainel($ano_base,$cod_unidade){
		try {
		/*	$sql = "SELECT d.`nome`,d.`anoinicial`,d.`anofinal`, d.CodUnidade, `Objetivo`,i.nome as nomeindicador,i.interpretacao ,
			i.calculo,c.nome as nomeiniciativa,`meta`,ma.ano,case when `metrica`='Q' then 'Absoluto' else 'Percentual' end as metrica,
			case when r.`periodo`=1 then 'Parcial' else 'Final' end periodo1,`meta_atingida`,`analiseCritica`,
			CASE WHEN ri.situacao=1 THEN 'Não iniciado' WHEN ri.situacao=2 THEN 'Em atraso' WHEN ri.situacao=3 THEN 'Com atrasos críticos'
			 WHEN ri.situacao=4 THEN 'Em andamento normal' WHEN ri.situacao=5 THEN 'Concluído' END as situacao, `pfcapacit`,`pfrecti`,
			 `pfinfraf`,`pfrecf`,`pfplanj`,`outros`,round((meta_atingida*100)/meta,2) AS desempenho 
			 FROM `documento` d inner 
			 join `mapa` m on d.`codigo`=m.`CodDocumento` 
inner join `objetivo` o on `codObjetivoPDI`=o.`Codigo` 
left join mapaindicador mi on mi.`codMapa`=m.`Codigo` left join indicador i on i.`Codigo`=`codIndicador` left join meta ma on ma.`CodMapaInd`=mi.codigo INNER JOIN calendario c1 ON c1.codCalendario = ma.codCalendario  left join resultados_pdi r on r.`CodMeta`=ma.Codigo left join indic_iniciativa ii on ii.`codMapaInd`=mi.`codigo` 
left join (select  i1.`codIniciativa`, i1.`nome`, i1.`codUnidade`, i1.`finalidade`, 
			  i1.`anoInicio`, i1.`anoFinal` FROM `iniciativa` i1
where i1.anoinicio<=:ano_base and (i1.anofinal>=:ano_base or i1.anofinal is null ) ) c on c.`codIniciativa`=ii.`codIniciativa` 
left join resultIniciativa ri on ri.`codIniciativa`=c.`codIniciativa`
 where d.CodUnidade=:cod_unidade AND (d.anoinicial <= :ano_base AND d.anofinal >= :ano_base) AND c1.anoGestao = :ano_base
and  (mi.tipoAssociado is null or mi.tipoAssociado='PDU')
union
SELECT d.`nome`,d.`anoinicial`,d.`anofinal`, d.CodUnidade, `Objetivo`,i.nome as nomeindicador,i.interpretacao ,
			i.calculo,c.nome as nomeiniciativa,`meta`,ma.ano,case when `metrica`='Q' then 'Absoluto' else 'Percentual' end as metrica,
			case when r.`periodo`=1 then 'Parcial' else 'Final' end periodo1,`meta_atingida`,`analiseCritica`,
			CASE WHEN ri.situacao=1 THEN 'Não iniciado' WHEN ri.situacao=2 THEN 'Em atraso' WHEN ri.situacao=3 THEN 'Com atrasos críticos'
			 WHEN ri.situacao=4 THEN 'Em andamento normal' WHEN ri.situacao=5 THEN 'Concluído' END as situacao, `pfcapacit`,`pfrecti`,
			 `pfinfraf`,`pfrecf`,`pfplanj`,`outros`,round((meta_atingida*100)/meta,2) AS desempenho 
FROM `documento` d  
join `mapa` m on d.`codigo`=m.`CodDocumento` 
inner join `objetivo` o on `codObjetivoPDI`=o.`Codigo` 
left join mapaindicador mi on mi.`codMapa`=m.`Codigo` and mi.propindicador=:cod_unidade
left join indicador i on i.`Codigo`=`codIndicador`
 left join meta ma on ma.`CodMapaInd`=mi.codigo 
JOIN calendario c1 ON c1.codCalendario = ma.codCalendario  
left join resultados_pdi r on r.`CodMeta`=ma.Codigo 
left join indic_iniciativa ii on ii.`codMapaInd`=mi.`codigo` 
left join (select  i1.`codIniciativa`, i1.`nome`, i1.`codUnidade`, i1.`finalidade`, 
	i1.`anoInicio`, i1.`anoFinal` FROM `iniciativa` i1
	where i1.anoinicio<=:ano_base and (i1.anofinal>=:ano_base or i1.anofinal is null ) ) c on c.`codIniciativa`=ii.`codIniciativa` 
left join resultIniciativa ri on ri.`codIniciativa`=c.`codIniciativa` 
where d.CodUnidade=938 AND (d.anoinicial <= :ano_base 
AND d.anofinal>= :ano_base) AND c1.anoGestao = :ano_base
and  (mi.tipoAssociado is null or mi.tipoAssociado='PDU')";*/
			
					
			
	$sql="		SELECT d.`nome`,d.`anoinicial`,d.`anofinal`, d.CodUnidade, 
               `Objetivo`,i.nome as nomeindicador,
                i.interpretacao ,i.calculo,c.nome as nomeiniciativa,`meta`,ma.ano ";


        	if ($ano_base<2022){
                
                //case when `metrica`='Q' then 'Absoluto' else 'Percentual' end as metrica,
                
                $sql.=" , CASE WHEN ma.metrica = 'P' THEN 'Percentual' WHEN ma.metrica = 'Q' THEN 'Absoluto' END AS metrica ";
            } else {
 
                  $sql.="  ,


                               CASE WHEN i.unidademedida = 'P' THEN 'Percentual' 
                               WHEN i.unidademedida = 'Q' THEN 'Absoluto' 
                               WHEN i.unidademedida = 'R' THEN 'Real' 
                               WHEN i.unidademedida = 'M' THEN 'Metro Quadrado' 
                               END   AS metrica 
  ";
                }



			$sql.=" , case when r.`periodo`=1 then 'Parcial' else 'Final' end as periodo1,`meta_atingida`,`analiseCritica`,
			CASE WHEN ri.situacao=1 THEN 'Não iniciado' WHEN ri.situacao=2 THEN 'Em atraso' WHEN ri.situacao=3 THEN 'Com atrasos críticos'
			 WHEN ri.situacao=4 THEN 'Em andamento normal' WHEN ri.situacao=5 THEN 'Concluído' END as situacao, `pfcapacit`,`pfrecti`,
			 `pfinfraf`,`pfrecf`,`pfplanj`,`outros`,
			 case when i.interpretacao=1 then round((meta_atingida*100)/meta,2) else round((meta*100)/meta_atingida,2) end  AS desempenho 
			 FROM `documento` d inner 
			 join `mapa` m on d.`codigo`=m.`CodDocumento` and (m.anoinicio is NULL or m.anoinicio<=:ano_base) and (m.anofim is NULL or m.anofim>:ano_base) 
inner join `objetivo` o on `codObjetivoPDI`=o.`Codigo` 
left join mapaindicador mi on mi.`codMapa`=m.`Codigo` and (mi.anoinicial<=:ano_base) and (mi.anofinal is NULL or mi.anofinal>:ano_base) left join indicador i on i.`Codigo`=`codIndicador`     
left join meta ma on ma.`CodMapaInd`=mi.codigo and ma.ano=:ano_base and  (ma.anoinicial<=:ano_base) and (ma.anofinal is NULL or ma.anofinal>:ano_base)   
INNER JOIN calendario c1 ON c1.codCalendario = ma.codCalendario 
 left join resultados_pdi r on r.`CodMeta`=ma.Codigo left join indic_iniciativa ii on ii.`codMapaInd`=mi.`codigo` 
left join (select  i1.`codIniciativa`, i1.`nome`, i1.`codUnidade`, i1.`finalidade`, 
			  i1.`anoInicio`, i1.`anoFinal` FROM `iniciativa` i1
where i1.anoinicio<=:ano_base and (i1.anofinal>=:ano_base or i1.anofinal is null ) ) c on c.`codIniciativa`=ii.`codIniciativa` 
left join resultIniciativa ri on ri.`codIniciativa`=c.`codIniciativa` AND ri.codCalendario=c1.codCalendario
 where d.CodUnidade=:cod_unidade AND (d.anoinicial <= :ano_base AND d.anofinal >= :ano_base) AND c1.anoGestao = :ano_base
and  (mi.tipoAssociado is null or mi.tipoAssociado='PDU')





union
SELECT d.`nome`,d.`anoinicial`,d.`anofinal`, d.CodUnidade, `Objetivo`,i.nome as nomeindicador,i.interpretacao ,
			i.calculo,c.nome as nomeiniciativa,`meta`,ma.ano ";
			
			
			
			if ($ano_base<2022){
			    
			    
			    //case when `metrica`='Q' then 'Absoluto' else 'Percentual' end as metrica,
			    
			    $sql.=" , CASE WHEN ma.metrica = 'P' THEN 'Percentual' WHEN ma.metrica = 'Q' THEN 'Absoluto' END AS metrica ";
			} else {
			    
			    $sql.="  ,  CASE WHEN i.unidademedida = 'P' THEN 'Percentual' 
                               WHEN i.unidademedida = 'Q' THEN 'Absoluto' 
                               WHEN i.unidademedida = 'R' THEN 'Real' 
                               WHEN i.unidademedida = 'M' THEN 'Metro Quadrado' 
                               END   AS metrica ";
			}
			
			
			
			
			$sql.=" ,case when r.`periodo`=1 then 'Parcial' else 'Final' end as periodo1,`meta_atingida`,`analiseCritica`,
			CASE WHEN ri.situacao=1 THEN 'Não iniciado' WHEN ri.situacao=2 THEN 'Em atraso' WHEN ri.situacao=3 THEN 'Com atrasos críticos'
			 WHEN ri.situacao=4 THEN 'Em andamento normal' WHEN ri.situacao=5 THEN 'Concluído' END as situacao, `pfcapacit`,`pfrecti`,
			 `pfinfraf`,`pfrecf`,`pfplanj`,`outros`,			 
			 case when i.interpretacao=1 then round((meta_atingida*100)/meta,2) else round((meta*100)/meta_atingida,2) end  AS desempenho 
			 
FROM `documento` d  
join `mapa` m on d.`codigo`=m.`CodDocumento` and (m.anoinicio is NULL or m.anoinicio<=:ano_base) and (m.anofim is NULL or m.anofim>:ano_base)  
inner join `objetivo` o on `codObjetivoPDI`=o.`Codigo` 
left join mapaindicador mi on mi.`codMapa`=m.`Codigo` and mi.propindicador=:cod_unidade and (mi.anoinicial is NULL or mi.anoinicial<=:ano_base) and (mi.anofinal is NULL or mi.anofinal>:ano_base)   
left join indicador i on i.`Codigo`=`codIndicador` 
left join meta ma on ma.`CodMapaInd`=mi.codigo  and ma.ano=:ano_base and  (ma.anoinicial<=:ano_base) and (ma.anofinal is NULL or ma.anofinal>:ano_base) 
JOIN calendario c1 ON c1.codCalendario = ma.codCalendario  
left join resultados_pdi r on r.`CodMeta`=ma.Codigo 
left join indic_iniciativa ii on ii.`codMapaInd`=mi.`codigo` and ii.anoinicial<=:ano_base  and (ii.anofinal is NULL or ii.anofinal>:ano_base)   
left join (select  i1.`codIniciativa`, i1.`nome`, i1.`codUnidade`, i1.`finalidade`, 
	i1.`anoInicio`, i1.`anoFinal` FROM `iniciativa` i1
	where i1.anoinicio<=:ano_base and (i1.anofinal>=:ano_base or i1.anofinal is null ) ) c on c.`codIniciativa`=ii.`codIniciativa` 
left join resultIniciativa ri on ri.`codIniciativa`=c.`codIniciativa` AND ri.codCalendario=c1.codCalendario 
where d.CodUnidade=938 AND (d.anoinicial <= :ano_base 
AND d.anofinal>= :ano_base) AND c1.anoGestao = :ano_base
and  (mi.tipoAssociado is null or mi.tipoAssociado='PDU')";
			
			
			
			
			
			
	
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano_base'=>$ano_base,':cod_unidade'=>$cod_unidade));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro:exportarResultadoPainel " . $ex->getMessage();
		}
	}
	
	//Exporta os resultados dos indicadores não PDI
	public  function  exportarResultadoPainelTodasUnidades($ano_base){
		try {
			
			$sql="		SELECT d.`nome`,d.`anoinicial`,d.`anofinal`, d.CodUnidade, uni.NomeUnidade,`Objetivo`,i.nome as nomeindicador,i.interpretacao ,
			i.calculo,c.nome as nomeiniciativa,`meta`,ma.ano";


//case when `metrica`='Q' then 'Absoluto' else 'Percentual' end as metrica,
                if ($ano_base<2022){
                
                //case when `metrica`='Q' then 'Absoluto' else 'Percentual' end as metrica,
                
                $sql.=" , CASE WHEN ma.metrica = 'P' THEN 'Percentual' WHEN ma.metrica = 'Q' THEN 'Absoluto' END AS metrica ";
            			} else {
            			    
            			    $sql.="  , CASE WHEN unidademedida = 'P' THEN 'Percentual'
                                           WHEN unidademedida = 'Q' THEN 'Absoluto'
                                           WHEN unidademedida = 'R' THEN 'Real'
                                           WHEN unidademedida = 'M' THEN 'Metro Quadrado'
                                           END AS metrica";
            			}







			$sql.=" , case when r.`periodo`=1 then 'Parcial' else 'Final' end periodo1,`meta_atingida`,`analiseCritica`,
			CASE WHEN ri.situacao=1 THEN 'Não iniciado' WHEN ri.situacao=2 THEN 'Em atraso' WHEN ri.situacao=3 THEN 'Com atrasos críticos'
			 WHEN ri.situacao=4 THEN 'Em andamento normal' WHEN ri.situacao=5 THEN 'Concluído' END as situacao, `pfcapacit`,`pfrecti`,
			 `pfinfraf`,`pfrecf`,`pfplanj`,`outros`,
			 case when i.interpretacao=1 then round((meta_atingida*100)/meta,2) else round((meta*100)/meta_atingida,2) end  AS desempenho 
			 FROM `documento` d inner 
			 join `mapa` m on d.`codigo`=m.`CodDocumento` and (m.anoinicio is NULL or m.anoinicio<=:ano_base) and (m.anofim is NULL or m.anofim>:ano_base) 
inner join `objetivo` o on `codObjetivoPDI`=o.`Codigo` 
left join mapaindicador mi on mi.`codMapa`=m.`Codigo` and (mi.anoinicial<=:ano_base) and (mi.anofinal is NULL or mi.anofinal>:ano_base) left join indicador i on i.`Codigo`=`codIndicador`     
left join meta ma on ma.`CodMapaInd`=mi.codigo and ma.ano=:ano_base and  (ma.anoinicial<=:ano_base) and (ma.anofinal is NULL or ma.anofinal>:ano_base)   
INNER JOIN calendario c1 ON c1.codCalendario = ma.codCalendario 
 left join resultados_pdi r on r.`CodMeta`=ma.Codigo left join indic_iniciativa ii on ii.`codMapaInd`=mi.`codigo` 
 left join unidade uni on uni.CodUnidade=d.CodUnidade
left join (select  i1.`codIniciativa`, i1.`nome`, i1.`codUnidade`, i1.`finalidade`, 
			  i1.`anoInicio`, i1.`anoFinal` FROM `iniciativa` i1
where i1.anoinicio<=:ano_base and (i1.anofinal>=:ano_base or i1.anofinal is null ) ) c on c.`codIniciativa`=ii.`codIniciativa` 
left join resultIniciativa ri on ri.`codIniciativa`=c.`codIniciativa` AND ri.codCalendario=c1.codCalendario
 where (d.anoinicial <= :ano_base AND d.anofinal >= :ano_base) AND c1.anoGestao = :ano_base
and  (mi.tipoAssociado is null or mi.tipoAssociado='PDU') and d.CodUnidade <> 938
union
SELECT d.`nome`,d.`anoinicial`,d.`anofinal`, mi.propindicador, uni.NomeUnidade,`Objetivo`,i.nome as nomeindicador,i.interpretacao ,
			i.calculo,c.nome as nomeiniciativa,`meta`,ma.ano";


//case when `metrica`='Q' then 'Absoluto' else 'Percentual' end as metrica,

if ($ano_base<2022){
                
                //case when `metrica`='Q' then 'Absoluto' else 'Percentual' end as metrica,
                
                $sql.=" , CASE WHEN ma.metrica = 'P' THEN 'Percentual' WHEN ma.metrica = 'Q' THEN 'Absoluto' END AS metrica ";
            			} else {
            			    
            			    $sql.="  , CASE WHEN unidademedida = 'P' THEN 'Percentual'
                                           WHEN unidademedida = 'Q' THEN 'Absoluto'
                                           WHEN unidademedida = 'R' THEN 'Real'
                                           WHEN unidademedida = 'M' THEN 'Metro Quadrado'
                                           END AS metrica";
            			}





		$sql.=",	case when r.`periodo`=1 then 'Parcial' else 'Final' end periodo1,`meta_atingida`,`analiseCritica`,
			CASE WHEN ri.situacao=1 THEN 'Não iniciado' WHEN ri.situacao=2 THEN 'Em atraso' WHEN ri.situacao=3 THEN 'Com atrasos críticos'
			 WHEN ri.situacao=4 THEN 'Em andamento normal' WHEN ri.situacao=5 THEN 'Concluído' END as situacao, `pfcapacit`,`pfrecti`,
			 `pfinfraf`,`pfrecf`,`pfplanj`,`outros`,			 
			 case when i.interpretacao=1 then round((meta_atingida*100)/meta,2) else round((meta*100)/meta_atingida,2) end  AS desempenho 
			 
FROM `documento` d  
join `mapa` m on d.`codigo`=m.`CodDocumento` and (m.anoinicio is NULL or m.anoinicio<=:ano_base) and (m.anofim is NULL or m.anofim>:ano_base)  
inner join `objetivo` o on `codObjetivoPDI`=o.`Codigo` 
left join mapaindicador mi on mi.`codMapa`=m.`Codigo` and (mi.anoinicial is NULL or mi.anoinicial<=:ano_base) and (mi.anofinal is NULL or mi.anofinal>:ano_base)   
left join indicador i on i.`Codigo`=`codIndicador` 
left join meta ma on ma.`CodMapaInd`=mi.codigo  and ma.ano=:ano_base and  (ma.anoinicial<=:ano_base) and (ma.anofinal is NULL or ma.anofinal>:ano_base) 
JOIN calendario c1 ON c1.codCalendario = ma.codCalendario  
left join resultados_pdi r on r.`CodMeta`=ma.Codigo 
left join indic_iniciativa ii on ii.`codMapaInd`=mi.`codigo` and ii.anoinicial<=:ano_base  and (ii.anofinal is NULL or ii.anofinal>:ano_base)   
left join unidade uni on uni.CodUnidade=mi.propindicador
left join (select  i1.`codIniciativa`, i1.`nome`, i1.`codUnidade`, i1.`finalidade`, 
	i1.`anoInicio`, i1.`anoFinal` FROM `iniciativa` i1
	where i1.anoinicio<=:ano_base and (i1.anofinal>=:ano_base or i1.anofinal is null ) ) c on c.`codIniciativa`=ii.`codIniciativa` 
left join resultIniciativa ri on ri.`codIniciativa`=c.`codIniciativa` AND ri.codCalendario=c1.codCalendario 
where d.CodUnidade=938 AND (d.anoinicial <= :ano_base 
AND d.anofinal>= :ano_base) AND c1.anoGestao = :ano_base
and  (mi.tipoAssociado is null or mi.tipoAssociado='PDU') and mi.propindicador <> 938";			
	
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano_base'=>$ano_base));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro:exportarResultadoPainel " . $ex->getMessage();
		}
	}
	
	//Exporta os resultados dos indicadores do PDI
	public  function  exportarResultadoPainelPDI($ano_base,$cod_unidade){
		try {
			$sql = "select DISTINCT doc1.nome as nomedocumento,doc1.`anoinicial`,doc1.`anofinal`, doc1.CodUnidade, ob1.`Objetivo`,
			i.nome as nomeindicador,i.Codigo AS codIndicador,i.interpretacao ,i.calculo,c.nome as nomeiniciativa,
              `meta`,ma.ano as ano";
			
			
			if ($ano_base<2022){
			    
			    //case when `metrica`='Q' then 'Absoluto' else 'Percentual' end as metrica,
			    
			    $sql.=" , CASE WHEN ma.metrica = 'P' THEN 'Percentual' WHEN ma.metrica = 'Q' THEN 'Absoluto' END AS metrica ";
			} else {
			    
			    $sql.="  , CASE WHEN unidademedida = 'P' THEN 'Percentual'
                               WHEN unidademedida = 'Q' THEN 'Absoluto'
                               WHEN unidademedida = 'R' THEN 'Real'
                               WHEN unidademedida = 'M' THEN 'Metro Quadrado'
                               END AS metrica";
			}
			
			
			$sql.=" , case when r.`periodo`=1 then 'Parcial' else 'Final' end periodo1, ob1.Objetivo as nomeobjetivo, pers1.nome as nomeperspectiva 
			,r.meta_atingida,r.analiseCritica,
			CASE WHEN ri.situacao=1 THEN 'Não iniciado' 
			WHEN ri.situacao=2 THEN 'Em atraso' WHEN ri.situacao=3 THEN 'Com atrasos críticos' 
			WHEN ri.situacao=4 THEN 'Em andamento normal' 
			WHEN ri.situacao=5 THEN 'Concluído' END as situacao,
			 `pfcapacit`,`pfrecti`,`pfinfraf`,`pfrecf`,`pfplanj`,`outros` ,
			case when i.interpretacao=1 then round((meta_atingida*100)/meta,2) else round((meta*100)/meta_atingida,2) end  AS desempenho 
			 
			FROM mapa m1 JOIN documento doc1 ON(m1.CodDocumento = doc1.codigo)  and (m1.anoinicio is NULL or m1.anoinicio<=:ano_base) and (m1.anofim is NULL or m1.anofim>:ano_base)
			JOIN objetivo ob1 ON(m1.codObjetivoPDI = ob1.Codigo) 
			JOIN perspectiva pers1 ON(m1.codPerspectiva = pers1.codPerspectiva) 
			inner join mapaindicador mi on mi.codMapa=m1.`Codigo` and mi.propindicador=:cod_unidade and ( mi.anoinicial<=:ano_base) and (mi.anofinal is NULL or mi.anofinal>:ano_base)
			left join indicador i on i.`Codigo`=`codIndicador` 
			left join meta ma on ma.`CodMapaInd`=mi.codigo  and ma.ano=:ano_base and ma.ano=:ano_base and ( ma.anoinicial<=:ano_base) and (ma.anofinal is NULL or ma.anofinal>:ano_base)
			left join resultados_pdi r on r.`CodMeta`=ma.Codigo 
			left join indic_iniciativa ii on ii.`codMapaInd`=mi.`codigo` and ii.anoinicial<=:ano_base  and (ii.anofinal is NULL or ii.anofinal>:ano_base)
			JOIN calendario c1 ON c1.codCalendario = ma.codCalendario
			left join 

			(select  i1.`codIniciativa`, i1.`nome`, i1.`codUnidade`, i1.`finalidade`, 
			  i1.`anoInicio`, i1.`anoFinal` FROM `iniciativa` i1
			  where i1.anoinicio<=:ano_base and (i1.anofinal>=:ano_base or i1.anofinal is null ) ) as
			
			c on c.`codIniciativa`=ii.`codIniciativa` 
			left join resultIniciativa ri on ri.`codIniciativa`=c.`codIniciativa` AND ri.codCalendario=c1.codCalendario
			WHERE mi.`PropIndicador` = :cod_unidade and doc1.anoinicial<=:ano_base AND doc1.anofinal >= :ano_base and doc1.CodUnidade=938
			and mi.tipoAssociado=7
			AND ano =:ano_base 
			
			ORDER BY ob1.`Objetivo`";
				//and (tipoAssociado is NULL or (tipoAssociado like 'PDU' and mi.`PropIndicador` = :cod_unidade) ) 
			
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano_base'=>$ano_base,':cod_unidade'=>$cod_unidade));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: exportarResultadoPainelPDI" . $ex->getMessage();
		}
	}
	
	//Exporta os resultados dos indicadores do PDI
	public  function  exportarResultadoPainelPDITodasUnidades($ano_base){
		try {
			$sql = "select DISTINCT doc1.nome as nomedocumento,doc1.`anoinicial`,doc1.`anofinal`, doc1.CodUnidade,mi.propindicador,uni.NomeUnidade, ob1.`Objetivo`,
			i.nome as nomeindicador,i.Codigo AS codIndicador,i.interpretacao ,i.calculo,c.nome as nomeiniciativa,`meta`,ma.ano as ano";
			
			
			//,case when `metrica`='Q' then 'Absoluto' else 'Percentual' end as metrica,
			
			if ($ano_base<2022){
			    
			    //case when `metrica`='Q' then 'Absoluto' else 'Percentual' end as metrica,
			    
			    $sql.=" , CASE WHEN ma.metrica = 'P' THEN 'Percentual' WHEN ma.metrica = 'Q' THEN 'Absoluto' END AS metrica ";
			} else {
			    
			    $sql.="  , CASE WHEN unidademedida = 'P' THEN 'Percentual'
                               WHEN unidademedida = 'Q' THEN 'Absoluto'
                               WHEN unidademedida = 'R' THEN 'Real'
                               WHEN unidademedida = 'M' THEN 'Metro Quadrado'
                               END AS metrica";
			}
			
			
			
			
		$sql.=",	case when r.`periodo`=1 then 'Parcial' else 'Final' end periodo1, ob1.Objetivo as nomeobjetivo, pers1.nome as nomeperspectiva
			,r.meta_atingida,r.analiseCritica,
			CASE WHEN ri.situacao=1 THEN 'Não iniciado'
			WHEN ri.situacao=2 THEN 'Em atraso' WHEN ri.situacao=3 THEN 'Com atrasos críticos'
			WHEN ri.situacao=4 THEN 'Em andamento normal'
			WHEN ri.situacao=5 THEN 'Concluído' END as situacao,
			 `pfcapacit`,`pfrecti`,`pfinfraf`,`pfrecf`,`pfplanj`,`outros` ,
			case when i.interpretacao=1 then round((meta_atingida*100)/meta,2) else round((meta*100)/meta_atingida,2) end  AS desempenho
	
			FROM mapa m1 JOIN documento doc1 ON(m1.CodDocumento = doc1.codigo)  and (m1.anoinicio is NULL or m1.anoinicio<=:ano_base) and (m1.anofim is NULL or m1.anofim>:ano_base)
			JOIN objetivo ob1 ON(m1.codObjetivoPDI = ob1.Codigo)
			JOIN perspectiva pers1 ON(m1.codPerspectiva = pers1.codPerspectiva)
			inner join mapaindicador mi on mi.codMapa=m1.`Codigo` and ( mi.anoinicial<=:ano_base) and (mi.anofinal is NULL or mi.anofinal>:ano_base)
			left join indicador i on i.`Codigo`=`codIndicador`
			left join meta ma on ma.`CodMapaInd`=mi.codigo  and ma.ano=:ano_base and ma.ano=:ano_base and ( ma.anoinicial<=:ano_base) and (ma.anofinal is NULL or ma.anofinal>:ano_base)
			left join resultados_pdi r on r.`CodMeta`=ma.Codigo
			left join unidade uni on uni.`CodUnidade`=mi.propindicador
			left join indic_iniciativa ii on ii.`codMapaInd`=mi.`codigo` and ii.anoinicial<=:ano_base  and (ii.anofinal is NULL or ii.anofinal>:ano_base)
			JOIN calendario c1 ON c1.codCalendario = ma.codCalendario
			left join
	
			(select  i1.`codIniciativa`, i1.`nome`, i1.`codUnidade`, i1.`finalidade`,
			  i1.`anoInicio`, i1.`anoFinal` FROM `iniciativa` i1
			  where i1.anoinicio<=:ano_base and (i1.anofinal>=:ano_base or i1.anofinal is null ) ) as
		
			c on c.`codIniciativa`=ii.`codIniciativa`
			left join resultIniciativa ri on ri.`codIniciativa`=c.`codIniciativa` AND ri.codCalendario=c1.codCalendario
			WHERE doc1.anoinicial<=:ano_base AND doc1.anofinal >= :ano_base and doc1.CodUnidade=938
			and mi.tipoAssociado=7
			AND ano =:ano_base
		
			ORDER BY ob1.`Objetivo`";
			//and (tipoAssociado is NULL or (tipoAssociado like 'PDU' and mi.`PropIndicador` = :cod_unidade) )
				
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano_base'=>$ano_base));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: exportarResultadoPainelPDI" . $ex->getMessage();
		}
	}
	
public  function  exportarResultadoPainelPDI938($ano_base){
		try {
			$sql = "select DISTINCT doc1.nome as nomedocumento,doc1.`anoinicial`,doc1.`anofinal`, doc1.CodUnidade, ob1.`Objetivo`,unidademedida,
			i.nome as nomeindicador,i.Codigo AS codIndicador,i.interpretacao ,i.calculo,c.nome as nomeiniciativa,
`meta`,ma.ano as ano";
			
			
			//case when `metrica`='Q' then 'Absoluto' else 'Percentual' end as metrica,
			if ($ano_base<2022){
			    
			    
			    //case when `metrica`='Q' then 'Absoluto' else 'Percentual' end as metrica,
			    
			    $sql.=" , CASE WHEN ma.metrica = 'P' THEN 'Percentual' WHEN ma.metrica = 'Q' THEN 'Absoluto' END AS metrica ";
			} else {
			    
			    $sql.="  , CASE WHEN unidademedida = 'P' THEN 'Percentual'
                               WHEN unidademedida = 'Q' THEN 'Absoluto'
                               WHEN unidademedida = 'R' THEN 'Real'
                               WHEN unidademedida = 'M' THEN 'Metro Quadrado'
                               END AS metrica";
			}
			
			
			
			
			
			$sql.=" , case when r.`periodo`=1 then 'Parcial' else 'Final' end periodo1, ob1.Objetivo as nomeobjetivo, pers1.nome as nomeperspectiva 
			,r.meta_atingida,r.analiseCritica,
			CASE WHEN ri.situacao=1 THEN 'Não iniciado' 
			WHEN ri.situacao=2 THEN 'Em atraso' WHEN ri.situacao=3 THEN 'Com atrasos críticos' 
			WHEN ri.situacao=4 THEN 'Em andamento normal' 
			WHEN ri.situacao=5 THEN 'Concluído' END as situacao,
			 `pfcapacit`,`pfrecti`,`pfinfraf`,`pfrecf`,`pfplanj`,`outros` ,
			case when i.interpretacao=1 then round((meta_atingida*100)/meta,2) else round((meta*100)/meta_atingida,2) end  AS desempenho 
			 
			FROM mapa m1 JOIN documento doc1 ON(m1.CodDocumento = doc1.codigo) and (m1.anoinicio is NULL or m1.anoinicio<=:ano_base) and (m1.anofim is NULL or m1.anofim>:ano_base)
			JOIN objetivo ob1 ON(m1.codObjetivoPDI = ob1.Codigo) 
			JOIN perspectiva pers1 ON(m1.codPerspectiva = pers1.codPerspectiva) 
			inner join mapaindicador mi on mi.codMapa=m1.`Codigo` and  mi.anoinicial<=:ano_base and (mi.anofinal is NULL or mi.anofinal>:ano_base)
			left join indicador i on i.`Codigo`=`codIndicador` 
			left join meta ma on ma.`CodMapaInd`=mi.codigo  and ma.ano=:ano_base and ma.ano=:ano_base and ma.anoinicial<=:ano_base and (ma.anofinal is NULL or ma.anofinal>:ano_base) 
			left join resultados_pdi r on r.`CodMeta`=ma.Codigo 
			left join indic_iniciativa ii on ii.`codMapaInd`=mi.`codigo` and ii.anoinicial<=:ano_base  and (ii.anofinal is NULL or ii.anofinal>:ano_base)
			left join 

			(select  i1.`codIniciativa`, i1.`nome`, i1.`codUnidade`, i1.`finalidade`, 
			  i1.`anoInicio`, i1.`anoFinal` FROM `iniciativa` i1
			  where i1.anoinicio<=:ano_base and (i1.anofinal>=:ano_base or i1.anofinal is null ) ) as
			
			c on c.`codIniciativa`=ii.`codIniciativa` 
			left join resultIniciativa ri on ri.`codIniciativa`=c.`codIniciativa`
			WHERE  doc1.anoinicial<=:ano_base AND doc1.anofinal >= :ano_base and doc1.CodUnidade=938
			and mi.tipoAssociado=7
			AND ano =:ano_base 
			
			ORDER BY ob1.`Objetivo`";
			
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano_base'=>$ano_base));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	
}
?>

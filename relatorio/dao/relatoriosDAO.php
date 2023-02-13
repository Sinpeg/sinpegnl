<?php
class RelatoriosDAO extends PDOConnectionFactory{
	
	public $conex = null;
	
	public  function  premios($ano_base,$cod_unidade){
		try {
			$sql = "SELECT u.nomeunidade,s.nomeunidade as subunidade ,`OrgaoConcessor`,case when `Categoria`=1 then 'Discente'
			when `Categoria`=2 then 'Docente'
			else 'Tecnico Administrativo' end as categorias
			,`Nome`,`Quantidade`,case when `Reconhecimento`=1  then 'Premios'
			 when `Reconhecimento`=2  then 'Distincoes'
			  when `Reconhecimento`=3  then 'Titulos'
			 when `Reconhecimento`=4  then 'Honrarias'
			 when `Reconhecimento`=5  then 'Portaria de Reconhecimento'
			  when `Reconhecimento`=6  then 'Certificacao'
			end as Reconhecimento
			,`infocomplementar`,Qtde_discente , Qtde_docente ,Qtde_tecnico,pais,link
			FROM `premios` p inner join unidade s on p.`CodSubunidade`=s.`CodUnidade`
			inner join unidade u on p.`CodUnidade`=u.`CodUnidade`
			WHERE ano=:ano_base AND (u.CodUnidade=:cod_unidade OR p.CodSubunidade=:cod_unidade)
			order by u.nomeunidade,s.nomeunidade";
		
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano_base'=>$ano_base,':cod_unidade'=>$cod_unidade));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}

	public  function  premiosAdm($ano_base){
		try {
			$sql = "SELECT u.nomeunidade,s.nomeunidade as subunidade ,`OrgaoConcessor`,case when `Categoria`=1 then 'Discente'
			when `Categoria`=2 then 'Docente'
			else 'Tecnico Administrativo' end as categorias
			,`Nome`,`Quantidade`,case when `Reconhecimento`=1  then 'Premios'
			 when `Reconhecimento`=2  then 'Distincoes'
			  when `Reconhecimento`=3  then 'Titulos'
			 when `Reconhecimento`=4  then 'Honrarias'
			 when `Reconhecimento`=5  then 'Portaria de Reconhecimento'
			  when `Reconhecimento`=6  then 'Certificacao'
			end as Reconhecimento
			,`infocomplementar`,Qtde_discente , Qtde_docente ,Qtde_tecnico,pais,link
			FROM `premios` p inner join unidade s on p.`CodSubunidade`=s.`CodUnidade`
			inner join unidade u on p.`CodUnidade`=u.`CodUnidade`
			WHERE ano=:ano_base 
			order by u.nomeunidade,s.nomeunidade";
	
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano_base'=>$ano_base));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	
	//Exporta o relatório de produção intelectual para o user ADMIN
	public  function  producaoAdm($ano_base){
		try {
			$sql = "SELECT CASE WHEN tp.Anuario = 'A' THEN 'Artística' 
					WHEN tp.Anuario = 'B' THEN 'Bibliográfica' 
					WHEN tp.Anuario = 'O' THEN 'Bibliográfica' 
					WHEN tp.Anuario = 'T' THEN 'Técnica' 
					END AS tipo, tp.Nome, SUM( p.Quantidade ) AS quant FROM prodintelectual AS p JOIN tdm_prodintelectual AS tp ON ( p.Tipo = tp.Codigo )
					WHERE p.Ano =:ano_base
					AND tp.Validade =2014
					GROUP BY tp.Anuario, tp.Nome";

			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano_base'=>$ano_base));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	
	//Exporta o relatório de produção intelectual para unidade
	public  function  producaoUni($ano_base,$codUnidade){
		try {
			$sql = "SELECT CASE WHEN tp.Anuario = 'A' THEN 'Artística'
					WHEN tp.Anuario = 'B' THEN 'Bibliográfica'
					WHEN tp.Anuario = 'O' THEN 'Bibliográfica'
					WHEN tp.Anuario = 'T' THEN 'Técnica'
					END AS tipo, tp.Nome, SUM( p.Quantidade ) AS quant FROM prodintelectual AS p JOIN tdm_prodintelectual AS tp ON ( p.Tipo = tp.Codigo )
					WHERE p.Ano =:ano_base
					AND p.codunidade=:unidade
					AND tp.Validade =2014
					GROUP BY tp.Anuario, tp.Nome";
	
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano_base'=>$ano_base,':unidade'=>$codUnidade));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	
	//Exportar o relatório de biblioteca para o user ADMIN
	public  function  bibliotecaAdm($ano_base){
		try {
			$sql = "SELECT `codEmec` , e.`nome` AS nome ,
			case when `tipo`=2 then 'setorial' else 'central' end as Tipo ,
			`nAssentos` as assentos ,
			`nEmpDomicilio` as 'Emprestimo a Domicilio' ,
			`nEmpBiblio` as 'Emprestimo entre bibliotecas',
			`nUsuariosTpc` as 'Usuarios treinados em capacitação' ,
			`tapi` as 'Periodicos impressos',
			`tali` as 'Livros impressos' ,
			`tomi` as 'Outros materiais impressos' ,
			`tape` as 'Acervo de periodicos eletronicos',
			`tale` as 'Acervo de livros eletronicos' ,			
			
			case when `fBuscaIntegrada`=1 then 'Sim' else 'Não' end as 'Busca Integrada' ,
			case when `comutBibliog`=1 then 'Sim' else 'Não' end as 'Comutação Bibliografica' ,
			case when `servInternet`=1 then 'Sim' else 'Não' end as 'Serviço de Internet',
			case when `redeSemFio`=1 then 'Sim' else 'Não' end as 'REde sem fio' ,
			case when `partRedeSociais`=1 then 'Sim' else 'Não' end as 'Part redes sociais',
			case when `atendTreiLibras`=1 then 'Sim' else 'Não' end as 'Atendente em libras' ,
			
			case when `acervoFormEspecial`=1 then 'Sim' else 'Não' end as 'Acervo form especial',
			case when `appFormEspecial`=1 then 'Sim' else 'Não' end as '2a' ,
			case when planoFormEspecial=1 then 'Sim' else 'Não' end as 'plano em form especial',
			
			case when `sofLeitura`=1 then 'Sim' else 'Não' end as 'sof leitura baixa visao' ,
			case when `impBraile`=1 then 'Sim' else 'Não' end as 'impressoras braile',
			case when `tecVirtual`=1 then 'Sim' else 'Não' end as 'teclado virtual',
						
			case when `portalCapes`=1 then 'Sim' else 'Não' end as 'Parte portal capes' ,
			case when `outrasBases`=1 then 'Sim' else 'Não' end as 'Assina outras bases',
			case when `bdOnlineSerPub`=1 then 'Sim' else 'Não' end as 'Repositorio Inst de serv publico',
			case when `catOnlineSerPub`=1 then 'Sim' else 'Não' end as 'Cat. on line de serv publico' ,
			
			`justificativa` ,l.nome AS abrangencia
			FROM `bibliemec` e left join `biblicenso` c on e.`idBibliemec` = c.`idBibliemec`
			left join bloferta f on f.`idBibliemec`= e.`idBibliemec`
			left join localoferta l on l.idLocal = f.idloferta
			WHERE e.`situacao` = 'A'
			AND ano =:ano_base
			order by e.nome";
	
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano_base'=>$ano_base));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	
	
	//Exporta o relatório de produção da saúde
	public  function  producaoSaude($ano_base,$cod_unidade){
		try {
			$sql = "SELECT  s.CodUnidade,us.NomeUnidade AS Subunidade,us.codunidade, ups.NomeUnidade AS Localizacao,			
			 s.Nome AS nomeServico, p.Nome AS nomeProcedimento,  ps.Ano,
			 sum(ps.ndiscentes) AS nDiscentes, 
			sum(ps.ndocentes) AS nDocentes,
			 sum(ps.npesquisadores) AS nPesquisadores, 
			sum(ps.npessoasatendidas) AS nPessoasAtendidas,
			sum(ps.nprocedimentos) AS nProcedimentos,
			sum(ps.nexames) AS nExames			
			FROM unidade us                
			join servico s on s.CodSubunidade = us.CodUnidade           
			join servproced sp on s.Codigo = sp.CodServico 
			join procedimento p on sp.CodProced = p.CodProcedimento
			join psaudemensal ps on ps.CodServProc = sp.CodServProc  
			left join unidade ups on  ps.CodLocal = ups.CodUnidade          
			WHERE ps.Ano =:ano_base AND s.CodUnidade=:cod_unidade
            group BY us.NomeUnidade ,us.codunidade, ups.NomeUnidade, s.Nome , p.Nome,ps.Ano
			order by us.NomeUnidade , ups.NomeUnidade, s.Nome , p.Nome";
	
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano_base'=>$ano_base,':cod_unidade'=>$cod_unidade));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	
	public  function  producaoSaudeAnuario($ano_base){
	    try {
	        $sql = "SELECT  s.CodUnidade,us.NomeUnidade AS Subunidade,us.codunidade, ups.NomeUnidade AS Localizacao,
			 s.Nome AS nomeServico, p.Nome AS nomeProcedimento,  ps.Ano,
			 sum(ps.ndiscentes) AS nDiscentes,
			sum(ps.ndocentes) AS nDocentes,
			 sum(ps.npesquisadores) AS nPesquisadores,
			sum(ps.npessoasatendidas) AS nPessoasAtendidas,
			sum(ps.nprocedimentos) AS nProcedimentos,
			sum(ps.nexames) AS nExames
			FROM unidade us
			join servico s on s.CodSubunidade = us.CodUnidade
			join servproced sp on s.Codigo = sp.CodServico
			join procedimento p on sp.CodProced = p.CodProcedimento
			join psaudemensal ps on ps.CodServProc = sp.CodServProc
			left join unidade ups on  ps.CodLocal = ups.CodUnidade
			WHERE ps.Ano =:ano_base
            group BY Subunidade ,us.codunidade, Localizacao, nomeServico , nomeProcedimento,ps.Ano
			order by us.NomeUnidade , ups.NomeUnidade, s.Nome , p.Nome";
	        
	        $stmt = parent::prepare($sql);
	        $stmt->execute(array(':ano_base'=>$ano_base));
	        return $stmt;
	    } catch (PDOException $ex) {
	        echo "Erro: " . $ex->getMessage();
	    }
	}
	
	//Exporta o relatório da estrura dos polos
	public  function  estruraPolos($ano_base){
		try {
			$sql = "SELECT u.NomeUnidade,p.bandaLarga,p.videoConf,p.micros,p.coordenacao,p.salaTutor FROM unidade AS u INNER JOIN poloEstrutura p ON u.CodUnidade=p.codUnidade WHERE p.ano=:ano_base";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano_base'=>$ano_base));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	
	//Exporta o relatório acerca do laçamento dos resultados do PDU
	public  function  resultadosPDU($ano_base){
		try {
			$sql = "SELECT u.sigla as unidade,u.codUnidade,d.`nome`, d.codigo as documento,d.`anoinicial`,d.`anofinal`, d.CodUnidade, i.Codigo as indicador,i.nome as nomeindicador,`meta`,ma.ano,`meta_atingida` as resultado FROM unidadePDU as u LEFT JOIN documento as d ON d.CodUnidade=u.codUnidade LEFT JOIN `mapa` m on d.`codigo`=m.`CodDocumento` 
LEFT JOIN `objetivo` o on `codObjetivoPDI`=o.`Codigo` LEFT JOIN mapaindicador mi on mi.`codMapa`=m.`Codigo` LEFT JOIN indicador i on i.`Codigo`=`codIndicador` LEFT JOIN meta ma on ma.`CodMapaInd`=mi.codigo LEFT JOIN calendario c1 ON c1.codCalendario = ma.codCalendario  left join resultados_pdi r on r.`CodMeta`=ma.Codigo 
WHERE (ma.ano=:ano_base OR ma.ano IS NULL) AND d.situacao<>'I'
					UNION
					select u.sigla as unidade,u.codUnidade,doc1.nome as nome,doc1.codigo AS documento,doc1.`anoinicial`,doc1.`anofinal`, doc1.CodUnidade, 
			i.Codigo AS indicador,i.nome as nomeindicador
			,ma.meta,ma.ano as ano,r.meta_atingida as resultado FROM mapa m1 JOIN documento doc1 ON(m1.CodDocumento = doc1.codigo)
			LEFT join objetivo ob1 ON(m1.codObjetivoPDI = ob1.Codigo) 
			LEFT join perspectiva pers1 ON(m1.codPerspectiva = pers1.codPerspectiva) 
			LEFT join mapaindicador mi on mi.codMapa=m1.`Codigo` 
			left join indicador i on i.`Codigo`=`codIndicador` 
			left join meta ma on ma.`CodMapaInd`=mi.codigo 
			left join resultados_pdi r on r.`CodMeta`=ma.Codigo 
            LEFT JOIN unidadePDU AS u ON u.codUnidade=mi.PropIndicador 
			
			WHERE mi.`PropIndicador` IN (SELECT codUnidade FROM unidadePDU) and doc1.anoinicial<=:ano_base AND doc1.situacao<>'I' AND doc1.anofinal >= :ano_base and doc1.CodUnidade=938
			and (tipoAssociado is NULL or (tipoAssociado like 'PDU' and mi.`PropIndicador` IN (SELECT codUnidade FROM unidadePDU)) ) AND ano =:ano_base 
            ORDER BY unidade ";
			
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano_base'=>$ano_base));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	/*
	 
	 FROM unidade as u 
LEFT JOIN documento as d ON d.CodUnidade=u.codUnidade and d.anoinicial<=2019 and d.anofinal>=2019 AND d.situacao = 'A'
LEFT JOIN `mapa` m on d.`codigo`=m.`CodDocumento` and m.anoinicio<=2019 and (m.anofim>2019 or  m.anofim is NULL)
LEFT JOIN `objetivo` o on `codObjetivoPDI`=o.`Codigo` 
LEFT JOIN mapaindicador mi on mi.`codMapa`=m.`Codigo` and mi.anoinicial<=2019 and (mi.anofinal>2019 OR mi.anofinal IS NULL)
LEFT JOIN indicador i on i.`Codigo`=`codIndicador` 
LEFT JOIN meta ma on ma.`CodMapaInd`=mi.codigo  and ma.anoinicial<=2019 and (ma.anofinal>2019 OR ma.anofinal IS NULL) and (ma.ano=2019 OR ma.ano IS NULL)
LEFT JOIN calendario c1 ON c1.codCalendario = ma.codCalendario  
left join resultados_pdi r on r.`CodMeta`=ma.Codigo (ESTA ESTÁ INCORRETA)
	 */
	
	////Relatório para o acompanhamento do PDU
	public  function  acompanhamentoPDU($ano_base){
		try {
			
			$sql="SELECT u.nomeunidade as unidade,u.codUnidade,d1.`nome`, d1.codigo as documento,d1.`anoinicial`,d1.`anofinal`, d1.CodUnidade,
			 i1.Codigo as indicador,i1.nome as nomeindicador,`meta`,a1.ano,`meta_atingida` as resultado
from unidade u
left join  documento d1 on d1.codunidade=u.codunidade and ( d1.anoinicial<=:ano_base and  d1.anofinal>=:ano_base)
left join `mapa` m1 on m1.`CodDocumento`=d1.`codigo` and m1.`anoinicio`<=:ano_base and (m1.`anofim` is null or m1.`anofim`>:ano_base)
left join `mapaindicador` mi1 on m1.`Codigo`=mi1.`codMapa` and mi1.`anoinicial`<=:ano_base and (mi1.`anofinal` is null or mi1.`anofinal`>:ano_base)
left join indicador i1 on mi1.codIndicador=i1.codigo and mi1.codindicador=i1.codigo 
left join `meta` a1 on a1.`CodMapaInd`=mi1.`codigo` and ano=:ano_base and  a1.`anoinicial`<=:ano_base and (a1.`anofinal` is null or a1.`anofinal`>:ano_base)
left join `resultados_pdi` r1 on r1.`CodMeta`=a1. `Codigo` 
 where   u.`unidade_responsavel` = 1 and u.nomeunidade not like 'POLO%'
UNION
select mi.nomeunidade as unidade,mi.codUnidade,doc1.nome as nome,doc1.codigo AS documento,doc1.`anoinicial`,doc1.`anofinal`, 
doc1.CodUnidade,mi.indicador,mi.nome as nomeindicador,ma.meta,ma.ano as ano,r.meta_atingida as resultado 
from documento doc1  
left JOIN mapa m1 ON (m1.CodDocumento = doc1.codigo) and m1.`anoinicio`<=:ano_base and (m1.`anofim` is null or m1.`anofim`>:ano_base) 
and doc1.anoinicial<=:ano_base and doc1.anofinal>=:ano_base AND doc1.situacao = 'A' and doc1.CodUnidade=938
left join  objetivo ob1 ON (m1.codObjetivoPDI = ob1.Codigo) 
left join  perspectiva pers1 ON(m1.codPerspectiva = pers1.codPerspectiva) 
left join (select mi1.codigo,i.Codigo AS indicador,i.nome,codMapa,u.sigla,u.CodUnidade,u.nomeunidade
           from unidade u join 

          mapaindicador mi1  on u.codunidade=mi1.PropIndicador
           
        join   indicador i ON i.`Codigo`=mi1.`codIndicador` and (mi1.tipoAssociado = 7 OR mi1.tipoAssociado like 'PDU'
         OR  mi1.tipoAssociado IS null) and mi1.PropIndicador=u.codunidade and mi1.`anoinicial`<=:ano_base and
          (mi1.`anofinal` is null or mi1.`anofinal`>:ano_base) ) as mi 
on mi.codMapa=m1.`Codigo`  
left join meta ma on ma.`CodMapaInd`=mi.codigo and ma.anoinicial<=:ano_base and 
      (ma.anofinal>:ano_base OR ma.anofinal IS NULL) and (ma.ano=:ano_base OR ma.ano IS NULL)
left join resultados_pdi r on r.`CodMeta`=ma.Codigo 
where mi.codunidade is not null
            ORDER BY unidade";
			
			
		/*	$sql = "SELECT u.sigla as unidade,u.codUnidade,d.`nome`, d.codigo as documento,d.`anoinicial`,d.`anofinal`, d.CodUnidade, i.Codigo as indicador,i.nome as nomeindicador,`meta`,ma.ano,`meta_atingida` as resultado FROM unidadePDU as u LEFT JOIN documento as d ON d.CodUnidade=u.codUnidade LEFT JOIN `mapa` m on d.`codigo`=m.`CodDocumento` 
LEFT JOIN `objetivo` o on `codObjetivoPDI`=o.`Codigo` LEFT JOIN mapaindicador mi on mi.`codMapa`=m.`Codigo` LEFT JOIN indicador i on i.`Codigo`=`codIndicador` LEFT JOIN meta ma on ma.`CodMapaInd`=mi.codigo LEFT JOIN calendario c1 ON c1.codCalendario = ma.codCalendario  left join resultados_pdi r on r.`CodMeta`=ma.Codigo 
WHERE (ma.ano=:ano_base OR ma.ano IS NULL) AND d.situacao = 'A' 
					UNION
					select u.sigla as unidade,u.codUnidade,doc1.nome as nome,doc1.codigo AS documento,doc1.`anoinicial`,doc1.`anofinal`, doc1.CodUnidade, 
			i.Codigo AS indicador,i.nome as nomeindicador
			,ma.meta,ma.ano as ano,r.meta_atingida as resultado FROM mapa m1 JOIN documento doc1 ON(m1.CodDocumento = doc1.codigo)
			LEFT join objetivo ob1 ON(m1.codObjetivoPDI = ob1.Codigo) 
			LEFT join perspectiva pers1 ON(m1.codPerspectiva = pers1.codPerspectiva) 
			LEFT join mapaindicador mi on mi.codMapa=m1.`Codigo` 
			left join indicador i on i.`Codigo`=`codIndicador` 
			left join meta ma on ma.`CodMapaInd`=mi.codigo 
			left join resultados_pdi r on r.`CodMeta`=ma.Codigo 
            LEFT JOIN unidadePDU AS u ON u.codUnidade=mi.PropIndicador 
			
			WHERE  doc1.situacao = 'A' AND mi.`PropIndicador` IN (SELECT codUnidade FROM unidadePDU) and doc1.anoinicial<=:ano_base AND doc1.anofinal >= :ano_base and doc1.CodUnidade=938
			and ((tipoAssociado = 7   or tipoAssociado IS null) or (tipoAssociado like 'PDU' and mi.`PropIndicador` IN (SELECT codUnidade FROM unidadePDU)) ) AND ano =:ano_base 
            ORDER BY unidade ";*/
				
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano_base'=>$ano_base));
			
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	
	

	//Relatório para o acompanhamento da entrega dos relatórios anuais
	public  function  entregaRAA($anobase){
		try {
			$sql = "SELECT nomeUnidade,u.codUnidade,anobase,dataFinalizacao 
			FROM `unidade` AS u LEFT JOIN hmologacaoRAA AS h ON u.codUnidade = h.CodUnidade
			 and `anobase`=:ano where unidade_responsavel=1 and nomeunidade not like 'POLO%' AND u.codUnidade NOT IN (3690,939,944,2026,538,545,7099,947,3757,2188,2222,955,941,942,938,940) ";
				
			
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano'=>$anobase));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	
	//Relatório para o acompanhamento da entrega dos anexos
	public  function  entregaArq($ano_base,$cod_unidade){
		try {
			$sql = "SELECT ar.Nome,ar.Ano,us.CodUnidade FROM `arquivo` AS ar INNER JOIN usuario AS us ON us.CodUsuario=ar.Codusuario WHERE Ano=:ano AND us.CodUnidade=:unidade";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano'=>$ano_base,':unidade'=>$cod_unidade));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	
	//Relatório para o acompanhamento do cadastro da tec. assistiva e laboratórios
	public  function  cadastroTecAssisLab($ano_base){
		try {
			$sql = "SELECT u.CodUnidade,u.NomeUnidade,c.CodCurso,c.NomeCurso, ta.CodTecnologiaAssistiva AS tecAssistiva,l.CodLaboratorio AS laboratorio FROM unidade AS u INNER JOIN curso AS c ON c.CodUnidade=u.CodUnidade  LEFT JOIN `tecnologia_assistiva` AS ta ON c.CodCurso=ta.CodCurso LEFT JOIN laboratorio_curso AS lc ON lc.CodCurso=c.CodCurso LEFT JOIN laboratorio AS l ON l.CodLaboratorio=lc.CodLaboratorio WHERE c.AnoValidade >=".$ano_base." AND (l.AnoDesativacao IS NULL OR l.AnoDesativacao >=".$ano_base.") AND (ta.Ano=2018 OR ta.Ano IS NULL) GROUP BY c.CodCurso ORDER BY u.NomeUnidade";
			$stmt = parent::prepare($sql);
			$stmt->execute();
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	
	//Verificar se existe documento cadastrado
	public  function  verificaCadDoc($ano_base,$cod_unidade){
		try {
			$sql = "SELECT * FROM `documento` WHERE anoinicial<=:ano AND anofinal >=:ano2  AND CodUnidade=:unidade";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano'=>$ano_base,':ano2'=>$ano_base,':unidade'=>$cod_unidade));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	
	//Busca para síntese do relatório de preenchimento do formúlário de gestão do plano
	public  function  avaliacaoPlano($ano_base){
		try {
			$sql = "SELECT u.NomeUnidade,c.anoGestao,a.avaliacao,a.RAT,a.periodo FROM `avaliacaofinal` a 
					INNER JOIN documento d ON a.codDocumento=d.codigo
					INNER JOIN unidade u ON d.CodUnidade=u.CodUnidade
					INNER JOIN calendario c ON a.codCalendario=c.codCalendario
					WHERE c.anoGestao=:ano ORDER BY u.NomeUnidade";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':ano'=>$ano_base));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	
	
}
?>
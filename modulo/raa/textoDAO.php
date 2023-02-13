<?php
class TextoDAO extends PDOConnectionFactory {

	public function buscaTopicosPreenchidos_Pendentes( $ano, $codunidade) {
		
		try {		
            $sql = 
           " SELECT con.ordem,con.codigo as tcodigo,con.titulo,con.codtopico,con.ano,con.subtopico,con.tipo, 
            con.Situação, con.texto ,con.codtexto,con.codunidade
            from 
            
            
            (SELECT t.codigo ,t.codtopico,e.ano,t.titulo,
            CASE WHEN t.codtopico is NULL tHEN t.codigo ELSE t.codtopico end as subtopico,
            CASE WHEN t.tipo='P' then 'Padrão' else 'Especial' end as tipo, 
            case WHEN e.texto is not NULL then 'P' else 'V' end as Situação, texto ,t.ordem,e.codigo as codtexto,e.codunidade
            FROM `raa_topico` t left  join raa_texto e on e.`codTopico`=t.codigo and (e.ano=:ano1 or e.ano is NULL) and e.codunidade=:uni
            
            WHERE  (t.codunidade=:uni or t.codunidade is null) and t.titulo  NOT like 'CONSIDER%FINAIS' 
            and t.titulo  NOT like 'Referências Bib%'
            and t.titulo  NOT like 'ANEXO I'
            and t.titulo  NOT like 'Anexo'
            and  t.codtopico is null
            and t.anoinicial<=:ano1 and (t.anofinal is null or t.anofinal>:ano1) 
            
      UNION 
      
      SELECT t.codigo ,t.codtopico,e.ano,t.titulo,
            CASE WHEN t.codtopico is NULL tHEN t.codigo ELSE t.codtopico end as subtopico,
            CASE WHEN t.tipo='P' then 'Padrão' else 'Especial' end as tipo, 
            case WHEN e.texto is not NULL then 'P' else 'V' end as Situação, texto ,ordemConsideracoesfinais(:uni,:ano1) as ordem,
            e.codigo as codtexto,e.codunidade
            FROM `raa_topico` t left  join raa_texto e on e.`codTopico`=t.codigo and (e.ano=:ano1 or e.ano is NULL)  and e.codunidade=:uni
            WHERE  (t.codunidade=:uni or t.codunidade is null) and t.titulo  like 'CONSIDER%FINAIS'
            and t.anoinicial<=:ano1 and (t.anofinal is null or t.anofinal>:ano1) 
     UNION 
      
      SELECT t.codigo ,t.codtopico,e.ano,t.titulo,
            CASE WHEN t.codtopico is NULL tHEN t.codigo ELSE t.codtopico end as subtopico,
            CASE WHEN t.tipo='P' then 'Padrão' else 'Especial' end as tipo, 
            case WHEN e.texto is not NULL then 'P' else 'V' end as Situação, texto ,ordemConsideracoesfinais(:uni,:ano1)+1 as ordem,
            e.codigo as codtexto,e.codunidade
            FROM `raa_topico` t left  join raa_texto e on e.`codTopico`=t.codigo and (e.ano=:ano1 or e.ano is NULL)  and e.codunidade=:uni
            WHERE  (t.codunidade=:uni or t.codunidade is null) and t.titulo  like 'Referências%B%'
            and t.anoinicial<=:ano1 and (t.anofinal is null or t.anofinal>:ano1) 
        UNION
        SELECT t.codigo ,t.codtopico,e.ano,t.titulo,
            CASE WHEN t.codtopico is NULL tHEN t.codigo ELSE t.codtopico end as subtopico,
            CASE WHEN t.tipo='P' then 'Padrão' else 'Especial' end as tipo, 
            case WHEN e.texto is not NULL then 'P' else 'V' end as Situação, texto ,ordemConsideracoesfinais(:uni,:ano1)+1 as ordem,
            e.codigo as codtexto,e.codunidade
            FROM `raa_topico` t left  join raa_texto e on e.`codTopico`=t.codigo and (e.ano=:ano1 or e.ano is NULL)  and e.codunidade=:uni
            WHERE  (t.codunidade=:uni or t.codunidade is null) and t.titulo  like 'Anexo'
            and t.anoinicial<=:ano1 and (t.anofinal is null or t.anofinal>:ano1) 
        UNION  
      
      SELECT t.codigo ,t.codtopico,e.ano,t.titulo,
            CASE WHEN t.codtopico is NULL tHEN t.codigo ELSE t.codtopico end as subtopico,
            CASE WHEN t.tipo='P' then 'Padrão' else 'Especial' end as tipo, 
            case WHEN e.texto is not NULL then 'P' else 'V' end as Situação, texto ,ordemConsideracoesfinais(:uni,:ano1)+2 as ordem,
            e.codigo as codtexto,e.codunidade
            FROM `raa_topico` t left  join raa_texto e on e.`codTopico`=t.codigo and (e.ano=:ano1 or e.ano is NULL)  and e.codunidade=:uni
            WHERE  (t.codunidade=:uni or t.codunidade is null) and t.titulo like 'ANEXO I'
            and t.anoinicial<=:ano1 and (t.anofinal is null or t.anofinal>:ano1) 
      )
           as con
order by con.ordem asc";
            /*"SELECT t.codigo as tcodigo,t.titulo,t.codtopico,ano,CASE WHEN t.codtopico is NULL tHEN t.codigo ELSE t.codtopico end as subtopico,
            CASE WHEN t.tipo='P' then 'Padrão' else 'Especial' end as tipo, 
            case WHEN e.texto is not NULL then 'P' else 'V' end as Situação, texto ,CASE WHEN t.titulo  like 'CONSIDERA%FINAIS' THEN ordemConsideracoesfinais(:uni,:ano) ELSE t.ordem end AS ordem,e.codigo as codtexto,e.codunidade
            FROM `raa_topico` t left  join raa_texto e on e.`codTopico`=t.codigo 
            WHERE  (t.codunidade=:uni or t.codunidade is null) and t.codtopico is null
            and t.anoinicial<=:ano and (t.anofinal is null or t.anofinal>=:ano) and (e.ano=:ano or e.ano is NULL )
            ORDER BY t.ordem ASC  ";*/
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':uni' => $codunidade,':ano1' => $ano));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: buscaTopicosPreenchidos_Pendentes - textoDAO" . $ex->getMessage();
        }
		
	}
	
		public function buscaSubTopicosPreenchidos_Pendentes( $ano, $codunidade) {
		
		try {
			
			
			
            $sql = "SELECT t.codigo as tcodigo,t.titulo,t.codtopico,ano,CASE WHEN t.codtopico is NULL tHEN t.codigo ELSE t.codtopico end as subtopico,
            CASE WHEN t.tipo='P' then 'Padrão' else 'Especial' end as tipo, 
            case WHEN e.texto is not NULL then 'P' else 'V' end as Situação, texto ,t.ordem,e.codigo as codtexto,e.codunidade
            FROM `raa_topico` t left  join raa_texto e on e.`codTopico`=t.codigo and (e.ano=:ano or e.ano is NULL ) and e.codunidade=:uni
            WHERE  (t.codunidade=:uni or t.codunidade is null) and t.codtopico is not null 
            and t.titulo not like 'CONSIDERA%FINAIS'
            and t.titulo not like 'REFER%BIBLIOGR%FICAS'
            AND t.titulo not like 'ANEXO I'
            AND t.titulo not like 'Anexo'
            and t.anoinicial<=:ano and (t.anofinal is null or t.anofinal>:ano) and (e.ano=:ano or e.ano is NULL ) 
            GROUP BY 1,2,3,4,5,
            6, 
            7, 8 ,9,10,11
            ORDER BY t.ordem ASC  ";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':uni' => $codunidade,':ano' => $ano,':ano' => $ano,':ano' => $ano));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: buscaSubTopicosPreenchidos_Pendentes - textoDAO" . $ex->getMessage();
        }
		
	}
	
	public function buscaTexto($codtopico, $ano, $codunidade) {
		
		try {
            $sql = "SELECT `codigo`, `codTopico`, `codUnidade`, `texto`, `ano`  FROM raa_texto WHERE codunidade=:uni and ano=:ano and codtopico=:codt and codtopico<>2";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':uni' => $codunidade, ':codt' => $codtopico,':ano'=>$ano));
            return $stmt;

        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
		
		
	}
	
	public function buscaArquivoAval($codigo) {
		
		try {
            $sql = "SELECT * FROM `avaliacaofinal` WHERE `codigo`=:codigo";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codigo));
                        return $stmt;

        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
	}
	
	public function insere(Texto $t) {
		
			try{
			$stmt = parent::prepare("INSERT INTO `raa_texto`( `codTopico`, `codUnidade`, `texto`,`ano`) VALUES (?,?,?,?)");
			parent::beginTransaction();
			
			$stmt->bindValue(1, $t->getTopico()->getCodigo());
			
			$stmt->bindValue(2, $t->getUnidade()->getCodunidade());
			
			
			$stmt->bindValue(3, $t->getDesctexto());
			$stmt->bindValue(4, $t->getAno());
			//echo  $t->getTopico()->getCodigo()."-".$t->getUnidade()->getCodunidade()."-".$t->getDesctexto()."-".$t->getAno()."xxx";die;
			$stmt->execute();
			parent::commit();

		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
		}
	
		
	}
	

    public function altera( Texto $t ){
		try{
			$sql="UPDATE  `raa_texto` SET `codTopico`=?,codUnidade=?,`texto`=?,`ano`=? WHERE  `codigo`=?";
			parent::beginTransaction();
			$stmt = parent::prepare($sql);
			$stmt->bindValue(1, $t->getTopico()->getCodigo());
			$stmt->bindValue(2, $t->getUnidade()->getCodunidade());
			$stmt->bindValue(3, $t->getDesctexto());
			$stmt->bindValue(4, $t->getAno());
			$stmt->bindValue(5, $t->getCodigo());
			
			$stmt->execute();
            parent::commit();
		}
		catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
		}
	}
	
	public function deleta($codigo) {
		try {
			parent::beginTransaction();
			$stmt = parent::prepare("DELETE FROM `raa_texto` WHERE `codigo`=?");
			$stmt->bindValue(1, $codigo);
			$stmt->execute();
			parent::commit();
		} catch (PDOException $ex) {
			parent::rollback();
			print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
	}
	
	public function lista() {
	
        
         try {
            $sql = "SELECT * FROM `raa_texto` ORDER BY `codigo`";
            $stmt = parent::prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro: " . $ex->getCode() . " " . $ex->getMessage();
        }
	}
	
	public function listadisctinct() {
		try {
			$stmt = parent::query("SELECT DISTINCT * FROM `raa_texto`");
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	
	public function buscaTextoPorCodigo($codigo){
			
			try {
				parent::beginTransaction();
				$stmt = parent::query("SELECT * FROM `raa_texto` WHERE codigo = ?");
				$stmt->bindValue(1, $codaval);
				$stmt->execute();
				parent::commit();
			} catch (PDOException $ex) {
				parent::rollback();
				print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
			}			
	}
	
	public function buscaAvaliacafinalPorCodDocumento(){
		
		try {
			parent::beginTransaction();
			$stmt = parent::query("SELECT * FROM avaliacaofinal WHERE codDocumento= {$codDocumento}");
			$stmt->execute();
			parent::commit();
		} catch (PDOException $ex) {
			parent::rollback();
			print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
		
	}
	
	public function fechar() {
		PDOConnectionFactory::Close();
	}
	
	//Grava a finalização da elaboração do relatório
	public function finalizaTexto($codUnidade,$ano) {
	
		try{
			$stmt = parent::prepare("INSERT INTO `hmologacaoRAA`( `CodUnidade`, `anobase`) VALUES (?,?)");
			parent::beginTransaction();
				
			$stmt->bindValue(1, $codUnidade);				
			$stmt->bindValue(2, $ano);

			$stmt->execute();
			parent::commit();
	
		}catch ( PDOException $ex ){
			parent::rollback();
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
		}
	
	
	}
	
	//Busca a ocorrencia de finalização do relatorio
	public function buscaFinalizacaoRel($codUnidade,$ano){			
		try {
			parent::beginTransaction();
			$stmt = parent::query("SELECT * FROM `hmologacaoRAA` WHERE CodUnidade = ".$codUnidade." AND anobase = ".$ano."");	
			$stmt->execute();
			parent::commit();
			return $stmt;
		} catch (PDOException $ex) {
			parent::rollback();
			print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
			
	}

	//Buscar relatório
	public function listaRelatoriosRAA($ano){
		try {
			parent::beginTransaction();
			$stmt = parent::query("SELECT hmologacaoRAA.CodUnidade as codunidade ,unidade.NomeUnidade as nomeunidade,hmologacaoRAA.anobase, hmologacaoRAA.dataFinalizacao AS dataF FROM hmologacaoRAA LEFT JOIN unidade ON hmologacaoRAA.CodUnidade=unidade.CodUnidade WHERE unidade_responsavel=1 AND anobase=".$ano);
			$stmt->execute();
			parent::commit();
			return $stmt;
		} catch (PDOException $ex) {
			parent::rollback();
			print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
			
	}
	
	//Buscar pendências na finalização do relatório
	public function buscaPendencia145($codUnidade,$ano){
		try {
			parent::beginTransaction();
			$stmt = parent::query("SELECT * FROM `raa_texto` WHERE codUnidade = ".$codUnidade." AND ano = ".$ano." AND codTopico = 145");
			$stmt->execute();
			parent::commit();
			return $stmt;
		} catch (PDOException $ex) {
			parent::rollback();
			print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
			
	}
	//Buscar pendências na finalização do relatório
	public function buscaPendencia143($codUnidade,$ano){
		try {
			parent::beginTransaction();
			$stmt = parent::query("SELECT * FROM `raa_texto` WHERE codUnidade = ".$codUnidade." AND ano = ".$ano." AND codTopico = 143");
			$stmt->execute();
			parent::commit();
			return $stmt;
		} catch (PDOException $ex) {
			parent::rollback();
			print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
		}
			
	}
}
?>
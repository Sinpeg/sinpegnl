<?php

class SolicitacaoVersaoIndicadorDAO extends PDOConnectionFactory {

    public $conex = null;
  

    public function insere(SolicitacaoVersaoIndicador $svi) {
    	try {
    		$sql = "INSERT INTO `solicitacaoPDU` (`codigo`,`tipoSolicitacao`, `codunidade`,
    		 `codmapaind` , `codindicador`,`nome`,
    		`calculo`,`interpretacao`,  `justificativa` ,
    		`anexo`, `situacao`, `dataEmanalise` , `codusuarioanalise`,`anogestao`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    		
    
    		$stmt = parent::prepare($sql);
    		parent::beginTransaction();
    		$stmt->bindValue(1, $svi->getCodigo()); 
    		$stmt->bindValue(2, $svi->getTipo()); // tipo da solicitacao
    		$stmt->bindValue(3, $svi->getUnidade()->getCodUnidade()); // unidade
    		$stmt->bindValue(4, $svi->getMapaindicador()->getCodigo()); // mapaindicador
    		$stmt->bindValue(5, $svi->getIndicador()->getCodigo()); // indicador
    		$stmt->bindValue(6, $svi->getNome()); // indicador
    		$stmt->bindValue(7, $svi->getCalculo()); // indicador
			$stmt->bindValue(8, $svi->getInterpretacao()); // indicador    		    		
    		$stmt->bindValue(9, $svi->getJustificativa()); 
    		$stmt->bindValue(10, $svi->getAnexo()); 
    		$stmt->bindValue(11, $svi->getSituacao()); 
    		$stmt->bindValue(12, $svi->getDataemanalise()); 
    		$stmt->bindValue(13, $svi->getUsuarioanalista()->getCodusuario()); 
    		$stmt->bindValue(14, $svi->getAnogestao()); 
    		
    		$stmt->execute();
    		parent::commit();
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: insere SolicitacaoVersaoIndicador:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
    	}
    }
    
    
    public function altera(SolicitacaoVersaoIndicador $svi) {
    	try {
    		$sql = "UPDATE `solicitacaoPDU` SET  `codmapaind`=? , `codindicador`=?,`nome`=?,
    		`calculo`=?,`interpretacao`=?,`justificativa`=? ,`anexo`=?,`situacao`=?,`datasolicitacao`=?,  `dataEmanalise`=? ,
  `codusuarioanalise`=? WHERE `codigo`=?";
    		$stmt = parent::prepare($sql);
    		parent::beginTransaction();
    		
    		$stmt->bindValue(1, $svi->getMapaindicador()->getCodigo()); // mapaindicador
    		$stmt->bindValue(2, $svi->getIndicador()->getCodigo()); // indicador
    		$stmt->bindValue(3, $svi->getNome()); // indicador
    		$stmt->bindValue(4, $svi->getCalculo()); // indicador
			$stmt->bindValue(5, $svi->getInterpretacao()); // indicador	    		
    		$stmt->bindValue(6, $svi->getJustificativa()); 
    		$stmt->bindValue(7, $svi->getAnexo()); 
    		$stmt->bindValue(8, $svi->getSituacao()); 
    		$stmt->bindValue(9, $svi->getDatasolicitacao()); 
    		$stmt->bindValue(10, $svi->getDataemnalise()); 
    		$stmt->bindValue(11, $svi->getUsuario()->getCodusuario()); 
    		$stmt->bindValue(12, $svi->getCodigo()); 
    		$stmt->execute();
    		parent::commit();
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: altera SolicitacaoVersaoIndicador:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
    	}
    }
    
    public function deleta($codigo) {
    	try {
    		parent::beginTransaction();
    		$stmt = parent::prepare("DELETE FROM `solicitacaoPDU` WHERE `Codigo`=?");
    		$stmt->bindValue(1, $codigo);
    		$stmt->execute();
    		parent::commit();
    		return 1;
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print  $ex->getCode() . "Mensagem" . $ex->getMessage();
    		return 0;
    	}
    }
    
    public function buscaSolicitacaoVersaoIndicador($unidade,$anobase) {
    	try {
    
    		$sql="SELECT s.`codigo`,`tipoSolicitacao`, s.`codindicador`, s.`codusuarioanalise`, s.`codunidade`, `codmapaind` ,
 mi.codIndicador , i.nome as nomeindicadorantigo,i.calculo as calculoantigo, i.interpretacao as interpretacaoantiga,
 s.nome,s.interpretacao,s.calculo,
`justificativa` ,`anexo`,`situacao`,`datasolicitacao`, `dataEmanalise` , `codusuarioanalise`
from solicitacaoPDU s inner join mapaindicador mi on mi.codigo=s.codmapaind
inner join indicador i on i.codigo=mi.codIndicador
where s.codunidade=:unidade and `anogestao`=:ano 
order by `datasolicitacao` DESC";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':ano' => $anobase, ':unidade' => $unidade	));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro SolicitacaoVersaoIndicador: " . $ex->getMessage();
    	}
    }
    
    
   public function buscaTodasSolicitacoesUnidade($unidade,$anobase) {
    	try {
    
    		$sql="SELECT s.`codigo`, `tipoSolicitacao`, s.`codunidade`, s.`coddocumento`, s.`codmapaind`, s.`codindicador`,s.`codmapa`, s.`codobjetivo`,
    		 s.`codmeta`, `novameta`, s.`nome`, s.`calculo`, s.`interpretacao`, `justificativa`, s.`anexo`,s.`situacao`, `datasolicitacao`, `dataEmanalise`, 
    		 `codusuarioanalise`, `anogestao`,i.`nome`, i.`calculo`, i.`interpretacao`,codsoledicao
from solicitacaoPDU s 
left join documento d on d.codigo=s.`coddocumento`
left join mapa ma  on d.codigo=ma.`coddocumento` and s.`codmapa`=ma.Codigo
left join objetivo o  on o.Codigo=s.`codobjetivo` and ma.codObjetivoPDI=s.`codobjetivo`
left join meta me on me.Codigo=s.`codmeta`
left join mapaindicador mi on mi.codigo=s.codmapaind 
left join indicador i on i.codigo=mi.codIndicador
where  `anogestao`=:ano and s.codunidade=:unidade

UNION

SELECT s.`codigo`, `tipoSolicitacao`, s.`codunidade`, s.`coddocumento`, s.`codmapaind`, s.`codindicador`,s.`codmapa`, s.`codobjetivo`,
    		 s.`codmeta`, `novameta`, s.`nome`, s.`calculo`, s.`interpretacao`, `justificativa`, s.`anexo`,s.`situacao`, `datasolicitacao`, `dataEmanalise`, 
    		 `codusuarioanalise`, `anogestao`,i.`nome`, i.`calculo`, i.`interpretacao`,codsoledicao
from solicitacaoPDU s 
inner join meta me on me.Codigo=s.`codmeta` 
inner join mapaindicador mi on mi.codigo=me.codmapaind and mi.PropIndicador=:unidade
inner join indicador i on i.codigo=mi.codIndicador
where  `anogestao`=:ano and  (s.codunidade=938)";
    		//s.codunidade=:unidade and
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':ano' => $anobase, ':unidade' => $unidade	));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscaTodasSolicitacoes: " . $ex->getMessage();
    	}
    }

  public function buscaTodasSolicitacoes($anobase) {
    	try {
    
    		$sql="SELECT s.`codigo`, `tipoSolicitacao`, s.`codunidade`, s.`coddocumento`,d.nome, s.`codmapaind`, s.`codindicador`,s.`codmapa`, s.`codobjetivo`,
    		 s.`codmeta`, `novameta`, s.`nome`, s.`calculo`, s.`interpretacao`, `justificativa`, s.`anexo`,s.`situacao`, `datasolicitacao`, `dataEmanalise`, 
    		 `codusuarioanalise`, `anogestao`,i.`nome`, i.`calculo`, i.`interpretacao`
from solicitacaoPDU s 
left join documento d on d.codigo=s.`coddocumento`
left join mapa ma  on d.codigo=ma.`coddocumento` and s.`codmapa`=ma.Codigo
left join objetivo o  on o.Codigo=s.`codobjetivo` and ma.codObjetivoPDI=s.`codobjetivo`
left join meta me on me.Codigo=s.`codmeta`
left join mapaindicador mi on mi.codigo=s.codmapaind
left join indicador i on i.codigo=mi.codIndicador
where  `anogestao`=:ano 
order by s.situacao,s.`datasolicitacao` DESC";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':ano' => $anobase	));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscaTodasSolicitacoes: " . $ex->getMessage();
    	}
    }
    
     public function buscaSolicitacaoVersaoIndicadorPorSituacao($unidade,$anobase,$situacao) {
        try {
 
$sql="SELECT s.`codigo`,`tipoSolicitacao`, s.`codunidade`, `codmapaind` , 
 mi.codIndicador , i.nome as nomeindicadorantigo,i.calculo as calculoantigo, i.interpretacao as interpretacaoantiga,
 s.nome,s.interpretacao,s.calculo,
`justificativa` ,`anexo`,`situacao`,`datasolicitacao`, `dataEmanalise` , `codusuarioanalise` 
from solicitacaoPDU s inner join mapaindicador mi on mi.codigo=s.codmapaind 
inner join indicador i on i.codigo=mi.codIndicador
where s.codunidade=:unidade and `anogestao`=:ano and `situacao`=:situacao
order by `tipoSolicitacao`,`datasolicitacao`";
           $stmt = parent::prepare($sql);
    	   $stmt->execute(array(':anobase' => $anobase, ':unidade' => $unidade, 'situacao'=>$situacao));
    	   return $stmt;
        } catch (PDOException $ex) {
            echo "Erro SolicitacaoVersaoIndicador: " . $ex->getMessage();
        }
    }    
    
    
      public function listaTodas($anobase) {
        try {
 
$sql="SELECT * from solicitacaoPDU where  `anogestao`=:ano";
           $stmt = parent::prepare($sql);
    	   $stmt->execute(array(':ano' => $anobase));
    	   return $stmt;
        } catch (PDOException $ex) {
            echo "Erro listaTodas SolicitacaoVersaoIndicador por ano: " . $ex->getMessage();
        }
    }    
    
     public function buscaSolicitacaoNoAnoParaUnidade($unidade,$anobase) {//07/01
        try {
 
$sql="SELECT * from solicitacaoPDU
where codunidade=:unidade and `anogestao`=:anobase and `situacao` in ('A','G')";//R
           $stmt = parent::prepare($sql);
    	   $stmt->execute(array(':anobase' => $anobase, ':unidade' => $unidade));
    	   return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaSolicitacao: " . $ex->getMessage();
        }
    }    
    
    
    
      public function buscaQQSolicitacao($codigo) {
        try {
 
$sql="SELECT * from solicitacaoPDU   where  codigo=:codigo";
           $stmt = parent::prepare($sql);
    	   $stmt->execute(array(':codigo' => $codigo));
    	   return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaSolicitacao SolicitacaoEditObjetivo por ano: " . $ex->getMessage();
        }
    }    
    
    
    public function buscaSolicitacao($codigo) {
    	try {
    
    		$sql="SELECT distinct s.`codigo`,`tipoSolicitacao`, s.`codindicador`, s.`codusuarioanalise`, s.`codunidade`, s.`anogestao`, a.`codmapaind` ,
 mi.codIndicador , i.nome as nomeindicadorantigo,i.calculo as calculoantigo, i.interpretacao as interpretacaoantiga,
 s.nome,s.interpretacao,s.calculo,s.coddocumento,s.codmapa,s.codobjetivo,s.codmeta,novameta,
`justificativa` ,`anexo`,s.`situacao`,`datasolicitacao`, `dataEmanalise` , `codusuarioanalise`,
case s.situacao when 'A' then 'Aberta' 
when 'G' then 'Delegada' 
when'C' then 'Cancelada'
when 'I' then 'Indeferida'
when 'D' then 'Deferida'
end as situacaofinal
from solicitacaoPDU s left join mapaindicador mi on mi.codigo=s.codmapaind
left join indicador i on i.codigo=mi.codIndicador
left join meta a on a.CodMapaInd=mi.codigo
where s.codigo=:codSol
order by `datasolicitacao`";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codSol' => $codigo));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro SolicitacaoVersaoIndicador: " . $ex->getMessage();
    	}
    }
  
    public function buscaSolicitacaoPorMapaindAno($mapaind,$anobase) {
    	try {
    
    		$sql="SELECT * from solicitacaoPDU where codmapaind=:mapaind AND `anogestao`=:ano and situacao in ('A','G')";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':ano' => $anobase,':mapaind'=>$mapaind));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro listaTodas SolicitacaoVersaoIndicador por ano: " . $ex->getMessage();
    	}
    }
    
    public function deferir(SolicitacaoVersaoIndicador $svi) {
    	try {
    		$sql = "UPDATE `solicitacaoPDU` SET `situacao`=? WHERE `codigo`=?";
    		$stmt = parent::prepare($sql);
    		parent::beginTransaction();
    
    		$stmt->bindValue(1, $svi->getSituacao()); 
    		$stmt->bindValue(2, $svi->getCodigo()); 
    		//echo $svi->getSituacao()." xxx ".$svi->getCodigo()."-".$svi->getSituacao();die;
    		$stmt->execute();
    		parent::commit();
    		
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: deferir SolicitacaoVersaoIndicador:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
    	}
    }
           
    public function delegar(SolicitacaoVersaoIndicador $svi) {
    	try {
    		$sql = "UPDATE `solicitacaoPDU` SET `situacao`=?,`codusuarioanalise`=? WHERE `codigo`=?";
    		$stmt = parent::prepare($sql);
    		parent::beginTransaction();
    
    		$stmt->bindValue(1, $svi->getSituacao());
    		$stmt->bindValue(2, $svi->getUsuarioanalista()->getCodusuario());
    		$stmt->bindValue(3, $svi->getCodigo());
    
    		$stmt->execute();
    		parent::commit();
    	} catch (PDOException $ex) {
    		parent::rollback();
    		print "Erro: Delegar SolicitacaoVersaoIndicador:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
    	}
    }
   
    public function buscaSolicitacoesUser($anobase,$user) {
    	try {
    
    		$sql="SELECT s.`codigo`, `tipoSolicitacao`, s.`codunidade`, s.`coddocumento`,d.nome, s.`codmapaind`, s.`codindicador`,s.`codmapa`, s.`codobjetivo`,
    		 s.`codmeta`, `novameta`, s.`nome`, s.`calculo`, s.`interpretacao`, `justificativa`, s.`anexo`,s.`situacao`, `datasolicitacao`, `dataEmanalise`,
    		 `codusuarioanalise`, `anogestao`,i.`nome`, i.`calculo`, i.`interpretacao`
from solicitacaoPDU s
left join documento d on d.codigo=s.`coddocumento`
left join mapa ma  on d.codigo=ma.`coddocumento` and s.`codmapa`=ma.Codigo
left join objetivo o  on o.Codigo=s.`codobjetivo` and ma.codObjetivoPDI=s.`codobjetivo`
left join meta me on me.Codigo=s.`codmeta`
left join mapaindicador mi on mi.codigo=s.codmapaind
left join indicador i on i.codigo=mi.codIndicador
where  `anogestao`=:ano and codusuarioanalise=:user
order by s.situacao,s.`datasolicitacao` DESC";
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':ano' => $anobase,':user' => $user	));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro buscaTodasSolicitacoes: " . $ex->getMessage();
    	}
    }
    
    
    public function fechar() {
        PDOConnectionFactory::Close();
    }

}

?>

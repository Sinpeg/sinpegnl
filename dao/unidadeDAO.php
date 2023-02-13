<?php

class UnidadeDAO extends PDOConnectionFactory {

   // public $conex = null;

    /*
     * Tipo de unidade:
     * P: subunidade que tem produção na área de saúde
     * N: não é levada em conta
     * I: local interno
     * E: local externo
     */

    // constructor
   // public function UnidadeDAO() {
    //    $this->conex = PDOConnectionFactory::getConnection();
    //}

    public function Lista($query = null) {
        try {
            if ($query == null) {
                // executo a query
                $stmt = parent::query("SELECT * FROM unidade");
            } else {
                $stmt = parent::query($query);
            }
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro Lista". $ex->getCode() ;die;
            
        }
    }
    
    public function ListaRes($query = null) {
    	try {
    		if ($query == null) {
    			// executo a query
    			$stmt = parent::query("SELECT * FROM unidade WHERE tipo=1");
    		} else {
    			$stmt = parent::query($query);
    		}
    		return $stmt;
    	} catch (PDOException $ex) {
    	    echo "Erro ListaRes". $ex->getCode() ;die;
    	    
    	}
    }
    
    public function ListaRes2($query = null) {
    	try {
    		if ($query == null) {
    			// executo a query
    			$stmt = parent::query("SELECT * FROM unidade WHERE tipo=1 OR unidade_responsavel=1");
    		} else {
    			$stmt = parent::query($query);
    		}
    		return $stmt;
    	} catch (PDOException $ex) {
    	    echo "Erro ListaRes2". $ex->getCode() ;die;
    	    
    	}
    }
    
    public function ListaResponsavel() {
        try {
            
            // executo a query
            $stmt = parent::query("SELECT CodUnidade,NomeUnidade,hierarquia_organizacional,id_unidade
    			 FROM unidade WHERE  unidade_responsavel=1 and tipounidade<>'T' order by nomeunidade");
            
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro ListaResponsavel ". $ex->getCode() ;die;
        }
    }
    public function unidadeporcodigo($codigo) {
        try {
            // executo a query
            $stmt = parent::prepare("SELECT * FROM `unidade` WHERE `CodUnidade`=:codigo");
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro unidadeporcodigo ". $ex->getCode() ;die;
            
        }
    }
    /* busca por código da unidade responsável */
    public function queryByUnidadeResponsavel($value) {
        $sql = 'SELECT * FROM unidade WHERE `unidade_responsavel` = :codigo  ORDER BY `NomeUnidade`';
        $stmt = parent::prepare($sql);
        $stmt->execute(array(':codigo' => $value));
        return $stmt;
    }

    public function spgravaunidade($nome, $uniresp,
        $tipouni, $sigla,
        $siafi,$codigo,$perfil)
    {
        try {
            $r=1;
            $stmt = parent::prepare("CALL gravarUnidade(:nome,:uniresp,:iduniresp,:tipouni,:sigla,:siafi,:tipo,:codigo,:perfil)");
            
            $stmt->bindParam(':nome', $nome);//,parent::PARAM_STR,200);
            $stmt->bindParam(':uniresp',$uniresp );//,parent::PARAM_INT);
            $stmt->bindParam(':iduniresp',$uniresp);//,parent::PARAM_INT);
            $stmt->bindParam(':tipouni',$tipouni );//,parent::PARAM_INT );
            $stmt->bindParam(':sigla',$sigla );//,parent::PARAM_INT );
            $stmt->bindParam(':siafi',$siafi );//,parent::PARAM_INT );
            $stmt->bindParam(':tipo',$r);//,parent::PARAM_INT );
            $stmt->bindParam(':codigo',$codigo);//,parent::PARAM_INT );
            $stmt->bindParam(':perfil',$perfil);//,parent::PARAM_INT );
            
            $success = $stmt->execute();
            
            
            
            
            
            if($success){
                //$result = $stmt->fetchAll(parent::FETCH_ASSOC);
                //echo 'teste';
                
            }else{
                echo 'Erro na sp';
            }
            
            
            
            ?><div id="success">
        <img src="webroot/img/accepted.png" width="30" height="30"/>
        <?php print "Operação realizada com sucesso!";?>
    </div>

<?php
            } catch (PDOException $ex) {
               // parent::rollback();
                $string= "Erro procedure gravaunidade: " . $ex->getMessage();?>
                <div id="error">
        <img src="webroot/img/error.png" width="30" height="30"/>
        <?php print $string;?>
    </div>
<?php
            }
        }

    public function buscaidunidade($codigo) {
        try {
            // executo a query
            $stmt = parent::prepare("SELECT * FROM `unidade` WHERE `id_unidade`=:codigo");
            $stmt->execute(array(':codigo' => $codigo));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscaidunidade". $ex->getCode() ;die;
            
        }
    }
    
    public function buscaidunidadeRel($codigo) {
    	try {
    		// executo a query
    		$stmt = parent::prepare("SELECT * FROM `unidade` WHERE `CodUnidade`=:codigo");
    		$stmt->execute(array(':codigo' => $codigo));
    		return $stmt;
    	} catch (PDOException $ex) {
    		$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
    		print "Erro buscaidunidadeRel ".$mensagem;
    	}
    }

     public function buscasubunidadebib($hierarquia) {
	    	try {
	    		$hierarquia = addslashes($hierarquia)."%";
	    		// executo a query
	    		$sql = "SELECT `CodUnidade`, `NomeUnidade` FROM unidade WHERE `hierarquia_organizacional`LIKE :hier and  `NomeUnidade` like 'BIBLIOTECA%'";
	    		$stmt = parent::prepare($sql);
	    		// desconecta
	    		$stmt->execute(array(':hier' => $hierarquia));
	    		return $stmt;
	    	} catch (PDOException $ex) {
	    		echo "Erro buscasubunidadebib: " . $ex->getMessage();
	    	}
    }
    //aqui subunidade
    public function subunidadePorStr($str,$hier, $max) {
        try {
            // executo a query
            $stmt = parent::query("SELECT * FROM `unidade`
            WHERE codunidade<>100000
            AND unidade_responsavel<>1
            and `NomeUnidade` LIKE '%$str%'
            and hierarquia_organizacional like '$hier%'
            and tipounidade<>'T'
             LIMIT 0, $max");
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: subunidadePorStr " . $ex->getMessage();die;
            
        }
    }
    
    function buscarUnidadeByNome($nomeUnidade){
        $stmt = parent::prepare("SELECT `CodUnidade`, `id_unidade`, `codigo_unidade`, `unidade_responsavel`, `id_unid_resp_org`,
`hierarquia_organizacional`, `tipo`, `CodEstruturado`,
`NomeUnidade`, `TipoUnidade`, `CodInstituto`, `sigla`, `siafi`, `perfil`, NOW() as dtatual
FROM `unidade` WHERE trim(`NomeUnidade`) like :nomeUnidade");
        $stmt->execute(array(':nomeUnidade' => $nomeUnidade));
        return $stmt;
        
        
        
    }

    public function unidadePorStr($str, $max) {
        try {
            // executo a query
            $stmt = parent::query("SELECT * FROM `unidade` WHERE `NomeUnidade` <> 'ADMINISTRACAO DO SISTEMA' AND `NomeUnidade` LIKE '%$str%' LIMIT 0, $max");
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro unidadePorStr: " . $ex->getMessage();
            
        }
    }

    public function buscaUnidade($codunidade) {
        try {
            // executo a query
            $sql = "SELECT NomeUnidade, g.Codigo, Codaplicacao, hierarquia_organizacional,sigla FROM unidade u INNER JOIN grupounidade gu ON gu.Codunidade = u.CodUnidade INNER JOIN grupo g ON gu.Codgrupo = g.Codigo INNER JOIN aplicacoesdogrupo a ON a.Codgrupo = g.Codigo WHERE u.CodUnidade=:codigo ORDER BY Codaplicacao";
            $stmt = parent::prepare($sql);
            // desconecta
            $stmt->execute(array(':codigo' => $codunidade));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: buscaUnidade " . $ex->getMessage();die;
        }
    }

    public function buscahierarquia($codunidade) {
    	try {
    		// executo a query
    		$sql = "SELECT hierarquia_organizacional AS hierarquia FROM unidade WHERE `CodUnidade`=:codigo";
    		$stmt = parent::prepare($sql);
    		// desconecta
    		$stmt->execute(array(':codigo' => $codunidade));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro: buscahierarquia " . $ex->getMessage();die;
    	}
    }

    public function buscasubunidade($hierarquia) {
    	try {
    		$hierarquia = addslashes($hierarquia)."%";
    		// executo a query
    		$sql = "SELECT `CodUnidade`, `NomeUnidade` FROM unidade WHERE `hierarquia_organizacional`LIKE :hier";
    		$stmt = parent::prepare($sql);
    		// desconecta
    		$stmt->execute(array(':hier' => $hierarquia));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro: buscasubunidade " . $ex->getMessage(); die;
    	}
    }

//carla acrescentou
    public function buscalunidadeNsel( $hierarquia,$grupo) {
    	try {
    		if (!empty($hierarquia))
    		   $h = $hierarquia."%";
    			// executo a query
 $sql = "SELECT u.`CodUnidade` , u.`NomeUnidade`"
." FROM `unidade` u WHERE";

if (!empty($hierarquia)){
$sql .="  u.`hierarquia_organizacional` != :p1";
$sql .=" AND u.`hierarquia_organizacional` LIKE :p2 AND";
    }

$sql .="  NOT EXISTS (SELECT gu.`CodUnidade`"
." FROM grupounidade gu"
." WHERE gu.`CodUnidade` = u.`CodUnidade` and gu.Codgrupo=:g)"
		."  order by    u.`NomeUnidade`  ";



    			$stmt = parent::prepare($sql);

    			if (!empty($hierarquia)){
    			$stmt->execute(array( ':p1' => $hierarquia, ':p2'=> $h, ':g'=> $grupo)); //$subunidades -> hierarquia com aspas, $hierarquia -> hierarquia sem aspas
    			}else $stmt->execute(array(':g'=> $grupo));
    			return $stmt;

    	} catch (PDOException $ex) {
    		$mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
    		$cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
    		header($cadeia);
    	}
    }

    public function buscalunidade($parametro, $hierarquia) {
        try {
            $nome = strtoupper(addslashes($parametro));
            $temporario = strtoupper(addslashes($hierarquia));
            $subunidades = $temporario."%";
            if (is_string($nome)) {
                // executo a query
                $nome1 = "%". $nome .="%";
                $sql = "SELECT `CodUnidade`, `NomeUnidade` FROM `unidade` WHERE `hierarquia_organizacional` != :hiera AND `NomeUnidade` LIKE :nome AND `hierarquia_organizacional` LIKE :hier";
                $stmt = parent::prepare($sql);
                $stmt->execute(array(':nome' => $nome1, ':hier' => $subunidades, ':hiera'=> $hierarquia)); //$subunidades -> hierarquia com aspas, $hierarquia -> hierarquia sem aspas
                return $stmt;
            }
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }
    
    

    public function buscacodestruturado($parametro) {
        try {
            $p = strtoupper(addslashes($parametro));

            if (is_string($p)) {
                $posicao = strpos($p, ".00");
                // executo a query
                $sql = "SELECT `CodUnidade`, `NomeUnidade`,`CodEstruturado` FROM `unidade` WHERE  `CodUnidade` like :codigo";
                $stmt = parent::prepare($sql);
                $stmt->execute(array(':codigo' => $p));
                return $stmt;
            }
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function buscalocais($hier) {
        try {
            $sqla="";
            if ($hier=='.1.2.302.'){//para o ics acrecenta Barros barreto, que nao é unidade tipo I/E
                $sqla=" union 
                        SELECT `CodUnidade`, `NomeUnidade`,`TipoUnidade` FROM `unidade` 
                        WHERE   codunidade =2188";
            }
                
            $hier.='%';
            
        	//2015 - inclui Faculdade de Medicina como local de prestacao de serviço de saude
            // executo a query
            $sql = "SELECT `CodUnidade`, `NomeUnidade`,`TipoUnidade` FROM `unidade` 
            WHERE  (`TipoUnidade` in ('E','I') or codunidade =2015) and hierarquia_organizacional like :hier ";
            $sql.=$sqla;
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':hier' => $hier));
            
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscalocais: " . $ex->getMessage();die;
        }
    }

    function buscasubunidades($tipo, $codestruturado) {

        try {
            $subs = $codestruturado. "%";
            $sql = "SELECT `CodUnidade`, `NomeUnidade`,`hierarquia_organizacional` FROM `unidade` WHERE  `TipoUnidade`=:tipo and `hierarquia_organizacional` like :codestr";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':tipo' => $tipo, ':codestr' => $subs));
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    function buscaestruturado($codestruturado) {

        try {
            $subs = substr($codestruturado, 0, 8) . '%';
            $sql = "SELECT `CodUnidade`, `NomeUnidade`,`CodEstruturado` FROM `unidade` WHERE  `CodEstruturado` like :codestr";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codestr' => $subs));
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    function buscasubunidades00($codestruturado) {

        try {
          //  $fim = strpos($codestruturado, ".00.");
          //  $subs = substr($codestruturado, 0, $fim) . "%";
        	//echo $codestruturado;
        	$subs = $codestruturado."%";

            $sql = "SELECT `CodUnidade`, `NomeUnidade`,`hierarquia_organizacional` FROM `unidade` WHERE  `hierarquia_organizacional` like :codestr";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codestr' => $subs));
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function RetornaCodUnidadeSuperior($value) {
    	$sql = 'SELECT * FROM unidade WHERE `id_unidade` = :codigo';
    	$stmt = parent::prepare($sql);
    	$stmt->execute(array(':codigo' => $value));
    	return $stmt;
    }
    
    public function buscaUniResponsaveis() {
    	$sql = 'SELECT * FROM unidade WHERE unidade_responsavel=1 AND CodUnidade <> 939';
    	$stmt = parent::prepare($sql);
    	$stmt->execute();
    	return $stmt;
    }

    function buscaSubunidadesCodestruturado($tipo, $codestruturado) {

    try {
            //$subs = substr($codestruturado, 0, 8) . "%";
             $subs = $codestruturado."%";
            $sql  = "SELECT `CodUnidade`, `NomeUnidade`,`CodEstruturado`,`hierarquia_organizacional`  FROM `unidade` WHERE  `TipoUnidade`=:tipo and `hierarquia_organizacional` like :codestr";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':tipo' => $tipo, ':codestr' => $subs));
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }
    
    function buscarCodUnidadeByNome($nomeUnidade){
    	$stmt = parent::prepare("SELECT `CodUnidade` FROM `unidade` WHERE `NomeUnidade`=:nomeUnidade");
    	$stmt->bindValue(":nomeUnidade",$nomeUnidade);
    	$run = $stmt->execute();
    
    	$rs = $stmt->fetch(PDO::FETCH_ASSOC);
    
    	if($rs){
    		$codUnidade = $rs['CodUnidade'];
    		return $codUnidade;
    	}
    }

    public function fechar() {
        PDOConnectionFactory::Close();
    }

}

?>

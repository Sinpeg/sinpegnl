<?php

class CursoDAO extends PDOConnectionFactory {

    // irá receber uma conexão
    public $conex = null;

    
    public function spgravacurso($nomecurso,$campus,$unidade,$codsigaa,$codemec,$nivel,$situacao,
        $modalidade,$formato,$codigo)
    {
        try {
            $r=1;
            $stmt = parent::prepare("CALL gravarCurso(:nomecurso,:campus,:unidade,:codsigaa,:codemec,:nivel,:situacao,:modalidade,
                :formato,:codigo)");
            
            $stmt->bindParam(':nomecurso', $nomecurso);//,parent::PARAM_STR,200);
            $stmt->bindParam(':campus',$campus );//,parent::PARAM_INT);
            $stmt->bindParam(':unidade',$unidade);//,parent::PARAM_INT);
            $stmt->bindParam(':codsigaa',$codsigaa );//,parent::PARAM_INT );
            $stmt->bindParam(':codemec',$codemec );//,parent::PARAM_INT );
            $stmt->bindParam(':nivel',$nivel );//,parent::PARAM_INT );
            $stmt->bindParam(':situacao',$situacao);//,parent::PARAM_INT );
            $stmt->bindParam(':modalidade',$modalidade);//,parent::PARAM_INT );
            $stmt->bindParam(':formato',$formato);//,parent::PARAM_INT );
            $stmt->bindParam(':codigo',$codigo);//,parent::PARAM_INT );
            
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
                $string= "Erro procedure spgravacurso: " . $ex->getMessage();?>
                <div id="error">
        <img src="webroot/img/error.png" width="30" height="30"/>
        <?php print $string;?>
    </div>
<?php
            }
        }
        
        
         public function cursoPorStrUni($parametro, $anobase,$CodUnidade) {
            try {
                $parametro="%".$parametro."%";
                
                $sql = "SELECT cur.`CodCurso` , cam.`CodCampus` codcampus, cam.`Campus` nomecampus, cur.`CodUnidade` ,
                     cur.`CodNivel` , cur.`CodCursoSis` , cur.`NomeCurso` , cc.`idcursoinep` as CodEmec , cur.`DataInicio`
                     FROM `campus` cam
                     inner join  `curso` cur on cam.`CodCampus` = cur.`CodCampus`
                     left outer join `cursocenso` cc on cc.`idCursocenso` = cur.`CodEmec`  AND cc.Situacao=1
                     WHERE cur.CodUnidade =:codunidade
                     and cur.nomecurso like :par
                     AND cur.`Situacao` = 'A'"          ;
                
                if ($anobase<=2013){
                    $sql .=  "AND (cur.`AnoValidade`=2013 or cur.`Formato`='D')";//ano de validade do conjunto de cursos - mudança devido ao sigaa
                }else if(2014 >= $anobase && $anobase <= 2017 ){
                    $sql .= " AND cur.`AnoValidade` = 2014";
                }else if($anobase >= 2018){
                    $sql .= " AND cur.`AnoValidade` = 2018";
                }
                //$sql .=" order by cc.`idcursoinep`";
                
                $stmt = parent::prepare($sql);
                $stmt->execute(array(':codunidade' => $CodUnidade, ':par' => $parametro));//, ':anobase' => $anobase));
                return $stmt;
            } catch (PDOException $ex) {
                echo "Erro cursoPorStrUni: " . $ex->getMessage();
            }
        }
        
        
         public function cursoPorStr($parametro, $anobase) {
            try {
                $parametro="%".$parametro."%";
                $sql = "SELECT cur.`CodCurso` , cam.`CodCampus` codcampus, cam.`Campus` nomecampus, cur.`CodUnidade` ,
                     cur.`CodNivel` , cur.`CodCursoSis` , cur.`NomeCurso` , cc.`idcursoinep` as CodEmec , cur.`DataInicio`
                     FROM  `curso` cur 
                     left join `campus` cam on cam.`CodCampus` = cur.`CodCampus`
                     left  join `cursocenso` cc on cc.`idCursocenso` = cur.`CodEmec`  AND cc.Situacao=1
                     WHERE  trim(cur.nomecurso) like trim(:par)
                     AND cur.`Situacao` = 'A' "          ;
                
                if ($anobase<=2013){
                    $sql .= " AND (cur.`AnoValidade`=2013 or cur.`Formato`='D')";//ano de validade do conjunto de cursos - mudança devido ao sigaa
                }else if(2014 >= $anobase && $anobase <= 2017 ){
                    $sql .= " AND cur.`AnoValidade` = 2014";
                }else if($anobase >= 2018){
                    $sql .= " AND cur.`AnoValidade` = 2018";
                }
                
                $sql .= " limit 15";
                //$sql .=" order by cc.`idcursoinep`";
                
                $stmt = parent::prepare($sql);
                $stmt->execute(array( ':par' => $parametro));//, ':anobase' => $anobase));
                return $stmt;
            } catch (PDOException $ex) {
                echo "Erro cursoPorStr: " . $ex->getMessage();
            }
        }
        
         public function cursoPorNomeExato($parametro, $anobase) {
            try {
                $sql = "SELECT cur.`CodCurso` , cam.`CodCampus` codcampus, cam.`Campus` nomecampus, cur.`CodUnidade` ,
                     cur.`CodNivel` , cur.`CodCursoSis` , cur.`NomeCurso` , cc.`idcursoinep` as CodEmec , cur.`DataInicio`
                     FROM `campus` cam
                     inner join  `curso` cur on cam.`CodCampus` = cur.`CodCampus`
                     left outer join `cursocenso` cc on cc.`idCursocenso` = cur.`CodEmec`  AND cc.Situacao=1
                     WHERE  trim(cur.nomecurso) like trim(:par)
                     AND cur.`Situacao` = 'A'"          ;
                
                if ($anobase<=2013){
                    $sql .= "AND (cur.`AnoValidade`=2013 or cur.`Formato`='D')";//ano de validade do conjunto de cursos - mudança devido ao sigaa
                }else if(2014 <= $anobase && $anobase <= 2017 ){
                    $sql .= "AND cur.`AnoValidade` = 2014";
                }else if($anobase >= 2018){
                    $sql .= "AND cur.`AnoValidade` = 2018";
                }
                //$sql .=" order by cc.`idcursoinep`";
                $sql .=" limit 1";
                //echo $sql; die;
                $stmt = parent::prepare($sql);
                $stmt->execute(array( ':par' => $parametro));//, ':anobase' => $anobase));
                return $stmt;
            } catch (PDOException $ex) {
                echo "Erro cursoPorNomeExato: " . $ex->getMessage();
            }
        }
        
         public function listacursocenso() { //Inserido ano base 06/11/2018
            try {
                
                $stmt = parent::query("SELECT * FROM cursocenso where Situacao<>3");
                
                // retorna o resultado da query
                return $stmt;
            } catch (PDOException $ex) {
                echo "Erro lista cursocenso: " . $ex->getMessage();
            }
            
        }

    // realiza uma insert
    public function Insere($curso) {
        try {
            $stmt = parent::prepare("INSERT INTO agenda (id, nome, email, telefone) VALUES (?, ?, ?, ?)");
            // valores encapsulados nas variáveis da classe Curso.
            // sequencia de índices que representa cada valor de minha query
            $stmt->bindValue(1, $curso->getCodCurso());
            $stmt->bindValue(2, $curso->getCodCampus());
            $stmt->bindValue(3, $curso->getCodUnidade());
            $stmt->bindValue(4, $curso->getCodCursoSis());
            $stmt->bindValue(5, $curso->getNomeCurso());
            $stmt->bindValue(6, $curso->getCodEmec());
            $stmt->bindValue(7, $curso->getDataInicio());

            // executo a query preparada
            $stmt->execute();

            // fecho a conexão
            // caso ocorra um erro, retorna o erro;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    // realiza um Update
    public function Update($curso) {
        try {
            // preparo a query de update - Prepare Statement
            $stmt = parent::prepare("UPDATE agenda SET CodCampus=?, CodUnidade=?, CodCursoSis=?, NomeCurso = ?,CodEmec=?,DataInicio=? WHERE CodCurso=?");
            parent::beginTransaction();
            // valores encapsulados nas vari�veis da classe Curso.
            // sequencia de �ndices que representa cada valor de minha query
            $stmt->bindValue(1, $curso->getCodCampus());
            $stmt->bindValue(2, $curso->getCodUnidade());
            $stmt->bindValue(3, $curso->getCodCursoSis());
            $stmt->bindValue(4, $curso->getNomeCurso());
            $stmt->bindValue(5, $curso->getCodEmec());
            $stmt->bindValue(6, $curso->getDataInicio());
            $stmt->bindValue(7, $curso->getCodCurso());
            // executo a query preparada
            $stmt->execute();
            parent::commit();
            // caso ocorra um erro, retorna o erro;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    // remove um registro
    public function Deleta($id) {
        try {
            // executo a query
            $num = parent::exec("DELETE FROM agenda WHERE id=$id");
            // caso seja execuado ele retorna o n�mero de rows que foram afetadas.
            if ($num >= 1) {
                return $num;
            } else {
                return 0;
            }
            // caso ocorra um erro, retorna o erro;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function Lista() {
        try {

            $stmt = parent::query("SELECT * FROM curso");

            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaCurso($codcurso) {
        try {
            $stmt = parent::prepare("SELECT * FROM curso where Codcurso=:codcurso");

            $stmt->execute(array(':codcurso' => $codcurso));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscacursonivelcampus($codunidade,$anobase) { //Inserido ano base 06/11/2018
        try {
        	
        	$sql = "SELECT * FROM curso where CodUnidade=:codigo ";
        	if ($anobase<=2013){
        		$sql .= "AND (`AnoValidade`=2013 or `Formato`='D')";//ano de validade do conjunto de cursos - mudança devido ao sigaa
        	}else if(2014 <= $anobase && $anobase <= 2017 ){
        		$sql .= "AND `AnoValidade` = 2014";
        	}else if($anobase >= 2018){
        		$sql .= "AND `AnoValidade` = 2018 and codnivel=1";
        	}
        	
            $stmt = parent::prepare($sql);

            $stmt->execute(array(':codigo' => $codunidade));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }
   
    public function buscacursonivelcampus1($anobase,$codlab) { //Exibir todos os cursos
        try {
            
            $sql = "SELECT distinct c.`CodCurso`,  `CodCampus`, `CodUnidade`, `CodNivel`,
`CodCursoSis`, `NomeCurso`, `Sigla`, `CodOCDE`, `CodEmec`, idcursoinep,
 `CodSigaa`,  `TipoIntegracao`, c.`Situacao`, `AnoValidade`, `DataInicio`
FROM curso c  left join cursocenso cc on c.codemec=cc.idcursocenso where ";
            if ($anobase<=2013){
                $sql .= " (`AnoValidade`=2013 or `Formato`='D')";//ano de validade do conjunto de cursos - mudança devido ao sigaa
            }else if(2014 <= $anobase && $anobase <= 2017 ){
                $sql .= "  `AnoValidade` = 2014";
                $sql .= " and CodCurso not in (select c1.CodCurso
                    from laboratorio_curso c1
                    where c1.Codcurso=c.Codcurso and ano=2014 and codlaboratorio=:codlab)";
            }else if($anobase >= 2018){
                $sql .= "  `AnoValidade` = 2018 and codnivel=1 ";
                $sql .= " and c.CodCurso not in (select c1.CodCurso
                    from laboratorio_curso c1
                    where c1.Codcurso=c.Codcurso and ano=2018 and codlaboratorio=:codlab)";
            }
            $sql .= " order by NomeCurso";
            $stmt = parent::prepare($sql);
            
            $stmt->execute(array(':codlab' => $codlab));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro buscacursonivelcampus1: " . $ex->getMessage();
        }
    }
    
    public function buscaCursosUnidade($CodUnidade, $anobase) {
        try {
            $sql = "SELECT cur.`CodCurso` , cam.`CodCampus` codcampus, cam.`Campus` nomecampus, cur.`CodUnidade` ," .
                    " cur.`CodNivel` , cur.`CodCursoSis` , cur.`NomeCurso` , cc.`idcursoinep` as CodEmec , cur.`DataInicio` " .
                    " FROM `campus` cam ".
                    " inner join  `curso` cur on cam.`CodCampus` = cur.`CodCampus` " .
                    " left outer join `cursocenso` cc on cc.`idCursocenso` = cur.`CodEmec`  AND cc.Situacao=1  ".
                    " WHERE cur.CodUnidade =:codunidade " .
                   // " AND Year( cur.`DataInicio` ) <=:anobase " .
                  
                    " AND cur.`Situacao` = 'A'"          ;
            
                    if ($anobase<=2013){
                    	$sql .= "AND (cur.`AnoValidade`=2013 or cur.`Formato`='D')";//ano de validade do conjunto de cursos - mudança devido ao sigaa
                    }else if(2014 <= $anobase && $anobase <= 2017 ){
                    	$sql .= "AND cur.`AnoValidade` = 2014";
                    }else if($anobase >= 2018){
                    	$sql .= "AND cur.`AnoValidade` = 2018";
                    }
                    //$sql .=" order by cc.`idcursoinep`";
                    
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codunidade' => $CodUnidade));//, ':anobase' => $anobase));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function fechar() {
        parent::Close();
    }

}

?>
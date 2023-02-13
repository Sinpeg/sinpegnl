<?php

class LabcursoDAO extends PDOConnectionFactory {

    // ir� receber uma conex�o
    public $conex = null;
    public $anobase;
    // constructor
 
    public function __construct() {
        
    }
 
    // realiza uma inser��o
    public function insere($labcurso) {
        try {
            $stmt = parent::prepare("INSERT INTO `laboratorio_curso` (`CodCurso`, `CodLaboratorio`,ano) VALUES (?, ?,?)");
            parent::beginTransaction();
            $stmt->bindValue(1, $labcurso->getCurso()->getCodcurso());
            $stmt->bindValue(2, $labcurso->getLaboratorio()->getCodlaboratorio());
            $stmt->bindValue(3, $labcurso->getAno());
            
           
            
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
         
            echo "Erro: inser lab curso:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }

    // remove um registro
    public function deleta($CodLabCurso,$anobase) {
        try {
        	/*$sessao = $_SESSION["sessao"];
            $anobase=$sessao->getAnobase();
            if ($anobase<2018){ $anobase=2014;}
            else {
               if ($anobase>2018 && $anobase<2021){ $anobase=2018; }
               else $anobase=2021;
           }*/
            parent::beginTransaction();
            $stmt = parent::prepare("DELETE FROM `laboratorio_curso` WHERE `CodLabCurso`=? ");
            $stmt->bindValue(1, $CodLabCurso);
      /*    $stmt->bindValue(2, $anobase);*/
            
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaCursoLaboratorio($codcurso, $codlab) {
        try {
        	$sessao = $_SESSION["sessao"];
        $anobase=$sessao->getAnobase();
            $sql = "SELECT * FROM `laboratorio_curso`  " .
                    "where `CodLaboratorio`=:codigo and `CodCurso`=:codcurso ";
            if ($anobase<2018){
               $sql.=" and ano=2014";
              
            }
            else if  ($anobase>=2021){
                $sql.=" and ano=".$anobase;
            }
                  
              else  $sql.=" and ano=2018";
              $stmt = parent::prepare($sql);
              $stmt->execute(array(':codigo' => $codlab, ':codcurso' => $codcurso));
              
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function Lista() {
        try {
        $sessao = $_SESSION["sessao"];
        $anobase=$sessao->getAnobase();
        $sql="SELECT * FROM laboratorio_curso ";
             if ($anobase<2018)
            $sql.=" where ano=2014";
            else $sql.=" where ano=2018";
            $stmt = parent::query($sql);
            
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaCursosLaboratorio($lab) {
        try {
        	$sessao = $_SESSION["sessao"];
            $anobase=$sessao->getAnobase();
            $sql = "SELECT t1.`CodCampus`,t1.`Campus`,t2.`CodCurso`,t2.`CodCursoSis`,t2.`DataInicio`,t2.`NomeCurso`,
t2.`CodEmec`, t3.`CodLabCurso`
 FROM  `laboratorio_curso`  t3
            join  `curso` t2 on  t2.`CodCurso`=t3.`CodCurso`
            left join `campus` t1  on  t1.`CodCampus`=t2.`CodCampus`
 where t3.`CodLaboratorio`=:codigo  ";
            
            
        
              if ($anobase<2018)
               $sql.=" and t2.anovalidade=2014";
               else if ($anobase<2021){
                    $sql.=" and t2.anovalidade=2018";
               }else{
                   $sql.=" and t2.anovalidade=2021";
                   
               }
             //  echo $sql;die;
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $lab->getCodlaboratorio()));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    
    
    public function buscaCursosLaboratorio2($lab,$anobase) {
        try {
            $sql = "SELECT distinct t2.`CodCurso`,t2.`CodCursoSis`,t2.`DataInicio`,t2.`NomeCurso`,t2.`CodEmec`,
                    t3.`CodLabCurso`,idcursoinep
 FROM `curso` t2,cursocenso cc,`laboratorio_curso`  t3  
 where t3.`CodLaboratorio`=:codigo 
 and t2.`CodCurso`=t3.`CodCurso` and codemec=idcursocenso";
            if ($anobase<2018)
               $sql.=" and t3.ano=2014";
              else if( $anobase>=2021)
                  $sql.=" and t3.ano=".$anobase;
              else
                  $sql.=" and t3.ano=2018";
              //echo $sql;
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $lab->getCodlaboratorio()));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }
    public function buscaCursosLaboratorio1($codlab, $ano) {
        $sql1="";$sql2="";
        try {
            if ($ano<2021){
                $sql1=" and t3.ano<2021 ";//vai ser obrigado vincular a cada ano
            }else{
                $sql2=" and (t0.situacao ='A')";  
            }
            $sql = "SELECT t1.`CodCampus`,t1.`Campus`,t2.`CodCurso`,t2.`CodCursoSis`,t2.`DataInicio`,t2.`NomeCurso`,t2.`CodEmec`," .
                    " t3.`CodLabCurso`" .
                    " FROM laboratorio t0
                       join   `laboratorio_curso`  t3 on t0.codlaboratorio=t3.codlaboratorio ".$sql1.
                    " join `curso` t2 on t2.`CodCurso`=t3.`CodCurso`
                    left join `campus` t1 on t1.`CodCampus`=t2.`CodCampus`
                     where t3.`CodLaboratorio`=:codigo 
                     ".$sql2;
          
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codlab));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaLaboratoriosUnidade($codunidade) {
        try {
            $stmt = parent::prepare("SELECT * FROM laboratorio_curso where CodUnidade=:codunidade ");
            if ($anobase<2018)
               $sql.=" and ano=2014";
              else $sql.=" and ano=2018";
            $stmt->execute(array(':codunidade' => $codunidade));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function fechar() {
        PDOConnectionFactory::Close();
    }

}

?>
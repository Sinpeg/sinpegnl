<?php

class EnsinoeaDAO extends PDOConnectionFactory {

    // irá receber uma conexão
    private $conex = null;

    // constructor
  /*  public function EnsinoeaDAO() {
        $this->conex = PDOConnectionFactory::getConnection();
    }
*/
    public function ListaEm($anobase) {
        try {
        if ($anobase<2018){
            $stmt = parent::query("SELECT * FROM `tdm_ensino_ea` where `Ensino` IN ( 'MEG','MRN', 'MJA') order by `Codigo`");
        	}else {
            $stmt = parent::query("SELECT * FROM `tdm_ensino_ea` where `Ensino` IN ('MEG','MML','MMI') order by `Codigo`");
        	}
            // retorna o resultado da query
            return $stmt;
        }catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			echo "Erro em listaem ".$ex->getCode()."&mensagem=".$mensagem;
		}
    }

    public function ListaEf($anobase) {
        try {
        	if ($anobase<2018){
            $stmt = parent::query("SELECT * FROM `tdm_ensino_ea` where  `Ensino`  in ('FUS','FIN') order by `Ensino`, `Codigo`");
        	}else {
            $stmt = parent::query("SELECT * FROM `tdm_ensino_ea` where  Ensino in ('FUN','FIN') order by `Ensino`, `Codigo`");
        	}
            // retorna o resultado da query
            return $stmt;
        }catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			echo "Erro em listaef ".$ex->getCode()."&mensagem=".$mensagem;
		}
    }

    public function buscaensinomedio($ano) {
        try {
         if ($ano<2018){
            $sql="SELECT e.`Codigo`, `Codtdmensinoea`, `Matriculados`, `Aprovados`, `Reprovados`, `Ano` FROM `ensino_ea` e left join tdm_ensino_ea t on (t.Codigo=Codtdmensinoea and e.Ano=:ano) where `Ensino` IN ( 'MEG','MRN', 'MJA') order by `Codtdmensinoea`";
            //echo $sql;
        	}else {
            $sql="SELECT e.`Codigo`, `Codtdmensinoea`, `Matriculados`, `Aprovados`, `Reprovados`, `Ano` FROM `ensino_ea` e left join tdm_ensino_ea t on (t.Codigo=Codtdmensinoea and e.Ano=:ano) where `Ensino` IN ('MEG','MML','MMI')  order by `Codtdmensinoea`";
            //echo $sql;
        	}
            
            $stmt = parent::prepare($sql);
            
            $stmt->execute(array(':ano' => $ano));
            // retorna o resultado da query
            return $stmt;
          }catch ( PDOException $ex ){
			$mensagem = urlencode($ex->getMessage());//para aceitar caracteres especiais
			echo "Erro em buscaensinomedio ".$ex->getCode()."&mensagem=".$mensagem;
		}
    }

    /** modificado em 17/08 */
    public function buscaensinofund($ano) {
        try {
           // $sql = "SELECT * FROM `ensino_ea` where `Codtdmensinoea`>=9 and `Codtdmensinoea`<=29 and `Ano`=:ano order by `Codtdmensinoea`";
            if ($ano<2018){
            $sql="SELECT e.`Codigo`, `Codtdmensinoea`, `Matriculados`, `Aprovados`, `Reprovados`, `Ano` FROM   tdm_ensino_ea t inner join  `ensino_ea` e  on (t.Codigo=Codtdmensinoea and e.Ano=:ano) where `Ensino` IN ( 'FUS','FIN') order by `Codtdmensinoea`";
            //echo $sql;
        	}else {
            $sql="SELECT e.`Codigo`, `Codtdmensinoea`, `Matriculados`, `Aprovados`, `Reprovados`, `Ano` FROM  tdm_ensino_ea t inner join  `ensino_ea` e on (t.Codigo=Codtdmensinoea and e.Ano=:ano) where `Ensino` IN ('FUN','FIN')  order by `Codtdmensinoea`";
            //echo $sql;
        	}
            
            
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':ano' => $ano));
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            $mensagem = urlencode($ex->getMessage()); //para aceitar caracteres especiais
            $cadeia = "location:../saida/erro.php?codigo=" . $ex->getCode() . "&mensagem=" . $mensagem;
            header($cadeia);
        }
    }

    public function inseretodos($teas) {
        try {
            parent::beginTransaction();
            //$tamanho=count($teas);
            //for ($i=1;$i<=$tamanho;$i++){

            $sql = "INSERT INTO `ensino_ea` (`Codtdmensinoea`,`Matriculados`,`Aprovados` ,`Reprovados`,`Ano`)  VALUES (?,?,?,?,?)";
            foreach ($teas as $t) {
                $stmt = parent::prepare($sql);
                $stmt->bindValue(1, $t->getCodigo());
                echo $t->getCodigo()."<br>";
                $stmt->bindValue(2, $t->getEnsinoea()->getMatriculados());
                $stmt->bindValue(3, $t->getEnsinoea()->getAprovados());
                $stmt->bindValue(4, $t->getEnsinoea()->getReprovados());
                
                $stmt->bindValue(5, $t->getEnsinoea()->getAno());
                $stmt->execute();
                
            }
            parent::commit();
            echo "passou<br>";
        } catch (PDOException $ex) {
            parent::rollback();
            echo "Erro em inseretodos ".$ex->getCode()."&mensagem=".$mensagem;
        }
        //criar variavel gravou e retornar para fazer a consultaemedio pegar.
    }

    public function alteratodos($tea) {
        try {
            $tamanho = count($tea);
          // echo "COMECO".$tamanho."aqui<br>";
            parent::beginTransaction();
            $sql = "UPDATE `ensino_ea` SET `Matriculados`=?,`Aprovados`=?,`Reprovados`=?  WHERE `Codigo`=?";
            //echo $tea[$tamanho]->getEnsinoea()->getCodigo()."codigo11<br>";
            for ($cont = 1; $cont <= $tamanho; $cont++) {
                if ($tea[$cont]->getEnsinoea() != null) {
                    $stmt = parent::prepare($sql);
                    $stmt->bindValue(1, $tea[$cont]->getEnsinoea()->getMatriculados());
                   // echo $tea[$cont]->getEnsinoea()->getMatriculados()."cont=".$cont."cont";
                    $stmt->bindValue(2, $tea[$cont]->getEnsinoea()->getAprovados());
                    //echo $tea[$cont]->getEnsinoea()->getAprovados()."---";
                    $stmt->bindValue(3, $tea[$cont]->getEnsinoea()->getReprovados());
                    //echo $tea[$cont]->getEnsinoea()->getReprovados()."--";
                    $stmt->bindValue(4, $tea[$cont]->getEnsinoea()->getCodigo());
                    //echo $tea[$cont]->getEnsinoea()->getCodigo()."---<br>";
                    $stmt->execute();
                }
            }
            
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            echo "Erro em alteratodos ".$ex->getCode()."&mensagem=".$mensagem;
        }
    }

    public function fechar() {
        PDOConnectionFactory::Close();
    }

}

?>

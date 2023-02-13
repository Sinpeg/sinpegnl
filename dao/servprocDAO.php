<?php

class ServprocDAO extends PDOConnectionFactory 	{

    // irá receber uma conexão
    public $conex = null;

    // constructor
   //public function ServprocDAO() {
   //     $this->conex = PDOConnectionFactory::getConnection();
    //}

    public function buscaservproced($servico, $subunidade) {
        try {
            $sql = "SELECT `CodServProc` , sp.`CodServico` , sp.`CodProced` , p.`Nome`" .
                    " FROM `servico` s,`procedimento` p, `servproced` sp " .
                    " WHERE  (s.`Codigo` = sp.`CodServico`) " .
                    " AND  (p.`CodProcedimento` = sp.`CodProced`) " .
                    " AND sp.`CodServico` =:pservico " .
                    " AND s.`CodSubunidade`=:psubunidade" .
                    " ORDER BY p.`Nome`";

            $stmt = parent::prepare($sql);
            $stmt->execute(array(':pservico' => $servico, ':psubunidade' => $subunidade));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscacodservproced($servico, $subunidade, $procedimento) {
        try {
            $sql = "SELECT `CodServProc` , sp.`CodServico` , sp.`CodProced` , p.`Nome`" .
                    " FROM `servico` s,`procedimento` p, `servproced` sp " .
                    " WHERE  (s.`Codigo` = sp.`CodServico`) " .
                    " AND  (p.`CodProcedimento` = sp.`CodProced`) " .
                    " AND sp.`CodServico` =:pservico " .
                    " AND p.`CodProcedimento` =:pproced " .
                    " AND s.`CodSubunidade`=:psubunidade";

            $stmt = parent::prepare($sql);
            $stmt->execute(array(':pservico' => $servico, ':psubunidade' => $subunidade, ':pproced' => $procedimento));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaservproced1($subunidade) {
        try {
            $sql = "SELECT `CodServproc` , sp.`CodServico` , sp.`CodProced` , p.`Nome`" .
                    " FROM `servico` s,`procedimento` p, `servproced` sp " .
                    " WHERE  (s.`Codigo` = sp.`CodServico`) " .
                    " AND  (p.`CodProcedimento` = sp.`CodProced`) " .
                    " AND s.`CodSubunidade`=:psubunidade";

            $stmt = parent::prepare($sql);
            $stmt->execute(array(':psubunidade' => $subunidade));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

  
    public function buscaservproced2($subunidade, $local, $ano) {
        try {
            $sql = "SELECT s.Codigo, ps.Codigo as CodigoProdSaude, p.CodProcedimento, us.NomeUnidade AS Subunidade, ups.NomeUnidade AS Localizacao, s.Nome AS nomeServico, p.Nome AS nomeProcedimento, ps.Mes, ps.Ano, ps.ndiscentes, ps.ndocentes, ps.npesquisadores, ps.npessoasatendidas,ps.nprocedimentos
                FROM psaudemensal ps, servproced sp, procedimento p, servico s, unidade us, unidade ups
                WHERE s.Codigo = sp.CodServico
                AND sp.CodProced = p.CodProcedimento
                AND ps.CodServProc = sp.CodServProc
                AND ps.CodLocal = ups.CodUnidade
                AND s.CodSubunidade = us.CodUnidade
                AND s.CodSubunidade = :subunidade
                AND ps.CodLocal = :local
                AND ps.Ano = :ano
                ORDER BY nomeServico, nomeProcedimento, Mes";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':subunidade' => $subunidade, ':local' => $local, ':ano' => $ano));
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro: " . $ex->getMessage();
        }
    }

   
    public function buscaservproced3($subunidade, $ano) {
        try {
            $sql = "SELECT s.Codigo, ps.Codigo as CodigoProdSaude, p.CodProcedimento,
                   s.Nome AS nomeServico, p.Nome AS nomeProcedimento, ps.Mes, ps.Ano, ps.npessoasatendidas, ps.nexames
                    FROM psaudemensal ps, servproced sp, procedimento p, servico s
                    WHERE
                    ps.CodServProc = sp.CodServProc
                    AND s.Codigo = sp.CodServico
                    AND p.CodProcedimento = sp.CodProced
                    AND s.CodSubunidade = :subunidade
                    AND ps.Ano = :ano";
            //$stmt = $this->conex->prepare($sql);
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':subunidade' => $subunidade, ':ano' => $ano));
            return $stmt;
        } catch (PDOException $ex) {
            print "Erro: " . $ex->getMessage();
        }
    }

    public function buscaprocedimentos($codunidade) {
        try {

            $stmt = parent::prepare("SELECT * FROM `procedimentos` WHERE `CodUnidade` = :codigo and `Codsubunidade` is null");
            $stmt->execute(array(':codigo' => $codunidade));
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
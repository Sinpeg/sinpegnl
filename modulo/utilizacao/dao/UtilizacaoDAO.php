<?php

class UtilizacaoDAO extends PDOConnectionFactory {

    private $conex = null;

    // constructor
      public function __construct() {
       // $this->conex = PDOConnectionFactory::getConnection();
    }

    public function listaAplicacoes() {
        try {
            $sql = "-- Seleciona as aplicações com as unidades \n"
                    . "SELECT u.`CodUnidade`, `NomeUnidade`, a.`Codigo`, a.`Nome` FROM \n"
                    . "`aplicacao` a,\n"
                    . "`grupo` g,\n"
                    . "`grupounidade` gu,\n"
                    . "`unidade` u,\n"
                    . "`aplicacoesdogrupo` ag\n"
                    . "WHERE\n"
                    . "ag.`Codgrupo` = g.`Codigo`\n"
                    . "and ag.`Codaplicacao` = a.`Codigo`\n"
                    . "and gu.`Codgrupo` = g.`Codigo` \n"
                    . "and gu.`Codunidade` = u.`CodUnidade`\n"
                    . "and a.`Codigo` not in (3,23,29,31,32,33,34,35,36,37,38,39,40,41,42,43,44) \n"
                    . "order by u.`nomeUnidade`, a.`Nome`\n"
                    . "";
            $stmt = parent::query($sql);
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function consulta($sql) {
        try {
//            echo $sql . "<br/>";
            $stmt = parent::query($sql);
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
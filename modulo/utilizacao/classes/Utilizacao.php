<?php

class Utilizacao {

    private $config = array();

    public function condfigure($codaplicacao, $table, array $params = null, $query=null) {
        if (!isset($query) && !isset($params)) {
            $sql = "SELECT * FROM `$table`";
        } 
        else if ($query!=null) {
          $sql = $query;
        }
        else if (isset($params)) {
            $sql = "SELECT * FROM `$table`\n";
            $sql .= "WHERE \n";
            $and = FALSE;
            foreach ($params as $key => $value) {
                if (!$and) {
                    $sql .= $key . " " . $value . "\n";
                    $and = TRUE;
                } else {
                    $sql .= "\nAND" . $key . " " . $value;
                }
            }
        }
        $this->config[$codaplicacao] = array(
            'query' => $sql
        );
    }

    public function getConfig(){
        return $this->config;
    }

}

?>

<?php

class Grafico {

    private $dados; // vetor associativo contendo os dados
    private $nLinha; // número de linhas
    private $nCol; // número de colunas

    public function __construct($dados) {
        $this->dados = $dados;
        $this->nLinha = ceil(count($dados, 0));
        $this->nCol = ceil((count($dados, 1) / count($dados, 0)) - 1);
    }

    public function ajusteDados() {
        // duas colunas (eixo)
        // configuração possível para três colunas disponíveis na consulta
        // neste caso o terceiro valor (quantidade) é atribuído ao vetor associativo
        $dados = $this->dados;
        if ($this->nCol == 2) {
            foreach ($dados as $chave => $valor) {
                foreach ($valor as $chave1 => $valor1) {
                    $colunas[$chave1] = $valor1; // as categorias que existem no array associativo
                }
            }
            // itera nas categorias
            foreach ($dados as $chave => $valor) {
                foreach ($colunas as $k => $v) {
                    if ($dados[$chave][$k] == NULL) {
                        $dados[$chave][$k] = 0.00;
                    }
                }
            }
        }
        $dados1 = $this->pivot($dados);
        return $dados;
    }

    // inverte as colunas pelas linhas
    private function pivot($dados) {
        $linhas = ceil(count($dados, 0));
        $cols = ceil((count($dados, 1) / count($dados, 0)) - 1);
        $dados1 = array();
        if ($linhas < $cols) {
            foreach ($dados as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $dados1[$key1][$key] = $value1;
                }
            }
            return $dados1;
        } else {
            return $dados;
        }
    }
    public function getData() {
        $dados = $this->dados;
        $data = array(); /* dados prontos para o highcharts */
        $nseries = 0;
        $ncat = 0;
        /* Caso haja mais de uma coluna nos resultados */
        if ($this->nCol > 0) {
            $series = array();
            $categorias = array();
            /* itera nas séries */
            foreach ($dados as $key => $value) {
                $series[] = $key; // seleciona os nomes das séries
                $nseries++;
                /* itera nas categorias */
                foreach ($value as $key1 => $value1) {
                    $categorias[$key1] = $key1;
                    $ncat++;
                }
            }
            /* recuperação dos dados e coerção de valores nulos para 0. */
            $data[0] = array(
                "name" => 'Série',
                "data" => $series
            );
            $cont = 1;
            $j = 0;   
            foreach ($categorias as $cat) {
                $tupla = array();
                for ($i = 0; $i < $nseries; $i++) {
                    if ($dados[$series[$i]][$cat] == NULL) {
                        $tupla[$i] = 0;
                    } else
                        $tupla[$i] = $dados[$series[$i]][$cat];
                }
                $data[$cont] = array(
                    "name" => $cat,
                    "data" => $tupla
                );
                $cont++;
            }
        }
        else {
            $categorias = array();
            $dados1 = array();
            foreach ($dados as $key => $value) {
                $categorias[] = "$key";
                $dados1[] = $value;
            }
            $data[0] = array(
                "name" => 'Série',
                "data" => $categorias
            );
            $data[1] = array(
                "name" => "Simple",
                "data" => $dados1
            );
        }
        return $data;
    }

    public function getDataPie() {
        $dados = $this->dados;
        $data = array();
        $i = 0;

        foreach ($dados as $key => $value) {
            $data[$i][0] = "$key";
            $data[$i][1] = $value;
            $i++;
        }
        $data1[0] = array(
            'type' => "pie",
            'data' => $data
        );

        return $data1;
    }
    
    public function isDataValid($type, $data) {
        $dados = array();
        $contzero = 0; // contador de zeros
        $contTotal = 0; // todos os elementos
        switch ($type) {
            case "pie": // tipo de gráfico for pizza
                $dados = $data[0]["data"];
                foreach ($dados as $data1) {
                    if ($data1[1]==0 || $data1[1]==0.0)
                        $contzero++;
                    $contTotal++;
                }
                if ($contTotal-$contzero>1) {
                    return true;
                }
                else {
                    return false;
                }
                break;
            case "column": // caso o tipo de gráfico for coluna
               if ($this->nCol > 0) {
                   $contZero = 0; // total de zeros
                   $dados = array();
                   for ($i=1; $i<count($data); $i++) {
                       $j = count($data[$i]["data"]);
                       for ($k=0; $k<$j; $k++) {
                           $data1[] = $data[$i]["data"][$k];
                           if ($data[$i]["data"][$k]==0 || $data[$i]["data"][$k]==0.0) {
                               $contZero++; // incrementa
                           }
                       }
                   }
                   // se o array só possuir zero, então não plota
                   if (count($data1)-$contZero<=1) {
                       return false;
                   }
                   else {
                       return true;
                   }
               }
               else {
                   $contZero = 0;
                   for ($i=0; $i<count($data[1]["data"]); $i++) {
                       if (count($data[1]["data"])==0 || count($data[1]["data"])==0.0) {
                           $contZero++;
                       }
                   }
                   if ((count($data[1]["data"])-$contZero)>=2) {
                       return true;
                   }
                   else {
                       return false;
                   }
               }
                break;
        }
    }
    
    

}

?>

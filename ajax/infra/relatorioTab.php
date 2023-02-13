<?php

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../modulo/infra/dao/infraDAO.php';
require_once '../../modulo/infra/dao/tipoinfraDAO.php';
require_once '../../util/Grafico.php'; // classe para auxiliar na criação de gráficos
?>
<?php

/* grant */
sleep(1);
require_once '../../classes/sessao.php';
session_start();
$sessao = $_SESSION["sessao"];
if (!isset($sessao)) {
    exit(0);
} else {
    $aplicacoes = $sessao->getAplicacoes();
    if (!$aplicacoes[8]) { // Infra
        exit(0);
    }
}
?>
<?php

$pesquisa = filter_input(INPUT_GET, 'pesquisa', FILTER_SANITIZE_ENCODED); /* tipo de pesquisa */
$anounico = filter_input(INPUT_GET, 'anounico', FILTER_SANITIZE_ENCODED); /* ano único de pesquisa */
$ano = filter_input(INPUT_GET, 'ano', FILTER_SANITIZE_ENCODED); /* primeiro ano da série */
$ano1 = filter_input(INPUT_GET, 'ano1', FILTER_SANITIZE_ENCODED); /* segundo ano da série */
$agrupamento = filter_input(INPUT_GET, 'agrupamento', FILTER_SANITIZE_ENCODED); /* tipo de agrupamento */
$campovalor = filter_input(INPUT_GET, 'campovalor', FILTER_SANITIZE_ENCODED); /* campo para valor */
$tchart = filter_input(INPUT_GET, 'tipografico', FILTER_SANITIZE_ENCODED); /* tipo do gráfico */
$pcd = filter_input(INPUT_GET, 'pcd', FILTER_SANITIZE_ENCODED); /* pcd */
$modalidade = filter_input(INPUT_GET, 'modalidade', FILTER_SANITIZE_ENCODED); /* se a infraestrutura é a distância */
$situacao = filter_input(INPUT_GET, 'situacao', FILTER_SANITIZE_ENCODED); /* situação da infraestrutura */
$tipoinfra = filter_input(INPUT_GET, 'tipoinfra', FILTER_SANITIZE_ENCODED); /* tipo da infraestrutura */
$txtUnidade = filter_input(INPUT_GET, 'txtUnidade', FILTER_SANITIZE_STRING); /* nome da unidade */
////////////////////////////////////////////////////////////////////////////
// Validação de alguns campos do formulário
////////////////////////////////////////////////////////////////////////////
$array_agrupamento = array("INSTITUTO", "CAMPUS", "ESCOLA", "HOSPITAL", "PCD", "MODALIDADE", "TIPO");
if (!isset($pesquisa)) {
    $erro = "Selecione o critério de pesquisa: anual ou série histórica";
} else if ($pesquisa == "serie" && !preg_match("/^[1-9][0-9]{3,3}$/", $ano)) {
    $erro = "O <strong> ano inicial</strong> deve conter 4 dígitos e não iniciar com 0 (Ex.: 2000)";
} else if ($pesquisa == "serie" && !preg_match("/^[1-9][0-9]{3,3}$/", $ano1)) {
    $erro = "O <strong> ano final</strong> deve conter 4 dígitos e não iniciar com 0 (Ex.: 2000)";
} else if ($pesquisa == "serie" && $ano > $ano1) {
    $erro = "O <strong> ano final</strong> deve ser maior ou igual ao <strong> ano inicial</strong>";
} else if (!in_array($agrupamento, $array_agrupamento)) {
    $erro = "Selecione o campo para agrupamento";
} else if ($campovalor != "1" && $campovalor != "2" && $campovalor != "3") {
    $erro = "Selecione o campo para valor";
} else if ($situacao != "A" && $situacao != "D") {
    $erro = "Selecione a situação da infraestrutura";
} else if ($txtUnidade != "" && !preg_match("/^[a-zA-Z]+([a-zA-Z\s]*)[a-zA-Z]+$/", $txtUnidade)) {
    $erro = "Formato inválido para a pesquisa da unidade";
}
if (empty($erro)) {
    $daoinfra = new InfraDAO();
    // Tipo de consulta: anual
    if ($pesquisa == "anual") {
        $stmt = $daoinfra->buscaInfraGrafico($anounico, $agrupamento, $campovalor, $txtUnidade, $pcd, $modalidade, $situacao, $tipoinfra);
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            if ($stmt->columnCount() == 2) {
                // o trecho a seguir avalia o título de cada colunas
                // importante para gerar o título dos gráficos
                $ncol = $stmt->columnCount();
                $header = array(); // cabeçalhos
                for ($i = 0; $i < $ncol; $i++) {
                    $array = $stmt->getColumnMeta($i);
                    $header[$i] = $array["name"];
                }
                $dados[$row[0]] = $row[1] + 0;
                if ($tchart == "pizza") {
                    $tipografico = "pie"; // gráfico de pizza
                } else {
                    $tipografico = "column"; // série simples sem categorias
                }
            } else {
                $dados[$row[0]][$row[1]] = $row[2] + 0; // gráfico de barras
                $tipografico = "bar";
            }
        }
    }
    // tipo de pesquisa: série histórica
    // neste caso são avaliados todos os os anos incluídos na série informada
    else if ($pesquisa == "serie") {
        $tipografico = "column"; // neste caso não é possível mostrar o gráfico de pizza
        for ($i = 0; $i <= ($ano1 - $ano); $i++) { // avaliação em cada ano
            $avalia = $ano + $i; // ano de avaliação na iteração
            $stmt = $daoinfra->buscaInfraGrafico($avalia, $agrupamento, $campovalor, $txtUnidade, $pcd, $modalidade, $situacao, $tipoinfra);
            if (!$stmt) {
                $erro = "A pesquisa não retornou resultado, provavelmente falta algum parâmetro.";
            } else {
                if ($stmt->columnCount() > 2) {
                    $erro = "Não foi possível gerar o gráfico pois há muitos parâmetros selecionados";
                }
                if ($stmt->rowCount() == 0) {
                    $erro = "A pesquisa não retornou resultado!";
                }
                if ($stmt->columnCount() == 2) {
                    // o trecho a seguir avalia o título de cada coluna retornada na 
                    // pesquisa. Esta ação é importante para gerar o título do gráfico
                    $ncol = $stmt->columnCount(); // número de colunas
                    $header = array(); // array de títulos
                    for ($j = 0; $j < $ncol; $j++) { // iteração no número de colunas
                        $array = $stmt->getColumnMeta($j); // recebe o meta dados da coluna
                        $header[$j] = $array["name"]; // adiciona os cabeçalhos
                    } // fim
                    // somente é possível gráfico de barras
                    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                        $dados[$avalia][$row[0]] = $row[1] + 0;
                    }
                }
            }
        }
    }
}
$json = array();
// não foram encontrados erros
// pode apresentar o gráfico
if (empty($erro)) {
    $isfloat = ($campovalor == "2") ? true : false;
    $grafico = new Grafico($dados); // instancia o objeto
    $grafico->ajusteDados(); // ajuste dos dados
    $data = array();
    if ($tipografico == "column" || $tipografico == "bar") {
        $data = $grafico->getData();
    } else {
        $data = $grafico->getDataPie();
    }
    $header_aux = array_reverse($header);
    $title = implode($header_aux, ' '); // gera o título
    ////////////////////////////////////////////////////////////////////////////
    // Configuração do subtitle
    ////////////////////////////////////////////////////////////////////////////

    $subtitle_array = array();

    // Unidade Específica
    $unidades = array("INSTITUTO", "CAMPUS", "ESCOLA", "HOSPITAL");
    if (!in_array($agrupamento, $unidades) && !empty($txtUnidade)) {
        $subtitle_array[] = $txtUnidade;
    }

    // PCD
    if ($agrupamento != "PCD" && $pcd == "S" || $pcd == "N") {
        $pcd_aux = ($pcd == "S") ? " com PCD" : " sem PCD";
        $subtitle_array[] = $pcd_aux;
    }

    // Modalidade
    if ($agrupamento != "MODALIDADE" && ($modalidade == "1" || $modalidade == "2" && $modalidade == "3")) {
        switch ($modalidade) {
            case "1":
                $mod = "presencial";
                break;
            case "2":
                $mod = "à distância";
                break;
            default :
                $mod = "presencial e à distância";
                break;
        }
        $subtitle_array[] = $mod;
    }
    // Tipo da infraestrutura
    if ($agrupamento != "TIPO" && $tipoinfra != "0") {
        $daotinf = new TipoinfraDAO();
        $rows = $daotinf->buscatipota($tipoinfra);
        foreach ($rows as $row) {
            $tipoinf = $row["Nome"];
        }
        $subtitle_array[] = $tipoinf;
    }
    // Formatação Final
    for ($i = 0; $i < count($subtitle_array) - 1; $i++) {
        $subtitle .= $subtitle_array[$i] . ", ";
    }

    $subtitle = ($subtitle=="")?"":substr($subtitle, 0, -2) . " e ";
    $subtitle.= $subtitle_array[count($subtitle_array) - 1];
    $subtitle_final = ($subtitle=="")?"":"Parâmetro(s): " . $subtitle;
// $subtitle = implode(" ", $subtitle_array);
    $configuracoes = array(
        'subtitle' => $subtitle_final,
        'title' => $title,
        'float' => $isfloat
    );
    $json['dados'] = $data; // dados;
    $json['configuracoes'] = $configuracoes; // configurações
} else {
    $json['dados'][] = array(
        "erro" => $erro
    );
}
echo json_encode($json);
?>
<?php header('Content-type: text/html; charset=UTF-8'); ?>
<?php

require '../../dao/PDOConnectionFactory.php';
require_once '../../classes/unidade.php';
require_once "../../modulo/labor/dao/laboratorioDAO.php";
require_once '../../modulo/labor/dao/categoriaDAO.php';
require_once '../../modulo/labor/dao/tplaboratorioDAO.php';
require_once '../../modulo/labor/classes/laboratorio.php';
require_once '../../modulo/labor/classes/labcurso.php';
require_once '../../classes/curso.php';
require_once '../../util/Grafico.php'; // classe para auxiliar na criação de gráficos
?>
<?php

/* grant */
require_once '../../classes/sessao.php';
session_start();
$sessao = $_SESSION["sessao"];
if (!isset($sessao)) {
    exit(0);
} else {
    $aplicacoes = $sessao->getAplicacoes();
    if (!$aplicacoes[7]) { // laboratório
        exit(0);
    }
}
?>
<?php

$anounico = $_GET["anounico"]; // ano único
$ano = $_GET["ano"]; // primeiro ano do período
$ano1 = $_GET["ano1"]; // segundo ano do período
$situacao = $_GET["situacao"]; // situação do laboratório
$txtUnidade = $_GET["txtUnidade"]; // nome da unidade
$cat = $_GET["categoria"]; // categoria do laboratório
$tipo = $_GET["tlab"]; // tipo do laboratório
//$curso = $_GET["curso"]; // inclui o curso ou não
$nivelcurso = $_GET["nivelcurso"]; // nível do curso
//$area = $_GET["area"]; // área do laboratório
$labprat = $_GET["labprat"]; // laboratórios de aula práticas
$labinf = $_GET["labinf"]; // laboratórios de informática
$so = $_GET["so"]; // Sistema Operacional
$cab = $_GET["cab"]; // cabeamento estruturado
$pesquisa = $_GET["pesquisa"]; // adicionado para saber como o dado deve ser recebido
$tipografico = $_GET["tipografico"]; // tipo do gráfico selecionado no caso da série anual
$erro = null; // variável que armazena se existe erro nos dados
$consulta = $_GET["consulta"]; // tipo de consulta
$area = null;
$curso = null;
// Tipo de consulta
// 1 - laboratórios
// 2 - Soma das áreas dos laboratórios
// 3 - Número de laboratórios que possuem curso
switch ($consulta) {
    case 2:
        $area = "area";
        break;
    case 3:
        $curso = "curso";
        break;
}
if ($pesquisa == "serie") {
    $curso = null;
}
////////////////////////////////////////////////////////////////////////////////
// Validação de alguns campos do formulário
////////////////////////////////////////////////////////////////////////////////
if (!isset($pesquisa)) {
    $erro = "Selecione o critério de pesquisa: anual ou série histórica";
} else if ($txtUnidade != "" && !preg_match("/^[a-zA-Z]+([a-zA-Z\s\-]*)[a-zA-Z]+$/", $txtUnidade)) {
    $erro = "Formato inválido para a pesquisa da unidade";
} else if ($pesquisa == "serie" && !preg_match("/^[1-9][0-9]{3,3}$/", $ano)) {
    $erro = "O <strong> ano inicial</strong> deve conter 4 dígitos e não iniciar com 0 (Ex.: 2000)";
} else if ($pesquisa == "serie" && !preg_match("/^[1-9][0-9]{3,3}$/", $ano1)) {
    $erro = "O <strong> ano final</strong> deve conter 4 dígitos e não iniciar com 0 (Ex.: 2000)";
} else if ($pesquisa == "serie" && $ano > $ano1) {
    $erro = "O <strong> ano final</strong> deve ser maior ou igual ao <strong> ano inicial</strong>";
} else if ($ano1 > date("Y") - 1) {
    $anodf = date("Y") - 1;
    $erro = "O ano final da série histórica não pode ser superior a $anodf";
}
if ($erro == null) {
    $dados = array(); // dados
    $daolab = new LaboratorioDAO();
    if ($pesquisa == "anual") { // somente um ano
        // se retornar duas ou três colunas, é possível formar o resultado
        if ($curso != "") {
            $stmt = $daolab->buscalabgraficocurso($anounico, $cat, $tipo, $situacao, $area, $labprat, $labinf, $so, $cab, $txtUnidade, $nivelcurso);
        } else {
            $stmt = $daolab->buscalabgrafico($anounico, $cat, $tipo, $situacao, $area, $labprat, $labinf, $so, $cab, $txtUnidade);
        }
        if (!$stmt) {
            $erro = "A pesquisa encerrou com falhas!";
        } else {
            if ($stmt->rowCount() > 25) {
                $erro = "Não foi possível gerar o gráfico, pois foram gerados muitos pontos!";
            } else if ($stmt->columnCount() > 3) {
                $erro = "Não foi possível gerar o gráfico pois há muitos parâmetros selecionados";
            } else if ($stmt->rowCount() == 0) {
                $erro = "A pesquisa não retornou resultado!";
            } else if ($stmt->columnCount() == 1) {
                $erro = "Falta parâmetros na pesquisa!";
            } else if ($stmt->columnCount() == 2 || $stmt->columnCount() == 3) {
                // o trecho a seguir avalia o título de cada coluna retornada na 
                // pesquisa. Esta ação é importante para gerar o título do gráfico
                $ncol = $stmt->columnCount(); // número de colunas
                $header = array(); // array de títulos
                for ($i = 0; $i < $ncol; $i++) { // iteração no número de colunas
                    $array = $stmt->getColumnMeta($i); // recebe o meta dados da coluna
                    $header[$i] = $array["name"]; // adiciona os cabeçalhos
                } // fim
                while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                    if ($stmt->columnCount() == 2) {
                        $dados[$row[0]] = $row[1] + 0;
                    } else {
                        $tipografico = "column"; // força o gráfico para colunas
                        $dados[$row[0]][$row[1]] = $row[2] + 0; // gráfico de colunas
                    }
                }
            }
        }
    } else if ($pesquisa == "serie") {
        for ($i = $ano; $i <= $ano1; $i++) {
            $stmt = $daolab->buscalabgrafico($i, $cat, $tipo, $situacao, $area, $labprat, $labinf, $so, $cab, $txtUnidade);
            $ncol = $stmt->columnCount();
            if ($ncol == 1 || $ncol == 2) { // resultados válidos
                $header = array(); // array de títulos
                for ($k = 0; $k < $ncol; $k++) { // iteração no número de colunas
                    $array = $stmt->getColumnMeta($k); // recebe o meta dados da coluna
                    $header[$k] = $array["name"]; // adiciona os cabeçalhos
                } // fim
                // se for uma coluna
                if ($ncol == 1) {
                    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                        $dados[$i] = $row[0] + 0;
                    }
                }
                // se for duas colunas
                if ($ncol == 2) {
                    $tipografico = "column"; // força o gráfico para colunas
                    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                        $dados[$i][$row[0]] = $row[1] + 0;
                    }
                }
            } else {
                $erro = "Não foi possível gerar o gráfico pois há muitos parâmetros selecionados";
            }
        }

        if (empty($dados)) {
            $erro = "Não foi possível gerar o gráfico! A consulta não gerou resultados.";
        }
    }
}
if (isset($erro)) {
    $json['dados'][] = array(
        "erro" => $erro
    );
    echo json_encode($json);
} else {
    $grafico = new Grafico($dados); // instancia o objeto
    $grafico->ajusteDados(); // ajuste dos dados
    $data = array();
    if ($tipografico == "column") {
        $data = $grafico->getData();
    } else {
        $data = $grafico->getDataPie();
    }
    $flagPlot = $grafico->isDataValid($tipografico, $data);

    if (!$flagPlot) {
        $erro = "O gráfico não foi gerado! Possíveis causas: ";
        $erro .= "<ul>";
        $erro .= "<li>Há somente um ponto no gráfico</li>";
        $erro .= "<li>Todos os pontos são nulos</li>";
        $erro .= "</ul>";
        $erro .= 'Para mais detalhes clique no botão "Consultar".';
        $json['dados'][] = array(
            "erro" => $erro
        );
        echo json_encode($json);
        exit();
    }
    $header_aux = array_reverse($header);
    $title = implode($header_aux, ', por ');
    $subtitle = '';
    ////////////////////////////////////////////////////////////////////////////
    // Configurações dos parâmetros para gerar o subtítulo
    ////////////////////////////////////////////////////////////////////////////
    // Unidades
    $keys_u = array(
        "todas" => "Todas",
        "institutos" => "Institutos",
        "faculdades" => "Faculdades",
        "hospitais" => "Hospitais",
        "campus" => "Campus",
        "escolas" => "Escolas",
        "nucleos" => "Núcleos",
    );
    if (!array_key_exists(strtolower($txtUnidade), $keys_u) && $txtUnidade != "") {
        $subtitle .= "Parâmetro(s): unidade <b>" . strtoupper($txtUnidade) . "</b>";
    } else if (trim($txtUnidade) == "") {
        $subtitle .= "Parâmetro(s): independente de unidade";
    } else {
        $subtitle .= "Parâmetro(s): unidade " . $keys_u[strtolower($txtUnidade)] . "</b>";
    }
    // Categoria
    $daocat = new CategoriaDAO();
    $rows = $daocat->buscacat($cat);
    foreach ($rows as $row) {
        $subtitle .= ", categoria <b>" . strtolower($row["Nome"]) . "</b>";
    }
    // Tipo ou subcategoria
    $daotipo = new TplaboratorioDAO();
    $rows = $daotipo->buscatipo($tipo);
    foreach ($rows as $row) {
        $subtitle .= ", subcategoria <b>" . strtolower($row["Nome"]) . "</b>";
    }
    // situação 
    if ($situacao == "A") {
        $subtitle .= ", situação <b>ativos</b>";
    } else if ($situacao == "D") {
        $subtitle .= ", situação <b>desativados</b>";
    }
    // aulas práticas
    if ($labprat) {
        $subtitle .= ", laboratórios de aulas práticas";
    }
    // laboratórios de informática
    if ($labinf) {
        $subtitle .= ", laboratórios de informática";
    }
    if ($pesquisa == "anual") {
        $subtitle.= "<br/>Ano: " . $anounico;
    } else {
        $periodo .= "<br/>Período: $ano a $ano1";
    }
    $isfloat = ($area == "area") ? (true) : (false);
    $configuracoes = array(
        'subtitle' => $subtitle,
        'title' => $title,
        'float' => $isfloat
    );
    $json['dados'] = $data; // dados;
    $json['configuracoes'] = $configuracoes; // configurações
    echo json_encode($json);
}
?>

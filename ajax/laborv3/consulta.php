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
$tchart = $_GET["tipografico"]; // tipo do gráfico selecionado no caso da série anual
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
$tabela = array(); // resultado final da consulta
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
    $tipografico = null;
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
            if ($stmt->rowCount() == 0) {
                $erro = "A pesquisa não retornou resultado!";
            }
            $ncol = $stmt->columnCount(); // número de colunas
            $header = array(); // array de títulos
            for ($i = 0; $i < $ncol; $i++) { // iteração no número de colunas
                $array = $stmt->getColumnMeta($i); // recebe o meta dados da coluna
                $header[$i] = $array["name"]; // adiciona os cabeçalhos
            } // fim
            // captura os dados
            $l = 0; // linhas do array
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                for ($k = 0; $k < $ncol; $k++) {
                    $tabela[$l][$k] = $row[$k]; // preenche a tabela
                }
                $l++;
            }
        }
    } else if ($pesquisa == "serie") {
        $l = 0; /* linha */
        $header = array(); // array de títulos
        for ($i = $ano; $i <= $ano1; $i++) { // avalia o resultado para cada ano
            $stmt = $daolab->buscalabgrafico($i, $cat, $tipo, $situacao, $area, $labprat, $labinf, $so, $cab, $txtUnidade);
            $ncol = $stmt->columnCount(); // número de colunas
            for ($k = 0; $k < $ncol; $k++) { // iteração no número de colunas
                $array = $stmt->getColumnMeta($k); // recebe o meta dados da coluna
                $h = ($array["name"] == "por unidade")?"unidade":$array["name"];
                $header[$k] = $h; // adiciona os cabeçalhos
            } // fim

            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                for ($m = 0; $m < $ncol; $m++) {
                    if ($row[$m] == NULL) {
                        $tabela[$l][$m] = 0;
                    } else {
                        $tabela[$l][$m] = $row[$m]; // preenche a tabela
                    }
                }
                $l++;
                $tabela[$l - 1][$ncol] = $i;
            }
        }
        $ncol = $ncol + 1;
        $header[$ncol] = "Ano";
        if (empty($tabela)) {
            $erro = "Não foi possível gerar o gráfico! A consulta não gerou resultados.";
        }
    }
}
?> 
<?php if (!$erro) { ?>
    <div id="download-list">
        <ul>
            <li class="xls-type"><a id="reportchart" href="#">Exportar consulta em planilha (.xls)</a></li>
        </ul>
    </div>
    <table class="tablesorter tablesorter-dropbox">
        <thead>
            <tr>
                <?php foreach ($header as $h) { ?>
                    <th><?php echo $h; ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < $l; $i++) { ?>
                <tr>
                    <?php for ($j = 0; $j < $ncol; $j++) { ?>
                        <td><?php echo $tabela[$i][$j]; ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="7" class="ts-pager form-horizontal">
                    <button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
                    <button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
                    <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                    <button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
                    <button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
                    <select class="pagesize input-mini" title="Select page size">
                        <option selected="selected" value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                    </select>
                    <select class="pagenum input-mini" title="Select page number"></select>
                </th>
            </tr>
        </tfoot>
    </table>
<?php } else { ?>
<div class='alert alert-danger'><?php echo $erro; ?></div>
<?php } ?>


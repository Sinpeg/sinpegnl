<?php
header('Content-Type: text/html; charset=utf-8');
require_once '../../vendors/pChart/class/pData.class.php';
require_once '../../vendors/pChart/class/pDraw.class.php';
require_once '../../vendors/pChart/class/pPie.class.php';
require_once '../../vendors/pChart/class/pImage.class.php';
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../modulo/infraensino/dao/tipoinfraensinoDAO.php';
require_once '../../util/PieChartHelper.php';
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
 if (!$aplicacoes[9]) { // Acessibilidade
 exit(0);
 }
}
?>
<?php
$ano = $_GET["ano"];
$ano1 = $_GET["ano1"];
$tipo = $_GET["tipo"];
$txtUnidade = $_GET["txtUnidade"];
$y = $_GET["y"];
?>
<?php
// caso os anos sejam iguais e o tipos seja "todos".
// então calcula os dados para a formação do gráfico de pizza.
if ($ano == $ano1 && $tipo == 0) {
 $daoie = new TipoinfraensinoDAO();
 $rows = $daoie->buscatipo($tipo, $ano, $ano1, $txtUnidade);
 $dados = array();
 foreach ($rows as $row) {
 $dados[$row['Nome']] += $row['Quantidade'];
 }
}
?>
<?php
$xvalues = array();
$yvalues = array();
foreach ($dados as $key => $data) {
 $xvalues[] = $key;
 $yvalues[] = $data;
}
?>
<?php
 $title = "Percentual das infraestruturas de ensino";
?>
<?php
/* Create and populate the pData object */
$MyData = new pData();
$MyData->addPoints($yvalues, "ScoreA");
$MyData->setSerieDescription("ScoreA", "Application A");
$MyData->addPoints($xvalues, "Labels");
$MyData->setAbscissa("Labels");
$pieHelper = new PieChartHelper($MyData);
$pieHelper->configPieChart(900, 350, 190, $title, 620, 20);
$pieHelper->getPicture()->autoOutput("pictures/example.draw3DPie.png");
?>

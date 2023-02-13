<?php
// Report all PHP errors
require_once '../../vendors/pChart/class/pData.class.php';
require_once '../../vendors/pChart/class/pDraw.class.php';
require_once '../../vendors/pChart/class/pImage.class.php';
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../modulo/infraensino/dao/tipoinfraensinoDAO.php';
//require_once '../../util/BarChartHelper.php';
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
//$situacao = $_GET["situacao"];
$txtUnidade = $_GET["txtUnidade"];
$y = $_GET["y"];
//$curso = $_GET["curso"];
?>
<?php
$daoie = new TipoinfraensinoDAO();
$rows = $daoie->buscatipo($tipo, $ano, $ano1, $txtUnidade);
$dados = array();
foreach ($rows as $row) {
 if ($y == "infraensino")
 $dados[$row["Ano"]][$row["NomeUnidade"]]+= $row["Quantidade"];
}
?>
<?php
$points = array();
$legend = array();
foreach ($dados as $key1 => $value1) {
 $periodo[] = $key1;
 foreach ($value1 as $key2 => $value2) {
 $points[] = $value2; // array de pontos
 $legend[] = $key2; // unidades;
 }
}
?>
<?php
// ajuste das dimensões
$width = 900;
$heigth = 700;
$x0 = 100;
$y0 = 100;
$x1 = $x0+500;
$y1 = $y0+500;
$xL = $width-300;
$yL = $y0;
$xTitle = 200;
$yTitle = 20;
?>
<?php
$unid = array(
 "TODAS" => " por unidade",
 "CAMPI" => " dos campi",
 "ESCOLAS" => " das escolas",
 "HOSPITAIS" => " dos hospitais",
 "INSTITUTOS" => " dos institutos",
 "NUCLEOS" => " dos núcleos",
 "FACULDADES" => " das faculdades"
);
$pTitulo = $unid[strtoupper($txtUnidade)];
$pStr = ($ano == $ano1) ? "Ano: " . $ano : "Período: $ano a $ano1";
if ($tipo != 0) {
 $daoie = new TipoinfraensinoDAO();
 $rows1 = $daoie->buscatipoinfraensino($tipo);
 foreach ($rows1 as $r1) {
 $tstr = "(" . $r1['Nome'] . ")";
 }
}
$yl = "";
$title = "Quantidade de Infraestrutura de ensino $tstr" . $pTitulo . "\n$pStr";
?>
<?php
/* Create and populate the pData object */
$MyData = new pData();
$MyData->loadPalette("../palettes/light.color", TRUE);
for ($i = 0; $i < count($points); $i++) {
 $MyData->addPoints($points[$i], $legend[$i]);
}
 $MyData->addPoints($periodo,"Months");
$MyData->setSerieDescription("Months", "Month");
$MyData->setAbscissa("Months");
/* Create the pChart object */
$myPicture = new pImage($width, $heigth, $MyData);
$myPicture->drawGradientArea(0, 0, 900, 800, DIRECTION_VERTICAL, array("StartR" => 240, "StartG" => 240, "StartB" => 240, "EndR" => 180, "EndG" => 180, "EndB" => 180, "Alpha" => 100));
$myPicture->drawGradientArea(0, 0, 900, 800, DIRECTION_HORIZONTAL, array("StartR" => 240, "StartG" => 240, "StartB" => 240, "EndR" => 180, "EndG" => 180, "EndB" => 180, "Alpha" => 20));
$myPicture->setFontProperties(array("FontName" => "../../vendors/pChart/fonts/verdana.ttf", "FontSize" => 6));
/* Draw the scale */
$myPicture->setGraphArea($x0, $y0, $x1, $y1);
$myPicture->drawScale(array("Mode" => SCALE_MODE_START0, 'Factors' => array(1), "CycleBackground" => TRUE, "DrawSubTicks" => TRUE, "GridR" => 0, "GridG" => 0, "GridB" => 0, "GridAlpha" => 10));
/* Turn on shadow computing */
$myPicture->setShadow(TRUE, array("X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));
/* Draw the chart */
$settings = array("Gradient" => TRUE, "GradientMode" => GRADIENT_EFFECT_CAN, "DisplayPos" => LABEL_CLASSIC, "DisplayValues" => TRUE, "DisplayR" => 0, "DisplayG" => 0, "DisplayB" => 0, "DisplayShadow" => TRUE, "Surrounding" => 10);
$myPicture->drawBarChart($settings);
/* Write the chart legend */
$myPicture->drawLegend($xL, $yL, array("Style" => LEGEND_ROUND, "Mode" => LEGEND_VERTICAL));
$myPicture->drawText($xTitle, $yTitle, $title, $TextSettings);
/* Render the picture (choose the best way) */
$myPicture->autoOutput("pictures/example.drawBarChart.can.png");
?>
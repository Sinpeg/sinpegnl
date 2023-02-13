<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../classes/sessao.php';
require_once '../dao/PDOConnectionFactory.php';
require_once 'dao/relatoriosDAO.php';
require_once '../vendors/dompdf/autoload.inc.php';


$ano = $_GET['anobase'];

$relatorioDAO = new  RelatoriosDAO();

//Busca hierarquia das unidades
$rowHier = $relatorioDAO->buscaUnidadeCensoHier();

$uniHiera = array();
$count = 0;

$aux_sim="";
$aux_nao="";

$html="<table align='center' border='1'  cellspacing='0'   width='100%'>
		<tr align='center' bgcolor='#BDBDBD'><td>UNIDADE</td><td>UNIDADES QUE CADATRARAM</td><td>UNIDADES PENDENTES</td></tr>";

foreach ($rowHier as $row){
	$uniHiera[$count]["hier"] = $row['hierarquia_organizacional'];
	$uniHiera[$count]["unidade"] = $row['nomeUnidade'];
	$uniHiera[$count]["codUnidade"] = $row['codUnidade'];	
	$count++;
}
//count($uniHiera)
for ($i=0;$i<count($uniHiera);$i++){
	$html.="<tr><td>".$uniHiera[$i]["unidade"]."</td>";
	$aux_sim="";
	$aux_nao="";
	//echo $uniHiera[$i]["hier"]."<br/>";
	$rowEa = $relatorioDAO->buscaEaporHier($uniHiera[$i]["hier"], $ano);
	foreach ($rowEa as $row){
		if(is_null($row['CodigoEstrutura'])){
			$aux_nao .= $row['NomeUnidade']."<br/>";
		}else{ 
			$aux_sim .= $row['NomeUnidade']."<br/>";
		} 
	}

	$html.="<td>".$aux_sim."</td><td>".$aux_nao."</td></tr>";
}

$html.="</table>";

/*
// Configurações header para forçar o download
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel; charset=utf-8");
header ("Content-Disposition: attachment; filename='Relatorio_lancamento.xls'");
header ("Content-Description: PHP Generated Data" );
*/

// reference the Dompdf namespace
use Dompdf\Dompdf;
// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');
// Render the HTML as PDF
$dompdf->render();
// Output the generated PDF to Browser
$dompdf->stream('Relatorio_Estrutura.pdf');

// Envia o conteúdo do arquivo
echo $html;
?>
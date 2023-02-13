<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

//require_once '../classes/sessao.php';
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../dao/painelTaticoDAO.php';
//require_once '../classes/validacao.php';
require_once '../../vendors/dompdf/autoload.inc.php';

//Recupera os parâmetros enviados via GET
$cod_unidade = $_GET['unidade'];
$ano_base = $_GET['anoBase'];

//Definição da classe
$painelPDI = new PainelTaticoDAO();
//$rows = $painelTatico->exportarResultadoPainel($ano_base, $cod_unidade);

//NOVO CODIGO
if($cod_unidade != 938){
	//Banco de Dados e Query para obter o PDI
	$rows= $painelPDI->exportarResultadoPainelPDI($ano_base, $cod_unidade);
	// Comentado by Diogo $daoserv = new ServprocDAO();
   $rows_pdi= $painelPDI->exportarResultadoPainel($ano_base, $cod_unidade);
}else {
	$rows = $painelPDI->exportarResultadoPainelPDI938($ano_base);
	
}

//NOVO CODIGO

//Cabeçalho do relatório
$html ='<center><img src="../../webroot/img/logo.jpg" height="50" width="50" />
		<h5>UNIVERSIDADE FEDERAL DO PARÁ<br/></h5><h5>
		<br/><br/> ANO BASE '.$ano_base.'<br/><br/>PENCENTUAIS DE DESEMPENHO GERAL</h5>
		</center>';

//Cabeçalho da tabela
$html .= '<table style="font-size:13px;" border="0"  cellspacing="0"  rules="cols" width="100%">			
		<tr>
		<td align="center" width="15%" bgcolor="#DCDCDC" ><b>Objetivo</b></td>
		<td align="center" width="15%" bgcolor="#DCDCDC"><b>Indicador</b></td>
		<td align="center" width="18%" bgcolor="#DCDCDC"><b>F&oacute;rmula</b></td>
		<td align="center" width="" bgcolor="#DCDCDC"><b>Meta</b></td>
		<td align="center" width="" bgcolor="#DCDCDC"><b>Resultado</b></td>
		<td align="center" width="" bgcolor="#DCDCDC"><b>Percentual de Desempenho</b></td>
		<td align="center" width="" bgcolor="#DCDCDC"><b>Sinalizador</b></td></tr>';

//Definindo variáveis auxiliares
$aux = 0;$ob_aux=''; $color_tr="";$aux2=1;
$ind_aux='';$color_tr_ind="";$aux3=1;
$color_meta=0;$color_meta2=0;$aux4=1;
$linha_aux=""; //Para construir a linha da meta do indicador a cada loop
$meta_aux = "";$ano_aux="";$aux_pGeral=0;$aux_qtdInd=0;$desem_geral=0;

//aqui
include 'looprows.php';

if ($cod_unidade!=938){
	$rows=$rows_pdi;
	
	include 'looprows.php';
}

$desem_geral=round($aux_pGeral/$aux_qtdInd,2);
$html .= "<tr bgcolor='#C0C0C0'><td colspan='5' align='center'> <b>PERCENTUAL DE DESENPENHO GERAL</b></td><td align='center'><b>".$desem_geral."%</b></td>";

//Definindo Sinalizador
if ($desem_geral >= 90) {
	$html .= '<td align="center" ><img src="../../webroot/img/bullet-green.png"/></td></tr>';
}else if($desem_geral > 60 && $desem_geral < 90) {
		$html .= '<td align="center" ><img src="../../webroot/img/bullet-yellow.png"/></td></tr>';
} else {
		$html .= '<td align="center" ><img src="../../webroot/img/bullet-red.png"/></td></tr>';
}
						
$html .= "</table><br/><br/>
		<table>
		<tr><td><img src='../../webroot/img/bullet-green.png'/>: Resultado esperado! A meta atingida superou 90% em relação a  meta definida.</td></tr>
		<tr><td><img src='../../webroot/img/bullet-yellow.png'/>: Atenção! A meta atingida está entre 60% e 90% da meta.</td></tr>
		<tr><td><img src='../../webroot/img/bullet-red.png'/>: Abaixo do esperado! A meta atingida é inferior a 60% do esperado.</td></tr>
		</table>";

//echo $html;

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
$dompdf->stream('Resultados_Percentuais.pdf');

?>
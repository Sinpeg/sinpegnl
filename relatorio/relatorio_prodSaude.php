<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../classes/sessao.php';
require_once '../dao/PDOConnectionFactory.php';
require_once 'dao/relatoriosDAO.php';
require_once '../vendors/dompdf/autoload.inc.php';

session_start();
$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeUnidade();
$codunidade = $sessao->getCodUnidade();

//Recupera os parâmetros enviados via GET
$ano_base = $_GET['anoBase'];
$cod_unidade = $_GET['cod_unidade'];

//Instancia o método
$relatorios = new RelatoriosDAO();
$rows = $relatorios->producaoSaude($ano_base,$cod_unidade);

$html='';
//Cabeçalho do relatório
$html ='
		<center><img src="../webroot/img/logo.jpg" height="50" width="50" />
		<h5>UNIVERSIDADE FEDERAL DO PARÁ</h5>
		<h5>RELATÓRIO DE PRODUÇÃO DA ÁREA DA SAÚDE</h5>		
		</center>
<br><br><br><br><br>
';

$subunidade="";$aux=0;
foreach ($rows as $row) {
	
	if($subunidade != $row['Subunidade']){
		if($aux!=0){$html .="</table><br/>";}
		$subunidade = $row['Subunidade'];
		$sigla= ($row['codunidade'] == 2015 || $row['codunidade'] == 2017 ||
		    $row['codunidade'] == 4999 ||  $row['codunidade'] == 2188)?"ICS":"";
		$sigla= ($row['codunidade'] == 1900)?"IFCH":$sigla;
		$sigla= (empty($sigla))?"NMT":$sigla;
		    
		$sigla.=" - ".$subunidade;
		$html .= '<table border="0" cellspacing=0 cellpadding=0 width="90%" align="center">';
		//	$html .= '<tr ><td colspan="8" ><br/><b>SUBUNIDADE</b>: '.$row['Subunidade'].'</td></tr>
		$html .= '<tr ><td colspan="8" ><br/><b>SUBUNIDADE</b>: '.$sigla.'</td></tr>
		<tr bgcolor="#F08080" style="border: 1px;">
				  		<td ><b>Local</b></td>
				  		<td ><b>Serviço</b></td>
				  		<td ><b>Procedimento</b></td>				  		
				  		<td ><b>Nº Procedimentos</b></td>
				  		<td ><b>Nº Exames</b></td>
				  		<td ><b>Nº Pessoas Atendidas</b></td>
				  </tr>';
		
		$html .= '<tr>
						<td >'.$row['Localizacao'].'</td>
				  		<td >'.$row['nomeServico'].'</td>
				  		<td >'.$row['nomeProcedimento'].'</td>				  		
				  		<td align="center">'.$row['nProcedimentos'].'</td>
                        <td align="center">'.$row['nExames'].'</td>
                        <td align="center">'.$row['nPessoasAtendidas'].'</td>
				 </tr>';
		
	}else{
		$aux=0;
		$html .= '<tr>
						<td >'.$row['Localizacao'].'</td>
				  		<td >'.$row['nomeServico'].'</td>
				  		<td >'.$row['nomeProcedimento'].'</td>				  		
				  		<td align="center">'.$row['nProcedimentos'].'</td>
                        <td align="center">'.$row['nPessoasAtendidas'].'</td>

				 </tr>';
	}
	
	
	$aux=1;
}


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
$dompdf->stream('Relatorio_saude.pdf');

?>

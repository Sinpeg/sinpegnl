<?php

require_once '../../../classes/pdf.php';

require_once '../../../dao/PDOConnectionFactory.php';

require_once '../../../modulo/mapaestrategico/dao/MapaDAO.php';

require_once '../../../modulo/documentopdu/dao/DocumentoDAO.php';

require_once '../../../classes/validacao.php';

?>

<?php

// Recupera a sessão e testa se está setada
require_once '../../../classes/sessao.php';
session_start();

$sessao = $_SESSION["sessao"]; // objeto da sessão

?>

<?php 
$nomeunidade = $sessao->getNomeunidade(); // recupera o nome da unidade
$codunidade = (int) $sessao->getCodunidade(); // recupera o código da unidade
$anobase = $sessao->getAnobase(); // ano base

$daodoc = new DocumentoDAO();
$querrydoc = $daodoc->buscadocumentoporunidade($codunidade);
foreach ($querrydoc as $doc){
	if($anobase >= $doc['anoinicial'] AND $anobase <=$doc['anofinal']){
		$codDocumento = $doc['codigo'];
	}
}


$daomapa = new MapaDAO();
$querrypdu = $daomapa->buscaPainelAcoesIndMetas($codDocumento);

// echo "<pre><br>";
// var_dump($arrayrela);die;




// ob_start();
// ?>




<!-- <html> -->
<!-- <head> -->
<!-- <title>Mesclando Células na Vertical</title> -->
<!-- </head> -->
<!-- <body> -->

<!-- <h1>Tabela de Produtos</h1> -->
<!-- <table border= '1' class='bordasimples'> -->
<!-- <tr> -->
<!-- <td>Objetivos Estrategicos PDI 2016-2025 UFPA</td><td>Iniciativas Táticas</td><td>Indicadores PDU</td><td>metas</td> -->
<!-- </tr> -->

// <?php
// // foreach ($querrypdu as $pdu){

// // 	$pdf->Row(array(utf8_decode($pdu['Objetivo']),utf8_decode($pdu['iniciativa']),utf8_decode($pdu['indicador']),utf8_decode($pdu['meta'])));

// // }
// ?>


// <?php // foreach ($querrypdu as $pdu){
// 	if($pdu['codObjetivo']){
// 	?>
		
// <?php //}?>

<!-- <tr> -->
<td><?php// echo $pdu['Objetivo']; ?></td>
<td><?php// echo $pdu['iniciativa']; ?></td>
<td><?php// echo $pdu['indicador']; ?></td>
<td><?php// echo $pdu['meta']; ?></td>
<!-- </tr> -->

// <?php 
// 		}?>
<!-- </table> -->
<!-- </body> -->
<!-- </html> -->

// <?php


// // Pega a guardada pelo buffer e salva na variável "$conteudo".
// $conteudo = ob_get_contents();






// include("../../classes/mpdf60/mpdf.php");

// $mpdf=new mPDF('utf-8', 'A4-L');
// $mpdf->SetDisplayMode('fullpage');
// $css = file_get_contents("estilo.css");
// $mpdf->WriteHTML($css,1);
// $mpdf->WriteHTML($conteudo);
// $mpdf->Image('../../webroot/img/braUFPA.jpg', 140, 0, 20, 30, 'jpg', '', true, true);
// $mpdf->Output();

// exit;





ob_start();

$data = array($nomeunidade, utf8_decode('LABORATÓRIOS - ANO BASE - ') . $anobase);
$pdf = new pdf("L"); // instancia o objeto de pdf
$pdf->setData($data);

$pdf->Open(); // abre o arquivo
$pdf->AddPage(); // adiciona página
$pdf->AliasNbPages();

$w = array(65, 65, 60, 60, 30);
$a = array('C', 'C', 'C', 'C', 'C');
$pdf->SetWidths($w); // configura o array de width
$pdf->SetAligns($a); // array de posicionamento


$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10,utf8_decode('Painel de Ações, Indicadores e Metas'), 0, 0, 'L');
$pdf->Ln(10);

//colunas
$colunas = array('Objetivos Estrategicos PDI 2016-2025 UFPA', utf8_decode('Iniciativas Táticas'), 'Indicadores PDU', 'Metas', 'Ano');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Row($colunas);



// objetivos
$cont=0;
foreach ($querrypdu as $pdu){
$arraypdu[$cont++] = $pdu;
}

$numelm = count($arraypdu);


$cont = 1;
foreach ($arraypdu as $pdu){
	
	if($pdu['Objetivo'] == $objetivoanterior){
		$mascaraobj[$cont-1]=0;
		$mascaraobj[$cont++]=1;
	}else{
		$objetivoanterior = $pdu['Objetivo'];
		$mascaraobj[$cont++]=0;
		if(count($arraypdu) + 1 == $cont){$mascaraobj[$cont-1]=1;}
	}	
		// 		$objetivorepetidos['obetivo'] = $objetivoanterior;
		// 		$objetivorepetidos['numrep'] = $contobjet;
		// 		$arrayobjet[$cont2++] = $objetivorepetidos;
		// 		$objetivoanterior = $pdu['Objetivo'];
		// 		$contobjet = 1;
		
	}
	
	
	$cont=1;
	foreach ($arraypdu as $pdu){
		
		if($pdu['iniciativa'] == $iniciativanterior){
			$mascaraini[$cont-1]=0;
			$mascaraini[$cont++]=1;
		}else{
			$iniciativanterior = $pdu['iniciativa'];
			$mascaraini[$cont++]=0;
			if(count($arraypdu) + 1 == $cont){$mascaraini[$cont-1]=1;}
		}
		// 		$objetivorepetidos['obetivo'] = $objetivoanterior;
		// 		$objetivorepetidos['numrep'] = $contobjet;
		// 		$arrayobjet[$cont2++] = $objetivorepetidos;
		// 		$objetivoanterior = $pdu['Objetivo'];
		// 		$contobjet = 1;
		
	}
	
	echo "<pre><br>";var_dump($mascaraini);die;
	
	
	
 
 //iniciativas
 $triger = 0;
 $cont = 0;
 $cont2 = 0;
 $continiciativa = 1;
 foreach ($arraypdu as $pdu){
 	if($pdu['iniciativa'] == $iniciativaanterior or $triger == 0){
 		if($triger != 0) $continiciativa++;
 		$triger = 1;
 	}else{
 		$iniciativarepetidos['iniciativa'] = $iniciativaanterior;
 		$iniciativarepetidos['numrep'] = $continiciativa;
 		$arrayiniciativa[$cont2++] = $iniciativarepetidos;
 		$iniciativaanterior = $pdu['iniciativa'];
 		$continiciativa = 1;
 	}
 	
 	$arraypdu[$cont++] = $pdu;
 }
 
 
 $triger = 0;
 $cont = 0;
 $cont2 = 0;
 $contindicad = 1;
 foreach ($arraypdu as $pdu){
 	if($pdu['indicador'] == $indicadoranterior or $triger == 0){
 		if($triger != 0)$contindicad++;
 		$triger = 1;
 	}else{
 		$indicrepetidos['indicador'] = $indicadoranterior;
 		$indicrepetidos['numrep'] = $contindicad;
 		$arrayindic[$cont2++] = $indicrepetidos;
 		$indicadoranterior= $pdu['indicador'];
 		$contindicad= 1;
 	}
 	
 }
 
 
//  echo "<pre><br>";var_dump($arrayindic);die;
 
//  echo "<pre><br>";var_dump($arrayiniciativa);die;
 
 
//linhas
	$pdf->SetFont('Arial', '', 10);
	
	$regressive = 3;
foreach ($arraypdu as $pdu){
	
	$meta = $pdu['meta'];
	$metaat = $pdu['meta_atingida'];
	
	$pdu['meta'] = ($metaat/$meta)*100;
	
	
	$arrayobjet[0]['numrep'];
	
	$pdf->Row(array(utf8_decode($pdu['Objetivo']),utf8_decode($pdu['iniciativa']),utf8_decode($pdu['indicador']),utf8_decode($pdu['meta'])."%", utf8_decode($pdu['ano'])));

}




$pdf->Ln(10);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 10, "Siglas: ", 0, 0, 'L');
$pdf->Ln(10);
$siglas = array("Lab", "Capac", "AP", "NE", "SO", "W", "L", "0", "CE", "N", "S");
$significado = array("Laboratório", "Capacidade", ("Aulas Práticas"),
		("Número de Estações"), "Sistema Operacional",
		"Windows", "Linux", "Nenhum", "Cabeamento Estruturado", ("Não"), "Sim");
		$i = 0;
		/** Cria a legenda para as siglas * */
		foreach ($siglas as $s) {
			$pdf->Cell(0, 10, utf8_decode($s) . ' = ' . utf8_decode($significado[$i++]), 0, 0, 'L');
			$pdf->Ln(5);
		}
		/** Fim * */
		/** Disponibiliza o relatório para download * */
		$nome = explode(" ", $nomeunidade);
		$nome = implode("_", $nome);
		$pdf->Output("Relatorio_$nome.pdf", 'D');
?>
<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../dao/PDOConnectionFactory.php';
require_once 'dao/relatoriosDAO.php';
require_once '../../vendor/autoload.php';

//Recupera os parâmetros enviados via GET
$ano_base = $_GET['anoBase'];

$cod_unidade = $_GET['codUnidade'];

//Instancia a classe
$relatorios = new RelatoriosDAO();

if($cod_unidade == 100000){
	$rows = $relatorios->bibliotecaAdm($ano_base);
}else{
	//$rows = $relatorios->premios($ano_base, $cod_unidade);
}

// Create new PHPExcel object
$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

//Define alinhamento 	
$sheet = $objPHPExcel->getActiveSheet();
//$sheet->getStyle('A1:AE1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
//$sheet->getStyle('A1:AE1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:AE1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

//Títulos de Colunas
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'CÓDIGO EMEC')
->setCellValue('B1', 'NOME')
->setCellValue('C1', 'TIPO')
->setCellValue('D1', 'ASSENTOS')
->setCellValue('E1', 'EMPRÉSTIMO A DOMICÍLIO')
->setCellValue('F1', 'EMPRÉSTIMOS ENTRE BIBLIOTECAS')
->setCellValue('G1', 'USUÁRIOS TREINADOS EM CAPACITAÇÃO')
->setCellValue('H1', 'PERIÓDICOS IMPRESSOS')
->setCellValue('I1', 'LIVROS IMPRESSOS')
->setCellValue('J1', 'OUTROS MATERIAIS IMPRESSOS')
->setCellValue('L1', 'ACERVO DE PERIÓDICOS ELETRÔNICOS')
->setCellValue('M1', 'ACERVO DE LIVROS ELETRÔNICOS')
->setCellValue('N1', 'BUSCA INTEGRADA')
->setCellValue('O1', 'COMUTAÇÃO BIBLIOGRÁFICA')
->setCellValue('P1', 'SERVIÇO DE INTERNET')
->setCellValue('Q1', 'REDE SEM FIO')
->setCellValue('R1', 'PARTICIPA DE REDES SOCIAIS')
->setCellValue('S1', 'ATENDENTE EM LIBRAS')
->setCellValue('T1', 'ACERVO EM FORMATO ESPECIAL')
->setCellValue('U1', 'SÍTIOS E APLICAÇÕESDESENVOLVIDOS PARA QUE PESSOAS PERCEBAM, COMPREENDAM, NAVEGUEM E UTILIZEM SERVIÇOS OFERECIDOS')
->setCellValue('V1', 'PLANO DE AQUISIÇÃO GRADUAL DE ACERVO BIBLIOGRÁFICO DOS CONTEÚDOS BÁSICOS EM FORMATO ESPECIAL ')
->setCellValue('W1', 'SOFTWARE DE LEITURA PARA PESSOAS COM BAIXA VISÃO')
->setCellValue('X1', 'IMPRESSORAS EM BRAILE')
->setCellValue('Y1', 'TECLADO VIRTUAL')
->setCellValue('Z1', 'ACESSO AO PORTAL CAPES DE PERIÓDICOS ')
->setCellValue('AA1', 'ASSINA OUTRAS BASES DE DADOS ')
->setCellValue('AB1', 'BIBLIOTECA DIGITAL DE SERVIÇO PÚBLICO ')
->setCellValue('AC1', 'CATÁLOGO ONLINE DE SERVIÇO PÚBLICO')
->setCellValue('AD1', 'JUSTIFICATIVA')
->setCellValue('AE1', 'ABRANGÊNCIA');

//Define background dos títulos do cabeçalho
$objPHPExcel->getActiveSheet()
->getStyle('A1:AE1')
->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()
->setRGB('CDCDCD');

// Define o nome da folha
$objPHPExcel->getActiveSheet()->setTitle('Dados');

// Seta a primeira folha
$objPHPExcel->setActiveSheetIndex(0);

//Define a linha em que começa os dados
$linha = 1;
$emec =""; 
$abrangencia = "";

//Conteúdo do Arquivo
foreach ($rows as $row){
	if($emec == $row['codEmec']){
		
		$abrangencia = $abrangencia.chr(13).$row['abrangencia'];		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE'.$linha, $abrangencia);		
	
	}else{
		$linha++;//Avança para próxima linha
		
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$linha, $row['codEmec'])
		->setCellValue('B'.$linha, $row['nome'])
		->setCellValue('C'.$linha, $row['Tipo'])
		->setCellValue('D'.$linha, $row['assentos'])
		->setCellValue('E'.$linha, $row['Emprestimo a Domicilio'])
		->setCellValue('F'.$linha, $row['Emprestimo entre bibliotecas'])
		->setCellValue('G'.$linha, $row['Usuarios treinados em capacitação'])
		->setCellValue('H'.$linha, $row['Periodicos impressos'])
		->setCellValue('I'.$linha, $row['Livros impressos'])
		->setCellValue('J'.$linha, $row['Outros materiais impressos'])
		->setCellValue('L'.$linha, $row['Acervo de periodicos eletronicos'])
		->setCellValue('M'.$linha, $row['Acervo de livros eletronicos'])
		->setCellValue('N'.$linha, $row['Busca Integrada'])
		->setCellValue('O'.$linha, $row['Comutação Bibliografica'])
		->setCellValue('P'.$linha, $row['Serviço de Internet'])
		->setCellValue('Q'.$linha, $row['REde sem fio'])
		->setCellValue('R'.$linha, $row['Part redes sociais'])
		->setCellValue('S'.$linha, $row['Atendente em libras'])
		->setCellValue('T'.$linha, $row['Acervo form especial'])
		->setCellValue('U'.$linha, $row['2a'])
		->setCellValue('V'.$linha, $row['plano em form especial'])
		->setCellValue('W'.$linha, $row['sof leitura baixa visao'])
		->setCellValue('X'.$linha, $row['impressoras braile'])
		->setCellValue('Y'.$linha, $row['teclado virtual'])
		->setCellValue('Z'.$linha, $row['Parte portal capes'])
		->setCellValue('AA'.$linha, $row['Assina outras bases'])
		->setCellValue('AB'.$linha, $row['Repositorio Inst de serv publico'])
		->setCellValue('AC'.$linha, $row['Cat. on line de serv publico'])
		->setCellValue('AD'.$linha, $row['justificativa'])
		->setCellValue('AE'.$linha, $row['abrangencia']);
		
		$abrangencia = $row['abrangencia'];		
		
		$emec = $row['codEmec'];
	}	
	
}

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Biblioteca.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
$objWriter->save('php://output');
exit;
?>
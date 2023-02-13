<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../dao/PDOConnectionFactory.php';
//require_once 'dao/relatoriosDAO.php';
require_once '../../vendor/autoload.php';

require_once('../modulo/praticajuridica/dao/praticajuridicaDAO.php');
require_once('../modulo/praticajuridica/classes/praticajuridica.php');
require_once('../modulo/praticajuridica/dao/tipopraticajuridicaDAO.php');
require_once('../modulo/praticajuridica/classes/tipopraticajuridica.php');


//Recupera os parâmetros enviados via GET
$ano_base = $_GET['anoBase'];
$ano_base_menos1 = $ano_base-1;
$ano_base_menos2 = $ano_base-2;
$ano_base_menos3 = $ano_base-3;
$ano_base_menos4 = $ano_base-4;


$tipospj = array();
$cont = 0;
$daotpj = new TipopraticajuridicaDAO();
$daopj = new PraticajuridicaDAO();
$rows_tpj = $daotpj->Lista();
foreach ($rows_tpj as $row) {
    $tipospj[$cont] = new Tipopraticajuridica();
    $tipospj[$cont]->setCodigo($row['Codigo']);
    $tipospj[$cont]->setNome($row['Nome']);
    $cont++;
}

$registosPj = array();
$registosTipoPj = array();
$cont1 = 0;
$soma = 0;
$rows_pj = $daopj->buscapjAnuario($ano_base,$ano_base_menos4);
foreach ($rows_pj as $row) {   
    $registosPj[$cont1] = new Tipopraticajuridica();
    $registosTipoPj[$cont1] = $row['Tipo'];
    $registosPj[$cont1]->criaPraticajuridica($row["Codigo"], $row['CodUnidade'], $row['Ano'], $row["Quantidade"]);
    //$soma += $row["Quantidade"];   
    $cont1++;
}
$daopj->fechar();


// Create new PHPExcel object
$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

//Define alinhamento
$sheet = $objPHPExcel->getActiveSheet();


//Títulos de Colunas
foreach(range('A','F') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
    ->setAutoSize(true);
}

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'Tipo de Atendimento')
->setCellValue('B1', ''.$ano_base_menos4.'')
->setCellValue('C1', ''.$ano_base_menos3.'')
->setCellValue('D1', ''.$ano_base_menos2.'')
->setCellValue('E1', ''.$ano_base_menos1.'')
->setCellValue('F1', ''.$ano_base.'');


$objPHPExcel->getActiveSheet()->getStyle( 'A1:F1')->getFont()->setBold( true );

//Define background dos títulos do cabeçalho
$objPHPExcel->getActiveSheet()
->getStyle('A1:F1')
->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()
->setRGB('CDCDCD');

// Define o nome da folha
$objPHPExcel->getActiveSheet()->setTitle('Relatorio_Pratica_Juridica');

// Seta a primeira folha
$objPHPExcel->setActiveSheetIndex(0);

//Define a linha em que começa os dados
$linha = 1;
$coluna = 2;

$tipo = 0;
$ano_corrente= 1;
$total_geral = 0;
$total1=0;$total2=0;$total3=0;$total4=0;$total5=0;
//Conteudo
for ($i = 0; $i < $cont1; $i++) {
    
    if($registosTipoPj[$i] != $tipo){
        $linha++;
        for ($j = 0; $j < $cont; $j++) {
            if ($tipospj[$j]->getCodigo() == $registosTipoPj[$i]) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$linha, $tipospj[$j]->getNome());                
            }            
        }     
        $ano_corrente=1;        
    }
    
    if($ano_corrente==1){
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$linha, $registosPj[$i]->getPraticajuridica()->getQuantidade());
        $total1 += $registosPj[$i]->getPraticajuridica()->getQuantidade();
    }
    if($ano_corrente==2){
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$linha, $registosPj[$i]->getPraticajuridica()->getQuantidade());
        $total2 += $registosPj[$i]->getPraticajuridica()->getQuantidade();
    }
    if($ano_corrente==3){
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$linha, $registosPj[$i]->getPraticajuridica()->getQuantidade());
        $total3 += $registosPj[$i]->getPraticajuridica()->getQuantidade();
    }
    if($ano_corrente==4){
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$linha, $registosPj[$i]->getPraticajuridica()->getQuantidade());
        $total4 += $registosPj[$i]->getPraticajuridica()->getQuantidade();
    }
    if($ano_corrente==5){
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$linha, $registosPj[$i]->getPraticajuridica()->getQuantidade());
        $total5 += $registosPj[$i]->getPraticajuridica()->getQuantidade();
    }
      
    $ano_corrente++;
    
    $tipo = $registosTipoPj[$i];
    $coluna++;
}

$linha++;

//Define background dos títulos do cabeçalho
$objPHPExcel->getActiveSheet()
->getStyle('A'.$linha.':'.'F'.$linha)
->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()
->setRGB('eaeaea');

$objPHPExcel->getActiveSheet()->getStyle( 'A'.$linha.':'.'F'.$linha)->getFont()->setBold( true );

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$linha, "Total");
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$linha, "".$total1."");
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$linha, "".$total2."");
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$linha, "".$total3."");
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$linha, "".$total4."");
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$linha, "".$total5."");


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Relatorio_Pratica_Juridica.xls"');
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
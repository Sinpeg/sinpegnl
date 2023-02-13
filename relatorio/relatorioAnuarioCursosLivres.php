<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../dao/PDOConnectionFactory.php';
require_once 'dao/relatoriosDAO.php';
require_once '../../vendor/autoload.php';

require_once('../modulo/cledprofissional/dao/edprofissionallivreDAO.php');
require_once('../modulo/cledprofissional/classes/edprofissionallivre.php');
require_once('../modulo/cledprofissional/classes/tdmedprofissionallivre.php');
require_once('../modulo/cledprofissional/dao/tdmedprofissionallivreDAO.php');

//Recupera os parâmetros enviados via GET
$ano_base = $_GET['anoBase'];


$tipos = array();
$daot = new TdmedprofissionallivreDAO();
$dao = new EdprofissionallivreDAO();
$cont = 0;
$rowst = $daot->Lista();

foreach ($rowst as $row) {
    $cont++;
    $tipos[$cont] = new Tdmedprofissionallivre();
    $tipos[$cont]->setCodigo($row['Codigo']);
    $tipos[$cont]->setCategoria($row['Categoria']);
}
$tamanho = count($tipos);
$cont1 = 0;
$rows = $dao->buscaeduc($ano_base);
foreach ($rows as $row) {
    $tipo = $row['Categoria'];
    for ($i = 1; $i <= $tamanho; $i++) {
        if ($tipos[$i]->getCodigo() == $tipo) {
            $cont1++;
            $tipos[$i]->adicionaItemEdprofl($row['Codigo'], $row['NomeCurso']
                , $row['Ingressantes1'], $row['Ingressantes2'], $row['Matriculados1'], $row['Matriculados2'], $row['Aprovados1'], $row['Aprovados2'], $row['Concluintes1'], $row['Concluintes2'], $row['Ano']);
        }
    }
}
if ($cont1 == 0) {//Sem registros
    
}



// Create new PHPExcel object
$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

//Define alinhamento 	
$sheet = $objPHPExcel->getActiveSheet();

$sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

//Títulos de Colunas
foreach(range('A','E') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
    ->setAutoSize(true);
}

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'NÍVEL')
->setCellValue('B1', 'INGRESSANTES')
->setCellValue('C1', 'MATRICULADOS')
->setCellValue('D1', 'APROVADOS')
->setCellValue('E1', 'CONCLUINTES');

$objPHPExcel->getActiveSheet()->getStyle( 'A1:E1')->getFont()->setBold( true );

//Define background dos títulos do cabeçalho
$objPHPExcel->getActiveSheet()
->getStyle('A1:E1')
->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()
->setRGB('CDCDCD');

// Define o nome da folha
$objPHPExcel->getActiveSheet()->setTitle('Relatorio_Educacao_Profissional');

// Seta a primeira folha
$objPHPExcel->setActiveSheetIndex(0);


//Define a linha em que começa os dados
$linha = 2;


//Conteúdo do Arquivo
$nome_categoria = "";
$total_ingressantes = 0;
$total_matriculados = 0;
$total_aprovados = 0;
$total_concluintes = 0;

//For para varrer vetor com os resultados
for ($i = 1; $i <= $tamanho; $i++) {
    $tamanho1 = count($tipos[$i]->getEdproflivres());
    if ($tamanho1 != 0) {
        
        
        $categoria_div = explode("-", $tipos[$i]->getCategoria());
        //Inserir Linhas
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$linha.':E'.$linha);
        
        //Define background dos títulos do cabeçalho
        $objPHPExcel->getActiveSheet()
        ->getStyle('A'.$linha.':E'.$linha)
        ->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('dadada');
        
        if($nome_categoria != $categoria_div[0]){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$linha, $categoria_div[0]);
            $objPHPExcel->getActiveSheet()->getStyle( 'A'.$linha )->getFont()->setBold( true );
            $linha++;//Avança para próxima linha
            $nome_categoria = $categoria_div[0];
        }
        
        $objPHPExcel->getActiveSheet()
        ->getStyle('A'.$linha.':E'.$linha)
        ->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('e6e6fa');
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$linha, $categoria_div[1]);
        $objPHPExcel->getActiveSheet()->getStyle( 'A'.$linha)->getFont()->setBold( true );
        
        foreach ($tipos[$i]->getEdproflivres()as $a) {
                
                
                $linha++;//Avança para próxima linha
                
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$linha, $a->getNomecurso());
                
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$linha, "".$a->getIngressantes1() + $a->getIngressantes2()."");
                $total_ingressantes += $a->getIngressantes1() + $a->getIngressantes2();
                
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('C'.$linha, "".$a->getMatriculados1() + $a->getMatriculados2()."");
                $total_matriculados += $a->getMatriculados1() + $a->getMatriculados2();

                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('D'.$linha, "".$a->getAprovados1() + $a->getAprovados2()."");
                $total_aprovados += $a->getAprovados1() + $a->getAprovados2();
                                
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('E'.$linha, "".$a->getConcluintes1() + $a->getConcluintes2()."");                     
                $total_concluintes += $a->getConcluintes1() + $a->getConcluintes2();
        }       
        $linha++;//Avança para próxima linha        
    }
 }

 $objPHPExcel->setActiveSheetIndex(0)
 ->setCellValue('A'.$linha, "Total geral");
 
 $objPHPExcel->setActiveSheetIndex(0)
 ->setCellValue('B'.$linha, "".$total_ingressantes."");
 
 $objPHPExcel->setActiveSheetIndex(0)
 ->setCellValue('C'.$linha, "".$total_matriculados."");
 
 $objPHPExcel->setActiveSheetIndex(0)
 ->setCellValue('D'.$linha, "".$total_aprovados."");
 
 $objPHPExcel->setActiveSheetIndex(0)
 ->setCellValue('E'.$linha, "".$total_concluintes."");
 
 //Define background dos títulos do cabeçalho
 $objPHPExcel->getActiveSheet()
 ->getStyle('A'.$linha.':E'.$linha)
 ->getFill()
 ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
 ->getStartColor()
 ->setRGB('CDCDCD');
 
 $objPHPExcel->getActiveSheet()->getStyle( 'A'.$linha.':E'.$linha )->getFont()->setBold( true );
 


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Relatorio_Educacao_Profissional.xls"');
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
<?php

require_once '../dao/PDOConnectionFactory.php';
//require_once 'dao/relatoriosDAO.php';
require_once '../../vendor/autoload.php';

header('Content-Type: text/html; charset=utf-8');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Preenchimento' . date("d-m-Y") . '_' . date("H:m:s") . '.xls"');
header('Cache-Control: max-age=0');

require_once('../dao/PDOConnectionFactory.php');
require('../modulo/utilizacao/dao/UtilizacaoDAO.php');
require('../modulo/utilizacao/classes/Utilizacao.php');

$ano_base = $_GET['anoBase'];

$daoutil = new UtilizacaoDAO();
$rows = $daoutil->listaAplicacoes(); // Busca aplicações verificadas
$unidades = NULL;
//$ano_base = 2011;
foreach ($rows as $row) {
//    if ($codUnidade == $row["CodUnidade"]) {
    $unidades[$row['NomeUnidade']][] = array(
        'aplicacao' => $row['Nome'],
        'codap' => $row['Codigo'],
        'unidade' => $row['NomeUnidade'],
        'codunidade' => $row['CodUnidade']
    );
}
$daoutil->fechar();

// Create new PHPExcel object
$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

//Títulos de Colunas
foreach(range('A','D') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
    ->setAutoSize(true);
}

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'Unidade/Aplicação')
->setCellValue('B1', 'Situação')
->setCellValue('C1', 'Ano');


$objPHPExcel->getActiveSheet()->getStyle( 'A1:D1')->getFont()->setBold( true );

//Define background dos títulos do cabeçalho
$objPHPExcel->getActiveSheet()
->getStyle('A1:C1')
->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()
->setRGB('CDCDCD');

// Define o nome da folha
$objPHPExcel->getActiveSheet()->setTitle('Relatorio_Pratica_Juridica');

// Seta a primeira folha
$objPHPExcel->setActiveSheetIndex(0);

$linha = 2;

foreach ($unidades as $unidade => $uni) {
        $objut = new Utilizacao();
        // Ações do SIMEC
        $objut->condfigure(2, 'acao', array('`Ano` = ' => $ano_base, '`AnaliseCritica`' => '!=" "', '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Produção Intelectual
        $objut->condfigure(4, 'prodintelectual', null, "SELECT * FROM `prodintelectual` pi, `curso` c WHERE pi.`CodCurso` = c.`CodCurso` AND c.`CodUnidade` = " . $uni[0]['codunidade'] . " AND pi.`Ano` = $ano_base");
        // Prêmios
        $objut->condfigure(5, 'premios', array('`Ano` = ' => $ano_base, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Computadores
        $objut->condfigure(6, 'micros', array('`Ano` = ' => $ano_base, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Laboratorio
        $objut->condfigure(7, 'laboratorio', array('(`AnoAtivacao` = ' => $ano_base . " OR `AnoDesativacao` = $ano_base)", '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Infraestrutura de ensino
        $objut->condfigure(9, 'infraensino', array('`Ano` = ' => $ano_base, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Estrutura de Acessibilidade
        $objut->condfigure(10, 'estrutura_acessibilidade', array('`Ano` = ' => $ano_base, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Tecnologia Assistiva
        $objut->condfigure(11, 'tecnologia_assistiva', null, "SELECT * FROM `tecnologia_assistiva` ta, `curso` c WHERE ta.`CodCurso` = c.`CodCurso` AND c.`CodUnidade` = " . $uni[0]['codunidade'] . " AND ta.`Ano` = $ano_base");
        // Libras
        $objut->condfigure(12, 'librascurriculo', null, "SELECT * FROM `librascurriculo` lc, `curso` c WHERE lc.`CodCurso` = c.`CodCurso` AND c.`CodUnidade` = " . $uni[0]['codunidade'] . " AND lc.`Ano` = $ano_base");
        // Práticas Jurídicas
        $objut->condfigure(13, 'praticajuridica', array('`Ano` = ' => $ano_base, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Produção das Clínicas
        $objut->condfigure(14, 'qprodsaude', array('`Ano` = ' => $ano_base, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Patologia Tropical e Dermatologia
        $objut->condfigure(15, 'qprodsaude', array('`Ano` = ' => $ano_base, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Produção Artística
        $objut->condfigure(18, 'prodartistica', array('`Ano` = ' => $ano_base, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Atividades de Extensão
        $objut->condfigure(19, 'atividadeextensao', array('`Ano` = ' => $ano_base, '`CodUnidade` = ' => $uni[0]['codunidade']));
        //Quadro RH ICA
        $objut->condfigure(24, 'rhetemufpa', array('`Ano` = ' => $ano_base, '`CodUnidade` = ' => $uni[0]['codunidade']));
        //Produção da Farmácia
        $objut->condfigure(16, 'prodfarmacia', array('`Ano` = ' => $ano_base));
        // Frequentadores da Farmácia
        $objut->condfigure(17, 'freqfarmacia', array('`Ano` = ' => $ano_base));
        // Projetos de Extensão
        $objut->condfigure(25, 'ea_projextensao', array('`Ano` = ' => $ano_base));
        // Projetos de Pesquisa
        $objut->condfigure(20, 'ea_projpesquisa', array('`Ano` = ' => $ano_base));
        // Educação Profissional e Livre
        $objut->condfigure(26, 'edprofissionallivre', array('`Ano` = ' => $ano_base));
        // Portadores de necessidades especiais
        $objut->condfigure(27, 'pnd', array('`Ano` = ' => $ano_base));
        // Práticas de Intervenções Metodológicas
        $objut->condfigure(28, 'pi_metodologicas', array('`Ano` = ' => $ano_base));
        // Incubadora
        $objut->condfigure(30, 'prodincubadora', array('`Ano` = ' => $ano_base));
        // Ensino Fundamental
        $objut->condfigure(21, 'ensino_ea', array('`Codtdmensinoea` >= ' => '9', '`Codtdmensinoea` <= ' => '25', '`Ano` = ' => $ano_base));
        // Ensino Médio
        $objut->condfigure(22, 'ensino_ea', array('`Codtdmensinoea` >= ' => '1', '`Codtdmensinoea` <= ' => '8', '`Ano` = ' => $ano_base));
        // Infraestrutura
        $objut->condfigure(8, 'infraestrutura', array('`CodUnidade` = ' => $uni[0]['codunidade'], '(`AnoAtivacao` = ' => $ano_base . " OR `AnoDesativacao` = $ano_base)"));
        // PND
        $objut->condfigure(27, 'pnd', null, "SELECT * FROM `pnd` p, `curso` c WHERE p.`CodCurso` = c.`CodCurso` AND c.`CodUnidade` = " . $uni[0]['codunidade'] . " AND p.`Ano` = $ano_base");
       
        $utilReport = $objut->getConfig();
        
        $daout = new UtilizacaoDAO();
        
        $cont = 0;
        
        
        
        //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('G'.$linha.':G'.(count($unidades[$unidade])+$linha));        
        
        $status="";$aux=0;
        for ($i = 0; $i < count($uni); $i++) {
            //print_r($utilReport[$uni[$i]['codap']]);
           if ($utilReport[$uni[$i]['codap']]['query'] != null) {           
                if ($aux==0) {              
                   $linha++;
                   $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$linha, $unidade);
                   //Define background dos títulos do cabeçalho
                   $objPHPExcel->getActiveSheet()
                   ->getStyle('A'.$linha.':'.'C'.$linha)
                   ->getFill()
                   ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                   ->getStartColor()
                   ->setRGB('eaeaea');
                }
                $rows = $daout->consulta($utilReport[$uni[$i]['codap']]['query']);
                if ($rows->rowCount() >= 1) {
                    $status = 'Possui informação';
                } else if ($rows->rowCount() == 0) {
                    $status = 'Sem informação';
                }
            
                $linha++;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$linha, ''.$uni[$i]['aplicacao'].'');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$linha, ''.($status).'');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$linha, ''.$ano_base.'');
                $aux=1;
           } else {
                $status = "Não apurado";
            }                      
            
            $cont++;         
        }   
                
        unset($objut);
        $daout->fechar();
    }

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Preenchimento'.date("d-m-Y").'.xls"');
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

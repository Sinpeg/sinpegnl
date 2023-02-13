<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
ob_start();


//header('Content-Type: text/html; charset=utf-8');
require_once '../../classes/sessao.php';
require_once '../../dao/PDOConnectionFactory.php';

session_start();
if ($_SESSION['sessao'] == NULL) {
    print 'Sessão não foi iniciada';
    exit();
}



$aplicacoes = $_SESSION["sessao"]->getAplicacoes();
if (!$aplicacoes[33]) {
    exit();
}

//require_once('../../includes/dao/PDOConnectionFactory.php'); 
require_once('../../modulo/infra/dao/infraDAO.php');
//require_once('../../includes/classes/sessao.php'); 
require_once(dirname(__FILE__) . '/../../classes/validacao.php');

$ano = addslashes($_GET['ano']); // ano 
$unidade = $_GET['unidade']; // unidade selecionada 
$situacao = $_GET['situacao']; // situação 
$validacao = new Validacao();
/* * ******************************* VALIDAÇÃO DOS DADOS ******************************************* */

// 1 - Ano 
$validacao = new Validacao(); // objeto para o módulo de validação 
if ($validacao->is_yearempty($ano)) {
    $error = "Por favor, informe o ano!";
   
} else if (!$validacao->is_validyear($ano)) {
    $error = "Ano inválido, por favor informe outro";
   
}
// 2 - Situação 
else if ($situacao != "A" && $situacao != "D") {
    $error = "A situação informada não existe!";
   
}
// 3 - Unidade 
else if (($unidade != "todas" && $unidade != "institutos" && $unidade != "campus" && $unidade != "nucleos") && (!preg_match("/^[1-9][0-9]{0,}/", $unidade))) {
    $error = "Unidade não encontrada! ";
    echo $unidade;
}
/* * *********************************************************************************************** */ else {


    date_default_timezone_set('Europe/London');
    require_once('../../classes/relatorioxls.php');
    $title = array("Nome da Unidade", "Nome da infraestrutura", "Tipo", "Forma", "PCD", "Área",
        "Capacidade", "Hora de Início", "Hora de Fim");
    $objPHPExcel = new RelatorioXLS();
    $objPHPExcel->header();
    $objPHPExcel->maketitle($title);
    $sheet = $objPHPExcel->getActiveSheet();
    if ($situacao == "D")
        $sit_texto = "Desativada";
    else
        $sit_texto = "";
    $sheet->setCellValue('A1', 'Infraestrutura ' . $sit_texto . ' - Ano Base: ' . $ano);
    $sheet->mergeCells('A1:I1');

    switch ($unidade) {
        case "todas":
            $sql_param = "";
            break;
        case "institutos":
            $sql_param = "AND `NomeUnidade` LIKE 'instituto%'";
            break;
        case "campus":
            $sql_param = "AND `NomeUnidade` LIKE 'campus%'";
            break;
        case "nucleos":
            $sql_param = "AND `NomeUnidade` LIKE 'nucleo%'";
            break;
        default:
            $sql_param = "AND i.`CodUnidade` = " . $unidade;
            break;
    }
    
    $sql_limite = " ";
    $daoin = new InfraDAO();
    $row = $daoin->buscaInfraAdmin($ano, $situacao, $sql_param);
    $line = 3;
    $soma = 0;
    $sheet->getStyle('A:I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
    $sheet->setCellValue('A1', 'Universidade Federal do Pará' . chr(13) . 'Pró-Reitoria de Planejamento' . chr(13) .
            'Diretoria de Informações Institucionais' . chr(13) . 'Infraestrutura - Ano: ' . $ano);

    foreach ($row as $r) {
        $objPHPExcel->getActiveSheet()->setCellValue("A" . $line, $r['NomeUnidade']);
        $objPHPExcel->getActiveSheet()->setCellValue("B" . $line, ($r['NomeInfra']));
        $objPHPExcel->getActiveSheet()->setCellValue("C" . $line, ($r['NomeTipo']));
        $objPHPExcel->getActiveSheet()->setCellValue("D" . $line, $r['FORMA']);
        $objPHPExcel->getActiveSheet()->setCellValue("E" . $line, $r['PCD']);
        $objPHPExcel->getActiveSheet()->setCellValue("F" . $line, str_replace(".", ",", $r['Area']));
        $objPHPExcel->getActiveSheet()->setCellValue("G" . $line, $r['Capacidade']);
        $objPHPExcel->getActiveSheet()->setCellValue("H" . $line, $r['HoraInicio']);
        $objPHPExcel->getActiveSheet()->setCellValue("I" . $line, $r['HoraFim']);
        $soma += $r['Area'];
        $line++;
    }
    $objPHPExcel->getActiveSheet()->setCellValue("F" . $line, str_replace(".", ",", $soma));
    $objPHPExcel->getActiveSheet()->getStyle('F' . $line)->applyFromArray($objPHPExcel->getStyle2());
    $daoin->fechar();
    unset($row);
    unset($daoin);
    ob_clean();
    $file_name = "Relatorio_" . $ano . "_" . date('d/m/Y') . ".xls";
    $objPHPExcel->download($file_name);
    ob_flush();
    exit;
}
?> 

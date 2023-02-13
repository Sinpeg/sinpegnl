<?php
	ob_start();
	header('Content-Type: text/html; charset=utf-8');
	require_once '../../classes/sessao.php';
	require_once '../../dao/PDOConnectionFactory.php'; 
	require_once '../../classes/validacao.php'; 
	require_once '../../modulo/micros/dao/microsDAO.php';
	
	date_default_timezone_set('Europe/London');
	require_once('../../classes/relatorioxls.php');
	$ano = $_GET['anoBase']; // ano inicial
	$ano1 = $_GET['anoBase']; // ano final
	$unidade = "todas"; // unidade selecionada
	$sql_param = "";

	$title = array("Unidade Acadêmica", "Subunidade", "Uso Acadêmico", "Uso Academico(s/ internet)", "Uso Administrativo", "Uso Administrativo(s/ internet)", "Ano");
	$title2 = array("com internet", "sem internet", "com internet", "sem internet");
	$objPHPExcel = new RelatorioXLS();
	$objPHPExcel->header();
	$objPHPExcel->maketitle($title);
	$objPHPExcel->maketitle($title2);
	$sheet = $objPHPExcel->getActiveSheet();	
	
	$daotie = new microsDAO();
    $row = $daotie->buscamicrosadmin($sql_param, $ano, $ano1);
	$line = 4;
	$sheet->getStyle('A:G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
	$sheet->setCellValue('A1','Universidade Federal do Pará'.chr(13).'Pró-Reitoria de Planejamento'.chr(13).
			'Diretoria de Informações Institucionais'.chr(13).'Relatório de Micros - Período: '.$ano.
			' a '.$ano1);
	
	foreach ($row as $r) {
		$objPHPExcel->getActiveSheet()->setCellValue("A".$line, $r['Unidade']);
		$objPHPExcel->getActiveSheet()->setCellValue("B".$line, $r['Subunidade']);
		$objPHPExcel->getActiveSheet()->setCellValue("C".$line, ($r['AcademicoInternet']));
		$objPHPExcel->getActiveSheet()->setCellValue("D".$line, $r['Academico']);
		$objPHPExcel->getActiveSheet()->setCellValue("E".$line, $r['AdministrativoInternet']);
		$objPHPExcel->getActiveSheet()->setCellValue("F".$line, ($r['Administrativo']));
		$objPHPExcel->getActiveSheet()->setCellValue("G".$line, ($r['Ano']));
		$line++;
	}
	$sheet-> fromArray($title, null, A2);
	$sheet->mergeCells('A2:A3');
	$sheet->mergeCells('A1:G1');
	$sheet->mergeCells('B2:B3');
	$sheet->mergeCells('G2:G3');
	$sheet->mergeCells('C2:D2');
	$sheet->mergeCells('E2:F2');
	$sheet-> fromArray($title2, null, C3);
	$objPHPExcel->getActiveSheet()->getStyle("C3:F3")->getFont()->setBold(true);
	foreach(range('A','G') as $columnID) {
		$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
		->setAutoSize(true);
	}
	$daotie->fechar();
	ob_clean();
	$file_name = "Relatorio_de_utilização_de_computadores_".$ano."_".date('d/m/Y').".xls";
	$objPHPExcel->download($file_name);
	ob_flush();
	exit;
?>
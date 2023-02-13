<?php
ob_start();
echo ini_get('display_errors');
if (!ini_get('display_errors')) {
 ini_set('display_errors', 1);
 ini_set('error_reporting', E_ALL & ~E_NOTICE);
}
?>
<?php
ob_start();
header('Content-Type: text/html; charset=utf-8');
require_once '../../classes/sessao.php';
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/validacao.php';
require_once('../../modulo/biblio/dao/bibliocensoDAO.php');
require_once('../../modulo/biblio/dao/blofertaDAO.php');
require_once('../../modulo/biblio/dao/biblioEmecDAO.php');
session_start();
$aplicacoes = $_SESSION["sessao"]->getAplicacoes();

/*
if (!$aplicacoes[45]) {
	// header("Location:../../index.php");
	exit;
}
if (!isset($_SESSION["sessao"])) {
	// header("Location:../../index.php");
}
$sessao = $_SESSION["sessao"];
if ($sessao->getGrupo()!=1) {
	// header("location:../../index.php");
}
*/
?>

<?php
date_default_timezone_set('Europe/London');
require_once('../../classes/relatorioxls.php');
$ano = $_POST['ano']; // ano inicial
$ano1 = $_POST['ano1']; // ano final
$unidade = addslashes($_POST['unidade']); // unidade selecionada

if ($ano1 == "") {
	$ano1 = $ano;
}

$objPHPExcel = new RelatorioXLS();
$sheet = $objPHPExcel->getActiveSheet();
$objPHPExcel->header();
if($unidade == "todas"){ //Caso a unidade selecionada seja todas.
	
	$objPHPExcel->getActiveSheet()->setCellValue("A2", "Unidade");
	$objPHPExcel->getActiveSheet()->setCellValue("B2", "Assentos");
	$objPHPExcel->getActiveSheet()->setCellValue("C2", "Empréstimos domiciliares");
	$objPHPExcel->getActiveSheet()->setCellValue("D2", "Empréstimos entre Bibliotecas");
	$objPHPExcel->getActiveSheet()->setCellValue("E2", "Frequência");
	$objPHPExcel->getActiveSheet()->setCellValue("F2", "Consulta presencial");
	$objPHPExcel->getActiveSheet()->setCellValue("G2", "Consulta online");
	$objPHPExcel->getActiveSheet()->setCellValue("H2", "Usuários treinados em programas de capacitação");
	$objPHPExcel->getActiveSheet()->setCellValue("I2", "Itens do acervo impresso");
	$objPHPExcel->getActiveSheet()->setCellValue("J2", "Itens do acervo eletrônico");
	$objPHPExcel->getActiveSheet()->setCellValue("K2", "Usa ferramenta de busca integrada");
	$objPHPExcel->getActiveSheet()->setCellValue("L2", "Realiza comutações bibliográficas ");
	$objPHPExcel->getActiveSheet()->setCellValue("M2", "Oferece serviços pela Internet ");
	$objPHPExcel->getActiveSheet()->setCellValue("N2", "Possui rede sem fio ");
	$objPHPExcel->getActiveSheet()->setCellValue("O2", "Participa de redes sociais ");
	$objPHPExcel->getActiveSheet()->setCellValue("P2", "Possui Atendente ou Membro da Equipe de Atendimento Treinado na Língua Brasileira de Sinais - LIBRAS ");
	$objPHPExcel->getActiveSheet()->setCellValue("Q2", "Possui acervo em formato especial (Braile/sonoro)");
	$objPHPExcel->getActiveSheet()->setCellValue("R2", "Sítios e aplicações desenvolvidos para que pessoas percebam, compreendam, naveguem e utilizem serviços oferecidos");
	$objPHPExcel->getActiveSheet()->setCellValue("S2", "Plano de aquisição gradual de acervo bibliográfico dos conteúdos básicos em formato especial");
	$objPHPExcel->getActiveSheet()->setCellValue("T2", "Disponibiliza software de leitura para pessoas com baixa visão");
	$objPHPExcel->getActiveSheet()->setCellValue("U2", "Disponibiliza impressoras em Braile");
	$objPHPExcel->getActiveSheet()->setCellValue("V2", "Teclado virtual");
	$objPHPExcel->getActiveSheet()->setCellValue("W2", "Possui acesso ao Portal Capes de Periódicos");
	$objPHPExcel->getActiveSheet()->setCellValue("X2", "Assina outras bases de dados");
	$objPHPExcel->getActiveSheet()->setCellValue("Y2", "Possui biblioteca digital de Serviço Público");
	$objPHPExcel->getActiveSheet()->setCellValue("Z2", "Possui catálogo online de Serviço Público");
	
	$be = new bibliocensoDAO;
	$row = $be->buscaBiblitodos($ano);
	
	$line1 = 3;
	
	foreach($row as $r){
		$objPHPExcel->getActiveSheet()->setCellValue("A".$line1, $r['nome']);
		$objPHPExcel->getActiveSheet()->setCellValue("B".$line1, $r['nAssentos']);
		$objPHPExcel->getActiveSheet()->setCellValue("C".$line1, $r['nEmpDomicilio']);
		$objPHPExcel->getActiveSheet()->setCellValue("D".$line1, $r['nEmpBiblio']);
		$objPHPExcel->getActiveSheet()->setCellValue("E".$line1, $r['frequencia']);
		$objPHPExcel->getActiveSheet()->setCellValue("F".$line1, $r['nConsPresencial']);
		$objPHPExcel->getActiveSheet()->setCellValue("G".$line1, $r['nConsOnline']);
		$objPHPExcel->getActiveSheet()->setCellValue("H".$line1, $r['nUsuariosTpc']);
		$objPHPExcel->getActiveSheet()->setCellValue("I".$line1, $r['nItensAcervoImp']);
		$objPHPExcel->getActiveSheet()->setCellValue("J".$line1, $r['nItensAcervoElet']);
	
		if($r['fBuscaIntegrada']) {
			$objPHPExcel->getActiveSheet()->setCellValue("K".$line1, "Sim");
		} else {
			$objPHPExcel->getActiveSheet()->setCellValue("K".$line1, "Não");}
	
			if($r['comutBibliog']) {
				$objPHPExcel->getActiveSheet()->setCellValue("L".$line1, "Sim");
			} else {
				$objPHPExcel->getActiveSheet()->setCellValue("L".$line1, "Não");}
	
				if($r['servInternet']) {
					$objPHPExcel->getActiveSheet()->setCellValue("M".$line1, "Sim");
				} else {
					$objPHPExcel->getActiveSheet()->setCellValue("M".$line1, "Não");}
	
					if($r['redeSemFio']) {
						$objPHPExcel->getActiveSheet()->setCellValue("N".$line1, "Sim");
					} else {
						$objPHPExcel->getActiveSheet()->setCellValue("N".$line1, "Não");}
	
						if($r['partRedeSociais']) {
							$objPHPExcel->getActiveSheet()->setCellValue("O".$line1, "Sim");
						} else {
							$objPHPExcel->getActiveSheet()->setCellValue("O".$line1, "Não");}
	
							if($r['atendTreiLibras']) {
								$objPHPExcel->getActiveSheet()->setCellValue("P".$line1, "Sim");
							} else {
								$objPHPExcel->getActiveSheet()->setCellValue("P".$line1, "Não");}
	
								if($r['acervoFormEspecial']) {
									$objPHPExcel->getActiveSheet()->setCellValue("Q".$line1, "Sim");
								} else {
									$objPHPExcel->getActiveSheet()->setCellValue("Q".$line1, "Não");}
	
									if($r['appFormEspecial']) {
										$objPHPExcel->getActiveSheet()->setCellValue("R".$line1, "Sim");
									} else {
										$objPHPExcel->getActiveSheet()->setCellValue("R".$line1, "Não");}
	
										if($r['planoFormEspecial']) {
											$objPHPExcel->getActiveSheet()->setCellValue("S".$line1, "Sim");
										} else {
											$objPHPExcel->getActiveSheet()->setCellValue("S".$line1, "Não");}
												
											if($r['sofLeitura']) {
												$objPHPExcel->getActiveSheet()->setCellValue("T".$line1, "Sim");
											}
											else {
												$objPHPExcel->getActiveSheet()->setCellValue("T".$line1, "Não");}
	
												if($r['impBraile']) {
													$objPHPExcel->getActiveSheet()->setCellValue("U".$line1, "Sim");
												}
												else {
													$objPHPExcel->getActiveSheet()->setCellValue("U".$line1, "Não");}
	
													if($r['tecVirtual']) {
														$objPHPExcel->getActiveSheet()->setCellValue("V".$line1, "Sim");
													}
													else {
														$objPHPExcel->getActiveSheet()->setCellValue("V".$line1, "Não");
													}
	
													if($r['portalCapes']) {
														$objPHPExcel->getActiveSheet()->setCellValue("W".$line1, "Sim");
													}
													else {
														$objPHPExcel->getActiveSheet()->setCellValue("W".$line1, "Não");
													}
	
													if($r['outrasBases']) {
														$objPHPExcel->getActiveSheet()->setCellValue("X".$line1, "Sim");
													}
													else {
														$objPHPExcel->getActiveSheet()->setCellValue("X".$line1, "Não");}
	
														if($r['bdOnlineSerPub']) {
															$objPHPExcel->getActiveSheet()->setCellValue("Y".$line1, "Sim");
														}
														else {
															$objPHPExcel->getActiveSheet()->setCellValue("Y".$line1, "Não");}
																
															if($r['catOnlineSerPub']) {
																$objPHPExcel->getActiveSheet()->setCellValue("Z".$line1, "Sim");
															}
															else {
																$objPHPExcel->getActiveSheet()->setCellValue("Z".$line1, "Não");}
	++$line1;
	}

	$sheet->getStyle('A2:G32')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
	$sheet->getStyle('A:G')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
	$sheet->setCellValue('A1','Universidade Federal do Pará'.chr(13).'Pró-Reitoria de Planejamento'.chr(13).
			'Diretoria de Informações Institucionais'.chr(13).'Relatório de Bibliotecas - Ano Base: '.$ano);
	
	$sheet->mergeCells('A1:O1');
	$sheet->getStyle('A2:AA2')->getFont()->setBold(true)->setName('Arial');
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
	
}else{ //Caso uma unidade especifica seja selecionada.
	$objPHPExcel->getActiveSheet()->setCellValue("A2", "Ano base");
	$objPHPExcel->getActiveSheet()->setCellValue("A3", "Assentos");
	$objPHPExcel->getActiveSheet()->setCellValue("A4", "Empréstimos domiciliares");
	$objPHPExcel->getActiveSheet()->setCellValue("A5", "Empréstimos entre Bibliotecas");
	$objPHPExcel->getActiveSheet()->setCellValue("A6", "Frequência");
	$objPHPExcel->getActiveSheet()->setCellValue("A7", "Consulta presencial");
	$objPHPExcel->getActiveSheet()->setCellValue("A8", "Consulta online");
	$objPHPExcel->getActiveSheet()->setCellValue("A9", "Usuários treinados em programas de capacitação");
	$objPHPExcel->getActiveSheet()->setCellValue("A10", "Itens do acervo impresso");
	$objPHPExcel->getActiveSheet()->setCellValue("A11", "Itens do acervo eletrônico");
	$objPHPExcel->getActiveSheet()->setCellValue("A12", "Usa ferramenta de busca integrada");
	$objPHPExcel->getActiveSheet()->setCellValue("A13", "Realiza comutações bibliográficas ");
	$objPHPExcel->getActiveSheet()->setCellValue("A14", "Oferece serviços pela Internet ");
	$objPHPExcel->getActiveSheet()->setCellValue("A15", "Possui rede sem fio ");
	$objPHPExcel->getActiveSheet()->setCellValue("A16", "Participa de redes sociais ");
	$objPHPExcel->getActiveSheet()->setCellValue("A17", "Possui Atendente ou Membro da Equipe de Atendimento Treinado na Língua Brasileira de Sinais - LIBRAS ");
	$objPHPExcel->getActiveSheet()->setCellValue("A18", "Possui acervo em formato especial (Braile/sonoro)");
	$objPHPExcel->getActiveSheet()->setCellValue("A19", "Sítios e aplicações desenvolvidos para que pessoas percebam, compreendam, naveguem e utilizem serviços oferecidos");
	$objPHPExcel->getActiveSheet()->setCellValue("A20", "Plano de aquisição gradual de acervo bibliográfico dos conteúdos básicos em formato especial");
	$objPHPExcel->getActiveSheet()->setCellValue("A21", "Disponibiliza software de leitura para pessoas com baixa visão");
	$objPHPExcel->getActiveSheet()->setCellValue("A22", "Disponibiliza impressoras em Braile");
	$objPHPExcel->getActiveSheet()->setCellValue("A23", "Teclado virtual");
	$objPHPExcel->getActiveSheet()->setCellValue("A24", "Possui acesso ao Portal Capes de Periódicos");
	$objPHPExcel->getActiveSheet()->setCellValue("A25", "Assina outras bases de dados");
	$objPHPExcel->getActiveSheet()->setCellValue("A26", "Possui biblioteca digital de Serviço Público");
	$objPHPExcel->getActiveSheet()->setCellValue("A27", "Possui catálogo online de Serviço Público");
	$objPHPExcel->getActiveSheet()->setCellValue("A31", "Locais de oferta");//Aba 5 - Concatena
	
	if($ano<$ano1){ //Relatorio para um periodo de tempo.
		$periodo = $ano1 - $ano + 1;
		$cont = 0;
		$column = 'B'; //Inicia na coluna B2
		
		while($periodo > 0){
		$be = new bibliocensoDAO;
		$row = $be->buscaBibli($unidade, $ano+$cont);
		
		$ma = new BlofertaDAO;
		$oferta = $ma->buscaporIdbibliemec($unidade);
		
		foreach($row as $r){
			$objPHPExcel->getActiveSheet()->setCellValue($column."2", $r['ano']);
			$objPHPExcel->getActiveSheet()->setCellValue($column."3", $r['nAssentos']);
			$objPHPExcel->getActiveSheet()->setCellValue($column."4", $r['nEmpDomicilio']);
			$objPHPExcel->getActiveSheet()->setCellValue($column."5", $r['nEmpBiblio']);
			$objPHPExcel->getActiveSheet()->setCellValue($column."6", $r['frequencia']);
			$objPHPExcel->getActiveSheet()->setCellValue($column."7", $r['nConsPresencial']);
			$objPHPExcel->getActiveSheet()->setCellValue($column."8", $r['nConsOnline']);
			$objPHPExcel->getActiveSheet()->setCellValue($column."9", $r['nUsuariosTpc']);
			$objPHPExcel->getActiveSheet()->setCellValue($column."10", $r['nItensAcervoImp']);
			$objPHPExcel->getActiveSheet()->setCellValue($column."11", $r['nItensAcervoElet']);
			
			if($r['fBuscaIntegrada']) {
			$objPHPExcel->getActiveSheet()->setCellValue($column."12", "Sim");
			} else {
			$objPHPExcel->getActiveSheet()->setCellValue($column."12", "Não");}
		
			if($r['comutBibliog']) {
				$objPHPExcel->getActiveSheet()->setCellValue($column."13", "Sim");
			} else { 
				$objPHPExcel->getActiveSheet()->setCellValue($column."13", "Não");}
		
			if($r['servInternet']) {
				$objPHPExcel->getActiveSheet()->setCellValue($column."14", "Sim");
			} else {
				$objPHPExcel->getActiveSheet()->setCellValue($column."14", "Não");}
			
			if($r['redeSemFio']) {
				$objPHPExcel->getActiveSheet()->setCellValue($column."15", "Sim");
			} else {
				$objPHPExcel->getActiveSheet()->setCellValue($column."15", "Não");}
			
			if($r['partRedeSociais']) {
				$objPHPExcel->getActiveSheet()->setCellValue($column."16", "Sim");
			} else {
				$objPHPExcel->getActiveSheet()->setCellValue($column."16", "Não");}
		
			if($r['atendTreiLibras']) {
				$objPHPExcel->getActiveSheet()->setCellValue($column."17", "Sim");
			} else {
				$objPHPExcel->getActiveSheet()->setCellValue($column."17", "Não");}
			
			if($r['acervoFormEspecial']) {
				$objPHPExcel->getActiveSheet()->setCellValue($column."18", "Sim");
			} else {
				$objPHPExcel->getActiveSheet()->setCellValue($column."18", "Não");}	
		
			if($r['appFormEspecial']) {
				$objPHPExcel->getActiveSheet()->setCellValue($column."19", "Sim");
			} else {
				$objPHPExcel->getActiveSheet()->setCellValue($column."19", "Não");}
			
			if($r['planoFormEspecial']) {
				$objPHPExcel->getActiveSheet()->setCellValue($column."20", "Sim");
			} else {
				$objPHPExcel->getActiveSheet()->setCellValue($column."20", "Não");}
				
			if($r['sofLeitura']) {
				$objPHPExcel->getActiveSheet()->setCellValue($column."21", "Sim");
			} 
			else {
				$objPHPExcel->getActiveSheet()->setCellValue($column."21", "Não");}
				
			if($r['impBraile']) {
				$objPHPExcel->getActiveSheet()->setCellValue($column."22", "Sim");
			}
			else {
				$objPHPExcel->getActiveSheet()->setCellValue($column."22", "Não");}
			
			if($r['tecVirtual']) {
				$objPHPExcel->getActiveSheet()->setCellValue($column."23", "Sim");
			}
			else {
				$objPHPExcel->getActiveSheet()->setCellValue($column."23", "Não");
			}
		
			if($r['portalCapes']) {
				$objPHPExcel->getActiveSheet()->setCellValue($column."24", "Sim");
			}
			else {
				$objPHPExcel->getActiveSheet()->setCellValue($column."24", "Não");
			}
		
			if($r['outrasBases']) {
				$objPHPExcel->getActiveSheet()->setCellValue($column."25", "Sim");
			}
			else {
				$objPHPExcel->getActiveSheet()->setCellValue($column."25", "Não");}
		
			if($r['bdOnlineSerPub']) {
				$objPHPExcel->getActiveSheet()->setCellValue($column."26", "Sim");
			}
			else {
				$objPHPExcel->getActiveSheet()->setCellValue($column."26", "Não");}
				
			if($r['catOnlineSerPub']) {
				$objPHPExcel->getActiveSheet()->setCellValue($column."27", "Sim");
			}
			else {
				$objPHPExcel->getActiveSheet()->setCellValue($column."27", "Não");}
		
			}
		$line = 32;
		foreach($oferta as $of){
			$objPHPExcel->getActiveSheet()->setCellValue("A".$line, $of['nome']); // Locais de oferta
			$line++;
			}
		
		++$column;
		++$cont;
		--$periodo;
		}
	}else{ //Relatório para um ano unico
		$be = new bibliocensoDAO;
		$row = $be->buscaBibli($unidade, $ano);
		
		$ma = new BlofertaDAO;
		$oferta = $ma->buscaporIdbibliemec($unidade);
		
		foreach($row as $r){
			$objPHPExcel->getActiveSheet()->setCellValue("B2", $r['ano']);
			$objPHPExcel->getActiveSheet()->setCellValue("B3", $r['nAssentos']);
			$objPHPExcel->getActiveSheet()->setCellValue("B4", $r['nEmpDomicilio']);
			$objPHPExcel->getActiveSheet()->setCellValue("B5", $r['nEmpBiblio']);
			$objPHPExcel->getActiveSheet()->setCellValue("B6", $r['frequencia']);
			$objPHPExcel->getActiveSheet()->setCellValue("B7", $r['nConsPresencial']);
			$objPHPExcel->getActiveSheet()->setCellValue("B8", $r['nConsOnline']);
			$objPHPExcel->getActiveSheet()->setCellValue("B9", $r['nUsuariosTpc']);
			$objPHPExcel->getActiveSheet()->setCellValue("B10", $r['nItensAcervoImp']);
			$objPHPExcel->getActiveSheet()->setCellValue("B11", $r['nItensAcervoElet']);
		
			if($r['fBuscaIntegrada']) {
				$objPHPExcel->getActiveSheet()->setCellValue("B12", "Sim");
			} else {
				$objPHPExcel->getActiveSheet()->setCellValue("B12", "Não");}
		
				if($r['comutBibliog']) {
					$objPHPExcel->getActiveSheet()->setCellValue("B13", "Sim");
				} else {
					$objPHPExcel->getActiveSheet()->setCellValue("B13", "Não");}
		
					if($r['servInternet']) {
						$objPHPExcel->getActiveSheet()->setCellValue("B14", "Sim");
					} else {
						$objPHPExcel->getActiveSheet()->setCellValue("B14", "Não");}
		
						if($r['redeSemFio']) {
							$objPHPExcel->getActiveSheet()->setCellValue("B15", "Sim");
						} else {
							$objPHPExcel->getActiveSheet()->setCellValue("B15", "Não");}
		
							if($r['partRedeSociais']) {
								$objPHPExcel->getActiveSheet()->setCellValue("B16", "Sim");
							} else {
								$objPHPExcel->getActiveSheet()->setCellValue("B16", "Não");}
		
								if($r['atendTreiLibras']) {
									$objPHPExcel->getActiveSheet()->setCellValue("B17", "Sim");
								} else {
									$objPHPExcel->getActiveSheet()->setCellValue("B17", "Não");}
		
									if($r['acervoFormEspecial']) {
										$objPHPExcel->getActiveSheet()->setCellValue("B18", "Sim");
									} else {
										$objPHPExcel->getActiveSheet()->setCellValue("B18", "Não");}
		
										if($r['appFormEspecial']) {
											$objPHPExcel->getActiveSheet()->setCellValue("B19", "Sim");
										} else {
											$objPHPExcel->getActiveSheet()->setCellValue("B19", "Não");}
		
											if($r['planoFormEspecial']) {
												$objPHPExcel->getActiveSheet()->setCellValue("B20", "Sim");
											} else {
												$objPHPExcel->getActiveSheet()->setCellValue("B20", "Não");}
													
												if($r['sofLeitura']) {
													$objPHPExcel->getActiveSheet()->setCellValue("B21", "Sim");
												}
												else {
													$objPHPExcel->getActiveSheet()->setCellValue("B21", "Não");}
														
													if($r['impBraile']) {
														$objPHPExcel->getActiveSheet()->setCellValue("B22", "Sim");
													}
													else {
														$objPHPExcel->getActiveSheet()->setCellValue("B22", "Não");}
		
														if($r['tecVirtual']) {
															$objPHPExcel->getActiveSheet()->setCellValue("B23", "Sim");
														}
														else {
															$objPHPExcel->getActiveSheet()->setCellValue("B23", "Não");
														}
		
														if($r['portalCapes']) {
															$objPHPExcel->getActiveSheet()->setCellValue("B24", "Sim");
														}
														else {
															$objPHPExcel->getActiveSheet()->setCellValue("B24", "Não");
														}
		
														if($r['outrasBases']) {
															$objPHPExcel->getActiveSheet()->setCellValue("B25", "Sim");
														}
														else {
															$objPHPExcel->getActiveSheet()->setCellValue("B25", "Não");}
		
															if($r['bdOn$lineSerPub']) {
																$objPHPExcel->getActiveSheet()->setCellValue("B26", "Sim");
															}
															else {
																$objPHPExcel->getActiveSheet()->setCellValue("B26", "Não");}
																	
																if($r['catOn$lineSerPub']) {
																	$objPHPExcel->getActiveSheet()->setCellValue("B27", "Sim");
																}
																else {
																	$objPHPExcel->getActiveSheet()->setCellValue("B27", "Não");}
		
		}
		$line = 32;
		foreach($oferta as $of){
			$objPHPExcel->getActiveSheet()->setCellValue("A".$line, $of['nome']); // Locais de oferta
			$line++;
		}
	}
	$sheet->getStyle('B2:G60')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
	$sheet->getStyle('A:G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
	
	$sheet->setCellValue('A1','Universidade Federal do Pará'.chr(13).'Pró-Reitoria de Planejamento'.chr(13).
			'Diretoria de Informações Institucionais'.chr(13).'Relatório de Bibliotecas - Período: '.$ano.
			' a '.$ano1);
	$sheet->getStyle('A2:A60')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
	$sheet->getStyle('A2:A31')->getFont()->setBold(true)->setName('Arial');
	$sheet->getStyle('A29:A31')->getFont()->setName('Arial');
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
	
	$sheet->mergeCells('A28:B28');
	$sheet->mergeCells('A1:G1');
}

ob_clean();
$file_name = "Relatorio_de_bibliotecas_".$ano."_".date('d/m/Y').".xls";
$objPHPExcel->download($file_name);
?>
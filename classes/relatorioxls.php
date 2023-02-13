<?php 
require_once '../../../vendor/autoload.php'; 

/* ! 
 * \author Diego da Costa do Couto 
 * \since 09/07/2012 
 * \version 1.0 
 */ 
 
class RelatorioXLS extends \PhpOffice\PhpSpreadsheet\Spreadsheet { 
 /* ! 
 * \brief 
 * array que possui os elementos para estilizar o título do arquivo .xls 
 */ 
 private $array_style = array( 
 'font' => array( 
 'name' => 'Arial', 
 'bold' => true, 
 'italic' => false, 
 'size' => 12 
 ), 
 'alignment' => array( 
 'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 
 'vertical' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 
 'wrap' => true 
 ) 
 ); 

 private $array_style1 = array( 
	 'font' => array( 
	 'name' => 'Arial', 
	 'bold' => true, 
	 'italic' => false, 
	 'size' => 12 
	 ), 
	 'alignment' => array( 
	 'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT, 
	 'vertical' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP, 
	 'wrap' => true 
	 ), 
	 'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 
	 'color' => array( 
	 'rgb' => '1006A3' 
	 ), 
	 'fill' => array( 
	 'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 
	 'color' => array('rgb' => 'BEBEBE') 
	 ) 
 ); 
 
 
 private $array_style3 = array(
 	 'font' => array(
 	 		'name' => 'Arial',
 	 		'bold' => true,
 	 		'italic' => false,
 	 		'size' => 12
 	 ),
 	 'alignment' => array(
 	 		'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
 	 		'vertical' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
 	 		'wrap' => true
 	 ),
 	 'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
 	 'color' => array(
 	 		'rgb' => '1006A3'
 	 ),
 	 'fill' => array(
 	 		'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
 	 		'color' => array('rgb' => 'BEBEBE')
 	 )
 );
 
 
 
 private $array_style2 = array( 
 'font' => array( 
 'name' => 'Arial', 
 'bold' => false, 
 'italic' => false, 
 'size' => 10 
 ), 
 'alignment' => array( 
 'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT, 
 'vertical' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 
 'wrap' => true 
 ) 
 ); 
 
 
 private $array_style4 = array(
 		'font' => array(
 				'name' => 'Arial',
 				'bold' => false,
 				'italic' => false,
 				'size' => 10
 		),
 		'alignment' => array(
 				'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
 				'vertical' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
 				'wrap' => true
 		)
 );
 
 /** 
 * @return o array de estilo utilizado nas células dos dados que serão incluídos 
 * na planilha. 
 */ 
 public function getStyle2() { 
 return $this->array_style2; 
 } 
 
 public function getStyle4() {
 	return $this->array_style4;
 }
 /** 
 * @return 
 */ 
 public function getStyle1() { 
 return $this->array_style1; 
 } 

 public function getStyle3() {
 	return $this->array_style3;
 }
 
 /** 
 * @return 
 */ 
 public function getStyle() { 
 return $this->array_style; 
 } 
 
 private function style() { 
 $this->getActiveSheet()->getRowDimension(1)->setRowHeight(80); 
 $this->getActiveSheet()->getStyle('A1')->applyFromArray($this->array_style1); 
 } 
 
 /* ! 
 * função que gera o preâmbulo da planilha, comparado a um cabeçalho interno do arquivo, isto é, 
 * são as propriedades deste. 
 */ 
 
 function header() { 
 $this->getProperties()->setCreator("PROPLAN") 
 ->setLastModifiedBy("sisraa.ufpa.br") 
 ->setTitle("Relatório Geral das Unidades") 
 ->setSubject("Office 2007 XLSX Test Document") 
 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.") 
 ->setKeywords("office 2007 openxml php") 
 ->setCategory("Test result file"); 
 $this->setActiveSheetIndex(0); 
 $this->style(); 
 } 
 
 //! Função que gera o título das células do arquivo em .xls 
 /* ! 
 * \param $title um array que possui os títulos das colunas 
 * \return 
 */ 
 function maketitle($title) { 
 $ascii = 65; // começa com o valor A 
 foreach ($title as $t) { // iteração nas células 
 $this->getActiveSheet()->setCellValue(chr($ascii) . '2', $t); 
 $this->getActiveSheet()->getColumnDimension(chr($ascii))->setAutoSize(true); 
 $this->getActiveSheet()->getStyle(chr($ascii) . '2')->applyFromArray($this->array_style); 
 $ascii++; 
 } 
 } 
 
 /** 
 * Função que disponibiliza a planilha para download 
 * @param $file uma string que representa o nome do arquivo 
 */ 
 function download($file) { 
 header('Content-Type: application/vnd.ms-excel'); 
 header('Content-Disposition: attachment;filename="' . $file . '"'); 
 header('Cache-Control: max-age=0'); 
 $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this, 'Xls'); 
 $objWriter->save('php://output'); 
 } 
 
} 
 
?>
<?php
require_once '../../classes/pdf.php';
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../modulo/labor/dao/laboratorioDAO.php'; 
require_once '../../classes/validacao.php';
?>
<?php
// Recupera a sessão e testa se está setada
require_once '../../classes/sessao.php';
session_start();
$sessao = $_SESSION["sessao"]; // objeto da sessão
$aplicacoes = $_SESSION["sessao"]->getAplicacoes();
if (!isset($_SESSION["sessao"]) || !$aplicacoes[7]) 
{
    header("location:../../index.php");
    exit();
}
?>
<?php
ob_start();
$nomeunidade = $sessao->getNomeunidade(); // recupera o nome da unidade
$codunidade = (int) $sessao->getCodunidade(); // recupera o código da unidade
$anobase = $sessao->getAnobase(); // ano base
/* * *************************************************************************** */
$validacao = new Validacao(); // objeto para validação dos dados recebido
if ($validacao->is_yearempty($anobase)) {
    print "Erro ao processar a requisição: Ano base em branco";
} else if (!$validacao->is_validyear($anobase)) {
    print "Erro ao processar a requisição: Ano base inválido";
} else if (!is_int($codunidade) || (is_int($codunidade) && $codunidade < 0)) {
    print "Erro ao processar a requisição: Código da unidade é inválido ";
}
/* * *************************************************************************** */ 
  else {
?>
    <?php
    $data = array($nomeunidade, utf8_decode('LABORATÓRIOS - ANO BASE - ') . $anobase);
    $pdf = new pdf("L"); // instancia o objeto de pdf
    $pdf->setData($data);
    $pdf->AddPage(); // adiciona página
    $pdf->AliasNbPages();
    $w = array(60, 35, 15, 20, 20, 15, 15, 60, 15, 15);
    $a = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C');
    $pdf->SetWidths($w); // configura o array de width
    $pdf->SetAligns($a); // array de posicionamento
    $colunas = array(('Lab'), 'Curso', 'Sigla', utf8_decode('Área'), 'Capac',
        ('AP'), ('NE'), 'Local', 'SO',
        'CE');   
    $daolab = new LaboratorioDAO();
    $row = $daolab->buscaLaboratorioPDF($codunidade,$anobase);
    $categoria = " ";
    $subcategoria = " ";
    /** Busca todos os resultados * */
    foreach ($row as $r) {
        /** Lista os resultados por categoria * */
        if ($categoria != $r['Categoria']) {
            $categoria = $r['Categoria'];
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, 'Categoria: ' . utf8_decode($categoria), 0, 0, 'L');
            $pdf->Ln(10);
        }
        /** Fim da categoria * */
        /** Lista os resultados por subcategoria * */
        if ($subcategoria != $r['Subcategoria']) { // Considera a subcategoria associada a categoria
            $subcategoria = $r['Subcategoria'];
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, 'Subcategoria: ' .  utf8_decode($subcategoria), 0, 0, 'L');
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Row($colunas);
        } // Fim
        /** Formata a fonte para 12 Arial * */
        $pdf->SetFont('Arial', '', 10);
        /** Apresenta os resultados * */
        $pdf->Row(array(utf8_decode($r['Laboratorio']), $r['Curso'], $r['Sigla'], str_replace(".", ",", $r['Area']),
            $r['Capacidade'], $r['LabEnsino'],
            $r['Nestacoes'], utf8_decode($r['Local']), $r['SisOperacional'], $r['CabEstruturado']));
    } // Fim da iteração
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
// Fim
}
$daolab->fechar();
ob_flush();
?>


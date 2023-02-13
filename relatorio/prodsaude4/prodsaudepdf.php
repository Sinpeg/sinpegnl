<?php
require_once '../../classes/sessao.php';
require_once '../../classes/pdf.php';
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../dao/servprocDAO.php';
require_once '../../classes/validacao.php';
session_start();

if (!isset($_SESSION["sessao"])) {
    header("location:../../index.php");
    exit();
}
$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeUnidade();
$codunidade = $sessao->getCodUnidade();
$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
$codestruturado = $sessao->getCodestruturado();

session_start();
if (!isset($_SESSION["sessao"])) {
    header("location:../../index.php");
    exit();
}
$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
$aplicacoes = $_SESSION["sessao"]->getAplicacoes();
if (!$aplicacoes[14]) {
    exit();
}

// Recupera os parâmetros enviados via POST
$serv = $_POST['serv'];
$proc = $_POST['proc'];
$subunidade = $_POST['subunidade'];
$local = $_POST['local'];

$daoserv = new ServprocDAO();

if ($codunidade == 270) {
    $w = array(40, 40, 40, 40, 60);
    $a = array('C', 'C', 'C', 'C', 'C');
    $colunas = array(utf8_decode('Mês'), 'Discentes', 'Docentes',
        'Pesquisadores', 'Pessoas Atendidas');
    $rows = $daoserv->buscaservproced2($subunidade, $local, $anobase);
} else if ($codunidade == 202) {
    $w = array(60, 80);
    $a = array('C', 'C');
    $colunas = array(utf8_decode('Mês'), 'Quantidade de Exames');
    $rows = $daoserv->buscaservproced3($subunidade, $anobase);
} else if ($codunidade == 1644) {
    $w = array(60, 80);
    $a = array('C', 'C');
    $colunas = array(utf8_decode('Mês'), 'Pessoas Atendidas');
    $rows = $daoserv->buscaservproced3($subunidade, $anobase);
}

$pdf = new pdf("L"); // instancia o objeto de pdf

$data = array(utf8_decode('PRODUÇÃO DA ÁREA DE SAÚDE'), 'Ano Base - ' . $anobase);
$pdf->setData($data);
$pdf->Open(); // abre o arquivo
$pdf->AddPage(); // adiciona página
$pdf->AliasNbPages();


$pdf->SetWidths($w); // configura o array de width
$pdf->SetAligns($a); // array de posicionamento

$meses = array('janeiro', 'fevereiro', ('março'), 'abril', 'maio', 'junho'
    , 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro');

$servico = "";
$procedimento = "";

foreach ($rows as $row) {
    // seleciona somente os serviços
    if ($servico != $row['nomeServico']) {
        $servico = utf8_encode($row['nomeServico']);
        $procedimento = '';
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, utf8_decode('Serviço: ') . $servico, 0, 0, 'L');
        $pdf->Ln(10);
    }
    // procedimentos
    if ($procedimento != $row['nomeProcedimento']) { // Considera a subcategoria associada a categoria
        $procedimento = utf8_decode($row['nomeProcedimento']);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Procedimento: ' . $procedimento, 0, 0, 'L');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Row($colunas);
    }
    if ($codunidade == 270) { // ICS
        $pdf->Row(array($meses[$row['Mes'] - 1], $row['ndiscentes'],
            $row['ndocentes'], $row['npesquisadores'], $row['npessoasatendidas']));
    } else if ($codunidade == 202) { // 
        $pdf->Row(array($meses[$row['Mes'] - 1], $row['npessoasatendidas']));
    } else if ($codunidade == 1644) { // NMT
        $pdf->Row(array($meses[$row['Mes'] - 1], $row['nexames']));
    }
}
$pdf->Output("Relatorio.pdf", 'D');
// Fim
?>
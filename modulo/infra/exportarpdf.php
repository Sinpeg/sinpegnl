<?php
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../modulo/infra/dao/infraDAO.php';
require_once '../../classes/pdf.php';
require_once '../../classes/sessao.php';
echo "teste";
?>
<?php
session_start();
if (!isset($_SESSION["sessao"])) {
    header("location:../../index.php");
    exit();
}
?>
<?php

$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
?>
<?php

$pdf = new pdf("L"); // instancia o objeto de pdf
$data = array($nomeunidade, 'INFRAESTRUTURA - ANO BASE - ' . $anobase);
$pdf->setData($data);
$pdf->AddPage(); // adiciona página
$pdf->AliasNbPages();

$w = array(60, 80, 30, 12, 30, 30, 20, 20);
$a = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C');
$pdf->SetWidths($w); //configura o array de width
$pdf->SetAligns($a);

$colunas = array("Tipo", "Nome", ("Forma"),
    "PCD", "Área", "Capacidade", "Hora Início", "Hora Fim");
$pdf->SetFont('Arial', 'B', 12);
$pdf->Row($colunas);
$daoin = new InfraDAO();
$row = $daoin->buscaInfraPDF($codunidade);
$pdf->SetFont('Arial', '', 12);
foreach ($row as $r) {
    $pdf->Row(array($r["NomeTipo"], $r['NomeInfra'], ($r['FORMA']), ($r['PCD']),
        str_replace(".", ",", $r['Area']), $r['Capacidade'], $r['HoraInicio'], $r['HoraFim']));
}
// disponibiliza o relatório para download
$nome = explode(" ", $nomeunidade);
$nome = implode("_", $nome);
$pdf->Output("Relatorio_$nome.pdf", 'D');
// Fim
?>
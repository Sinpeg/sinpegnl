<?php
require '../../dao/PDOConnectionFactory.php';
//require_once(dirname(__FILE__) . '/../../dao/PDOConnectionFactory.php');
require_once(dirname(__FILE__) . '/../../modulo/infraensino/dao/tipoinfraensinoDAO.php');
?>
<?php
/* grant */
require_once '../../classes/sessao.php';
session_start();
$sessao = $_SESSION["sessao"];
if (!isset($sessao)) {
 exit(0);
} else {
 $aplicacoes = $sessao->getAplicacoes();
 if (!$aplicacoes[9]) { // Acessibilidade
 exit(0);
 }
}
?>
<?php
$ano = addslashes($_POST['ano']); // ano inicial
$ano1 = addslashes($_POST['ano1']); // ano final
$txtUnidade = addslashes($_POST['txtUnidade']); // unidade selecionada
$tipo = $_POST['tipo']; // tipo da infraestrutura de ensino
$dados = array();
$erro = NULL;
if ($ano == "") {
 $erro = "Preencha o <strong>ano inicial.</strong>";
} else if (!preg_match("/^[1-9][0-9]{3,3}$/", $ano)) {
 $erro = "O <strong> ano inicial</strong> deve conter 4 dígitos e não iniciar com 0 (Ex.: 2000)";
} elseif ($ano1 == "") {
 $erro = "Preencha o <strong> ano final</strong>";
} else if (!preg_match("/^[1-9][0-9]{3,3}$/", $ano1)) {
 $erro = "O <strong> ano final</strong> deve conter 4 dígitos e não iniciar com 0 (Ex.: 2000)";
} else if ($ano1 < $ano) {
 $erro = "O <strong> ano final</strong> deve ser maior ou igual ao <strong> ano inicial</strong>";
} else if (!preg_match("/^[a-zA-Z]+([a-zA-Z\s]*)[a-zA-Z]+$/", $txtUnidade)) {
 $erro = "Formato inválido para a pesquisa da unidade";
}if (isset($erro)) {
 echo "<div class='alert alert-danger'>$erro</div>";
} else {
 $daotie = new TipoinfraensinoDAO();
 $row = $daotie->buscatipo($tipo, $ano, $ano1, $txtUnidade);
 $cat = array();
 $cont = 0;
 $ano = array();
 foreach ($row as $r) {
 $cat[$r["Nome"]] = $r["Nome"]; // primeiro header
 $dados[$r["NomeUnidade"]][$r["Nome"]][$r["Ano"]][] = array(
 "Quantidade" => $r["Quantidade"],
 "Ano" => $r["Ano"]
 );
 $ano[$r["Ano"]] = $r["Ano"];
 $cont++;
 }
 foreach ($dados as $key1 => $dados1) {
 foreach ($dados1 as $key2 => $dados2) {
 if (count($dados[$key1][$key2]) != count($ano)) {
 foreach ($ano as $a) {
 if (!isset($dados[$key1][$key2][$a])) {
 $dados[$key1][$key2][$a][] = array(
 "Quantidade" => 'x',
 "Ano" => $a
 );
 }
 }
 }
 }
 }
 $totalAno = 0;
 ?>
<?php if ($row->rowCount()==0): ?>
<div class="alert alert-warning">Nenhum resultado foi encontrado!</div>
<?php else: ?>
 <div id="download-list">
 <ul>
 <li class="xls-type"><a href="relatorio/infraensino/relatorioXLS.php" class="XLS">Download da planilha</a></li>
 <li class="chart-type"><a href="ajax/infraensino/barchart.php?y=infraensino" class="chart">Gráfico da Infraestruturas de Ensino</a></li>
 <?php if ($ano == $ano1): ?>
 <li class="chart-type" id="piechart"><a href="ajax/infraensino/piechart.php?y=area" class="chart">Percentual por tipo da Infraestrutura</a></li>
 <? endif; ?>
 </ul>
 </div>
 <div id="render"></div>
 <table class="tab_resultado table table-hover">
 <thead>
 <tr>
 <th rowspan="2" style="vertical-align: middle; text-align: center">Unidade</th>
 <?php if ($tipo == 0): ?>
 <th rowspan="2" style="vertical-align: middle; text-align: center">Categoria</th>
 <?php endif; ?>
 <th colspan="<?php echo count($ano); ?>" style="vertical-align: middle; text-align: center">Ano</th>
 <th rowspan="2" style="vertical-align: middle; text-align: center">Total</th>
 </tr>
 <tr>
 <?php foreach ($ano as $a): ?>
 <td style="vertical-align: middle; text-align: center"><?php echo $a; ?></td>
 <?php endforeach; ?>
 </tr>
 </thead>
 <tbody>
 <?php foreach ($dados as $key => $value): ?>
 <tr>
 <td rowspan="<?php echo (count($cat) + 1); ?>"><?php echo $key; ?></td>
 </tr>
 <?php foreach ($value as $key1 => $value1): ?> 
 <tr>
 <?php if ($tipo == 0): ?>
 <td><?php echo ($key1); ?></td>
 <?php endif; ?>
 <?php $totalAno = 0; ?>
 <?php foreach ($value1 as $key2 => $value2):
 ?>
 <?php foreach ($value2 as $key3 => $value3): ?>
 <?php
 if ($value3["Quantidade"] == "x")
 echo "<td style='color:red'><strong>0</strong></td>";
 else
 echo "<td>" . $value3["Quantidade"] . "</td>";
 ?> 
 <?php
 $totalAno += $value3["Quantidade"];
 ?>
 <?php endforeach; ?> 
 <?php endforeach; ?>
 <td><strong><?php echo $totalAno; ?></strong></td>
 <?php endforeach; ?> 
 </tr>
 <?php endforeach; ?>
 </tbody> 
 </table>
 <?php endif; ?>
<?php } ?>

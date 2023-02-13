<?php
require_once('../../dao/PDOConnectionFactory.php');
require_once('../../modulo/acessib/dao/tpacessibilidadeDAO.php');
//require_once(dirname(__FILE__) . '/../../includes/classes/validacao.php'); // componente para validação
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
 if (!$aplicacoes[10]) { // Acessibilidade
 exit(0);
 }
}
?>
<?php
$ano = $_POST['ano']; // ano inicial
$ano1 = $_POST['ano1']; // ano final
$tipo = $_POST['tipo']; // tipo da infraestrutura de ensino
$txtUnidade = $_POST['txtUnidade']; // unidade
?>
<?php
// validação temporária
$dados = array();
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
} else if (!preg_match("/^([a-zA-Z]+)((\s){1,1}[a-zA-Z]+)*$/", $txtUnidade)) {
 $erro = "Formato inválido para a pesquisa da unidade";
}if (isset($erro)) {
 echo "<div class='alert alert-danger'>$erro</div>";
} else {
 $daoacess = new TpacessibilidadeDAO();
 $row = $daoacess->buscaestruturaacess($ano, $ano1, $tipo, $txtUnidade);
 $daoacess->fechar();
 ?>
 <?php
 $anos = array();
 foreach ($row as $r) {
 $cat[$r["Nome"]] = $r["Nome"]; // primeiro header
 $dados[$r["NomeUnidade"]][$r["Nome"]][$r["Ano"]][] = array(
 "Quantidade" => $r["Quantidade"],
 "Ano" => $r["Ano"]
 );
 $anos[$r["Ano"]] = $r["Ano"];
 }
 foreach ($dados as $key1 => $dados1) {
 foreach ($dados1 as $key2 => $dados2) {
 if (count($dados[$key1][$key2]) != count($anos)) {
 foreach ($anos as $a) {
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
 <li class="xls-type"><a href="relatorio/acessib/relatorioXLS.php" class="XLS">Download da planilha</a></li>
 <li class="chart-type"><a href="ajax/acessib/barchart.php?y=acessib" class="chart">Gráfico da Estrutura de Acessibilidade</a></li>
 <?php if ($ano==$ano1 && $tipo=="todos") :?>
 <li class="chart-type" id="piechart"><a href="ajax/acessib/piechart.php?y=area" class="chart">Percentual por tipo de Estrutura de Acessibilidade</a></li>
 <?php endif; ?>
 </ul>
 </div>
 <div id="render"></div> 
 <table class="tab_resultado table table-hover">
 <thead>
 <tr>
 <th rowspan="2" style="vertical-align: middle; text-align: center">Unidade</th>
 <?php if ($tipo == "todos"): ?>
 <th rowspan="2" style="vertical-align: middle; text-align: center">Categoria</th>
 <?php endif; ?>
 <th colspan="<?php echo count($anos); ?>" style="vertical-align: middle; text-align: center">Ano</th>
 <th rowspan="2" style="vertical-align: middle; text-align: center">Total</th>
 </tr>
 <tr>
 <?php foreach ($anos as $a): ?>
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
 <?php if ($tipo == "todos"): ?>
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

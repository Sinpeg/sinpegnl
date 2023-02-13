<?php
require_once '../../classes/sessao.php';
session_start();
?>
<?php
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/unidade.php';
require_once '../../dao/unidadeDAO.php';
require_once('../../modulo/biblio/dao/biblioEmecDAO.php');

$modulo = $_POST['modulo']; // módulo de trabalho: veja usajax2.js
$unidade = $_POST['unidade'];// unidade
$ano = $_POST['ano'];
$ano1 = $_POST['ano1']; // ano final
$daoun = new biblioEmecDAO(); // objeto de acesso ao banco de dados
if($unidade=="todas"){ $unidade = "biblio";}
$row = $daoun->buscaUnidade($unidade); // busca a unidade
?>
<?php
if (trim($unidade)=="") {
 print "%Preencha o nome da unidade!";
}
else if(!preg_match("/^[a-z|A-Z]{4,}$/", str_replace(' ', '', $unidade))) {
 print "%Nome da unidade inválida! ";
}
else {
?>
<label for="selunidade">Selecione a biblioteca:</label>
<select class="sel" id="selunidade" name="selunidade" size="4">
 <option value="todas" class="opt">TODAS</option>
 <?php foreach ($row as $r) {?>
 <option value=<?php print $r["idBibliemec"]; ?> class="opt">
 <?php print $r["nome"]; ?>
 </option>
 <?php }?>
</select>
<?php }?>

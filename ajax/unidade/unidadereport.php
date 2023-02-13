<?php
require_once '../../classes/sessao.php';
session_start();

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/unidade.php';
require_once '../../dao/unidadeDAO.php';
//$modulo = $_POST['modulo']; // módulo de trabalho: veja usajax2.js
$unidade = $_POST['unidade'];// unidade
$daoun = new UnidadeDAO(); // objeto de acesso ao banco de dados
$row = $daoun->buscalunidade($unidade); // busca a unidade

/*if (trim($unidade)=="") {
 print "%Preencha o nome da unidade!";
}
else if(!preg_match("/^[a-z|A-Z]{4,}$/", str_replace(' ', '', $unidade))) {
 print "%Nome da unidade inválida! ";
}
else {?>
*/
<table>
<tr><td>

</td></tr>
</table>
?>

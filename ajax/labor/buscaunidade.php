<?php
/* grant */
require_once '../../classes/sessao.php';
session_start();
$sessao = $_SESSION["sessao"];
if (!isset($sessao)) {
 exit(0);
}
?>
<?php
 require '../../dao/PDOConnectionFactory.php';
 require '../../dao/unidadeDAO.php';
 $str = $_GET['name_startsWith'];
 $maxRows = $_GET['maxRows'];
 $daoun = new UnidadeDAO();
 $rows = $daoun->unidadePorStr($str, $maxRows);
 $unidades = array();
 foreach ($rows as $row) {
 $unidades[] = array('nome'=>$row['NomeUnidade'], 'codigo'=>$row['CodUnidade']);
 }
 echo json_encode($unidades);
// require../../classes/..//';
?>
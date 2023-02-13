<?php
sleep(1);
/* grant */
require_once '../../classes/sessao.php';
session_start();
$sessao = $_SESSION["sessao"];
if (!isset($sessao)) {
 exit(0);
} else {
 $aplicacoes = $sessao->getAplicacoes();
 if (!$aplicacoes[7]) { // laboratório
 exit(0);
 }
}
?>
<?php
// $sessao = $_SESSION["sessao"];
// $nomeunidade = $sessao->getNomeunidade();
// $codunidade = $sessao->getCodunidade();
// $responsavel = $sessao->getResponsavel();
// $anobase = $sessao->getAnobase();
define('BASE_DIR', dirname(__FILE__));
require_once(BASE_DIR . DIRECTORY_SEPARATOR . '../../dao/PDOConnectionFactory.php');
require_once(BASE_DIR . DIRECTORY_SEPARATOR . '../../modulo/labor/dao/tplaboratorioDAO.php');
require_once(BASE_DIR . DIRECTORY_SEPARATOR . '../../modulo/labor/classes/tplaboratorio.php');
$cont = 0;
$daocat = new TplaboratorioDAO();
$categoria = addslashes($_POST["categoria"]);
$rows = $daocat->buscacattipo($categoria);
$tplab = array();
foreach ($rows as $row) {
 $tplab[$cont] = new Tplaboratorio();
 $tplab[$cont]->setCodigo($row['Codigo']);
 $tplab[$cont]->setNome($row['Nome']);
 $cont++;
}
$display = "<label>Tipo:</label><select name=tlab>";
$display .= "<option value=0>--Selecione o tipo de laboratório--</option>"; 
$display .= "<option value=todas>Todas</option>"; 
foreach ($tplab as $tt) {
 $display .="<option value=";
 $display .=$tt->getCodigo() . ">";
 $display .= $tt->getNome() . "</option>";
}
$display.="</select>";
$daocat->fechar();
echo $display;
?>

<?php
ob_start();
echo ini_get('display_errors');
if (!ini_get('display_errors')) {
 ini_set('display_errors', 1);
 ini_set('error_reporting', E_ALL & ~E_NOTICE);
}
?>
<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
session_start();
if (!isset($_SESSION["sessao"])) {
 header("location:index.php");
}
$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
//require_once('../../includes/dao/PDOConnectionFactory.php');
// var_dump($usuario);
require_once('dao/infraDAO.php');
require_once('classes/infraestrutura.php');
//require_once('../../includes/classes/unidade.php');
//require_once('../../includes/dao/unidadeDAO.php');
require_once('classes/tipoinfraestrutura.php');
$daoin = new InfraDAO();
$tipoin1 = new Tipoinfraestrutura();
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
if (isset($_POST["pcd"])) {
 $pcd = "S";
} else {
 $pcd = "N";
}
$npn = $_POST["npn"];
$npa = $_POST["npa"];
$nphi = $_POST["nphi"];
$nphf = $_POST["nphf"];
$pad = $_POST["pad"];
$npr1 = $_POST["npr"];
$npr = addslashes(str_replace(",", ".", $npr1));
$npc = $_POST["npc"];
$codtinfra = addslashes($_POST["codtinfra"]);
if (is_string($npn) && $npn != "" && is_string($npa)
//&& is_string($nphi) && is_string($nphf)
 && $npr != "" && is_numeric($npc) && $npc != "" && is_numeric($pad) && $pad != "") {
 if ($_POST["operacao"] == "I") {
 $tipoin1->setCodigo($codtinfra);
 $tipoin1->criaInfraestrutura(null, $unidade, $anobase, $npn, $npa, $nphi, $nphf, $pad, $pcd, $npr, $npc, null, "A");
 $daoin->insere($tipoin1);
 } elseif ($_POST["operacao"] == "A") {
 $codti = $_POST["codti"];
 $situacao = $_POST["situacao"];
 if (is_string($codti)) {
 $tipoin1->setCodigo($codtinfra);
 $tipoin1->criaInfraestrutura($codti, $unidade, null, $npn, $npa, $nphi, $nphf, $pad, $pcd, $npr, $npc, $anobase, $situacao);
 $daoin->altera($tipoin1);
 }
 }
 $daoin->fechar();
} else {
 $daoin->fechar();
 $mensagem = urlencode(" ");
 $cadeia = "location:../saida/erro.php?codigo=1&mensagem=" . $mensagem;
 header($cadeia);
 //exit();
}
Flash::addFlash('Operação realizada com sucesso!');
Utils::redirect('infra', 'consultainfra');
//exit();
//ob_end_flush();
?>

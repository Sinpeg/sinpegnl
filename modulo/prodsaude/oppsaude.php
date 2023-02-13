<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('classes/qprodsaude.php');
require_once('dao/qprodsaudeDAO.php');
require_once('classes/prodsaude.php');
session_start();
if (!isset($_SESSION["sessao"])) {
    header("location:../../index.php");
//	exit();;
}

$sessao = $_SESSION["sessao"];
$nomeUnidade = $sessao->getNomeunidade();
$codUnidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
$aplicacoes = $_SESSION["sessao"]->getAplicacoes();

if (!$aplicacoes[15]) {
    header("Location:index.php");
//    exit();
}
//require_once('../../includes/classes/unidade.php');
$unidade = new Unidade();
$unidade->setCodunidade($codUnidade);
$unidade->setNomeunidade($nomeUnidade);
$tipo = array();
for ($i = 1; $i <= 47; $i++) {
    $tipo[$i] = 0;
}


foreach ($_POST as $key => $element){
	if(is_numeric($key)){
		if($element == null){
			$element = 0;
		}
		$arrayquantidade[$key] = $element;
	}
}



// $tipo[1] = $_POST["q1"];
// $tipo[2] = $_POST["q2"];
// $tipo[3] = $_POST["q3"];
// $tipo[4] = $_POST["q4"];
// $tipo[5] = $_POST["q5"];
// $tipo[6] = $_POST["q6"];
// $tipo[7] = $_POST["q7"];
// $tipo[8] = $_POST["q8"];
// $tipo[9] = $_POST["q9"];
// $tipo[10] = $_POST["q10"];
// $tipo[11] = $_POST["q11"];
// $tipo[12] = $_POST["q12"];
// $tipo[13] = $_POST["q13"];
// $tipo[14] = $_POST["q14"];
// $tipo[15] = $_POST["q15"];
// $tipo[16] = $_POST["q16"];
// $tipo[17] = $_POST["q17"];
// $tipo[18] = $_POST["q18"];
// $tipo[19] = $_POST["q19"];
// $tipo[20] = $_POST["q20"];
// $tipo[21] = $_POST["q21"];
// $tipo[22] = $_POST["q22"];
// $tipo[23] = $_POST["q23"];
// $tipo[24] = $_POST["q24"];
// $tipo[25] = $_POST["q25"];
// $tipo[26] = $_POST["q26"];
// $tipo[27] = $_POST["q27"];
// $tipo[28] = $_POST["q28"];
// $tipo[29] = $_POST["q29"];
// $tipo[30] = $_POST["q30"];
// $tipo[31] = $_POST["q31"];
// $tipo[32] = $_POST["q32"];
// $tipo[33] = $_POST["q33"];
// $tipo[34] = $_POST["q34"];
// $tipo[35] = $_POST["q35"];
// $tipo[36] = $_POST["q36"];
// $tipo[37] = $_POST["q37"];
// $tipo[38] = $_POST["q38"];
// $tipo[39] = $_POST["q39"];
// $tipo[40] = $_POST["q40"];
// $tipo[41] = $_POST["q41"];
// $tipo[42] = $_POST["q42"];
// $tipo[43] = $_POST["q43"];
// $tipo[44] = $_POST["q44"];
// $tipo[45] = $_POST["q45"];
// $tipo[46] = $_POST["q46"];
// $tipo[47] = $_POST["q47"];
// $tipo[48] = $_POST["q48"];
// $tipo[49] = $_POST["q49"];
// $tipo[50] = $_POST["q50"];
// $tipo[51] = $_POST["q51"];
// $tipo[52] = $_POST["q52"];
// $tipo[53] = $_POST["q53"];
// $tipo[54] = $_POST["q54"];
// $tipo[55] = $_POST["q55"];
// $tipo[56] = $_POST["q56"];

// if (is_numeric($tipo[1]) && is_numeric($tipo[2]) && is_numeric($tipo[3]) && is_numeric($tipo[4]) && is_numeric($tipo[5]) && is_numeric($tipo[6]) && is_numeric($tipo[7]) && is_numeric($tipo[8]) && is_numeric($tipo[9]) && is_numeric($tipo[10]) && is_numeric($tipo[11]) && is_numeric($tipo[12]) && is_numeric($tipo[13]) && is_numeric($tipo[14]) && is_numeric($tipo[15]) && is_numeric($tipo[16]) && is_numeric($tipo[17]) && is_numeric($tipo[18]) && is_numeric($tipo[19]) && is_numeric($tipo[20]) && is_numeric($tipo[21]) && is_numeric($tipo[22]) && is_numeric($tipo[23]) && is_numeric($tipo[24]) && is_numeric($tipo[25]) && is_numeric($tipo[26]) && is_numeric($tipo[27]) && is_numeric($tipo[28]) && is_numeric($tipo[29]) && is_numeric($tipo[30]) && is_numeric($tipo[31]) && is_numeric($tipo[32]) && is_numeric($tipo[33]) && is_numeric($tipo[34]) && is_numeric($tipo[35]) && is_numeric($tipo[36]) && is_numeric($tipo[37]) && is_numeric($tipo[38]) && is_numeric($tipo[39]) && is_numeric($tipo[40]) && is_numeric($tipo[41]) && is_numeric($tipo[42]) && is_numeric($tipo[43]) && is_numeric($tipo[44]) && is_numeric($tipo[45]) && is_numeric($tipo[46]) && is_numeric($tipo[47]) && is_numeric($tipo[48]) && is_numeric($tipo[49]) && is_numeric($tipo[50]) && is_numeric($tipo[51]) && is_numeric($tipo[52]) && is_numeric($tipo[53]) && is_numeric($tipo[54]) && is_numeric($tipo[55]) && is_numeric($tipo[56])) {

    $vprodsaude = array();
    $cont = 0;

    $qpsDAO = new qprodsaudeDAO();
    foreach ($arrayquantidade as $i => $qtde) {
        $cont++;
        $vprodsaude[$cont] = new Prodsaude();
        $vprodsaude[$cont]->setCodigo($i);

        $consulta = $qpsDAO->buscaQps($anobase, $i);
        $passou = false;
        foreach ($consulta as $row) {
            $passou = true;
            //$qpsDAO->altera($vprodsaude[$cont]);
            $vprodsaude[$cont]->criaQprodsaude($row["Codigo"], $unidade, $qtde, $anobase);
        }//for
        if (!$passou) {
            $vprodsaude[$cont]->criaQprodsaude(null, $unidade, $qtde, $anobase);
        }
    }
    if (!$passou) {
		Flash::addFlash('Procedimentos cadastrados com sucesso!! Voce está na téla de consulta.');
        $qpsDAO->inseretodos($vprodsaude);
    } else {
    	Flash::addFlash('Procedimentos Alterados com sucesso!! Voce está na téla de consulta.');
        $qpsDAO->alteratodos($vprodsaude);
    }

    $qpsDAO->fechar();
// }
Utils::redirect('prodsaude', 'consultapsaude');
?>
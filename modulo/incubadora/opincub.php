<?php

//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('classes/incubadora.php');
require_once('dao/incubadoraDAO.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[30]) {
    header("Location:index.php");
}
$qtdEmpg = $_POST["qtdEmpg"];
$qtdEmpgs = $_POST["qtdEmpgs"];
$qtdProja = $_POST["qtdProja"];
$qtdEmpcap = $_POST["qtdEmpcap"];
$qtdEven = $_POST["qtdEven"];
$qtdCap = $_POST["qtdCap"];
$qtdCons = $_POST["qtdCons"];
$qtdFei = $_POST["qtdFei"];
$operacao = $_POST["operacao"];
//$sessao = $_SESSION["sessao"];
//$nomeUnidade = $sessao->getNomeunidade();
$codUnidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
if (is_numeric($qtdEmpg) && $qtdEmpg != "" && is_numeric($qtdEmpgs) && $qtdEmpgs != "" &&
        is_numeric($qtdProja) && $qtdProja != "" && is_numeric($qtdEmpcap) && $qtdEmpcap != "" &&
        is_numeric($qtdEven) && $qtdEven != "" && is_numeric($qtdCap) && $qtdCap != "" &&
        is_numeric($qtdCons) && $qtdCons != "" && is_numeric($qtdFei) && $qtdFei != "") {
    $daope = new IncubadoraDAO();
    $incubadora = new Incubadora();
    switch ($codUnidade) {
        case "277": // ICSA
            $tipo = "S"; // social
            break;
        case "962": // UNIVERSITEC
            $tipo = "E"; // empresas
        default:
            break;
    }
    $incubadora->setEmpresasgrad($qtdEmpg);
    $incubadora->setEmpgerados($qtdEmpgs);
    $incubadora->setProjaprovados($qtdProja);
    $incubadora->setEventos($qtdEven);
    $incubadora->setCapacitrh($qtdCap);
    $incubadora->setNempreendedores($qtdEmpcap);
    $incubadora->setConsultorias($qtdCons);
    $incubadora->setPartempfeiras($qtdFei);
    $incubadora->setAno($anobase);
    $incubadora->setUnidade($codUnidade);
    $incubadora->setTipo($tipo);
    if ($operacao == "I") {
        $daope->Insere($incubadora);
    } else {
        $codigo = $_POST["codigo"];
        if (is_numeric($codigo)) {
            $incubadora->setCodigo($codigo);
            $daope->altera($incubadora);
        }
    }
    $daope->fechar();
    Utils::redirect('incubadora', 'consultaincub');
} else {
}
//ob_end_flush();
?>
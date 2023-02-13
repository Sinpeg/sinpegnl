<?php
require_once('classes/producaoartistica.php');
require_once('dao/producaoartisticaDAO.php');
require_once('classes/tipoproducaoartistica.php');
require_once('dao/tipoproducaoartisticaDAO.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[18]) {
    header("Location:index.php");
}
$operacao = $_POST["operacao"];
$qtdPTeatro = $_POST["qtdPTeatro"];
$qtdConcertos = $_POST["qtdConcertos"];
$qtdPerformances = $_POST["qtdPerformances"];
//$sessao = $_SESSION["sessao"];
$nomeUnidade = $sessao->getNomeunidade();
$codUnidade = $sessao->getCodunidade();
$anobase = $sessao->getAnobase();
$daopa = new ProducaoartisticaDAO();

if ($operacao=="Excluir")
{
	$daopa->deleta($codunidade, $anobase); 
	Utils::redirect('prodartistica', 'consultaprodartistica');
}
else {

if (is_numeric($qtdPTeatro) && $qtdPTeatro != "" &&
        is_numeric($qtdConcertos) && $qtdConcertos != "" &&
        is_numeric($qtdPerformances) && $qtdPerformances != "") {
    $unidade = new Unidade();
    $unidade->setCodunidade($codUnidade);
    $unidade->setNomeunidade($nomeUnidade);
    
    $tipoPA = array();
    $pa = array();
    $tipoPA["1"] = $qtdPTeatro;
    $tipoPA["2"] = $qtdConcertos;
    $tipoPA["3"] = $qtdPerformances;
    $cont = 0;
    $paDAO = new ProducaoartisticaDAO();
    foreach ($tipoPA as $i => $tpPA) {
        $cont++;
        $pa[$cont] = new Tipoproducaoartistica();
        $pa[$cont]->setCodigo($i);
        $consulta = $daopa->buscapa($codUnidade, $anobase, $i);
        $passou = false;
        foreach ($consulta as $row) {
            $passou = true;
            $pa[$cont]->criaProdartistica($row["Codigo"], $unidade, $anobase, $tpPA);
        }//for
        if (!$passou) {
            $pa[$cont]->criaProdartistica(null, $unidade, $anobase, $tpPA);
        }
    }
    if (!$passou) {
        $paDAO->inseretodos($pa);
    } else {
        $paDAO->alteratodos($pa);
    }

    
    Flash::addFlash('Quantitativo da produção artística cadastrado com sucesso!');
    Utils::redirect('prodartistica', 'consultaprodartistica');
} else {
     Error::addErro('Erro encontrado durante o cadastro do quantitativo da produção artística');
     Utils::redirect('prodartistica', 'consultaprodartistica');
  }

}

$daopa->fechar();
ob_end_flush();
?>
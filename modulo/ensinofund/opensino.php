<?php

ob_start();
echo ini_get('display_errors')."erro";
if (!ini_get('display_errors')) {
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL & ~E_NOTICE);
}
?>
<?php

//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//require_once('../../includes/dao/PDOConnectionFactory.php');
//require_once('../../includes/classes/ensinoea.php');
//require_once('../../includes/dao/ensinoeaDAO.php');
//require_once('../../includes/classes/tdmensinoea.php');
//require_once('../../includes/classes/unidade.php');
//session_start();
if (!isset($_SESSION["sessao"])) {
    header("location:index.php");
}
$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anoBase = $sessao->getAnobase();

$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
//Valida campos
$operacao = $_POST["operacao"];
$em = $_POST["em"];
$ea = $_POST["ea"];
$er = $_POST["er"];
$matinv = false;
$repinv = false;
$aprinv = false;
if (is_array($em) && is_array($ea) && is_array($er)) {
    foreach ($em as $i => $qtde) {
        if (!is_numeric($qtde) && $qtde != "") {
            $matinv = true;
            break;
        }
    }
    foreach ($er as $i => $qtde) {
        if (!is_numeric($qtde) && $qtde != "") {
            $repinv = true;
            break;
        }
    }
    foreach ($ea as $i => $qtde) {
        if (!is_numeric($qtde) && $qtde != "") {
            $aprinv = true;
            break;
        }
    }
}

if ($matinv || $repinv || $aprinv) {
    echo "Erro! Comunicar o administrador do sistema.";die;
}
$invalidado = false;

foreach ($ea as $i => $qea) {
    foreach ($er as $j => $qer) {
        foreach ($em as $k => $qem) {
            if ($i == $j && $i == $k && $qem < $qer + $qea) {
                $invalidado = true;
                break;
            }
        }
    }
}

if ($invalidado) {
    echo "Erro invalidado! Os dados estão inconsistentes!";die;
}
$dao = new EnsinoeaDAO();

$cont = 0;
$rows_tea = $dao->ListaEf($anoBase);
//echo "contagem<br>";
foreach ($rows_tea as $row) {
    $cont++;
    $tiposea[$cont] = new Tdmensinoea();
    $tiposea[$cont]->setCodigo($row['Codigo']);
    print $tiposea[$cont]->getCodigo() . "<br/>";
}
$i = 0;

$tamanho = $cont;
$tamanho2 = count($em);
if ($operacao == "I") {
    for ($cont1 = 1; $cont1 <= $tamanho; $cont1++) {
        for ($cont2 = 0; $cont2 < $tamanho2; $cont2++) {
            if ($cont1 == $cont2 + 1) {
//                echo "teste";
                $tiposea[$cont1]->criaEnsinoea(null, $em[$cont2], $ea[$cont2], $er[$cont2], $anoBase);
            }
        }
    }
    foreach ($tiposea as $t){
    	echo '<br>'.$t->getCodigo();
    }
    
    $dao->inseretodos($tiposea);
    
} elseif ($operacao == "A") {
//    echo "ok";
//    exit;
    $cod = $_POST["cod"];
    $codinv = false;
    if (is_array($em) && is_array($ea) && is_array($er) && is_array($cod)) {
        foreach ($cod as $i => $qtde) {
            if (!is_numeric($qtde)) {
                $codinv = true;
                break;
            }
        }
        if ($codinv) {
            echo "Erro codinv! Comunicar ao administrador";die;
        }
        echo "Tamanho: " . $tamanho . " e tamanho2 " . $tamanho2."<br>";
        for ($cont1 = 1; $cont1 <= $tamanho; $cont1++) {
            for ($cont2 = 0; $cont2 < $tamanho2; $cont2++) {
             // echo "Tamanho: ". $cont1 . " e tamanho2 ".$cont2."linha";
                if ($cont1 == $cont2 + 1) {
                    $tiposea[$cont1]->criaEnsinoea($cod[$cont2], $em[$cont2], $ea[$cont2], $er[$cont2], $anoBase);
                    
                }
            }
        }
        $dao->alteratodos($tiposea);
    }
}
$dao->fechar();
if ($operacao == "A")
    Flash::addFlash('Quantitativo do Ensino Fundamental atualizado com sucesso!');
else
    Flash::addFlash('Quantitativo do Ensino Fundamental cadastrado com sucesso!');
Utils::redirect('ensinofund', 'consultaensino');
//$cadeia = "location:consultaensino.php";
//header($cadeia);
//exit();
//obs_end_flush();
?>
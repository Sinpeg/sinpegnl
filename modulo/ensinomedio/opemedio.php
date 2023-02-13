<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//require_once('../../includes/dao/PDOConnectionFactory.php');
//require_once('../../includes/classes/ensinoea.php');
//require_once('../../includes/dao/ensinoeaDAO.php');
//require_once('../../includes/classes/tdmensinoea.php');
//require_once('../../includes/classes/unidade.php');
//session_start();
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[21]) {
    echo "Você não tem permissão para acessar esta opção de menu! Contate o administrador do sistema.";die;
}

//$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anoBase = $sessao->getAnobase();
//Valida campos
$operacao = $_POST["operacao"];
$em = $_POST["em"];
$ea = $_POST["ea"];
$er = $_POST["er"];
$matinv = false;
$repinv = false;
$aprinv = false;
if (is_array($em) && is_array($ea) && is_array($er)) {
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);
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
    echo "Erro";die;
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
    echo "Erro - invalidado";
    //exit();
}
$dao = new EnsinoeaDAO();
$cont = 0;
$rows_tea = $dao->ListaEm($anoBase);
foreach ($rows_tea as $row) {
    $cont++;
    $tiposea[$cont] = new Tdmensinoea();
    $tiposea[$cont]->setCodigo($row['Codigo']);
//    print $tiposea[$cont]->getCodigo() . "<br/>";
}
$i = 0;
$tamanho = $cont;
$tamanho2 = count($em);
if ($operacao == "I") {
	
    for ($cont1 = 1; $cont1 <= $tamanho; $cont1++) {
        for ($cont2 = 0; $cont2 < $tamanho2; $cont2++) {
            if ($cont1 == $cont2 + 1) {
                $tiposea[$cont1]->criaEnsinoea(null, $em[$cont2], $ea[$cont2], $er[$cont2], $anoBase);
            }
        }
    }
    $dao->inseretodos($tiposea);
} elseif ($operacao == "A") {
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
            echo "Erro: Quantidade nao numerica";
        }
//        echo $tamanho ." ". $tamanho2;
//        exit;

        for ($cont1 = 0; $cont1 <= $tamanho; $cont1++) {
            for ($cont2 = 0; $cont2 < $tamanho2; $cont2++) {
                if ($cont1 == $cont2 + 1) {
                    $tiposea[$cont1]->criaEnsinoea($cod[$cont2], $em[$cont2], $ea[$cont2], $er[$cont2], $anoBase);
                }
            }
        }
        $dao->alteratodos($tiposea);
    }
}
$dao->fechar();
$str = ($operacao == "A") ? ("Quantitativo do Ensino Médio atualizado com sucesso!") : ("Quantitativo do Ensino Médio cadastrado com sucesso!");
Flash::addFlash($str);
Utils::redirect('ensinomedio', 'consultaemedio');
?>
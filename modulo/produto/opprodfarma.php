<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');;
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[16]) {
    header("Location:index.php");
} else {
    $sessao = $_SESSION["sessao"];
//	$nomeunidade = $sessao->getNomeunidade();
//	$codunidade = $sessao->getCodunidade();
//	$responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();
//    require_once('../../includes/dao/PDOConnectionFactory.php');
    // var_dump($usuario);
    require_once('dao/produtosDAO.php');
    require_once('classes/produtos.php');
//    require_once('../../includes/classes/unidade.php');

    require_once('dao/prodfarmaciaDAO.php');
    require_once('classes/prodfarmacia.php');
    
    //Variáveis enviadas pelo formulário
    $mes = $_POST["mes"];
    $tproduto = $_POST["produto"];
    $quantidade = $_POST["quantidade"];
    $precoanterior = $_POST["preco"];
    $preco = str_replace(",", ".", $precoanterior);
    $operacao = $_POST["operacao"];

    if ($mes == "" && !is_numeric($mes)) {
        $mensagem = urlencode("Tipo incorreto para o campo M&ecirc;s");
        $cadeia = "location:../saida/erro.php?codigo=1&mensagem=" . $mensagem;
        header($cadeia);
//        exit();
    } else if ($tproduto == "" && !is_numeric($tproduto)) {
        $mensagem = urlencode("Tipo incorreto para o campo Produto");
        $cadeia = "location:../saida/erro.php?codigo=1&mensagem=" . $mensagem;
        header($cadeia);
//        exit();
    } else if ($quantidade == "" && !is_numeric($quantidade)) {
        $mensagem = urlencode("Tipo incorreto para o campo Quantidade");
        $cadeia = "location:../saida/erro.php?codigo=1&mensagem=" . $mensagem;
        header($cadeia);
//        exit();
    } else if ($preco == "" && !is_float($preco)) {
        $mensagem = urlencode("Tipo incorreto para o campo Preco");
        $cadeia = "location:../saida/erro.php?codigo=1&mensagem=" . $mensagem;
        header($cadeia);
//        exit();
    } else {
        $dao = new ProdfarmaciaDAO();
        $tpro = new Produtos();
        //$pro->setSubunidade($subunidade);
        $tpro->setCodigo($tproduto);
        
        if ($operacao == "I") {
            
        	$passou = false;
            $rows = $dao->buscatipoproduto1($anobase, $mes, $tproduto);

            foreach ($rows as $row) {
                $codigo = $row['Codigo'];
                $passou = true;
            }
            if ($passou) {
                $tpro->criaProdfarmacia($codigo, $quantidade, $anobase, $preco, $mes);
                $dao->altera($tpro);
            } else {
                $tpro->criaProdfarmacia(null, $quantidade, $anobase, $preco, $mes);
                $dao->insere($tpro);
            }
        } else if ($operacao == "A") {
        	  	        	
        	$codigo = $_POST["codigo"];       	
        	
        	$tpro->criaProdfarmacia($codigo, $quantidade, $anobase, $preco, $mes);    
        	$dao->altera($tpro);
              	
			         
        }
    }
    $dao->fechar();
//    $cadeia = "location:consultapfarma.php";
    Utils::redirect('produto', 'consultapfarma');
//    header($cadeia);
//    exit();
//    ob_end_flush();
}
?>


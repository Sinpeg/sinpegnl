<?php

//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('dao/edprofissionallivreDAO.php');
require_once('classes/edprofissionallivre.php');
require_once('classes/tdmedprofissionallivre.php');
require_once('dao/tdmedprofissionallivreDAO.php');
//require_once('../../includes/classes/unidade.php');;
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[26]) {
    header("Location:index.php");
} else {
//$sessao = $_SESSION["sessao"];
    $nomeUnidade = $sessao->getNomeunidade();
    $codUnidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();
//$aplicacoes = $_SESSION["sessao"]->getAplicacoes();
//if (!$aplicacoes[26]){
////	$mensagem = urlencode(" ");
////	$cadeia="location:../saida/erro.php?codigo=2&mensagem=".$mensagem;
////	header($cadeia);
////	exit();
//}
    $unidade = new Unidade();
    $unidade->setCodunidade($codUnidade);
    $unidade->setNomeunidade($nomeUnidade);
    $cat = $_POST["cat"];
    $nomecurso = $_POST["nome"];
    $ingressantes1 = $_POST["qtding1"];
    $ingressantes2 = $_POST["qtding2"];
    $matriculados1 = $_POST["qtdmatr1"];
    $matriculados2 = $_POST["qtdmatr2"];
    $aprovados1 = $_POST["qtdapr1"];
    $aprovados2 = $_POST["qtdapr2"];
    $concluintes1 = $_POST["qtdconc1"];
    $concluintes2 = $_POST["qtdconc2"];
    $operacao = $_POST["operacao"];

    if (is_string($nomecurso) && $nomecurso != "" && is_numeric($cat) && $cat != "" && is_numeric($ingressantes1) && $ingressantes1 != "" && is_numeric($ingressantes2) && $ingressantes2 != "" && is_numeric($matriculados1) && $matriculados1 != "" && is_numeric($matriculados2) && $matriculados2 != "" && is_numeric($aprovados1) && $aprovados1 != "" && is_numeric($aprovados2) && $aprovados2 != "" && is_numeric($concluintes1) && $concluintes1 != "" && is_numeric($concluintes2) && $concluintes2 != "") {
        $tipoed = new Tdmedprofissionallivre();
        $tipoed->setCodigo($cat);
        $tipoed->setCategoria(null);
        $dao = new EdprofissionallivreDAO();
        if ($operacao == "I") {
            $tipoed->criaEdproflivre(null, $nomecurso, $ingressantes1, $ingressantes2, $matriculados1, $matriculados2, $aprovados1, $aprovados2, $concluintes1, $concluintes2, $anobase);
            $dao->Insere($tipoed);
        } else if ($operacao == "A") {
            $codigo = $_POST["codigo"];
            if (is_numeric($codigo) && $codigo != "") {
                $tipoed->criaEdproflivre($codigo, $nomecurso, $ingressantes1, $ingressantes2, $matriculados1, $matriculados2, $aprovados1, $aprovados2, $concluintes1, $concluintes2, $anobase);
                $dao->Altera($tipoed);
            }
        }

        $dao->fechar();
    } else {
        $mensagem = urlencode(" ");
        $cadeia = "location:../saida/erro.php?codigo=1&mensagem=" . $mensagem;
        header($cadeia);
    }
    Flash::addFlash('Dados da Educação Profissional e Cursos Livres atualizados com sucesso!');
    Utils::redirect('cledprofissional', 'conscleducprof');
//$cadeia = "location:conscleducprof.php";
//header($cadeia);
//exit();
}
ob_end_flush();
?>


<?php
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[27]) {
    header("Location:index.php");
}
//$sessao = $_SESSION["sessao"];
$anobase = $sessao->getAnobase();
require_once('dao/pndDAO.php');
require_once('classes/pnd.php');
$noatendidos = $_POST['noatendidos'];
$nopnd = $_POST['nopnd'];
$codcurso = $_POST['codcurso'];
$nomecurso = $_POST['nomecurso'];
$operacao = $_POST['operacao'];
$codigo = $_POST['codigo'];
if (is_numeric($nopnd) && $nopnd != "" && is_numeric($noatendidos) && $noatendidos != "" && is_numeric($codcurso) && $codcurso != "" && is_string($nomecurso) && $nomecurso != "") {
    $cont = 0;
    $dao = new PndDAO();
    $curso = new Curso();
    $curso->setCodcurso($codcurso);
    if ($operacao == "I") {
        $curso->criaPnd(null, $nopnd, $noatendidos, $anobase);
        $dao->insere($curso);
    } else if ($operacao == "A") {
        if ($codcurso != "" && is_numeric($codigo)) {
            $curso->criaPnd($codigo, $nopnd, $noatendidos, $anobase);
            $dao->altera($curso);
        }
    }
    $dao->fechar();
    Flash::addFlash('Dados atualizados com sucesso!');
    Utils::redirect('pnd', 'consultapnd', array('codcurso' => $codcurso, 'nomecurso' => $nomecurso));
}
?>


<?php
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[11]) {
    header("Location:index.php");
} else {
    $sessao = $_SESSION["sessao"];
    $nomeunidade = $sessao->getNomeunidade();
    $codunidade = $sessao->getCodunidade();
    $responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();
    $codcurso = $_GET["codcurso"];
    $codta = $_GET["codta"];
    $codtta = $_GET["codtta"];
//	require_once('../../includes/dao/PDOConnectionFactory.php');
    // var_dump($usuario);
//	require_once('../../includes/dao/cursoDAO.php');
//	require_once('../../includes/classes/curso.php');
//	require_once('../../includes/classes/unidade.php');
    require_once('dao/tptecnassistDAO.php');
    require_once('classes/tipotecnologiassistiva.php');
    require_once('dao/tecnassistivaDAO.php');
    require_once('classes/tecnologiassistiva.php');

    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);
    if (is_numeric($codta) && is_numeric($codcurso) && is_string($codtta)) {
        $tipota1 = new Tipotecnologiassistiva();
        $tipota1->setCodigo($codtta);

        $daocur = new CursoDAO();

        $rows_cur = $daocur->buscacurso($codcurso);
        foreach ($rows_cur as $row) {
            $curso = $unidade->criaCurso(null, null, $row['CodCurso'], $row['NomeCurso'], null, null);
        }

        $ta = $curso->criaTacurso($curso, $anobase, $tipota1, $codta);
        $daota = new TecnassistivaDAO();
        $daota->deleta($ta);
        $daota->fechar();
        Utils::redirect('tecnol', 'consultatecnol', array('nomecurso' => $curso->getNomecurso(), 'codcurso' => $codcurso));
//		$cadeia="location:consultatecnol.php?codcurso=".$codcurso."&nomecurso=".$curso->getNomecurso();;
//        header($cadeia);
    }
}
//ob_end_flush();
?>



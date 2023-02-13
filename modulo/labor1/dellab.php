<?php
ob_start();
if (!$aplicacoes[7]) {
    header("Location:index.php");
} else {
    require_once('dao/laboratorioDAO.php');
    require_once('classes/laboratorio.php');
    $sessao = $_SESSION["sessao"];
    $codlab = $_GET["codlab"];
    $daolab=new LaboratorioDAO();
    $rows=$daolab->buscaLaboratorio($codlab);
    $dono=true; // dono da informação
    $ano = 0;
    foreach ($rows as $row) {
        if ($row["CodUnidade"]!=$codunidade) {
            $dono = false;
        }
        $ano = $row["AnoAtivacao"];
    }
    $daolab->fechar();
    if (!$dono) {
        sleep(2);
        Error::addErro("Você não pode remover os dados de outra unidade");
        Utils::redirect('labor', 'consultalab');
    }
    // se for subunidade e os dados já estiverem homologados
    else if (!$sessao->isUnidade() && Utils::isApproved(7, $cpga, $codunidade, $ano)) {
        sleep(2);
        Error::addErro("Você não pode remover dados que estão homologados");
        Utils::redirect('labor', 'consultalab'); 
        }
    }
    
    if ($codlab != "" && is_numeric($codlab)) {
        require_once('dao/labcursoDAO.php');
        $daolabcurso = new LabcursoDAO();
        $cont = 0;
        //busca cursos do lab
        $rows = $daolabcurso->buscaCursosLaboratorio1($codlab);
        foreach ($rows as $row) {
            $cont++;
        }
        if ($cont == 0) {
            $daolab = new LaboratorioDAO();
            $daolab->deleta($codlab);
            $daolab->fechar();
            $daolabcurso->fechar();
            Utils::redirect('labor', 'consultalab');
        }  else {
        	Flash::addFlash("Este laboratório possui cursos vinculados. Exclua primeiro os cursos.");
        	Utils::redirect('labor', 'conslabcurso1',array('codlab' => $codlab));
//             Error::addErro("Este laboratório possui cursos vinculados. Exclua primeiro os cursos.");
//             $cadeia = "Location:" . Utils::createLink('labor', 'conslabcurso1', array('codlab' => $codlab));
//             $daolabcurso->fechar();
//             header($cadeia);
        }
    }
?>

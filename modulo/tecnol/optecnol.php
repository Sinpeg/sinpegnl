<?php

//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[11]) {
    header("Location:index.php");
} 
//$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();

//require_once('../../includes/dao/PDOConnectionFactory.php');
// var_dump($usuario);

require_once('dao/tecnassistivaDAO.php');
require_once('classes/tecnologiassistiva.php');

//require_once('../../includes/classes/curso.php');
//require_once('../../includes/dao/cursoDAO.php');
//require_once('../../includes/classes/unidade.php');
require_once('classes/tipotecnologiassistiva.php');
$daota = new TecnassistivaDAO();
$operacao = $_POST["operacao"];
$codcurso = $_POST["codcurso"];
$nomecurso = $_POST["nomecurso"];

is_null($_POST["ARE"])? 0 : $tassist[0] = "ARE";
is_null($_POST["AUD"])? 0 : $tassist[1] = "AUD";
is_null($_POST["BRA"])? 0 : $tassist[2] = "BRA";
is_null($_POST["CAM"])? 0 : $tassist[3] = "CAM";
is_null($_POST["DLI"])? 0 : $tassist[4] = "DLI";
is_null($_POST["INT"])? 0 : $tassist[5] = "INT";
is_null($_POST["MDA"])? 0 : $tassist[6] = "MDA";
is_null($_POST["MFI"])? 0 : $tassist[7] = "MFI";
is_null($_POST["MLI"])? 0 : $tassist[8] = "MLI";
is_null($_POST["RIA"])? 0 : $tassist[9] = "RIA";
is_null($_POST["SVO"])? 0 : $tassist[10] ="SVO";
is_null($_POST["TLI"])? 0 : $tassist[11] ="TLI";

$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
if ($operacao == "I" && is_numeric($codcurso) && is_string($nomecurso) && is_array($tassist)) {
    $curso = $unidade->criaCurso(null, null, $codcurso, null, null, null);
    if ($tassist) {
        foreach ($tassist as $t) {
            //  $consulta = $daota->buscata($_POST["codcurso"],$_POST["anobase"],$t);

            $tipota1 = new Tipotecnologiassistiva();
            $tipota1->setCodigo($t);
            $curso->adicionaItemTacursos($curso, $anobase, $tipota1, null);
            
        }
        $daota->inseretodos($curso->getTacursos());
    }

    /* elseif ($_POST["operacao"]=="A") {
      $tipota1=new Tipotecnologiassistiva();
      $tipota1->setCodigo($_POST["tassist"]);

      $curso = new Curso();
      $curso->setCodcurso($_POST["codcurso"]);

      $ta = new Tecnologiassistiva();
      $ta->setCodtecnologiaassistiva($_POST["codta"]);
      $ta->setAno( $_POST["anobase"]);
      $ta->setCurso($curso);
      $ta->setTipota($tipota1);
      $ta->setNpessoasnecessitadas($_POST["npn"]);
      $ta->setNpessoasatendidas($_POST["npa"]);

      $daota->altera($ta);
      } */
    $daota->fechar();
    Flash::addFlash('Tecnologia Assistiva cadastrada com sucesso!');
    Utils::redirect('tecnol', 'consultatecnol', array('codcurso'=>$codcurso, 'nomecurso'=>$nomecurso));
//    header($cadeia);
//exit();
}else{
	Error::addErro('Marque os itens a serem adicionados!');
	Utils::redirect('tecnol', 'incluitecnol', array('codcurso'=>$codcurso, 'nomecurso'=>$nomecurso));
}
//ob_end_flush();
?>

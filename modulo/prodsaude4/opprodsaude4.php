<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

//set_include_path(';../../includes');

//require_once('../../includes/classes/sessao.php');
//require_once('../../includes/dao/PDOConnectionFactory.php');
//require_once('../../includes/classes/servico.php');
//require_once('../../includes/classes/procedimento.php');
//require_once('../../includes/classes/servproced.php');
//require_once('../../includes/dao/servprocDAO.php');
require_once('classes/local.php');
//require_once('../../includes/dao/psaudemensalDAO.php');
//require_once('../../includes/classes/psaudemensal.php');
//
//require_once('../../includes/classes/unidade.php');
$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[14]) {
    header("Location:index.php");
}

$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$erro = false;

//Valida campos
$operacao = $_POST["operacao"];
$mes = $_POST["mes"];
$codsubunidade = $_POST["subunidade"];
switch ($codunidade) {
    case 270:
        $codlocal = $_POST["local"];
        $ndisc = $_POST["ndisc"];
        $ndoc = $_POST["ndoc"];
        $npesq = $_POST["npesq"];
        $nproc = $_POST["nproc"];
        $nexames = 0;
        if (!is_numeric($ndoc) || $ndoc == ""
                && !is_numeric($ndisc) || $ndisc == ""
                && !is_numeric($npesq) || $npesq == ""
                && !is_numeric($codlocal) || $codlocal == 0) {
            $erro = true;
        }
        $npaten = $_POST["npaten"];
        if (!is_numeric($npaten) || $npaten == "") {
            $erro = true;
        }
        break;
    case 202:
        $ndisc = 0;
        $ndoc = 0;
        $npesq = 0;
        $codlocal = 0;
        $codservico = 1900;
        $nexames = 0;
        $nproc=0;
        $npaten = $_POST["npaten"];
        if (!is_numeric($npaten) || $npaten == "") {
            $erro = true;
        }
        break;
    case 1644:
        $ndisc = 0;
        $ndoc = 0;
        $npesq = 0;
         $nproc=0;
        $codlocal = 0;
        
        $npaten = 0;
        $nexames = $_POST["nexames"];
        if (!is_numeric($nexames) || $nexames == "") {
            $erro = true;
        }
        break;
}
$codprocedimento = $_POST["procedimento"];
$codservico = $_POST["servico"];

if (is_numeric($codservico) && $codservico > 0
        && is_numeric($codsubunidade) && $codsubunidade > 0
        && is_numeric($codprocedimento) && $codprocedimento > 0
        && is_numeric($mes) && !$erro) {
    $local = new Local();
    $local->setCodigo($codlocal);

    $unidade->criaSubunidade($codsubunidade, null, null);
    $procedimento = new Procedimento();
    $procedimento->setCodigo($codprocedimento);
    $unidade->getSubunidade()->criaServico($codservico, null);
    $daosp = new ServprocDAO();
    $rows = $daosp->buscacodservproced($codservico, $codsubunidade, $codprocedimento);
    foreach ($rows as $row) {
        $codservproced = $row['CodServProc'];
    }
    $unidade->getSubunidade()->getServico()->criaSp($codservproced, $procedimento);
    $dao = new PsaudemensalDAO();

    
    if ($operacao == "I") {
    	
        $passou = false;
        if ($codunidade == 270) {
        	
        	$rows = $dao->BuscaPsaudemensal($anobase, $mes, $codlocal, $codservico, $codprocedimento);
        	
        } else {        	
            $rows = $dao->BuscaPsaudemensal1($anobase, $mes, $codservico, $codprocedimento);
        }
        
        foreach ($rows as $row) {
            $codigo = $row['Codigo'];
            $passou = true;
        }
        
        
        
        if ($passou) {        	
        	$unidade->getSubunidade()->getServico()->getSp()->criaPsaude($codigo, $procedimento, $local, $anobase, $mes, $ndoc, $ndisc, $npesq, $npaten, $nproc, $nexames);
            if ($codunidade == 270)
                $dao->altera($unidade->getSubunidade()->getServico()->getSp());
            else
                $dao->altera1($unidade->getSubunidade()->getServico()->getSp());
        }else {
        	
            $unidade->getSubunidade()->getServico()->getSp()->criaPsaude(null, $procedimento, $local, $anobase, $mes, $ndoc, $ndisc, $npesq, $npaten, $nproc, $nexames);
            if($codunidade == 270){
            	
                $dao->insere($unidade->getSubunidade()->getServico()->getSp());
            }else{
            	
           // echo "xx";
            	
                $dao->insere1($unidade->getSubunidade()->getServico()->getSp());
                          //  	echo "teste";die;
                
            }
        }
    }
    elseif ($operacao == "A") {
        $codigo = $_POST["codigo"];
        if (is_numeric($codigo) && $codigo != "") {
            if ($codunidade == 270) {
                $rows = $dao->BuscaPsaudemensal($anobase, $mes, $codlocal, $codservico, $codprocedimento);
            } else {
                $rows = $dao->BuscaPsaudemensal1($anobase, $mes, $codservico, $codprocedimento);
            }
            foreach ($rows as $row) {
                $codigo = $row['Codigo'];
                $passou = true;
            }
            $unidade->getSubunidade()->getServico()->getSp()->criaPsaude($codigo, $procedimento, $local, $anobase, $mes, $ndoc, $ndisc, $npesq, $npaten, $nproc, $nexames);
            if ($codunidade == 270)
                $dao->altera($unidade->getSubunidade()->getServico()->getSp());
            else
                $dao->altera1($unidade->getSubunidade()->getServico()->getSp());
        }else {
            $erro = true;
        }
    }

    $dao->fechar();
} else {
    $erro = true;
}
if ($erro) {
    $mensagem = urlencode(" ");
    $cadeia = "location:../saida/erro.php?codigo=1&mensagem=" . $mensagem;
    header($cadeia);
} else {
	
    $cadeia = "location:?modulo=prodsaude4&acao=conspsaude41&sub=" . $codsubunidade . "&servico=" . $codservico . "&proced=" . $codprocedimento . "&local=" . $codlocal;
    header($cadeia);
//    exit();
}
//ob_end_flush();
?>

<?php
//ob_start();
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//require_once('../../includes/dao/PDOConnectionFactory.php');;
require_once('classes/infraensino.php');
require_once('dao/infraensinoDAO.php');
require_once('classes/tipoinfraensino.php');
require_once('dao/tipoinfraensinoDAO.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[9]) {
 header("Location:index.php");
}
//$sessao = $_SESSION["sessao"];
$nomeUnidade = $sessao->getNomeunidade();
$codUnidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
//require_once('../../includes/classes/unidade.php');
$unidade = new Unidade();
$unidade->setCodunidade($codUnidade);
$unidade->setNomeunidade($nomeUnidade);
$daoie= new InfraensinoDAO();
$qtdDVD=$_POST["qtdDVD"];
$qtdAudio=$_POST["qtdAudio"];
$qtdAr=$_POST["qtdAr"];
$qtdPC=$_POST["qtdPC"];
$qtdVideoconferencia=$_POST["qtdVideoconferencia"];
$qtdEspecificos=$_POST["qtdEspecificos"];
$qtdEletronico=$_POST["qtdEletronico"];
$qtdMoveis=$_POST["qtdMoveis"];
$qtdOutrosequipamentos=$_POST["qtdOutrosequipamentos"];
$qtdProjetores=$_POST["qtdProjetores"];
$qtdTV=$_POST["qtdTV"];
$qtdInovacoes=$_POST["qtdInovacoes"];
if (is_numeric($qtdDVD) && is_numeric($qtdAudio) && is_numeric($qtdAr)
&& is_numeric($qtdPC) && is_numeric($qtdVideoconferencia) && is_numeric($qtdEspecificos)
&& is_numeric($qtdMoveis) && is_numeric($qtdOutrosequipamentos) && is_numeric($qtdProjetores)
&& is_numeric($qtdTV) && is_numeric($qtdInovacoes)){
 $tipoIE = array();
 $tipoIE["1"]= $qtdDVD;
 $tipoIE["2"] = $qtdAudio;
 $tipoIE["3"] = $qtdAr;
 $tipoIE["4"] = $qtdPC;
 $tipoIE["5"] = $qtdVideoconferencia;
 $tipoIE["6"] = $qtdEspecificos;
 $tipoIE["7"] = $qtdEletronico;
 $tipoIE["8"] = $qtdMoveis;
 $tipoIE["9"] = $qtdOutrosequipamentos;
 $tipoIE["10"] = $qtdProjetores;
 $tipoIE["11"] = $qtdTV;
 $tipoIE["12"] = $qtdInovacoes;
 $cont = 0;
 $tIE = array();
 //$consulta = $daoea->buscaea ($codUnidade,$anobase,$tipoEA);
 $ieDAO = new InfraensinoDAO();
 foreach ($tipoIE as $i => $tpIE ){
 $cont++;
 $tIE[$cont] = new Tipoinfraensino();
 $tIE[$cont]->setCodigo($i);
 $consulta = $daoie->buscaie ($codUnidade,$anobase,$i);
 $passou = false;
 foreach ($consulta as $row){
 $passou = true;
 $tIE[$cont]->criaInfraensino($row["Codigo"],$unidade,$anobase,$tpIE);
 }
 if (!$passou){
 $tIE[$cont]-> criaInfraensino(null,$unidade,$anobase,$tpIE);
 }
 }
 if (!$passou){
 $ieDAO->inseretodos($tIE);
 }else{
 $ieDAO->alteratodos($tIE);
 }
 $daoie->fechar();
}
Flash::addFlash('Infraestrutura de ensino cadastrada com sucesso!');
Utils::redirect('infraensino', 'consultainfraensino');
//$cadeia = "location:consultainfraensino.php";
//header($cadeia);
// exit();
//ob_end_flush();
?>
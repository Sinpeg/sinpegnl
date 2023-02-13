<?php
ini_set('display_errors', 'on');//habilita mensagens de erro

usleep(500000);
/* grant */
require_once '../../classes/sessao.php';
session_start();
$sessao = $_SESSION["sessao"];
if (!isset($sessao)) {
 exit(0);
} else {
 $aplicacoes = $sessao->getAplicacoes();
 if (!$aplicacoes[47]) {
 exit(0);
 }
}

define('BASE_DIR', dirname(__FILE__));
require_once(BASE_DIR . DIRECTORY_SEPARATOR . '../../dao/PDOConnectionFactory.php');
require_once(BASE_DIR . DIRECTORY_SEPARATOR . '../../dao/grupoDAO.php');
require_once(BASE_DIR . DIRECTORY_SEPARATOR . '../../dao/unidadeDAO.php');

$hierarquia=$sessao->getCodestruturado();
//$grupo = $_POST["cad-consulta"];
//$dao = new GrupoDAO();
$daocat = new UnidadeDAO();
//$hierarquia = $daocat->buscahierarquia($codunidade);
//foreach($hierarquia as $hiera){
	//$hier =  addslashes($hiera["hierarquia"]);
//}
$retorno = array();
//$_SESSION['grupose'] = $_POST["cad-consulta"];
//$subunidades = $daocat->buscalunidade("", $hierarquia);
$grupo=$_POST["cad-consulta"];
$subunidades = $daocat->buscalunidadeNsel( $hierarquia,$grupo);
if (!empty($subunidades)){

   $display=  " <select multiple class='custom-select' id='select1' name='lista1[]'' >";
   foreach ($subunidades as $res) {
	$display.=  "<option value=".$res['CodUnidade'].">".$res['NomeUnidade']."</option>";
   }
   $display.= "</select>";
}else{
  $display=" <select multiple class='custom-select' id='select1' name='lista1[]'' ></select>";
}



echo $display;

?>


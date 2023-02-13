<?php

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
$hierarquia=$sessao->getCodestruturado();
$grupo = $_POST["cad-consulta"];
$dao = new GrupoDAO();
$retorno = array();
$_SESSION['grupose'] = $_POST["cad-consulta"];
$unidades = $dao->buscaunidgrupo($grupo, $hierarquia);
if (!empty($unidades)){
	$passou=0;
   $display=  "<select id='select3' name='lista2[]' multiple size=multiple style='width: 600px'>";
   foreach ($unidades as $res) {


	$display.=  "<option value=".$res['CodUnidade'].">".$res['NomeUnidade']."</option>";
   }
  	 	 $display.= "</select>";
}else{
  $display="<select id='select3' ></select>";

}

echo $display;

?>
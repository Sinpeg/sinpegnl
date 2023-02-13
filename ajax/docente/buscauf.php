<?php

require '../../classes/sessao.php';
require_once('../../dao/PDOConnectionFactory.php');
require_once('../../modulo/docente/dao/docentedao.php');
session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
if (!isset($sessao)) {
    exit(0);
} else {
    $aplicacoes = $sessao->getAplicacoes();
   // if (!$aplicacoes[7]) { // laboratório
   //     exit(0);
    //}
}


$cont = 0;
$dao = new docenteDAO();
$uf = $_POST["uf"];
if ($uf!=0){
        $rows = $dao->buscaMunicipioCodigos($uf);
      
        $display = "<select class='form-control' id=mun  name=mun>";
        $display .= "<option value=0>Selecione a Municipio, se país de origem for o Brasil</option>"; 
        
        foreach ($rows as $row) {
            $display .="<option value=".$row['idMuncipio'] .">".$row['nome'] ."</option>";
        }
        $display .= "</select></td></tr>";
        
        echo $display;
}
?>

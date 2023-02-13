<?php
require_once('../../classes/sessao.php');
session_start();
if (!isset($_SESSION["sessao"])) {
   echo "Sua sessão expirou!"; die;
}
$sessao = $_SESSION["sessao"];
//$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
$anobase = $sessao->getAnobase();
$aplicacoes = $_SESSION["sessao"]->getAplicacoes();
if (!$aplicacoes[14]) {
    echo "Você nã tem permissão para entrar neste sistema"; die;
}
require_once('../../dao/PDOConnectionFactory.php');
require_once('../../dao/servprocDAO.php');
$categoria = $_POST["servico"];
$subunidade = $_POST["subunidade"];
?>
<?php

if (is_numeric($categoria)) {
    require_once('../../dao/servprocDAO.php');
    $daos = new ServprocDAO();
    
    }
    
    $display = "<select name=procedimento id=procedimento class=sel1>";
    
    $display.="<option value='0'>Selecione um procedimento...</option>";
    if ($categoria != "") {
    	if ($anobase==2018 && $codunidade=270){
    		    	   $rows = $daos->buscaservproced2018(addslashes($categoria));//Em 2018 foi solicitado para nao informar servico
    		
    	}else{
    	              $rows = $daos->buscaservproced(addslashes($categoria), addslashes($subunidade));
    	   
    	}
    		
    } else {
        $rows = $daos->buscaservproced1(addslashes($subunidade));     
    }
    
    foreach ($rows as $row) {
        $display .="<option  value=";
        $display .=$row['CodProced']. ">";
        $display .= ($row['Nome']);
    }
    $display.="</select>";
    echo $display;

?>

<?php

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/sessao.php';
require_once '../../classes/unidade.php';
require_once '../../dao/unidadeDAO.php';
session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
if (!isset($_SESSION["sessao"])) {
    echo "Sua sessão expirou, faça login novamente!";
    exit();
}
	 



$dao=new UnidadeDAO();
$rows=$dao->ListaResponsavel();
$string='  <select name="uresp"  id="uresp">
<option value="0">Selecione a unidade responsável por esta unidade...</option>';

foreach ($rows as $r){
	$string.='<option value="'.$r['id_unidade'].'">'.$r['NomeUnidade'].'</option>';
                            
                            
}
$string.='';

  echo $string;                                             
?>



<?php

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/sessao.php';
require_once '../../classes/unidade.php';
require_once '../../dao/LiberacaoCalendarioDAO.php';
session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
if (!isset($_SESSION["sessao"])) {
    echo "Sua sessão expirou, faça login novamente!";
    exit();
}
$coduni=$_POST['codUnidade'];
$databloc=prepararDataHora($_POST['databloqueio']);
$datafinaliz=prepararDataHora($_POST['datafinaliz']);
$datadesbloc=prepararDataHora($_POST['datadesbloqueio']);
$ativ=$_POST['codAtividade'];
$codigo=$_POST['codigolib'];
$codigo=empty($codigo)?"0":$codigo;
$codusuario=$sessao->getCodusuario();
$ano=$sessao->getAnobase();
if ($sessao->getCodUnidade()==100000){
    
    if(empty($databloc)){
    	echo "O campo data final do período de liberação deve ser informado.";
    }else if  ($ativ=="0"){
    	echo  "O campo atividade deve ser informado.";
    }else{
     
    $dao=new LiberacaoCalendarioDAO();
    $dao->spliberacalendario($codigo,$ativ, $coduni, $datafinaliz, $datadesbloc, $databloc,$ano,$codusuario);
    
    }
}




function prepararDataHora($data){
    if (!empty($data) ){
        $dia = substr($data,0,2);
        $mes = substr($data,3,2);
        $ano = substr($data,6,4);
        $hora= substr($data,11,strlen($data));
        $data = $ano."-".$mes."-".$dia." ".$hora;
    }else{
        $data =null;
    }
    return $data;
}

?>



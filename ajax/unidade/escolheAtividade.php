<?php
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../dao/unidadeDAO.php';
require_once '../../dao/LiberacaoCalendarioDAO.php';

require_once '../../classes/sessao.php';



session_start();
$sessao = $_SESSION['sessao'];

if (!isset($sessao)) {
    echo "Sessão expirou...";
    exit(0);
} 
$unidade=addslashes($_POST["unid"]);
$tipo=$_POST["ativ"];
$anogestao=$sessao->getAnobase();
$dataatual=null;
$daou=new unidadeDAO();
$rows=$daou->buscarUnidadeByNome(trim($unidade));
foreach ($rows as $r){
    $dataatual=prepararDataHora($r['dtatual']);
    $coduni=$r['CodUnidade'];
}
$datadesbloqueio=null;
$databloqueio=null;
$datafinaliz=null;
$codigolib=null;
//Verifica se já existe liberação da atividade para unidade selecionada no ano de gestao
$daolib=new LiberacaoCalendarioDAO();
$rows=$daolib->buscaliberacaocalendario($coduni, $tipo, $anogestao);
foreach ($rows as $r){
    $datadesbloqueio=prepararDataHora($r['dataDesbloqueio']);
    $codigolib=$r['codigo'];
    $databloqueio=prepararDataHora($r['dataBloqueio']);
    $datafinaliz=prepararDataHora($r['dataFinalizacao']);
    
}

$atividade="";
$agora = new DateTime(); // Pega o momento atual
$hoje=$agora->format('d/m/Y H:i'); // Exibe no formato desejado

switch ($tipo){
    case 1: $atividade="Liberar RAA";//carregar dados para a tabela libatividade
    break;
    case 2: $atividade="Liberar Solicitação de Alteração do PDU";//marcar finalização no lançamento se houver solicitação
    break;
    case 3: $atividade="Liberar Elaboração do PDU";
    break;
    case 4: $atividade="Liberar Elaboração do Painel Tático";
    break;
    case 5: $atividade="Liberar Lançamento Final do Painel Tático";
    break;
}


$datafinaliz=empty($datafinaliz)?"":$datafinaliz;
echo json_encode(array('unidade' => $unidade,'atividade'=>$atividade, 'datafinaliz'=>$datafinaliz,'datadesbloqueio'=>$dataatual,
    'databloqueio'=>$databloqueio,'coduni'=>$coduni,'codAtividade'=>$tipo,'codigolib'=>$codigolib));

function prepararDataHora($data){
    if (!empty($data) ){
        $ano = substr($data,0,4);
        $mes = substr($data,5,2);
        $dia = substr($data,8,2);
        $hora= substr($data,11,strlen($data));
        $data = $dia."/".$mes."/".$ano." ".$hora;
    }else{
        $data =null;
    }
    return $data;
}

?>
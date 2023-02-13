<?php
    require_once '../../classes/sessao.php';
    session_start();
    $sessao = $_SESSION["sessao"];
    $aplicacoes = $sessao->getAplicacoes();
?>
<?php
if (!$aplicacoes[44]) {
    exit;
}
?>
<?php
$codsub = filter_input(INPUT_POST, 'codsub', FILTER_DEFAULT); // código da unidade
$codap = filter_input(INPUT_POST, 'codap', FILTER_DEFAULT); // código da aplicação
$anobase = filter_input(INPUT_POST, 'anobase', FILTER_DEFAULT); // código da aplicação
// Include all DAO files
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../banco/include_dao.php';
require_once '../../dao/unidadeDAO.php';
?>
<?php
// start new transaction;
$transaction = new Transaction();
// inicializa o objeto
$homologacao = new Homologacao();
$arr = DAOFactory::getHomologacaoDAO()->queryByCodSub($codsub);
for ($i=0; $i<count($arr); $i++) {
    $row = $arr[$i];
    if ($row->codAplicacao==$codap && $row->ano==$anobase && $row->situacao=="S") {
        $homologacao = $row;
        $homologacao->situacao = "A"; // situação aberta (para nova homologação)
        $homologacao->dataDesbloqueio = date("Y-m-d H:i:s"); // timestamp do dia da atualização 
    }
}
DAOFactory::getHomologacaoDAO()->update($homologacao);
$transaction->commit();
?>
    


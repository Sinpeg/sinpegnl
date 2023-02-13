<?php
require '../../classes/sessao.php';
// objeto da sessão
session_start();
$sessao = $_SESSION["sessao"];
$anobase = $sessao->getAnoBase(); // ano base
$codunidadesup = $sessao->getCodUnidade(); // código da unidade CPGA
?>
<?php
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[43]) {
    exit;
}
?>
<?php
$codsubunidade = filter_input(INPUT_POST, 'codunidade', FILTER_DEFAULT); // código da unidade
$codapp = filter_input(INPUT_POST, 'codapp', FILTER_DEFAULT); // código da aplicação
// Include all DAO files
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../banco/include_dao.php';
require_once '../../dao/unidadeDAO.php';
?>
<?php
// Recuperar todas as subunidades da unidade
$daoun = new UnidadeDAO();
$rows = $daoun->unidadeporcodigo($codunidadesup); // recupera os atributos da unidade
foreach ($rows as $row) {
    $id_unidade = $row["id_unidade"]; // id da unidade responsável   
}
$rows1 = $daoun->queryByUnidadeResponsavel($id_unidade); // busca por unidade responsável
$flag = false; // indica se a subunidade selecionada pertence a unidade
foreach ($rows1 as $row1) {
    if ($row1["CodUnidade"] == $codsubunidade) {
        $flag = true; // neste caso pertence
    }
}
// Fim
?>
<?php

if ($flag) {
// start new transaction;
    $transaction = new Transaction();
    // Verifique se para o ano base, código de aplicação e código da subunidade
    // existe alguma homologação cadastrada
    $arr = DAOFactory::getHomologacaoDAO()->queryByCodSub($codsubunidade);
    $lock = true;
    for ($i = 0; $i < count($arr); $i++) {
        $row = $arr[$i];
        if ($row->ano == $anobase && $row->codAplicacao == $codapp) {
            $lock = false;
        }
    }
    if (!$lock) {
        for ($i = 0; $i < count($arr); $i++) {
            $row = $arr[$i];
            // Se existe homologação cadastrada para 
            // o ano base, código da aplicação e código da subunidade
            if ($row->ano == $anobase && $row->codAplicacao == $codapp) {
                if ($row->situacao == "A") {
                    // neste caso o sistema está aberto pela PROPLAN
                    $dataalt = date("Y-m-d H:i:s"); // data de desbloqueio
                    $obj_h = new Homologacao();
                    $ob_h = $row;
                    // muda novamente o estado para homologado
                    // significa que já foi solicitada nova homologação e liberada pela PROPLAN
                    $ob_h->situacao = "H";
                    $ob_h->dataAlteracao = $dataalt; // data de alteração
                    DAOFactory::getHomologacaoDAO()->update($ob_h);
                    $transaction->commit();
                    echo "O formulário foi homologado novamente no dia " . date("d/m/Y", strtotime($ob_h->dataAlteracao)) . " às " . date("H:i:s", strtotime($ob_h->dataAlteracao));
                } else if ($row->situacao == "H") {
                    // dois casos são possíveis de acontecer
                    // 1 - O formulário ter sido homologado uma vez apenas
                    if ($row->dataAlteracao == "0000-00-00 00:00:00" || is_null($row->dataAlteracao)) {
                        $timestamp = strtotime($row->dataRegistro);
                        echo "Erro ao homologar: O formulário foi homologado no dia " . date("d/m/Y", $timestamp) . " às " . date("H:i:s", $timestamp);
                    }
                    // 2 - O formulário ter sido homologado mais de uma vez
                    else {
                        $timestamp = strtotime($row->dataAlteracao);
                        echo "Erro ao homologar:  O formulário foi homologado pela última vez em " . date("d/m/Y", $timestamp) . " às " . date("H:i:s", $timestamp);
                    }
                } else if ($row->situacao == "S") {
                    echo "Erro ao homologar: Este formulário está sob avaliação para desbloqueio da PROPLAN, portanto não pode ser homologado.";
                }
            }
            // Não existe homologação para o item selecionado 
            // desta forma, pode-se gerar a homologação para o formulário, ano base e 
            // subunidade
        }
    } else {
        $ob_h = new Homologacao();
        $ob_h->ano = $anobase; // config. ano base
        $ob_h->codAplicacao = $codapp; // config. código da aplicação
        $ob_h->codUnidade = $codunidadesup; // config. o código da unidade
        $ob_h->codSub = $codsubunidade; // registra subunidade
        $ob_h->situacao = "H"; // homologado
        $dataHomolog = date("Y-m-d H:i:s");
        $timestamp = strtotime($dataHomolog);
        $ob_h->dataRegistro = $dataHomolog; // data da homologação
        DAOFactory::getHomologacaoDAO()->insert($ob_h);
        echo "Formulário homologado com sucesso!";
        $transaction->commit();
    }
}
<?php
require '../../classes/unidade.php';
require '../../dao/PDOConnectionFactory.php';
require '../../classes/sessao.php';
require '../../modulo/documentopdi/classe/Documento.php';
require '../../modulo/documentopdi/dao/DocumentoDAO.php';
?>
<?php
session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
$codunidade = $sessao->getCodUnidade();
////////////////////////////////////////////////////////////////////////////////
$nomedoc = addslashes($_POST['nomedoc']);
$anoinicial = addslashes($_POST['anoinicial']);
$anofinal = addslashes($_POST['anofinal']);
$missao = addslashes($_POST['missao']);
$visao = addslashes($_POST['visao']);
$situacao = addslashes($_POST['situacao']);
$coddoc = addslashes($_POST['coddoc']);
$objdoc = new Documento();
////////////////////////////////////////////////////////////////////////////////
if (!isset($_POST['pdi'])) {
    $pdi = NULL;
} else {
    $pdi = addslashes($_POST['pdi']);
    $daodoc = new DocumentoDAO();
    $rows = $daodoc->buscadocumento($pdi);
    foreach ($rows as $row) {
        $anoinicial = $row['anoinicial'];
        $anofinal = $row['anofinal'];
    }
}
?>
<?php
if (!$aplicacoes[36] && !$aplicacoes[40]) {
    print "Erro ao acessar esta aplicação";
    exit();
}
?>
<?php
$erro = "";
if (!preg_match('/^([1-9][0-9]*)$/', $pdi) && isset($pdi)) {
    $erro = "Selecione o documento";
} else if ($nomedoc == "") {
    $erro = "Preencha o campo nome do documento";
} else if ($anoinicial == "") {
    $erro = "Preencha o ano inicial";
} else if (!preg_match('/^(2)([0-9]){3}$/', $anoinicial)) {
    $erro = "Informe corretamente o ano inicial";
} else if ($anofinal == "") {
    $erro = "Preencha o ano final";
} else if (!preg_match('/^(2)([0-9]){3}$/', $anofinal)) {
    $erro = "Informe corretamente o ano final";
} else if (($anofinal < $anoinicial)) {
    $erro = "O ano final deve ser maior que o inicial";
} else if ($situacao != 'A' && $situacao != 'D') {
    $erro = "Selecione a situação do documento";
} else if ($missao == "") {
    $erro = "Preencha o campo missão";
} else if ($visao == "") {
    $erro = "Preencha o campo visão";
} else {
    $daodoc = new DocumentoDAO();
    $objdoc->setNome(($nomedoc));
    $objdoc->setAnoInicial($anoinicial);
    $objdoc->setAnoFinal($anofinal);
    $objdoc->setMissao(($missao));
    $objdoc->setVisao(($visao));
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $objdoc->setUnidade($unidade);
    $objdoc->setSituacao($situacao);
    $objdoc->setCodigoPDI($pdi);
    $cadastro = true;
    $rows = $daodoc->buscadocumento($coddoc);
    foreach ($rows as $row) {
        if ($row['CodUnidade'] == $codunidade) {
            $cadastro = false;
            $objdoc->setCodigo($row['codigo']);
        }
    }
    if ($cadastro) {
        $daodoc->insere($objdoc);
        $string = "Documento cadastrado com sucesso!";
    } else {
        $daodoc->altera($objdoc);
        $string = "Documento atualizado com sucesso!";
    }
    $daodoc->fechar();
}
?>
<?php if ($erro != ""): ?>
    <div class="erro">
        <img src="webroot/img/error.png" width="30" height="30"/>
        <?php print $erro; ?>
    </div>
<?php else: ?>
    <div id="success">
        <img src="webroot/img/accepted.png" width="30" height="30"/><?php print $string; ?>
    </div>
<?php endif; ?>

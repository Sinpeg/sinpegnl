<?php
/* DAO */
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/sessao.php';
require_once '../../classes/unidade.php';
require_once '../../dao/unidadeDAO.php';
require_once '../../modulo/documentopdi/dao/DocumentoDAO.php';
require_once '../../modulo/objetivopdi/dao/MapaDAO.php';
require_once '../../modulo/indicadorpdi/dao/IndicadorDAO.php';
require_once '../../modulo/metapdi/dao/MetaDAO.php';
/* Model */
require_once '../../modulo/documentopdi/classe/Documento.php';
require_once '../../modulo/objetivopdi/classe/Mapa.php';
require_once '../../modulo/indicadorpdi/classe/Indicador.php';
require_once '../../modulo/metapdi/classe/Meta.php';
$erro = "";
?>
<?php
session_start();
$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();
$anobase = $sessao->getAnobase();
$coddoc = addslashes($_POST['documento']); // código do documento
$codobjetivo = addslashes($_POST['objetivo']); // código do objetivo
$codindicador = addslashes($_POST['indicador']); // código do objetivo
$meta = addslashes($_POST['meta']); // valor da meta
$metrica = addslashes($_POST['metrica']); // métrica
$tm = addslashes($_POST['tipometa']); // tipo da meta
$coleta = $_POST['coleta']; // tipo da coleta do indicador
$tipometa = (!isset($tm)) ? (0) : (1);
$metrica = strtoupper($metrica);

?>
<?php
if (!$aplicacoes[39]) {
    print "Erro ao acessar esta aplicação";
    exit();
}
?>
<?php
if (!preg_match('/^([1-9])+|([1-9]+[0-9]*)$/', $coddoc)) {
    $erro = "Selecione um documento";
} else if (!preg_match('/^([1-9])+|([1-9]+[0-9]*)$/', $codobjetivo)) {
    $erro = "Selecione um objetivo";
} else if (!preg_match('/^([1-9])+|([1-9]+[0-9]*)$/', $codindicador)) {
    $erro = "Selecione um indicador";
} else if (!preg_match('/^(([0-9]+\,[0-9]+)|([1-9][0-9]*)|([0]{1}))$/', $meta)) {
    $erro = "Preencha corretamente o valor para a meta";
} else if ($coleta != "A" && $coleta != "T" && $coleta != "M" && $coleta != "S") {
    $erro = "Marque o período para os resultados serem coletados";
} else if ($metrica != "Q" && $metrica != "P") {
    $erro = "Marque a métrica para a meta";
} else {
    ?>
    <?php
    $daodoc = new DocumentoDAO();
    $rows = $daodoc->buscadocumento($coddoc);
    $objdoc = new Documento();
    foreach ($rows as $row) {
        if ($anobase >= $row['anoinicial'] && $anobase <= $row['anofinal']) {
            $objdoc->setCodigo($row['codigo']);
            $objdoc->setNome($row['nome']);
        }
    }
    $daomapa = new MapaDAO();
    $objmapa = new Mapa();
    $rows = $daomapa->buscamapa($codobjetivo);
    foreach ($rows as $row) {
        if ($row['CodigoDocumento'] == $objdoc->getCodigo()) {
            $objmapa->setDocumento($objdoc);
            $objmapa->setCodigo($row['Codigo']);
            $objmapa->setOrdem($row['Ordem']);
            $objmapa->setObjetivo($row['Objetivo']);
        }
    }
    $daoind = new IndicadorDAO();
    $objind = new Indicador();
    $rows = $daoind->buscaindicador($codindicador);
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    foreach ($rows as $row) {
        if ($row['CodMapa'] == $objmapa->getCodigo()) {
            $objind->setMapa($objmapa);
            $objind->setCodigo($row['Codigo']);
            $objind->setIndicador($row['indicador']);
            $objind->setUnidade($unidade);
            $objind->setMapa($objmapa);
            
        }
    }
    $daometa = new MetaDAO();
    $objmeta = new Meta();
    $rows = $daometa->buscarmetaindicador($objind->getCodigo());
    foreach ($rows as $row) {
        if ($row['ano'] == $anobase) {
            $objmeta->setIndicador($objind);
            $objmeta->setCodigo($row['Codigo']);
            $objmeta->setMetrica($row['metrica']);
            $objmeta->setPeriodo($row['periodo']);
            $objmeta->setAno($row['ano']);
            $objmeta->setAnoinicial($row['anoinicial']);
            $objmeta->setPeriodoinicial($row['periodoinicial']);
            $objmeta->setAnofinal($row['anofinal']);
            $objmeta->setPeriodofinal($row['periodofinal']);
            
        }
    }
}
?>
<?php if ($erro != ""): ?>
    <div class="erro">
        <img src="webroot/img/error.png" width="30" height="30"/>
    <?php print $erro; ?>
    </div>
<?php else: ?>
    <?php
    // realiza o registro
    $objmeta->setIndicador($objind);
    $objmeta->setPeriodo($coleta);
    $objmeta->setAno($anobase);
    $objmeta->setCumulativo($tipometa);
    $objmeta->setMetrica($metrica);
    $objmeta->setMeta(str_replace(',', '.', $meta));
    $daometa = new MetaDAO();
    $rows = $daometa->altera($objmeta);
    // Fim
    ?>
    <div id="success">
        <img src="webroot/img/accepted.png" width="30" height="30"/><?php print "Meta atualizada com sucesso!"; ?>
    </div>
<?php endif; ?>
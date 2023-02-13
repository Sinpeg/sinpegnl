<?php
session_start();
if (!isset($_SESSION["sessao"])) {
    exit();
}
$codindicador = $_GET['indicador'];
$anobase = $_SESSION['sessao']->getAnobase();
?>
<?php
///* Consultas */
$daodoc = new DocumentoDAO();
$rows = $daodoc->lista();
$objdoc = new Documento();
foreach ($rows as $row) {
    if ($anobase >= $row['anoinicial'] &&
            $anobase <= $row['anofinal'] && $row['situacao'] = 'A') {
        $objdoc->setCodigo($row['codigo']);
        $objdoc->setNome($row['nome']);
        $objdoc->setAnoInicial($row['anoinicial']);
        $objdoc->setAnoFinal($row['anofinal']);
        $objdoc->setMissao($row['missao']);
        $objdoc->setVisao($row['visao']);
    }
}
$cont = 0;
$daomapa = new MapaDAO();
$rows = $daomapa->lista();
$objmapa = array();
foreach ($rows as $row) {
    if ($objdoc->getCodigo() == $row['CodigoDocumento']) {
        $objmapa[$cont] = new Mapa();
        $objmapa[$cont]->setDocumento($objdoc);
        $objmapa[$cont]->setCodigo($row['Codigo']);
        $objmapa[$cont]->setObjetivo($row['Objetivo']);
        $objmapa[$cont]->setOrdem($row['Ordem']);
        $cont++;
    }
}

$daoind = new IndicadorDAO();
$rows = $daoind->buscaindicador($codindicador);
$objetoind = new Indicador();

foreach ($rows as $row) {
    $objetoind->setCodigo($row['Codigo']);
    $objetoind->setIndicador($row['indicador']);
    $objetoind->setCalculo($row['calculo']);
    for ($i = 0; $i < $cont; $i++) {
    if ($objmapa[$i]->getCodigo() == $row["CodMapa"]) {
        $objetoind->setMapa($objmapa[$i]);
    }
}
}
//echo $cont;


$daometa = new MetaDAO();
$rows = $daometa->lista();
$objetometa = new Meta();
foreach ($rows as $row) {
    if ($row['ano'] == $anobase && $row['CodigoIndicador'] == $objetoind->getCodigo()) {
        $objetometa->setMeta($row['meta']);
        $objetometa->setPeriodo($row['periodo']);
        $objetometa->setMetrica($row['metrica']);
        $objetometa->setIndicador($objetoind);
        $objetometa->setCodigo($row['Codigo']);
        $objetometa->setCumulativo($row['cumulativo']);
    }
}
$daores = new ResultadoDAO();
$rows = $daores->buscaresultadometa($objetometa->getCodigo());
$objres = array();
$cont1 = 0; // contador dos resultados
foreach ($rows as $row) {
    $objres[$cont1] = new Resultado();
    $objres[$cont1]->setMeta($objetometa);
    $objres[$cont1]->setMetaAtingida($row['meta_atingida']);
    $objres[$cont1]->setAnaliseCritica($row['analiseCritica']);
    $objres[$cont1]->setPeriodo($row['periodo']);
    $cont1++;
}

?>
<script>
    $(function() {
        $('#accordion').accordion(function() {
        });
    });
</script>
<?php
switch ($objetometa->getPeriodo()) {
    case 'A':
        $periodo = array('Ano de ' . $anobase);
        break;
    case 'T':
        $periodo = array('1º trimestre', '2º trimestre', '3º trimestre',
            '4º trimestre');
        break;
    case 'M':
        $periodo = array('janeiro', 'fevereiro', 'março', 'abril', 'maio',
            'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro',
            'dezembro');
        break;
    case 'S':
        $periodo = array('1º semestre', '2º semestre');
        break;
}
?>
<div id="pdi-resumo">
    <p>Documento:<?php print $objetometa->getIndicador()->getMapa()->getDocumento()->getNome(); ?></p>
    <p>Objetivo Estratégico:<?php print ($objetometa->getIndicador()->getMapa()->getObjetivo()); ?> </span></p>
    <p>Indicador: <?php print ($objetometa->getIndicador()->getIndicador()); ?></p>
    <p>Meta:<?php print ($objetometa->getMeta()); ?></p>
    <p>Métrica:<?php print (($objetometa->getMetrica() == "Q") ? ("Quantitativo") : ("Percentual")); ?></p>
    <p>Forma de Cálculo:<?php print ($objetometa->getIndicador()->getCalculo()); ?>
</div>
<?php if ($cont1==0) { ?>
    <div class="ui-widget">
        <div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">
            <p>
                <span class="ui-icon ui-icon-alert" 
                      style="float: left; margin-right: .3em;"></span>
                <strong>Importante:</strong> Não há resultados cadastrados para este indicador no ano de <?php echo $anobase; ?>.
            </p>
            <p><strong>Indicador: </strong><?php echo ($objetoind->getIndicador()); ?></p>
            <p><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Voltar</a></p>
        </div>
    </div>
<?php } else {?>
<div id="accordion">
    <?php for ($i = 0; $i < count($periodo); $i++) { ?>
        <?php
        $daores = new ResultadoDAO();
        $analise = null;
        $rows = $daores->buscaresultadometa1($objetometa->getCodigo(), ($i + 1));
        if ($rows->rowCount() == 0) {
            $meta_atingida[$i] = "-";
        } else {
            foreach ($rows as $row) {
                $meta_atingida[$i] = $row['meta_atingida'];
                $analise = $row['analiseCritica'];
            }
        }
        $total += ($meta_atingida[$i] == '-') ? (0) : ($meta_atingida[$i]);
        $total_array[$i] = $total;
        /** Procura a situação da meta* */
        $meta = $objetometa->getMeta();
        if ($meta != 0) {
            if ($objetometa->getCumulativo() == "1") {
                $valor_referencia = $total_array[$i];
            } else {
                $valor_referencia = ($meta_atingida[$i] == '-') ? (0) : ($meta_atingida[$i]);
            }
            $situacao = ($valor_referencia / $meta);
            if ($situacao >= 0.9) {
                $sinalizacao[$i] = 'green.png';
                $title[$i] = 'Resultado esperado! O resultado superou 90% em relação a meta';
            } else if ($situacao > 0.6 && $situacao < 0.9) {
                $sinalizacao[$i] = 'yellow.png';
                $title[$i] = 'Atenção! O resultado pelo menos 60% da meta';
            } else {
                $sinalizacao[$i] = 'red.png';
                $title[$i] = 'Abaixo do esperado! O resultado está inferior a 60% da meta';
            }
        } else {
            $sinalizacao[$i] = 'grey.png';
            $title[$i] = 'Indicador indisponível';
        }
        /** Fim * */
        $daores->fechar();
        ?>
        <h3 class="card-title"><?php print $periodo[$i]; ?></h3>
        <div>
            <p>Resultado: <?php print str_replace('.', ',', $meta_atingida[$i]); ?> </p>
            <p>Situação:<a href="#" class="help" data-trigger="hover" data-content='<?php print $title[$i]; ?>' title="Significado" ><img src="webroot/img/bullet-<?php print $sinalizacao[$i]; ?>" width="22" height="22" /></a></p>
            <p>Análise Crítica: <?php print ($analise); ?></p>
            <?php if ($objetometa->getCumulativo() == "1"): // Caso seja quantitativo    ?>
                <p><span class="label">Total:</span><span  class="descricao"> <?php print str_replace('.', ',', $total_array[$i]); ?></span></p>
<?php endif; ?>
        </div>
    <?php } ?>
</div>
<?php } ?>
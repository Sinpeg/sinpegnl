<?php
/* DAO */
require_once '../../../includes/dao/PDOConnectionFactory.php';
require_once '../../../includes/classes/sessao.php';
require_once '../../../includes/classes/unidade.php';
require_once '../../../includes/dao/unidadeDAO.php';
require_once '../dao/DocumentoDAO.php';
require_once '../dao/MapaDAO.php';
require_once '../dao/IndicadorDAO.php';
require_once '../dao/MetaDAO.php';
/* Model */
require_once '../model/Documento.php';
require_once '../model/Mapa.php';
require_once '../model/Indicador.php';
require_once '../model/Meta.php';
?>
<?php
session_start();
$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade(); // código da unidade
$aplicacoes = $sessao->getAplicacoes(); // código das aplicações
$anobase = $sessao->getAnobase();       // ano base
$codestruturado = $sessao->getCodestruturado();
?>
<?php
if (!$aplicacoes[38]) {
    print "Erro ao acessar esta aplicação";
    exit();
}
?>
<?php
$daounidade = new UnidadeDAO();
$rows = $daounidade->buscasubunidades00($codestruturado);
$unidade = array();
$cont = 0;
foreach ($rows as $row) {
    $unidade[$cont] = new Unidade();
    $unidade[$cont]->setCodunidade($row['CodUnidade']);
    $unidade[$cont]->setNomeunidade($row['NomeUnidade']);
    $cont++;
}
$daodoc = new DocumentoDAO();
$objdoc = array();
$cont1 = 0;
for ($i=0; $i<$cont; $i++) {
    $daodoc = new DocumentoDAO();
    $rows = $daodoc->buscadocumentoporunidade($unidade[$i]->getCodunidade());
    foreach ($rows as $row) {
        $objdoc[$cont1] = new Documento();
        $objdoc[$cont1]->setCodigo($row['codigo']);
        $objdoc[$cont1]->setNome($row['nome']);
        $cont1++;
    }
} 
$daomapa = new MapaDAO();
$objmapa = array();
$cont2 = 0;
for ($i = 0; $i < $cont1; $i++) {
    $rows = $daomapa->buscamapadocumento($objdoc[$i]->getCodigo());
    foreach ($rows as $row) {
        $objmapa[$cont2] = new Mapa();
        $objmapa[$cont2]->setCodigo($row['Codigo']);
        $objmapa[$cont2]->setObjetivo($row['Objetivo']);
        $objmapa[$cont2]->setDocumento($objdoc[$i]);
        $cont2++;
    }
}

//$daodoc = new DocumentoDAO();
//$rows = $daodoc->lista();
//$objdoc = array();
//$cont = 0;
//foreach ($rows as $row) {
//    $objdoc[$cont] = new Documento();
//    $objdoc[$cont]->setCodigo($row['codigo']);
//    $objdoc[$cont]->setNome($row['nome']);
//    $objdoc[$cont]->setAnoInicial($row['anoinicial']);
//    $objdoc[$cont]->setAnoFinal($row['anofinal']);
//    $cont++;
//}
//$daomapa = new MapaDAO();
//$objmapa = array();
//$cont1 = 0;
//for ($i = 0; $i < $cont; $i++) {
//    $rows = $daomapa->buscamapadocumento($objdoc[$i]->getCodigo());
//    foreach ($rows as $row) {
//        $objmapa[$cont1] = new Mapa();
//        $objmapa[$cont1]->setCodigo($row['Codigo']);
//        $objmapa[$cont1]->setObjetivo($row['Objetivo']);
//        $objmapa[$cont1]->setDocumento($objdoc[$i]);
//        $cont1++;
//    }
//}
$daoind = new IndicadorDAO();
$objind = array();
$cont3 = 0;
for ($i = 0; $i < $cont2; $i++) {
    $rows = $daoind->buscaindicadorpormapa2($objmapa[$i]->getCodigo());
    foreach ($rows as $row) {
        $unidades_pdi = array(945, 948, 949, 950, 951, 952, 953, 954, 957, 962, 964,
            966, 2504);
        if ($row['PropIndicador'] == $codunidade || in_array($codunidade, $unidades_pdi)) {
            $objind[$cont3] = new Indicador();
            $objind[$cont3]->setMapa($objmapa[$i]);
            $objind[$cont3]->setCodigo($row['Codigo']);
            $objind[$cont3]->setIndicador($row['indicador']);
            $objind[$cont3]->setValidade($row['validade']);
            $cont3++;
        }
    }
}
$daometa = new MetaDAO();
$objmeta = array();
$cont4 = 0;
for ($i = 0; $i < $cont3; $i++) {
    $rows = $daometa->buscarmetaindicador($objind[$i]->getCodigo());
    foreach ($rows as $row) {
        if ($anobase == $row['ano']) {
            $objmeta[$cont4] = new Meta();
            $objmeta[$cont4]->setCodigo($row['Codigo']); // código da meta
            $objmeta[$cont4]->setMeta($row['meta']);    // valor da meta
            $objmeta[$cont4]->setMetrica($row['metrica']); // métrica da meta
            $objmeta[$cont4]->setIndicador($objind[$i]);
            $cont4++;
        }
    }
}
if ($objmeta == NULL) {
    header("Location: incluimeta.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="../../../estilo/estilos.css"/>
        <link rel="stylesheet" href="../../../estilo/msgs.css"/>
        <link rel="stylesheet" href="../../../estilo/table.css"/>
        <link rel="stylesheet" href="../../../estilo/pdi.css"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="../../../includes/scripts/jquery-1.7.2.js"></script>
        <script type="text/javascript" src="../../../includes/scripts/jquery.tablesorter.js"></script>
        <script>
            $(function(){
                $("#tablesorter")
                .tablesorter({
                    widthFixed: true, 
                    headers: {
                        3: {
                            sorter: false
                        }
                    },
                    widgets: ['zebra']
                }
            ); 
            });
        </script>
    </head>
    <body style="font-family: arial, helvetica, sans-serif; font-size: 14px;">
        <h3>Metas</h3>
        <hr style="border-top: 1px solid #0b559b;"/>
        <table class="tab_resultado" id="tablesorter">
            <thead>
                <tr>
                    <th>Indicador</th>
                    <th>Valor da Meta</th>
                    <th>Métrica</th>
                    <th>Editar</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < $cont4; $i++): ?>
                    <tr>
                        <td><?php print ($objmeta[$i]->getIndicador()->getIndicador()); ?></td>
                        <td><?php print (str_replace('.', ',', $objmeta[$i]->getMeta())); ?></td>
                        <td><?php print ($objmeta[$i]->getMetrica() == 'Q') ? ('quantitativo') : ('percentual'); ?></td>
                        <?php
                        $today = new DateTime('now');
                        $validade = new DateTime($objmeta[$i]->getIndicador()->getValidade());
                        ?>
                        <?php if ($today < $validade) : ?>
                         <td><a href="editarmeta.php?codmeta=<?php print $objmeta[$i]->getCodigo(); ?>" title="Editar o valor da meta"><img src="../../../includes/images/editar.gif"/></a></td>
                       <?php else: ?>
                          <td><img src="../../../imagens/edit-not-validated.png" width="20" height="20"/></td>
                       <?php endif; ?>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
        <div class="incluir">
            <span class="plus"></span><a href="incluimeta.php">Incluir nova meta</a>
        </div>
    </body>
</html>
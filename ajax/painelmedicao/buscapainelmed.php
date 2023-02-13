<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

define("BASE_DIR", dirname(__FILE__));
require_once '../../util/Utils.php';
require_once BASE_DIR . '/../../dao/PDOConnectionFactory.php';
require_once BASE_DIR . '/../../classes/unidade.php';
require_once BASE_DIR . '/../../classes/sessao.php';
require_once BASE_DIR . '/../../modulo/documentopdi/classe/Documento.php';
require_once BASE_DIR . '/../../modulo/mapaestrategico/classes/Mapa.php';
require_once BASE_DIR . '/../../modulo/indicadorpdi/classe/Indicador.php';
require_once BASE_DIR . '/../../modulo/documentopdi/dao/DocumentoDAO.php';
require_once BASE_DIR . '/../../modulo/mapaestrategico/dao/MapaDAO.php';
require_once BASE_DIR . '/../../modulo/indicadorpdi/dao/IndicadorDAO.php';
require_once BASE_DIR . '/../../modulo/mapaestrategico/dao/ObjetivoDAO.php';
require_once BASE_DIR . '/../../modulo/mapaestrategico/classes/Objetivo.php';
require_once BASE_DIR . '/../../modulo/metapdi/classe/Meta.php';
require_once BASE_DIR . '/../../modulo/metapdi/dao/MetaDAO.php';
require_once BASE_DIR . '/../../modulo/resultadopdi/classes/Resultado.php';
require_once BASE_DIR . '/../../modulo/resultadopdi/dao/ResultadoDAO.php';

/* Sessão utilizada para esta aplicação é a PDI_PAINEL */
session_start();
$sessao = $_SESSION['sessao'];
if (empty($sessao)) {
    exit();
}

$anobase = $sessao->getAnobase();   // ano base
$coddocumento = $_POST['coddocumento']; // código do documento
$daodoc = new DocumentoDAO();

$rows = $daodoc->buscadocumento($coddocumento);

$contacal=0;
foreach ($rows as $row) {
$contacal++;	
        $objdoc = new Documento();
        $objdoc->setCodigo($row['codigo']);
        $objdoc->setNome($row['nome']);
        $objdoc->setAnoInicial($row['anoinicial']);
        $objdoc->setAnoFinal($row['anofinal']);
        $objdoc->setMissao($row['missao']);
        $objdoc->setVisao($row['visao']);
        $objuni=new Unidade();
        $objuni->setCodunidade($row['CodUnidade']);
        $objdoc->setUnidade($objuni);
}

if ($contacal == 0){
	echo "<div class='erro'>
        	   <img src='webroot/img/error.png' width='30' height='30'/>
    			Dcumento não pertence ao Ano Vigente!
    	  </div>";die;
}

$daomapa = new MapaDAO();
//$rows = $daomapa->lista();

 $rows = $daomapa->buscaMapaByUnidadeDocumentoOuCalendario111($coddocumento, $objdoc->getUnidade()->getCodunidade(),$anobase); 
$objmapa = array();
$mapaInd = array();
$cont = 0;
foreach ($rows as $row) {
   // if ($objdoc->getCodigo() == $row['CodDocumento']) {
    	
        $objmapa[$cont] = new Mapa();
        $daoObjetivo = new ObjetivoDAO();
        $arrayObjetivo = $daoObjetivo->buscaobjetivo($row['codObjetivoPDI'])->fetch();
        $objetivo = new Objetivo();
        $objetivo->setCodigo($arrayObjetivo['Codigo']);
        $objetivo->setObjetivo( $arrayObjetivo['Objetivo']);
        $objetivo->setDescricao($arrayObjetivo['DescricaoObjetivo']);
        $objmapa[$cont]->setObjetivoPDI($objetivo);
        $objmapa[$cont]->setDocumento($objdoc);
        $objmapa[$cont]->setCodigo($row['Codigo']);
        $docreal=new Documento();
        $docreal->setCodigo($row['CodDocumento']);
        $objmapa[$cont]->setDocumento($docreal);
        $mapaInd[$cont] = $row['codIndicador'];
        $cont++;
  //  }
}

$daoind = new IndicadorDAO();
$objetoind = array();
$cont1 = 0;
$cont2 = 0;
foreach ($objmapa as $mapa) {
    $queryInd = $daoind->selectIndicadorByCodMapa22($mapa->getCodigo(),$mapaInd[$cont2]);
    $cont2++;
    foreach ($queryInd as $arrayInd){
        
        	
            $objetoind[$cont1] = new Indicador();
            $objetoind[$cont1]->setCodigo($arrayInd['Codigo']);
            $objetoind[$cont1]->setCalculo($arrayInd['calculo']);
            $objetoind[$cont1]->setMapa($mapa);
            $objetoind[$cont1]->setNome($arrayInd['nome']);
            $cont1++;
        
    }
}
// echo '<pre><br>';var_dump($objetoind);die;

// $daoind = new IndicadorDAO();
// $rows = $daoind->lista();
// $objetoind = array();
// $cont1 = 0;
// foreach ($rows as $row) {
//     for ($i = 0; $i < $cont; $i++) {
//         if ($objmapa[$i]->getCodigo() == $row['CodMapa']) {
//             $objetoind[$cont1] = new Indicador();
//             $objetoind[$cont1]->setCodigo($row['Codigo']);
//             $objetoind[$cont1]->setCalculo($row['calculo']);
//             $objetoind[$cont1]->setMapa($objmapa[$i]);
//             $objetoind[$cont1]->setIndicador($row['indicador']);
//             $cont1++;
//         }
//     }
// }
?>

<script>
        $(function() {
            $.extend($.tablesorter.themes.bootstrap, {
                table: 'table table-bordered',
                caption: 'caption',
                header: 'bootstrap-header', // give the header a gradient background
                footerRow: '',
                footerCells: '',
                icons: '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
                sortNone: 'bootstrap-icon-unsorted',
                sortAsc: 'icon-chevron-up glyphicon glyphicon-chevron-up', // includes classes for Bootstrap v2 & v3
                sortDesc: 'icon-chevron-down glyphicon glyphicon-chevron-down', // includes classes for Bootstrap v2 & v3
                active: '', // applied when column is sorted
                hover: '', // use custom css here - bootstrap class may not override it
                filterRow: '', // filter row class
                even: '', // odd row zebra striping
                odd: ''  // even row zebra striping
            });

    // call the tablesorter plugin and apply the uitheme widget
            $("table").tablesorter({
    // this will apply the bootstrap theme if "uitheme" widget is included
    // the widgetOptions.uitheme is no longer required to be set
                theme: "bootstrap",
                widthFixed: true,
                headerTemplate: '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

    // widget code contained in the jquery.tablesorter.widgets.js file
    // use the zebra stripe widget if you plan on hiding any rows (filter widget)
                widgets: ["uitheme", "filter", "zebra"],
                widgetOptions: {
                    // using the default zebra striping class name, so it actually isn't included in the theme variable above
                    // this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
                    zebra: ["even", "odd"],
                    // reset filters button
                    filter_reset: ".reset"

                            // set the uitheme widget to use the bootstrap theme class names
                            // this is no longer required, if theme is set
                            // ,uitheme : "bootstrap"

                }
            })
                .tablesorterPager({
    // target the pager markup - see the HTML block below
                container: $(".ts-pager"),
    // target the pager page select dropdown - choose a page
                cssGoto: ".pagenum",
    // remove rows from the table to speed up the sort of large tables.
    // setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
                removeRows: false,
    // output string - default is '{page}/{totalPages}';
    // possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
                output: '{startRow} - {endRow} / {filteredRows} ({totalRows})'

            });

        });

        $(function() {

            $.extend($.tablesorter.defaults, {
                widthFixed: true,
                widgets: ['zebra', 'columns'],
                sortList: [[0, 0], [1, 0], [2, 0]]
            });

            $('.demo').tablesorter();

            // grey & dropbox themes need the {icon} for header icons
            $('.tablesorter-dropbox,.tablesorter-grey').tablesorter({
                headerTemplate: '{content}{icon}' // dropbox theme doesn't like a space between the content & icon
            });

            $('.tablesorter-bootstrap').tablesorter({
                theme: 'bootstrap',
                headerTemplate: '{content} {icon}',
                widgets: ['zebra', 'columns', 'uitheme']
            });

            $('.tablesorter-jui').tablesorter({
                theme: 'jui',
                headerTemplate: '{content} {icon}',
                widgets: ['zebra', 'columns', 'uitheme']
            });

        });
        $(document).ready(function() {
            $("#tablesorter").tablesorter({
                theme: 'blue',
                // initialize zebra striping of the table
                widgets: ["zebra"],
                // change the default striping class names
                // updated in v2.1 to use widgetOptions.zebra = ["even", "odd"]
                // widgetZebra: { css: [ "normal-row", "alt-row" ] } still works
                widgetOptions: {
                    zebra: ["normal-row", "alt-row"]
                }
            });
        });

        $(function() {
            $('#accordion').accordion(function() {
            });
        });
</script>
<br>
<div class="card">
    <div class="card-body">
        <table id="tablesorter" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Objetivo</th>
                    <th>Indicador</th>
                    <th>Parcial</th>
                    <th>Final</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i=0; $i<$cont1; $i++) { ?>
                    <tr>
                        <td><?php print ($objetoind[$i]->getMapa()->getObjetivoPDI()->getObjetivo()); ?></td>
                        <td><?php print ($objetoind[$i]->getNome()); ?></td>
                        <td align="center">  
                            <a href='<?php echo Utils::createLink("painelmedicao", "consultapainel", array('indicador' => $objetoind[$i]->getCodigo(),'codmapa'=>$objetoind[$i]->getMapa()->getCodigo(),'coddoc'=>$objetoind[$i]->getMapa()->getDocumento()->getCodigo(),'p'=>1)); ?>'><img src="webroot/img/busca.png"/></a>
                        </td>
                        <td align="center"> <a href='<?php echo Utils::createLink("painelmedicao", "consultapainel", array('indicador' => $objetoind[$i]->getCodigo(),'codmapa'=>$objetoind[$i]->getMapa()->getCodigo(),'coddoc'=>$objetoind[$i]->getMapa()->getDocumento()->getCodigo(),'p'=>2)); ?>'><img src="webroot/img/b1.png" width="30" height="30"/></a>
                        </td>
                    </tr>
                
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php
define("BASE_DIR", dirname(__FILE__), TRUE);
require_once '../../util/Utils.php';
require_once BASE_DIR . '/../../dao/PDOConnectionFactory.php';
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

?>
<?php
/* Sessão utilizada para esta aplicação é a PDI_PAINEL */
session_start();
$sessao = $_SESSION['sessao'];
if (empty($sessao)) {
    exit();
}

$anobase = $sessao->getAnobase();   // ano base
$coddocumento = $_POST['coddocumento']; // código do documento
$daodoc = new DocumentoDAO();



$rows = $daodoc->buscadocumentoPorAnoGestao($coddocumento, $anobase);



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
}



if ($contacal == 0){
	echo "<div class='erro'>
        	   <img src='webroot/img/error.png' width='30' height='30'/>
    			Dcumento não pertence ao Ano Vigente!
    	  </div>";die;
}


$daomapa = new MapaDAO();
$rows = $daomapa->lista();
$objmapa = array();
$cont = 0;
foreach ($rows as $row) {
    if ($objdoc->getCodigo() == $row['CodDocumento']) {
        $objmapa[$cont] = new Mapa();
        $daoObjetivo = new ObjetivoDAO();
        $arrayObjetivo = $daoObjetivo->buscaobjetivo($row['codObjetivoPDI'])->fetch();
        $objetivo = new Objetivo();
        $objetivo->_construct($arrayObjetivo['Codigo'], $arrayObjetivo['Objetivo'], $arrayObjetivo['DescricaoObjetivo']);
        $objmapa[$cont]->setObjetivoPDI($objetivo);
        $objmapa[$cont]->setDocumento($objdoc);
        $objmapa[$cont]->setCodigo($row['Codigo']);
        $cont++;
    }
}


$daoind = new IndicadorDAO();
$objetoind = array();
$cont1 = 0;
foreach ($objmapa as $mapa) {
	$arrayInd = $daoind->selectIndicadorByCodMapa($mapa->getCodigo())->fetch();
	if($arrayInd){	
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

<table id="tablesorter" class="table table-bordered table-hover">
    <thead>
        <tr>
            <th style="width: 80px;">Ordem</th>
            <th>Objetivo</th>
            <th>Indicador</th>
            <th>Consultar</th>
        </tr>
    </thead>
    <tfoot>
            <tr>
                <th colspan="7" class="ts-pager form-horizontal">
                    <button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
                    <button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
                    <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                    <button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
                    <button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
                    <select class="pagesize input-mini" title="Select page size">
                        <option selected="selected" value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                    </select>
                    <select class="pagenum input-mini" title="Select page number"></select>
                </th>
            </tr>
    </tfoot>
    <tbody>
        <?php for ($i=0; $i<$cont1; $i++) { ?>
            <tr>
                <td><?php print ($objetoind[$i]->getMapa()->getObjetivoPDI()->getCodigo()); ?></td>
                <td><?php print ($objetoind[$i]->getMapa()->getObjetivoPDI()->getObjetivo()); ?></td>
                <td><?php print ($objetoind[$i]->getNome()); ?></td>
                <td>  
    				<a href='<?php echo Utils::createLink("painelmedicao", "consultapainel", array('indicador' => $objetoind[$i]->getCodigo())); ?>'><img src="webroot/img/busca.png"/></a>
  				</td>
            </tr>
           
        <?php } ?>
    </tbody>
</table>

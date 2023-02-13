<?php
$sessao = $_SESSION['sessao'];
$codestruturado = $sessao->getCodestruturado();
$codUnidade = $sessao->getCodunidade();
$aplicacoes = $sessao->getAplicacoes(); // código das aplicações
$coddoc=$_SESSION['coddoc'] ;
$codmapa = $_SESSION['codmapa'] ;

?>
<?php
if (!$aplicacoes[38]) {
    print "Erro ao acessar esta aplicação";
    exit();
}
?>
<?php
/*
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
for ($i = 0; $i < $cont; $i++) {
	$daodoc = new DocumentoDAO();
	$rows = $daodoc->buscadocumentoporunidade($unidade[$i]->getCodunidade());
	foreach ($rows as $row) {
		$objdoc[$cont1] = new Documento();
		$objdoc[$cont1]->setCodigo($row['codigo']);
		$objdoc[$cont1]->setNome($row['nome']);
		$objdoc[$cont1]->setUnidade($unidade[$i]);
		$objdoc[$cont1]->setAnoInicial($row['anoinicial']);
		$objdoc[$cont1]->setAnoFinal($row['anofinal']);
		$cont1++;
	}
}

$daomapa = new MapaDAO();
$daoobjetivo = new ObjetivoDAO();
$objmapa = array();
$objobjetivo = array();
$cont2 = 0;
for ($i = 0; $i < $cont1; $i++) {
	$rows = $daomapa->buscamapadocumento($objdoc[$i]->getCodigo());
	foreach ($rows as $row) {
		$objmapa[$cont2] = new Mapa();
		$objmapa[$cont2]->setCodigo($row['Codigo']);
		$rowsobjetivo = $daoobjetivo->buscaobjetivo($row['codObjetivoPDI']);
		foreach ($rowsobjetivo as $rowobjetivo)
		{
			$objobjetivo[$cont2] = new Objetivo();
			$objobjetivo[$cont2]->setCodigo($rowobjetivo['Codigo']);
			$objobjetivo[$cont2]->setObjetivo($rowobjetivo['Objetivo']);
		}
		$objmapa[$cont2]->setObjetivoPDI($objobjetivo[$cont2]);
		$objmapa[$cont2]->setDocumento($objdoc[$i]);
		$cont2++;
	}
}

 $daoind = new IndicadorDAO();
$objind = array();
$cont3 = 0;
for ($i = 0; $i < $cont2; $i++) {
    $rows = $daoind->buscaindicadorpormapa2($objmapa[$i]->getCodigo());
    foreach ($rows as $row) {
        $objind[$cont3] = new Indicador();
        $objind[$cont3]->setCodigo($row['Codigo']);
        $objind[$cont3]->setMapa($objmapa[$i]);
        $objind[$cont3]->setIndicador($row['indicador']);
        $objind[$cont3]->setValidade($row['validade']);
        $cont3++;
    }
}
*/
$daoobjetivo = new ObjetivoDAO();
        $rowsobjetivo = $daoobjetivo->buscaObjetivoPorMapa($codmapa);
		$objobjetivo = new Objetivo();
		foreach ($rowsobjetivo as $rowobjetivo)
		{
			$objobjetivo->setCodigo($rowobjetivo['codobj']);
			$objobjetivo->setObjetivo($rowobjetivo['des']);
		}

$daoind = new IndicadorDAO();
$objind = array();
$cont3 = 0;
	$rows = $daoind->listaIndicadorNaoVinculado($sessao->getAnobase(),$coddoc);
	foreach ($rows as $row) {
		$objind[$cont3] = new Indicador();
		$objind[$cont3]->setCodigo($row['Codigo']);		
		$objind[$cont3]->setNome($row['nome']);
		$objind[$cont3]->setValidade($row['validade']);
		$cont3++;
}

if ($cont3 == 0) {
    Utils::redirect('indicadorpdi', 'incluiindicador');
}
?>

<script>
    $(function() {
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
                ).tablesorterPager(
                {
                    container: $("#pager"),
                    positionFixed: false,
                    size: 10
                });

    });
</script>
</head>
<body style="font-family: arial, helvetica, sans-serif; font-size: 14px;">
    <h3 class="card-title">Cesta de Indicadores</h3>
    <hr style="border-top: 1px solid #0b559b;"/>
 
 Objetivo:<?php  echo $objobjetivo->getObjetivo();?>
 <table id="tablesorter" class="table table-bordered table-hover" >
		<tfoot>
		        <tr>
		            <th colspan="7" class="ts-pager form-horizontal">
		                <button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
		                <button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
		                <span class="pagedisplay"></span> <!-- this can be any element, including an input class="form-control"-->
		                <button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
		                <button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
		                <select class="custom-select" title="Select page size">
		                    <option selected="selected" value="10">10</option>
		                    <option value="20">20</option>
		                    <option value="30">30</option>
		                    <option value="40">40</option>
		                </select>
		                <select class="pagenum input-mini" title="Select page number"></select>
		            </th>
		        </tr>
		    </tfoot>
		
			<thead>
            <tr>
                <th>Indicador</th>
                <th>Vincular Indicador</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < $cont3; $i++) { ?>
                <tr>               
                    <td><?php print ($objind[$i]->getNome()); ?></td>
                    <td><a href="<?php echo Utils::createLink('indicadorpdi', 'vincularindicador', array( 'ind'=> $objind[$i]->getCodigo(), 'mapa'=> $codmapa)); ?>"><img src="webroot/img/editar.gif"/></a></td>
               
                </tr>
            <?php } ?>

        </tbody>
    </table>
    <div class="incluir">
    
    
   
    
    
     <form class="form-horizontal" method="post" action="<?php  echo Utils::createLink('indicadorpdi', 'consultaindicador'); ?>">
	 <input class="form-control"type=hidden name="coddoc" value="<?php echo $coddoc; ?>" />
	 <input class="form-control"type="hidden" name="codigo" value="<?php echo $codmapa; ?>" />
	 
	 <input class="btn btn-info" type="submit"  value="Voltar" name="cp"  class="btn btn-info btn"  />
     </form>
    </div>
</body>
</html>
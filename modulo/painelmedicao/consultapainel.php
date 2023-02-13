<script type="text/javascript">     
     google.charts.load('current', {'packages':['bar']});
</script>

<?php


if (!isset($_SESSION["sessao"])) {
	exit();
}
$anobase = $_SESSION['sessao']->getAnobase();

///* Consultas */
$daodoc = new DocumentoDAO();
$rows = $daodoc->buscadocumento(addslashes($_GET['coddoc']));
$objdoc = new Documento();
foreach ($rows as $row) {
   
        $objdoc->setCodigo($row['codigo']);
        $objdoc->setNome($row['nome']);
        $objdoc->setAnoInicial($row['anoinicial']);
        $objdoc->setAnoFinal($row['anofinal']);
        $objdoc->setMissao($row['missao']);
        $objdoc->setVisao($row['visao']);    
}

$objcal=new Calendario();
$daocal=new CalendarioDAO();

$rows=$daocal->buscaCalendarioporAnoBaseOnly($anobase);

foreach ($rows as $r){
	$objcal->setCodigo($r['codCalendario']);
}

$cont = 0;
$daomapa = new MapaDAO();
$rows = $daomapa->lista($anobase);
$objmapa = array();

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
        //echo $objmapa[$cont]->getCodigo()."-".$_GET['indicador']."<br>";
        $cont++;
    }
}

$mapaindicador = new Mapaindicador();
$daomapaindicador = new MapaIndicadorDAO();
$daoind = new IndicadorDAO();
$rows = $daoind->selectIndicadorByCodMapa2(addslashes($_GET['indicador']),addslashes($_GET['codmapa']));
$objetoind = new Indicador();
$indicemapa=-1;

foreach ($rows as $row) {
	//echo "passou1";
    $objetoind->setCodigo($row['codIndicador']);
    $objetoind->setNome($row['nome']);
    $objetoind->setCalculo($row['calculo']);
    $objetoind->setInterpretacao($row['interpretacao']);
	$mapaindicador->setIndicador($objetoind);
	for ($i=0;$i<count($objmapa);$i++){
		
		if ($objmapa[$i]->getCodigo()==$row['codMapa']){
		   $mapaindicador->setMapa($objmapa[$i]);
		   $objmapa[$i]->setMapaindicador($mapaindicador);
		   $indicemapa=$i;
		  // echo "passou";
		}
	}
    $mapaindicador->setCodigo($row['codigo']);
    $mapaindicador->setPropindicador($row['PropIndicador']);
}


//Verificar valor da interpretação
$interpretacao="";
if($objetoind->getInterpretacao() == 1){
	$interpretacao = "Quanto maior,melhor.";
}else if($objetoind->getInterpretacao() == 2){
	$interpretacao = "Quanto menor, melhor.";
}

$daocalendario = new CalendarioDAO();
$daometa = new MetaDAO();
$objetometa = new Meta();
$arraycal = $daocalendario->buscaCalendarSomenteioPorAnoBase($anobase);
foreach ($arraycal as $r) {
	$cal=$r['codCalendario'];
}

//echo $objmapa[$indicemapa]->getMapaindicador()->getCodigo().','.$cal;
$arraymetas = $daometa->buscarmetaindicador($objmapa[$indicemapa]->getMapaindicador()->getCodigo(),$cal);

foreach ($arraymetas as $arraymeta){
//	echo 'passou1';
    if ($arraymeta['ano'] == $anobase) {
//	echo 'passou2';
        $objetometa->setMeta($arraymeta['meta']);
        $objetometa->setPeriodo($arraymeta['periodo']);
        $objetometa->setMetrica($arraymeta['metrica']);
        $objetometa->setMapaindicador($mapaindicador);
        $objetometa->setCodigo($arraymeta['Codigo']);
        $objetometa->setCumulativo($arraymeta['cumulativo']);
    }
}

$daores = new ResultadoDAO();

$rows = $daores->buscaresultaperiodometa($objetometa->getCodigo(),$_GET['p']);
$objres = array();
$cont1 = 0; // contador dos resultados
//echo $cont1;
foreach ($rows as $row) {
//	echo "passou";
    $objres[$cont1] = new Resultado();
    $objres[$cont1]->setMeta($objetometa);
    $objres[$cont1]->setMetaAtingida($row['meta_atingida']);
    $objres[$cont1]->setAnaliseCritica($row['analiseCritica']);
    $objres[$cont1]->setPeriodo($row['periodo']);
    $cont1++;
}
//echo "codigo meta".$objetometa->getCodigo().",".$_GET['p'];die;

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
    case 'P':
        $periodo = array('Parcial', 'Final');
        break;
}
?>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
            <li class="active">
                <form class="form-horizontal" method="post" action="<?php print Utils::createLink('painelmedicao', 'painelmed') ?>">
                    <input class="form-control"type="hidden" name="codDocumento" value="<?php print ($objdoc->getCodigo()); ?>">
                    <input class="form-control"style="border: inherit; background: inherit;" class="btn btn-info" type="submit"  name="teste" value="Painel de Medição" />
                </form>
            </li>
            <li>
                Consulta
            </li>  
		</ul>
	</div>
</head>

<div class="card card-info">
    <div class="card-body">
        <div id="pdi-resumo">
            <p><b>Documento:</b> <?php print $objdoc->getNome(); ?></p>
            <p><b>Objetivo Estratégico:</b> <?php  print $objmapa[$indicemapa]->getObjetivoPDI()->getObjetivo(); ?> </span></p>
            <p><b>Indicador:</b> </td><td><?php print ($objmapa[$indicemapa]->getMapaindicador()->getIndicador()->getNome()); ?></p>
            <p><b>Forma de Cálculo:</b> </td><td><?php print ($objmapa[$indicemapa]->getMapaindicador()->getIndicador()->getCalculo()); ?></p>
            <p><b>Métrica:</b> </td><td><?php print (($objetometa->getMetrica() == "Q") ? ("Absoluto") : ("Percentual")); ?></p>
            <p><b>Meta:</b> </td><td><?php  print ($objetometa->getMeta());  ?></p>
            <p><b>Interpretação:</b> </td><td><?php  print ($interpretacao);  ?></p>	       
        </div>

        <?php if ($cont1==0) { ?>
            <div class="ui-widget">
                <div class="ui-state-highlight ui-corner-all" style="padding: 3 .7em;">
                    <p>
                        <span class="ui-icon ui-icon-alert" 
                            style="float: left; margin-left:3px; margin-right: .3em;"></span>
                        <strong>Importante:</strong> Não há resultados cadastrados para este indicador no ano de <?php echo $anobase; ?>. 
                                Caso a meta seja igual a 0, nenhum resultado será lançado.
                    </p>
                    <p><strong>Indicador: </strong><?php echo ($objetoind->getNome()); ?></p>

                </div>
            </div>
            <br>
            <form class="form-horizontal" align="center" method="post" style="padding:0" action="<?php print Utils::createLink('painelmedicao', 'painelmed') ?>">
                    <input class="form-control"type="hidden" name="codDocumento" value="<?php print ($objdoc->getCodigo()); ?>">
                    <input class="btn btn-info" id="botao" style="border: inherit;" class="btn btn-info" type="submit"  name="teste" value="Voltar" />
            </form>
        <?php } else {?>
            <?php
            $total=0;
            $daores = new ResultadoDAO();?>
            <?php
            $per= $_GET['p'];
            $i=$per-1;
            $analise = null;
            $rows = $daores->buscaresultadometa1($objetometa->getCodigo(),$per );
            if ($rows->rowCount() == 0) {
                $meta_atingida[$i] = "-";
            } else {
                foreach ($rows as $row) {
                    $meta_atingida[$i] = $row['meta_atingida'];
                    $analise = $row['analiseCritica'];
                }
            
                include 'vissemaforo.php';  
                        
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="card-title"><?php print $periodo[$i]; ?></h3></div>
                    <div class="panel-body">
                        <h3 class="card-title">Indicador </h3>
                        <table>
                            <tr><td>Resultado:</td><td> <?php print str_replace('.', ',', $meta_atingida[$i]); ?> </td></tr>
                            <tr><td>Situação:</td><td><a href="#" class="help" data-trigger="hover" data-content='<?php print $title[$i]; ?>' title="Significado" >
                            <img src="webroot/img/bullet-<?php print $sinalizacao[$i]; ?>" width="22" height="22" /></a></td></tr>
                        </table>
                        <br><br>
                        <p>Tabela da Série Histórica do Desempenho do Indicador:</p>
                        
                        <?php  
                        //   if ($periodo[$i]='Parcial'){
                        include 'geragrafico2.php'; 
                        /*  }else if ($periodo[$i]='Final'){
                            include 'geragrafico3.php';
                            
                            }*/
                        ?>
                        
                        <p>Análise Crítica: <?php print ($analise); ?></p>
                        <?php if ($objetometa->getCumulativo() == "1"){ // Caso seja quantitativo    ?>
                            <p><span class="label" style="color: black;">Total:</span><span  class="descricao"> <?php print str_replace('.', ',', $total_array[$i]); ?></span></p>
                        <?php }   
                            //INICIATIVA
                                            
                        include 'visiniciativa.php'; 
                    ?> </div>	
                </div>      
            <?php  }  
        }  //nao tem resultado

        $daores->fechar(); ?>
    </div>
</div> 

        

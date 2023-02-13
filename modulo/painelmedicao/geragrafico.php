<?php
	//session_start();
	if (!isset($_SESSION["sessao"])) {
		exit();
	}
	?>
	
	<?php
switch ($_POST['periodo_meta']) {
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
	
	<?php
	$daometa = new MetaDAO();
	$queryMetRes = $daometa->buscaMetaResultadoporCodMapaIndi($_POST['codMapaIndicador']);
	$i=0;
	
	
	if($_POST['periodo'] == 'Parcial'){
		foreach ($queryMetRes as $metaresult){
			if($metaresult['periodo'] == '1'){
				$arraymetares[$i++] = $metaresult;
			}
		}
	} elseif ($_POST['periodo'] == 'Final'){
		foreach ($queryMetRes as $metaresult){
			if($metaresult['periodo'] == '2'){
				$arraymetares[$i++] = $metaresult;
			}
		}
	}elseif($_POST['periodo_meta'] == "M"){
		foreach ($queryMetRes as $metaresult){
				$indice = $metaresult['periodo']-1;
				$metaresult["ano"] = $periodo["{$indice}"];
				$arraymetares[$i++] = $metaresult;
		}
	}else{
		foreach ($queryMetRes as $metaresult){
			$arraymetares[$i++] = $metaresult;
		}
	}
	
		
	?>
    
	<head>
		<div class="bs-example">
			<ul class="breadcrumb">
			    <li>
				    <form class="form-horizontal" method="post" action="<?php print Utils::createLink('painelmedicao', 'consultapainel',array('coddoc'=>$_POST['coddoc']));print("&indicador=");print ($_POST['codIndicador']);?>">
		            	<input class="form-control"type="hidden" name="indicador" value="<?php ; ?>">
		            	<input class="form-control"style="border: inherit; background: inherit;" class="btn btn-info" type="submit"  name="teste" value="Consulta Painel" />
		            </form>
			    </li>
				<li class="active">Consulta</li>  
			</ul>
		</div>

		
		<script type="text/javascript" src="webroot/js/jquery-ui-1.10.3/js/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="webroot/js/jquery-ui-1.10.3/js/jquery-ui-1.10.3.custom.js"></script>


		<!-- Bootstrap 4 -->
		<script src="novo_layout/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
		<!-- AdminLTE App -->
		<script src="novo_layout/dist/js/adminlte.min.js"></script>
		<!-- AdminLTE for demo purposes <script src="novo_layout/dist/js/demo.js"></script>-->	<script type="text/javascript" src="webroot/js/jquery-ui-1.10.3/js/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="webroot/js/jquery-ui-1.10.3/js/jquery-ui-1.10.3.custom.js"></script>

		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
			google.charts.load('current', {'packages':['corechart']});

			google.charts.load('current', {'packages':['bar']});
			google.charts.setOnLoadCallback(drawChart);

			function drawChart() {
				
				var data = new google.visualization.DataTable([
				
				
				<?php
				
				//define o titulo das colunas do gráfico
				if($_POST['periodo_meta'] == "A" || $_POST['periodo_meta'] == "P")
				{
						print ("['Ano', 'Meta', 'Resultado'],");
				}
				else if($_POST['periodo_meta'] == "M")
				{
						print ("['Mês', 'Meta', 'Resultado'],");
				} 
				
				//define o corpo do gráfico, verifica se valores são cumulativos e se os periodos são anuais mensais trimestrrais etc.. 
				foreach ($arraymetares as $metaresult){
					
					if($_POST['cumulativo']==1 && $_POST['periodo_meta'] == "P")
					{
						print ("['{$metaresult['ano']}'],[{$metaresult['meta']}],[{$_POST['total_meta']}],false ");
						$cont++;
					}
					else if($_POST['periodo_meta'] == "M")
					{
						if($_POST['cumulativo']==1)
						{
							$metaating = $metaating + $metaresult['meta_atingida'];}else{$metaating = $metaresult['meta_atingida'];
						}
						
						print ("['{$metaresult['ano']}',{$metaresult['meta']},{$metaating}], ");
						$cont++;
					}
					else
					{
						print ("['{$metaresult['ano']}',{$metaresult['meta']},{$metaresult['meta_atingida']}], ");
					} 
				
				}
				?>
			]);
		
				var options = {
				chart: {
					title: 'Série Histórica Metas e Resultado',
					subtitle: 'Ano, Metas e Resultado 2011-2017',
				}
				};
		
				var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
		
				chart.draw(data, google.charts.Bar.convertOptions(options));
				
				var chart_div = document.getElementById('chart_div');
				var chart = new google.visualization.ColumnChart(chart_div);

				// Wait for the chart to finish drawing before calling the getImageURI() method.
			google.visualization.events.addListener(chart, 'ready', function () {
				chart_div.innerHTML = '<a href="' + chart.getImageURI() + '" download ><img src="webroot/img/pngicon.png"></a>';
				console.log(chart_div.innerHTML);
				});

				chart.draw(data, options);
				
			}
		
		</script>
	</head>

	
    
    <div id="pdfg"></div>
    
  <div id="columnchart_material" <?php if($cont < 6){ $cont = $cont*30; print("style='width:{$cont}%;height:400px;max_width:100%;'");}else{print("style='height:400px;max_width:100%;'");} ?> ></div>
     
     <div id="chart_div"></div>
    <div id="toolbar_div"></div>
    
	<br><h3 class="card-title"><?php
	if($_POST['periodo_meta'] == "A" || $_POST['periodo_meta'] == "P"){
		print ("<h4> Resultado: {$_POST['periodo']}</h4>");
	}else if($_POST['periodo_meta'] == "M"){
		print ("<h4> Resultado: Mensal</h4>");
	}
	print $_POST['nomeIndicador']; ?></h3>
	

    <?php foreach ($arraymetares as $metaresult){ ?>
	        <div id="pdi-resumo" >
	        <?php
		        if($_POST['periodo_meta'] == "A" || $_POST['periodo_meta'] == "P"){
		        	echo ("<p><label>Ano:</label>{$metaresult['ano']}</p>");
		        }else if($_POST['periodo_meta'] == "M"){
		        	print ("<p><label>Mes:</label>{$metaresult['ano']}</p>");
		        }
	        ?>
	        
	        <p><label>Análise Crítica:</label><?php print $metaresult['analiseCritica']; ?></p>
	        </div>
	<?php } ?>
	
	

	 
	 


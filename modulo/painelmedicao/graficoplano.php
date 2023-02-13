<?php

//session_start();
if (!isset($_SESSION["sessao"])) {
	exit();
}

?>

<?php 

	$daoresultado = new ResultadoDAO();
	$queryresultado = $daoresultado->buscaResMetaMapIndDoc(1);
	$i=0;
	
	$arraypercent['green'] = 0;$arraypercent['yellow'] = 0;$arraypercent['red'] = 0;
	foreach ($queryresultado as $resultado){
		$meta = $resultado['meta'];
		$meta_atingida = $resultado['meta_atingida'];
		if($meta != 0){
			$percentMeta = $meta_atingida/$meta;
			if($percentMeta >= 0.9) {$arraypercent['green'] = $arraypercent['green']+1;}
			elseif($percentMeta >= 0.6 && $percentMeta < 0.9){$arraypercent['yellow'] = $arraypercent['yellow']+1;}
			else{$arraypercent['red'] = $arraypercent['red']+1;}
		}
		 
	}
// 	var_dump($arraypercent);die;
?>


<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'sdsd'],
          ['',     0],
          ['menos de 60%', <?php print $arraypercent['red'];?>],
          ['entre 60% e 90%',  <?php print $arraypercent['yellow'];?>],
          ['mais de 90%', <?php print $arraypercent['green'];?>],
        ]);

        var options = {
          title: 'Rendimendo dos Indicadore para cada Plano.'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
    
    
    <body>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
  </body>
    

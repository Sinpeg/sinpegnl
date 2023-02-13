<?php
	//session_start();
	if (!isset($_SESSION["sessao"])) {
		exit();
	}
	


$daometa = new MetaDAO();
	$queryMetRes = $daometa->buscaMetaResultadoporCodMapaIndiResult($mapaindicador->getCodigo(),$objdoc->getAnoInicial(),$objdoc->getAnoFinal());
	
	
	if($periodo[$i] == 'Parcial'){
		foreach ($queryMetRes as $metaresult){
			if($metaresult['periodo'] == '1'){
				$arraymetares[$i++] = $metaresult;
			}
		}
	} elseif ($periodo[$i] == 'Final'){
		foreach ($queryMetRes as $metaresult){
			if($metaresult['periodo'] == '2'){
				$arraymetares[$i++] = $metaresult;
			}
		}
	}elseif($objetometa->getPeriodo() == "M"){
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

	$x='Meta e Resultado:'.$objdoc->getAnoInicial().','.$objdoc->getAnoFinal();
	
	?>
   <head>
   
     <script type="text/javascript">
     //para a tabela do gráfico
       google.charts.load('current', {'packages':['table']});
      google.charts.setOnLoadCallback(drawTableParcial);

      function drawTableParcial() {
          
    	  var data = new google.visualization.DataTable();

    	  <?php  if($objetometa->getPeriodo() == "A" || $objetometa->getPeriodo() == "P")
          {?>
          data.addColumn('string', 'Ano');
          data.addColumn('string', 'Meta');
          data.addColumn('string', 'Resultado');
          <?php }  ?>
        data.addRows([ 
                      

<?php
$linha='';
	$cont=0;
	$numlinhas=count($arraymetares);

                   foreach ($arraymetares as $metaresult){?>
                      ['<?php echo $metaresult['ano'];?>','<?php echo str_replace('.', ',',$metaresult['meta']); ?>',
                       '<?php echo str_replace('.', ',',$metaresult['meta_atingida']); ?>']
                   <?php
       	            $cont++;           	
     			    if ($cont!=$numlinhas){
     	          	   print( "," );
     	          }
     	          }

?>

]);


                   
                      
                        

          var table = new google.visualization.Table(document.getElementById('table_div'));

          table.draw(data, { width: '100%', height: '100%'});


     
      }
     


     //para o gráfico
     google.charts.setOnLoadCallback(drawChartParcial);

     
     function drawChartParcial() {
       var data = google.visualization.arrayToDataTable([
       
	  <?php
	          
			  //define o titulo das colunas do gráfico
	          if($objetometa->getPeriodo() == "A" || $objetometa->getPeriodo() == "P")
	          {
	          		print ("['Ano', 'Meta', 'Resultado'],");
	          }
	          else if($objetometa->getPeriodo()== "M")
	          {
	          		print ("['Mês', 'Meta', 'Resultado'],");
	          } 
	          
	          //define o corpo do gráfico, verifica se valores são cumulativos e se os periodos são anuais mensais trimestrrais etc.. 
	          foreach ($arraymetares as $metaresult){
	          	
			      if($objetometa->getCumulativo() ==1 && $objetometa->getPeriodo() == "P")
			      {
				      print ("['{$metaresult['ano']}'],[{$metaresult['meta']}],[{$_POST['total_meta']}],false ");
				      $cont++;
			      }
			      else if($objetometa->getPeriodo() == "M")
			      {
			      	  if($objetometa->getCumulativo() ==1)
			      	  {
			      	  	   $metaating = $metaating + $metaresult['meta_atingida'];}else{$metaating = $metaresult['meta_atingida'];
			      	  }
			      	  
					  print ("['{$metaresult['ano']}',{$metaresult['meta']},{$metaating}], ");
					  $cont++;
			      }
	              else
			      {
			      	  $meta1= $metaresult['meta']  ;
			      	  $metaatingida1=$metaresult['meta_atingida']  ;
			      	  
					  print ("['{$metaresult['ano']}',{$meta1},{$metaatingida1}], ");
			      } 
	          
	          }
	          ?>
       ]);

       var options = {
         chart: {
           title: 'Série Histórica do Desempenho do Indicador',
           
         }
       };

       var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

       chart.draw(data, google.charts.Bar.convertOptions(options));

       
     }
   </script>
 <div id="table_div"></div>
<br><br>
   <div id="columnchart_material" style="width: 800px; height: 400px;"></div>
   
  
   
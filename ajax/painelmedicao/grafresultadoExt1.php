<?php 
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../dao/unidadeDAO.php';
require_once '../../classes/sessao.php';
require_once '../../modulo/documentopdi/dao/DocumentoDAO.php';
require_once '../../modulo/indicadorpdi/dao/IndicadorDAO.php';
require_once '../../modulo/calendarioPdi/dao/CalendarioDAO.php';

require_once '../../dao/painelTaticoDAO.php';


$anogestao= substr($_POST['periodo'],0,4); 
$periodo= substr($_POST['periodo'],5,1); 
$daodoc=new DocumentoDAO();
$tudo="&ano=".$anogestao."&periodo=".$periodo."&doc=".$_POST['documento'];

if ($_POST['documento']==2){
	$codunidade=938;
}
$daocal=new CalendarioDAO(); 

$rows=$daocal->buscaCalendarioporAnoBaseOnly($anogestao);
foreach ($rows as $r){
	$codcal=$r['codCalendario'];
}
$linhas=$daodoc->buscadocumento($_POST['documento']);
foreach ($linhas as $l){
	$codunidade=$l['CodUnidade'];
}
$rows = $daodoc->painelSituacaoIndicadores(2,$codunidade,$codcal,$periodo,$anogestao);
$rows1 = $daodoc->painelSituacaoIniciativas(2,$codunidade,$periodo,$codcal,$anogestao);
$rows2 = $daodoc->painelFatoresIniciativas(2,$codunidade,$codcal,$periodo,$anogestao);
/*foreach ($rows as $r){ 
            echo $r['sit'].'<br>';
          }*/

$daoind=new IndicadorDAO();
//$rows3=$daoind->indicadoresPDI($anogestao);
$rows3=$daoind->indicadoresPDI1($anogestao,2);

$daounid= new UnidadeDAO();
$rows4= $daounid->queryByUnidadeResponsavel(1);

//Calcular o percentual de desempenho geral/////////////////////////////////////
//Definição da classe
$painelPDI = new PainelTaticoDAO();
//$rows = $painelTatico->exportarResultadoPainel($ano_base, $cod_unidade);

//NOVO CODIGO
if($codunidade != 938){
	//Banco de Dados e Query para obter o PDI
	$rows_p= $painelPDI->exportarResultadoPainelPDI($anogestao, $codunidade);
	// Comentado $daoserv = new ServprocDAO();
	$rows_pdi= $painelPDI->exportarResultadoPainel($anogestao, $codunidade);
}else {
	$rows_p = $painelPDI->exportarResultadoPainelPDI938($anogestao);
}


//Definindo variáveis auxiliares
$aux = 0;$ob_aux='';$aux2=1;
$ind_aux='';$aux3=1;
$color_meta=0;$color_meta2=0;$aux4=1;
$linha_aux=""; //Para construir a linha da meta do indicador a cada loop
$meta_aux = "";$ano_aux="";$aux_pGeral=0;$aux_qtdInd=0;$desem_geral=0;

include 'looprows.php';
if ($codunidade!=938){
	$rows_p=$rows_pdi;
	include 'looprows.php';
}

$desem_geral=round($aux_pGeral/$aux_qtdInd,2); //cálculo para o desempenho geral dos indicadores

?>
<style>
<!--
.card1 {
  /* Add shadows to create the "card" effect */
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
  transition: 0.3s;
  width: 460px;
}

.card2 {
  /* Add shadows to create the "card" effect */
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
  transition: 0.3s;
}

/* On mouse-over, add a deeper shadow */
.card:hover {
  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}
.card-header {
    padding: .75rem 1.25rem;
    margin-bottom: 0;
    background-color: #eceff1;;
    
}
-->
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">

</style>

 
<head>
 

	
<!-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> -->
<script type="text/javascript">  

google.charts.load('current', {'packages':['corechart', 'controls']});

google.charts.setOnLoadCallback(drawStuff);

function drawStuff() {

  var dashboard = new google.visualization.Dashboard(
    document.getElementById('programmatic_dashboard_div'));


// gráfico de pizza - desempenho geral de indicadores
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart2);
      google.charts.load('current', {'packages':['table']});
      google.charts.setOnLoadCallback(drawTable1);
      
      function drawChart() {

        

          var data = google.visualization.arrayToDataTable([
            ['Situação', 'Percentagem'],    
              
         <?php 
         	$cont=0;$string1='';
         	$criatabela=" 

      function drawTable1() {
         	
         	var datatab = new google.visualization.DataTable();
			datatab.addColumn('string', 'Desempenho');
			datatab.addColumn('number', 'Percentagem');
			datatab.addRows([";
 

         foreach ($rows as $r){ 
         	switch ($r['sit']){
         		case 1:	print("['Alto',". $r['qtsit']."],");$string1.=$cont.": {color: 'green'},";$criatabela.="['Alto',".$r['qtsit']."],";
         		break;
         		case 2:	print("['Médio',". $r['qtsit']."],");$string1.=$cont.": {color: 'yellow'},";$criatabela.="['Médio',".$r['qtsit']."],";
         		break;
         		case 3:	print("['Baixo',". $r['qtsit']."],");$string1.=$cont.": {color: 'red'},";$criatabela.="['Baixo',".$r['qtsit']."],";
         		break;
         		case 4:	print("['Sem meta',". $r['qtsit']."],");$string1.=$cont.": {color: 'gray'},";$criatabela.="['Sem meta',".$r['qtsit']."],";
         		break;
         		case 5:	print("['Não informado',". $r['qtsit']."]");$string1.=$cont.": {color: 'white'},";$criatabela.="['Não informado',".$r['qtsit']."],";
         		break;
         		
         		
         		
         	}
         	
         	$cont++;
         	  /* slices: {
            	<?php echo $string;?>
            	},*/
         	            		
            		 }
        print(']);');
        $criatabela.="]);
         var table = new google.visualization.Table(document.getElementById('table_div1'));
         table.draw(datatab, {showRowNumber: true, width: '100%', height: '100%'});
      }";
            		  ?>
        

        var options = {
          title: '', //Desempenho Geral dos Indicadores
       //   slices: {0: {color: 'green'}, 1: {color: 'yellow'},2: {color: 'red'}, 3: {color: 'gray'}, 4: {color: 'white'}},
       
        slices: {
        	<?php echo $string1;?>
        	},
       
          pieSliceTextStyle: {
              color: 'black',
            },
         

         
          //          slices: {1: {color: 'green'}, 2: {color: 'yellow'}, 3: {color: 'red'}, 4: {color: 'blue'}, 5: {color: 'white'}},
          
          
          
              is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        function selectHandler() {
            var selectedItem = chart.getSelection()[0];
            if (selectedItem) {
              var topping = data.getValue(selectedItem.row, 0);
           
              $.ajax({
  				method: "POST",
  				async:true,
  				url: "painelmedicaoExt.php",
  				data: "desempenho="+topping+'<?php print($tudo)?>',
  				success: function(response) {
  					$('#jplacehold').html(response);
  			        //$("#dialog").dialog({width: 700,height:500});

  				    $( "#dialog-confirm" ).dialog({
  				      resizable: false,
  				      height:500,
  				      width: 700,
  				      modal: true,
  				      title: "SisRAA - Indicadores - Desempenho",
  				      buttons: {
  				        
  				        Fechar: function() {
  				          $( this ).dialog( "close" );
  				        }
  				      }
  				    });
  				  $(".ui-dialog").find(".ui-widget-header").css("background", "#3299CC");
  				  $(".ui-dialog").find(".ui-dialog-titlebar").css("color", "white");
  				  $(".ui-dialog").find(".ui-dialog-buttonset .ui-button").css("color", "3299CC");
  				  
  				}
  			});

             

          
             
            }
          }

       
    	
          google.visualization.events.addListener(chart, 'select', selectHandler);  
        chart.draw(data, options);

  }

      

      <?php print($criatabela);?>
    
      
  	//gráfico de pizza - desempenho das iniciativas
      function drawChart2() {

        var data = google.visualization.arrayToDataTable([
          ['Situação', 'Percentagem'],
         <?php 
         $cont=0;$string='';
         foreach ($rows1 as $r){ 
         	switch ($r['sit']){
         		
         		case 1:	print("['Não iniciada',". $r['qtsit']."],");$string.=$cont.": {color: 'gray'},";
         		break;
         		case 2:	print("['Em atraso',". $r['qtsit']."],");$string.=$cont.": {color: 'red'},";
         		break;
         		case 3:	print("['Com atrasos críticos',". $r['qtsit']."],");$string.=$cont.": {color: 'yellow'},";
         		break;
         		case 4:	print("['Em andamento normal',". $r['qtsit']."],");$string.=$cont.": {color: 'blue'},";
         		break;
         		case 5:	print("['Concluída',". $r['qtsit']."]");$string.=$cont.": {color: 'green'},";
         		break;         		
         	}
         	$cont++;
         	
         	            		
            		 } ?>
        ]);

        var options = {
          title: '', //Situação Geral das Iniciativas/Ações

          //slices: {0: {color: 'green'}, 1: {color: 'yellow'},2: {color: 'red'}, 3: {color: 'gray'}, 4: {color: 'white'}},
          slices: {
        	<?php echo $string;?>
        	},
          
          pieSliceTextStyle: {
              color: 'black',
            },
              is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart2'));

        chart.draw(data, options);
      }




//Gráfico de colunas

      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart3);

      function drawChart3() {
        var data = google.visualization.arrayToDataTable([
          ['Situação', 'Capacitação', 'Recursos de TI', 'Infraestrutura Física','Recursos Financeiros','Planejamento'],
          <?php foreach ($rows2 as $r){ 
           	switch ($r['situacao']){
           		case 1: print ( "['Não iniciada',".$r['capacit'].",". $r['recti'].",". $r['infra'].",". $r['recfinanc'].",".$r['planeja']."],");
           		        break;
                case 2: print ( "['Em atraso',".$r['capacit'].",". $r['recti'].",". $r['infra'].",". $r['recfinanc'].",".$r['planeja']."],");
           		        break;
           		case 3: print ( "['Com atrasos críticos',".$r['capacit'].",". $r['recti'].",". $r['infra'].",". $r['recfinanc'].",".$r['planeja']."],");
           		        break;
           		case 4: print ( "['Em andamento normal',".$r['capacit'].",". $r['recti'].",". $r['infra'].",". $r['recfinanc'].",".$r['planeja']."],");
           		        break;
           		case 5: print ( "['Concluída',".$r['capacit'].",". $r['recti'].",". $r['infra'].",". $r['recfinanc'].",".$r['planeja']."],");
           		        break;            
           } 
          }?>
        ]);

        var options = {
          chart: {
            title: '',//Comportamento dos fatores que determinam a situação da iniciativa
           // subtitle: 'Sales, Expenses, and Profit: 2014-2017',
          },
           colors: ['green', 'gray', 'blue','yellow','red']
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
      
}


</script>
</head>
<table><tr><td>
<div class="card">
  <h5 class="card-header"><b>Percentual Geral de Desempenho dos Indicadores: <?php echo str_replace('.', ',', $desem_geral);?>%</b></h5>
  <div class="card-body">
    <div  id="piechart" style="width: 440px; height: 250px;float: left;padding: 5px 0px 5px 0px; "></div>
 
  </div>
</div>
 </td><td>
<div class="card">
  <h5 class="card-header"><b>Situação Geral das Iniciativas/Ações</b></h5>
  <div class="card-body">
   <div id="piechart2" style="width: 440px; height: 250px;float: left;padding: 5px 0px 5px 0px;"></div>
  </div>
</div>
</td></tr><tr><td colspan="2"><br/>
<div class="card2">
  <h5 class="card-header"><b>Comportamento dos fatores que determinam a situação da iniciativa</b></h5>
  <center><div class="card-body">
  <center> <div id="columnchart_material" style="width: 100%; height: 400px;float: left;padding: ;"></div></center>
  </div></center>
</div>

</td></tr>  </table>

<?php if ( $codunidade==938){?>

<?php } ?>
<script type="text/javascript">  


$("#ind12").change(function() {
	$("#result12").empty();
    $.ajax({
        url: "ajax/painelmedicao/grafresultadoExt2.php",
        type: "POST",
        data: $("form").serialize(),
        success: function(data) {
            //$("#result12").html(data);
        	$("#result12").html(data);
        },
    });
});

</script>

 
  
<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="http:/resources/demos/style.css">
  
   

<div id="dialog-confirm" title="SisRAA - Indicadores - Desempenho" >
        <div  id="jplacehold"></div>

</div>

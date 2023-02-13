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

session_start();

$sessao = $_SESSION["sessao"];
 
if (!isset($_SESSION["sessao"])) {
  echo "A sessão expirou...";
	exit(0);
}

$codunidade= $sessao->getCodunidade();
$anogestao= substr($_POST['periodo'],0,4); 

$periodo= substr($_POST['periodo'],5,1); 
$daodoc=new DocumentoDAO();

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
$rows3=$daoind->indicadoresPDI1($anogestao,$periodo);

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
if($aux_qtdInd==0) {
	echo '<div class="alert alert-warning" role="alert">
  			Não foram encontrados Inidicadores cadastrados!
		  </div>';
}else{
	$desem_geral=round($aux_pGeral/$aux_qtdInd,2); //cálculo para o desempenho geral dos indicadores
}
?>

<?php if($aux_qtdInd!=0) {?>
  <table align="center"style="width:100%">
    <tr>
      <td>
        <div class="card card-info">
          <h5 class="card-header"><b>Percentual Geral de Desempenho dos Indicadores: <?php echo str_replace('.', ',', $desem_geral);?>%</b></h5>
          <div class="card-body">
            <canvas id="piechart" style="float: left;padding: 0px 0px 0px 0px; "></canvas>
          </div>
        </div>
      </td>
      <td>
        <div class="card card-info">
          <h5 class="card-header"><b>Situação Geral das Iniciativas/Ações</b></h5>
          <div class="card-body">
            <canvas id="piechart" style="float: left;padding: 0px 0px 0px 0px;"></canvas>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td colspan="5"><br/>
        <div class="card card-info">
          <h5 class="card-header"><b>Comportamento dos fatores que determinam a situação da iniciativa</b></h5>
          <center><div class="card-body">
          <center> <div id="columnchart_material" style="width: 100%; height: 400px;float: left;padding: 0px 0px 5px 0px;"></div></center>
          </div></center>
        </div>
      </td>
    </tr>  
  </table>
  <?php if ($codunidade==953 || $codunidade==938){?>
  <br>
  <form>
    <?php //if ($codunidade==938){?>

    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Comparação entre os resultados dos indicadores do PDI e indicadores do PDU</h3><br>
      </div>

      <div class="card-body">
        <table>
          <tr>
            <td class="coluna1"><b>Indicadores</b></td>
          </tr>
          <tr>
            <td class="coluna2">
              <select id="ind12" name="sel"><option value="0">Selecione o indicador...</option>
                <?php foreach ($rows3 as $r){?>
                  <option value="<?php echo $r['codigo'];?>"><?php echo $r['nome']; ?></option>
                <?php } ?>
              </select>
              <input name="periodo" value="<?php echo $_POST['periodo'];?>" type="hidden"/>
            </td>
          </tr>
        </table>
      </div>
      <br>
      <table>	
        <tr>
          <td>
            <div class="card-body">
              <div id='result12'></div>
            </div>
          </td>
        </tr>
      </table>
    </div>
  </form>
  <br>
<?php } 
}
?>
<script type="text/javascript">  
$("#unid12").change(function() {
	$("#result12").empty();
    $.ajax({
        url: "ajax/painelmedicao/grafresultado2.php",
        type: "POST",
        data: $("form").serialize(),
        success: function(data) {
            $("#result12").html(data);
        },
    });
});

$("#ind12").change(function() {
	$("#result12").empty();
    $.ajax({
        url: "ajax/painelmedicao/grafresultado2.php",
        type: "POST",
        data: $("form").serialize(),
        success: function(data) {
            $("#result12").html(data);
        },
    });
});

</script>



<!-- Se fosse por unidade <table><tr><td>Unidades</td><td>

	<select id="unid12" name="sel"><option value="0">Selecione a unidade...</option>
	<?php //foreach ($rows4 as $r){?>
		<option value="<?php //echo $r['CodUnidade'];?>"><?php //echo $r['NomeUnidade']; ?></option>
	
	<?php //} ?>
	</select>
	</td></tr></table>-->
	
<?php //} ?>

<!-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> -->
<script type="text/javascript">  
  $(function (){
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    var donutData        = {
      labels: [
          'Chrome',
          'IE',
          'FireFox',
          'Safari',
          'Opera',
          'Navigator',
      ],
      datasets: [
        {
          data: [700,500,400,600,300,100],
          backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
        }
      ]
    }

    var donutOptions = {
      maintainAspectRatio : false,
      responsive : true,
    }

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData        = donutData;
    var pieOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    //new Chart(pieChartCanvas, {
    //  type: 'pie',
    //  data: pieData,
    //  options: pieOptions
    //})
  })


//google.charts.load('current', {'packages':['corechart', 'controls']});
//
//google.charts.setOnLoadCallback(drawStuff);
//
//function drawStuff() {
//
//  var dashboard = new google.visualization.Dashboard(
//    document.getElementById('programmatic_dashboard_div'));
//
//
//// gráfico de pizza - desempenho geral de indicadores
//      google.charts.load('current', {'packages':['corechart']});
//      google.charts.setOnLoadCallback(drawChart);
//      google.charts.load('current', {'packages':['corechart']});
//      google.charts.setOnLoadCallback(drawChart2);
//
//      function drawChart() {
//
//        var data = google.visualization.arrayToDataTable([
//          ['Situação', 'Percentagem'],
//
//         <?php 
//         	$cont=0;$string1='';
//         foreach ($rows as $r){ 
//         	switch ($r['sit']){
//         		case 1:	print("['Alto(>90%)',". $r['qtsit']."],");$string1.=$cont.": {color: 'green'},";
//         		break;
//         		case 2:	print("['Médio(60%-90%)',". $r['qtsit']."],");$string1.=$cont.": {color: 'yellow'},";
//         		break;
//         		case 3:	print("['Baixo(<60%)',". $r['qtsit']."],");$string1.=$cont.": {color: 'red'},";
//         		break;
//         		case 4:	print("['Sem meta',". $r['qtsit']."],");$string1.=$cont.": {color: 'gray'},";
//         		break;
//         		case 5:	print("['Não informado',". $r['qtsit']."]");$string1.=$cont.": {color: 'white'},";
//         		break;
//         		
//         	}
//         	
//         	$cont++;
//         	  /* slices: {
//            	<?php echo $string;?>
//            	},*/
//         	            		
//            		 } ?>
//        ]);
//
//        var options = {
//          title: '', //Desempenho Geral dos Indicadores
//       //   slices: {0: {color: 'green'}, 1: {color: 'yellow'},2: {color: 'red'}, 3: {color: 'gray'}, 4: {color: 'white'}},
//       
//        slices: {
//        	<?php //echo $string1;?>
//        	},
//       
//          pieSliceTextStyle: {
//              color: 'black',
//            },
//         
//
//         
//          //          slices: {1: {color: 'green'}, 2: {color: 'yellow'}, 3: {color: 'red'}, 4: {color: 'blue'}, 5: {color: 'white'}},
//          
//          
//          
//              is3D: true,
//        };
//
//        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
//
//        chart.draw(data, options);
//      }
//
//  	//gráfico de pizza - desempenho das iniciativas
//      function drawChart2() {
//
//        var data = google.visualization.arrayToDataTable([
//          ['Situação', 'Percentagem'],
//         <?php 
//         $cont=0;$string='';
//         foreach ($rows1 as $r){ 
//         	switch ($r['sit']){
//         		
//         		case 1:	print("['Não iniciada',". $r['qtsit']."],");$string.=$cont.": {color: 'gray'},";
//         		break;
//         		case 2:	print("['Em atraso',". $r['qtsit']."],");$string.=$cont.": {color: 'red'},";
//         		break;
//         		case 3:	print("['Com atrasos críticos',". $r['qtsit']."],");$string.=$cont.": {color: 'yellow'},";
//         		break;
//         		case 4:	print("['Em andamento normal',". $r['qtsit']."],");$string.=$cont.": {color: 'blue'},";
//         		break;
//         		case 5:	print("['Concluída',". $r['qtsit']."]");$string.=$cont.": {color: 'green'},";
//         		break;         		
//         	}
//         	$cont++;
//         	
//         	            		
//            		 } ?>
//        ]);
//
//        var options = {
//          title: '', //Situação Geral das Iniciativas/Ações
//
//          //slices: {0: {color: 'green'}, 1: {color: 'yellow'},2: {color: 'red'}, 3: {color: 'gray'}, 4: {color: 'white'}},
//          slices: {
//        	<?php// echo $string;?>
//        	},
//          
//          pieSliceTextStyle: {
//              color: 'black',
//            },
//              is3D: true,
//        };
//
//        var chart = new google.visualization.PieChart(document.getElementById('piechart2'));
//
//        chart.draw(data, options);
//      }
//
//
//
//
////Gráfico de colunas
//
//      google.charts.load('current', {'packages':['bar']});
//      google.charts.setOnLoadCallback(drawChart3);
//
//      function drawChart3() {
//        var data = google.visualization.arrayToDataTable([
//          ['Situação', 'Capacitação', 'Recursos de TI', 'Infraestrutura Física','Recursos Financeiros','Planejamento'],
//          <?php // foreach ($rows2 as $r){ 
//           	switch ($r['situacao']){
//           		case 1: print ( "['Não iniciada',".$r['capacit'].",". $r['recti'].",". $r['infra'].",". $r['recfinanc'].",".$r['planeja']."],");
//           		        break;
//                case 2: print ( "['Em atraso',".$r['capacit'].",". $r['recti'].",". $r['infra'].",". $r['recfinanc'].",".$r['planeja']."],");
//           		        break;
//           		case 3: print ( "['Com atrasos críticos',".$r['capacit'].",". $r['recti'].",". $r['infra'].",". $r['recfinanc'].",".$r['planeja']."],");
//           		        break;
//           		case 4: print ( "['Em andamento normal',".$r['capacit'].",". $r['recti'].",". $r['infra'].",". $r['recfinanc'].",".$r['planeja']."],");
//           		        break;
//           		case 5: print ( "['Concluída',".$r['capacit'].",". $r['recti'].",". $r['infra'].",". $r['recfinanc'].",".$r['planeja']."],");
//           		        break;            
//           } 
//          }?>
//        ]);
//
//        var options = {
//          chart: {
//            title: '',//Comportamento dos fatores que determinam a situação da iniciativa
//           // subtitle: 'Sales, Expenses, and Profit: 2014-2017',
//          },
//           colors: ['green', 'gray', 'blue','yellow','red']
//        };
//
//        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
//
//        chart.draw(data, google.charts.Bar.convertOptions(options));
//      }
//      
//}
//

</script>
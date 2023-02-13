<?php
class Contribuicao{
	private $Rindpdi;
	private $Rindpdu;
	private $Metapdi;
	private $indicador;
		private $unidade;
	
	
    public function getRindpdi(){
		return $this->rindpdi;
	}

	public function setRindpdi($rindpdi){
		$this->rindpdi= $rindpdi;
	}
    public function getRindpdu(){
		return $this->rindpdu;
	}

	public function setRindpdu($rindpdu){
		$this->rindpdu= $rindpdu;
	}
 public function getIndicador(){
		return $this->indicador;
	}

	public function setIndicador($indicador){
		$this->indicador= $indicador;
	}
	
public function getUnidade(){
		return $this->unidade;
	}

	public function setUnidade($unidade){
		$this->unidade= $unidade;
	}
}


require_once '../../dao/PDOConnectionFactory.php';
require_once '../../dao/unidadeDAO.php';
require_once '../../classes/sessao.php';
require_once '../../modulo/documentopdi/dao/DocumentoDAO.php';
require_once '../../modulo/indicadorpdi/dao/IndicadorDAO.php';
require_once '../../modulo/calendarioPdi/dao/CalendarioDAO.php';



	$codindicador=$_POST['sel'];

$anogestao=$_POST['ano'];;
$daoind=new IndicadorDAO();
$array_indpdu[]=array();
$array_indpdures[]=array();
$array_indpduuni[]=array();

$cont=0;
$periodo=substr($_POST['periodo'],5,1);
$rows1=$daoind->indicadoresPDU1($anogestao,$periodo,$codindicador);

foreach ($rows1 as $r1){
	    $cont++;
	    //echo "pdu ".$r1['codigo']."-".$r1['resultado']."-".$r1['indicador']."<br>";
	    	   
	   //echo "pdu1   ".$r1['codigo']."-".$r1['resultado']."-".$r1['nomeunidade']."<br>";
	    
		$array_indpdu[$cont]= $r1['codigo'];
	    $array_indpdures[$cont]= $r1['resultado'];
	    $resp=$r1['tipoassociado']==7?' (Unidade Responsável no PDI)':"";
	    $array_indpduuni[$cont]=$r1['nomeunidade'].$resp;
	    
}

$rows=$daoind->indicadoresPDI1($anogestao,2);

 
if ($cont==0){
	echo "A unidade não possui PDU!";
}else{
	$cont1=0;
	$contrib=array();
  foreach ($rows as $r){
	$indpdi=$r['codigo'];
	$resultpdi=$r['resultado'];
    for ($i=1;$i<=$cont;$i++){
	  if ($array_indpdu[$i]==$indpdi){
	  	   $cont1++;
	  	   $contrib[$cont1]=new Contribuicao();
		   $contrib[$cont1]->setRindpdi($resultpdi);
		   $contrib[$cont1]->setRindpdu($array_indpdures[$i]);
		//   $contrib[$cont1]->setIndicador($r['nome']);
		   $contrib[$cont1]->setUnidade($array_indpduuni[$i]);
		   		   
		   //echo "aqui".$contrib[$cont1]->getIndicador()."-". $contrib[$cont1]->getRindpdu()."-".$contrib[$cont1]->getRindpdi()."<br>";
		   	//echo "aqui".$contrib[$cont1]->getUnidade()."-". $contrib[$cont1]->getRindpdu()."-".$contrib[$cont1]->getRindpdi()."<br>";
		   
	  }
    }
}
//die;
?>
<head>
<script type="text/javascript">  

//Gráfico de colunas

      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart4);
      $legenda="";
      function drawChart4() {
        var data = google.visualization.arrayToDataTable([
          ['Unidade', 'resultadoPDI', 'resultadoPDU'],
          <?php 
          $cont2=0;
          foreach ($contrib as $r){ 
                 $cont2++;
           		 print ( "['".Unid.$cont2."',".$r->getRindpdi().",". $r->getRindpdu()."],");
           		 $legenda.="Unid".$cont2.": ".$r->getUnidade()."<br>";
          } ?>
        ]);

        var options = {
          chart: {
            //title: 'Comparação entre os resultados dos indicadores do PDI e indicadores do PDU',
           // subtitle: 'Sales, Expenses, and Profit: 2014-2017',
          },
          bars: 'horizontal',
          hAxis: {format: 'decimal'}, 
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material1'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
      


</script>
</head>


 <center><div id="columnchart_material1" style="width: 800px; height: 500px;float: left;padding: 5px 50px 50px 5px;"></div></center>
 
  
<div style="float: left;align:right;">
<?php 
echo "<b>Legenda:</b></br>".$legenda; ?>
<?php } ?>
</div>
<?php

$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();
$anobase=$sessao->getAnobase();
$u=new UnidadeDAO();
$rowsun=$u->buscaidunidadeRel($codunidade);
foreach ($rowsun as $r){
	$unidade=new Unidade();
	$unidade->setCodunidade($r['CodUnidade']);
	$unidade->setNomeunidade($r['NomeUnidade']);
	$sigla=$r['sigla'];
}
  //-----------------------------Verifica pdi 
$daodoc=new DocumentoDAO();
$rowsdoc=$daodoc->buscadocumentoporunidadePeriodoSemPDI($codunidade,$anobase);

$coddoc = '';

foreach ($rowsdoc as $r){
	$coddoc=$r['codigo'];
}
if ($codunidade!=938) {
	$rows = $daodoc->listaIndporDocCal1($anobase,$coddoc,$unidade->getCodunidade());//lista indicadores da unidade por doc e calendario
	}else{
	$rows = $daodoc->listaIndporDocCal2($anobase,$coddoc,$unidade->getCodunidade());//lista indicadores da unidade por doc e calendario	
	}
	$dados=array();
	$cont=0;
	
	foreach ($rows as $r){
		//echo $cont;
	        $subarray=array();
	        $subarray[1]=$r['periodo'];
	        $periodo=2;
	        $subarray[2]=$r['codobj'];
	        $subarray[3]=$r['Objetivo'];
	        $subarray[4]=$r['codmi'];
	        $subarray[5]=$r['nome'];
	        $subarray[6]=($sessao->getCodUnidade()==938)?$r['PropIndicador']:$r['metrica'];
	        $subarray[7]=$r['codmeta'];
	        $subarray[8]=$r['codmapa'];
	        $subarray[9]=$r['coddoc'];
	        $subarray[10]=$r['meta'];
	        $dados[$cont]=$subarray;
	        $cont++;      
	 }
	 
	 $daores = new ResultadoDAO();
	 	$completo=0;
	 
for ($j=0;$j<count($dados);$j++){
	$rows=NULL;
	if ($dados[$j][10]!=0.0){
	    $rows= $daores->buscaresultaperiodometa($dados[$j][7], $periodo);
	 	if ($rows->rowCount() == 0) {
	        $completo=0;
		 }else{
		 	$completo=1;
		 	break;
		 }
	}
}
//--------------------Verifica raa
$daoraa=new TextoDAO();
$rows=$daoraa->buscaFinalizacaoRel($codunidade, $anobase);
$finalizado=1;
if ($rows->rowCount() == 0) {
	$finalizado=0;
}
?>

<div class="bs-example">
	<ul class="breadcrumb">
		Exportar Análise do RAA
	</ul>
</div>

<style>
#teste { position:relative; }
#upload { position:absolute; top:0;left:0; border:1px solid #ff0000; opacity:0.01; z-index:1; }
</style>

<div class="card card-info" style="width:70%">
	<div class="card-header">
		<h3 class="card-title">Exportar Análise do RAA</h3>
	</div>
	<form class="form-horizontal" enctype="multipart/form-data" name="adicionar" id="adicionar" method="POST"  >
			
		<table class="card-body">
			<div id="msg"></div>
			<tr>                     
				<td><label>Exportar arquivo de análise do RAA</label></td>
				<td>
					<?php 
					$url="../public/avaliacao/ava".$sessao->getAnobase()."/RAA_".$sigla."_".$sessao->getAnobase().".zip";
					if ($finalizado==1){
						if (file_exists($url)){
						?>
					
						<a href="<?php echo $url; ?>"
											target="_self"><img  src="webroot/img/download-2.png"  alt="Download" width="25" height="25" /> </a>
											
					<?php }else{
						echo "Análise do RAA ainda não foi concluída.";
					}
					
					}
					
					else {
						echo "Análise do RAA indisponível, porque a unidade não inseriu a parte textual do RAA.";
					
					}?>
				</td>
			</tr>
			<tr>                     
				<td><label>Exportar arquivo de análise do painel de indicadores do PDU</label></td>
				<td>
					<?php 
					$url="../public/avaliacao/ava".$sessao->getAnobase()."/PDU_".$sigla."_".$sessao->getAnobase().".zip";
					if  ($completo==1){
						if (file_exists($url)){?>
						<a href="<?php  echo $url; ?>"
											target="_self"><img  src="webroot/img/download-2.png"  alt="Download" width="25" height="25" /> </a> 
					<?php  }else {
					
						echo "Análise do painel de indicadores ainda não foi concluída.";
					
					}
					}else{
						echo "Análise  do painel de indicadores do PDU indisponível, pois a unidade não lançou resultados.";
					}   
					?>
				</td>
			</tr>
		</table>
	</form>
</div>
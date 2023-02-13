<?php 
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

$sessao = $_SESSION['sessao'];

$anobase=$sessao->getAnobase();
$codUnidade = $sessao->getCodunidade();
$aplicacoes = $sessao->getAplicacoes(); // código das aplicações
$grupo = $sessao->getGrupo();
$c=new Controlador();
if ($c->identificarAnalistas($sessao->getCodusuario())) {
	$grupoAnalista = TRUE;
}else {
    $grupoAnalista = FALSE;
}
$c=new Controlador();

$daoSol = new SolicitacaoVersaoIndicadorDAO();

$daoMapaInd = new MapaIndicadorDAO();

$daoMeta=new MetaDAO();

$daoobjetivo=new ObjetivoDAO();

$daoind = new IndicadorDAO();

$obje="";

if ($c->identificarAnalistas($sessao->getCodusuario())){	
	if ($sessao->getCodusuario()==228) {
		$rowSol= $daoSol->buscaTodasSolicitacoes($anobase);//pega todas as solicitacoes
	}else{
		$rowSol= $daoSol->buscaSolicitacoesUser($anobase,$sessao->getCodusuario());//pega todas as solicitacoes delegadas para o usuario
	}
		

}else{
    $rowSol= $daoSol->buscaTodasSolicitacoesUnidade($codUnidade, $anobase);//pega todas as solicitacoes
}

//print_r($c->identificarAnalistas($sessao->getCodusuario()));

$mi = new Mapaindicador();

$count=0;
if ($rowSol->rowCount()>=1) {
	$sol=array();
	foreach ($rowSol as $row){		
		$uni = new  Unidade();
		$uni->setCodunidade($row['codunidade']);
		
		$ind = new  Indicador();
		$mapaInd = new Mapaindicador();
		$objetivo= new Objetivo();
		$docobj=new Documento();
		$metaobj=new Meta();
		$mapaobj=new Mapa();
		
		if ($row['codmapaind']!=NULL){
			$mapaInd->setCodigo($row['codmapaind']);
		}
		if ($row['codindicador']!=NULL){
			$ind->setCodigo($row['codindicador']);
		}
	    if ($row['codobjetivo']!=NULL){
			$objetivo->setCodigo($row['codobjetivo']);
		}
	    if ($row['coddocumento']!=NULL){
			$docobj->setCodigo($row['coddocumento']);
		}
	    if ($row['codmapa']!=NULL){
			$mapaobj->setCodigo($row['codmapa']);
		}
	    if ($row['codmeta']!=NULL){
			$metaobj->setCodigo($row['codmeta']);
		}
		
		$usuario=new Usuario();
		$usuario->setCodusuario( $row['codusuarioanalise']);
		
		$sol[$count]=$uni->criaSolicitacao($row['codigo'], $row['justificativa'], $row['anexo'],
		$row['situacao'], $row['datasolicitacao'], $row['dataEmanalise'],$usuario,
		$row['anogestao'], $row['tipoSolicitacao'],$mapaInd,$ind,$objetivo,$docobj,$metaobj,$row['novameta'],$mapaobj);
		$count++;
	}	
}

//print_r($sol);
?>
<div class="bs-example">
	<ul class="breadcrumb">
		<li class="active"><a href="<?php echo Utils::createLink('mapaestrategico', 'listamapapdu'); ?>">
		Painel <?php print $sessao->getCodunidade()==938?"Estratégico":"Tático"; ?></a> 
		<i class="fas fa-long-arrow-alt-right"></i>
		<a href="#" >Quadro de Solicitações</a></li>  
	</ul>
</div>

<div class="card card-info">
	<div id="subcaixa">
		<div class="card-header">
			<h3 class="card-title">Solicitações</h3>
		</div>
		
		<div class="card-body">
			<table class="table table-bordered table-hover" >
				<thead>
					<tr>
						<?php 
						if ($c->identificarAnalistas($sessao->getCodusuario())){
							echo  "<th>Unidade</th>";
						}
						?>
						<th>Elemento do Painel</th>
						<th>Tipo de Alteração</th>
						<th>Data</th>
						<th>Situação</th>
						<th>Visualizar</th>		
					</tr>
				</thead>

				<tbody>	
					<?php 
				//	foreach($mi->getarraysolicitacaoversaoindicador() as $item) {
					for ($i=0;$i<$count;$i++){    
							$class="";  

						//print_r($value) ;				    
						$elemento = "";				    
					
						if ($sol[$i]->getTipo()==1){
							$tipo = "Substituir Indicador";
							$dadosSol = "#dadosSolSubs";
							$rowMapaInd = $daoMapaInd->buscaMapaIndicador($sol[$i]->getMapaIndicador()->getCodigo());
						
							foreach ($rowMapaInd as $row){
								$elemento = $row['nome'];
							}
						}elseif ($sol[$i]->getTipo()==2){
							$tipo = "Editar Indicador";
							$dadosSol = "#dadosSolEdit";
							$rowMapaInd = $daoMapaInd->buscaMapaIndicador($sol[$i]->getMapaIndicador()->getCodigo());
						
							foreach ($rowMapaInd as $row){
								$elemento = $row['nome'];
							}
						}elseif ($sol[$i]->getTipo()==3){
							$tipo = "Incluir Objetivo";
							$dadosSol = "#incluirObjetivo";
							$rows=$daoobjetivo->buscaobjetivo($sol[$i]->getObjetivo()->getCodigo());
							
							foreach ($rows as $r){
								$elemento=$r['Objetivo'];
							}
							
						}elseif ($sol[$i]->getTipo()==4){
							$tipo = "Repactuar Meta";
							$dadosSol = "#repactuarMeta";
							$rows=$daoMeta->buscarindicadorpormeta($sol[$i]->getMeta()->getCodigo());
							foreach ($rows as $r){
								$elemento=$r['nome'];
							}
						}elseif ($sol[$i]->getTipo()==5){
							$tipo = "Excluir Objetivo";
							$dadosSol = "#excluirObjetivo";
							$rows=$daoobjetivo->buscaobjetivo($sol[$i]->getObjetivo()->getCodigo());
							
							foreach ($rows as $r){
								$elemento=$r['Objetivo'];
							}
							
						}elseif ($sol[$i]->getTipo()==6){
							$tipo = "Incluir Indicador";
							$dadosSol = "#dadosSolIncluir";
							$rowInd = $daoind->buscaindicador($sol[$i]->getIndicador()->getCodigo());
													
							foreach ($rowInd as $row){
								$elemento = $row['nome'];
							}
						}elseif ($sol[$i]->getTipo()==7){
							$tipo = "Excluir Indicador";
							$dadosSol = "#dadosSolExcluir";
							$rowMapaInd = $daoMapaInd->buscaMapaIndicador($sol[$i]->getMapaIndicador()->getCodigo());
							
							foreach ($rowMapaInd as $row){
								$elemento = $row['nome'];
							}							
						}
						
						if ($sol[$i]->getSituacao()=="A") {
							$situacao="Aberta";
							$class = "style='background-color:#A9E2F3'";
						}elseif ($sol[$i]->getSituacao()=="G") {
							$situacao="Delegada";
							$class = "style='background-color:#F0E68C'";
						}elseif ($sol[$i]->getSituacao()=="D") {
							$situacao="Deferida";
							$class = "style='background-color:#A9F5A9'";
						}elseif ($sol[$i]->getSituacao()=="I") {
							$situacao="Indeferida";
							$class = "style='background-color:#F6CECE'";
						}elseif ($sol[$i]->getSituacao()=="C") {
							$situacao="Cancelada";
							$class = "style='background-color:#E6E6FA'";
						}
						
						
						echo "<tr $class >";

						if ($c->identificarAnalistas($sessao->getCodusuario())) {
							$daoUni = new UnidadeDAO();					
							$rowUni = $daoUni->buscaidunidadeRel($sol[$i]->getUnidade()->getCodunidade());
							foreach ($rowUni as $rowUni2){
								$nomeUni = $rowUni2['NomeUnidade'];
							}
							echo  "<td>$nomeUni</td>";
						}
						
						echo "<td> $elemento </td>
							<td> $tipo </td>
							<td>".date("d/m/Y",strtotime($sol[$i]->getDatasolicitacao()))."</td>
							<td> $situacao </td>
							<td><img src='webroot/img/busca.png' onClick='buscarDadosSolicitacao(".$sol[$i]->getCodigo().",".$sol[$i]->getTipo().")' data-toggle='modal' data-target=' $dadosSol '></td>
							</tr>";				    
					}	
					?>              
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php 

include "modaisEdicao.php";

include "modulo/mapaestrategico/modaismapaedicao.php";

?>	

    
    
    
    

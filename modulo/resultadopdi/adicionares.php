<?php
/* models */
require_once MODULO_DIR . 'documentopdi/classe/Documento.php';
require_once MODULO_DIR . 'mapaestrategico/classes/Mapa.php';
require_once MODULO_DIR . 'mapaestrategico/classes/Objetivo.php';
require_once MODULO_DIR . 'documentopdi/classe/Perspectiva.php';
require_once MODULO_DIR . 'indicadorpdi/classe/Indicador.php';
require_once MODULO_DIR . 'metapdi/classe/Meta.php';
require_once MODULO_DIR . 'resultadopdi/classes/Resultado.php';
require_once MODULO_DIR . 'resultadopdi/classes/ResultIniciativa.php';

require_once MODULO_DIR . 'indicadorpdi/classe/Mapaindicador.php';
/* DAO */
require_once MODULO_DIR . 'documentopdi/dao/DocumentoDAO.php';
require_once MODULO_DIR . 'mapaestrategico/dao/MapaDAO.php';
require_once MODULO_DIR . 'indicadorpdi/dao/MapaIndicadorDAO.php';
require_once MODULO_DIR . 'indicadorpdi/dao/IndicadorDAO.php';
require_once MODULO_DIR . 'metapdi/dao/MetaDAO.php';
require_once MODULO_DIR . 'resultadopdi/dao/ResultadoDAO.php';
require_once MODULO_DIR . 'resultadopdi/dao/ResultIniciativaDAO.php';

/* fim */
?>
<?php
// DAO

$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
$anogestao=$sessao->getAnobase();
if (!isset($sessao)) {
	exit();
}

if (!$aplicacoes[29]) {
	print ("O usuário não possui permissão para acessar esta aplicação.");
	exit();
}
//$anobase = $sessao->getAnobase();
$c=new Controlador();
if ($c->getProfile($sessao->getGrupo())) {//se grupo for 18

}else{
	$codunidade=$sessao->getCodUnidade();
}


?>
<?php
$coddocumento = $_GET["documento"]; // código do documento
$codmapa = $_GET["objetivo"]; // código do mapa
//echo "mapa".$codmapa;
//die();
$codmapind = $_GET["indicador"]; // código do mapaindicador
$codmeta = $_GET["meta"]; // código da meta atingida
$periodo = $_GET["periodo"]; // período
$mperiodo=$_GET["mperiodo"];
//echo $periodo." e ".$mperiodo;
if (!is_numeric($coddocumento) && !is_numeric($codmapind)){
	echo ("Os campos devem ser numéricos!");
	exit();
}
$p = array(
                'A' => array('Ano de ' . $anobase),
                'M' => array('janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho',
                    'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'),
                'T' => array('1º trimestre', '2º trimestre', '3º trimestre', '4º trimestre'),
                'S' => array('1º semestre', '2º semestre'),
                'P'=> array('Parcial', 'Final')
);
$vetor = $p[$periodo];

$daodoc = new DocumentoDAO();
$rows = $daodoc->buscadocumento($coddocumento);

foreach ($rows as $row) {
	$unidadeDoPlano=new Unidade();
	$unidadeDoPlano->setCodunidade($row['CodUnidade']);
	$unidadeDoPlano->criaDocumento($row['codigo'],$row['nome'],$row['anoinicial'],$row['anofinal'],$row['situacao'],$row['missao'],$row['visao'],null,$row['tipo']);
	$objdoc = $unidadeDoPlano->getDocumento();
}

$daocal=new CalendarioDAO();
$rows=$daocal->verificaPrazoCalendarioDoDocumento($anobase);
$habilita=false;
foreach ($rows as $row) {
	$habilita=$row['habilita'];
	$objcal=new Calendario();
	$objcal->setCodigo($row['codCalendario']);
	$objcal->setUnidade(NULL);
	$objcal->setDocumento(NULL);
	$objcal->setDatainianalise(NULL);
	$objcal->setDatafimanalise(NULL);
	$objcal->setAnogestao($row['anoGestao']);
}

$cont=0;
$daomapa = new MapaDAO();
$rows = $daomapa->buscamapa($codmapa);
foreach ($rows as $row) {
	if ($row['Codigo'] == $codmapa) {
		//        buscar persctiva nome e objetivo nome
		$cont++;
		$objpers=new Perspectiva();
		$daop=new PerspectivaDAO();
		$rows=$daop->buscaperspectiva($row['codPerspectiva']);
		foreach ($rows as $row1) {
			$objpers->setCodigo($row1['codPerspectiva']);
			$objpers->setNome($row1['nome']);
		}
		$objobji=new Objetivo();
		$daobji=new ObjetivoDAO();
		$rows=$daobji->buscaobjetivo($row['codObjetivoPDI']);
		foreach ($rows as $row1) {
			$objobji->setCodigo($row1['Codigo']);
			$objobji->setObjetivo($row1['Objetivo']);
		}
		$objdoc->criaMapa($row['Codigo'],  $objpers, $objobji,  $unidadeDoPlano,$row['anoinicio'],$row['periodoinicial']);
		$objmapa=$objdoc->getMapa();
	}
}

//busca mapaindicador e indicador
$daomapind = new MapaIndicadorDAO();
$rows = $daomapind->buscaMapaIndicador($codmapind);//BUSCA UMA ÚNICA LINHA
$cont1 = 0;
foreach ($rows as $row) {
	// echo $objmapa->getCodigo() ."==". $row['codMapa'];
	// echo "uni".$codunidade." prop ".$row['PropIndicador'];
	//  echo "- aqui-". $objmapa->getCodigo()."=".$row['codMapa']."--".$row['PropIndicador']." ". $codunidade."=".$row['nome']."</br>";
	$cont1++;
	$objetoind =new Indicador();
	$objetoind ->setCodigo($row['codIndicador']);
	$objetoind ->setNome($row['nome']);
	$objetoind ->setCalculo($row['calculo']);
	$objetoind->setInterpretacao($row['interpretacao']);
	$objetoind->setUnidademedida($row['unidadeMedida']);//metrica
	$objmapa->criaMapaIndicador($row['codigo'],$objetoind,$codunidade ) ;
	$objmapind=$objmapa->getMapaindicador();
	//}//for
}//foreac

//Verificar valor da interpretação
$interpretacao="";
if($objetoind->getInterpretacao() == 1){
	$interpretacao = "Quanto maior,melhor.";
}else if($objetoind->getInterpretacao() == 2){
	$interpretacao = "Quanto menor, melhor.";
}


// Buscar a meta associada com o indicador
//echo $objmapind->getCodigo().",".$objcal->getCodigo();
$daometa = new MetaDAO();
$objmeta=NULL;
$rows = $daometa->buscarmetaindicador($objmapind->getCodigo(),$objcal->getCodigo());
foreach ($rows as $row) {

    //echo "tttrtffffmeta".$row["meta"]." ".$row["periodo"]."Anual ".$row["Codigo"].$objmapind->getIndicador()->getUnidademedida().'</br>';
    //echo $objmapind->getMeta()->getMetrica()."dddd";
    
    if ($anobase<2022 || $objdoc->getAnofinal()==2022) {

       $objmapind->criaMeta($row["Codigo"],$row["periodo"],$objcal->getCodigo(),$row["meta"],$row['ano'],
                $row["metrica"],$row["cumulativo"],$row['anoinicial'],$row['periodoinicial']);
    }else {

        $objmapind->criaMeta($row["Codigo"],$row["periodo"],$objcal->getCodigo(),$row["meta"],$row['ano'],
                $objmapind->getIndicador()->getUnidademedida(),$row["cumulativo"],$row['anoinicial'],$row['periodoinicial']);
    }
  

	$objmeta=$objmapind->getMeta();
}
if ($objmeta->getMeta() == NULL) {
	?>
<div class="ui-widget">
	<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">
		<p>
			<strong>Indicador: </strong>
			<?php echo ($objmapind->getIndicador()->getNome()); ?>
		</p>

		<p>
			<span class="ui-icon ui-icon-alert"
				style="float: left; margin-right: .3em;"></span> <strong>Importante:</strong>
			O indicador não possui meta, portanto NÃO é possível registrar
			resultados no ano base.
		</p>
		<p>
			<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Voltar</a>
		</p>
	</div>
</div>
			<?php
} else {
	// Busca o resultado associado

	// Busca o resultado associado
	$daores = new ResultadoDAO();
	$rows = $daores->buscaresultaperiodometa($codmeta, $mperiodo);
	$objresultado=NULL;
	foreach ($rows as $row) {
		$objmeta->criaResultado($row['Codigo'],$row['meta_atingida'],$row['periodo'],$row['analiseCritica'],$row['acaoPdi']);
		$objresultado = $objmeta->getResultado();
	}
	
	//-------------------------------  Buscar Iniciativa -----------------------------------------------------------
	$daoini=new IndicIniciativaDAO();
//	echo "Cod mapa indic ".$codmapind;
	$rows=$daoini->iniciativaPorMapIndicador($codmapind,$anogestao,$mperiodo);
	$cont=0;
	$objini=array();
	
	foreach  ($rows as $row){
		$objini[$cont]=new Iniciativa();
		$objini[$cont]->setCodiniciativa($row['codIniciativa']);
		$objini[$cont]->setNome($row['nome']);
		$objini[$cont]->setAnoinicio($row['anoInicio']);
		$objini[$cont]->criaIndicIniciativa($row['codindinic'],$objmapind);
		//-------------
				$objiniresult=NULL;
				//busca se há algum resultado de iniciativa
				$daorini=new ResultIniciativaDAO();
				$rows=$daorini->iniciativaPorResultado($objini[$cont]->getCodiniciativa(), $sessao->getAnobase(),$mperiodo);
				
				if ($rows->rowcount()>0){
					
					$rows=$daorini->iniciativaPorResultado1($objini[$cont]->getCodiniciativa(),$anogestao,$mperiodo);
					
					//$indice=0;
					foreach ($rows as $row){
						
						$objiniresult=new ResultIniciativa();
						$objiniresult->setCodigo($row['codResultIniciativa']) ;
						$objiniresult->setCalendario($objcal->getCodigo()==$row['codCalendario']?$objcal:NULL);
						
						$objiniresult->setIniciativa($objini[$cont]->getCodIniciativa()==$row['codIniciativa']?$objini[$cont]:NULL);
						$objiniresult->setSituacao($row['situacao']);					
						$objiniresult->setPfcapacit($row['pfcapacit']);
						$objiniresult->setPfrecti($row['pfrecti']);
						$objiniresult->setPfinfraf($row['pfinfraf']);						
						$objiniresult->setPfrecf($row['pfrecf']);
						$objiniresult->setPfplanj($row['pfplanj']);
					//	$objiniresult->setPfOutros($row['pfoutros']==NULL?"":$row['pfoutros']);//numero
						$objiniresult->setOutros($row['outros']);
						//  echo "<br>row".$objiniresult->getOutros();
						$objiniresult->setPeriodo($row['periodo']);		
																																			
						$objini[$cont]->setResultiniciativa($objiniresult);
						
						
					}
				}
		//----
		$cont++;
	  
	}
//------------------------------------------------------------------------------------------------------------
	?>

<head>
<div class="bs-example">
	<ul class="breadcrumb">
		<li class="active"><a
			href="<?php echo Utils::createLink('resultadopdi', 'consultaresult'); ?>">Lançar
				Resultados</a> >> <a href="#">Adicionar Resultados</a></li>
	</ul>
</div>
</head>

<span id='topo'></span>
<!-- perminte pagina voltar ao topo -->

	<?php if ($objresultado == NULL) { ?>
<fieldset>
	<div id="pdi-resumo">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<div>
			<strong>Documento:</strong>
			<?php print $objdoc->getNome(); ?>
		</div>
		<div>
			<strong>Objetivo Estratégico:</strong>
			<?php print $objdoc->getMapa()->getObjetivoPDI()->getObjetivo(); ?>
		</div>
		<div>
			<strong>Indicador:</strong>
			<?php print ($objmapind->getIndicador()->getNome()); ?>
		</div>

		<div>
			<strong>Meta:</strong>
			<?php print (str_replace('.', ',', $objmeta->getMeta())); ?>
		</div>
		<div>
			<strong>Métrica:</strong>
			
			<?php 
			
			
			//$metrica= $objmapind->getIndicador()->getUnidademedida();
			$metrica = $objmeta->getMetrica();
			
			if ($metrica == "Q") {
			               $metrica1="Absoluto";
			}else if ($metrica == "P"){
			               $metrica1= "Percentual"; 
			}else if ($metrica == "M"){
			    $metrica1= "Metro quadrado";
			}else if ($metrica == "R"){
			    $metrica1= "Real";
			}
			
			

			print $metrica1;
			?>
		</div>
		<div>
			<strong>Forma de Cálculo:</strong>
			<?php print ($objmapind->getIndicador()->getCalculo()); ?>
		</div>
		<div>
			<strong>Interpretação:</strong>
			<?php print ($interpretacao); ?>
		</div>
		<!--  <div>
                    <strong>Registro cumulativo da meta atingida:</strong>
<?php //echo $objmeta->getCumulativo()==1?"Sim":"Não"; ?>
                </div>-->

	</div>
	<div>
		<legend>
		
			Cadastrar Resultados -
			<?php
if ($mperiodo==0){
	print "Período não identificado! Solicite liberação do período para lançamento.";
}else {
	print $vetor[$mperiodo-1];
}       	
			?>
		</legend>
		<div id="resposta-ajax"></div>
		<form class="form-horizontal" name="resultado-atualiza" method="POST"
			action="ajax/resultadopdi/persisteresult.php" id="resultado-atualiza">

			<input class="form-control"type="hidden" name="coddoc"
				value="<?php print $objdoc->getCodigo(); ?>" /> <input class="form-control"type="hidden"
				name="codindicador" value="<?php print $objmapind->getCodigo(); ?>" />
			<input class="form-control"type="hidden" name="periodoini"
				value="<?php print $periodo; ?>" /> <input class="form-control"type="hidden"
				name="codcal" value="<?php print $objcal->getCodigo(); ?>" /> <input
				type="hidden" name="codmeta" value="<?php print   $codmeta; ?>" /> <input
				type="hidden" name="coddoc"
				value="<?php print $objdoc->getCodigo(); ?>" />  <input
				type="hidden" name="codindicador"
				value="<?php print $objmapind->getCodigo(); ?>" /> <input
				type="hidden" name="periodo" value="<?php print $periodo; ?>" /> <input
				type="hidden" name="mperiodo" value="<?php print $mperiodo; ?>" /> <input
				type="hidden" name="codcal"
				value="<?php print $objcal->getCodigo(); ?>" /> <input class="form-control"type="hidden"
				name="meta" value="<?php print $objmeta->getMeta(); ?>" /> <input
				type="hidden" name="cumulativo"
				value="<?php print $objmeta->getCumulativo(); ?>" /> <input
				type="hidden" name="metrica"
				value="<?php print   $objmeta->getMetrica(); ?>" /> <input
				type="hidden" name="codmapa"
				value="<?php print   $objmapa->getcodigo(); ?>" />
				<input class="form-control"type="hidden" name="interpretacao" value="<?php print   $objetoind->getInterpretacao();?>" />
			<table>
				<tr>
					<td>Resultado</td>
					<td><input class="form-control"type="text" id="infSemaforo" data-mask="000000,00" data-mask-reverse="true" name="resultado" />
						<div id="resposta-semaforo"></div>
					</td>
				
				
				<tr>
					<td>Análise Crítica</td>
					<td>
					 <div>
							<p style="padding-left: 50px;">Analisar criticamente o
								desempenho, considerando:</p>
							<p>
							
							
							<ul style="padding-left: 60px;">
								<li>A consistência dos resultados apurados (compatibilidade com
									a realidade);</li>
								<li>O impacto (ou a relevância) das iniciativas (realizadas ou
									não realizadas) no desempenho;</li>
								<li>Causas/dificuldades para o alcance das metas estabelecidas;</li>
								<li>Perspectivas de alcance das metas, quando for o caso;</li>
								<li>Ações executadas em decorrência das análises críticas
									anteriores;</li>
								<li>Experiências exitosas e/ou inovadoras.</li>
							</ul>
							</p>
						</div>  
					<textarea class="area" name="critica" cols="100" rows="15"></textarea>
					</td>
				</tr>

				<?php if ($objdoc->getTipo()==1) {
					/* Só a partir do documento 2 que as ações começam a ser registradas
					 Tipo 1 - PDI, Tipo 2 - PDI*/

					?>
			<!-- 	<tr>
					<td>Ações relativas ao indicador</td>
					<td><textarea class="carea" name="acaopdi" cols="100" rows="15"></textarea>
					</td>
				</tr> -->

<?php } ?>
			</table>

			<!-- Iniciativa                -->
			
			<!--  <div id="resposta-ajax"></div>-->
			<br>
			<div>
				<legend>Cadastrar resultados das iniciativas vinculadas ao indicador</legend>
			</div>
			<?php
			if (count($objini)==0){?>
				 <div class="erro">
        <img src="webroot/img/error.png" width="30" height="30"/>Pendência: É necessário vincular uma iniciativa a este indicador! Só após este procedimento, poderá ser registrado o resultado deste indicador!
         </div>
		      <?php } ?>	 
			
			<div class="panel-group" id="accordion" role="tablist"
				aria-multiselectable="true">
				<?php for ($cont=0;$cont<count($objini);$cont++){ ?>
				
				<?php 	if ($objini[$cont]->getResultiniciativa() == NULL) {?>
				
				<div>
					<input class="form-control"type="hidden" name="codindini<?php echo $cont;?>"
						value="<?php print $objini[$cont]->getIndicIniciativa()->getCodigo(); ?>" />
					<input class="form-control"type="hidden" name="codini<?php echo $cont;?>"
						value="<?php print   $objini[$cont]->getCodiniciativa(); ?>" />
				</div>
				<div id="<?php echo "chkb".$cont;?>">
					<!--  <div id='respiniciativa'>
                
                </div> -->

					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingTwo">
							<h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse"
									data-parent="#accordion" href="<?php echo "#spand".$cont;?>"
									aria-expanded="false" aria-controls="collapseTwo"> <strong>Iniciativa
										- </strong> <?php print($objini[$cont]->getNome());?> </a>
							</h4>
						</div>
						<div id="<?php echo "spand".$cont;?>"
							class="panel-collapse collapse" role="tabpanel"
							aria-labelledby="headingTwo">
							<div class="panel-body">


								<table>
								<!--  	<tr>
										<td><strong>Finalidade da iniciativa:</strong></td>
										<td><?php //echo $objini[$cont]->getFinalidade(); ?></td>
									</tr>-->




									<tr>
										<td><strong>Situação</strong></td>
										<td><select class="custom-select" name="sit<?php echo $cont;?>">
												<option value="0">Selecione a situação...</option>
												<option value="1">Não iniciado</option>
												<option value="2">Em atraso</option>
												<option value="3">Com atrasos críticos</option>
												<option value="4">Em andamento normal</option>
												<option value="5">Concluído</option>
										</select>
										</td>
									
									
									<tr>
										<td><strong>Que fatores abaixo contribuíram para a situação atual da inciativa?</strong></td>
										<td></td>
									</tr>
									<tr>
										<td>Capacitação</td>
										<td><input class="form-check-input" type="checkbox"
											name="capacitacao<?php echo $cont;?>" value="1"><br>
										</td>
									</tr>
									<tr>
										<td>Recursos de Tecnologia da Informação</td>
										<td><input class="form-check-input" type="checkbox" name="recti<?php echo $cont;?>"
											value="1"><br></td>
									</tr>
									<tr>
										<td>Infraestrutura Física</td>
										<td><input class="form-check-input" type="checkbox" name="infraf<?php echo $cont;?>"
											value="1"><br></td>
									</tr>
									<tr>
										<td>Recursos financeiros</td>
										<td><input class="form-check-input" type="checkbox" name="recf<?php echo $cont;?>"
											value="1"><br></td>
									</tr>
									<tr>
										<td>Planejamento</td>
										<td><input class="form-check-input" type="checkbox" name="plan<?php echo $cont;?>"
											value="1"><br></td>
									</tr>
									<tr>
										<td>Outros</td>
										<td><input class="form-control"name="outro<?php echo $cont;?>" type="text"
											maxlength=50 value=""><br>
									
									</tr>

								</table>

							</div>
						</div>
					</div>



				</div>
<?php }else{ ?>
            
    <div>
        <input class="form-control"type="hidden" name="codindini<?php echo $cont;?>" value="<?php print $objini[$cont]->getIndicIniciativa()->getCodigo(); ?>" />
        <input class="form-control"type="hidden" name="codini<?php echo $cont;?>" value="<?php print   $objini[$cont]->getCodiniciativa(); ?>" /> 
        <input class="form-control"type="hidden" name="codini<?php echo $cont;?>" value="<?php print   $objini[$cont]->getCodiniciativa(); ?>" /> 
        
         
    </div>
  
  
	<div class="panel panel-default">
		<div class="panel-heading" role="tab" id="headingTwo">
			<h4 class="panel-title">
				<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="<?php echo "#spand".$cont;?>" aria-expanded="false" aria-controls="collapseTwo">
	   				<strong>Iniciativa - </strong><?php print($objini[$cont]->getNome());?>
	  			</a>
	   		</h4>
	   	</div>
   			<div id="<?php echo "spand".$cont;?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
   				<div class="panel-body">
  			
                <table>
                    <tr>
                        <td><strong>Finalidade da iniciativa:</strong></td>
                        <td> <?php echo $objini[$cont]->getFinalidade(); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Situação</strong></td>
                        <td>
                            <select class="custom-select" name="sit<?php echo $cont;?>">                 
                                   <option selected value="0">Selecione a situação...</option>
                                    <option <?php echo $objini[$cont]->getResultiniciativa()->getSituacao()==1?"selected":""; ?> value="1">Não iniciado</option>
                                    <option <?php echo $objini[$cont]->getResultiniciativa()->getSituacao()==2?"selected":""; ?> value="2">Em atraso</option>
                                    <option <?php echo $objini[$cont]->getResultiniciativa()->getSituacao()==3?"selected":""; ?> value="3">Com atrasos críticos</option>
                                    <option <?php echo $objini[$cont]->getResultiniciativa()->getSituacao()==4?"selected":""; ?> value="4">Em andamento normal</option>
                                    <option <?php echo $objini[$cont]->getResultiniciativa()->getSituacao()==5?"selected":""; ?> value="5">Concluído</option>
                                </select>
                           </td>
                    </tr>
                    <tr>
                        <td><strong>Influência dos fatores na situação atual: de 0 (nenhuma) a 5 (muito alta)</strong></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Capacitação </td>
                        <td>  
                        	<input class="form-check-input" type="checkbox" name="capacitacao<?php echo $cont;?>" value="1"  <?php echo $objini[$cont]->getResultiniciativa()->getPfcapacit()==1?"checked":""; ?> ><br>  
                       </td>
                    </tr>
                    <tr>
                       <td>Recursos de Tecnologia da Informação</td>
	                       	<td>
	                       		<input class="form-check-input" type="checkbox" name="recti<?php echo $cont;?>" value="1" <?php echo $objini[$cont]->getResultiniciativa()->getPfrecti()==1?"checked":""; ?>><br>
	                       </td>
                       </tr>
                    <tr>
                       <td>Infraestrutura Física</td>
                        <td>
                        	<input class="form-check-input" type="checkbox" name="infraf<?php echo $cont;?>" value="1" <?php echo $objini[$cont]->getResultiniciativa()->getPfinfraf()==1?"checked":""; ?>><br>
                        </td>
                    </tr>
                    <tr>
                        <td>Recursos financeiros</td>
                        	<td>  <input class="form-check-input" type="checkbox" name="recf<?php echo $cont;?>" value="1" <?php echo $objini[$cont]->getResultiniciativa()->getPfrecf()==1?"checked":""; ?>><br>
                        </td>
                    </tr>
                    <tr>
                        <td>Planejamento</td>
                        	<td><input class="form-check-input" type="checkbox" name="plan<?php echo $cont;?>" value="1" <?php echo $objini[$cont]->getResultiniciativa()->getPfplanj()==1?"checked":""; ?>><br>
                        </td>
                    </tr>
                    <tr>
                        <td>Outros</td>
                        <td>
                        	  <input class="form-control"name="outro<?php echo $cont;?>" type="text" maxlength=50  value="<?php print $objini[$cont]->getResultiniciativa()->getOutros(); ?>">
                        </td>
                     </tr>
                         
                    </table>
				</div>
			</div>
		</div>	


				<?php }
				}//for?>
			</div>
		



			<?php   if ($objmeta->getPeriodo()=='P' && $habilita=='Parcial' && $mperiodo==1 && count($objini)>0) {?>
			<a href='#topo'><input class="form-control"id="pdi-salvar" type="button" value="Gravar"
				class="btn btn-info" /> </a>
				<?php  } else  if ($objmeta->getPeriodo()=='P' && $habilita=='Final' && $mperiodo==2 && count($objini)>0){?>
			<a href='#topo'><input class="form-control"id="pdi-salvar" type="button" value="Gravar"
				class="btn btn-info" /> </a>
				<?php  }  ?>

<?php }//if resultado do indicador
				else{ ?>
			<div class="alert alert-danger">
				<button type="button " class="close" data-dismiss="alert">&times;</button>
				<?php echo "Retorne à página anterior e atualizar o resultado do indicador!"; ?>
			</div>
			<?php  }
} //meta
?>



			<input class="form-control"type="hidden" name="salvar" value="I" />
		</form>
	</div>







	<script>

$("#iniciativa").click(function(event){
	
    $("#respiniciativa").empty();
    $.ajax({
		type: "POST",
		url: "ajax/resultadopdi/riniciativa.php",
		data: $("#resultado-atualiza").serialize(),
		success: function(data){
			$("#respiniciativa").html(data);
		}    });

    });
    
function exibe(cheque){
 var nameid="#chkb"+cheque.id;
    if (cheque.checked){
       $(nameid).show();
    }else{
        $(nameid).hide();
    }
}  

 
</script>

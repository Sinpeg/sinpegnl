<?php
/* models */

require_once MODULO_DIR . 'documentopdi/classe/Documento.php';
require_once MODULO_DIR . 'mapaestrategico/classes/Mapa.php';
require_once MODULO_DIR . 'indicadorpdi/classe/Indicador.php';
require_once MODULO_DIR . 'metapdi/classe/Meta.php';
require_once MODULO_DIR . 'calendarioPdi/classes/Calendario.php';
require_once MODULO_DIR . 'resultadopdi/classes/Resultado.php';
require_once MODULO_DIR . 'mapaestrategico/classes/Objetivo.php';
require_once MODULO_DIR . 'documentopdi/classe/Perspectiva.php';
require_once MODULO_DIR . 'indicadorpdi/classe/Mapaindicador.php';
/* DAO */
require_once MODULO_DIR . 'documentopdi/dao/DocumentoDAO.php';
require_once MODULO_DIR . 'mapaestrategico/dao/MapaDAO.php';
require_once MODULO_DIR . 'mapaestrategico/dao/ObjetivoDAO.php';
require_once MODULO_DIR . 'documentopdi/dao/PerspectivaDAO.php';
require_once MODULO_DIR . 'indicadorpdi/dao/IndicadorDAO.php';
require_once MODULO_DIR . 'metapdi/dao/MetaDAO.php';
require_once MODULO_DIR . 'resultadopdi/dao/ResultadoDAO.php';
require_once MODULO_DIR . 'calendarioPdi/dao/CalendarioDAO.php';
require_once MODULO_DIR . 'indicadorpdi/dao/MapaIndicadorDAO.php';

/* fim */
?>
<?php
// DAO
//session_start();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
if (!isset($sessao)) {
    exit();
}
if (!$aplicacoes[29]) {
    print ("O usuário não possui permissão para acessar esta aplicação.");
    exit();
}
$anobase = $sessao->getAnobase();
$codunidade = $sessao->getCodUnidade();
$unidade=new Unidade();
$unidade->setCodunidade($codunidade);
?>
<?php
$coddocumento = $_GET["documento"]; // código do documento
$codmapa = $_GET["objetivo"]; // código do mapa
$codmapind = $_GET["indicador"]; // código do mapaindicador
$codmeta = $_GET["meta"]; // código da meta atingida
$periodo = $_GET["periodo"]; // período
$mperiodo=$_GET["mperiodo"];
//echo $periodo." e ".$mperiodo;
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
foreach ($rows as $row) {
      $habilita=$row['habilita'];
      $objcal=new Calendario();
      $objcal->setCodigo($row['codCalendario']);
      $objcal->setUnidade(NULL);
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
//echo "mapaind".$codmapind."<br>";
$rows = $daomapind->buscaMapaIndicador($codmapind);
$cont1 = 0;
foreach ($rows as $row) {
   // for ($i = 1; $i <= count($objmapa); $i++) { 
       // echo "mapa".$row['codMapa'];
         //echo "uni".$unidade->getCodunidade();
        //if ($objmapa->getCodigo() == $row['codMapa'] 
        //&& $row['PropIndicador'] == $unidade->getCodunidade()) {
                /* echo "- aqui-". $objmapa->getCodigo()."=".$row['codMapa']."--".$row['PropIndicador']."=". $unidade->getCodunidade()."=".$row['nome']."</br>";*/
                 $cont1++;             
                 $objetoind =new Indicador();
                 $objetoind->setCodigo($row['codIndicador']);
                 $objetoind->setNome($row['nome']);
                 $objetoind->setCalculo($row['calculo']);
                 $objetoind->setInterpretacao($row['interpretacao']);
                 $objetoind->setUnidademedida($row['unidadeMedida']);
                 $objmapa->criaMapaIndicador($row['codigo'],$objetoind,$unidade->getCodunidade() ) ;
                 $objmapind=$objmapa->getMapaindicador();
        //}
    //}//for
}//foreac
// Buscar a meta associada com o indicador
//echo "mi".$objmapind->getCodigo().",".$objcal->getCodigo();
$daometa = new MetaDAO();
$rows = $daometa->buscarmetaindicador($objmapind->getCodigo(),$objcal->getCodigo());
    foreach ($rows as $row) {
                    //echo "meta".$row["meta"]." ".$row["periodo"]."Anual ".$row["Codigo"].'</br>';
                 
                   // if ($anobase<2022  ) {
                   
                   if ($anobase<2022 || $objdoc->getAnofinal()==2022) {
                        $objmapind->criaMeta($row["Codigo"],$row["periodo"],$objcal->getCodigo(),$row["meta"],$sessao->getAnobase(),$row["metrica"],$row["cumulativo"],$row['anoinicial'],$row['periodoinicial']);
                    }else {
                        $objmapind->criaMeta($row["Codigo"],$row["periodo"],$objcal->getCodigo(),$row["meta"],$sessao->getAnobase(), $objmapind->getIndicador()->getUnidademedida(),$row["cumulativo"],$row['anoinicial'],$row['periodoinicial']);
                    }
        
                    
                    $objmeta=$objmapind->getMeta();
    }
// Busca o resultado associado
//echo "meta ".$objmeta->getCodigo()."<br>";
$daores = new ResultadoDAO();
$rows = $daores->buscaresultaperiodometa($codmeta, $mperiodo);
$objresultado=NULL;
foreach ($rows as $row) {
   $objmeta->criaResultado($row['Codigo'],$row['meta_atingida'],$row['periodo'],$row['analiseCritica'],$row['acaoPdi']);
   $objresultado = $objmeta->getResultado();
}
//}


?>


<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active"><a href="<?php echo Utils::createLink('resultadopdi', 'consultaresult'); ?>">Lançar Resultados</a> >> <a href="#" >Atualizar Resultados</a></li>  
		</ul>
	</div>
</head>



<span id='topo'></span> <!-- perminte pagina voltar ao topo -->

<?php if ($objresultado != NULL) { ?>
    <fieldset>
        <div id="pdi-resumo"> 
            <button type="button " class="close" data-dismiss="alert">&times;</button>
            <div>
                <strong>Documento:</strong> <?php print $objdoc->getNome(); ?>
            </div>
            <div>
                <strong>Objetivo Estratégico:</strong> <?php echo $objmapa->getObjetivoPDI()->getObjetivo(); ?>
            </div>
            <div>
                <strong>Indicador:</strong> <?php print ($objmapind->getIndicador()->getNome()); ?>
            </div>

            <div>
                <strong>Meta:</strong><?php print (str_replace('.', ',', $objmeta->getMeta())); ?>
            </div>
            <div>
                <strong>Métrica:</strong><?php 
                
                if ($anobase<2022){
                    print (($objmeta->getMetrica() == "Q") ? ("Absoluto") : ("Percentual"));
                }else {
                           // $metrica= $objmapind->getIndicador()->getUnidademedida();
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
                }
                ?>
            </div>
            <div>
                <strong>Forma de Cálculo:</strong> <?php echo $objmapind->getIndicador()->getCalculo(); ?>
            </div>
           <!-- <div>
                <strong>Registro cumulativo da meta atingida:</strong> <?php
                                  //echo $objmeta->getCumulativo()==1?"Sim":"Não"; ?>
            </div>-->
            
        </div>
        <div>
            <legend>Atualizar Resultados - <?php print $vetor[$mperiodo-1];?></legend>
            <div id="resposta-ajax"></div>
            <form class="form-horizontal" name="resultado-atualiza" id="resultado-atualiza" method="POST" action="ajax/resultadopdi/persisteresult.php">
                <table>
                    <tr>  <td>  <label>Resultado</label></td>
                        <td><input class="form-control"type="text" name="resultado" data-mask="000000,00" data-mask-reverse="true" id="infSemaforo" value="<?php print str_replace('.', ',', $objresultado->getMetaAtingida()); ?>"/>
                            <input class="form-control"type="hidden" name="interpretacao" value="<?php print $objetoind->getInterpretacao(); ?>" />
                        
                        
                    <div id="resposta-semaforo"></div>    
                        </td>
                    </tr>
                <tr><td> <label>Análise Crítica  </label>
                    </td><td>  
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
                    <textarea class="carea"  name="critica" cols="100" rows="15"><?php print $objresultado->getAnaliseCritica(); ?></textarea>
                
                    </td>
                    </tr>
                    
        <?php 
                    if ($objdoc->getTipo()==1) {
                /* Só a partir do documento 2 que as ações começam a ser registradas
                Tipo 1 - PDI, Tipo 2 - PDI*/      ?>
            <!--      <tr><td> 
                    <label>Ações relativas ao indicador</label></td>
                    <td>
                    <textarea class="carea" name="acaopdi" cols="100" rows="15"><?php //print $objresultado->getAcao(); ?></textarea>
                    </td>
                    </tr>-->
                    
<?php } ?>
                </table>
          
<?php   
                $daoii=new IndicIniciativaDAO();
                //$rows=$daoii->iniciativaPorMapIndicador($objmeta->getMapaindicador()->getCodigo());
                $rows=$daoii->iniciativaPorMapIndicador($objmeta->getMapaindicador()->getCodigo(),$anobase,$mperiodo);
                $objini=array();
                $j=0;
                foreach ($rows as $r){
                	//echo "passou ".$r['codIniciativa']." e ".$r['periodo'].'<br>';
                	
                	
                    $objini[$j]=new Iniciativa();
                    $objini[$j]->setCodIniciativa($r['codIniciativa']);
                    //echo "antes ".$objini[$i]->getCodIniciativa();
                    $objini[$j]->setNome($r['nome']);
                    //echo $objini[$j]->getNome()."<br>";
                    $objini[$j]->setFinalidade($r['finalidade']);
                    $objini[$j]->criaIndicIniciativa($r['codindinic'],$objmeta->getMapaindicador());
                    $j++;
                }

?>
<br>
<?php

$objiniresult=array();   
$daorini=new ResultIniciativaDAO();
for ($i=0;$i<count($objini);$i++){
	//echo $i.'<br>';
    $rows=$daorini->iniciativaPorResultado($objini[$i]->getCodIniciativa(), $sessao->getAnobase(),$mperiodo);
    foreach  ($rows as $row){
             $objini[$i]->criaResultIniciativa($row['codResultIniciativa'], $objcal->getCodigo()==$row['codCalendario']?$objcal:NULL, 
             $row['situacao'], $row['pfcapacit'], $row['pfrecti'],$row['pfinfraf'],$row['pfrecf'],$row['pfplanj'],
             $row['outros'], $row['periodo']);
             
                       //  echo "aqui".$objini[$i]->getResultiniciativa()->getCodigo()." ind ".$i; 
             
    }  
}
            //echo "aqui".$objini[1]->getResultiniciativa()->getCodigo(); 

?>
        
            <legend>Atualizar Resultados da Iniciativa - 
            
<?php
if ($mperiodo==0){
	print "Período não identificado! Solicite liberação do período para lançamento.";
}else {
			print $vetor[$mperiodo-1];
}       	
			?>

<?php //print $vetor[$mperiodo-1];?></legend>
            <?php 
        
         
            
            
for ($i=0;$i<count($objini);$i++){
              //echo $objiniresult[$i]->getCodigo()."cccc";
            ?>
    <div>
        <input class="form-control"type="hidden" name="codindini<?php echo $i;?>" value="<?php print $objini[$i]->getIndicIniciativa()->getCodigo(); ?>" />
        <input class="form-control"type="hidden" name="codini<?php echo $i;?>" value="<?php print   $objini[$i]->getCodiniciativa(); ?>" />  
    </div>
  
  
	<div class="panel panel-default">
		<div class="panel-heading" role="tab" id="headingTwo">
			<h4 class="panel-title">
				<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="<?php echo "#spand".$i;?>" aria-expanded="false" aria-controls="collapseTwo">
	   				<strong>Iniciativa - </strong><?php print($objini[$i]->getNome());?>
	  			</a>
	   		</h4>
	   	</div>
   			<div id="<?php echo "spand".$i;?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
   				<div class="panel-body">
  		  
  			     <input class="form-control"type="hidden" name="codresultini<?php echo $i;?>" value="<?php print $objini[$i]->getResultiniciativa()->getCodigo(); ?>"/>
  			
                <table>
                 <!--    <tr>
                        <td><strong>Finalidade da iniciativa:</strong></td>
                        <td> <?php //echo $objini[$i]->getFinalidade(); ?></td>
                    </tr>--> 
                    
                    
                    <tr>
                        <td><strong>Situação</strong></td>
                        <td>
                            <select class="custom-select" name="sit<?php echo $i;?>">                 
                                   <option selected value="0">Selecione a situação...</option>
                                    <option <?php echo $objini[$i]->getResultiniciativa()->getSituacao()==1?"selected":""; ?> value="1">Não iniciado</option>
                                    <option <?php echo $objini[$i]->getResultiniciativa()->getSituacao()==2?"selected":""; ?> value="2">Em atraso</option>
                                    <option <?php echo $objini[$i]->getResultiniciativa()->getSituacao()==3?"selected":""; ?> value="3">Com atrasos críticos</option>
                                    <option <?php echo $objini[$i]->getResultiniciativa()->getSituacao()==4?"selected":""; ?> value="4">Em andamento normal</option>
                                    <option <?php echo $objini[$i]->getResultiniciativa()->getSituacao()==5?"selected":""; ?> value="5">Concluído</option>
                                </select>
                           </td>
                    </tr>
                    <tr>
                        <td><strong>Que fatores abaixo contribuíram para a situação atual da inciativa?</strong></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Capacitação </td>
                        <td>  
                        	<input class="form-check-input" type="checkbox" name="capacitacao<?php echo $i;?>" value="1"  <?php echo $objini[$i]->getResultiniciativa()->getPfcapacit()==1?"checked":""; ?> ><br>  
                       </td>
                    </tr>
                    <tr>
                       <td>Recursos de Tecnologia da Informação</td>
	                       	<td>
	                       		<input class="form-check-input" type="checkbox" name="recti<?php echo $i;?>" value="1" <?php echo $objini[$i]->getResultiniciativa()->getPfrecti()==1?"checked":""; ?>><br>
	                       </td>
                       </tr>
                    <tr>
                       <td>Infraestrutura Física</td>
                        <td>
                        	<input class="form-check-input" type="checkbox" name="infraf<?php echo $i;?>" value="1" <?php echo $objini[$i]->getResultiniciativa()->getPfinfraf()==1?"checked":""; ?>><br>
                        </td>
                    </tr>
                    <tr>
                        <td>Recursos financeiros</td>
                        	<td>  <input class="form-check-input" type="checkbox" name="recf<?php echo $i;?>" value="1" <?php echo $objini[$i]->getResultiniciativa()->getPfrecf()==1?"checked":""; ?>><br>
                        </td>
                    </tr>
                    <tr>
                        <td>Planejamento</td>
                        	<td><input class="form-check-input" type="checkbox" name="plan<?php echo $i;?>" value="1" <?php print $objini[$i]->getResultiniciativa()->getPfplanj()==1?"checked":""; ?>><br>
                        </td>
                    </tr>
                    <tr>
                        <td>Outros</td>
                        <td>
                        	  <input class="form-control"name="outro<?php echo $i;?>" type="text" maxlength=50  value="<?php print $objini[$i]->getResultiniciativa()->getOutros(); ?>">
                        </td>
                     </tr>
                       
                    </table>
				</div>
			</div>
		</div>			
            
<?php }//for objiniresult ?>
            <div>
                        <input class="form-control"type="hidden" name="coddoc" value="<?php print $objdoc->getCodigo(); ?>"/>
                        <input class="form-control"type="hidden" name="codindicador" value="<?php print $objmapind->getCodigo(); ?>" />
                        <input class="form-control"type="hidden" name="codcal" value="<?php print $objcal->getCodigo(); ?>" />
                        <input class="form-control"type="hidden" name="codmeta" value="<?php print   $codmeta; ?>" />
                        <input class="form-control"type="hidden" name="codindicador" value="<?php print $objmapind->getCodigo(); ?>" />
                        <input class="form-control"type="hidden" name="coddoc" value="<?php print $objdoc->getCodigo(); ?>" />
                        <input class="form-control"type="hidden" name="codcal" value="<?php print $objcal->getCodigo(); ?>" />
                        <input class="form-control"type="hidden" name="periodo" value="<?php print $periodo; ?>"/>
                        <input class="form-control"type="hidden" name="mperiodo" value="<?php print $mperiodo; ?>"/>
                        <input class="form-control"type="hidden" name="meta" value="<?php print $objmeta->getMeta(); ?>" />
                        
                        <input class="form-control"type="hidden" name="cumulativo" value="<?php print $objmeta->getCumulativo(); ?>" />
                        <input class="form-control"type="hidden" name="metrica" value="<?php print   $objmeta->getMetrica(); ?>" />
                       <input class="form-control"type="hidden" name="salvar" value="U"/>
            </div>
        <div>
        <?php 
        
       // echo $objmeta->getPeriodo()."-".$habilita."-".$mperiodo;
        if ($objmeta->getPeriodo()=='P' && $habilita=='Parcial' && $mperiodo==1) {?>
            <a href='#topo'><input class="form-control"id="pdi-salvar" type="button" value="Alterar" class="btn btn-info" /></a>
            	<?php  } else  if ($objmeta->getPeriodo()=='P' && $habilita=='Final' && $mperiodo==2){?>
            <a href='#topo'><input class="form-control"id="pdi-salvar" type="button" value="Alterar" class="btn btn-info" /></a>
        <?php  }   ?>
        </div>
        </form>
        </div>
    </fieldset>
<?php }else{ ?>
    <div class="alert alert-danger">
        <button type="button " class="close" data-dismiss="alert">&times;</button>
        <?php echo "A Pesquisa não retornou resultado!"; ?>
    </div>
<?php } ?>
        

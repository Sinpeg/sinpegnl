<?php
/* models */
require_once MODULO_DIR . 'documentopdi/classe/Documento.php';
require_once MODULO_DIR . 'mapaestrategico/classes/Mapa.php';
require_once MODULO_DIR . 'indicadorpdi/classe/Indicador.php';
require_once MODULO_DIR . 'metapdi/classe/Meta.php';
require_once MODULO_DIR . 'calendarioPdi/classes/Calendario.php';
require_once MODULO_DIR . 'resultadopdi/classes/Resultado.php';
require_once MODULO_DIR . 'mapaestrategico/classes/Objetivo.php';
require_once MODULO_DIR . 'mapaestrategico/classes/Perspectiva.php';
require_once MODULO_DIR . 'indicadorpdi/classe/Mapaindicador.php';
/* DAO */
require_once MODULO_DIR . 'documentopdi/dao/DocumentoDAO.php';
require_once MODULO_DIR . 'mapaestrategico/dao/MapaDAO.php';
require_once MODULO_DIR . 'mapaestrategico/dao/ObjetivoDAO.php';
require_once MODULO_DIR . 'mapaestrategico/dao/PerspectivaDAO.php';
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
$codini= $_GET["iniciativa"]; // iniciativa
$coddocumento = $_GET["documento"]; // código do documento
$codmapa = $_GET["objetivo"]; // código do mapa
$codmapind = $_GET["indicador"]; // código do mapaindicador
$codmeta = $_GET["meta"]; // código da meta atingida
$periodo = $_GET["periodo"]; // período
$mperiodo=$_GET["mperiodo"];
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
$rows=$daocal->verificaPrazoCalendarioDoDocumento($anobase,$objdoc->getCodigo());
$habilita=false;
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
    $objdoc->criaMapa($row['Codigo'],  $objpers, $objobji,  $unidadeDoPlano,$row['dataCadastro']); 
    $objmapa=$objdoc->getMapa();
    }
}

//busca mapaindicador e indicador
$daomapind = new MapaIndicadorDAO();
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
                 $objetoind ->setCodigo($row['codIndicador']);
                 $objetoind ->setNome($row['nome']);
                 $objetoind ->setCalculo($row['calculo']);
                 $objmapa->criaMapaIndicador($row['codigo'],$objetoind,$unidade->getCodunidade() ) ;
                 $objmapind=$objmapa->getMapaindicador();
        //}
    //}//for
}//foreac
// Buscar a meta associada com o indicador
//echo $objmapind->getCodigo().",".$objcal->getCodigo();
$daometa = new MetaDAO();
$rows = $daometa->buscarmetaindicador($objmapind->getCodigo(),$objcal->getCodigo());
    foreach ($rows as $row) {
                   // echo "meta".$row["meta"]." ".$row["periodo"]."Anual ".$row["Codigo"].'</br>';
                    $objmapind->criaMeta($row["Codigo"],$objcal->getCodigo(),$row["periodo"],$row["meta"],$row["metrica"],$row["cumulativo"]);
                    $objmeta=$objmapind->getMeta();
    }
// Busca o resultado associado
$daores = new ResultadoDAO();
$rows = $daores->buscaresultaperiodometa($codmeta, $mperiodo);
$objresultado=NULL;
foreach ($rows as $row) {
   $objmeta->criaResultado($row['Codigo'],$row['meta_atingida'],$row['periodo'],$row['analiseCritica'],$row['acaoPdi']);
   $objresultado = $objmeta->getResultado();
}
//}
//Iniciativa

$daoini=new IndicIniciativaDAO();
$rows=$daoini->iniciativaPorMapIndicadorIni($codmapind,$codini);
foreach  ($rows as $row){
    $objini=new Iniciativa();
    $objini->setCodiniciativa($row['codIniciativa']);
    $objini->setNome($row['nome']);
    $objini->setFinalidade($row['finalidade']);
    $objini->criaIndicIniciativa($row['codindinic'],$objmapind);  
}
$objiniresult=NULL;   
$daorini=new ResultIniciativaDAO();
$rows=$daorini->iniciativaPorResultado($objini->getIndicIniciativa()->getCodigo(), $sessao->getAnobase(),$mperiodo);
foreach  ($rows as $row){
        $objiniresult=new ResultIniciativa();
     	$objiniresult->setCodigo($row['codResultIniciativa']) ;
        $objiniresult->setCalendario($objcal->getCodigo()==$row['codCalendario']?$objcal:NULL);
		$objiniresult->setIndic_iniciativa($objini->getIndicIniciativa()->getCodigo()==$row['codInidIniciativa']?$objini->getIndicIniciativa():NULL);
        $objiniresult->setSituacao($row['situacao']);
		$objiniresult->setPfcapacit($row['pfcapacit']);
        $objiniresult->setPfrecti($row['pfrecti']);
		$objiniresult->setPfinfraf($row['pfinfraf']);
		$objiniresult->setPfrecf($row['pfrecf']);
        $objiniresult->setPfplanj($row['pfplanj']);
        $objiniresult->setPfOutros($row['pfoutros']==NULL?"":$row['pfoutros']);//numero
		$objiniresult->setOutros($row['outros']);
  //  echo "<br>row".$objiniresult->getOutros();
        $objiniresult->setAnalisecritica($row['analisecritica']);
        $objiniresult->setPeriodo($row['periodo']);
    
}    
?>
<head>
<style>
.formfield * {
  vertical-align: text-top;
}
</style>



</head>

<?php if ($objiniresult != NULL) { ?>
    <fieldset>
        <div id="pdi-resumo"> 
            <button type="button " class="close" data-dismiss="alert">&times;</button>
           <div>
                    <strong>Documento:</strong> <?php print $objdoc->getNome(); ?>
                </div>
                <div>
                    <strong>Objetivo Estratégico:</strong> <?php print $objdoc->getMapa()->getObjetivoPDI()->getObjetivo(); ?>
                </div>
                <div>
                    <strong>Indicador:</strong> <?php print ($objmapind->getIndicador()->getNome()); ?>
                </div>

                <div>
                    <strong>Meta:</strong><?php print ($objmeta->getMeta()); ?>
                </div>
                <div>
                    <strong>Resultado do indicador:</strong><?php print($objresultado->getMetaAtingida()); ?>
                </div>
                <div>
                    <strong>Iniciativa:</strong> <?php print($objini->getNome()); ?>
                </div>
            <!--    <div>
                     <strong>Finalidade da iniciativa:</strong> <?php // echo $objini->getFinalidade(); ?> 
                </div> -->
            
        </div>
        <div>
            <legend>Atualizar Resultados da Iniciativa - <?php print $vetor[$mperiodo-1];?></legend>
            <div id="resposta-ajax"></div>
            <form class="form-horizontal" name="resultado-atualiza" method="POST" action="ajax/resultadopdi/persisteresultini.php">

            <table>
                       <tr><td><strong>Situação</strong></td>
                           <td><select class="custom-select" name="sit">                 
                                   <option selected value="0">Selecione a situação...</option>
                                    <option <?php echo $objiniresult->getSituacao()==1?"selected":""; ?> value="1">Não iniciado</option>
                                    <option <?php echo $objiniresult->getSituacao()==2?"selected":""; ?> value="2">Em atraso</option>
                                    <option <?php echo $objiniresult->getSituacao()==3?"selected":""; ?> value="3">Com atrasos críticos</option>
                                    <option <?php echo $objiniresult->getSituacao()==4?"selected":""; ?> value="4">Em andamento normal</option>
                                    <option <?php echo $objiniresult->getSituacao()==5?"selected":""; ?> value="5">Concluído</option>
                                </select>
                           </td>
                    <tr><td>
                        <strong>Influência dos fatores na situação atual: de 0 (nenhuma) a 5 (muito alta)</strong></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            Capacitação </td>
                        <td>  <input class="form-control"type="radio" name="capacitacao" value="0"  <?php echo $objiniresult->getPfcapacit()==0?"checked":""; ?> >0
                              <input class="form-control"type="radio" name="capacitacao" value="1" <?php echo $objiniresult->getPfcapacit()==1?"checked":""; ?>>1
                              <input class="form-control"type="radio" name="capacitacao" value="2" <?php echo $objiniresult->getPfcapacit()==2?"checked":""; ?>>2
                              <input class="form-control"type="radio" name="capacitacao" value="3" <?php echo $objiniresult->getPfcapacit()==3?"checked":""; ?>>3
                              <input class="form-control"type="radio" name="capacitacao" value="4" <?php echo $objiniresult->getPfcapacit()==4?"checked":""; ?>>4
                              <input class="form-control"type="radio" name="capacitacao" value="5" <?php echo $objiniresult->getPfcapacit()==5?"checked":""; ?>>5<br>
                    
                       </td>
                       </tr>
                       <tr>
                       <td>Recursos de Tecnologia da Informação
                         </td>
                        <td><input class="form-control"type="radio" name="recti" value="0" <?php echo $objiniresult->getPfrecti()==0?"checked":""; ?>>0
                              <input class="form-control"type="radio" name="recti" value="1" <?php echo $objiniresult->getPfrecti()==1?"checked":""; ?>>1
                              <input class="form-control"type="radio" name="recti" value="2" <?php echo $objiniresult->getPfrecti()==2?"checked":""; ?>>2
                              <input class="form-control"type="radio" name="recti" value="3"  <?php echo $objiniresult->getPfrecti()==3?"checked":""; ?>>3
                              <input class="form-control"type="radio" name="recti" value="4" <?php echo $objiniresult->getPfrecti()==4?"checked":""; ?>>4
                              <input class="form-control"type="radio" name="recti" value="5" <?php echo $objiniresult->getPfrecti()==5?"checked":""; ?>>5<br></td>
                       </tr>
                        <tr>
                       <td>Infraestrutura Física
                         </td>
                        <td><input class="form-control"type="radio" name="infraf" value="0" <?php echo $objiniresult->getPfinfraf()==0?"checked":""; ?>>0  
                              <input class="form-control"type="radio" name="infraf" value="1" <?php echo $objiniresult->getPfinfraf()==1?"checked":""; ?>>1  
                              <input class="form-control"type="radio" name="infraf" value="2" <?php echo $objiniresult->getPfinfraf()==2?"checked":""; ?>>2  
                              <input class="form-control"type="radio" name="infraf" value="3" <?php echo $objiniresult->getPfinfraf()==3?"checked":""; ?>>3  
                              <input class="form-control"type="radio" name="infraf" value="4" <?php echo $objiniresult->getPfinfraf()==4?"checked":""; ?>>4  
                              <input class="form-control"type="radio" name="infraf" value="5" <?php echo $objiniresult->getPfinfraf()==5?"checked":""; ?>>5<br></td>
                       </tr>
                       <tr>
                        <td>Recursos financeiros
                         </td>
                        <td><input class="form-control"type="radio" name="recf" value="0" <?php echo $objiniresult->getPfrecf()==0?"checked":""; ?>>0  
                              <input class="form-control"type="radio" name="recf" value="1" <?php echo $objiniresult->getPfrecf()==1?"checked":""; ?>>1 
                              <input class="form-control"type="radio" name="recf" value="2" <?php echo $objiniresult->getPfrecf()==2?"checked":""; ?>>2  
                              <input class="form-control"type="radio" name="recf" value="3" <?php echo $objiniresult->getPfrecf()==3?"checked":""; ?>>3  
                              <input class="form-control"type="radio" name="recf" value="4" <?php echo $objiniresult->getPfrecf()==4?"checked":""; ?>>4  
                              <input class="form-control"type="radio" name="recf" value="5" <?php echo $objiniresult->getPfrecf()==5?"checked":""; ?>>5<br></td>
                       </tr>
                       <tr>
                        <td>Planejamento</td>
                        <td><input class="form-control"type="radio" name="plan" value="0" <?php echo $objiniresult->getPfplanj()==0?"checked":""; ?>>0  
                              <input class="form-control"type="radio" name="plan" value="1" <?php echo $objiniresult->getPfplanj()==1?"checked":""; ?>>1  
                              <input class="form-control"type="radio" name="plan" value="2" <?php echo $objiniresult->getPfplanj()==2?"checked":""; ?>>2  
                              <input class="form-control"type="radio" name="plan" value="3" <?php echo $objiniresult->getPfplanj()==3?"checked":""; ?>>3  
                              <input class="form-control"type="radio" name="plan" value="4" <?php echo $objiniresult->getPfplanj()==4?"checked":""; ?>>4  
                              <input class="form-control"type="radio" name="plan" value="5" <?php echo $objiniresult->getPfplanj()==5?"checked":""; ?>>5<br></td>
                       </tr>
                         <tr>
                        <td>Outros  
                         </td>
                        <td>  <input class="form-control"name="outro" type="text" maxlength=50  value="<?php print $objiniresult->getOutros(); ?>">
                              <input class="form-control"type="radio" name="pfOutros" value="0" <?php echo $objiniresult->getPfOutros()==NULL?"":"checked"; ?>>0  
                              <input class="form-control"type="radio" name="pfOutros" value="1" <?php echo $objiniresult->getPfOutros()==1?"checked":""; ?>>1 
                              <input class="form-control"type="radio" name="pfOutros" value="2" <?php echo $objiniresult->getPfOutros()==2?"checked":""; ?>>2  
                              <input class="form-control"type="radio" name="pfOutros" value="3" <?php echo $objiniresult->getPfOutros()==3?"checked":""; ?>>3  
                              <input class="form-control"type="radio" name="pfOutros" value="4" <?php echo $objiniresult->getPfOutros()==4?"checked":""; ?>>4  
                              <input class="form-control"type="radio" name="pfOutros" value="5" <?php echo $objiniresult->getPfOutros()==5?"checked":""; ?>>5<br></td>
                       </tr>
                          <tr><td>
                        <strong>Análise Crítica</strong></td>
                        <td>
                        <textarea class="area" name="critica" cols="100" rows="15"><?php echo $objiniresult->getAnalisecritica(); ?></textarea>
                        </td>
                       </tr> 
                    </table>
                <div>
             
                     
                     <input class="form-control"type="hidden" name="coddoc" value="<?php print $objdoc->getCodigo(); ?>"/>
                        <input class="form-control"type="hidden" name="codindicador" value="<?php print $objmapind->getCodigo(); ?>" />
                        <input class="form-control"type="hidden" name="periodoini" value="<?php print $mperiodo; ?>"/>
                        <input class="form-control"type="hidden" name="codcal" value="<?php print $objcal->getCodigo(); ?>" />
                        <input class="form-control"type="hidden" name="codindini" value="<?php print $objini->getIndicIniciativa()->getCodigo(); ?>" />
                        <input class="form-control"type="hidden" name="codini" value="<?php print   $objini->getCodiniciativa(); ?>" />
                        <input class="form-control"type="hidden" name="codmeta" value="<?php print   $codmeta; ?>" />
                        <input class="form-control"type="hidden" name="codresultinic" value="<?php print    $objiniresult->getCodigo(); ?>" />
                   
                  

                    <input class="form-control"type="hidden" name="salvar" value="U"/>
                </div>
                <div>
                <?php 

                     if ($objmeta->getPeriodo()=='P' && $habilita=='Parcial' && $mperiodo==1) {?>
                    <input class="form-control"id="pdi-salvar" type="button" value="Alterar" class="btn btn-info" />
                     <?php  } else  if ($objmeta->getPeriodo()=='P' && $habilita=='Final' && $mperiodo==2){?>
                    <input class="form-control"id="pdi-salvar" type="button" value="Alterar" class="btn btn-info" />

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

<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

//require_once '../documentopdi/dao/DocumentoDAO.php';
//require_once 'classes/Controlador.php';
//require_once 'classes/sessao.php';

$sessao = $_SESSION['sessao'];
$codestruturado = $sessao->getCodestruturado();
$anobase=$sessao->getAnobase();
$codUnidade = $sessao->getCodunidade();
$aplicacoes = $sessao->getAplicacoes(); // código das aplicações
$grupo = $sessao->getGrupo();
$possui_resultado = true;



$c=new Controlador();

if ($c->identificarAnalistas($sessao->getCodusuario())) {
	$grupoAnalista = true;
}else {
	$grupoAnalista = false;
}

if (!$aplicacoes[38]) {
    print "Erro ao acessar esta aplicação";
    exit();
}
if (!empty($_GET['codigo'])){
   $_SESSION['codmapa'] = addslashes($_GET['codigo']);
   $codmapa=$_GET['codigo'];
 
}else{
	$codmapa=$_SESSION['codmapa'];
 
}

if (is_numeric($codmapa)){

    $daomapa=new MapaDAO();
    $rows1=$daomapa->buscamapa($codmapa );
 	foreach ($rows1 as $row) {
      $coddocumento= $row['CodDocumento'];
 	}
 	
    $_SESSION['coddoc'] = $coddocumento;	
    $daometa = new MetaDAO();
    $daomapaind = new MapaIndicadorDAO();
    $objmapaind = array();
    $daoind = new IndicadorDAO();
    $objind = array();
    $daoobjetivo = new ObjetivoDAO();

    $daoResul = new ResultadoDAO();

//Buscar dados do documento
$daoDoc = new DocumentoDAO();
$rowDoc = $daoDoc->buscadocumentoporunidadePeriodo($codUnidade,$anobase,$coddocumento);//aqui 29

//$rowDoc = $daoDoc->buscadocumentoporunidade($codUnidade);
foreach ($rowDoc as $row2){
	$nomeDoc = $row2['nome'];
	$anoinicial = $row2['anoinicial'];
	$anofinalDoc = $row2['anofinal'];
}

$cont = 0;
$rowsobjetivo = $daoobjetivo->buscaObjetivoPorMapa($codmapa);
$objobjetivo = new Objetivo();

foreach ($rowsobjetivo as $rowobjetivo){
	$objobjetivo->setCodigo($rowobjetivo['codobj']);
	$objobjetivo->setObjetivo($rowobjetivo['des']);
}

if ($codUnidade==938){
    $rows = $daomapaind->buscaporMapaSemUnidadeComAno($codmapa,$ano,$coddocumento);//busca indicador vinculado ao objetivo
}else{
    $rows = $daomapaind->buscaporMapaUnidadeAno($codmapa,$codUnidade,$anobase,$coddocumento);//busca indicador vinculado ao objetivo
}

foreach ($rows as $row) {
    $cont++;
    $objmapaind[$cont] = new Mapaindicador();
    $objmapaind[$cont]->setCodigo($row['codigo']);		
    $rowsindicador = $daoind->buscaindicador($row['codIndicador']);
    
    foreach ($rowsindicador as $rowindicador){
        $objind[$cont] = new Indicador();
        $objind[$cont]->setCodigo($rowindicador['Codigo']);			
        $objind[$cont]->setNome($rowindicador['nome']);
        $objind[$cont]->setCalculo($rowindicador['calculo']);
    }
    
    $objmapaind[$cont]->setIndicador($objind[$cont]);
    
    $mapa1=new Mapa();
    $mapa1->setCodigo($row['codMapa']);
    
    $objmapaind[$cont]->setMapa($mapa1);
    
    $objmapaind[$cont]->setPropindicador($row['PropIndicador']);
    
    $querymeta = $daometa->buscaMetaResultadoporCodMapaIndiOnly($row['codigo'],$anobase);//busca meta pelo mapaindicador
    
    $cont2 = 0;
    //monta vetor de metas do indicador
    foreach ($querymeta as $m1){
        $c=new Calendario();
        $c->setAnoGestao($m1['ano']);
        $objmapaind[$cont]->adicionaItemMeta($m1['Codigo'], $m1['periodo'], $c, $m1['meta'], $m1['ano'], $m1['metrica'], 0,$anobase,1,NULL,NULL, $cont2++);
    }
    $objind[$cont]->setMapaIndicador($objmapaind[$cont]);	
}	
	
	
	
	
$rowsresant=$daoResul->verResultadosAnosAnteriores( $sessao->getCodunidade(),$anoinicial, $anobase,$coddocumento);
$contlinhasant= $rowsresant->rowCount();
$rowsres=$daoResul->verResultadosAnoPeriodoFinal($sessao->getCodunidade(),$anobase,$coddocumento);
$contlinhas= $rowsres->rowCount();
// echo $contlinhasant."x".$contlinhas;die;
		    
?>

    <script>
        $(function() {
            $("#tabela")
            .tablesorter({
                widthFixed: true,
                headers: {
                    3: {
                        sorter: false
                    }
                },
                widgets: ['zebra']
            }).tablesorterPager({
                container: $("#pager"),
                positionFixed: false,
                size: 10
            });
        });
    
        function deletaindicador(button){
            $(function() {
                $( "#dialog-confirm" ).dialog({
                    resizable: false,
                    height: "auto",
                    width: 400,
                    modal: true,
                    buttons: {
                        "Deletar": function() {
                        $( this ).dialog( "close" );
                            $.ajax({
                                    url: "ajax/indicadorpdi/deletamapaindicador.php", type: 'POST', data: { codindicador: button.value, action : "D"},
                                    
                                    async: true,
                                    success: function(data) {
                                    // alert(data);
                                    if (data.search("SQLSTATE")>0){
                                        
                                        //scroll suave
                                        $('html, body').animate({scrollTop:0}, 'slow'); //slow, medium, fast
                                        $('div#message').html("<div class='alert alert-danger' role='alert'>Erro na operação, o indicador pode estar vinculado a metas ou a iniciativas!</div>");
                                        
                                    }else{
                                    //scroll suave
                                    $('html, body').animate({scrollTop:0}, 'slow'); //slow, medium, fast
                                    $('div#message').html("<div class='alert alert-success' role='alert'>Indicador desvinculado com sucesso!</div>");
                                    var teste = "#"+button.id;
                                    teste = $(teste).parent().parent();
                                    teste.remove();
                                    }
                                    
                                    },
                                error:function(data) {
                                    //scroll suave
                                    $('html, body').animate({scrollTop:0}, 'slow'); //slow, medium, fast
                                    $('div#message').html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'></button><img src='webroot/img/error.png' width='30' height='30'/><strong>Falha ao deletar indicador.</strong></div>");
                            }						
                            });					
                        },
                        Cancelar: function() {
                            $( this ).dialog( "close" );
                        }
                    }
                });
            });
        }
    </script>

	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active"><a href="<?php echo Utils::createLink('mapaestrategico', 'listamapapdu'); ?>">
                Painel <?php print $sessao->getCodunidade()==938?"Estratégico":"Tático"; ?></a> 
                <i class="fas fa-long-arrow-alt-right"></i> <a href="#" >Indicadores Vinculados</a>
            </li>  
		</ul>
	</div>
    
    <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Indicadores Vinculados</h3>
            </div>
            <div class="card-body">
                <?php
                $codObjetico = $objobjetivo->getCodigo(); 
                $obje = $objobjetivo->getObjetivo(); 
                print ( 'Objetivo: '. $objobjetivo->getObjetivo());
                
                if ($cont==0){
                    print '<br><br><br><p color="red">Não há indicadores vinculados a este objetivo!</p>';
                }
                ?>
            </div>
            
            <br><br>
            
            <div id="message"></div>
        
            <?php if ($cont!=0){ ?>
                <div class="card-body">
                    <table class="table table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th>Indicador</th>
                                <th>Fórmula</th>
                                <th>Meta</th>
                                <th>Desvincular</th>
                                <?php echo ($contlinhas==0 && $contlinhasant>0)?" <th>Solicitar Alteração de Indicador</th>":"";?>
                            </tr>
                        </thead>
                        <tbody>        
                            <?php for ($i = 1; $i <= $cont; $i++) {       	?>
                                <tr>
                                    <td><?php print ($objind[$i]->getNome()); ?></td>
                                    <td><?php print ($objind[$i]->getCalculo()); ?></td> 
                                
                                    <?php 
                            
                                    if($objmapaind[$i]!=null && $objmapaind[$i]->getArraymeta()==null  ){?>   
                                    
                                        <td align="center"> 
                                            <!--<a href="// echo Utils::createLink('indicadorpdi', 'cadastrarmeta'); ?>"><img src="webroot/img/add.png"/></a>-->
                                            <form class="form-horizontal" method="post" action="<?php  echo Utils::createLink('indicadorpdi', 'cadastrarmeta'); ?>">
                                                <input class="form-control"type="hidden" name="ind" value="<?php echo $objind[$i]->getCodigo(); ?>" />
                                                <input class="form-control"type="hidden" name="coddoc" value="<?php echo $coddocumento; ?>" />
                                                <input class="form-control"type="hidden" name="mapaind" value="<?php echo $objind[$i]->getMapaIndicador()!=null?$objind[$i]->getMapaIndicador()->getCodigo():""; ?>" />

                                                <input class="form-control"type='image' value="editar" src="webroot/img/add.png" onClick="this.form.submit()" />
                                            </form>
                                        </td>
                        
                                    <?php }else{?> 
                                        <td align="center"> 
                                            <form class="form-horizontal" method="post" action="<?php  echo Utils::createLink('indicadorpdi', 'editarmeta'); ?>">
                                                <input class="form-control"type="hidden" name="ind"value="<?php echo $objind[$i]->getCodigo(); ?>" />
                                                <input class="form-control"type="hidden" name="coddoc"value="<?php echo $coddocumento; ?>" />
                                                <!-- <input class="form-control"type="hidden" name="codobjpdi"value="<?php // echo $objobjetivo->getCodigo(); ?>" />-->
                                                <input class="form-control"type="hidden" name="mapaind"value="<?php echo $objind[$i]->getMapaIndicador()!=null?$objind[$i]->getMapaIndicador()->getCodigo():""; ?>" />
                                                <input class="form-control"type='image' value="editar" src="webroot/img/editar.gif" onClick="this.form.submit()" />
                                            </form>
                                            <!--  <a href=" // echo Utils::createLink('indicadorpdi', 'editarmeta'); ?>"><img src="webroot/img/editar.gif"/></a> -->
                                        </td>
                                    <?php }//if ?> 
                    
                                    <td align="center"> 
                                        <?php if ($objind[$i]->getMapaIndicador()!=null) {
                                            $dao=new MetaDAO();
                                            $rowsi=$dao->buscarmetamapaindicador($objind[$i]->getMapaIndicador()->getCodigo(),$anobase);
                                            
                                            $passou=false;

                                            foreach ($rowsi as $r){
                                                $passou=true;
                                            }

                                            if ($passou){
                                                $img="webroot/img/delete.no.png";
                                                $disabled = "disabled";
                                                $evento="";
                                                $ajuda = "title='Não é possível excluir, pois o Indicador possui Metas cadastradas.'' data-trigger='hover'";
                                            }else{
                                                $img="webroot/img/delete2.png";
                                                $evento='onclick="deletaindicador(this);"';
                                                $disabled = "";
                                                $ajuda = "title='Desvincular Indicador.' data-trigger='hover'";
                                            }
                                            
                                            
                                            //verificar se a mapaind já possui resultado independente do ano
                                            $rowResultMI = $daoResul->buscaResultMapaind($objind[$i]->getMapaIndicador()->getCodigo());
                                            if ($rowResultMI->rowCount() > 0) {
                                                $possui_resultado = false;
                                            }
                                            
                                            
                                            ?>
                                            <button <?php echo $disabled;?> id="<?php echo "btn{$i}";?>" value="<?php echo ($objind[$i]->getMapaIndicador()->getCodigo());?>"
                                                <?php echo $evento;?> <?php echo $ajuda;?>> <img src='<?php echo $img;?>' 
                                                title="Ajuda" data-trigger='hover' alt="Possui meta cadastrada!" width="19" height="19">
                                            </button>
                                        <?php }?>    
                                    </td>
                                
                                    <?php if ($contlinhasant!=0 && $contlinhas==0){
                                        $rowResult = $daoResul->buscaResultMapaindAno($objind[$i]->getMapaIndicador()->getCodigo(),$anobase);
                                        if($rowResult->rowCount()==0){
                                            $troca_img = "webroot/img/troca.png";
                                            $ajudaT = "";
                                            $acaoT = 'onClick="buscarDadosModal('.$objind[$i]->getMapaIndicador()->getCodigo().','.$anobase.')" data-toggle="modal" data-target="#cadastrarSol"';
                                            $disabledT="";
                                        }else{
                                            $troca_img = "webroot/img/troca2.png";
                                            $ajudaT = "title='Não é possível alterar indicador, pois possui resultados lançados para este ano.'' data-trigger='hover'";
                                            $acaoT="";
                                            $disabledT = 'style="pointer-events: none;';
                                        } ?>  
                                    
                                        <td>
                                            <a href="javascript:void(0)" <?php echo $acaoT;?> ><img <?php echo $ajudaT;?> src='<?php echo $troca_img;?>' data-trigger='hover' alt="Solicitar Alteração!" width="19" height="19"></a>
                                        </td>
                                    <?php } ?>
                            </tr>
                            <?php } //for ?>
                        </tbody>
                    </table>
                </div>
                <br>
            <?php } ?>
            <div>
                <table class="card-body">
                    <tfoot>
                        <td colspan="7" align="center">
                            <?php if($contlinhasant!=0 && $contlinhas==0){
                                echo '<button  type="button" onClick="buscarIndicadoresInclusao()"  class="btn btn-info btn" 
                                data-toggle="modal" data-target="#cadastrarSolInclusao">Solicitar Inclusão de Indicador</button>';
                                ?><a href="<?php echo Utils::createLink('indicadorpdi', 'incluiindicador'); ?>" >
                                <button  type="button" id="botao" class="btn btn-info btn">Cadastrar novo indicador</button></a> 
                            <?php } else{
                                if ($codUnidade==938){ ?>    
                                    <a href="<?php echo Utils::createLink('indicadorpdi', 'consultaindicadorproprio938'); ?>" >
                                    <button  type="button" id="botao" class="btn btn-info btn">Vincular Indicadores</button></a>  
                                <?php } else if ($contlinhas==0 && $contlinhasant==0){ ?>
                                    <a href="<?php	
                                        if($anobase<2022){
                                            print Utils::createLink('indicadorpdi', 'consultaindicadorproprio');
                                        }else{
                                            print Utils::createLink('indicadorpdi', 'consultaindicadorproprio2022'); 
                                        } ?>" >
                                        <button  type="button" id="botao" class="btn btn-info btn">Vincular Indicadores</button>
                                    </a>  
                                <?php  }
                            } ?>
                            <!--  <a href=" //echo Utils::createLink('indicadorpdi', 'cestaindicadores'); ?>" >
                            <button  type="button" class="btn btn-info btn">Consultar Cesta de Indicadores</button></a>  -->   
                        </td>
                    </tfoot>
                </table>   
            </div>
            <div id="dialog-confirm" title="Confirmação" style="display: none">
                <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Você tem certeza que deseja desvincular o indicador?</p>
            </div> 
    </div>
    <?php include "modaisEdicao.php";
}//TEste de $_GET ?>




<?php

require '../../dao/PDOConnectionFactory.php';
require '../../dao/unidadeDAO.php';
require '../../classes/sessao.php';
require '../../classes/Controlador.php';
require '../../classes/unidade.php';

require '../../util/Utils.php';

define('MODULO_DIR', (dirname(__FILE__)) . '/../../modulo/');
require_once MODULO_DIR . 'documentopdi/classe/Documento.php';
require_once MODULO_DIR . 'mapaestrategico/classes/Mapa.php';
require_once MODULO_DIR . 'indicadorpdi/classe/Indicador.php';
require_once MODULO_DIR . 'indicadorpdi/classe/Mapaindicador.php';
require_once MODULO_DIR . 'metapdi/classe/Meta.php';
require_once MODULO_DIR . 'resultadopdi/classes/Resultado.php';
require_once MODULO_DIR . 'calendarioPdi/classes/Calendario.php';
require_once MODULO_DIR . 'mapaestrategico/classes/Objetivo.php';
require_once MODULO_DIR . 'documentopdi/classe/Perspectiva.php';
require_once MODULO_DIR . 'iniciativa/classe/Iniciativa.php';
require_once MODULO_DIR . 'iniciativa/classe/IndicIniciativa.php';
require_once MODULO_DIR . 'resultadopdi/classes/ResultIniciativa.php';
require_once MODULO_DIR . 'iniciativa/classe/IndicIniciativa.php';
require_once MODULO_DIR . 'iniciativa/classe/Iniciativa.php';

require_once MODULO_DIR . 'indicadorpdi/dao/MapaIndicadorDAO.php';
require_once MODULO_DIR . 'documentopdi/dao/DocumentoDAO.php';
require_once MODULO_DIR . 'mapaestrategico/dao/MapaDAO.php';
require_once MODULO_DIR . 'indicadorpdi/dao/IndicadorDAO.php';
require_once MODULO_DIR . 'metapdi/dao/MetaDAO.php';
require_once MODULO_DIR . 'resultadopdi/dao/ResultadoDAO.php';
require_once MODULO_DIR . 'calendarioPdi/dao/CalendarioDAO.php';
require_once MODULO_DIR . 'mapaestrategico/dao/ObjetivoDAO.php';
require_once MODULO_DIR . 'documentopdi/dao/PerspectivaDAO.php';
require_once MODULO_DIR . 'iniciativa/dao/IniciativaDAO.php';
require_once MODULO_DIR . 'iniciativa/dao/IndicIniciativaDAO.php';
require_once MODULO_DIR . 'resultadopdi/dao/ResultIniciativaDAO.php';
require_once MODULO_DIR . 'iniciativa/dao/IndicIniciativaDAO.php';
require_once MODULO_DIR . 'iniciativa/dao/IniciativaDAO.php';


//obter dados do formulario
//echo var_dump($_POST);


session_start();
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
$c=new Controlador();
$unidade=new Unidade();
$c->getProfile($sessao->getGrupo())?$unidade->setCodunidade($sessao->getCodUnidsel()):$unidade->setCodunidade($sessao->getCodUnidade());

$periodo = $_POST['periodo']; // período de referência P
$mperiodo = $_POST['mperiodo']; // período de referência 1/2
$analise = strip_tags($_POST['critica']); // análise crítica
$codmeta = addslashes($_POST['codmeta']); // código da meta
$acao = $_POST["salvar"];
$acaopdi=NULL;

if ($c->getProfile($sessao->getGrupo())) {
   // $acaopdi=addslashes($_POST["acaopdi"]);
}
$codmapind = addslashes($_POST['codindicador']); // código do mapa indicador
$resultado = strip_tags(addslashes($_POST['resultado'])); // resultado
$resultado=str_replace(',', '.', $resultado);
$coddoc=$_POST["coddoc"];

$erro = "";

//if ($objresultado->getMetaAtingida() == NULL) {
//    $erro = "O resultado para o período selecionado não existe. Portanto, 
//        não pode ser atualizado.";
//}
if (!is_numeric($codmapind)){
      $erro="Problema no módulo, comunique o administrador do sistema.";
}//else if ($resultado == "" || !is_numeric($resultado)) {
  //  $erro = "Por favor, informe o valor do resultado em número.";
 //else if (!preg_match('/^(([0-9]+\.[0-9]+)|([1-9][0-9]*)|([0]{1}))$/', strip_tags(addslashes($_POST['resultado'])))) {
  // $erro = "Preencha corretamente o valor do resultado, que deve ser um número inteiro ou decimal com vírgula.";
 //else if (strpos(strip_tags(addslashes($_POST['resultado'])),',') && $_POST['metrica']=="Q"){
 //  $erro = "Métrica quantitativa não aceita resultado decimal!";
 //}
 else if ($analise == "") {
    $erro = "Por favor, preencha o campo para a análise crítica.";
}else{

    
//-----Preenche vetores da iniciativa
        $daoini=new IndicIniciativaDAO();
        $rowini=$daoini->iniciativaPorMapIndicador($codmapind,$anobase,$periodo);
        $contini=$rowini->rowcount();
        $iniciativas=array();
        $situacao=array();
        $capacitacao=array();
        $plan=array();
        $recti=array();
        $recf=array();
        $infraf=array();
//         $pfoutro=array();
        $outro=array();
       
        for ($i=0;$i<$contini;$i++){
            $iniciativas[$i]=$_POST['codini'.$i]; 
            $situacao[$i]=$_POST['sit'.$i]; 
            $infraf[$i]=isset($_POST['infraf'.$i])?1:0;
            $recf[$i]=isset($_POST['recf'.$i])?1:0;
            $recti[$i]=isset($_POST['recti'.$i])?1:0;
            $plan[$i]=isset($_POST['plan'.$i])?1:0;
            $capacitacao[$i]=isset($_POST['capacitacao'.$i])?1:0;
            if  (!is_numeric($situacao[$i]) || $situacao[$i]==0 ) {
                $erro.= "Por favor, informe situação válida da iniciativa ".($i+1);
            }else if ($_POST['outro'.$i]!=""){
                $outro[$i]=addslashes($_POST['outro'.$i]);
         //      $pfoutro[$i]=$_POST['pfOutros'.$i]; 
           //     echo "cccccc".$_POST['outro'.$i];
//                 if (!is_numeric($pfoutro[$i])){
// //                 	$pfoutro[$i] = 0;
//                 }
            }else if ($_POST['outro'.$i]==""){
                    $outro[$i]=NULL;
//                     $pfoutro[$i]=NULL;
            }
            
            if (!is_numeric($capacitacao[$i])){
            	$capacitacao[$i] = 0;
            }
            if  (!is_numeric($recti[$i])){
            	$recti[$i] = 0;
            }
            if (!is_numeric($infraf[$i])){
            	$infraf[$i] = 0;
            }
            if (!is_numeric($recf[$i])){
            	$recf[$i] = 0;
            }
            if (!is_numeric($plan[$i])){
            	$plan[$i] = 0;
            }
            
            
            
        }//for
        
}



if ($erro!=""){ ?>
                                        <div class="alert alert-danger">
                                            <button type="button " class="close" data-dismiss="alert">&times;</button>
                                            <?php echo $erro; ?>
                                        </div>
        <?php }
else{
        	
            $objdoc=new Documento();
                                    $objdoc->setCodigo($coddoc);
                                    $daocal=new CalendarioDAO();
                                    $rows=$daocal->verificaPrazoCalendarioDoDocumento($anobase);
                                    
                                    foreach ($rows as $row) {
                                          $habilita=$row['habilita'];
                                          $objcal=new Calendario();
                                          $objcal->setCodigo($row['codCalendario']);
                                          $objcal->setUnidade(NULL);
                                          $objcal->setAnogestao($row['anoGestao']);
                                    }

                                    $periodos = array(
                                        'A' => 1,
                                        'M' => 12,
                                        'T' => 4,
                                        'S' => 2,
                                        'P' => 2
                                    );

                                    $cont=0;
                                    $objmapa=new Mapa;

                                    //busca mapaindicador e indicador
                                    $daomapind = new MapaIndicadorDAO();
                                    $rows = $daomapind->buscaMapaIndicador($codmapind);
                                    $cont1 = 0;
                                    
                                    foreach ($rows as $row) {
                                                     $cont1++;  
                                                     $objmapa->setCodigo($row['codMapa']);
                                                     $objetoind =new Indicador();
                                                     $objetoind->setCodigo($row['codIndicador']);
                                                     $objetoind->setNome($row['nome']);
                                                     $objetoind->setCalculo($row['calculo']);
                                                     $objmapa->criaMapaIndicador($row['codigo'],$objetoind,$unidade->getCodunidade() ) ;
                                                     $objmapind=$objmapa->getMapaindicador();


                                    }//foreac

                                    
                                    // Buscar a meta associada com o indicador
                                    //echo $objmapind->getCodigo().",".$objcal->getCodigo();
                                    $daometa = new MetaDAO();
                                    $objmeta=NULL;
                                    
                                    $rows = $daometa->buscarmetaindicador($objmapind->getCodigo(),$objcal->getCodigo());
                                  
                                        foreach ($rows as $row) {
                                                      //  echo "meta".$row["meta"]." ".$row["periodo"]."Anual ".$row["Codigo"]." cum ".$row["cumulativo"].'</br>';
                                            $objmapind->criaMeta($row["Codigo"],$row["periodo"],$objcal,$row["meta"],$row['ano'],$row["metrica"],$row["cumulativo"],$row["anoinicial"],$row["periodoinicial"]);
                                                        $objmeta=$objmapind->getMeta();
                                                        $periodo_comp = $periodos[$row['periodo']];
                                        }
                                    
                                        
                                    // Busca o resultado associado
                                    $daores = new ResultadoDAO();
                                    $rows = $daores->buscaresultaperiodometa($codmeta, $mperiodo);
                                    $objresultado=NULL;
                                    foreach ($rows as $row) {
                                       $objmeta->criaResultado($row['Codigo'],$row['meta_atingida'],$row['periodo'],$row['analiseCritica'],$row["acaoPdi"]);
                                       $objresultado = $objmeta->getResultado();
                                       
                                    }
                                    
                                    //Iniciativa
                                    $objini=array();
                                    
                                    $contin=0;
                                                                                       
                                    foreach ($rowini as $r){
                                        $objini[$contin]=new Iniciativa();
                                        $objini[$contin]->setCodiniciativa($r['codIniciativa']);
                                        $objini[$contin]->setNome($r['nome']);
                                        $objini[$contin]->setFinalidade($r['finalidade']);

                                        $contin++;
                                    }
                                    
                                    $daoindini=new IndicIniciativaDAO();
                                    $daorini=new ResultIniciativaDAO();
                                    $objiniresult=NULL;   

                                    for ($i=0;$i<count($objini);$i++){
                                            //echo 'filtros '.$codmapind.','.$objini[$i]->getCodiniciativa();
                                          
                                      
                                            $rows=$daoindini->iniciativaPorMapIndicadorIni($codmapind,$objini[$i]->getCodiniciativa(),$anobase);
                                            foreach  ($rows as $row){
                                                    $objini[$i]->criaIndicIniciativa($row['codindinic'],$objmapind); 
                                               //  echo "<br>passou1";
                                            }
                                            

                                          
                                        
                                           $rows=$daorini->iniciativaPorResultado($objini[$i]->getCodIniciativa(), $sessao->getAnobase(),$mperiodo);
                                          
//                                            echo "i".$i."-".$objini[$i]->getIndicIniciativa()->getCodigo();
                                           $objiniresult=NULL;
                                           foreach  ($rows as $row){
                                                $objiniresult=new ResultIniciativa();
                                                $objiniresult->setCodigo($row['codResultIniciativa']) ;
                                              //  echo "<br>cod r ini ".$row['codResultIniciativa']." ini".;
                                                $objini[$i]->setResultIniciativa($objiniresult);
                                           }  
                                          
                                    }//for
                                                                                  
                                   
            
            
                                    if ($objresultado != NULL) { // UPDATE
                                        $objresultado->setPeriodo($mperiodo);
                                        $objresultado->setAnaliseCritica($analise);
                                        $objresultado->setAcao($acaopdi);
                                        $objresultado->setMetaAtingida(str_replace(',', '.', $resultado));

                                        $daores->altera($objresultado);

                                    } else {
                                        $objresultado = new Resultado();
                                        $objresultado->setPeriodo($mperiodo);
                                        $objresultado->setAnaliseCritica($analise);
                                        $objresultado->setMetaAtingida($resultado);
                                        $objresultado->setAcao($acaopdi);
                                        $objresultado->setMeta($objmeta);            
                                        $daores->insere($objresultado);
                                    } 
                                        
                                                                          
                                       for ($i=0;$i<count($objini);$i++){  
                                            $objiniresult = new ResultIniciativa();
                                            $objiniresult->setCalendario($objcal);
                                            $objiniresult->setIniciativa($objini[$i]);
                                            $objiniresult->setSituacao($situacao[$i]);
                                            $objiniresult->setPfcapacit($capacitacao[$i]);
                                            $objiniresult->setPfrecti($recti[$i]);
                                            $objiniresult->setPfinfraf($infraf[$i]);
                                            $objiniresult->setPfrecf($recf[$i]);
                                            $objiniresult->setPfplanj($plan[$i]);
//                                             $objiniresult[$i]->setPfOutros($pfoutro[$i]);//numero
                                            $objiniresult->setOutros($outro[$i]);
                                            $objiniresult->setPeriodo($mperiodo);
                                            
                                            if ($objini[$i]->getResultIniciativa()!=NULL){
                                            	$objiniresult->setCodigo($objini[$i]->getResultIniciativa()->getCodigo());
                                            	$daorini->altera($objiniresult);
                                            }else{    
                                            	
                                            	$daorini->insere($objiniresult);	
                                            }
                                            
                                            // echo "passou1"; die;
                                        }//for ini
                                                                                
                                        
                                   
                                        
                                    
                                        $msg ="Resultado cadastrado com sucesso!";
                                        ?>
                                         <div class="alert alert-success">
        <button type="button " class="close" data-dismiss="alert">&times;</button>
         <strong><?php print $msg; ?></strong>
    </div>
<?php 
                                        
                                                       
        }
    ///    $daores->fechar();

     
?>




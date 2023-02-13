<?php
require '../../dao/PDOConnectionFactory.php';
require '../../dao/unidadeDAO.php';
require '../../classes/sessao.php';
require '../../classes/Controlador.php';
require '../../classes/unidade.php';

require '../../util/Utils.php';
/* models */
define('MODULO_DIR', (dirname(__FILE__)) . '/../../modulo/');
require_once MODULO_DIR . 'documentopdi/classe/Documento.php';
require_once MODULO_DIR . 'mapaestrategico/classes/Mapa.php';
require_once MODULO_DIR . 'indicadorpdi/classe/Indicador.php';
require_once MODULO_DIR . 'indicadorpdi/classe/Mapaindicador.php';
require_once MODULO_DIR . 'metapdi/classe/Meta.php';
require_once MODULO_DIR . 'resultadopdi/classes/Resultado.php';
require_once MODULO_DIR . 'resultadopdi/classes/ResultIniciativa.php';
require_once MODULO_DIR . 'calendarioPdi/classes/Calendario.php';
require_once MODULO_DIR . 'mapaestrategico/classes/Objetivo.php';
require_once MODULO_DIR . 'documentopdi/classe/Perspectiva.php';
require_once MODULO_DIR . 'iniciativa/classe/IndicIniciativa.php';
require_once MODULO_DIR . 'iniciativa/classe/Iniciativa.php';

/* dao */
require_once MODULO_DIR . 'indicadorpdi/dao/MapaIndicadorDAO.php';
require_once MODULO_DIR . 'documentopdi/dao/DocumentoDAO.php';
require_once MODULO_DIR . 'mapaestrategico/dao/MapaDAO.php';
require_once MODULO_DIR . 'indicadorpdi/dao/IndicadorDAO.php';
require_once MODULO_DIR . 'metapdi/dao/MetaDAO.php';
require_once MODULO_DIR . 'resultadopdi/dao/ResultadoDAO.php';
require_once MODULO_DIR . 'resultadopdi/dao/ResultIniciativaDAO.php';
require_once MODULO_DIR . 'calendarioPdi/dao/CalendarioDAO.php';
require_once MODULO_DIR . 'mapaestrategico/dao/ObjetivoDAO.php';
require_once MODULO_DIR . 'documentopdi/dao/PerspectivaDAO.php';
require_once MODULO_DIR . 'iniciativa/dao/IndicIniciativaDAO.php';
require_once MODULO_DIR . 'iniciativa/dao/IniciativaDAO.php';

/* fim */


session_start();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
if (!isset($sessao)) {
    exit();
}      

if (!$aplicacoes[29]) {
    print ("O usuário não possui permissão para acessar este aplicativo.");
    exit();
}$pfoutros
?>
<?php
$outros=NULL;
$pfoutros=NULL;

$outros=strip_tags($_POST['outro']);//numero  
$pfoutros=$_POST['pfOutros'];

$anobase = $sessao->getAnobase();
$c=new Controlador();
$unidade=new Unidade();
$c->getProfile($sessao->getGrupo())?$unidade->setCodunidade($sessao->getCodUnidsel()):$unidade->setCodunidade($sessao->getCodUnidade());
$codini=$_POST['codini'];
$situacao=$_POST['sit'];
$capacitacao=$_POST['capacitacao'];
$recti=$_POST['recti'];      
$infraf=$_POST['infraf'];
$recf=$_POST['recf'];
$plan=$_POST['plan'];
$acao = $_POST["salvar"];
if ($acao=="U") {
    $codigo=$_POST['codresultinic'];
}
$analise = strip_tags($_POST['critica']); // análise crítica
$periodo = $_POST['periodoini']; // período de referência
$codmeta = addslashes($_POST['codmeta']); // código da meta ------
$codmapind = addslashes($_POST['codindicador']); // código do indicador
$coddoc=$_POST["coddoc"];
$objdoc=new Documento();
$objdoc->setCodigo($coddoc);
$daocal=new CalendarioDAO();
$rows=$daocal->verificaPrazoCalendarioDoDocumento($anobase,$objdoc->getCodigo());

$habilita=false;
foreach ($rows as $row) {
      $habilita=$row['habilita']=="H"?true:false;
      $objcal=new Calendario();
      $objcal->setCodigo($row['codCalendario']);
      $objcal->setUnidade(NULL);
      $objcal->setAnogestao($row['anoGestao']);
   // echo "calendario";
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
                   // echo "meta".$row["meta"]." ".$row["periodo"]."Anual ".$row["Codigo"].'</br>';
                    $objmapind->criaMeta($row["Codigo"],$objcal,$row["periodo"],$row["meta"],$row["metrica"],$row["cumulativo"]);
                    $objmeta=$objmapind->getMeta();
                    $periodo_comp = $periodos[$row['periodo']];
    }

// Busca o resultado associado
$daores = new ResultadoDAO();
$rows = $daores->buscaresultaperiodometa($codmeta, $periodo);
$objresultado=NULL;
foreach ($rows as $row) {
   $objmeta->criaResultado($row['Codigo'],$row['meta_atingida'],$row['periodo'],$row['analiseCritica'],$row["acaoPdi"]);
   $objresultado = $objmeta->getResultado();
}
//Iniciativa
$daoini=new IndicIniciativaDAO();
$rows=$daoini->iniciativaPorMapIndicadorIni($codmapind,$codini);
foreach  ($rows as $row){
    if ($codindini=$row['codindinic']){
        $objini=new Iniciativa();
        $objini->setCodiniciativa($row['codIniciativa']);
        $objini->setNome($row['nome']);
        $objini->setFinalidade($row['finalidade']);
        $objini->criaIndicIniciativa($row['codindinic'],$objmapind);  
    }
}
$objiniresult=NULL;   
$daorini=new ResultIniciativaDAO();
//echo $objini->getIndicIniciativa()->getCodigo().",".$sessao->getAnobase()."-".$periodo;
$rows=$daorini->iniciativaPorResultado($objini->getIndicIniciativa()->getCodigo(), $sessao->getAnobase(),$periodo);
foreach  ($rows as $row){
    $objiniresult=new ResultIniciativa();
    if ($codigo==$row['codResultIniciativa']){
       $objiniresult->setCodigo($codigo) ;
    }
}   

$erro = "";

if ($objresultado==NULL){
    $erro="Resultado do Indicador associado a esta iniciativa ainda não foi informado. Favor, informá-lo.";
}else if ($situacao == "0") {
    $erro = "Por favor, informe situação válida.";
}  else if ($objmeta->getMapaindicador()->getIndicador()->getCodigo() == NULL) {
    $erro = "O indicador não existe ou não pertence à unidade!";
}  else if (!is_numeric($capacitacao)){
    $erro= "Informe um valor na escala de 0 a 5 para o fator capacitação!";
}else if  (!is_numeric($recti)){
    $erro= "Informe um valor na escala de 0 a 5 para o fator Recurso de TI!";
}else if (!is_numeric($infraf)){
    $erro= "Informe um valor na escala de 0 a 5 para o fator Infraestrutura físcia!";
}else if (!is_numeric($recf)){
    $erro= "Informe um valor na escala de 0 a 5 para o fator Recursos financeiros!";
}else if (!is_numeric($plan)){
    $erro= "Informe um valor na escala de 0 a 5 para o fator Planejamento!";
}else if (!is_numeric($pfoutros) && $outros!=""){
    $erro= "Informe um valor na escala de 0 a 5 para o fator Outros, que foi preenchido!";
}else if (is_numeric($pfoutros) && $outros==""){
    $erro= "Ao preencher o grau de influência de Outros fatores, informe que outros fatores são estes!";
}else if ($analise == "") {
    $erro = "Por favor, preencha o campo para a análise crítica.";
}
 if ($erro != ""): ?>
    <div class="alert alert-danger">
        <button type="button " class="close" data-dismiss="alert">&times;</button>
        <?php print $erro; ?>
    </div>
    <?php
else:
    if ($acao == "U" && $objiniresult != NULL) { // UPDATE
        $objiniresult->setCalendario($objcal);
		$objiniresult->setIndic_iniciativa($objini->getIndicIniciativa());
        $objiniresult->setSituacao($situacao);
		$objiniresult->setPfcapacit($capacitacao);
        $objiniresult->setPfrecti($recti);
		$objiniresult->setPfinfraf($infraf);
		$objiniresult->setPfrecf($recf);
        $objiniresult->setPfplanj($plan);
        $objiniresult->setPfOutros($pfoutros);//numero
		$objiniresult->setOutros($outros);
        $objiniresult->setAnalisecritica($analise);
        $objiniresult->setPeriodo($periodo);
          /*echo  $objiniresult->getCalendario()->getCodigo()."-".
            $objiniresult->getIndic_iniciativa()->getCodigo()."-".
             $objiniresult->getSituacao()."-".
            $objiniresult->getPfcapacit()."-".
             $objiniresult->getPfrecti()."-".
             $objiniresult->getPfinfraf()."-".
             $objiniresult->getPfrecf()."-".
             $objiniresult->getPfplanj()."-".
             $objiniresult->getPfOutros()."-".
             $objiniresult->getAnalisecritica()."-".
             $objiniresult->getPeriodo()."-".
             $objiniresult->getOutros()."-".
             $objiniresult->getCodigo();*/
        $daorini->altera($objiniresult);
        $msg = "Resultado atualizado com sucesso!";
    } else if ($acao == "I" && $objiniresult == NULL) {
        $objiniresult = new ResultIniciativa();
        $objiniresult->setCalendario($objcal);
		$objiniresult->setIndic_iniciativa($objini->getIndicIniciativa());
        $objiniresult->setSituacao($situacao);
		$objiniresult->setPfcapacit($capacitacao);
        $objiniresult->setPfrecti($recti);
		$objiniresult->setPfinfraf($infraf);
		$objiniresult->setPfrecf($recf);
        $objiniresult->setPfplanj($plan);
        $objiniresult->setPfOutros($pfoutros);//numero
		$objiniresult->setOutros($outros);
        $objiniresult->setAnalisecritica($analise);
        $objiniresult->setPeriodo($periodo);
                
       // echo var_dump($objiniresult);

        $daorini->insere($objiniresult);
        $msg = "Resultado cadastrado com sucesso!";
    }
    ?>
    <div class="alert alert-success">
        <button type="button " class="close" data-dismiss="alert">&times;</button>
        

        <strong><?php print $msg; ?></strong>
    </div>

    <?php
    $daores->fechar();
endif;
?>
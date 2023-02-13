<?php
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/sessao.php';

require_once "../../modulo/laborv3/dao/laboratorioDAO.php";
require_once '../../classes/unidade.php';
require_once('../../modulo/laborv3/classes/laboratorio.php');
require_once('../../modulo/laborv3/classes/tplaboratorio.php');
require_once "../../util/Utils.php";

//$sessao = $_SESSION["sessao"];
//$aplicacoes = $sessao->getAplicacoes();
//if (!$aplicacoes[7]) {
//    exit();
    
//}
//ob_end_flush();

//Exibir Erros
/*ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);*/
//Importa bibliotecas


//echo var_dump($_POST);die;
$nomeunidade = $_POST["nomeUnidade"];
$codunidade = $_POST["codUnidade"];
$anobase = $_POST["anoBase"];
$daolab = new LaboratorioDAO();
$tipolab = new Tplaboratorio();
$tipolab->setCodigo($_POST["cat"]);
$unidade = new Unidade();
$unidade->setCodUnidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$so = $_POST["so"];

$erro="";
$sucesso="";
$operacao = $_POST["operacao"];
if ($operacao != "I"){
    $areaanterior = addslashes(str_replace(",", ".",  $_POST["areaAnterior"]));
}
$area = addslashes(str_replace(",", ".",  $_POST["area"]));
/*if (isset($_POST["aulapratica"])) {
	$labensino = "S";
	$resposta = $_POST["resposta"];
	if ( $resposta=="" || $resposta==null) {
		$erro="Erro na resposta para a pergunta sobre aulas prÃ¡ticas.";
	}	
}else{
	$labensino = "N";
	$resposta = null;
}
*/
if (isset($_POST["cabo"])) {
	$cabo = "S";
} else {
	$cabo = "N";
}
$capacidade = $_POST["capacidade"];
$nestacoes = $_POST["nestacoes"];
$nomelab = addslashes(strtoupper($_POST["nome"]));
$siglalab = addslashes(strtoupper($_POST["sigla"]));
$locallab = addslashes(strtoupper($_POST["local"]));
$infoadd=addslashes(strtoupper($_POST["infoad"]));
$sugestao=addslashes(strtoupper($_POST["sugestao"]));
$situacao= ($operacao == "I")?'V':$_POST["situacao"];
if (isset($_POST["situacao2"])){
    $situacao='D';
}else if ($situacao=='C') {
    $situacao='V';
   
}else if ($situacao=='V') {
    $situacao='V';
} else    {
    $situacao='A';
}


if (($situacao=="V") && (($codunidade==981) || ($codunidade==973))){//NTPC EA
    $situacao='A';
}

$justificativa = $_POST['justificativa'];

//echo $tipolab->getCodigo()."xx".$nomelab;die;
 if ($_POST["cat"]=="0"){	$erro="Categoria é campo obrigatório!";
}else if ($nomelab == "" ){
	$erro="Nome do laboratório é obrigatório!";
}else if ($capacidade==""){
	$erro="Capacidade do laboratório Ã© obrigatória e deve ser um número!";
}else if ($infoadd==""){
    $erro="Se você não quiser sugerir campos adicionais, preencha o que campo com a frase: Os campos deste formulário são suficientes!";
}else if ($sugestao==""){
    $erro="Se você não quiser sugerir uma nova categoria, preencha o que campo com a frase: A categoria selecionada para este laboratório a representa!";
}else if ($operacao == "I" && empty($_POST["area"]) ){
		$erro="Área é um campo obrigatório!";
}else if ($operacao == "A" && !empty($_POST["area"]) && !empty($_POST["areaAnterior"]) && empty($justificativa) ){
		$erro="Ao alterar a área, preencha o campo justificativa!";
}else if  (!is_string($locallab)){
		$erro="Local do laboratório é inválido!";
}else if  (!is_string($so)){
		$erro="Sistema operacional do laboratório é inválido!";
}else if  ($nestacoes!='' && !is_numeric($nestacoes)){
		$erro="Número de computadores do laboratório é inválido!";
}else{
    //Verifica se nome do laborÃ¡tÃ³rio jÃ¡ existe
    if ($operacao == "I"){
        $parecidos=$daolab->buscaNomeLaboratorio($nomelab,$anobase,0);
        
        foreach ($parecidos as $p){
            $erro= '<ul "margin-left: 80%;"><li>'.$p["Nome"].' - '.$p["NomeUnidade"].'</li></ul><br>';
        }
        if ($erro!="")   {
$erro.= "<p>Como exibido acima, o nome de laboratório informado JÁ EXISTE na UFPA!</p> <br><br>
<p>Verifique se não há duplicação, caso realmente seja um novo laboratório
      acrescente ao nome a sigla da unidade ou uma numeraçãoo
      para diferenciá-lo.</p>";
        }
    } else{
        
        $codlab = $_POST["codlab"];
        
        $parecidos=$daolab->buscaNomeLabAlteracao($nomelab,$codlab,$anobase);
        foreach ($parecidos as $p){
            
           $erro=$p["Nome"].' - '.$p["NomeUnidade"].'</li>';
        }
        if ($erro!="")   {
        $erro.= "<p>Como exibido acima, o nome de laboratório informado JÁ EXISTE na UFPA!</p> <br><br>
<p>Verifique se não há duplicação, caso realmente seja um novo laboratório
      acrescente ao nome a sigla da unidade ou uma numeração
      para diferenciá-lo.</p>";
        }
    }
        
        
        if ($erro==""){
          
            //IF de inserÃ§Ã£o de laboratÃ³rio
            if ($operacao == "I"){
                
                $lab = $unidade->criaLabv2(null, $nomelab, $capacidade, $siglalab, $area, $cabo,
                    $locallab, $so, $nestacoes, $situacao, $anobase, null);
                
                $daolab->insere($lab);
                
                $rowCod = $daolab->ultimoCodLab();
                foreach ($rowCod as $row){
                    $codLab =  $row['cod'];
                    $lab->setCodlaboratorio($codLab);
                }
                if (!empty($area)){
                    $daolab->insereArea($codLab, $anobase,$area,NULL);
                }
                
                $daolab->insereLabclasse($tipolab->getCodigo(),$codLab,$anobase,$sugestao,$infoadd);
                
                
                
                $sucesso="Operação realizada com sucesso!";
                
            } elseif ($operacao == "A") {//Alterar dados laboratÃ³rio
                
            //    $situacao = $_POST["situacao"];

                $anodesativacao = '';

                if ($situacao == "D") {
                    $anodesativacao = $anobase;
                }
                $codlab = $_POST["codlab"];
                
                                
                if ($codlab != "" && is_numeric($codlab)) {
                    
                  
                    
                    $lab = $unidade->criaLabv2($codlab,  $nomelab, $capacidade, $siglalab,  $area,  
                        $cabo, $locallab, $so, $nestacoes, $situacao, $anoativacao, $anodesativacao);
                    $daolab->altera($lab);
                    
                    // echo $lab->getCodlaboratorio()."/".$anobase."/".$justificativa."/";
                    if ($_POST['anoBase']>=2021){
                        $daolab->alteraLabclasse($tipolab->getCodigo(), $codlab, $anobase, $sugestao, $infoadd);
                        $tipolab->setCodigo(NULL);
                    }
                   
                    
                    //verifica se existe area cadastrada para o lab no ano
                    $qrows = $daolab->qtdAreaLabAno($lab->getCodlaboratorio(), $anobase);
                    foreach ($qrows as $r){
                        $qtdArea=$r['qtd'];
                    }
                    
                    if($qtdArea==0){
                        if (!empty($_POST["area"]) && ($_POST["areaAnterior"] != $_POST["area"])){
                            $daolab->insereArea($lab->getCodlaboratorio(),$anobase,$area, $justificativa);
                        }elseif (empty($_POST["area"]) && !empty($_POST["areaAnterior"])) {
                            # code...
                        }
                        
                    }else{
                        if (!empty($_POST["area"]) && ($_POST["areaAnterior"] != $_POST["area"])){
                            $daolab->alteraArea($lab->getCodlaboratorio(),$anobase,$area, $justificativa);
                        }elseif (empty($_POST["area"]) && !empty($_POST["areaAnterior"])) {
                            # code...
                        }
                        
                        
                    }
                    
                    
                    //echo $teste;
                }
                $sucesso="Alteração realizada com sucesso!";
            }
            $daolab->fechar();
            
            
            
                
        }else{
            
             ' </ul><br>
      Verifique se não há duplicação, caso realmente seja um novo laboratório
      acrescente ao nome a sigla da unidade ou uma numeração
      para diferenciá-lo.<br>';
        }
    }
    
 
?>

<?php 


if ($sucesso != ""): ?>
    <div id="success" class="alert alert-success" role="alert">
        <img src="webroot/img/accepted.png" width="30" height="30"/><?php print $sucesso; ?>
    
        <span class="plus"></span><a href="<?php echo Utils::createLink('laborv3', 'conslabcurso',array('codlab'=>$lab->getCodlaboratorio())); ?>">Vincular cursos</a>      
        
    </div>
    <?php else : ?>
     <div class="erro alert alert-danger" role="alert">
        <img src="webroot/img/error.png" width="30" height="30"/>
    <?php print $erro; ?>
    </div>
<?php endif; ?>


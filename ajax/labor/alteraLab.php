<?php
//ob_end_flush();

//Exibir Erros
/*ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);*/
session_start();
//Importa bibliotecas
require '../../dao/PDOConnectionFactory.php';
require_once "../../modulo/labor/dao/laboratorioDAO.php";
require_once '../../classes/unidade.php';
require_once('../../modulo/labor/classes/laboratorio.php');
require_once('../../modulo/labor/classes/tplaboratorio.php');
require_once "../../util/Utils.php";

//echo var_dump($_POST);die;
$nomeunidade = $_POST["nomeUnidade"];
$codunidade = $_POST["codUnidade"];
$anobase = $_POST["anoBase"];
$daolab = new LaboratorioDAO();
$tipolab = new Tplaboratorio();
$tipolab->setCodigo($_POST["tlab"]);
$unidade = new Unidade();
$unidade->setCodUnidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$so = $_POST["so"];

$erro="";
$sucesso="";
$areaanterior = addslashes(str_replace(",", ".",  $_POST["areaAnterior"]));
$area = addslashes(str_replace(",", ".",  $_POST["area"]));
if (isset($_POST["aulapratica"])) {
	$labensino = "S";
	$resposta = $_POST["resposta"];
	if ( $resposta=="" || $resposta==null) {
		$erro="Erro na resposta para a pergunta sobre aulas práticas.";
	}	
}else{
	$labensino = "N";
	$resposta = null;
}

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
$operacao = $_POST["operacao"];
$justificativa = $_POST['justificativa'];

//echo $tipolab->getCodigo()."xx".$nomelab;die;
if (isset($_POST["aulapratica"])  && ( $resposta=="" || $resposta==null)    ) {
	$erro="Erro na resposta para a pergunta sobre aulas práticas.";
}else if ($_POST["cat"]=="0"){
	$erro="Categoria é campo obrigatório!";
}else if ($tipolab->getCodigo()=="0"){
	$erro="Subcategoria é campo obrigatório!";
}else if ($nomelab == "" ){
	$erro="Nome do laboratório é obrigatório!";
}else if ($capacidade==""){
	$erro="Capacidade do laboratório é obrigatória e deve ser um número!";
}else if ($operacao == "I" && empty($_POST["area"]) ){
		$erro="Área é um campo obrigatório!";
}else if ($operacao == "A" && !empty($_POST["area"]) && !empty($_POST["areaAnterior"]) && empty($justificativa) ){
		$erro="Ao alterar a área, preencha o campo justificativa!";
}else if  (!is_string($locallab)){
		$erro="Local do laboratório é inválido!";
}else if  (!is_string($so)){
		$erro="Sistema operacional do laboratório é inválido!";
}else if  ($nestacoes!='' && !is_numeric($nestacoes)){
		$erro="Número de estações do laboratório é inválido!";
}else{

    //Verifica se nome do laborátório já existe
    if ($operacao == "I"){
        
        $parecidos=$daolab->buscaNomeLaboratorio($nomelab,$anobase);
        foreach ($parecidos as $p){
            
            $erro=
            ' Como exibido abaixo, o nome de laboratório informado JÁ EXISTE na UFPA! <br><br>
      <ul "margin-left: 80%;"><li>'.$p["Nome"].' - '.$p["NomeUnidade"].'</li></ul><br>
      Verifique se não há duplicação, caso realmente seja um novo laboratório
      acrescente ao nome a sigla da unidade ou uma numeração
      para diferenciá-lo.<br>';
            
        }
    } else{
        $codlab = $_POST["codlab"];
        $erro1=' Como exibido abaixo, o nome de laboratório informado JÁ EXISTE na UFPA! <br><br>'.
        '<ul "margin-left: 80%;"><li>';
        $parecidos=$daolab->buscaNomeLabAlteracao($nomelab,$codlab,$anobase);
        foreach ($parecidos as $p){
            
           $erro=$p["Nome"].' - '.$p["NomeUnidade"].'</li>';
        }

        
        
        if ($erro==""){
          
            //IF de inserção de laboratório
            if ($operacao == "I"){
                $lab = $unidade->criaLab(null, $tipolab, $nomelab, $capacidade, $siglalab, $labensino, $area, $resposta, $cabo, $locallab, $so, $nestacoes, "A", $anobase, null);
                $daolab->insere($lab);
                
                if (!empty($area)){
                    $rowCod = $daolab->ultimoCodLab();
                    foreach ($rowCod as $row){
                        $codLab =  $row['cod'];
                        $lab->setCodlaboratorio($codLab);
                    }
                    $daolab->insereArea($codLab, $anobase,$area,NULL);
                }
                
                $sucesso="Operação realizada com sucesso!";
                
            } elseif ($operacao == "A") {//Alterar dados laboratório
                $situacao = $_POST["situacao"];
                
                if ($situacao == "A") {//Alterar dados laboratório
                    $anoativacao = null;
                    $anodesativacao = null;
                } else {
                    $anoativacao = null;
                    $anodesativacao = $anobase;
                }
                $codlab = $_POST["codlab"];
                
                
                
                
                if ($codlab != "" && is_numeric($codlab)) {
                    $lab = $unidade->criaLab($codlab, $tipolab, $nomelab, $capacidade, $siglalab, $labensino, $area, $resposta, $cabo, $locallab, $so, $nestacoes, $situacao, $anoativacao, $anodesativacao);
                    $daolab->altera($lab);
                    
                    // echo $lab->getCodlaboratorio()."/".$anobase."/".$justificativa."/";
                    
                    
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
            $erro=$erro1.$erro.
             ' </ul><br>
      Verifique se não há duplicação, caso realmente seja um novo laboratório
      acrescente ao nome a sigla da unidade ou uma numeração
      para diferenciá-lo.<br>';
        }
    }
    
} 
?>

<?php 


if ($sucesso != ""): ?>
    <div id="success" class="alert alert-success" role="alert">
        <img src="webroot/img/accepted.png" width="30" height="30"/><?php print $sucesso; ?>
        <span class="plus"></span><a href="<?php echo Utils::createLink('labor', 'conslabcurso',array('codlab'=>$lab->getCodlaboratorio())); ?>">Vincular cursos</a>     
        
    </div>
    <?php else : ?>
     <div class="erro alert alert-danger" role="alert">
        <img src="webroot/img/error.png" width="30" height="30"/>
    <?php print $erro; ?>
    </div>
<?php endif; ?>


<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

//set_include_path(';../../includes');

require_once('../../classes/sessao.php');
require_once('../../dao/PDOConnectionFactory.php');
require_once('../../classes/servico.php');
require_once('../../classes/procedimento.php');
require_once('../../classes/servproced.php');
require_once('../../dao/servprocDAO.php');
require_once('../../modulo/prodsaude4/classes/local.php');
require_once('../../dao/psaudemensalDAO.php');
require_once('../../classes/psaudemensal.php');
require_once('../../classes/unidade.php');
require_once('../../dao/unidadeDAO.php');

require_once( '../../util/Utils.php');

session_start();
$sessao = $_SESSION['sessao'];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodUnidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[14]) {
    echo "Você não tem permissão para acessar este formulário"; die;
}
$operacao = $_POST["operacao"];
$mes = $_POST["mes"];
$codsubunidade = $_POST["subunidade"];
if ($sessao->getUnidadeResponsavel()>1){
    $udao= new UnidadeDAO();
    $rows=$udao->buscaidunidade($sessao->getUnidadeResponsavel());
    foreach ($rows as $r){
        $codunidade=$r['CodUnidade'];// quandoo usuario for local
    }
    
}
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$erro = "";

//Valida campos

//echo $codsubunidade;
switch ($codunidade) {
    case 270:
        $codlocal = $_POST["local"];
        $ndisc = $_POST["ndisc"];
        $ndoc = $_POST["ndoc"];
        $npesq = $_POST["npesq"];
        $nproc = $_POST["nproc"];
      //  echo $codlocal.",".$ndisc.",".$ndoc.",".$npesq.",".$nproc;die;
        $nexames = 0;
        if ( $ndoc == "" || $ndisc == "" ||  $npesq == "" || $codlocal == 0) {
            $erro = "Todos os campos são obrigatórios";
        }
        $npaten = $_POST["npaten"];
        if ( $npaten == "") {
            $erro = "Número de pessoas atendidas inválido!";
        }
        break;
    case 202:
        
        $ndisc = 0;
        $ndoc = 0;
        $npesq = 0;
        $codlocal = 7000;
        $nexames = 0;
        $nproc=0;
        $npaten = $_POST["npaten"];
        if ( $npaten == "") {
            $erro = "Número de pacientes inválido!";
        }
        break;
    case 1644:
        $ndisc = 0;
        $ndoc = 0;
        $npesq = 0;
         $nproc=0;
        $codlocal = 0;
        
        $npaten = 0;
        $nexames = $_POST["nexames"];
        if (!is_numeric($nexames) || $nexames == "") {
            $erro = "Número de exames inválido!";
        }
        break;
}
$codprocedimento = $_POST["procedimento"];
$codservico = $_POST["servico"];

if ( $codservico > 0
        &&  $codsubunidade > 0
        &&  $codprocedimento > 0
        && $mes>0 && $erro=="") {
    $local = new Local();
    $local->setCodigo($codlocal);
   
    $unidade->criaSubunidade($codsubunidade, null, null);
    $procedimento = new Procedimento();
    $procedimento->setCodigo($codprocedimento);
    $unidade->getSubunidade()->criaServico($codservico, null);
    $daosp = new ServprocDAO();
    if ($anobase==2018 and $codunidade==270){
    	    $rows = $daosp->buscacodservproced2018($codservico,  $codprocedimento);
    	
    }else{
    	    $rows = $daosp->buscacodservproced($codservico, $codsubunidade, $codprocedimento);
    	
    }
   
    foreach ($rows as $row) {
        $codservproced = $row['CodServProc'];
    }
    $unidade->getSubunidade()->getServico()->criaSp($codservproced, $procedimento);
    $dao = new PsaudemensalDAO();
   
    if ($operacao == "I") {
    	
        $passou = False;
        if ($codunidade == 270) {
        	
        	$rows = $dao->BuscaPsaudemensal($anobase, $mes, $codlocal, $codservico, $codprocedimento);
        	
        } else {        	
            $rows = $dao->BuscaPsaudemensal1($anobase, $mes, $codservico, $codprocedimento);
        }
        
        foreach ($rows as $row) {
            $codigo = $row['Codigo'];
            $passou = True;
        }
        
        
      //  echo $passou.','.$codsubunidade.','.$codprocedimento.','.$mes.','.$codlocal;
       
        
        if ($passou) {  
        	$unidade->getSubunidade()->getServico()->getSp()->criaPsaude($codigo, $procedimento, $local, $anobase, $mes, $ndoc, $ndisc, $npesq, $npaten, $nproc, $nexames);
            if ($codunidade == 270)
                $dao->altera($unidade->getSubunidade()->getServico()->getSp());
            else
                $dao->altera1($unidade->getSubunidade()->getServico()->getSp());
        }else {
            $unidade->getSubunidade()->getServico()->getSp()->criaPsaude(null, $procedimento, $local, $anobase, $mes, $ndoc, $ndisc, $npesq, $npaten, $nproc, $nexames);
            if($codunidade == 270){
            	
                $dao->insere($unidade->getSubunidade()->getServico()->getSp());
            }else{
            	
            	
                $dao->insere1($unidade->getSubunidade()->getServico()->getSp());
                          //  	echo "teste";die;
                
            }
                        
            
        }
        $string="Operação realizada com sucesso!";
    }
    elseif ($operacao == "A") {
        $codigo = $_POST["codigo"];
        if (is_numeric($codigo) && $codigo != "") {
            if ($codunidade == 270) {
                $rows = $dao->BuscaPsaudemensal($anobase, $mes, $codlocal, $codservico, $codprocedimento);
            } else {
                $rows = $dao->BuscaPsaudemensal1($anobase, $mes, $codservico, $codprocedimento);
            }
            foreach ($rows as $row) {
                $codigo = $row['Codigo'];
                $passou = true;
            }
            $unidade->getSubunidade()->getServico()->getSp()->criaPsaude($codigo, $procedimento, $local, $anobase, $mes, $ndoc, $ndisc, $npesq, $npaten, $nproc, $nexames);
            if ($codunidade == 270)
                $dao->altera($unidade->getSubunidade()->getServico()->getSp());
            else
                $dao->altera1($unidade->getSubunidade()->getServico()->getSp());
            $string="Alteração realizada com sucesso";
        }else {
            $erro = "Código inválido!Contate o administrador.";
        }
    }

    $dao->fechar();
} else {
    $erro = "Todos os campos devem ser preenchidos!";
}

//ob_end_flush();
?>
<?php if ($erro!=""): ?>
    <div class="erro">
        <img src="webroot/img/error.png" width="30" height="30"/>
        <?php print $erro; ?>
    </div>
<?php else : ?>
    <div id="success">
        <img src="webroot/img/accepted.png" width="30" height="30"/>
        <?php print $string;?>
         <span class="plus"></span>     
    </div>
<?php endif; ?>

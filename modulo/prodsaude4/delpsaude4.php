<?php
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[14]) {
    header("Location:index.php");
} 
//$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
//$anobase = $sessao->getAnobase();
//require_once('../../dao/PDOConnectionFactory.php');
// var_dump($usuario);
//require_once('../../dao/psaudemensalDAO.php');
//require_once('../../classes/psaudemensal.php');
//require_once('../../classes/unidade.php');
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$codigo = $_POST["codigo"];
if (is_numeric($codigo) && $codigo != "") {
    $dao = new PsaudemensalDAO();
    $p = new Psaudemensal();
    /*if ($codunidade == 270) {
        $rows = $dao->psaudecodigo($codigo);
    } else {
        $rows = $dao->psaudecodigo1($codigo);
    }
    foreach ($rows as $row) {
        $codsubunidade = $row['codsubunidade'];
        $codservico = $row['codservico'];
        $codprocedimento = $row['codproc'];
        $codlocal = $row['codlocal'];
    }*/
    $p->setCodigo($codigo);
    $retorno=$dao->deleta($p);
    if ($retorno=="ok"){?>
    
    <div id="success">
        <img src="webroot/img/accepted.png" width="30" height="30"/>
        <?php print "Operação realizada com sucesso!";?>
         <span class="plus"></span>     
    </div>

    <?php }else{?>
   <div class="erro">
			<img src="webroot/img/error.png" width="30" height="30"/>
			<?php print $retorno; ?>
    </div>
        
  <?php   }
    
    
 
    
    
    
//	exit();
}
//ob_end_flush();
?>




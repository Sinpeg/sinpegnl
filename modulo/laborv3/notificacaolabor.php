<?php

//session_start(); - Sessão já inicalizada	

$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[7]) {
exit();}
else {
 $sessao = $_SESSION["sessao"];
// $nomeunidade = $sessao->getNomeunidade();
 $codunidade = $sessao->getCodunidade();
 require_once('dao/laboratorioDAO.php');
 require_once('classes/laboratorio.php');
 require_once('dao/labcursoDAO.php');
}
$anobase=$sessao->getAnobase();

$daolab = new LaboratorioDAO();
$rows = $daolab->buscaLaboratoriosUnidade($codunidade,$anobase);
$daolabcurso = new LabcursoDAO();
$cslab = 0;
foreach ($rows as $row) {
    $rows1 = $daolabcurso->buscaCursosLaboratorio1($row['CodLaboratorio'],$anobase);
    if ($rows1->rowCount()==0) {
         $cslab++;
    }
}
?>
<?php if ($cslab>1) { ?>
<div class="ui-widget">
 <div class="ui-state-highlight" >
 <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
 <strong>Importante!</strong>
 </p>
 <p>Há <strong><?php echo $cslab; ?></strong> laboratórios que não estão vinculados a cursos de graduação.
  Verifique se eles atendem estes cursos, caso atendam, devem 
  ser vinculados, a fim de que sejam informados no Censo da Educação Superior.
Para informar tais cursos, clique no ícone Alterar. </p>
 
 <!-- <a href="?modulo=labor&acao=conslabdesv">Clique aqui.</a> -->
 </p>
 </div>
</div>
<?php } ?>
<?php if ($cslab==1) { ?>
<div class="ui-widget">
 <div class="ui-state-highlight">
 <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
 <strong>Importante!</strong>
 </p>
 <p>Há <strong><?php echo $cslab; ?></strong> laboratório que não possui cursos vinculados a serem informados no Censo da Educação Superior.</p>
 Para vincular <a href="?modulo=labor&acao=conslabdesv">Clique aqui.</a>
 </p>
 </div>
</div>
<?php } ?>




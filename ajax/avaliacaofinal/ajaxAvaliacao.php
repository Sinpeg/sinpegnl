<?php

require '../../classes/unidade.php';
require '../../dao/PDOConnectionFactory.php';
require '../../classes/sessao.php';
require '../../classes/Controlador.php';
require '../../modulo/avaliacao/dao/AvaliacaofinalDAO.php';
require '../../modulo/calendarioPdi/dao/CalendarioDAO.php';
require '../../modulo/calendarioPdi/classes/Calendario.php';
require '../../modulo/avaliacao/classe/Avaliacaofinal.php';
require '../../modulo/documentopdi/dao/DocumentoDAO.php';
require '../../modulo/documentopdi/classe/Documento.php';
require '../../util/Utils.php';


session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
$codunidade = $sessao->getCodUnidade();
$unidade=NULL;
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$c=new Controlador();

////////////////////////////////////////////////////////////////////////////////
$codDocumento = addslashes($_POST['codDocumento']);
$avaliacao = addslashes($_POST['avaliacaofinal']);
if (isset( $_POST['rat'])){
$RAT = addslashes($_POST['rat']);
}else $RAT = 0;

$periodo=$_POST['aperiodo'];
$situacao = $_POST['situacao'];

if (!$aplicacoes[36] && !$aplicacoes[40]) {
	print "Erro ao acessar esta aplicação";
	exit();
}
$erro = NULL;
$unidadePlano=NULL;


if ($c->getProfile($sessao->getGrupo()) && is_null($nomeunidadeselecionada) && $coddoc==0){
	$erro="Selecione a unidade";
}else if (!preg_match('/^([1-9][0-9]*)$/', $codDocumento) && !isset($codDocumento)) {
	$erro = "Selecione o documento";
} else if ($avaliacao == "") {
	$erro = "Preencha o campo avaliação";
} else if ($periodo==0){
    	$erro = "Informe o periodo";

}
	//verificar periodo se ja existe
	$objavaliacaofinal = new Avaliacaofinal();
	$daoavaliacaofinal = new AvaliacaofinalDAO();
	$daocalendario = new CalendarioDAO();
	$arraycal = $daocalendario->buscaCalendarSomenteioPorAnoBase($sessao->getAnoBase())->fetch();
	$objdoc=new Documento();
	$objdoc->setCodigo($codDocumento);
	$objcal = new Calendario();
	$objcal->setCodigo($arraycal['codCalendario']);
	
	
	$objavaliacaofinal->setDocumento($objdoc);
	$objavaliacaofinal->setCalendario($objcal);
	$objavaliacaofinal->setAvaliacao($avaliacao);
	$objavaliacaofinal->setPeriodo($periodo);
	$objavaliacaofinal->setRat($RAT);
	$codAvaliacao=NULL;
$rows = $daoavaliacaofinal->buscaAvalDP($objdoc->getCodigo(), $objcal->getCodigo(),$periodo);
  if ($rows!=NULL){
    foreach ($rows as $r){
        $codAvaliacao=$r['codigo'];
    }
  }
if ($erro==NULL){
	
	if ($codAvaliacao==NULL) {
		$daoavaliacaofinal->insere($objavaliacaofinal);
		$string = "Avaliação realizada com sucesso!";

	} else if($codAvaliacao>0) {
		
		$codAvaliacao = $codAvaliacao;
		$objavaliacaofinal->setCodigo($codAvaliacao);
		$daoavaliacaofinal->altera($objavaliacaofinal);
		$string = "Cadastro atualizado com sucesso!";

	}
    
	$daoavaliacaofinal->fechar();?>
	<div id="success">
        <img src="webroot/img/accepted.png" width="30" height="30"/><?php print $string; ?>
    </div> 
<?php }
if ($erro != NULL){ ?>
    <div class="erro">
        <img src="webroot/img/error.png" width="30" height="30"/>
        <?php print $erro; ?>
    </div>
 <?php }?>

<?php
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
$RAT = addslashes($_POST['rat']);
$periodo=$_POST['aperiodo'];

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
$rows = $daoavaliacaofinal->buscaAvalDP($objdoc->getCodigo(), $anobase,$periodo);

if ($rows!=NULL){
	echo "passou";die;
	foreach ($rows as $r){
		$codAvaliacao=$r['codigo'];
	}
}

if (!$erro){
	if ($codAvaliacao==NULL) {
		$daoavaliacaofinal->insere($objavaliacaofinal);
		//$string = "Cadastro realizado com sucesso!";
              echo "passou1";die;

	} else if($codAvaliacao>0) {
		
		$codAvaliacao = $codAvaliacao['codigo'];
		$objavaliacaofinal->setCodigo($codAvaliacao);
		$daoavaliacaofinal->altera($objavaliacaofinal);
		//$string = "Cadastro atualizado com sucesso!";
              echo "passou2";die;

	}
	$daoavaliacaofinal->fechar();
}
if ($erro != NULL){ ?>
    <div class="erro">
        <img src="webroot/img/error.png" width="30" height="30"/>
        <?php print $erro; ?>
    </div>
  <?php    
      Flash::addFlash($erro); 
   //   Utils::redirect('avaliacao', 'registraEditaAvaliacao');
     } else { ?>
   
 <?php  
      Flash::addFlash("Operação realizada com sucesso!"); 
    //  Utils::redirect('avaliacao', 'registraEditaAvaliacao');

  } ?>

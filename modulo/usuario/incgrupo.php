<?php

/*
 * Inclui uma ou mais unidades/sub no grupo
 */

ini_set('display_errors', 'on');//habilita mensagens de erro
$sessao = $_SESSION["sessao"];
if (!$aplicacoes[3]) {
    echo "Esta unidade nao tem permissão para acessar o formulário de inclusão de usuários!";
}


$nomeunidade = $sessao->getNomeunidade(); /* nome da unidade */
$codUnidSession = $sessao->getCodunidade();  /* código da unidade na sessão */
$aplicacoes = $sessao->getAplicacoes(); /* aplicações carregadas */
$codusuario=$sessao->getCodusuario();

if (!$aplicacoes[47]) {
    exit();
}

$BASE_DIR = dirname(__FILE__) . '/';
require_once $BASE_DIR . '../../dao/grupoDAO.php';
$grupo = $_POST['cad-consulta'];//grupo

if (!empty($_POST['lista2']))
   $unidadesel = $_POST['lista2'];

if ($codUnidSession==100000){
	$unidade=$_GET('unidade');
	//buscar hierarquia e codunidade
}
else {
	$codunidade=$codUnidSession;
	$hierarquia = $sessao->getCodestruturado();

}

$dao = new GrupoDAO();

$passou=0;

/*Validação dos dados */
if($grupo==0){
	Error::addErro("Selecione um grupo.");
}else{//Dados estão válidos

	$rows = $dao->buscaGrupoUnidade($grupo,$hierarquia);
	
/*
 * inserir em gu-temporaria
 */
    $dao->delete_gu_temporaria($grupo,$codusuario);
    
	if (!empty($unidadesel)){
    	$dao->insere_gu_temporaria( $grupo,$unidadesel,$codusuario );
    	
	}

	echo "<script>alert('$codunidade');</script>";
/*
 * Execute sp
 */
   $dao->spvincular1($grupo,$hierarquia,$codusuario,$codunidade);

   $passou=1;
   if ($passou==1){
	   Flash::addFlash("Operação realizada com sucesso!");
   }else {
  	   Flash::addFlash("Nenhuma operação realizada!");
   }
}

Utils::redirect('usuario', 'grupunidade', array('grupo' => $grupo));
?>
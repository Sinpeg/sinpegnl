<?php
/*
 * Inclui uma ou mais unidades/sub no grupo
 */
ini_set('display_errors', 'on');//habilita mensagens de erro

if (!isset($_SESSION["sessao"])) {
    header("location:../../index.php");
    exit();
}
$sessao = $_SESSION["sessao"]; /* sessão */
$nomeunidade = $sessao->getNomeunidade(); /* nome da unidade */
$codUnidSession = $sessao->getCodunidade();  /* código da unidade na sessão */
$aplicacoes = $sessao->getAplicacoes(); /* aplicações carregadas */
if (!$aplicacoes[23] && !$aplicacoes[3]) {
    exit();
}
$BASE_DIR = dirname(__FILE__) . '/';
require_once $BASE_DIR . '../../dao/grupoDAO.php';
?>
<?php
/* Configura os parâmetros POST */
echo $_POST['cad-consulta'];
$grupo = $_POST['cad-consulta'];
$unidadesel = $_POST['lista2'];
//$_SESSION['codunidade'] = $_POST['lista2'];

$fim = count($unidadesel)-1;
$dao = new GrupoDAO();
?>
<?php
/*Validação dos dados */
if($grupo==0){
	Error::addErro("Selecione um grupo.");
//}else if(is_null($unidadesel)){
//	Error::addErro("Selecione uma ou mais unidades.");
}else{//Dados estão válidos
	$rows = $dao->buscaGrupoUnidade($grupo);
	foreach ($rows as $row) {
		$indice = 0;
		$encontrou=false;
		while($indice <= $fim){
			if ($unidadesel[$indice]==$row['Codunidade']){
				$unidadesel[$indice]=-1;//cada unidade encontrada dentro do grupo recebe -1
				$encontrou=true;
			}
			++$indice;
		}
		if (!$encontrou){
			$dao->deletaGrupoUnidade($row['Codigo']);
		}
	}
	$indice = 0;
	while($indice <= $fim){
		if ($unidadesel[$indice]!=-1){
			$dao->insere( $grupo,$unidadesel[$indice] );
		}
		++$indice;
	}
	if($indice == $fim) Flash::addFlash("Dados atualizados com sucesso!");
}
Utils::redirect('usuario', 'grupunidade', array('grupo' => $grupo));
?>
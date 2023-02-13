<?php

require '../../classes/unidade.php';
require '../../dao/PDOConnectionFactory.php';
require '../../classes/sessao.php';
require '../../modulo/mapaestrategico/classes/Mapa.php';
require '../../modulo/mapaestrategico/dao/MapaDAO.php';
require '../../modulo/documentopdi/classe/Documento.php';
require '../../modulo/mapaestrategico/classes/Objetivo.php';
require '../../modulo/documentopdi/classe/Perspectiva.php';
require '../../modulo/calendarioPdi/classes/Calendario.php';
$documento = new Documento();
$objetivo = new Objetivo();
$perspectiva = new Perspectiva();
$calendario = new Calendario();


session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
$codunidade = ($sessao->getCodunidsel())?$sessao->getCodunidsel():$sessao->getCodUnidade();

// var_dump($_POST);die;

/////////////////////////////////////////////////////

$documento->setCodigo(addslashes($_POST['codDocumento'])) ;
$perspectiva->setCodigo(addslashes($_POST['codPerspectiva']));
$objetivo->setCodigo(addslashes($_POST['codObjetivo']));
$propMapa = $codunidade;
$action = addslashes($_POST['action']);


if (isset($_POST['codmapa'])) {
	$codmapa_atualizar = $_POST['codmapa'];
}


// fim
$erro = "";


/* Validação */
if($perspectiva->getCodigo() == "" || $perspectiva->getCodigo()== 0) {
	$erro = "Selecione o campo 'perspectiva'";
} else if($documento->getCodigo() == "" || $documento->getCodigo() == 0){
	$erro = "Selecione o campo 'Documento'";
} else if ($objetivo->getCodigo() == "" || $objetivo->getCodigo() == 0) {
	$erro = "Selecione o campo 'Objetivo'";
} else {
	$objmapa = new Mapa();
	$objmapa->setDocumento($documento); 
	$objmapa->setPerspectiva($perspectiva); 
	$objmapa->setObjetivoPDI($objetivo); 
	$objmapa->setPropMapa($propMapa);
	
	$daomapa = new MapaDAO();
	
	if ($action == 'A') {
		$rows1 = $daomapa->buscamapa($codmapa_atualizar);
		$rows1 = $rows1->fetch();
		$rows2 = $daomapa->buscamapadocumento($documento->getCodigo());
		$rows2 = $rows2->fetch();
 		$rows3 = $daomapa->buscaMapaObjetivoPerspectivaPorDocumento($objetivo->getCodigo(), $perspectiva->getCodigo(), $documento->getCodigo());
		$row = $rows3->fetch();
		$objmapa->setCodigo($codmapa_atualizar);
		
		if ($row['Codigo'] != $codmapa_atualizar && $rows3->rowCount() != 0){
			$erro = "Este documento já possui os respectivos campos:  Perspectiva e Objetivo  cadastrados.";
		}else if($rows2['Codigo'] == $codmapa_atualizar && $rows3->rowCount() != 0 && $row['codDocumento'] != $rows1['codDocumento'] ){
			$erro = "Este documento já possui os respectivos campos: Perspectiva e Objetivos  cadastrados.";
		}else{
			$daomapa->altera($objmapa);
			$sucesso = "Dados atualizados com sucesso!";
		}
		
// 		if ($rows->rowCount() == 0) {
// 			$daomapa->altera($objmapa);
// 			$sucesso = "Dados atualizados com sucesso!";
// 		}
		// Caso contrário
// 		else {
// 			foreach ($rows as $row) {
// 				if ($row['Codigo'] == $codmapa_atualizar) {
// 					$daomapa->altera($objmapa);
// 					$sucesso = "Dados atualizados com sucesso!";
// 				}
// 				else {
// 					$erro = "A 'Ordem do Objetivo' já está cadastrada. Por favor, tente outro valor";
// 				}
// 			}
// 		}
	}
	
	else if ($action == 'I') {
		
		$rows2 = $daomapa->buscaMapaObjetivoPerspectivaPorDocumento($objetivo->getCodigo(), $perspectiva->getCodigo(), $documento->getCodigo());
 		if(!($rows2->rowCount() == 0)){
 			$erro = "Este documento já possui os respectivos campos: 'Objetivos' e 'Perspectivas' cadastrados.";
 		}else{ 

			$daomapa->insere($objmapa);
			$sucesso = "Dados cadastrados com sucesso!";
 		}
	}
			
}

?>


<?php if ($erro != ""): ?>
    <div class="erro">
        <img src="webroot/img/error.png" width="30" height="30"/>
    <?php print $erro; ?>
    </div>
    <?php else : ?>
    <div id="success">
        <img src="webroot/img/accepted.png" width="30" height="30"/><?php print $sucesso; ?>
    </div>
<?php endif; ?>


<?php
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/sessao.php';
require_once '../../classes/unidade.php';
require_once '../../dao/unidadeDAO.php';
require_once '../../modulo/documentopdi/dao/DocumentoDAO.php';
require_once '../../modulo/mapaestrategico/dao/MapaDAO.php';
require_once '../../modulo/indicadorpdi/dao/IndicadorDAO.php';
require_once '../../modulo/indicadorpdi/dao/MapaIndicadorDAO.php';
require_once '../../modulo/metapdi/dao/MetaDAO.php';
require_once '../../modulo/calendarioPdi/dao/CalendarioDAO.php';
/* Model */
require_once '../../modulo/documentopdi/classe/Documento.php';
require_once '../../modulo/mapaestrategico/classes/Mapa.php';
require_once '../../modulo/indicadorpdi/classe/Indicador.php';
require_once '../../modulo/indicadorpdi/classe/Mapaindicador.php';
require_once '../../modulo/metapdi/classe/Meta.php';
require_once '../../modulo/calendarioPdi/classes/Calendario.php';

require_once '../../util/Utils.php';
?>
<?php


session_start();
$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[38]) {
    print "O usuário não tem permissão para acessar este módulo";
    exit();
}



?>
<?php
$coddoc = $_POST['coddoc'];
$codmapaindicador=$_POST['codmapaind'];
//echo $coddoc."-".$codmapaindicador;die;
// $coleta = $_POST['coleta'.$i]; // tipo da coleta do indicador

$daometa = new MetaDAO();
$daocalendario = new CalendarioDAO();
$daodoc = new DocumentoDAO();
$rows2 = $daodoc->buscadocumento($coddoc);

$objdoc = new Documento();
foreach ($rows2 as $row2) {
	$objdoc->setCodigo($row2['codigo']);
	$objdoc->setAnoFinal($row2['anofinal']);
	$objdoc->setAnoInicial($row2['anoinicial']);
	
}

$erro="";
for($i=$objdoc->getAnoinicial(); $i<=$objdoc->getAnofinal();$i++){
			
			        $codigometa = $_POST['codigo'.$i]; // valor da meta
				    $objmeta=new Meta();
				    $objmeta->setCodigo($codigometa);
				    $arraymeta[$i] = $objmeta;
				    // Fim
			  
} 
if( count($arraymeta)>0 ){
	$daometa->deletaAll($arraymeta);
	$message = "Metas excluídas com sucesso!";
	print "<br><img src='webroot/img/accepted.png' width='30' height='30'/>".$message;
   // Flash::addFlash($string);
	//Utils::redirect('indicadorpdi', 'cadastrarindicador');
}
else{
	$message = "Falha ao excluir a meta";
	print "<br><img src='webroot/img/error.png' width='30' height='30'/>". $message;
}



?>
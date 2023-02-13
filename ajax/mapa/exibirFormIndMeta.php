<?php
require '../../classes/unidade.php';
require '../../dao/PDOConnectionFactory.php';
require '../../classes/sessao.php';
require '../../modulo/mapaestrategico/classes/Mapa.php';
require '../../modulo/mapaestrategico/dao/MapaDAO.php';
require '../../modulo/documentopdi/classe/Documento.php';
require '../../modulo/documentopdi/dao/DocumentoDAO.php';
require '../../modulo/indicadorpdi/dao/IndicadorDAO.php';



session_start();
$sessao = $_SESSION['sessao'];


$daoind=new IndicadorDAO();


?>
<table>

</table>
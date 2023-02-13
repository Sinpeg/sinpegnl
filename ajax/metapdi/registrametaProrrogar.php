<?php 
require_once '../../dao/PDOConnectionFactory.php';

require_once '../../modulo/metapdi/dao/MetaDAO.php';

$valor = str_replace(',', '.', $_POST['valor']);
$codMeta = $_POST['codmeta'];

$metadao = new MetaDAO();
$metadao->alteraMeta($codMeta, $valor);


?>
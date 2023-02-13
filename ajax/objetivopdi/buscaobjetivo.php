<?php
/* DAO */
require_once '../../classes/sessao.php';
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../modulo/documentopdi/dao/DocumentoDAO.php';
require_once '../../modulo/objetivopdi/dao/MapaDAO.php';
require_once '../../modulo/indicadorpdi/dao/IndicadorDAO.php';
///* Model */
require_once '../../modulo/documentopdi/classe/Documento.php';
require_once '../../modulo/objetivopdi/classe/Mapa.php';
require_once '../../modulo/indicadorpdi/classe/Indicador.php';
?>
<?php
session_start();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
$anobase = $sessao->getAnobase();
$coddoc= addslashes($_POST['documento']);
if (empty($coddoc)) { // alternativa
    $coddoc = $_POST["doc"];
} 
$codunidade = $sessao->getCodUnidade();
?>
<?php
$daodoc = new DocumentoDAO();
$rows = $daodoc->buscadocumento($coddoc);
$objdoc = new Documento();
foreach ($rows as $row) {
    if ($anobase >= $row['anoinicial'] && $anobase <= $row['anofinal']) {
        $objdoc->setCodigo($row['codigo']);
        $objdoc->setNome($row['nome']);
    }
}
$daomapa = new MapaDAO();
$objmapa = array();
$cont1 = 0;
$rows = $daomapa->buscamapadocumento($objdoc->getCodigo());
foreach ($rows as $row) {
    $objmapa[$cont1] = new Mapa();
    $objmapa[$cont1]->setCodigo($row['Codigo']);
    $objmapa[$cont1]->setObjetivo($row['Objetivo']);
    $objmapa[$cont1]->setDocumento($objdoc);
    $cont1++;
}
?>
<label class="curto" for="objetivo">Objetivo:</label>
<select name='objetivo' class="sel1">
    <option value="0">Selecione um objetivo...</option>
    <?php for ($i = 0; $i < $cont1; $i++): ?>
        <option value="<?php print $objmapa[$i]->getCodigo(); ?>"><?php print ($objmapa[$i]->getObjetivo()); ?></option>
    <?php endfor; ?>
</select>
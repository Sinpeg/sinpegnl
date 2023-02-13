<?php
/* DAO */
//require_once '../../../includes/classes/sessao.php';
//require_once '../../../includes/dao/PDOConnectionFactory.php';
//require_once '../dao/DocumentoDAO.php';
//require_once '../dao/MapaDAO.php';
//require_once '../dao/IndicadorDAO.php';
///* Model */
//require_once '../model/Documento.php';
//require_once '../model/Mapa.php';
//require_once '../model/Indicador.php';
/* DAO */
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/sessao.php';
require_once '../../classes/unidade.php';
require_once '../../dao/unidadeDAO.php';
require_once '../../modulo/documentopdi/dao/DocumentoDAO.php';
require_once '../../modulo/objetivopdi/dao/MapaDAO.php';
require_once '../../modulo/indicadorpdi/dao/IndicadorDAO.php';
//require_once '../../modulo/metapdi/dao/MetaDAO.php';
/* Model */
require_once '../../modulo/documentopdi/classe/Documento.php';
require_once '../../modulo/objetivopdi/classe/Mapa.php';
require_once '../../modulo/indicadorpdi/classe/Indicador.php';
//require_once '../../modulo/metapdi/classe/Meta.php';
?>
<?php
//print "teste";
//exit;
session_start();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
$anobase = $sessao->getAnobase();
$coddocumento = $_POST['documento']; // código do documento
$codobjetivo = $_POST['objetivo']; // código do objetivo
$codunidade = $sessao->getCodUnidade();
?>
<?php
$daodoc = new DocumentoDAO();
$rows = $daodoc->buscadocumento($coddocumento);
$objdoc = new Documento();
foreach ($rows as $row) {
    if ($anobase >= $row['anoinicial'] && $anobase <= $row['anofinal']) {
        $objdoc->setCodigo($row['codigo']);
        $objdoc->setNome($row['nome']);
    }
}
$daomapa = new MapaDAO();
$rows = $daomapa->lista();
$objmapa = array();
$cont = 0;
foreach ($rows as $row) {
    if ($row['CodigoDocumento'] == $objdoc->getCodigo()) {
        $objmapa[$cont] = new Mapa();
        $objmapa[$cont]->setDocumento($objdoc);
        $objmapa[$cont]->setCodigo($row['Codigo']);
        $objmapa[$cont]->setOrdem($row['Ordem']);
        $objmapa[$cont]->setObjetivo($row['Objetivo']);
        $cont++;
    }
}
$objind = array();
$cont1 = 0;
for ($i = 0; $i < $cont; $i++) {
    $daoind = new IndicadorDAO();
    $rows = $daoind->buscaindicadorpormapa2($objmapa[$i]->getCodigo());
    foreach ($rows as $row) {
        if ($row['CodMapa'] == $codobjetivo) {
            $objind[$cont1] = new Indicador();
            $objind[$cont1]->setMapa($objmapa[$i]);
            $objind[$cont1]->setCodigo($row['Codigo']);
            $objind[$cont1]->setIndicador($row['indicador']);
            $cont1++;
        }
    }
}
?>
<label>Indicador:</label>
<select name='indicador' class="sel1">
    <option value="0">Selecione um indicador...</option>
    <?php for ($i = 0; $i < $cont1; $i++): ?>
    <option value="<?php echo ($objind[$i]->getCodigo()); ?>"><?php echo $objind[$i]->getIndicador(); ?></option>
    <?php endfor; ?>
</select>
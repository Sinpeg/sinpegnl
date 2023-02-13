<?php
require '../../dao/PDOConnectionFactory.php';
require '../../classes/sessao.php';
require '../../util/Utils.php';
/* models */
define('MODULO_DIR', (dirname(__FILE__)).'/../../modulo/');
require_once MODULO_DIR . 'documentopdi/classe/Documento.php';
require_once MODULO_DIR . 'objetivopdi/classe/Mapa.php';
require_once MODULO_DIR . 'indicadorpdi/classe/Indicador.php';
require_once MODULO_DIR . 'metapdi/classe/Meta.php';
require_once MODULO_DIR . 'calendarioPdi/classes/Calendario.php';
require_once MODULO_DIR . 'resultadopdi/classes/Resultado.php';
/* DAO */
require_once MODULO_DIR . 'documentopdi/dao/DocumentoDAO.php';
require_once MODULO_DIR . 'objetivopdi/dao/MapaDAO.php';
require_once MODULO_DIR . 'indicadorpdi/dao/IndicadorDAO.php';
require_once MODULO_DIR . 'metapdi/dao/MetaDAO.php';
require_once MODULO_DIR . 'resultadopdi/dao/ResultadoDAO.php';
require_once MODULO_DIR . 'calendarioPdi/dao/CalendarioDAO.php';

/* fim */




?>
<?php
sleep(1);
session_start();
$sessao = $_SESSION["sessao"];
require_once '../../classes/unidade.php';

$unidade = new Unidade();
$unidade->setCodunidade($sessao->getCodunidade());
$unidade->setNomeunidade($sessao->getNomeunidade());

//$aplicacoes = $sessao->getAplicacoes();
$anobase = $sessao->getAnobase();   // ano base
$codunidade = $sessao->getCodUnidade(); // código da unidade
$coddocumento = $_POST["doc"];
$daodoc = new DocumentoDAO();
$rows = $daodoc->buscadocumento($coddocumento);
$cont=0;
foreach ($rows as $row) {
  ////  if ($anobase >= $row['anoinicial'] 
          //  && $anobase <= $row['anofinal']
            //&& $row['situacao'] = 'A'
     //  ) {
        $cont++;
        $uni=new Unidade();
        $uni->setCodunidade($row['CodUnidade']);                                
        $uni->criaDocumento($row['codigo'],$row['nome'],$row['anoinicial'],$row['anofinal'],$row['situacao'],
                            $row['missao'],$row['visao'],null,$row['tipo']);
        $objdoc=$uni->getDocumento();                        
   // }
}

$daocal = new CalendarioDAO();
$rows = $daocal->listaCalendarioPorDoc($uni->getDocumento()->getCodigo());
$objetocal = array();
$cont1 = 0;
foreach ($rows as $row) {
    for ($i = 0; $i < $cont; $i++) {
                $cont1++;
                $objdoc->criaCalendario($row['codCalendario'],$uni->getCodunidade(),null,null,$row['anoGestao']);
                $objetocal[$cont1]=$objdoc->getCalendario();            
        
    }
}
?>

<label for="calendario">Selecione o calendário: </label>
<select name="calendario"  id="pdi-result-bind">
    <option value="0">--Selecione o calendário--</option>
<?php for ($i = 1; $i < $cont1; $i++) { ?>
    <option value="<?php echo $objetocal[$i]->getCodigo(); ?>"><?php echo ($objetocal[$i]->getAnogestao()); ?></option>
<?php } ?>
</select>


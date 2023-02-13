<?php
require '../../dao/PDOConnectionFactory.php';
require '../../classes/sessao.php';
require '../../classes/unidade.php';

require '../../dao/unidadeDAO.php';

require '../../util/Utils.php';
/* models */
define('MODULO_DIR', (dirname(__FILE__)).'/../../modulo/');
require_once MODULO_DIR . 'documentopdi/classe/Documento.php';
require_once MODULO_DIR . 'mapaestrategico/classes/Mapa.php';
require_once MODULO_DIR . 'indicadorpdi/classe/Indicador.php';
require_once MODULO_DIR . 'metapdi/classe/Meta.php';
require_once MODULO_DIR . 'resultadopdi/classes/Resultado.php';
/* DAO */
require_once MODULO_DIR . 'documentopdi/dao/DocumentoDAO.php';
require_once MODULO_DIR . 'mapaestrategico/dao/MapaDAO.php';
require_once MODULO_DIR . 'indicadorpdi/dao/IndicadorDAO.php';
require_once MODULO_DIR . 'metapdi/dao/MetaDAO.php';
require_once MODULO_DIR . 'resultadopdi/dao/ResultadoDAO.php';
/* fim */
?>
<?php
sleep(1);
session_start();
$sessao = $_SESSION["sessao"];
//$aplicacoes = $sessao->getAplicacoes();
$anobase = $sessao->getAnobase();   // ano base é usado como ano de gestão
$coddocumento = $_POST["doc"];
$codobj = $_POST["obj"];
$unidade = new Unidade();
if (!empty($sessao ->getCodunidsel())) {
        $unidade->setCodunidade($sessao ->getCodunidsel());
        $unidade->setNomeunidade($sessao ->getNomeunidsel());
}else {
        $unidade->setCodunidade($sessao->getCodUnidade());
        $unidade->setNomeunidade($sessao->getNomeunidade());
}


$daodoc= new DocumentoDAO();
//echo $anobase.",".$coddocumento.",".$unidade->getCodunidade()." ".$codobj;
$rows = $daodoc->listaIndporDocCalObj($anobase,$coddocumento,$unidade->getCodunidade(),$codobj);//lista indicadores da unidade por doc e calendario
$objetoind = array();
$cont1 = 0;
foreach ($rows as $row) {
  //  for ($i = 0; $i < $cont; $i++) {
      //  if ($objmapa[$i]->getCodigo() == $row['CodMapa']) {
        //    if ($row['PropIndicador'] == $codunidade) {
                $cont1++;
                $indi = new Indicador();
                $indi->setCodigo($row['Codigo']);
                $indi->setNome($row['nome']);          
                $objetoind[$cont1]=$indi;
    
          //  }
    //  }
  //  }
}
if ($coddocumento!=0){
    if (count($objetoind)==0){?>
        <p style='color:red'>Não há indicadores de responsabilidade desta unidade gerencial!</p>
    <?php }
    else { 
    ?>
    <div>
    <label for="indicador" class="curto">Indicador </label>
    <select name="indicador">
        <option value="0">--Selecione o indicador--</option>
    <?php for ($i = 1; $i <= $cont1; $i++) { ?>
        <option value="<?php echo $objetoind[$i]->getCodigo(); ?>"><?php echo $objetoind[$i]->getNome(); ?></option>
    <?php } ?>
    </select>
    </div>
    <div>
        <input type="submit" value="Buscar" id="pdi-result-br" class="btn btn-info" />
    </div>
    <?php }
} 
?>
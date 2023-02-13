<?php
/* DAO */
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/sessao.php';
require_once '../../classes/unidade.php';
require_once '../../dao/unidadeDAO.php';
require_once '../../modulo/documentopdi/dao/DocumentoDAO.php';
require_once '../../modulo/mapaestrategico/dao/MapaDAO.php';
require_once '../../modulo/indicadorpdi/dao/IndicadorDAO.php';
require_once '../../modulo/indicadorpdi/dao/MapaIndicadorDAO.php';
require_once '../../modulo/resultadopdi/dao/ResultadoDAO.php';
require_once '../../modulo/metapdi/dao/MetaDAO.php';
/* Model */
require_once '../../modulo/documentopdi/classe/Documento.php';
require_once '../../modulo/mapaestrategico/classes/Mapa.php';
require_once '../../modulo/indicadorpdi/classe/Indicador.php';
require_once '../../modulo/metapdi/classe/Meta.php';
require_once '../../util/Utils.php';

session_start();
$sessao = $_SESSION['sessao'];
$codmapa = $_SESSION['codmapa'];
$anobase=$sessao->getAnobase();
$codestruturado = $sessao->getCodestruturado();
$codunidade = $sessao->getCodunidade();
$aplicacoes = $sessao->getAplicacoes(); // código das aplicações

if (!$aplicacoes[38]) {
    print "Erro ao acessar esta aplicação";
    exit();
}

//$coddoc = addslashes($_POST['doc']);
//$objcod = addslashes($_POST['objetivo']);
//$propindicador = addslashes($_POST['propindicador']);

$indicador = isset($_POST['indicador']) ? addslashes($_POST['indicador']) : NULL;
$objeto = isset($_POST['objeto']) ? addslashes($_POST['objeto']) : NULL;
$calculo = isset($_POST['calculo']) ? strip_tags(addslashes($_POST['calculo'])) : NULL;
$unidademedida = isset($_POST['unidadeMedida']) ? addslashes($_POST['unidadeMedida']) : NULL;
$interpretacao = isset($_POST['interpretacao']) ? addslashes($_POST['interpretacao']) : NULL;
$metodo = isset($_POST['metodo']) ? addslashes($_POST['metodo']) : NULL;
$benchmarch = isset($_POST['benchmarch']) ? addslashes($_POST['benchmarch']) : NULL;
$observacoes = isset($_POST['observacoes']) ? addslashes($_POST['observacoes']) : NULL;
$codDocumento = isset($_POST['codigo']) ? addslashes($_POST['codigo']) : NULL;
$acao = isset($_POST['acao']) ? addslashes($_POST['acao']) : NULL;
$propindicador = isset($_POST['cxunudade']) ? addslashes($_POST['cxunidade']) : NULL;
$codDocumento = NULL;
$anoinicialdoc = NULL;
$objcod = NULL;

$daoResul=new ResultadoDAO();
$rowsresant=$daoResul->verResultadosAnosAnteriores($codDocumento, $codunidade, $anoinicialdoc, $anobase);
$contlinhasant= $rowsresant->rowCount();

$rowsres=$daoResul->verResultadosAnoPeriodoFinal($codDocumento,$codunidade,$anobase);
$contlinhas= $rowsres->rowCount();

$erro = "";

if ($indicador == "") {
    $erro = "Preencha o nome do indicador!";
}else if ($calculo == "") {
    $erro = "Preencha o campo para a fórmula de cálculo";
} else if ($interpretacao == "") {
    $erro = "Preencha o campo Interpretação";
} else if ($unidademedida == "") {
    $erro = "Selecione o campo Unidade de Medida";
}else if ($codunidade==938){ 
       if ($objeto == "") {
         $erro = "Preencha o campo objeto";
       } else if ($metodo == "") {
             $erro = "Preencha o campo Fonte/Método";
       } else if ($benchmarch == "") {
             $erro = "Preencha o campo Benchmarch";
       } 
}


if ($erro==""){
     $daounidade = new UnidadeDAO();
	
    $codunidade=$sessao->getCodUnidade();
    $rows = $daounidade->buscasubunidades00($codestruturado);
    $unidade = array();
    $cont = 0;
    foreach ($rows as $row) {
        $unidade[$cont] = new Unidade();
        $unidade[$cont]->setCodunidade($row['CodUnidade']);
        $unidade[$cont]->setNomeunidade($row['NomeUnidade']);
        $cont++;
    }
    $daodoc = new DocumentoDAO();
    $objdoc = array();
    $cont1 = 0;
    for ($i = 0; $i < $cont; $i++) {
        $daodoc = new DocumentoDAO();
        $rows = $daodoc->buscadocumentoporunidade($unidade[$i]->getCodunidade(), $anobase);
        foreach ($rows as $row) {
            $objdoc[$cont1] = new Documento();
            $objdoc[$cont1]->setCodigo($row['codigo']);
            $objdoc[$cont1]->setNome($row['nome']);
            $objdoc[$cont1]->setUnidade($unidade[$i]);
            $objdoc[$cont1]->setAnoFinal($row['anofinal']);
            $cont1++;
        }
    }
    $daomapa = new MapaDAO();
    $objmapa = new Mapa();                  
    
    for ($i = 0; $i < $cont1; $i++) {
        $rows = $daomapa->buscamapadocumento($objdoc[$i]->getCodigo(),$anobase);
        foreach ($rows as $row) {
            if ($row['Codigo'] == $objcod) {
                $objmapa = new Mapa();
                $objmapa->setCodigo($row['Codigo']);
                $objmapa->setObjetivo($row['codObjetivoPDI']);
                $objmapa->setDocumento($objdoc[$i]);
            }
        }
    }
    

        $validade = "2025-12-31";
        $objind = new Indicador();
        $objind->setNome($indicador);
        $objind->setObjeto($objeto);
        $objind->setCalculo($calculo);
        $objind->setValidade($validade);
        $objind->setAnoinicio($anobase);
        $objind->setUnidademedida($unidademedida);
        $objind->setInterpretacao($interpretacao);
        $objind->setMetodo($metodo);
        $objind->setEixo(16);//16 - Eixo PDU       
        $objind->setBenchmarch($benchmarch);
        $observacoes!=""?$objind->setObservacoes($observacoes):""; 
        if ($codunidade!=938){
        	 $objind->setCesta('4');
        }else{
        	 $objind->setCesta($_POST['cesta']);
        }
        
        $u=new Unidade();
        $u->setCodunidade($codunidade);
        $objind->setUnidade($u);
        
        $daoind = new IndicadorDAO(); 
        
        if ($acao == "A") {
        	if ($daoind->insere($objind)>0){
        	    $string = '<div class="alert alert-success" role="alert">Dados gravados com sucesso!</div>';
        	}
            else $string="Erro na operação de gravação";
       }else if ($acao == "E") {
        	$codind=$_POST['codigo'];
            $rows = $daoind->buscaindicador($codind);
            
            if ($rows->rowCount() == 0) {
            	
                $erro = "Indicador não foi encontrado";
            } else {
                $today = new DateTime('now');
                
                foreach ($rows as $row) {
                    $objind->setValidade($row['validade']);
                }
                $validade = new DateTime($objind->getValidade());
                if ($today > $validade) {
                    $erro = "Você não pode mais atualizar este indicador";
                } else {
                    $objind->setCodigo($codind);
                    $daoind->altera($objind);
                    $string = "Dados atualizados com sucesso!";
                }
            }
        }
        $daoind->fechar();
    }
?>
<?php if ($erro != ""){ ?>
    <div class="erro">
        <img src="webroot/img/error.png" width="30" height="30"/>
        <?php print $erro; ?>
      <!--   <span class="plus"></span><a href="<?php // echo Utils::createLink('indicadorpdi', 'consultaindicador'); ?>">Voltar</a>-->
    </div>
<?php } else { ?>
    <div id="success">
        <img src="webroot/img/accepted.png" width="30" height="30"/>
        <?php print $string;
        if ($contlinhas == 0 && $contlinhasant == 0) { //exibe botão de adição se nao houver resultado no documento para o ano no periodo final
        } else { ?>
       
          <span class="plus"></span><a href="<?php echo $codunidade == 938 ? Utils::createLink('indicadorpdi', 'consultaindicadorproprio938') : Utils::createLink('indicadorpdi', 'consultaindicadorproprio'); ?>">Consultar indicador</a>
          <?php } ?>     
    </div>
<?php } ?>
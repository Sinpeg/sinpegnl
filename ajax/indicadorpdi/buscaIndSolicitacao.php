<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/unidade.php';
require_once '../../classes/Controlador.php';
require_once '../../dao/unidadeDAO.php';
require_once '../../modulo/documentopdi/dao/DocumentoDAO.php';
require_once '../../modulo/mapaestrategico/dao/MapaDAO.php';
require_once '../../modulo/indicadorpdi/dao/IndicadorDAO.php';
require_once '../../modulo/mapaestrategico/dao/ObjetivoDAO.php';
require_once '../../modulo/mapaestrategico/classes/Objetivo.php';
require_once '../../modulo/documentopdi/classe/Documento.php';
require_once '../../modulo/mapaestrategico/classes/Mapa.php';
require_once '../../modulo/indicadorpdi/classe/Indicador.php';

require_once '../../classes/sessao.php';
session_start();
$sessao = $_SESSION["sessao"];
if (!isset($sessao)) {
 echo "Sessão expirou...";
 exit(0);
} else {
 $aplicacoes = $sessao->getAplicacoes();
 if (!$aplicacoes[55]) { 
 echo "Você não tem permissão para acessar esta operação!";
 exit(0);
 }
}

$codestruturado = $_POST['codestruturado'];
$codUnidade = $_POST['codUnidade'];
//$aplicacoes = $_POST['aplicacoes']; // código das aplicações
$codmapa = $_POST['codmapa'];
$coddoc= $_POST['coddoc'];
//$grupo= $_POST['grupo'];
$anobase= $_POST['anobase'];

$daodoc = new DocumentoDAO();

$rowsdoc = $daodoc->buscadocumentoporunidadePeriodoSemPDI($codUnidade, $anobase);


$daoobjetivo=new ObjetivoDAO();
 $rowsobjetivo = $daoobjetivo->buscaObjetivoPorMapa($codmapa);
		$objobjetivo = new Objetivo();
		foreach ($rowsobjetivo as $rowobjetivo)
		{
			$objobjetivo->setCodigo($rowobjetivo['codobj']);
			$objobjetivo->setObjetivo($rowobjetivo['des']);
		}

$daoind = new IndicadorDAO();
$objind = array();
$cont3 = 0;
	$rows = $daoind->listaIndicadorNaoVinculado1($anobase,$coddoc,$codUnidade);
	foreach ($rows as $row) {
		$objind[$cont3] = new Indicador();
		$objind[$cont3]->setCodigo($row['Codigo']);		
		$objind[$cont3]->setNome($row['nome']);
		$objind[$cont3]->setValidade($row['validade']);
		$objind[$cont3]->setCesta($row['cesta']);
		
		$cont3++;
}

?>
<span>Selecione o indicador: </span>
 <table id="tabelaInd" class="table table-bordered table-hover">
		
			<tfoot>
		        <tr>
		            <th colspan="7" class="ts-pager form-horizontal">
		                <button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
		                <button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
		                <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
		                <button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
		                <button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
		                <select class="pagesize input-mini" title="Select page size">
		                    <option selected="selected" value="10">10</option>
		                    <option value="20">20</option>
		                    <option value="30">30</option>
		                    <option value="40">40</option>
		                </select>
		                <select class="pagenum input-mini" title="Select page number"></select>
		            </th>
		        </tr>
		    </tfoot>
		
			<thead>
				<tr>
					<th>Selecionar</th>
					<th>Indicador</th>
					<th>Cesta </th>					
				</tr>
			</thead>	
			<tbody>			
			<?php for ($i = 0; $i < $cont3; $i++) { ?>
                <tr>
                	<td><input type="radio" name="subsInd" id="subsInd" value="<?php print ($objind[$i]->getCodigo());  ?>">
              
                    <td><?php print ($objind[$i]->getNome());  ?></td>
                    <td> <?php 
                    switch ($objind[$i]->getCesta()){
                    	case 0: print 'PDI 2011';
                    	        break;
                    	case 1: print 'PDI';
                    	        break;
                    	case 2: print 'Essencial';
                    	        break;
                    	case 3: print 'Opcional';
                    	        break;
                    	case 4: print 'PDU';
                    	        break;
                    }                   
                   ?></td>             
                </tr>
            <?php } ?>				
			</tbody>
		</table>

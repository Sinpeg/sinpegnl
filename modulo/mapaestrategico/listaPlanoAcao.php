<?php 
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

$sessao = $_SESSION['sessao'];

$anobase=$sessao->getAnobase();
$codUnidade = $sessao->getCodunidade();
$aplicacoes = $sessao->getAplicacoes(); // código das aplicações

$daoMapa=new MapaDAO();
$rowsPlano = $daoMapa->buscaPlanoAcao($anobase);

?>

 <legend>Plano de Ação</legend>

 <table  class="table table-bordered table-hover" >
		
			<tfoot>
		        <tr>
		            <th colspan="7" class="ts-pager form-horizontal">
		                <button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
		                <button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
		                <span class="pagedisplay"></span> <!-- this can be any element, including an input class="form-control"-->
		                <button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
		                <button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
		                <select class="custom-select" title="Select page size">
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
					<th>Unidade</th>					
					<th>Comentário</th>
					<th>Ano</th>
					<th>Cadastro</th>
					<th>Download</th>				    		
				</tr>
			</thead>		
			<tbody>	
				<?php
			     if ($rowsPlano->rowCount() > 0) {
			         foreach ($rowsPlano as $rowP){
			             echo '<tr>
                                <td>'.$rowP['NomeUnidade'].'</td>
                                <td>'.$rowP['comentario'].'</td>
                                <td>'.$rowP['anobase'].'</td>
                                <td>'.date("d/m/Y h:i:s a",strtotime($rowP['cadastro'])).'</td>
                                <td><a href="../public/plano_de_acao/'.$rowP['arquivo'].'"><img src="webroot/img/download-2.png" alt="Download" width="25" height="25"></a></td>
                               </tr>';
			         }
			     }
				?>
			</tbody>
		</table>

  
   
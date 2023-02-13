<?php
ob_start();

if (!$aplicacoes[47]) {
    header("Location:../../index.php");
    exit;
}
if (!isset($_SESSION["sessao"])) {
    header("Location:../../index.php");
}

require_once('classes/sessao.php');
require_once('dao/PDOConnectionFactory.php');
require_once('classes/validacao.php');
require_once('modulo/biblio/dao/bibliocensoDAO.php');

$daotie = new BibliocensoDAO;

$row = $daotie->buscaBiblioUni($hierarquia, $anobase);

if ($row->rowCount() == 0) {	
?>	
	<div class="alert alert-success">
        <button type="button " class="close" data-dismiss="alert">&times;</button>
         <strong><?php print "A(s) biblioteca(s) da sua unidade ainda não registrou(aram) os dados para o Censo de Educação Superior. Fique atento ao prazo!"; ?></strong>
    </div>
<?php 
} else { ?>
    <br/>  
    <table id="tablesorter" class="table table-bordered table-hover"> 
        <thead> 
            <tr> 
                <th>Unidade</th> 
                <th>Biblioteca</th> 
                <th>Assentos</th> 
                <th>Empréstimos domiciliares</th> 
                <th>Empréstimos entre Bibliotecas</th>
                <th>Frequência</th>
                <th>Ano</th>
                <th>Visualizar</th>
            </tr> 
        </thead> 
        <tfoot> 
            <tr> 
                <th colspan="8" class="ts-pager form-horizontal"> 
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
        <tbody> 
            <?php foreach ($row as $r) { ?> 
                <tr>
                    <td><?php echo $r['idUnidade']; ?></td>
                    <td><?php echo $r['Biblioteca']; ?></td>
                    <td><?php echo ($r['nAssentos']); ?></td> 
                    <td><?php echo ($r['nEmpDomicilio']); ?></td> 
                    <td><?php echo $r['nEmpBiblio']; ?></td>
                    <td><?php echo $r['frequencia']; ?></td> 
                    <td><?php echo $r['ano']; ?></td>
                    <td><a href="<?php echo Utils::createLink('biblio', 'altbiblicensoSub', array('biblioteca' => $r['idUnidade']));?>"><img src="webroot/img/busca.png" alt="Visualizar Detalhes"></a></td>  
                </tr>
            <?php } ?> 
        </tbody> 
    </table> 
<?php } ?>

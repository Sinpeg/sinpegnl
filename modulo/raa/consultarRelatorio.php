<?php
    require_once('dao/textoDAO.php');
    
    $daot = new TextoDAO();            
    $trows = $daot->listaRelatoriosRAA($anobase);
    $cont = 0;
	$arrayt = array();
    
    foreach ($trows as $row){
    	$arrayt[$cont] = array("codunidade" => $row['codunidade'],"nomeunidade" => $row['nomeunidade'],"anobase" => $row['anobase'],"data" => $row['dataF']); 
    	$cont++;
    }
	   
?>
<head>
<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active">Consultar RAA</li>
		</ul>
	</div>
</head>
<h3 class="card-title">Relatórios de Atividades</h3><br/>
<?php if ($cont > 0) { ?>    
    
    <table id="tablesorter" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Unidade </th>
                <th>Ano</th>
                <th>Data da Finalização</th>                
                <th>RAA</th>
            </tr>
        </thead>
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
        <tbody>
            <tr>
    <?php for ($i = 0; $i < count($arrayt); $i++) { ?>
                    <td><?php print ($arrayt[$i]["nomeunidade"]);?></td>
                    <td><?php print ($arrayt[$i]["anobase"]); ?></td>
                    <td><?php print (date("d/m/Y H:i",strtotime($arrayt[$i]["data"]))); ?></td>
                    <td ><a href="modulo/raa/<?php echo $anobase>2021?'relatorioRaaPDF2W.php':'relatorioRaaPDF2.php';?>?codunidade=<?php print ($arrayt[$i]["codunidade"]);?>&ano=<?php print ($arrayt[$i]["anobase"]); ?>"><img alt="Exportar Relatório" title="Exportar Relatório" src="webroot/img/<?php echo $anobase>2021?'word.png':'pdf.png';?>" /></a></td>
                </tr>
     <?php } ?>
        </tbody>
    </table><br/>
    <a href="?modulo=uparquivo&acao=relarqsadmin"><button class="btn btn-info">Consultar Anexos</button></a>&nbsp;   
    <a href="relatorio/acompanhamentoRAA.php?anoBase=<?php echo $anobase;?>"><button class="btn btn-info">Relatório de Finalização</button></a> 
    <?php
}else{
	echo "Nenhum Relatório foi Finalizado!";
} ?> 

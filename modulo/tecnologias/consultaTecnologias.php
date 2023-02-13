<?php
if (!$aplicacoes[6]) {
    header("Location:index.php");
} else {
    require_once('dao/tecnologiasDAO.php');
    require_once('classes/tecnologias.php');
    
    $bandaL=0;
    $salaC=0;
    $videoc=0;
    $salasA=0;
    $micro=0;
    
    
    $daot = new tecnologiasDAO();
    $lock = new Lock();
    $codigo = null;
    
    if (!$sessao->isUnidade()) {
        // verifica se possui homologação
        $lock->setLocked(Utils::isApproved(6, $cpga, $codunidade, $anobase));
                 
    }
    // Procura em todos os códigos de unidades e subunidades
    for ($i = 0; $i < count($array_codunidade); $i++) {
        // busca os micros associados a unidade e ano base
        $rows = $daot->buscatecnologiasunidade($array_codunidade[$i], $anobase);
        // Se é CPGA, o código de busca é de outra subunidade
        // e esta subunidade possui dados $rowCount()>0
        // Bloqueia edição
        if ($sessao->isUnidade() && $array_codunidade[$i] != $codunidade && $rows->rowCount() > 0) {
            $lock->setLocked(true);
        }
        foreach ($rows as $row) {
            $bandaL+=$row['bandaL'];            
            
            // fim
            $codigo = $row["codigo"]; // código micros registrado
            $cont++;
        }
        // configuração dos dados
        $micros->setAcad($qtdeAcad);
        $micros->setAcadi($qtdeAcadInt);
        $micros->setAdm($qtdeAdm);
        $micros->setAdmi($qtdeAdmInt);
        $micros->setCodigo($codigo);
        // fim
    }
    if ($cont==0) {
        Utils::redirect('micros', 'incluimicros');
    }
}
?>
<script type="text/javascript">
    function send(action)
    {
        switch (action) {
            case 'alterar':
                url = "<?php echo Utils::createLink('micros', 'alteramicros'); ?>";
                break;
            case 'incluir':
                url = 'incluimicros';
                break;
        }
        document.forms[0].action = url;
        document.forms[0].submit();
    }

</script>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li><a href="<?php echo Utils::createLink("micros", "incluimicros"); ?>" >Computadores</a></li>
		    <li class="active">Consulta</li>
		</ul>
	</div>
</head>
<form class="form-horizontal" name="gravar" method="post">
    <h3 class="card-title"> Computadores com/sem acesso &agrave; Internet</h3>
    <table id="tablesorter" class="table table-bordered table-hover">
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
            <tr align="center" style="font-weight: bold;">
                <th></th>
                <th>Qtde. com Internet</th>
                <th>Qtde. sem Internet</th>
                <th> Totais </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Uso Acad&ecirc;mico</td>
                <td align="center"><?php echo $micros->getAcadi(); ?></td>
                <td align="center"><?php echo $micros->getAcad(); ?></td>
                <td align="center"><?php echo $somaacad; ?></td>
            </tr>
            <tr>
                <td>Uso Administrativo</td>
                <td align="center"><?php echo $micros->getAdmi(); ?></td>
                <td align="center"><?php echo $micros->getAdm(); ?></td>
                <td align="center"><?php echo $somaadm; ?></b></td>

            </tr>
        </tbody>
        <tfoot>
            <tr align="center"><td>Total Geral</td><td><?php echo $somaint; ?></td><td><?php echo $somasemint; ?></td><td><?php echo $somaacad + $somaadm; ?></td></tr>
        </tfoot>
    </table>
    <ul class="excel">
    	<li><a href="relatorio/micros/relatorioForm.php">Planilha (versão completa)</a></li>
   	</ul>
<?php if (!$lock->getLocked()): ?>
        <input class="form-control"name="codigo" type="hidden" value="<?php echo $micros->getCodigo(); ?>" />
        <input type="button" class="btn btn-info" value="Alterar" onclick="send('alterar');" />
<?php endif; ?>
</form>